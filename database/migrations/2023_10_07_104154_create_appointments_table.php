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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_schedule_id');
            $table->unsignedBigInteger('patient_id');
            $table->dateTime('appointment_date');
            $table->enum('appointment_type', ['Normal', 'Follow-Up', 'Re-Scheduled']);
            $table->enum('appointment_status', ['Scheduled', 'Cancelled', 'Completed']);
            $table->text('reason_for_visit')->nullable();
            $table->timestamps();

            $table->foreign('doctor_schedule_id')->references('id')->on('doctor_schedules');
            $table->foreign('patient_id')->references('id')->on('patients');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
