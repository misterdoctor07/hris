<?php
$id = $_SESSION['idno'];
$sqlProfile = mysqli_query($con, "SELECT * FROM employee_profile WHERE idno='$id'");
$profile = mysqli_fetch_array($sqlProfile);
$idno = $profile['idno'];

// Get today's date
$today = date('Y-m-d');

// Function to get date range based on the filter selected
function getDateRange($filter) {
    if ($filter == 'week') {
        $start = date('Y-m-d', strtotime('monday this week'));
        $end = date('Y-m-d', strtotime('sunday this week'));
    } elseif ($filter == 'month') {
        $start = date('Y-m-01');
        $end = date('Y-m-t');
    } else {
        $start = $end = date('Y-m-d'); // Default to today
    }
    return [$start, $end];
}

// Determine the time frame based on user selection
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    list($startDate, $endDate) = getDateRange($filter);
} elseif (isset($_GET['custom_start']) && isset($_GET['custom_end'])) {
    $startDate = $_GET['custom_start'];
    $endDate = $_GET['custom_end'];
} else {
    $filter = 'today'; // Default to today
    $startDate = $endDate = $today;
}

?>

<script type="text/javascript">
function SubmitDetails(){        
    return confirm('Do you wish to submit details?');        
}
</script>

<div class="row">
    <div class="col-lg-12">
        <h4 style="text-indent: 10px;"><a href="?main"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-users"></i> EMPLOYEE ATTENDANCE</h4>      
    </div>
</div>

<form class="form-horizontal style-form" method="GET">
    <input type="hidden" name="attendance">                        
    <div class="col-lg-12 mt">
        <div class="content-panel">
            <div class="panel-heading">                                
                <h4><i class="fa fa-calendar"></i> Attendance Options</h4>            
            </div>
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
                <div class="form-group">
                    <label class="col-sm-3 control-label">Custom Date Range</label>
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
        </div>
    </div>
</form>

<!-- Attendance Table -->
<div class="col-lg-12 mt">
    <div class="content-panel">
        <div class="panel-heading">
            <h4><i class="fa fa-clock-o"></i> Attendance (<?= date('F d, Y', strtotime($startDate)) . " to " . date('F d, Y', strtotime($endDate)); ?>)</h4>
        </div>
        <div class="panel-body">                                                            
            <table width="100%" class="table table-bordered">
                <tr>
                    <td align="center">DATE</td>
                    <td colspan="2" align="center">1ST SHIFT</td>
                    <td colspan="2" align="center">2ND SHIFT</td>
                </tr>
                <tr>
                    <td align="center"></td>
                    <td align="center">LOGIN</td>
                    <td align="center">LOGOUT</td>
                    <td align="center">LOGIN</td>
                    <td align="center">LOGOUT</td>
                </tr>
                <?php
                // Fetch attendance data based on the selected time frame
                $sqlAttendance = mysqli_query($con, "SELECT * FROM attendance WHERE logindate BETWEEN '$startDate' AND '$endDate' AND idno='$idno'");
                if (mysqli_num_rows($sqlAttendance) > 0) {
                    while ($attend = mysqli_fetch_array($sqlAttendance)) {
                        echo "<tr>";
                            echo "<td align='center'>" . date('F d, Y', strtotime($attend['logindate'])) . "</td>"; // Display attendance date
                            
                            // Format loginam and logoutam to 12-hour format
                            echo "<td align='center'>" . ($attend['loginam'] ? date('h:i A', strtotime($attend['loginam'])) : '-') . "</td>";
                            echo "<td align='center'>" . ($attend['logoutam'] ? date('h:i A', strtotime($attend['logoutam'])) : '-') . "</td>";
                            echo "<td align='center'>" . ($attend['loginpm'] ? date('h:i A', strtotime($attend['loginpm'])) : '-') . "</td>";
                            echo "<td align='center'>" . ($attend['logoutpm'] ? date('h:i A', strtotime($attend['logoutpm'])) : '-') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' align='center'>No attendance data found</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>

<?php
?>
