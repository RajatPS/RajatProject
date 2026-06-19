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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->string('product_name');
            $table->string('category')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->decimal('weight', 8, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->json('type')->nullable(); // for featured, new, onSale
            $table->timestamps();
            $table->index('seller_id');
            $table->index('category');
            $table->index('status');
            
            // Foreign key - nullable for now
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
