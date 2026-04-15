<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClinicQueue | Add New Appointment</title>
    <!-- Google Fonts for clean typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 (free icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="clinic-container">
    <!-- Header Section - Light blue queue theme -->
    <div class="form-header">
        <div class="header-title">
            <div class="title-section">
                <h1>
                    <i class="fas fa-plus-circle"></i> 
                    Add New Appointment
                </h1>
            </div>
            <div class="queue-badge">
                <i class="fas fa-clinic-medical"></i> 
                <span>ClinicQueue </span>
            </div>
        </div>
        <div class="subhead">
            <span><i class="fas fa-stethoscope"></i> Create patient appointment & join queue</span>
            <span><i class="fas fa-chart-line"></i> Status options: Ongoing, Available, Waiting</span>
        </div>
    </div>

    <!-- Display validation errors if any -->
    @if ($errors->any())
    <div style="padding: 0 2rem; margin-top: 1rem;">
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
    </div>
    @endif

    <!-- Display success message if coming from redirect -->
    @if (session('success'))
    <div style="padding: 0 2rem; margin-top: 1rem;">
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Main Creation Form -->
    <form class="appointment-form" action="/pages/create" method="POST">
        @csrf
        
        <div class="form-grid">
            <!-- Patient Last Name -->
            <div class="form-field">
                <label><i class="fas fa-user-circle"></i> Patient Last Name *</label>
                <input type="text" id="lname" name="lname" value="{{ old('lname') }}" placeholder="e.g., Dela Cruz" required>
            </div>
            
            <!-- Patient First Name -->
            <div class="form-field">
                <label><i class="fas fa-user"></i> Patient First Name *</label>
                <input type="text" id="fname" name="fname" value="{{ old('fname') }}" placeholder="e.g., Maria" required>
            </div>
            
            <!-- Phone Number -->
            <div class="form-field">
                <label><i class="fas fa-phone-alt"></i> Phone Number *</label>
                <input type="tel" id="pnumber" name="pnumber" value="{{ old('pnumber') }}" placeholder="1234567890" required>
            </div>
            
            <!-- Email -->
            <div class="form-field">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="patient@example.com">
            </div>
            
            <!-- Home Address -->
            <div class="form-field">
                <label><i class="fas fa-home"></i> Home Address *</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="Street, City, ZIP" required>
            </div>
            
            <!-- Doctor Assigned -->
            <div class="form-field">
                <label><i class="fas fa-user-md"></i> Doctor Assigned *</label>
                <input type="text" id="doctor" name="doctor" value="{{ old('doctor') }}" placeholder="e.g., Dr. Sarah Chen" required>
            </div>
            
            <!-- Date -->
            <div class="form-field">
                <label><i class="fas fa-calendar-alt"></i> Appointment Date *</label>
                <input type="date" id="date" name="date" value="{{ old('date') }}" required>
            </div>
            
            <!-- Time Slot -->
            <div class="form-field">
                <label><i class="fas fa-clock"></i> Time Slot *</label>
                <input type="time" id="time" name="time" value="{{ old('time') }}" required>
            </div>
            
            <!-- Status Dropdown - only 3 options for creation -->
            <div class="form-field">
                <label><i class="fas fa-chart-line"></i> Initial Status *</label>
                <select id="status" name="status" required>
                    <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>🟢 Available</option>
                    <option value="Waiting" {{ old('status') == 'Waiting' ? 'selected' : '' }} selected>🟡 Waiting (queue)</option>
                    <option value="Ongoing" {{ old('status') == 'Ongoing' ? 'selected' : '' }}>🔵 Ongoing</option>
                </select>
                <div class="status-preview">
                    <i class="fas fa-info-circle" style="color:#4f8bb3;"></i> 
                    <span>Creating appointment: Only <strong>Available, Waiting, Ongoing</strong> allowed. (Finished/Cancelled can be set later via Edit)</span>
                </div>
            </div>
        </div>
        
        <!-- Info note about status rules based on requirements -->
        <div class="info-note">
            <i class="fas fa-clipboard-list"></i>
            <span><strong>Queue rule:</strong> When creating → <strong>Ongoing, Available, Waiting</strong> only. After creation, you can update to <strong>Finished</strong> or <strong>Cancelled</strong> from the appointment list (Edit).</span>
        </div>
        
        <!-- Action Buttons -->
        <div class="button-group">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Create Appointment
            </button>
            <button type="reset" class="btn-secondary" id="resetFormBtn">
                <i class="fas fa-undo-alt"></i> Clear Form
            </button>
            <a href="/" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Queue
            </a>
        </div>
        
        <!-- live queue micro demo -->
        <div style="margin-top: 1rem; display: flex; justify-content: flex-end;">
            <div class="queue-mini">
                <i class="fas fa-hourglass-half"></i> 
                <span>Queue ready · add new patient</span>
            </div>
        </div>
    </form>
</div>

<script>
    (function() {
        // Set default date to tomorrow if not already set
        const dateInput = document.getElementById('date');
        if (dateInput && !dateInput.value) {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const yyyy = tomorrow.getFullYear();
            const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
            const dd = String(tomorrow.getDate()).padStart(2, '0');
            dateInput.value = `${yyyy}-${mm}-${dd}`;
        }
        
        // Set default time to 09:00 if not already set
        const timeInput = document.getElementById('time');
        if (timeInput && !timeInput.value) {
            timeInput.value = "09:00";
        }
        
        // Reset button functionality
        const resetBtn = document.getElementById('resetFormBtn');
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                setTimeout(() => {
                    if (dateInput && !dateInput.value) {
                        const tomorrow = new Date();
                        tomorrow.setDate(tomorrow.getDate() + 1);
                        const yyyy = tomorrow.getFullYear();
                        const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
                        const dd = String(tomorrow.getDate()).padStart(2, '0');
                        dateInput.value = `${yyyy}-${mm}-${dd}`;
                    }
                    if (timeInput && !timeInput.value) {
                        timeInput.value = "09:00";
                    }
                    const statusSelect = document.getElementById('status');
                    if (statusSelect && !statusSelect.value) {
                        statusSelect.value = "Waiting";
                    }
                }, 10);
            });
        }
        
        // Phone number validation - prevent non-numeric
        const phoneInput = document.getElementById('pnumber');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);
            });
        }
        
        // Update status hint dynamically
        const statusSelect = document.getElementById('status');
        const previewSpan = document.querySelector('.status-preview span');
        if (statusSelect && previewSpan) {
            statusSelect.addEventListener('change', function() {
                const val = this.value;
                let hintMsg = '';
                if (val === 'Available') hintMsg = '🟢 Available slot — patient can be scheduled freely.';
                else if (val === 'Waiting') hintMsg = '🟡 Waiting queue — patient is in line for consultation.';
                else if (val === 'Ongoing') hintMsg = '🔵 Ongoing — doctor is currently attending the patient.';
                previewSpan.innerHTML = `Creating appointment: Only <strong>Available, Waiting, Ongoing</strong> allowed. Selected: <strong>${val}</strong> — ${hintMsg}`;
            });
        }
    })();
</script>
</body>
</html>