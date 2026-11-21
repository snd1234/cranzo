<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('order_notifications')) {
            return;
        }

        Schema::create('order_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('kind', 32);
            $table->string('audience', 16);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->unique(['order_id', 'kind', 'audience'], 'order_notifications_once');
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_notifications');
    }
};
