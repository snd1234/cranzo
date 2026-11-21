<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->convertCharset([
            'address', 'admin', 'category', 'colors', 'contact_us', 'orders', 'order_details',
            'payments', 'product', 'product_details', 'product_images', 'reviews', 'state',
            'sub_category', 'ternary_category',
        ]);

        $this->primaryKey('payments', 'payment_id');
        $this->primaryKey('product_details', 'product_details_id');
        $this->primaryKey('product_images', 'image_id');
        $this->primaryKey('reviews', 'review_id');
        $this->primaryKey('state', 'id');
        $this->primaryKey('ternary_category', 'ternary_id');

        $this->bigint('orders', 'id', true);
        $this->bigint('order_details', 'id', false);
        $this->bigint('order_details', 'order_id', false);
        $this->bigint('order_details', 'product_id', false);
        $this->bigint('payments', 'payment_id', false);
        $this->bigint('payments', 'order_id', false);
        $this->bigint('payment_transactions', 'order_id', false);
        $this->bigint('order_adjustments', 'order_id', false);
        $this->bigint('stock_movements', 'order_id', false, true);
        $this->bigint('order_events', 'order_id', false);
        $this->bigint('product', 'id', true);
        $this->bigint('product_images', 'image_id', false);
        $this->bigint('product_images', 'product_id', false);
        $this->bigint('product_details', 'product_details_id', false);
        $this->bigint('product_details', 'product_id', false);
        $this->bigint('state', 'id', false);
        $this->bigint('reviews', 'review_id', false);
        $this->bigint('ternary_category', 'ternary_id', false);

        DB::statement('ALTER TABLE product MODIFY actual_price DECIMAL(12,2) NOT NULL, MODIFY discounted_price DECIMAL(12,2) NOT NULL');
        DB::statement('ALTER TABLE order_details MODIFY amount_per_item DECIMAL(12,2) NOT NULL');

        $this->addColumn('orders', 'created_at', 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP');
        $this->addColumn('orders', 'updated_at', 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        DB::statement('UPDATE orders SET created_at = booking_date WHERE created_at IS NULL');

        $this->addColumn('payments', 'amount', 'DECIMAL(12,2) NULL');
        $this->addColumn('payments', 'currency', "CHAR(3) NOT NULL DEFAULT 'INR'");
        $this->addColumn('payments', 'payment_gateway', 'VARCHAR(50) NULL');
        $this->addColumn('payments', 'gateway_transaction_id', 'VARCHAR(255) NULL');
        $this->addColumn('payments', 'idempotency_key', 'VARCHAR(255) NULL');
        $this->addColumn('payments', 'payment_state', 'VARCHAR(32) NULL');
        $this->addColumn('payments', 'failure_reason', 'TEXT NULL');
        $this->addColumn('payments', 'gateway_response', 'JSON NULL');
        $this->addColumn('payments', 'ip_address', 'VARCHAR(45) NULL');
        $this->addColumn('payments', 'user_agent', 'TEXT NULL');
        $this->addColumn('payments', 'created_at', 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP');
        $this->addColumn('payments', 'updated_at', 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        DB::statement("UPDATE payments SET amount = CAST(total_amount AS DECIMAL(12,2)) WHERE amount IS NULL");
        DB::statement("UPDATE payments SET payment_state = CASE payment_status WHEN 2 THEN 'succeeded' WHEN 1 THEN 'pending' ELSE 'pending' END WHERE payment_state IS NULL");
        DB::statement('UPDATE payments SET created_at = created_date WHERE created_at IS NULL');
        DB::statement("UPDATE payments p JOIN orders o ON o.id = p.order_id SET p.payment_gateway = CASE WHEN o.booking_type = 1 THEN 'razorpay' ELSE 'cod' END WHERE p.payment_gateway IS NULL");

        $this->addColumn('payment_transactions', 'idempotency_key', 'VARCHAR(255) NULL');
        $this->addColumn('payment_transactions', 'amount', 'DECIMAL(12,2) NULL');
        $this->addColumn('payment_transactions', 'ip_address', 'VARCHAR(45) NULL');
        $this->addColumn('payment_transactions', 'user_agent', 'TEXT NULL');
        $this->addColumn('payment_transactions', 'gateway_response', 'JSON NULL');
        DB::statement('UPDATE payment_transactions SET amount = ROUND(amount_paise / 100, 2) WHERE amount IS NULL');
        DB::statement("UPDATE payment_transactions SET idempotency_key = CONCAT('razorpay:', razorpay_order_id) WHERE idempotency_key IS NULL AND razorpay_order_id IS NOT NULL");

        foreach (['users', 'admins'] as $table) {
            $this->addColumn($table, 'last_login_at', 'TIMESTAMP NULL');
            $this->addColumn($table, 'last_login_ip', 'VARCHAR(45) NULL');
            $this->addColumn($table, 'failed_login_attempts', 'INT NOT NULL DEFAULT 0');
            $this->addColumn($table, 'locked_until', 'TIMESTAMP NULL');
            $this->addColumn($table, 'two_factor_secret', 'TEXT NULL');
            $this->addColumn($table, 'is_2fa_enabled', 'TINYINT(1) NOT NULL DEFAULT 0');
            $this->addColumn($table, 'deleted_at', 'TIMESTAMP NULL');
        }

        $this->uniqueIndex('orders', 'orders_order_number_unique', 'order_number');
        $this->index('orders', 'orders_user_id_index', 'user_id');
        $this->index('payments', 'payments_order_id_index', 'order_id');
        $this->index('payments', 'payments_gateway_transaction_index', 'gateway_transaction_id');
        $this->uniqueIndex('payments', 'payments_idempotency_key_unique', 'idempotency_key');
        $this->index('payments', 'payments_payment_state_index', 'payment_state');
        $this->uniqueIndex('payment_transactions', 'payment_transactions_idempotency_unique', 'idempotency_key');
        $this->index('order_details', 'order_details_order_id_index', 'order_id');
        $this->index('order_details', 'order_details_product_id_index', 'product_id');
        $this->index('product_details', 'product_details_product_id_index', 'product_id');
        $this->index('product_images', 'product_images_product_id_index', 'product_id');

        $this->foreign('order_details', 'order_id', 'orders', 'id', 'order_details_order_fk');
        $this->foreign('payments', 'order_id', 'orders', 'id', 'payments_order_fk');
        $this->foreign('payment_transactions', 'order_id', 'orders', 'id', 'payment_transactions_order_fk');
        $this->foreign('order_adjustments', 'order_id', 'orders', 'id', 'order_adjustments_order_fk');
        $this->foreign('stock_movements', 'order_id', 'orders', 'id', 'stock_movements_order_fk');
        $this->foreign('order_events', 'order_id', 'orders', 'id', 'order_events_order_fk');
        $this->foreign('product_images', 'product_id', 'product', 'id', 'product_images_product_fk');

        if (! Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->string('actor_type', 20)->nullable();
                $table->unsignedBigInteger('actor_id')->nullable();
                $table->string('action', 80)->index();
                $table->string('subject_type', 80)->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->json('context')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->index(['subject_type', 'subject_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }

    private function convertCharset(array $tables): void
    {
        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            DB::statement("ALTER TABLE `{$table}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        }
    }

    private function primaryKey(string $table, string $column): void
    {
        if (! Schema::hasTable($table) || $this->hasPrimary($table)) {
            return;
        }
        DB::statement("ALTER TABLE `{$table}` ADD PRIMARY KEY (`{$column}`)");
    }

    private function hasPrimary(string $table): bool
    {
        $rows = DB::select('SHOW KEYS FROM `'.$table.'` WHERE Key_name = ?', ['PRIMARY']);

        return $rows !== [];
    }

    private function bigint(string $table, string $column, bool $increment, bool $nullable = false): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return;
        }
        $null = $nullable ? 'NULL' : 'NOT NULL';
        $extra = $increment ? ' AUTO_INCREMENT' : '';
        DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` BIGINT UNSIGNED {$null}{$extra}");
    }

    private function addColumn(string $table, string $column, string $definition): void
    {
        if (! Schema::hasTable($table) || Schema::hasColumn($table, $column)) {
            return;
        }
        DB::statement("ALTER TABLE `{$table}` ADD `{$column}` {$definition}");
    }

    private function index(string $table, string $name, string $column): void
    {
        if (! Schema::hasTable($table) || $this->indexExists($table, $name)) {
            return;
        }
        DB::statement("ALTER TABLE `{$table}` ADD INDEX `{$name}` (`{$column}`)");
    }

    private function uniqueIndex(string $table, string $name, string $column): void
    {
        if (! Schema::hasTable($table) || $this->indexExists($table, $name)) {
            return;
        }
        DB::statement("ALTER TABLE `{$table}` ADD UNIQUE `{$name}` (`{$column}`)");
    }

    private function indexExists(string $table, string $name): bool
    {
        $rows = DB::select('SHOW INDEX FROM `'.$table.'` WHERE Key_name = ?', [$name]);

        return $rows !== [];
    }

    private function foreign(string $table, string $column, string $referenced, string $referencedColumn, string $name): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return;
        }
        $existing = DB::select(
            'SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?',
            [$table, $name]
        );
        if ($existing !== []) {
            return;
        }
        $orphans = DB::select(
            "SELECT COUNT(*) AS total FROM `{$table}` child LEFT JOIN `{$referenced}` parent ON parent.`{$referencedColumn}` = child.`{$column}` WHERE child.`{$column}` IS NOT NULL AND parent.`{$referencedColumn}` IS NULL"
        );
        if ((int) ($orphans[0]->total ?? 0) > 0) {
            return;
        }
        DB::statement("ALTER TABLE `{$table}` ADD CONSTRAINT `{$name}` FOREIGN KEY (`{$column}`) REFERENCES `{$referenced}` (`{$referencedColumn}`) ON DELETE RESTRICT ON UPDATE CASCADE");
    }
};
