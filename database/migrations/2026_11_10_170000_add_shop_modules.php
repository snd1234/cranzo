<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('product') && ! Schema::hasColumn('product', 'gst_rate')) {
            Schema::table('product', function (Blueprint $table) {
                $table->decimal('gst_rate', 5, 2)->nullable()->after('discount_rate');
            });
        }

        if (Schema::hasTable('contact_us') && ! Schema::hasColumn('contact_us', 'status')) {
            Schema::table('contact_us', function (Blueprint $table) {
                $table->string('status', 20)->default('new')->index();
                $table->text('admin_note')->nullable();
            });
        }

        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('session_id', 120)->nullable()->index();
            $table->unsignedInteger('product_id');
            $table->timestamps();
            $table->unique(['user_id', 'product_id']);
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();
            $table->string('type', 20);
            $table->decimal('amount', 10, 2);
            $table->decimal('min_order', 10, 2)->default(0);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('per_customer')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->index();
            $table->timestamps();
        });

        Schema::create('coupon_redemptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coupon_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedInteger('order_id')->index();
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });

        Schema::create('order_adjustments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('coupon_code')->nullable();
            $table->string('tax_label', 40)->nullable();
            $table->timestamps();
        });

        Schema::create('order_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id')->index();
            $table->string('field', 40);
            $table->string('from_value')->nullable();
            $table->string('to_value')->nullable();
            $table->string('note')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
        });

        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('quantity');
            $table->string('reason', 80);
            $table->text('details')->nullable();
            $table->string('image')->nullable();
            $table->string('status', 30)->default('pending')->index();
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id')->index();
            $table->unsignedInteger('color_id')->nullable();
            $table->integer('quantity_change');
            $table->string('reason', 40);
            $table->unsignedInteger('order_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('stored_carts', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 120)->unique();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->json('items');
            $table->timestamp('converted_at')->nullable();
            $table->timestamp('recovered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stored_carts');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('return_requests');
        Schema::dropIfExists('order_events');
        Schema::dropIfExists('order_adjustments');
        Schema::dropIfExists('coupon_redemptions');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('wishlists');
        if (Schema::hasColumn('contact_us', 'admin_note')) {
            Schema::table('contact_us', function (Blueprint $table) {
                $table->dropColumn(['status', 'admin_note']);
            });
        }
        if (Schema::hasColumn('product', 'gst_rate')) {
            Schema::table('product', function (Blueprint $table) {
                $table->dropColumn('gst_rate');
            });
        }
    }
};
