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
        Schema::create('staff_earnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('type')->default('delivery'); // delivery, bonus, deduction, etc
            $table->text('description')->nullable();
            $table->string('payment_status')->default('pending'); // pending, paid
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('delivery_id')->references('id')->on('delivery_tracking')->onDelete('set null');
            $table->index('staff_id');
            $table->index('type');
            $table->index('payment_status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_earnings');
    }
};
