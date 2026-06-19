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
        Schema::create('staff_attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->date('attendance_date');
            $table->timestamp('check_in_time')->nullable();
            $table->timestamp('check_out_time')->nullable();
            $table->integer('total_deliveries')->default(0);
            $table->string('status')->default('present'); // present, absent, half-day, on-leave
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('staff_id');
            $table->index('attendance_date');
            $table->unique(['staff_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_attendance');
    }
};
