<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['idno'])) {
    die("<script>alert('Session expired. Please log in again.'); window.location='/index.php';</script>");
}

$idno = $_SESSION['idno'];
$sqlProfile = mysqli_query($con, "SELECT * FROM employee_profile WHERE idno='$idno'");
$profile = mysqli_fetch_array($sqlProfile);

// Get department info
$dept = mysqli_fetch_array(mysqli_query($con, "SELECT department FROM employee_details WHERE idno='$idno'"));
$department = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM department WHERE id='{$dept['department']}'"));

// Date range handling
function getDateRange($filter) {
    if ($filter == 'week') {
        $start = date('Y-m-d', strtotime('monday this week'));
        $end = date('Y-m-d', strtotime('sunday this week'));
    } elseif ($filter == 'month') {
        $start = date('Y-m-01');
        $end = date('Y-m-t');
    } else {
        $start = $end = date('Y-m-d');
    }
    return [$start, $end];
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'today';
list($startDate, $endDate) = getDateRange($filter);

// Handle custom date range
if (isset($_GET['custom_start']) && isset($_GET['custom_end'])) {
    $startDate = $_GET['custom_start'];
    $endDate = $_GET['custom_end'];
}

// For calendar view
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Check if calendar view is requested
$showCalendar = isset($_GET['view']) && $_GET['view'] == 'calendar';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Attendance Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .calendar-container {
            margin: 20px auto;
            max-width: 1000px;
        }
        .dashboard-header {
            background-color: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
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
        .weekend { background-color: #e9ecef; }
        .month-navigation {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .attendance-table {
            margin-top: 20px;
        }
        .tab-content {
            padding: 15px;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
        }
        .view-calendar-btn {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h4 style="text-indent: 10px;">
                    <a href="?main"><i class="fa fa-arrow-left"></i> BACK</a> | 
                    <i class="fa fa-users"></i> EMPLOYEE ATTENDANCE
                </h4>      
            </div>
        </div>

        <div class="dashboard-header">
            <h2><?php echo $profile['lastname'].', '.$profile['firstname']; ?></h2>
            <p>Department: <?php echo $department['department']; ?></p>
        </div>

        <?php if (!$showCalendar): ?>
            <!-- Table View (default) -->
            <div class="view-calendar-btn">
                <a href="?attendance&view=calendar" class="btn btn-primary">
                    <i class="fa fa-calendar-alt"></i> View in Calendar
                </a>
            </div>

            <form class="form-horizontal style-form" method="GET">
                <input type="hidden" name="attendance">                        
                <div class="panel-body">
                    <!-- Buttons for date filtering -->
                    <div class="form-group">
                        <div class="col-sm-12">
                            <a href="?attendance&filter=today" class="btn btn-primary">Today</a>
                            <a href="?attendance&filter=week" class="btn btn-info">Current Week</a>
                            <a href="?attendance&filter=month" class="btn btn-success">Current Month</a>
                        </div>
                    </div>

                    <!-- Custom date range form -->
                    <div class="form-group row mt-3">
                        <label class="col-sm-3 col-form-label">Custom Date Range</label>
                        <div class="col-sm-3">
                            <input type="date" class="form-control" name="custom_start" value="<?= $startDate ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="date" class="form-control" name="custom_end" value="<?= $endDate ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="submit" name="submit" class="btn btn-primary" value="View">
                        </div>
                    </div>
                </div>
            </form>

            <!-- Attendance Table -->
            <div class="attendance-table">
                <div class="panel-heading">
                    <h4><i class="fa fa-clock-o"></i> Attendance (<?= date('F d, Y', strtotime($startDate)) . " to " . date('F d, Y', strtotime($endDate)); ?>)</h4>
                </div>
                <div class="panel-body">                                                            
                    <table width="100%" class="table table-bordered">
                        <tr>
                            <td align="center">DATE</td>
                            <td colspan="2" align="center">1ST SHIFT</td>
                            <td colspan="2" align="center">2ND SHIFT</td>
                            <td align="center">REMARKS</td>
                        </tr>
                        <tr>
                            <td align="center"></td>
                            <td align="center">LOGIN</td>
                            <td align="center">LOGOUT</td>
                            <td align="center">LOGIN</td>
                            <td align="center">LOGOUT</td>
                            <td align="center"></td>
                        </tr>
                        <?php
                        $sqlAttendance = mysqli_query($con, 
                            "SELECT * FROM attendance 
                             WHERE logindate BETWEEN '$startDate' AND '$endDate' 
                             AND idno='$idno'
                             ORDER BY logindate ASC");
                             
                        if (mysqli_num_rows($sqlAttendance) > 0) {
                            while ($attend = mysqli_fetch_array($sqlAttendance)) {
                                echo "<tr>";
                                    echo "<td align='center'>" . date('F d, Y (D)', strtotime($attend['logindate'])) . "</td>";
                                    echo "<td align='center'>" . ($attend['loginam'] ? date('h:i A', strtotime($attend['loginam'])) : '-') . "</td>";
                                    echo "<td align='center'>" . ($attend['logoutam'] ? date('h:i A', strtotime($attend['logoutam'])) : '-') . "</td>";
                                    echo "<td align='center'>" . ($attend['loginpm'] ? date('h:i A', strtotime($attend['loginpm'])) : '-') . "</td>";
                                    echo "<td align='center'>" . ($attend['logoutpm'] ? date('h:i A', strtotime($attend['logoutpm'])) : '-') . "</td>";
                                    echo "<td align='center'>" . $attend['remarks'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' align='center'>No attendance data found</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>

        <?php else: ?>
            <!-- Calendar View (only shown when specifically requested) -->
            <div class="view-calendar-btn">
                <a href="?attendance" class="btn btn-primary">
                    <i class="fa fa-table"></i> Back to Table View
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
                <div class="month-indicator text-center mb-3">
                    <h3 id="current-month-display"><?php echo date('F Y', strtotime("$year-$month-01")); ?></h3>
                </div>
                  <div id="calendar"></div>
            </div>
<?php endif; ?>
</div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
     <?php if ($showCalendar): ?>
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
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: 'get_attendance.php',
                        type: 'GET',
                        data: {
                            idno: '<?php echo $idno; ?>',
                            start: fetchInfo.startStr,
                            end: fetchInfo.endStr
                        },
                        success: function(response) {
                            var events = [];
                            response.forEach(function(record) {
                                // Create separate events for each time entry
                                if (record.loginam) {
                                    events.push({
                                        title: 'AM Login: ' + formatTimeForDisplay(record.loginam),
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
                                    events.push({
                                        title: 'Lunch Out: ' + formatTimeForDisplay(record.logoutam),
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
                                    events.push({
                                        title: 'PM Login: ' + formatTimeForDisplay(record.loginpm),
                                        start: record.logindate + 'T' + record.loginpm,
                                        color: '#28a745',
                                        display: 'list-item',
                                        extendedProps: {
                                            type: 'login',
                                            time: record.loginpm
                                        }
                                    });
                                }
                                
                                if (record.logoutpm) {
                                    events.push({
                                        title: 'Logout: ' + formatTimeForDisplay(record.logoutpm),
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
                                events.push({
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
                            successCallback(events);
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
                    
                    if (info.event.allDay) {
                        // For all-day events (main status events)
                        var details = [
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
                        var details = [
                            '<strong>Date:</strong> ' + new Date(dateStr).toLocaleDateString(),
                            '<strong>Event:</strong> ' + info.event.title,
                            '<strong>Time:</strong> ' + formatTime(info.event.extendedProps.time)
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
                    window.history.replaceState({}, '', '?month=' + month + '&year=' + year + '&view=calendar');
                }
            });
            
            calendar.render();

            function getEventTitle(record) {
                if (record.remarks == 'P') return "Present";
                if (record.remarks.startsWith('CI-')) return "CI: " + record.remarks.substring(3);
                if (['VL', 'SL', 'PTO'].includes(record.remarks)) return "Leave: " + record.remarks;
                return record.remarks;
            }

            function getEventColor(remarks) {
                if (remarks == 'P') return '#28a745';
                if (remarks.startsWith('CI-')) return '#dc3545';
                if (['VL', 'SL', 'PTO'].includes(remarks)) return '#ffc107';
                return '#6c757d';
            }

            function formatTime(timeStr) {
                if (!timeStr || timeStr == '0') return '-';
                var time = new Date('1970-01-01T' + timeStr + 'Z');
                return time.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            }
            
            function formatTimeForDisplay(timeStr) {
                if (!timeStr || timeStr == '0') return '';
                var time = new Date('1970-01-01T' + timeStr + 'Z');
                return time.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', hour12: true});
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
    <?php endif; ?>
</body>
</html>