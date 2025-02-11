<?php
date_default_timezone_set("Asia/Manila");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="Dashboard">
  <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Montserrat:400,700'><link rel="stylesheet" href="./style-clock.css">
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--external css-->
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="css/zabuto_calendar.css">
  <link rel="stylesheet" type="text/css" href="lib/gritter/css/jquery.gritter.css" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
  <script src="lib/chart-master/Chart.js"></script>
  <title>HRIS - North East Solutions Inc.</title>
  <style>
    /* Additional styles for positioning the button */
    #announcementButton {
      position: fixed; /* Fix the position relative to the viewport */
      bottom: 20px; /* Distance from the bottom */
      right: 20px; /* Distance from the right */
      z-index: 1000; /* Ensure it appears above other elements */
    }
    /* Heart container */
#heart-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none; /* Ensure clicks pass through */
  z-index: 1000; /* Make sure hearts appear above other content */
}

/* Individual hearts */
.heart {
  position: absolute;
  font-size: 24px; /* Adjust size as needed */
  color: red; /* Heart color */
  animation: fly 4s linear infinite; /* Animation */
  opacity: 0; /* Start invisible */
}

/* Keyframes for flying animation */
@keyframes fly {
  0% {
    opacity: 1;
    transform: translateY(100vh) translateX(-10vw); /* Start at bottom-left */
  }
  100% {
    opacity: 0;
    transform: translateY(-10vh) translateX(100vw); /* End at top-right */
  }
}

.attendance {
  transition: transform 0.2s ease-in-out; /* Smooth hover effect */
}

.attendance:hover {
  transform: scale(1.1); /* Slightly enlarge the button on hover */
}
/* Randomize heart positions and delays */
.heart:nth-child(1) { left: 10%; animation-delay: 0s; }
.heart:nth-child(2) { left: 20%; animation-delay: 1s; }
.heart:nth-child(3) { left: 30%; animation-delay: 2s; }
.heart:nth-child(4) { left: 40%; animation-delay: 3s; }
.heart:nth-child(5) { left: 50%; animation-delay: 4s; }
    
  </style>
  <!-- Favicons -->
  <!-- <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon"> -->
  <link rel="icon" type="image/x-icon" href="img/nesi.jpg">

  <!-- Bootstrap core CSS -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--external css-->
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
  <link href="https://fonts.cdnfonts.com/css/chinese-dragon" rel="stylesheet">
  <style>
                          @import url('https://fonts.cdnfonts.com/css/chinese-dragon');
                            
                            @import url('https://fonts.googleapis.com/css2?family=Lobster&display=swap');

                            @import url('https://fonts.googleapis.com/css2?family=Cookie&display=swap');
</style>
                
  <!-- =======================================================
    Template Name: Dashio
    Template URL: https://templatemag.com/dashio-bootstrap-admin-template/
    Author: TemplateMag.com
    License: https://templatemag.com/license/
  ======================================================= -->
</head>
<style>
  
  </style>
<body onload="getTime()">
  <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
     <!-- <div class="snow-container"></div> -->
     <div class="container" align="center">    
  <div class="date-container" style="font-size: 140px; text-transform: capitalize; font-family: 'Cookie', cursive; text-shadow: black; " >
  <p 
    style="
        text-shadow: 2px 2px 4px rgb(243, 243, 243);
        -webkit-text-stroke: 1px black;
        color:#ff4d4d;
    " 
    id="show-date"
></p>

  </div>
  <!--<div id="showtime" style="color:black; font-weight:bold  ; background-color:#f0ed49; width:35%; border-radius: 10%;"></div> -->
  <div class="clock-container">
  <div class="clock-time">
    <div class="clock-col">
      <p class="clock-hours clock-timer"></p>
      
    </div>
    <div class="clock-col">
      <p class="clock-minutes clock-timer"></p>
      
    </div>
    <div class="clock-col">
      <p class="clock-seconds clock-timer"></p>
      
    </div>
    <div class="clock-col">
      <p class="clock-ampm clock-timer"></p>
      
    </div>
  </div>
  <div class="clock-day-container">
    <p class="clock-day clock-timer"></p>
   
  </div>
</div>
  
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js'></script><script  src="./script-clock.js"></script>
<script>
    // Get the current date
    var currentDate = moment().format('MMMM D, YYYY');
    document.getElementById('show-date').innerHTML = currentDate;

    // Update the date in real-time (optional)
    setInterval(function() {
      var currentDate = moment().format('MMMM D, YYYY');
      document.getElementById('show-date').innerHTML = currentDate;
    }, 1000); // update every 1 second
  </script>
  
<div class="lock-screen" style="margin-left: 140px; ">
          <table width="90%" >
		<tr>
              <td height="100" colspan="4" align="center" >               
              
                    
              </td>
            </tr>              
              <tr>
              <td>
              <h2>
    <a data-toggle="modal" href="#myModal" class="btn btn-primary attendance" data-id="loginam" data-title="LOG IN" style="background: transparent; border: none;">
      <img src="img/heart-image3.png" height="90" alt="Heart Image"> <!-- Replace with your heart image -->
      <br>
      <span style="color:rgb(0, 0, 0); font-family: 'Dancing Script', cursive; font-size: 26px; font-weight: bold;">LOGIN</span>
    </a>
  </h2>
                  </td>
                  <td>
                  <h2>
    <a data-toggle="modal" href="#myModal" class="btn btn-primary attendance" data-id="logoutam" data-title="LUNCH OUT" style="background: transparent; border: none;">
      <img src="img/heart-image3.png" height="90" alt="Heart Image">
      <br>
      <span style="color:rgb(0, 0, 0); font-size: 26px; font-family: 'Dancing Script', cursive; font-weight: bold;">LUNCH OUT</span>
    </a>
  </h2>                   
                  </td>
                  <td>
                  <h2>
    <a data-toggle="modal" href="#myModal" class="btn btn-primary attendance" data-id="loginpm" data-title="LUNCH IN" style="background: transparent; border: none;">
      <img src="img/heart-image3.png" height="90" alt="Heart Image">
      <br>
      <span style="color:rgb(0, 0, 0); font-family: 'Dancing Script', cursive; font-size: 26px; font-weight: bold;">LUNCH IN</span>
    </a>
  </h2>
                  </td>
                  <td>
                  <h2>
    <a data-toggle="modal" href="#myModal" class="btn btn-primary attendance" data-id="logoutpm" data-title="LOG OUT" style="background: transparent; border: none;">
      <img src="img/heart-image3.png" height="90" alt="Heart Image">
      <br>
      <span style="color:rgb(0, 0, 0); font-family: 'Dancing Script', cursive; font-size: 26px; font-weight: bold;">LOGOUT</span>
    </a>
  </h2>
                    <div class="top-right-button" style="position: absolute;  top: 20px; right: 20px;">
                    <a href="/hris/employeeportal/" class="btn btn-primary attendance" style="background:#ee4258; border-radius: 40px 40px; float: right; padding: 10px 20px; border: none;">
                           EMPLOYEE PORTAL
                 </a>
                    </div>
                      </h2>
                  </td>
                
                     <?php
                        // Assuming you have established a connection to the database
                        include('../config.php'); // Ensure this path is correct
                        mysqli_query($con, "SET NAMES 'utf8'");

                        // Get today's date in the correct format for comparison
                        $today = date('Y-m-d');

                        // Fetch all announcements posted today
                        $sqlWidgets = mysqli_query($con, "SELECT * FROM widgets WHERE `type`='Announcement' AND DATE(datearray) = '$today' ORDER BY datearray DESC, timearray DESC");

                        $announcements = []; // Array to hold announcements
                        while ($emp = mysqli_fetch_array($sqlWidgets)) {
                            $announcements[] = $emp['details']; // Fetch all details
                        }

                        $isDetails = !empty($announcements); // Check if there are any announcements
                        $unreadCount = count($announcements); // Count unread announcements
                        ?>

                        <div style="position: absolute; top: 10px; right: 10px;">
                            <button1 id="announcementButton" class="btn btn-primary" style="background:#f9d6dc; border-radius: 40px 40px; border: none; color: black; font-weight:bolder;">
                                Announcements 
                                <span id="notificationBadge" class="badge" style="background:red; color:white;"><?= $unreadCount ?></span>
                            </button1>
                        </div>

<!-- Announcement Modal -->
<div aria-hidden="true" aria-labelledby="announcementModalLabel" role="dialog1" tabindex="-1" id="announcementModal" class="modal fade">
    <div class="modal-dialog1">
        <div class="modal-content1">
            <div class="modal-header1">
                <button1 type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button1>
                <h4 class="modal-title1" style="color:antiquewhite;" >Announcements</h4>
            </div>
            <div class="modal-body" id="announcementBody">
             
                <?php if ($isDetails): ?>
                    <ul id="announcementList">
                      
                       <pre style="border:0; background-color:white;white-space:pre-wrap;">  <?php foreach ($announcements as $index => $announcement): ?>
                         
                            <li>
                                <p style=" font-size: 30px;"class="announcementText" ><?php echo htmlspecialchars($announcement); ?></p>
                            </li>
                           
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p style="color:antiquewhite;">No announcements available.</p>
                <?php endif; ?>
               </pre>
            </div>
            <div class="modal-footer">
                <button1 data-dismiss="modal" class="btn btn-theme04" type="button">Close</button1>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("announcementButton").onclick = function () {
        $('#announcementModal').modal('show');
    };

    // Handle the "Mark as Read" checkboxes
    const checkboxes = document.querySelectorAll(".markAsRead");
    const notificationBadge = document.getElementById("notificationBadge");
    let unreadCount = parseInt(notificationBadge.textContent); // Initial unread count

    checkboxes.forEach(checkbox => {
        checkbox.onclick = function () {
            const announcementText = this.previousElementSibling; // Get the associated announcement text
            if (this.checked) {
                announcementText.style.display = 'none'; // Hide the announcement text
                unreadCount--; // Decrease the unread count
                notificationBadge.textContent = unreadCount; // Update badge count
            } else {
                announcementText.style.display = 'block'; // Show the announcement text
                unreadCount++; // Increase the unread count
                notificationBadge.textContent = unreadCount; // Update badge count
            }
        };
    });
</script>
              </tr>
          </table>
          <br>
          <div id="remarksError" style="color:black; background:pink; border-radius:10px; font-size:18px;"></div>
          <?php
          if(isset($_GET['success'])){
              $name=$_GET['empname']; 
              $type=$_GET['type'];
          ?>
          <div id="remarksSuccess" style="color:black; background:lightblue; border-radius:10px; font-size:18px;">
          <?=$type;?>, <?=$name;?>!
          <script>
                    //alert('Success!');                                        
                    setTimeout(function(){ 
                        window.location="../attendance/";
                    }, 2000);
                </script></div>
                <?php } ?>
        <!-- Modal -->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color: #ffcccc">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"style="color:black; font-family: 'Dancing Script', cursive; font-size:70px; font-weight:bolder;">Attendance</h4>
              </div>
              <div class="modal-body" >
                  <form name="f1" method="POST">
                      <?php
                      $time=date('H:i:s');
                      ?>
                      <input type="hidden" name="logintype" id="caseno">
                      <input type="hidden" name="timenow" value="<?=date('H:i:s');?>">
                <p class="centered" id="caseno">Enter Employee ID Number</p>
                <input type="text"  placeholder="Employee ID No." autocomplete="off" class="form-control placeholder-no-fix" autofocus name="empid" required>
              </div>
              <div class="modal-footer centered">
                <button data-dismiss="modal" class="btn btn-theme04" type="button">Cancel</button>
                <button class="btn btn-theme03" type="submit" name="submit" onclick="return confirm('Are you sure this is your ID Number?'); return false;">Submit</button>
              </div>
              
                </form>
            </div>
          </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Script to Change Modal Title -->
<script>
  $(document).ready(function() {
    $('.attendance').click(function() {
      var actionTitle = $(this).data('title'); // Get the data-title attribute
      console.log('Button clicked: ' + actionTitle); // Debug: Check if the button click is captured
      $('#myModal .modal-title').text(actionTitle); // Set the modal title
    });
  });
</script>

        <!-- modal -->
      </div>
      <!-- /lock-screen -->
    </div>
    <!-- /col-lg-4 -->
  </div>
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Attention</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="remarksError"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php
// echo "X-Forwarded-For: " . ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'Not Set') . "<br>";
// echo "Remote IP: " . $_SERVER['REMOTE_ADDR'];
?>
<?php
  if (isset($_POST['submit'])) {
    include('../config.php');
    $empid = $_POST['empid'];
    $logintype = $_POST['logintype']; // This should indicate whether it's 'loginam', 'logoutam', 'loginpm', or 'logoutpm'
    $timenow = date('H:i:s');
    $datenow = date('Y-m-d');
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i:s');

    // Check if employee exists
    $sqlCheckEmployee = mysqli_query($con, "SELECT * FROM employee_profile WHERE idno='$empid'");
    if (mysqli_num_rows($sqlCheckEmployee) > 0) {
        $profile = mysqli_fetch_array($sqlCheckEmployee);
        $name = $profile['firstname'] . " " . $profile['lastname'];

        // Check attendance for today
        $sqlCheckSession = mysqli_query($con, "SELECT * FROM attendance WHERE idno='$empid' AND logindate='$datenow'");
        $attendanceRecord = mysqli_fetch_array($sqlCheckSession);
        
      


       
        function logError($con, $empid, $errorMessage, $logType) {
          $ipAddress = getClientIP(); // Get the client IP address
          $stmt = $con->prepare("INSERT INTO error_logs (empid, error_message, log_type, ip_address) VALUES (?, ?, ?, ?)");
          $stmt->bind_param("ssss", $empid, $errorMessage, $logType, $ipAddress);
          $stmt->execute();
          $stmt->close();
      }
      
     function getClientIP() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipArray = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ipArray[0]); // Get the first IP in the list
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        // If REMOTE_ADDR is the gateway IP, you can log it separately
        return $_SERVER['REMOTE_ADDR'] . ' (Gateway)';
    } else {
        return 'Unknown IP';
    }
}
    
    
      
if ($logintype === 'loginam') {
          // Determine if the employee is in a night shift
          $employeeStartShift = mysqli_fetch_array(
              mysqli_query($con, "SELECT startshift FROM employee_details WHERE idno='$empid'")
          )['startshift'];
      
          $isNightShift = ($employeeStartShift >= "22:00:00" || $employeeStartShift <= "00:00:00");
      
          // Adjust the log date for night shift
          $logindateAdjusted = $datenow;
          if ($isNightShift && $timenow >= "00:00:00" && $timenow <= "09:00:00") {
              $logindateAdjusted = date('Y-m-d', strtotime('-1 day', strtotime($datenow)));
          }
      
          // Retrieve the attendance record for the determined date
          $attendanceRecord = mysqli_fetch_array(
              mysqli_query($con, "SELECT * FROM attendance WHERE idno='$empid' AND logindate='$logindateAdjusted'")
          );

          $leaveTypes = ['VL', 'SL', 'PTO', 'EO', 'BLP', 'SPL']; 
          if ($attendanceRecord && in_array($attendanceRecord['remarks'], $leaveTypes)) {
              $leaveType = $attendanceRecord['remarks']; // Save leave type to restore credit later
  
              // Restore leave credits
              $leaveColumn = '';
              switch ($leaveType) {
                  case 'VL': $leaveColumn = 'vlused'; break;
                  case 'SL': $leaveColumn = 'slused'; break;
                  case 'PTO': $leaveColumn = 'ptoused'; break;
                  case 'EO': $leaveColumn = 'eo_used'; break;
                  case 'BLP': $leaveColumn = 'blp_used'; break;
                  case 'SPL': $leaveColumn = 'spl_used'; break;
              }
  
              if ($leaveColumn) {
                  mysqli_query($con, "UPDATE leave_credits SET $leaveColumn = $leaveColumn - 1 WHERE idno = '$empid'");
              }
  
              // Set remarks to 'P' to indicate the employee is present
              $remarks = 'P';
              $status = (mysqli_fetch_array(mysqli_query($con, "SELECT startshift FROM employee_details WHERE idno='$empid'")))['startshift'] >= "03:00:00" || 
                  (mysqli_fetch_array(mysqli_query($con, "SELECT startshift FROM employee_details WHERE idno='$empid'")))['startshift'] == "00:00:00" ? "work" : "nd/work";
              mysqli_query($con, "UPDATE attendance SET status = '$status' WHERE  idno='$empid' AND logindate='$datenow'");
          }


          $sqlCheckStatus = mysqli_query($con, "SELECT status FROM employee_details WHERE idno='$empid'");
        $employeeDetails = mysqli_fetch_array($sqlCheckStatus);
        if ($employeeDetails['status'] === 'RESIGNED') {
          $errorMessage = "Unauthorize Access! Resigned ID.";
          // Optionally log the error
          logError($con, $empid, $errorMessage, $logintype);
  
          echo "<script>
              document.getElementById('remarksError').innerHTML = '$errorMessage';
              setTimeout(function() {
                  window.location='../attendance/';
              }, 3000);
          </script>";
          exit;
      }
         
          // Check for double entry in `loginam`
          if ($attendanceRecord && !empty($attendanceRecord['loginam']) && $attendanceRecord['loginam'] !== '0') {
            $errorMessage = 'You have already registered in this session!';
            logError($con, $empid, $errorMessage, 'loginam');
        
            echo "<script>
                document.getElementById('remarksError').innerHTML = '$errorMessage';
                setTimeout(function() {
                    window.location='../attendance/';
                }, 3000);
            </script>";
            exit;
        }
      
          // Determine status based on shift
     
          if ($employeeStartShift >= "03:00:00" && $employeeStartShift <= "15:00:00") {
              $status = "work";
          } elseif ($isNightShift) {
              $status = "nd/work";
          }
      
          // Insert or update attendance record
          if (!$attendanceRecord) {
              $stmt = $con->prepare(
                  "INSERT INTO attendance (idno, loginam, logoutam, loginpm, logoutpm, logindate, status, remarks) 
                  VALUES (?, ?, '0', '0', '0', ?, ?, 'P')"
              );
              $stmt->bind_param("ssss", $empid, $timenow, $logindateAdjusted, $status);
          } else {
              $stmt = $con->prepare(
                  "UPDATE attendance SET loginam = ?, remarks = 'P' WHERE idno = ? AND logindate = ?"
              );
              $stmt->bind_param("sss", $timenow, $empid, $logindateAdjusted);
          }
      
          // Execute the statement
          if ($stmt->execute()) {
            $message = 'Welcome';
               logError($con, $empid, $message, $logintype);
    
        echo "<script>
            window.location = '../attendance/?success&empname=" . urlencode($name) . "&type=" . urlencode($message) . "';
        </script>";
        exit;
          } else {
              echo "<script>
                  alert('Error: " . $stmt->error . "');
                  window.location='../attendance/';
              </script>";
          }
          exit;
      }
     elseif ($logintype === 'logoutam') {
  $isNightShift = false;
  
  // Check if the status is nd/work
  $employeeDetails = mysqli_fetch_array(mysqli_query($con, "SELECT startshift FROM employee_details WHERE idno='$empid'"));
  if ($employeeDetails['startshift'] >= "22:00:00" || $employeeDetails['startshift'] <= "00:00:00") {
      $isNightShift = true;
  }

  // Determine the date to use for fetching the log
  $logDateToCheck = $currentDate;
  if ($isNightShift && $timenow >= "00:00:00" && $timenow <= "09:00:00") {
      // For night shift, use the previous date if within early morning hours
      $logDateToCheck = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
  }

  // Retrieve the attendance record for the determined date
  $sqlCheckSession = mysqli_query($con, "SELECT * FROM attendance WHERE idno='$empid' AND logindate='$logDateToCheck'");
  $attendanceRecord = mysqli_fetch_array($sqlCheckSession);

  $sqlCheckStatus = mysqli_query($con, "SELECT status FROM employee_details WHERE idno='$empid'");
        $employeeDetails = mysqli_fetch_array($sqlCheckStatus);
        if ($employeeDetails['status'] === 'RESIGNED') {
          $errorMessage = "Unauthorize Access! Resigned ID.";
          // Optionally log the error
          logError($con, $empid, $errorMessage, $logintype);
  
          echo "<script>
              document.getElementById('remarksError').innerHTML = '$errorMessage';
              setTimeout(function() {
                  window.location='../attendance/';
              }, 3000);
          </script>";
          exit;
      }

  if ($attendanceRecord) {
      // Check if the employee has already logged out for AM
      if (!empty($attendanceRecord['logoutam']) && $attendanceRecord['logoutam'] !== '0') {
        $errorMessage = 'You have already registered in this session!';
            logError($con, $empid, $errorMessage, 'logoutam');

            echo "<script>
                document.getElementById('remarksError').innerHTML = '$errorMessage';
                setTimeout(function() {
                    window.location='../attendance/';
                }, 3000);
            </script>";
            exit;
            }

      // Update the logoutam time for the determined log date
      $sqlUpdate = mysqli_query($con, "UPDATE attendance SET logoutam='$timenow' WHERE idno='$empid' AND logindate='$logDateToCheck'");
      if ($sqlUpdate) {
        $message = 'Happy lunch!';
        logError($con, $empid, $message, $logintype);
    
        echo "<script>
            window.location = '../attendance/?success&empname=" . urlencode($name) . "&type=" . urlencode($message) . "';
        </script>";
        exit;
    } else {
        echo "<script>
            alert('Error: Unable to update attendance!');
            window.location = '../attendance/';
        </script>";
        exit;
    }
    
  } else {
    $errorMessage = 'No attendance record found for the selected date!';
    logError($con, $empid, $errorMessage, $logintype);

    echo "<script>
        document.getElementById('remarksError').innerHTML = '$errorMessage';
        setTimeout(function() {
            window.location='../attendance/';
        }, 3000);
    </script>";
}
  exit;
}
elseif ($logintype === 'loginpm') {
  $isNightShift = false;
  
  // Check if the status is nd/work
  $employeeDetails = mysqli_fetch_array(mysqli_query($con, "SELECT startshift FROM employee_details WHERE idno='$empid'"));
  if ($employeeDetails['startshift'] >= "22:00:00" || $employeeDetails['startshift'] <= "00:00:00") {
      $isNightShift = true;
  }

  // Determine the date to use for fetching the log
  $logDateToCheck = $currentDate;
  if ($isNightShift && $timenow >= "00:00:00" && $timenow <= "12:00:00") {
      // For night shift, use the previous date if within early morning hours
      $logDateToCheck = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
  }

  // Retrieve the attendance record for the determined date
  $sqlCheckSession = mysqli_query($con, "SELECT * FROM attendance WHERE idno='$empid' AND logindate='$logDateToCheck'");
  $attendanceRecord = mysqli_fetch_array($sqlCheckSession);

  $sqlCheckStatus = mysqli_query($con, "SELECT status FROM employee_details WHERE idno='$empid'");
        $employeeDetails = mysqli_fetch_array($sqlCheckStatus);
        if ($employeeDetails['status'] === 'RESIGNED') {
          $errorMessage = "Unauthorize Access! Resigned ID.";
          // Optionally log the error
          logError($con, $empid, $errorMessage, $logintype);
  
          echo "<script>
              document.getElementById('remarksError').innerHTML = '$errorMessage';
              setTimeout(function() {
                  window.location='../attendance/';
              }, 3000);
          </script>";
          exit;
      }

      if ($attendanceRecord) {
        // Restriction: Check if the user has logged out in the morning before logging in the afternoon
        if (empty($attendanceRecord['logoutam']) || $attendanceRecord['logoutam'] === '0') {
            $errorMessage = 'You need to lunch out first!';
            logError($con, $empid, $errorMessage, 'loginpm');
  
            echo "<script>
                document.getElementById('remarksError').innerHTML = '$errorMessage';
                setTimeout(function() {
                    window.location='../attendance/';
                }, 3000);
            </script>";
            exit;
        }
  
      }
  if ($attendanceRecord) {
      // Check if the employee has already logged out for AM
      if (!empty($attendanceRecord['loginpm']) && $attendanceRecord['loginpm'] !== '0') {
        $errorMessage = 'You have already registered in this session!';
        logError($con, $empid, $errorMessage, 'loginpm');

            echo "<script>
                document.getElementById('remarksError').innerHTML = '$errorMessage';
                setTimeout(function() {
                    window.location='../attendance/';
                }, 3000);
            </script>";
            exit;
        }

      // Update the logoutam time for the determined log date
      $sqlUpdate = mysqli_query($con, "UPDATE attendance SET loginpm='$timenow' WHERE idno='$empid' AND logindate='$logDateToCheck'");
      if ($sqlUpdate) {
        $message = 'Welcome Back';
        logError($con, $empid, $message, $logintype);
    
        echo "<script>
            window.location = '../attendance/?success&empname=" . urlencode($name) . "&type=" . urlencode($message) . "';
        </script>";
        exit;
    } else {
        echo "<script>
            alert('Error: Unable to update attendance!');
            window.location = '../attendance/';
        </script>";
        exit;
    }
    
  } else {
    $errorMessage = 'No attendance record found for the selected date!';
    logError($con, $empid, $errorMessage, $logintype);

    echo "<script>
        document.getElementById('remarksError').innerHTML = '$errorMessage';
        setTimeout(function() {
            window.location='../attendance/';
        }, 3000);
    </script>";
}
  exit;
}elseif ($logintype === 'logoutpm') {
  $isNightShift = false;
  
  // Check if the status is nd/work
  $employeeDetails = mysqli_fetch_array(mysqli_query($con, "SELECT startshift FROM employee_details WHERE idno='$empid'"));
  if ($employeeDetails['startshift'] >= "22:00:00" || $employeeDetails['startshift'] <= "00:00:00") {
      $isNightShift = true;
  }

  // Determine the date to use for fetching the log
  $logDateToCheck = $currentDate;
  if ($isNightShift && $timenow >= "00:00:00" && $timenow <= "15:00:00") {
      // For night shift, use the previous date if within early morning hours
      $logDateToCheck = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
  }

  // Retrieve the attendance record for the determined date
  $sqlCheckSession = mysqli_query($con, "SELECT * FROM attendance WHERE idno='$empid' AND logindate='$logDateToCheck'");
  $attendanceRecord = mysqli_fetch_array($sqlCheckSession);

  $sqlCheckStatus = mysqli_query($con, "SELECT status FROM employee_details WHERE idno='$empid'");
        $employeeDetails = mysqli_fetch_array($sqlCheckStatus);
        if ($employeeDetails['status'] === 'RESIGNED') {
          $errorMessage = "Unauthorize Access! Resigned ID.";
          // Optionally log the error
          logError($con, $empid, $errorMessage, $logintype);
  
          echo "<script>
              document.getElementById('remarksError').innerHTML = '$errorMessage';
              setTimeout(function() {
                  window.location='../attendance/';
              }, 3000);
          </script>";
          exit;
      }

      if ($attendanceRecord) {
        // Restriction: Check logoutam and loginpm conditions before allowing logoutpm
        if (empty($attendanceRecord['logoutam']) && empty($attendanceRecord['loginpm'])) {
            // Allow the user to loginpm since logoutam is missing
            // You can handle this scenario (e.g., prompt for loginpm)
        } elseif (!empty($attendanceRecord['logoutam']) && empty($attendanceRecord['loginpm'])) {
            // User has logoutam but no loginpm; prevent logoutpm
            $errorMessage = 'You need to lunch in first before you can log out!';
            logError($con, $empid, $errorMessage, 'logoutpm');
    
            echo "<script>
                document.getElementById('remarksError').innerHTML = '$errorMessage';
                setTimeout(function() {
                    window.location='../attendance/';
                }, 3000);
            </script>";
            exit;
        }
      }

  if ($attendanceRecord) {
      // Check if the employee has already logged out for AM
      if (!empty($attendanceRecord['logoutpm']) && $attendanceRecord['logoutpm'] !== '0') {
        $errorMessage = 'You have already registered in this session!';
        logError($con, $empid, $errorMessage, 'logoutpm');
    
        echo "<script>
            document.getElementById('remarksError').innerHTML = '$errorMessage';
            setTimeout(function() {
                window.location='../attendance/';
            }, 3000);
        </script>";
        exit;
    }

      // Update the logoutam time for the determined log date
      $sqlUpdate = mysqli_query($con, "UPDATE attendance SET logoutpm='$timenow' WHERE idno='$empid' AND logindate='$logDateToCheck'");
      if ($sqlUpdate) {
        $message = 'Goodbye';
        logError($con, $empid, $message, $logintype);
    
        echo "<script>
            window.location = '../attendance/?success&empname=" . urlencode($name) . "&type=" . urlencode($message) . "';
        </script>";
        exit;
    } else {
        echo "<script>
            alert('Error: Unable to update attendance!');
            window.location = '../attendance/';
        </script>";
        exit;
    }
    
  } else {
    $errorMessage = 'No attendance record found for the selected date!';
    logError($con, $empid, $errorMessage, $logintype);

    echo "<script>
        document.getElementById('remarksError').innerHTML = '$errorMessage';
        setTimeout(function() {
            window.location='../attendance/';
        }, 3000);
    </script>";
}
  exit;
}
}else {
  // Log the invalid ID error
  $errorMessage = "Invalid ID No.!";
  $stmt = $con->prepare("INSERT INTO error_logs (empid, error_message, log_type) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $empid, $errorMessage, $logintype);
  $stmt->execute();
  $stmt->close();
  ?>
  <script>
      document.getElementById("remarksError").innerHTML = "<?= $errorMessage; ?>";
      setTimeout(function () {
          window.location = "../attendance/";
      }, 2000);
  </script>
  <?php
}
}

  
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <!-- /container -->
  <!-- js placed at the end of the document so the pages load faster -->
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <!--BACKSTRETCH-->
  <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
  <script type="text/javascript" src="lib/jquery.backstretch.min.js"></script>
  <script>
  document.addEventListener("DOMContentLoaded", function () {
    const snowContainer = document.querySelector(".snow-container");

    const particlesPerThousandPixels = 0.1;
    const fallSpeed = 1.25;
    const pauseWhenNotActive = true;
    const maxSnowflakes = 200;
    const snowflakes = [];

    let snowflakeInterval;
    let isTabActive = true;

    function resetSnowflake(snowflake) {
      const size = Math.random() * 5 + 1;
      const viewportWidth = window.innerWidth - size; // Adjust for snowflake size
      const viewportHeight = window.innerHeight;

      snowflake.style.width = `${size}px`;
      snowflake.style.height = `${size}px`;
      snowflake.style.left = `${Math.random() * viewportWidth}px`; // Constrain within viewport width
      snowflake.style.top = `-${size}px`;

      const animationDuration = (Math.random() * 3 + 2) / fallSpeed;
      snowflake.style.animationDuration = `${animationDuration}s`;
      snowflake.style.animationTimingFunction = "linear";
      snowflake.style.animationName =
        Math.random() < 0.5 ? "fall" : "diagonal-fall";

      setTimeout(() => {
        if (parseInt(snowflake.style.top, 10) < viewportHeight) {
          resetSnowflake(snowflake);
        } else {
          snowflake.remove(); // Remove when it goes off the bottom edge
        }
      }, animationDuration * 1000);
    }

    function createSnowflake() {
      if (snowflakes.length < maxSnowflakes) {
        const snowflake = document.createElement("div");
        snowflake.classList.add("snowflake");
        snowflakes.push(snowflake);
        snowContainer.appendChild(snowflake);
        resetSnowflake(snowflake);
      }
    }

    function generateSnowflakes() {
      const numberOfParticles =
        Math.ceil((window.innerWidth * window.innerHeight) / 1000) *
        particlesPerThousandPixels;
      const interval = 5000 / numberOfParticles;

      clearInterval(snowflakeInterval);
      snowflakeInterval = setInterval(() => {
        if (isTabActive && snowflakes.length < maxSnowflakes) {
          requestAnimationFrame(createSnowflake);
        }
      }, interval);
    }

    function handleVisibilityChange() {
      if (!pauseWhenNotActive) return;

      isTabActive = !document.hidden;
      if (isTabActive) {
        generateSnowflakes();
      } else {
        clearInterval(snowflakeInterval);
      }
    }

    generateSnowflakes();

    window.addEventListener("resize", () => {
      clearInterval(snowflakeInterval);
      setTimeout(generateSnowflakes, 1000);
    });

    document.addEventListener("visibilitychange", handleVisibilityChange);
  });
</script>
<style>
  body,
  html {
    margin: 0;
    padding: 0;
    overflow-x: hidden;
    width: 100vw;
    height: auto;
  }

  .snow-container {
    position: fixed;
    top: 0;
    left: 0;
    overflow: hidden;
    width: 100vw;
    height: 100vh;
    z-index: 99999;
    pointer-events: none;
  }

  .snowflake {
    position: absolute;
    background-color: white;
    border-radius: 50%;
    opacity: 0.8;
    pointer-events: none;
  }

  @keyframes fall {
    0% {
      opacity: 0;
      transform: translateY(0);
    }
    10% {
      opacity: 1;
    }
    100% {
      opacity: 0.5;
      transform: translateY(100vh);
    }
  }

  @keyframes diagonal-fall {
    0% {
      opacity: 0;
      transform: translate(0, 0);
    }
    10% {
      opacity: 1;
    }
    100% {
      opacity: 0.25;
      transform: translate(10vw, 100vh);
    }
  }
</style>
  <script>
    $.backstretch("img/val3.jpg", {
      speed: 500
    });
  </script>
  <script>
    function getTime() {
      var st = srvTime();
          function srvTime() {
              var xmlHttp;
              if (window.XMLHttpRequest) {
                  //FF, Opera, Safari, Chrome
                  xmlHttp = new XMLHttpRequest();
              } else if (window.ActiveXObject) {
                  //Old IE Browsers.
                  xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
              }
              xmlHttp.open('HEAD', window.location.href, false);
              xmlHttp.setRequestHeader("Content-Type", "text/html");
              xmlHttp.send('');
              return xmlHttp.getResponseHeader("Date");
          }
      // var today = getservertime(false,"");
      // var h = today.getfieldbyname(1,"hour");
      // var m = today.getfieldbyname(1,"minute");
      // var s = today.getfieldbyname(1,"second");
      // var dd = today.getfieldbyname(1,"day");
      // var mo= today.getfieldbyname(1,"month");
      // var y = today.getfieldbyname(1,"year");
      // var d = mo + " " + dd + ", " + y;
      var today = new Date(st);
      var h = today.getHours();
      var hh = today.getHours();
      var m = today.getMinutes();
      var s = today.getSeconds();
      var d = new Date().toLocaleDateString('en-us', { year:"numeric", month:"short", day: "numeric" });
      var ampm="AM";
      if(h > 12){
        h -= 12;
        ampm="PM";
      }      
      // add a zero in front of numbers<10
      h = checkTime(h);
      m = checkTime(m);
      s = checkTime(s);
       document.getElementById('showtime').innerHTML = d + "<br>" + h + ":" + m + " " + ampm;      
      // document.getElementById('mytime').value= hh + ":" + m + ":" + s;
      t = setTimeout(function() {
        getTime()
      }, 500);
    }

    function checkTime(i) {
      if (i < 10) {
        i = "0" + i;
      }
      return i;
    }
  </script>
  <script>
        $(document).on("click", ".attendance", function () {
         var myBookId = $(this).data('id');         
         $(".modal-body #caseno").val(myBookId);         
         // As pointed out in comments,
         // it is unnecessary to have to manually call the modal.
         // $('#addBookDialog').modal('show');
       });
    </script>
        <script>
       var canvas = document.getElementById("canvas");
canvas.width = 500;
canvas.height = 500;


var xmlHttp;
function srvTime(){
    try {
        //FF, Opera, Safari, Chrome
        xmlHttp = new XMLHttpRequest();
    }
    catch (err1) {
        //IE
        try {
            xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
        }
        catch (err2) {
            try {
                xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
            }
            catch (eerr3) {
                //AJAX not supported, use CPU time.
                alert("AJAX not supported");
            }
        }
    }
    xmlHttp.open('HEAD',window.location.href.toString(),false);
    xmlHttp.setRequestHeader("Content-Type", "text/html");
    xmlHttp.send('');
    return xmlHttp.getResponseHeader("Date");
}

var st = srvTime();

function startTime() {          
var today = new Date(st);
  //const today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  var d = today.getDate();
  // const today = getservertime(false,"");
  // let h = today.getfieldbyname(1,"hour");
  // let m = today.getfieldbyname(1,"minutes"));
  // let s = today.getfieldbyname(1,"second");
  //let d = today.getDate();
  m = checkTime(m);
  s = checkTime(s);
  var ampm = h >= 12 ? 'PM' : 'AM';
  h=h%12;
  h=h ? h : 12;
  //var timenow= h + ":" + m + ":" + s + " " + ampm;
  var timenow= h + ":" + m + ":" + s + " " + ampm;
  document.getElementById('txt').innerHTML = timenow;  
  setTimeout(startTime, 1000);

}

function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
        </script>
</body>

</html>
