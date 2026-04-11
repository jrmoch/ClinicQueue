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
        Schema::create('appts', function (Blueprint $table) {
            $table->id('appt_id') -> primary() -> autoIncrement();
            $table->string('Patient_LN') -> required() -> max(70);
            $table->string('Patient_FN') -> required() -> max(100);
            $table->string('phone_number') -> required() -> max(20);
            $table->string('email') -> max(100);
            $table->text('HomeAddress') -> required();
            $table->string('Doctor_Assigned') -> required() -> max(100);
            $table->date('Date') -> required();
            $table->time('Time_slot') -> required();
            $table->enum('Status', ['Ongoing', 'Finished', 'Cancelled','Waiting','Available'])-> required();
           
        });
    }
/* ['appt_id','Customer_FN','Customer_LN',
'phone_number','email','HomeAddress',
'Doctor_Assigned','Date','Time_slot','Status'] <-- incase u get lost lol */
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appts');
    }
};
