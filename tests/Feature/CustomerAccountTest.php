<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class CustomerAccountTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->makeShopTables();
    }

    public function test_duplicate_email_is_rejected(): void
    {
        User::create([
            'username' => 'ada@example.com',
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'email' => 'ada@example.com',
            'mobile_number' => '9876543210',
            'password' => 'password123',
            'email_verified_at' => now(),
        ]);

        $this->from('/register')->post('/register', [
            'first_name' => 'Ann',
            'last_name' => 'Other',
            'email' => 'ada@example.com',
            'mobile_number' => '9123456780',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ])->assertRedirect('/register')->assertSessionHasErrors('email');
    }

    public function test_unverified_customer_cannot_open_the_account(): void
    {
        $user = User::create([
            'username' => 'new@example.com',
            'first_name' => 'New',
            'last_name' => 'Customer',
            'email' => 'new@example.com',
            'mobile_number' => '9988776655',
            'password' => 'password123',
        ]);

        $this->actingAs($user)->get('/account')->assertRedirect('/email/verify');
    }

    public function test_customer_cannot_view_another_customers_order(): void
    {
        $owner = User::create([
            'username' => 'owner@example.com',
            'first_name' => 'Own',
            'last_name' => 'Er',
            'email' => 'owner@example.com',
            'mobile_number' => '9012345678',
            'password' => 'password123',
        ]);
        $owner->forceFill(['email_verified_at' => now()])->save();
        $other = User::create([
            'username' => 'other@example.com',
            'first_name' => 'Oth',
            'last_name' => 'Er',
            'email' => 'other@example.com',
            'mobile_number' => '9012345679',
            'password' => 'password123',
        ]);
        $other->forceFill(['email_verified_at' => now()])->save();
        $orderId = DB::table('orders')->insertGetId([
            'order_number' => 'T100',
            'user_id' => $owner->id,
            'address_id' => 1,
            'order_status' => 1,
            'payment_status' => 1,
            'delivery_status' => 1,
            'booking_date' => now(),
        ]);

        $this->actingAs($other)->get('/account/orders/'.$orderId)->assertNotFound();
        $this->actingAs($owner)->get('/account/orders/'.$orderId)->assertOk();
    }

    public function test_login_removes_unavailable_cart_items(): void
    {
        $user = User::create([
            'username' => 'cart@example.com',
            'first_name' => 'Cart',
            'last_name' => 'User',
            'email' => 'cart@example.com',
            'mobile_number' => '9090909090',
            'password' => 'password123',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();
        DB::table('product')->insert([
            'id' => 5,
            'product_title' => 'Gone',
            'status' => 0,
            'discounted_price' => 100,
            'created_at' => now(),
        ]);

        $this->withSession(['shop_cart' => [5 => ['qty' => 2]]])
            ->post('/login', ['email' => 'cart@example.com', 'password' => 'password123'])
            ->assertRedirect('/account');

        $this->assertAuthenticatedAs($user);
        $this->assertSame([], session('shop_cart'));
    }

    private function makeShopTables(): void
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
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedTinyInteger('status')->default(1);
        });
        Schema::create('sub_category', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->string('name');
            $table->unsignedTinyInteger('status')->default(1);
        });
        Schema::create('product', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_title');
            $table->unsignedTinyInteger('status')->default(1);
            $table->decimal('discounted_price', 10, 2)->default(0);
            $table->timestamp('created_at')->nullable();
        });
        Schema::create('product_details', function (Blueprint $table) {
            $table->integer('product_details_id');
            $table->integer('product_id');
            $table->integer('product_quantity')->default(0);
        });
        Schema::create('product_images', function (Blueprint $table) {
            $table->integer('image_id');
            $table->integer('product_id');
            $table->string('image')->nullable();
            $table->string('thumbnail')->nullable();
        });
        Schema::create('address', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('user_id');
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('locality')->nullable();
            $table->string('city')->nullable();
            $table->string('state_id')->nullable();
            $table->integer('pincode')->nullable();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('session_id')->nullable();
            $table->string('order_number');
            $table->integer('user_id');
            $table->integer('address_id');
            $table->unsignedTinyInteger('order_status');
            $table->unsignedTinyInteger('payment_status');
            $table->unsignedTinyInteger('delivery_status');
            $table->dateTime('booking_date');
            $table->dateTime('cancelled_at')->nullable();
        });
        Schema::create('order_details', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('order_id');
            $table->string('product_title');
            $table->integer('order_quantity');
            $table->string('amount_per_item');
        });
    }
}
