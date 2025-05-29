<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['idno'])) {
    die("<script>alert('Session expired. Please log in again.'); window.location='/index.php';</script>");
}

$idno = $_SESSION['idno'];

// For calendar view
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Attendance Calendar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 0 auto;
            max-width: 95%;
        }

        .calendar-container {
            margin: 20px auto;
            max-width: 1000px;
        }
        
        .stat-card {
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .present { background-color: #d4edda; }
        .ci { background-color: #f8d7da; }
        .leave { background-color: #fff3cd; }
        .holiday { background-color: #e2e3f3; } /* New holiday color */
        
        .month-indicator {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .fc .fc-button {
            text-transform: capitalize;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="view-calendar-btn">
            <a href="?main" class="btn btn-primary">
                <i class="fa fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="stat-card bg-light">
                    <h5>Current Month</h5>
                    <h3><?php echo date('F Y', strtotime("$year-$month-01")); ?></h3>
                </div>
            </div>
            
            <?php
            // Get attendance stats for the month
            $month_start = "$year-$month-01";
            $month_end = date('Y-m-t', strtotime($month_start));
            
            $present_count = mysqli_fetch_array(mysqli_query($con, 
                "SELECT COUNT(*) as count FROM attendance 
                 WHERE idno='$idno' 
                 AND logindate BETWEEN '$month_start' AND '$month_end'
                 AND remarks='P'"))['count'];
                 
            $ci_count = mysqli_fetch_array(mysqli_query($con, 
                "SELECT COUNT(*) as count FROM attendance 
                 WHERE idno='$idno' 
                 AND logindate BETWEEN '$month_start' AND '$month_end'
                 AND remarks LIKE 'CI-%'"))['count'];
                 
            $leave_count = mysqli_fetch_array(mysqli_query($con, 
                "SELECT COUNT(*) as count FROM attendance 
                 WHERE idno='$idno' 
                 AND logindate BETWEEN '$month_start' AND '$month_end'
                 AND remarks IN ('VL', 'SL', 'PTO')"))['count'];
            ?>
            
            <div class="col-md-3">
                <div class="stat-card present">
                    <h5>Present</h5>
                    <h3><?php echo $present_count; ?></h3>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-card ci">
                    <h5>Code Infractions</h5>
                    <h3><?php echo $ci_count; ?></h3>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-card leave">
                    <h5>Leaves</h5>
                    <h3><?php echo $leave_count; ?></h3>
                </div>
            </div>
        </div>

        <div class="calendar-container">
            <div class="month-indicator">
                <h3 id="current-month-display" style="color:#fff;"></h3>
            </div>
            <div id="calendar"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize calendar
            var calendarEl = document.getElementById('calendar');
            var currentMonthDisplay = document.getElementById('current-month-display');
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                initialDate: '<?php echo "$year-$month-01"; ?>',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    // Array to hold all events
                    var allEvents = [];
                    
                    // First fetch attendance data
                    $.ajax({
                        url: 'get_attendance.php',
                        type: 'GET',
                        data: {
                            idno: '<?php echo $idno; ?>',
                            start: fetchInfo.startStr,
                            end: fetchInfo.endStr
                        },
                        success: function(attendanceResponse) {
                            attendanceResponse.forEach(function(record) {
                                // Create separate events for each time entry
                                if (record.loginam) {
                                    allEvents.push({
                                        title: 'Log In',
                                        start: record.logindate + 'T' + record.loginam,
                                        color: '#28a745',
                                        display: 'list-item',
                                        extendedProps: {
                                            type: 'login',
                                            time: record.loginam
                                        }
                                    });
                                }
                                
                                if (record.logoutam) {
                                    allEvents.push({
                                        title: 'Lunch Out',
                                        start: record.logindate + 'T' + record.logoutam,
                                        color: '#ffc107',
                                        display: 'list-item',
                                        extendedProps: {
                                            type: 'lunch-out',
                                            time: record.logoutam
                                        }
                                    });
                                }
                                
                                if (record.loginpm) {
                                    allEvents.push({
                                        title: 'Lunch In',
                                        start: record.logindate + 'T' + record.loginpm,
                                        color: '#5bc0de',
                                        display: 'list-item',
                                        extendedProps: {
                                            type: 'login',
                                            time: record.loginpm
                                        }
                                    });
                                }
                                
                                if (record.logoutpm) {
                                    allEvents.push({
                                        title: 'Logout',
                                        start: record.logindate + 'T' + record.logoutpm,
                                        color: '#dc3545',
                                        display: 'list-item',
                                        extendedProps: {
                                            type: 'logout',
                                            time: record.logoutpm
                                        }
                                    });
                                }
                                
                                // Main event showing the day's status
                                allEvents.push({
                                    title: getEventTitle(record),
                                    start: record.logindate,
                                    allDay: true,
                                    color: getEventColor(record.remarks),
                                    extendedProps: {
                                        loginam: record.loginam,
                                        logoutam: record.logoutam,
                                        loginpm: record.loginpm,
                                        logoutpm: record.logoutpm,
                                        remarks: record.remarks
                                    }
                                });
                            });
                            
                            // Now fetch holidays data
                            $.ajax({
                                url: 'get_holidays.php',
                                type: 'GET',
                                data: {
                                    start: fetchInfo.startStr,
                                    end: fetchInfo.endStr
                                },
                                success: function(holidaysResponse) {
                                    holidaysResponse.forEach(function(holiday) {
                                        allEvents.push({
                                            title: holiday.type === 'rh' ? 'Holiday: ' + holiday.description : 'Special Holiday: ' + holiday.description,
                                            start: holiday.date,
                                            allDay: true,
                                            color: '#6f42c1', // Purple color for holidays
                                            extendedProps: {
                                                type: 'holiday',
                                                holidayType: holiday.type,
                                                description: holiday.description,
                                                location: holiday.location
                                            }
                                        });
                                    });
                                    
                                    // Return all events combined
                                    successCallback(allEvents);
                                },
                                error: function() {
                                    // Even if holidays fail, return attendance data
                                    successCallback(allEvents);
                                }
                            });
                        },
                        error: function() {
                            failureCallback();
                        }
                    });
                },
                eventDidMount: function(info) {
                    // Style all-day events differently
                    if (info.event.allDay) {
                        info.el.style.fontWeight = 'bold';
                        info.el.style.fontSize = '1.1em';
                    }
                },
                eventClick: function(info) {
                    var dateStr = info.event.startStr;
                    var title = info.event.title;
                    var details = '';
                    
                    if (info.event.extendedProps.type === 'holiday') {
                        // For holiday events
                        details = [
                            '<strong>Date:</strong> ' + new Date(dateStr).toLocaleDateString(),
                            '<strong>Type:</strong> ' + (info.event.extendedProps.holidayType === 'rh' ? 'Regular Holiday' : 'Special Non-Working Holiday'),
                            '<strong>Location:</strong> ' + info.event.extendedProps.location,
                            '<strong>Description:</strong> ' + info.event.extendedProps.description
                        ].join('<br>');
                    } 
                    else if (info.event.allDay) {
                        // For all-day events (main status events)
                        details = [
                            '<strong>Date:</strong> ' + new Date(dateStr).toLocaleDateString(),
                            '<strong>Status:</strong> ' + info.event.extendedProps.remarks,
                            '<hr>',
                            '<strong>AM Login:</strong> ' + (info.event.extendedProps.loginam ? formatTime(info.event.extendedProps.loginam) : '-'),
                            '<strong>Lunch Out:</strong> ' + (info.event.extendedProps.logoutam ? formatTime(info.event.extendedProps.logoutam) : '-'),
                            '<strong>PM Login:</strong> ' + (info.event.extendedProps.loginpm ? formatTime(info.event.extendedProps.loginpm) : '-'),
                            '<strong>Logout:</strong> ' + (info.event.extendedProps.logoutpm ? formatTime(info.event.extendedProps.logoutpm) : '-')
                        ].join('<br>');
                    } else {
                        // For time-specific events
                        details = [
                            '<strong>Date:</strong> ' + new Date(dateStr).toLocaleDateString(),
                            '<strong>Event:</strong> ' + info.event.title
                        ].join('<br>');
                    }
                    
                    // Use Bootstrap modal for better display
                    $('#eventModal .modal-body').html(details);
                    $('#eventModal').modal('show');
                },
                datesSet: function(info) {
                    var date = info.view.currentStart;
                    var month = date.getMonth() + 1;
                    var year = date.getFullYear();
                    
                    // Update the month indicator
                    currentMonthDisplay.textContent = date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                    
                    // Update URL without reloading
                    window.history.replaceState({}, '', '?month=' + month + '&year=' + year);
                }
            });
            
            calendar.render();

            function getEventTitle(record) {
                if (record.remarks == 'P') return "Present";
                if (record.remarks.startsWith('Code A')) return "CI: Without supporting details " + record.remarks.substring(3);
                if (record.remarks.startsWith('AA')) return "Authorize Absence " + record.remarks.substring(3);
                if (record.remarks.endsWith('-D')) return "Time-D: " + record.remarks.substring(0, record.remarks.length - 2);
                if (['VL', 'SL', 'PTO', 'SL-A'].includes(record.remarks)) return "Leave: " + record.remarks;
                return record.remarks;
            }
            
            function getEventColor(remarks) {
                if (remarks == 'P') return '#28a745';
                if (remarks.startsWith('CI-')) return '#dc3545';
                if (remarks.startsWith('AA')) return '#dc3545';
                if (remarks.endsWith('-D')) return '#dc3545';
                if (['VL', 'SL', 'PTO', 'SL-A'].includes(remarks)) return '#ffc107';
                return '#6c757d';
            }

            function formatTime(timeStr) {
                if (!timeStr || timeStr == '0') return '-';
                var time = new Date('1970-01-01T' + timeStr + 'Z');
                return time.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            }
            
            function formatTimeForDisplay(timeStr) {
                if (!timeStr || timeStr === '0') return '';
                const [hour, minute, second] = timeStr.split(':');
                const date = new Date();
                date.setHours(parseInt(hour), parseInt(minute), parseInt(second || 0));
                return date.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }
        });
    </script>
    
    <!-- Modal for event details -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Attendance Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Content will be inserted here by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>