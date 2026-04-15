<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClinicQueue | Appointment Management System</title>
    
    <!-- Google Fonts for clean typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 (free icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="clinic-container">
    <!-- Header Section - Light blue queue themed -->
    <div class="queue-header">
        <div class="header-title">
            <div class="title-section">
                <h1>
                    <i class="fas fa-notes-medical"></i> 
                    Appointment Queue · Clinic Manager
                </h1>
            </div>
            <div class="queue-stats">
                @php
                    $waitingCount = isset($data) ? $data->where('Status', 'Waiting')->count() : 0;
                    $ongoingCount = isset($data) ? $data->where('Status', 'Ongoing')->count() : 0;
                    $finishedCount = isset($data) ? $data->where('Status', 'Finished')->count() : 0;
                @endphp
                <div class="stat-badge"><i class="fas fa-hourglass-half"></i> Waiting: {{ $waitingCount }} | Ongoing: {{ $ongoingCount }}</div>
                <div class="stat-badge"><i class="fas fa-chalkboard-user"></i> Finished: {{ $finishedCount }}</div>
            </div>
        </div>
        <div class="subhead">
            <span><i class="fas fa-stethoscope"></i> Real-time patient flow </span>
            <span><i class="fas fa-arrow-right"></i> Manage appointments, update statuses, or delete entries</span>
        </div>
    </div>

    <!-- Queue Live Indicator (simulated clinic status) -->
    <div class="queue-live">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <div class="live-dot"></div>
            <span><i class="fas fa-chart-simple"></i> <strong>Queue overview</strong> — waiting patients ready for consultation</span>
        </div>
        <div class="live-indicator">
            <span><i class="fas fa-users"></i> Today's activity</span>
        </div>
    </div>

    <!-- Main Table Area -->
    <div class="table-wrapper">
        <table class="appointment-table">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> ID</th>
                    <th><i class="fas fa-user"></i> First Name</th>
                    <th><i class="fas fa-user-friends"></i> Last Name</th>
                    <th><i class="fas fa-phone-alt"></i> Phone</th>
                    <th><i class="fas fa-envelope"></i> Email</th>
                    <th><i class="fas fa-home"></i> Address</th>
                    <th><i class="fas fa-user-md"></i> Doctor</th>
                    <th><i class="fas fa-calendar-day"></i> Date</th>
                    <th><i class="fas fa-clock"></i> Time</th>
                    <th><i class="fas fa-chart-line"></i> Status</th>
                    <th><i class="fas fa-cog"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $appt)
                <tr>
                    <td>{{ $appt->appt_id }}</td>
                    <td>{{ $appt->Patient_FN }}</td>
                    <td>{{ $appt->Patient_LN }}</td>
                    <td>{{ $appt->phone_number }}</td>
                    <td>{{ $appt->email }}</td>
                    <td>{{ $appt->HomeAddress }}</td>
                    <td>{{ $appt->Doctor_Assigned }}</td>
                    <td>{{ $appt->Date }}</td>
                    <td>{{ $appt->Time_slot }}</td>
                    <td>
                        @php
                            $statusClass = '';
                            switch($appt->Status) {
                                case 'Available': $statusClass = 'status-Available'; break;
                                case 'Waiting': $statusClass = 'status-Waiting'; break;
                                case 'Ongoing': $statusClass = 'status-Ongoing'; break;
                                case 'Finished': $statusClass = 'status-Finished'; break;
                                case 'Cancelled': $statusClass = 'status-Cancelled'; break;
                                default: $statusClass = 'status-Available';
                            }
                        @endphp
                        <span class="status-badge {{ $statusClass }}">
                            @if($appt->Status == 'Available') <i class="fas fa-calendar-check"></i>
                            @elseif($appt->Status == 'Waiting') <i class="fas fa-hourglass-half"></i>
                            @elseif($appt->Status == 'Ongoing') <i class="fas fa-stethoscope"></i>
                            @elseif($appt->Status == 'Finished') <i class="fas fa-check-circle"></i>
                            @elseif($appt->Status == 'Cancelled') <i class="fas fa-ban"></i>
                            @endif
                            {{ $appt->Status }}
                        </span>
                    </td>
                    <td class="action-group">
                        <a href="/appts/{{ $appt->appt_id }}/edit" class="btn-edit"><i class="fas fa-pen-alt"></i> Edit</a>
                        <form action="/appts/{{ $appt->appt_id }}" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete"><i class="fas fa-trash-alt"></i> Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="11">
                        <i class="fas fa-calendar-times"></i> No appointments found. Click "Add New Appointment" to create one.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer with Add New Appointment button -->
    <div class="footer-action">
        <a href="{{ route('pages.create') }}" class="btn-add">
            <i class="fas fa-plus-circle"></i> Add New Appointment
        </a>
    </div>
</div>

</body>
</html>