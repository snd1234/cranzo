<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payment_transactions')) {
            Schema::create('payment_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('order_id')->index();
                $table->string('provider', 20)->default('razorpay');
                $table->string('razorpay_order_id', 64)->nullable()->unique();
                $table->string('razorpay_payment_id', 64)->nullable()->unique();
                $table->unsignedInteger('amount_paise');
                $table->char('currency', 3)->default('INR');
                $table->string('status', 32)->index();
                $table->string('failure_reason', 255)->nullable();
                $table->timestamp('verified_at')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('payment_webhook_events')) {
            Schema::create('payment_webhook_events', function (Blueprint $table) {
                $table->id();
                $table->string('event_id', 80)->unique();
                $table->string('event_type', 64);
                $table->string('razorpay_order_id', 64)->nullable()->index();
                $table->string('razorpay_payment_id', 64)->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_webhook_events');
        Schema::dropIfExists('payment_transactions');
    }
};
