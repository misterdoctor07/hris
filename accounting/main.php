<?php
// Define holidays (you can also fetch this from a database)
$holidays = [
  
];

// Get ID from GET request
$id = $_GET['id'] ?? null;



// Fetch attendance data
$sqlAttendance = mysqli_query($con, "SELECT * FROM attendance WHERE id='$id'");
if (mysqli_num_rows($sqlAttendance) > 0) {
    $attend = mysqli_fetch_array($sqlAttendance);
    $loginam = date('H:i', strtotime($attend['loginam']));
    $logoutam = date('H:i', strtotime($attend['logoutam']));
    $loginpm = date('H:i', strtotime($attend['loginpm']));
    $logoutpm = date('H:i', strtotime($attend['logoutpm']));
    $logindate = $attend['logindate'];
    $status = $attend['status'];
    $remarks = $attend['remarks'];
} else {
    $loginam = "";
    $logoutam = "";
    $loginpm = "";
    $logoutpm = "";
    $logindate = $_GET['logindate'] ?? date('Y-m-d'); // Default to current date if not set
    $status = "";
    $remarks = "";
}

// Initialize variables
$work = $rh = $snwh = $nd = $leave = $ot = $pt = "";
if ($remarks == "") {
    $remarks = "P";
}

// Check if the date is a holiday and set the corresponding status
if (isset($holidays[$logindate])) {
    if ($holidays[$logindate] == 'rh') {
        $rh = "checked";
    } elseif ($holidays[$logindate] == 'snwh') {
        $snwh = "checked";
    }
}

// Parse the status and set the appropriate checks
$stat = explode('/', $status);
for ($i = 0; $i < sizeof($stat); $i++) {
    if ($stat[$i] == "work") {
        $work = "checked";
    }
    if ($stat[$i] == "nd") {
        $nd = "checked";
    }
    if ($stat[$i] == "leave") {
        $leave = "checked";
    }
    if ($stat[$i] == "ot") {
        $ot = "checked";
    }
    if ($stat[$i] == "pt") {
        $pt = "checked";
    }
}

// Function to check if a date is a holiday
function checkHoliday($date, $holidays) {
    return $holidays[$date] ?? 'Regular Day';
}

// Example for today's date
$today = date('Y-m-d');
$todayHolidayStatus = checkHoliday($today, $holidays);
?>
 <?php
        // Fetch holidays from the database
        $holidays = [];
        $result = mysqli_query($con, "SELECT * FROM holidays");

        // Populate the $holidays array
        while ($row = mysqli_fetch_assoc($result)) {
            $holidays[$row['date']] = [
                'type' => $row['type'],
                'description' => $row['description']
            ];
        }

        // Check if today is a holiday
        $today = date('Y-m-d');
        if (isset($holidays[$today])) {
            $todayHoliday = $holidays[$today];
            $holidayLabel = $todayHoliday['type'] === 'rh' ? 'Regular Holiday' : 'Special Non-Working Holiday';
            $description = $todayHoliday['description']; // Set description for today
        } else {
            $holidayLabel = 'No Holiday';
            $description = 'Regular Day'; // Default description
        }

        // Debug: Display holiday and description info
        // Uncomment the line below if needed for testing
        // var_dump($holidays, $today, $description);
        ?>
<div class="col-md-4 col-sm-2 mb">
    <div class="grey-panel pn donut-chart">
        <div class="grey-header">
            <!-- Display the description dynamically -->
            <h5 style="font-weight: bold; font-size: 18px;">
                <?php 
                // Check if a description is available
                echo !empty($description) ? htmlspecialchars($description) : 'No Description'; 
                ?>
            </h5>
        </div>
        <div class="row mt">
            <i class="fa fa-calendar fa-5x"></i>
        </div>
       
        <h3><?php echo date('F j, Y'); ?></h3>
        <h4><?php echo $holidayLabel; ?></h4>
        <p>Today</p>
    </div>
    <!-- /grey-panel -->
</div>
    
          <div class="col-lg-12">
          <div class="border-head">
              <h3><?=$_SESSION['access'];?> DASHBOARD</h3>
            </div>
            <div class="row mt">
              <!-- SERVER STATUS PANELS -->
              <div class="col-md-4 col-sm-2 mb">
                <div class="grey-panel pn donut-chart">
                  <div class="grey-header">
                    <h5>NEWIND</h5>
                  </div>
                  <div class="row mt"><a href="viewemployeemasterlist.php?company=NEWIND" target="_blank"><i class="fa fa-users fa-5x"></i></a></div>
                  <?php
                    $sqlEmployee=mysqli_query($con,"SELECT ep.idno FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.company='NEWIND' AND ed.status NOT LIKE '%RESIGNED%'");
                    $regular=mysqli_num_rows($sqlEmployee);
                  ?>
                  <h3><?=$regular;?></h3>
                  Employees
                </div>
                <!-- /grey-panel -->
              </div>
              <!-- /col-md-4-->
              <div class="col-md-4 col-sm-2 mb">
                <div class="grey-panel pn donut-chart">
                  <div class="grey-header">
                    <h5>SOLUTIONS</h5>
                  </div>
                  <div class="row mt"><a href="viewemployeemasterlist.php?company=NESI1" target="_blank"><i class="fa fa-users fa-5x"></i></a></div>
                  <?php
                    $sqlEmployee=mysqli_query($con,"SELECT ep.idno FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.company='NESI1' AND ed.status NOT LIKE '%RESIGNED%'");
                    $probationary=mysqli_num_rows($sqlEmployee);
                  ?>
                  <h3><?=$probationary;?></h3>
                  Employees
                </div>
                <!-- /grey-panel -->
              </div>
              <div class="col-md-4 col-sm-2 mb">
                <div class="grey-panel pn donut-chart">
                  <div class="grey-header">
                    <h5>STRATEGIES</h5>
                  </div>
                  <div class="row mt"><a href="viewemployeemasterlist.php?company=NESI2" target="_blank"><i class="fa fa-users fa-5x"></i></a></div>
                  <?php
                    $sqlEmployee=mysqli_query($con,"SELECT ep.idno FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.company='NESI2' AND ed.status NOT LIKE '%RESIGNED%'");
                    $resigned=mysqli_num_rows($sqlEmployee);
                  ?>
                  <h3><?=$resigned;?></h3>
                  Employees
                </div>
                <!-- /grey-panel -->
              </div>              
              <!-- /col-md-4 -->
            </div>
          </div>
