<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClinicQueue | Book Appointment</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="booking-container">
    <div class="booking-header">
        <h1><i class="fas fa-calendar-plus"></i> Book Appointment</h1>
        <p>Fill out the form below to schedule your visit</p>
    </div>

    <div class="queue-info">
        <div><i class="fas fa-chart-line"></i> <span id="waitingCount">Loading...</span></div>
        <div><i class="fas fa-clock"></i> Est. wait: <strong id="waitTime">15-30 min</strong></div>
        <div class="queue-badge"><i class="fas fa-users"></i> Walk-ins welcome</div>
    </div>

    @if ($errors->any())
    <div style="padding: 0 2rem;">
        <div class="error-message">
            <i class="fas fa-exclamation-triangle"></i>
            <ul style="margin-left: 1rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    @if (session('success'))
    <div style="padding: 0 2rem;">
        <div class="success-message">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    </div>
    @endif

    <form class="booking-form" action="{{ route('client.book.submit') }}" method="POST">
        @csrf
        <div class="form-grid">
            <div class="form-field">
                <label><i class="fas fa-user-circle"></i> Last Name <span class="required">*</span></label>
                <input type="text" name="lname" value="{{ old('lname') }}" placeholder="e.g., Dela Cruz" required>
            </div>
            <div class="form-field">
                <label><i class="fas fa-user"></i> First Name <span class="required">*</span></label>
                <input type="text" name="fname" value="{{ old('fname') }}" placeholder="e.g., Maria" required>
            </div>
            <div class="form-field">
                <label><i class="fas fa-phone-alt"></i> Phone Number <span class="required">*</span></label>
                <input type="tel" name="pnumber" value="{{ old('pnumber') }}" placeholder="09123456789" required>
            </div>
            <div class="form-field">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com">
            </div>
            <div class="form-field full-width">
                <label><i class="fas fa-home"></i> Home Address <span class="required">*</span></label>
                <input type="text" name="address" value="{{ old('address') }}" placeholder="Street, City, Province" required>
            </div>
            <div class="form-field">
                <label><i class="fas fa-user-md"></i> Select Doctor <span class="required">*</span></label>
                <select name="doctor" required>
                    <option value="">-- Select a Doctor --</option>
                    <option value="Dr. Sarah Chen">👩‍⚕️ Dr. Sarah Chen (General Medicine)</option>
                    <option value="Dr. James Carter">👨‍⚕️ Dr. James Carter (Cardiology)</option>
                    <option value="Dr. Emily Watson">👩‍⚕️ Dr. Emily Watson (Pediatrics)</option>
                    <option value="Dr. Michael Reyes">👨‍⚕️ Dr. Michael Reyes (Dermatology)</option>
                    <option value="Dr. Lisa Park">👩‍⚕️ Dr. Lisa Park (Neurology)</option>
                </select>
            </div>
            <div class="form-field">
                <label><i class="fas fa-calendar-alt"></i> Preferred Date <span class="required">*</span></label>
                <input type="date" name="date" value="{{ old('date') }}" min="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-field">
                <label><i class="fas fa-clock"></i> Preferred Time <span class="required">*</span></label>
                <select name="time" required>
                    <option value="">-- Select Time --</option>
                    <option value="08:00">08:00 AM</option>
                    <option value="09:00">09:00 AM</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="13:00">01:00 PM</option>
                    <option value="14:00">02:00 PM</option>
                    <option value="15:00">03:00 PM</option>
                    <option value="16:00">04:00 PM</option>
                </select>
            </div>
            <input type="hidden" name="status" value="Waiting">
        </div>
        
        <div class="info-note">
            <i class="fas fa-info-circle"></i>
            <span>By submitting, you will be added to our queue. Please arrive 10 minutes before your scheduled time.</span>
        </div>
        
        <!-- THIS IS WHERE THE BUTTON GOES - FIXED LOCATION -->
        <div class="button-group">
            <button type="submit" class="btn-submit">
                <i class="fas fa-calendar-check"></i> Confirm Appointment
            </button>
            <a href="{{ route('client.queue.status') }}" class="btn-secondary">
                <i class="fas fa-chart-line"></i> View Queue Status
            </a>
        </div>
        <!-- END OF BUTTON GROUP -->
    </form>
</div>

<script>
    const dateInput = document.querySelector('input[name="date"]');
    if (dateInput && !dateInput.value) {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        dateInput.value = `${yyyy}-${mm}-${dd}`;
    }
    
    const phoneInput = document.querySelector('input[name="pnumber"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);
        });
    }
    
    async function fetchQueueCount() {
        try {
            const response = await fetch('/api/queue-count');
            const data = await response.json();
            document.getElementById('waitingCount').innerHTML = `<i class="fas fa-hourglass-half"></i> ${data.waiting || 0} patient(s) waiting`;
            const mins = (data.waiting || 0) * 15;
            document.getElementById('waitTime').innerHTML = mins < 30 ? '<strong>15-30 min</strong>' : `<strong>${mins}-${mins+15} min</strong>`;
        } catch (error) {
            document.getElementById('waitingCount').innerHTML = '<i class="fas fa-hourglass-half"></i> Queue ready';
        }
    }
    fetchQueueCount();
    setInterval(fetchQueueCount, 30000);
</script>
</body>
</html>