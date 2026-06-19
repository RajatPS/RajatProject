<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seller_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('order_id');
            $table->decimal('order_amount', 12, 2);
            $table->decimal('commission_rate', 5, 2); // percentage
            $table->decimal('commission_amount', 12, 2);
            $table->decimal('seller_earning', 12, 2);
            $table->string('status')->default('pending'); // pending, credited, withdrawn
            $table->timestamps();
            
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->index('seller_id');
            $table->index('order_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_commissions');
    }
};
