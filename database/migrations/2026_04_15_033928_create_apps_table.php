<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->id('appt_id');
            $table->string('Patient_LN');
            $table->string('Patient_FN');
            $table->string('phone_number', 20);
            $table->string('email')->nullable();
            $table->string('HomeAddress');
            $table->string('Doctor_Assigned');
            $table->date('Date');
            $table->time('Time_slot');
            $table->enum('Status', ['Available', 'Waiting', 'Ongoing', 'Finished', 'Cancelled'])->default('Available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apps');
    }
};