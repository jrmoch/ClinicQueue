<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="refresh" content="30">
    <title>ClinicQueue | Your Queue Status</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #d4e6fa 0%, #eef4fc 100%);
            min-height: 100vh;
            padding: 2rem 1.5rem;
            color: #1a2c3e;
        }
        .status-container { max-width: 800px; margin: 0 auto; }
        .status-header {
            background: linear-gradient(120deg, #c5e0fa 0%, #d9edff 100%);
            padding: 1.6rem 2rem;
            border-radius: 1.5rem 1.5rem 0 0;
            text-align: center;
        }
        .status-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0a3b5c;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .status-header h1 i { color: #2c7da0; }
        .status-header p { margin-top: 0.5rem; color: #2c6079; }
        .search-card {
            background: white;
            padding: 2rem;
            border-radius: 0 0 1.5rem 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        .search-form { display: flex; gap: 1rem; flex-wrap: wrap; }
        .search-input {
            flex: 1;
            padding: 0.8rem 1rem;
            border: 1.5px solid #deeaf3;
            border-radius: 1rem;
            font-size: 0.95rem;
        }
        .search-input:focus {
            outline: none;
            border-color: #6bb0e0;
            box-shadow: 0 0 0 3px rgba(107, 176, 224, 0.2);
        }
        .search-btn {
            background: #549fd6;
            border: none;
            padding: 0.8rem 1.8rem;
            border-radius: 1rem;
            color: white;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .search-btn:hover { background: #3f86bd; }
        .search-note { margin-top: 0.8rem; font-size: 0.75rem; color: #6c94aa; text-align: center; }
        .appointment-card {
            background: white;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }
        .status-banner {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .status-banner.Waiting { background: #fff3db; border-bottom: 3px solid #f39c12; }
        .status-banner.Ongoing { background: #d9edfc; border-bottom: 3px solid #3498db; }
        .status-banner.Finished { background: #dcfce7; border-bottom: 3px solid #27ae60; }
        .status-banner.Cancelled { background: #fee2e2; border-bottom: 3px solid #e74c3c; }
        .status-label { display: flex; align-items: center; gap: 0.5rem; font-size: 1.1rem; font-weight: 600; }
        .queue-position {
            background: rgba(0, 0, 0, 0.1);
            padding: 0.3rem 0.8rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .appointment-details { padding: 1.5rem; }
        .detail-row { display: flex; padding: 0.8rem 0; border-bottom: 1px solid #eef2f6; }
        .detail-label { width: 130px; font-weight: 600; color: #1e5a7a; display: flex; align-items: center; gap: 0.5rem; }
        .detail-value { flex: 1; color: #1f405b; }
        .no-appointment {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 1.5rem;
            color: #6c94aa;
        }
        .no-appointment i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }
        .live-stats {
            background: white;
            border-radius: 1.5rem;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }
        .stat-item { text-align: center; flex: 1; }
        .stat-number { font-size: 1.5rem; font-weight: 700; color: #2c7da0; }
        .stat-label { font-size: 0.7rem; text-transform: uppercase; color: #6c94aa; }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            color: #2c7da0;
            text-decoration: none;
        }
        .readonly-badge {
            background: #e2f0fe;
            padding: 0.2rem 0.6rem;
            border-radius: 1rem;
            font-size: 0.65rem;
        }
        .auto-refresh { font-size: 0.7rem; color: #6c94aa; text-align: center; margin-top: 1rem; }
        @media (max-width: 640px) {
            body { padding: 1rem; }
            .detail-label { width: 100px; font-size: 0.8rem; }
        }
    </style>
</head>
<body>

<div class="status-container">
    <div class="status-header">
        <h1><i class="fas fa-chart-line"></i> Queue Status</h1>
        <p>Check your appointment status and queue position</p>
    </div>

    <div class="search-card">
        <form method="GET" action="{{ url('/queue-status') }}" class="search-form">
            <input type="text" name="reference" class="search-input" placeholder="Enter your phone number or email" value="{{ $reference ?? '' }}" autocomplete="off">
            <button type="submit" class="search-btn"><i class="fas fa-search"></i> Find</button>
        </form>
        <div class="search-note"><i class="fas fa-info-circle"></i> Enter the phone number you used when booking</div>
    </div>

    @if($reference)
        @if($appointment)
            <div class="appointment-card">
                <div class="status-banner {{ $appointment->Status }}">
                    <div class="status-label">
                        @if($appointment->Status == 'Waiting')
                            <i class="fas fa-hourglass-half"></i> Status: Waiting
                        @elseif($appointment->Status == 'Ongoing')
                            <i class="fas fa-stethoscope"></i> Status: In Consultation
                        @elseif($appointment->Status == 'Finished')
                            <i class="fas fa-check-circle"></i> Status: Finished
                        @elseif($appointment->Status == 'Cancelled')
                            <i class="fas fa-ban"></i> Status: Cancelled
                        @else
                            <i class="fas fa-calendar-check"></i> Status: {{ $appointment->Status }}
                        @endif
                    </div>
                    <div class="readonly-badge"><i class="fas fa-eye"></i> View Only</div>
                    @if($appointment->Status == 'Waiting' && $queuePosition)
                        <div class="queue-position"><i class="fas fa-hashtag"></i> Queue Position: {{ $queuePosition }}</div>
                    @endif
                </div>

                <div class="appointment-details">
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-user"></i> Patient Name</div>
                        <div class="detail-value">{{ $appointment->Patient_FN }} {{ $appointment->Patient_LN }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-phone-alt"></i> Phone Number</div>
                        <div class="detail-value">{{ $appointment->phone_number }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-envelope"></i> Email</div>
                        <div class="detail-value">{{ $appointment->email ?? 'Not provided' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-user-md"></i> Doctor</div>
                        <div class="detail-value">{{ $appointment->Doctor_Assigned }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-calendar-alt"></i> Date</div>
                        <div class="detail-value">{{ date('F j, Y', strtotime($appointment->Date)) }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-clock"></i> Time Slot</div>
                        <div class="detail-value">{{ date('g:i A', strtotime($appointment->Time_slot)) }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label"><i class="fas fa-home"></i> Address</div>
                        <div class="detail-value">{{ $appointment->HomeAddress }}</div>
                    </div>
                </div>

                @if($appointment->Status == 'Waiting')
                <div style="background: #fff3db; padding: 1rem; text-align: center;">
                    <i class="fas fa-info-circle"></i>
                    <strong>You are in queue position #{{ $queuePosition }}</strong><br>
                    <small>Please arrive 10 minutes before your scheduled time. Page refreshes every 30 seconds.</small>
                </div>
                @elseif($appointment->Status == 'Ongoing')
                <div style="background: #d9edfc; padding: 1rem; text-align: center;">
                    <i class="fas fa-stethoscope"></i>
                    <strong>The doctor is now attending to you!</strong>
                </div>
                @elseif($appointment->Status == 'Finished')
                <div style="background: #dcfce7; padding: 1rem; text-align: center;">
                    <i class="fas fa-check-circle"></i>
                    <strong>Your consultation is complete. Thank you!</strong>
                </div>
                @endif
            </div>
        @else
            <div class="no-appointment">
                <i class="fas fa-calendar-times"></i>
                <h3>No appointment found</h3>
                <p>We couldn't find an appointment with "{{ $reference }}"</p>
                <a href="{{ route('client.book') }}" style="color: #2c7da0;">Book a new appointment →</a>
            </div>
        @endif
    @endif

    <div class="live-stats">
        <div class="stat-item">
            <div class="stat-number" id="waitingCount">--</div>
            <div class="stat-label"><i class="fas fa-hourglass-half"></i> WAITING</div>
        </div>
        <div class="stat-item">
            <div class="stat-number" id="ongoingCount">--</div>
            <div class="stat-label"><i class="fas fa-stethoscope"></i> ONGOING</div>
        </div>
        <div class="stat-item">
            <div class="stat-number" id="finishedCount">--</div>
            <div class="stat-label"><i class="fas fa-check-circle"></i> TODAY'S DONE</div>
        </div>
    </div>

    <div class="auto-refresh"><i class="fas fa-sync-alt"></i> Page auto-refreshes every 30 seconds</div>
    <a href="{{ route('client.book') }}" class="back-link"><i class="fas fa-calendar-plus"></i> Book a new appointment</a>
</div>

<script>
    function fetchQueueStats() {
        fetch('/api/queue-count')
            .then(response => response.json())
            .then(data => {
                document.getElementById('waitingCount').innerText = data.waiting || 0;
                document.getElementById('ongoingCount').innerText = data.ongoing || 0;
            })
            .catch(error => console.log('Error:', error));
        
        fetch('/api/today-finished')
            .then(response => response.json())
            .then(data => {
                document.getElementById('finishedCount').innerText = data.finished || 0;
            })
            .catch(error => console.log('Error:', error));
    }
    
    fetchQueueStats();
    setInterval(fetchQueueStats, 30000);
</script>
</body>
</html>