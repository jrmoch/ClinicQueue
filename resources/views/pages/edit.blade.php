<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClinicQueue | Edit Appointment</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="clinic-container">
    <div class="form-header">
        <div class="header-title">
            <div class="title-section">
                <h1>
                    <i class="fas fa-edit"></i> 
                    Edit Appointment #{{ $appt->appt_id }}
                </h1>
            </div>
            <div class="queue-badge">
                <i class="fas fa-clinic-medical"></i> 
                <span>ClinicQueue • Update</span>
            </div>
        </div>
        <div class="subhead">
            <span><i class="fas fa-stethoscope"></i> Modify patient appointment details</span>
        </div>
    </div>

    @if ($errors->any())
    <div class="error-message">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul style="margin-top: 0.25rem; margin-left: 1rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    @if (session('success'))
    <div class="success-message">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    <!-- THE FORM - action matches your PUT route -->
    <form class="appointment-form" action="/appts/{{ $appt->appt_id }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <div class="form-field">
                <label><i class="fas fa-user-circle"></i> Patient Last Name *</label>
                <input type="text" name="lname" value="{{ old('lname', $appt->Patient_LN) }}" required>
            </div>
            
            <div class="form-field">
                <label><i class="fas fa-user"></i> Patient First Name *</label>
                <input type="text" name="fname" value="{{ old('fname', $appt->Patient_FN) }}" required>
            </div>
            
            <div class="form-field">
                <label><i class="fas fa-phone-alt"></i> Phone Number *</label>
                <input type="text" name="pnumber" value="{{ old('pnumber', $appt->phone_number) }}" required>
            </div>
            
            <div class="form-field">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" value="{{ old('email', $appt->email) }}">
            </div>
            
            <div class="form-field">
                <label><i class="fas fa-home"></i> Home Address *</label>
                <input type="text" name="address" value="{{ old('address', $appt->HomeAddress) }}" required>
            </div>
            
            <div class="form-field">
                <label><i class="fas fa-user-md"></i> Doctor Assigned *</label>
                <input type="text" name="doctor" value="{{ old('doctor', $appt->Doctor_Assigned) }}" required>
            </div>
            
            <div class="form-field">
                <label><i class="fas fa-calendar-alt"></i> Date *</label>
                <input type="date" name="date" value="{{ old('date', $appt->Date) }}" required>
            </div>
            
            <div class="form-field">
                <label><i class="fas fa-clock"></i> Time Slot *</label>
                <input type="time" name="time" value="{{ old('time', $appt->Time_slot) }}" required>
            </div>
            
            <div class="form-field">
                <label><i class="fas fa-chart-line"></i> Status *</label>
                <select name="status" required>
                    <option value="Available" {{ (old('status', $appt->Status) == 'Available') ? 'selected' : '' }}>🟢 Available</option>
                    <option value="Waiting" {{ (old('status', $appt->Status) == 'Waiting') ? 'selected' : '' }}>🟡 Waiting</option>
                    <option value="Ongoing" {{ (old('status', $appt->Status) == 'Ongoing') ? 'selected' : '' }}>🔵 Ongoing</option>
                    <option value="Finished" {{ (old('status', $appt->Status) == 'Finished') ? 'selected' : '' }}>✅ Finished</option>
                    <option value="Cancelled" {{ (old('status', $appt->Status) == 'Cancelled') ? 'selected' : '' }}>⛔ Cancelled</option>
                </select>
            </div>
        </div>
        
        <div class="info-note">
            <i class="fas fa-info-circle"></i>
            <span>All statuses available: Available, Waiting, Ongoing, Finished, Cancelled</span>
        </div>
        
        <div class="button-group">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Update Appointment
            </button>
            <a href="/" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Queue
            </a>
        </div>
    </form>
</div>

<script>
    // Phone number - numbers only
    const phoneInput = document.querySelector('input[name="pnumber"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);
        });
    }
</script>
</body>
</html>