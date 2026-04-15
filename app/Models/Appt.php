<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appt extends Model
{
    use HasFactory;

    protected $table = 'appts';  // Changed from 'apps' to 'appts'
    protected $primaryKey = 'appt_id';
    
    protected $fillable = [
        'Patient_LN',
        'Patient_FN',
        'phone_number',
        'email',
        'HomeAddress',
        'Doctor_Assigned',
        'Date',
        'Time_slot',
        'Status',
    ];
}