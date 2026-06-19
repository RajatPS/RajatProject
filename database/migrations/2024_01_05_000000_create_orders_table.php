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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->string('fullname');
            $table->string('contact_number');
            $table->string('email');
            $table->text('address');
            $table->text('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('paymentMethod')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('totalAmount', 12, 2);
            $table->string('cardName')->nullable();
            $table->string('cardNumber')->nullable();
            $table->string('expmonth')->nullable();
            $table->string('expyear')->nullable();
            $table->string('cvv')->nullable();
            $table->string('upi')->nullable();
            $table->string('status')->default('Pending');
            $table->date('order_date')->nullable();
            $table->time('order_time')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('reason')->nullable(); // for return reason
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('set null');
            $table->index('user_id');
            $table->index('product_id');
            $table->index('staff_id');
            $table->index('status');
            $table->index('order_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
