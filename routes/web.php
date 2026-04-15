<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\db_controller;
use App\Models\Appt;

// API endpoint for getting appointment status (for AJAX)
Route::get('/api/appointment-status', function () {
    try {
        $reference = request('reference');
        $appointment = null;
        $queuePosition = null;
        
        if ($reference) {
            // Search by phone number or email
            $appointment = Appt::where('phone_number', $reference)
                ->orWhere('email', $reference)
                ->first();
            
            if ($appointment && $appointment->Status == 'Waiting') {
                // Calculate queue position using loop method (more reliable)
                $allWaiting = Appt::where('Status', 'Waiting')
                    ->where('Date', $appointment->Date)
                    ->orderBy('appt_id', 'asc')
                    ->get();
                
                $position = 1;
                foreach ($allWaiting as $index => $waiting) {
                    if ($waiting->appt_id == $appointment->appt_id) {
                        $position = $index + 1;
                        break;
                    }
                }
                $queuePosition = $position;
            }
        }
        
        if ($appointment) {
            return response()->json([
                'found' => true,
                'appointment' => [
                    'appt_id' => $appointment->appt_id,
                    'Patient_LN' => $appointment->Patient_LN,
                    'Patient_FN' => $appointment->Patient_FN,
                    'phone_number' => $appointment->phone_number,
                    'email' => $appointment->email,
                    'HomeAddress' => $appointment->HomeAddress,
                    'Doctor_Assigned' => $appointment->Doctor_Assigned,
                    'Date' => $appointment->Date,
                    'Time_slot' => $appointment->Time_slot,
                    'Status' => $appointment->Status,
                ],
                'queuePosition' => $queuePosition
            ]);
        } else {
            return response()->json(['found' => false]);
        }
    } catch (\Exception $e) {
        return response()->json(['found' => false, 'error' => $e->getMessage()]);
    }
});

Route::get('/', function () {
    return view('index');
});

Route::get('/', [db_controller::class, 'index'])
->name('index');

Route::get('/pages/create', function () {
    return view('pages.create');
})->name('pages.create');

// Admin create route (stays on index)
Route::post('/pages/create', function(){ 
    Appt::create([
        'Patient_LN' => request('lname'),
        'Patient_FN' => request('fname'),
        'phone_number' => request('pnumber'),
        'email' => request('email'),
        'HomeAddress' => request('address'),
        'Doctor_Assigned' => request('doctor'),
        'Date' => request('date'),
        'Time_slot' => request('time'),
        'Status' => request('status')
    ]);
    return redirect()->route('index');
}); 

// EDIT FORM 
Route::get('/appts/{id}/edit', function ($id) {
    $appt = Appt::findOrFail($id);
    return view('pages.edit', ['appt' => $appt]);
});

// UPDATE DATA
Route::put('/appts/{id}', function ($id) {
    $appt = Appt::findOrFail($id);

    $appt->update([
        'Patient_LN' => request('lname'),
        'Patient_FN' => request('fname'),
        'phone_number' => request('pnumber'),
        'email' => request('email'),
        'HomeAddress' => request('address'),
        'Doctor_Assigned' => request('doctor'),
        'Date' => request('date'),
        'Time_slot' => request('time'),
        'Status' => request('status'),
    ]);

    return redirect('/')->with('success', 'Updated!');
});

// DELETE ROUTE
Route::delete('/appts/{id}', function ($id) {
    Appt::destroy($id);
    return back();
});

// ========== CLIENT ROUTES ==========

// Client booking page (the form)
Route::get('/book', function () {
    return view('client.book');
})->name('client.book');

// Client booking submission - Redirects to queue status page
Route::post('/book', function () {
    request()->validate([
        'lname' => 'required|string|max:255',
        'fname' => 'required|string|max:255',
        'pnumber' => 'required|string|max:20',
        'email' => 'nullable|email|max:255',
        'address' => 'required|string|max:500',
        'doctor' => 'required|string|max:255',
        'date' => 'required|date',
        'time' => 'required',
    ]);

    $appointment = Appt::create([
        'Patient_LN' => request('lname'),
        'Patient_FN' => request('fname'),
        'phone_number' => request('pnumber'),
        'email' => request('email'),
        'HomeAddress' => request('address'),
        'Doctor_Assigned' => request('doctor'),
        'Date' => request('date'),
        'Time_slot' => request('time'),
        'Status' => 'Waiting'
    ]);

    return redirect('/queue-status?reference=' . urlencode(request('pnumber')))->with('success', 'Appointment booked successfully! You can track your queue status below.');
})->name('client.book.submit');

// Client view-only queue status page - WITH DEBUGGING
Route::get('/queue-status', function () {
    $appointment = null;
    $queuePosition = null;
    $reference = request('reference');
    
    if ($reference) {
        // Search by phone number or email
        $appointment = Appt::where('phone_number', $reference)
            ->orWhere('email', $reference)
            ->first();
        
        // Calculate queue position using reliable loop method
        if ($appointment && $appointment->Status == 'Waiting') {
            // Get all waiting appointments on the same date, ordered by ID
            $allWaiting = Appt::where('Status', 'Waiting')
                ->where('Date', $appointment->Date)
                ->orderBy('appt_id', 'asc')
                ->get();
            
            // Find position (1-based index)
            $position = 1;
            foreach ($allWaiting as $index => $waiting) {
                if ($waiting->appt_id == $appointment->appt_id) {
                    $position = $index + 1;
                    break;
                }
            }
            $queuePosition = $position;
            
            // ===== DEBUG CODE - This will log to storage/logs/laravel.log =====
            \Log::info('========== QUEUE POSITION DEBUG ==========');
            \Log::info('Appointment ID: ' . $appointment->appt_id);
            \Log::info('Appointment Date: ' . $appointment->Date);
            \Log::info('All Waiting IDs on this date: ' . json_encode($allWaiting->pluck('appt_id')->toArray()));
            \Log::info('Calculated Position: ' . $queuePosition);
            \Log::info('Total Waiting Count: ' . $allWaiting->count());
            \Log::info('==========================================');
            // ===== END DEBUG CODE =====
        }
    }
    
    return view('client.queue-status', compact('appointment', 'queuePosition', 'reference'));
})->name('client.queue.status');

// API endpoint for queue count (for live updates)
Route::get('/api/queue-count', function () {
    $waiting = Appt::where('Status', 'Waiting')->count();
    $ongoing = Appt::where('Status', 'Ongoing')->count();
    return response()->json(['waiting' => $waiting, 'ongoing' => $ongoing]);
});

// API endpoint for today's finished appointments
Route::get('/api/today-finished', function () {
    $finished = Appt::where('Status', 'Finished')
        ->whereDate('Date', today())
        ->count();
    return response()->json(['finished' => $finished]);
});