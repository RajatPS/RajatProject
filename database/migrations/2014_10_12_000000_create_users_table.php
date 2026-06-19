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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->date('DOB')->nullable();
            $table->string('gender')->nullable();
            $table->enum('role', ['user', 'seller', 'staff', 'admin'])->default('user');
            $table->enum('account_status', ['active', 'inactive'])->default('active');
            $table->string('assigned_area')->nullable(); // for staff
            $table->string('vehicle_type')->nullable(); // for staff
            $table->string('vehicle_no')->nullable(); // for staff
            $table->string('license_no')->nullable(); // for staff
            $table->timestamp('LastSeen')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->index('email');
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
$table->enum('account_status', ['active', 'inactive'])->default('active');
