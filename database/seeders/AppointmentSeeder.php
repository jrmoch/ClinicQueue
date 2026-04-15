<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data from 'appts' table
        DB::table('appts')->truncate();  // Changed from 'apps' to 'appts'

        $dummyData = [
            // ===== AVAILABLE APPOINTMENTS =====
            [
                'Patient_LN' => 'Cruz',
                'Patient_FN' => 'Juan',
                'phone_number' => '09123456789',
                'email' => 'juan.cruz@email.com',
                'HomeAddress' => '123 Mabini St., Manila',
                'Doctor_Assigned' => 'Dr. Sarah Chen',
                'Date' => Carbon::tomorrow()->format('Y-m-d'),
                'Time_slot' => '09:00:00',
                'Status' => 'Available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Patient_LN' => 'Santos',
                'Patient_FN' => 'Maria',
                'phone_number' => '09234567890',
                'email' => 'maria.santos@email.com',
                'HomeAddress' => '456 Rizal Ave., Quezon City',
                'Doctor_Assigned' => 'Dr. James Carter',
                'Date' => Carbon::tomorrow()->format('Y-m-d'),
                'Time_slot' => '10:30:00',
                'Status' => 'Available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Patient_LN' => 'Gonzales',
                'Patient_FN' => 'Ana',
                'phone_number' => '09345678901',
                'email' => 'ana.gonzales@email.com',
                'HomeAddress' => '789 P. Burgos St., Makati',
                'Doctor_Assigned' => 'Dr. Emily Watson',
                'Date' => Carbon::tomorrow()->format('Y-m-d'),
                'Time_slot' => '14:00:00',
                'Status' => 'Available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ===== WAITING APPOINTMENTS =====
            [
                'Patient_LN' => 'Reyes',
                'Patient_FN' => 'Miguel',
                'phone_number' => '09456789012',
                'email' => 'miguel.reyes@email.com',
                'HomeAddress' => '321 Laurel St., Pasig',
                'Doctor_Assigned' => 'Dr. Michael Reyes',
                'Date' => Carbon::today()->format('Y-m-d'),
                'Time_slot' => '11:00:00',
                'Status' => 'Waiting',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Patient_LN' => 'Garcia',
                'Patient_FN' => 'Isabella',
                'phone_number' => '09567890123',
                'email' => 'isabella.garcia@email.com',
                'HomeAddress' => '555 Luna St., Taguig',
                'Doctor_Assigned' => 'Dr. Sarah Chen',
                'Date' => Carbon::today()->format('Y-m-d'),
                'Time_slot' => '13:00:00',
                'Status' => 'Waiting',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Patient_LN' => 'Fernandez',
                'Patient_FN' => 'Carlos',
                'phone_number' => '09678901234',
                'email' => 'carlos.fernandez@email.com',
                'HomeAddress' => '888 Roxas Blvd., Pasay',
                'Doctor_Assigned' => 'Dr. James Carter',
                'Date' => Carbon::today()->format('Y-m-d'),
                'Time_slot' => '15:30:00',
                'Status' => 'Waiting',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ===== ONGOING APPOINTMENT =====
            [
                'Patient_LN' => 'Villanueva',
                'Patient_FN' => 'Andrea',
                'phone_number' => '09789012345',
                'email' => 'andrea.villanueva@email.com',
                'HomeAddress' => '777 Quezon Ave., Caloocan',
                'Doctor_Assigned' => 'Dr. Lisa Park',
                'Date' => Carbon::today()->format('Y-m-d'),
                'Time_slot' => '09:30:00',
                'Status' => 'Ongoing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ===== FINISHED APPOINTMENTS =====
            [
                'Patient_LN' => 'Ramirez',
                'Patient_FN' => 'Roberto',
                'phone_number' => '09890123456',
                'email' => 'roberto.ramirez@email.com',
                'HomeAddress' => '444 Mabuhay St., Mandaluyong',
                'Doctor_Assigned' => 'Dr. Emily Watson',
                'Date' => Carbon::today()->format('Y-m-d'),
                'Time_slot' => '08:00:00',
                'Status' => 'Finished',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Patient_LN' => 'Torres',
                'Patient_FN' => 'Sofia',
                'phone_number' => '09901234567',
                'email' => 'sofia.torres@email.com',
                'HomeAddress' => '222 Bonifacio St., Marikina',
                'Doctor_Assigned' => 'Dr. Sarah Chen',
                'Date' => Carbon::yesterday()->format('Y-m-d'),
                'Time_slot' => '16:00:00',
                'Status' => 'Finished',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ===== CANCELLED APPOINTMENT =====
            [
                'Patient_LN' => 'Flores',
                'Patient_FN' => 'Paolo',
                'phone_number' => '09912345678',
                'email' => 'paolo.flores@email.com',
                'HomeAddress' => '111 Mabini St., Manila',
                'Doctor_Assigned' => 'Dr. Michael Reyes',
                'Date' => Carbon::tomorrow()->format('Y-m-d'),
                'Time_slot' => '10:00:00',
                'Status' => 'Cancelled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert all dummy data into 'appts' table
        foreach ($dummyData as $data) {
            DB::table('appts')->insert($data);  // Changed from 'apps' to 'appts'
        }

        $this->command->info('✅ ' . count($dummyData) . ' dummy appointments inserted into appts table!');
        $this->command->info('📊 Table: appts | Records: ' . DB::table('appts')->count());
    }
}