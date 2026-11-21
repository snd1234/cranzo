<?php

namespace Tests\Feature;

use App\Mail\OrderNoticeMail;
use App\Models\User;
use App\Services\Payments\PaymentException;
use App\Services\Payments\RazorpayClient;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RazorpayPaymentTest extends TestCase
{
    private FakeRazorpay $gateway;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withCredentials();
        $this->disableCookieEncryption();
        $this->makeTables();
        $this->gateway = new FakeRazorpay;
        $this->app->instance(RazorpayClient::class, $this->gateway);
        config([
            'razorpay.key_id' => 'rzp_test_key',
            'razorpay.key_secret' => 'test-secret',
            'razorpay.webhook_secret' => 'test-webhook',
            'mail.order_notify' => 'orders@cranzo.test',
            'mail.from.address' => 'support@cranzo.test',
            'mail.from.name' => 'Cranzo',
        ]);
        DB::table('state')->insert(['id' => 1, 'state_name' => 'DELHI', 'status' => 1]);
        DB::table('product')->insert([
            'id' => 7,
            'product_title' => 'Bike Cover',
            'status' => 1,
            'discounted_price' => 299,
            'created_at' => now(),
        ]);
        DB::table('product_details')->insert([
            'product_details_id' => 1,
            'product_id' => 7,
            'product_quantity' => 10,
            'color_id' => null,
        ]);
    }

    public function test_cash_on_delivery_stays_pending_and_deducts_stock_once(): void
    {
        $this->placeCod()->assertRedirect();

        $order = DB::table('orders')->first();
        $this->assertSame(2, (int) $order->booking_type);
        $this->assertSame(1, (int) $order->payment_status);
        $this->assertSame(9, (int) DB::table('product_details')->value('product_quantity'));
        $this->assertSame(1, DB::table('stock_movements')->where('reason', 'order')->count());
        $this->assertSame(0, DB::table('payment_transactions')->count());
        $this->assertSame('0', DB::table('payments')->value('amount_recieved'));
    }

    public function test_online_checkout_does_not_deduct_stock_until_payment_is_captured(): void
    {
        $started = $this->startOnline()->assertOk()->json();
        $this->assertSame(10, (int) DB::table('product_details')->value('product_quantity'));
        $this->assertSame(1, (int) DB::table('orders')->value('payment_status'));
        $this->assertSame(1, (int) DB::table('orders')->value('booking_type'));
        $this->assertSame('created', DB::table('payment_transactions')->value('status'));

        $this->gateway->payments['pay_1'] = $this->payment('pay_1', $started['razorpay_order_id'], 'authorized');
        $this->verify($started)->assertOk();

        $this->assertSame(2, (int) DB::table('orders')->value('payment_status'));
        $this->assertSame(9, (int) DB::table('product_details')->value('product_quantity'));
        $this->assertSame('captured', DB::table('payment_transactions')->value('status'));

        $this->verify($started)->assertOk();
        $this->assertSame(9, (int) DB::table('product_details')->value('product_quantity'));
    }

    public function test_invalid_signature_and_amount_mismatch_do_not_mark_the_order_paid(): void
    {
        $started = $this->startOnline()->json();
        $this->gateway->rejectSignature = true;
        $this->gateway->payments['pay_bad'] = $this->payment('pay_bad', $started['razorpay_order_id'], 'captured');
        $this->verify($started, 'pay_bad', 'invalid')->assertStatus(422);
        $this->assertSame(1, (int) DB::table('orders')->value('payment_status'));

        $this->gateway->rejectSignature = false;
        $this->gateway->payments['pay_low'] = $this->payment('pay_low', $started['razorpay_order_id'], 'captured', 100);
        $this->verify($started, 'pay_low')->assertStatus(422);
        $this->assertSame(10, (int) DB::table('product_details')->value('product_quantity'));
        $this->assertSame(1, (int) DB::table('orders')->value('payment_status'));
    }

    public function test_webhook_confirms_payment_and_ignores_duplicates_and_failures(): void
    {
        $started = $this->startOnline()->json();
        $this->gateway->payments['pay_hook'] = $this->payment('pay_hook', $started['razorpay_order_id'], 'captured');
        $body = $this->webhookBody('payment.captured', 'pay_hook', $started['razorpay_order_id'], 'captured');

        $this->call('POST', '/webhooks/razorpay', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => 'valid',
            'HTTP_X_RAZORPAY_EVENT_ID' => 'evt_1',
        ], $body)->assertNoContent();
        $this->assertSame(9, (int) DB::table('product_details')->value('product_quantity'));

        $this->call('POST', '/webhooks/razorpay', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => 'valid',
            'HTTP_X_RAZORPAY_EVENT_ID' => 'evt_1',
        ], $body)->assertNoContent();
        $this->assertSame(9, (int) DB::table('product_details')->value('product_quantity'));
        $this->assertSame(1, DB::table('payment_webhook_events')->count());

        $this->gateway->payments['pay_fail'] = $this->payment('pay_fail', $started['razorpay_order_id'], 'failed');
        $failed = $this->webhookBody('payment.failed', 'pay_fail', $started['razorpay_order_id'], 'failed');
        $this->call('POST', '/webhooks/razorpay', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => 'nope',
            'HTTP_X_RAZORPAY_EVENT_ID' => 'evt_bad',
        ], $failed)->assertStatus(400);
        $this->assertSame(2, (int) DB::table('orders')->value('payment_status'));
    }

    public function test_a_second_captured_payment_is_refunded_and_stock_is_not_deducted_again(): void
    {
        $started = $this->startOnline()->json();
        $this->gateway->payments['pay_1'] = $this->payment('pay_1', $started['razorpay_order_id'], 'captured');
        $this->verify($started)->assertOk();

        $this->gateway->payments['pay_2'] = $this->payment('pay_2', $started['razorpay_order_id'], 'captured');
        $body = $this->webhookBody('payment.captured', 'pay_2', $started['razorpay_order_id'], 'captured');
        $this->call('POST', '/webhooks/razorpay', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => 'valid',
            'HTTP_X_RAZORPAY_EVENT_ID' => 'evt_dup',
        ], $body)->assertNoContent();

        $this->assertSame(['pay_2'], $this->gateway->refunds);
        $this->assertSame(9, (int) DB::table('product_details')->value('product_quantity'));
        $this->assertSame('captured', DB::table('payment_transactions')->value('status'));
    }

    public function test_cancelled_payment_and_another_session_cannot_confirm_an_order(): void
    {
        $started = $this->startOnline()->json();
        $this->remember($this->postJson('/checkout/online/cancel', ['order_number' => $started['order_number']]))->assertOk();
        $this->assertSame('cancelled', DB::table('payment_transactions')->value('status'));
        $this->assertSame(10, (int) DB::table('product_details')->value('product_quantity'));

        $this->flushSession();
        $this->withCookie((string) config('session.cookie'), 'another-shopper');
        $this->withSession(['shop_cart' => [7 => ['qty' => 1]]]);
        $this->gateway->payments['pay_x'] = $this->payment('pay_x', $started['razorpay_order_id'], 'captured');
        $this->verify($started, 'pay_x')->assertStatus(422);
        $this->assertSame(1, (int) DB::table('orders')->value('payment_status'));
    }

    public function test_cash_on_delivery_emails_the_store_once_and_stays_pending(): void
    {
        Mail::fake();
        $this->placeCod()->assertRedirect();

        $this->assertSame(1, (int) DB::table('orders')->value('payment_status'));
        $this->assertSame(2, (int) DB::table('orders')->value('booking_type'));
        Mail::assertSent(OrderNoticeMail::class, 1);
        Mail::assertSent(OrderNoticeMail::class, function (OrderNoticeMail $mail) {
            return $mail->hasTo('orders@cranzo.test')
                && $mail->template === 'admin-notification'
                && $mail->order['payment_method'] === 'Cash on Delivery'
                && $mail->order['payment_status'] === 'Pending'
                && str_contains($mail->order['customer_name'], 'Asha');
        });
    }

    public function test_captured_payment_emails_once_when_the_callback_is_repeated(): void
    {
        Mail::fake();
        $started = $this->startOnline()->assertOk()->json();
        Mail::assertNothingSent();
        $this->gateway->payments['pay_1'] = $this->payment('pay_1', $started['razorpay_order_id'], 'authorized');
        $this->verify($started)->assertOk();
        $this->verify($started)->assertOk();

        $this->assertSame(2, (int) DB::table('orders')->value('payment_status'));
        Mail::assertSent(OrderNoticeMail::class, 2);
        $this->assertSame(9, (int) DB::table('product_details')->value('product_quantity'));
    }

    public function test_failed_and_duplicate_webhooks_email_once_without_marking_paid(): void
    {
        Mail::fake();
        $started = $this->startOnline()->json();
        $body = json_encode([
            'event' => 'payment.failed',
            'payload' => ['payment' => ['entity' => $this->payment('pay_fail', $started['razorpay_order_id'], 'failed') + ['error_description' => 'Payment timed out']]],
        ]);
        $server = ['CONTENT_TYPE' => 'application/json', 'HTTP_X_RAZORPAY_SIGNATURE' => 'valid', 'HTTP_X_RAZORPAY_EVENT_ID' => 'evt_fail'];
        $this->call('POST', '/webhooks/razorpay', [], [], [], $server, $body)->assertNoContent();
        $this->call('POST', '/webhooks/razorpay', [], [], [], $server, $body)->assertNoContent();

        $this->assertSame(1, (int) DB::table('orders')->value('payment_status'));
        $this->assertSame('failed', DB::table('payment_transactions')->value('status'));
        $this->assertSame(10, (int) DB::table('product_details')->value('product_quantity'));
        Mail::assertSent(OrderNoticeMail::class, 1);
        Mail::assertSent(OrderNoticeMail::class, fn (OrderNoticeMail $mail) => $mail->order['kind'] === 'failed'
            && $mail->order['payment_status'] === 'Unsuccessful'
            && $mail->order['failure_reason'] === 'Payment timed out');
    }

    public function test_closing_checkout_or_a_bad_signature_does_not_send_a_payment_email(): void
    {
        Mail::fake();
        $started = $this->startOnline()->json();
        $this->remember($this->postJson('/checkout/online/cancel', ['order_number' => $started['order_number']]))->assertOk();
        $this->gateway->rejectSignature = true;
        $this->gateway->payments['pay_1'] = $this->payment('pay_1', $started['razorpay_order_id'], 'captured');
        $this->verify($started)->assertStatus(422);

        Mail::assertNothingSent();
        $this->assertSame(1, (int) DB::table('orders')->value('payment_status'));
    }

    public function test_email_delivery_failure_does_not_undo_a_cod_order(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('mailbox unavailable'));
        $this->placeCod()->assertRedirect();

        $this->assertSame(1, DB::table('orders')->count());
        $this->assertSame(1, (int) DB::table('orders')->value('payment_status'));
        $this->assertSame(9, (int) DB::table('product_details')->value('product_quantity'));
    }

    public function test_a_customer_can_retry_their_own_unpaid_order_and_not_someone_elses(): void
    {
        Mail::fake();
        $this->customer();
        $started = $this->startOnline()->json();
        $body = $this->webhookBody('payment.failed', 'pay_fail', $started['razorpay_order_id'], 'failed');
        $this->call('POST', '/webhooks/razorpay', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => 'valid',
            'HTTP_X_RAZORPAY_EVENT_ID' => 'evt_customer_fail',
        ], $body)->assertNoContent();

        Mail::assertSent(OrderNoticeMail::class, fn (OrderNoticeMail $mail) => $mail->hasTo('asha@cranzo.test') && $mail->order['retry_url']);

        $orderId = (int) DB::table('orders')->value('id');
        $this->actingAs(User::query()->find(2))->get('/account/orders/'.$orderId.'/retry-payment')->assertNotFound();
        $this->actingAs(User::query()->find(1))
            ->get('/account/orders/'.$orderId.'/retry-payment')
            ->assertRedirect(route('checkout'));
        $this->assertSame($started['order_number'], session('pending_online_order'));
        $this->assertSame(1, session('shop_cart')[7]['qty']);

        $again = $this->remember($this->postJson('/checkout/online', $this->address('online')))->assertOk()->json();
        $this->gateway->payments['pay_ok'] = $this->payment('pay_ok', $again['razorpay_order_id'], 'authorized');
        $this->verify($again, 'pay_ok')->assertOk();
        $this->assertSame(1, DB::table('orders')->count());
        $this->assertSame(2, (int) DB::table('orders')->value('payment_status'));
        $this->assertSame(9, (int) DB::table('product_details')->value('product_quantity'));
    }

    private function customer(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('mobile_number')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        DB::table('users')->insert([
            ['id' => 1, 'username' => 'asha@cranzo.test', 'email' => 'asha@cranzo.test', 'first_name' => 'Asha', 'last_name' => 'Rao', 'password' => 'secret', 'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'username' => 'other@cranzo.test', 'email' => 'other@cranzo.test', 'first_name' => 'Other', 'last_name' => 'Shopper', 'password' => 'secret', 'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now()],
        ]);
        $this->actingAs(User::query()->find(1));
    }

    private function placeCod()
    {
        return $this->remember($this->withSession(['shop_cart' => [7 => ['qty' => 1]]])->post('/checkout', $this->address('cod')));
    }

    private function startOnline()
    {
        return $this->remember($this->withSession(['shop_cart' => [7 => ['qty' => 1]]])->postJson('/checkout/online', $this->address('online')));
    }

    private function verify(array $started, string $paymentId = 'pay_1', string $signature = 'valid')
    {
        return $this->remember($this->postJson('/checkout/online/verify', [
            'order_number' => $started['order_number'],
            'razorpay_order_id' => $started['razorpay_order_id'],
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => $signature,
        ]));
    }

    private function remember($response)
    {
        foreach ($response->headers->getCookies() as $cookie) {
            $this->withCookie($cookie->getName(), $cookie->getValue());
        }

        return $response;
    }

    private function address(string $method): array
    {
        return [
            'payment_method' => $method,
            'name' => 'Asha Rao',
            'mobile_number' => '9876543210',
            'address' => '12 Market Road',
            'locality' => 'Karol Bagh',
            'city' => 'Delhi',
            'state_id' => 1,
            'pincode' => '110005',
        ];
    }

    private function payment(string $id, string $orderId, string $status, int $amount = 29900): array
    {
        return [
            'id' => $id,
            'order_id' => $orderId,
            'amount' => $amount,
            'currency' => 'INR',
            'status' => $status,
        ];
    }

    private function webhookBody(string $event, string $paymentId, string $orderId, string $status): string
    {
        return json_encode([
            'event' => $event,
            'payload' => [
                'payment' => [
                    'entity' => $this->payment($paymentId, $orderId, $status),
                ],
            ],
        ]);
    }

    private function makeTables(): void
    {
        Schema::create('state', function (Blueprint $table) {
            $table->integer('id');
            $table->string('state_name');
            $table->integer('status');
        });
        Schema::create('product', function (Blueprint $table) {
            $table->integer('id');
            $table->string('product_title');
            $table->integer('status');
            $table->decimal('discounted_price', 10, 2);
            $table->timestamp('created_at')->nullable();
        });
        Schema::create('product_details', function (Blueprint $table) {
            $table->integer('product_details_id');
            $table->integer('product_id');
            $table->integer('product_quantity');
            $table->integer('color_id')->nullable();
        });
        Schema::create('product_images', function (Blueprint $table) {
            $table->integer('image_id')->nullable();
            $table->integer('product_id');
            $table->string('image')->nullable();
            $table->string('thumbnail')->nullable();
        });
        Schema::create('address', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('user_id');
            $table->string('name');
            $table->string('mobile_number');
            $table->string('address');
            $table->integer('pincode');
            $table->string('locality');
            $table->string('city');
            $table->string('state_id');
            $table->integer('address_type');
            $table->integer('default_type');
            $table->dateTime('created_date');
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('session_id')->nullable();
            $table->string('order_number');
            $table->integer('user_id');
            $table->integer('address_id');
            $table->integer('order_status');
            $table->integer('payment_status');
            $table->integer('delivery_status');
            $table->dateTime('booking_date');
            $table->integer('booking_type')->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->dateTime('cancelled_at')->nullable();
        });
        Schema::create('order_details', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('order_id');
            $table->integer('user_id');
            $table->integer('product_id');
            $table->string('product_title');
            $table->string('image');
            $table->integer('order_quantity');
            $table->string('amount_per_item');
        });
        Schema::create('payments', function (Blueprint $table) {
            $table->integer('payment_id');
            $table->integer('order_id');
            $table->string('total_amount');
            $table->string('amount_recieved');
            $table->string('discount_percent')->nullable();
            $table->integer('payment_status');
            $table->dateTime('created_date');
        });
        Schema::create('order_adjustments', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('coupon_code')->nullable();
            $table->string('tax_label')->nullable();
            $table->timestamps();
        });
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->index();
            $table->string('provider', 20);
            $table->string('razorpay_order_id', 64)->nullable()->unique();
            $table->string('razorpay_payment_id', 64)->nullable()->unique();
            $table->unsignedInteger('amount_paise');
            $table->char('currency', 3);
            $table->string('status', 32);
            $table->string('failure_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
        Schema::create('payment_webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_id', 80)->unique();
            $table->string('event_type', 64);
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->timestamps();
        });
        Schema::create('order_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('kind', 32);
            $table->string('audience', 16);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->unique(['order_id', 'kind', 'audience']);
        });
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('color_id')->nullable();
            $table->integer('quantity_change');
            $table->string('reason', 40);
            $table->integer('order_id')->nullable();
            $table->timestamps();
        });
    }
}

class FakeRazorpay implements RazorpayClient
{
    public array $payments = [];

    public array $refunds = [];

    public bool $rejectSignature = false;

    private int $sequence = 1;

    public function createOrder(int $amountPaise, string $receipt, array $notes): array
    {
        return ['id' => 'order_test_'.$this->sequence++, 'amount' => $amountPaise, 'currency' => 'INR'];
    }

    public function verifyPaymentSignature(string $orderId, string $paymentId, string $signature): void
    {
        if ($this->rejectSignature || $signature !== 'valid') {
            throw new PaymentException('Payment could not be confirmed.');
        }
    }

    public function verifyWebhookSignature(string $body, string $signature): void
    {
        if ($signature !== 'valid') {
            throw new PaymentException('Payment could not be confirmed.');
        }
    }

    public function fetchPayment(string $paymentId): array
    {
        return $this->payments[$paymentId];
    }

    public function capture(string $paymentId, int $amountPaise): array
    {
        $this->payments[$paymentId]['status'] = 'captured';
        $this->payments[$paymentId]['amount'] = $amountPaise;

        return $this->payments[$paymentId];
    }

    public function refund(string $paymentId, int $amountPaise): array
    {
        $this->refunds[] = $paymentId;

        return ['id' => 'rfnd_'.$paymentId, 'payment_id' => $paymentId, 'amount' => $amountPaise];
    }
}
