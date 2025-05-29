<?php
date_default_timezone_set("Asia/Manila");
?>
<?php
  session_start();
  include('../config.php');
  
// Restrict access if not logged in

// Get user's access level
$fullname = $_SESSION['fullname'];
$access = $_SESSION['access'];

// Fetch unique companies from the employee_details table
$sqlCompanies = mysqli_query($con, "SELECT DISTINCT company FROM employee_details ORDER BY company");

if (!$sqlCompanies) {
    echo "Query error: " . mysqli_error($con);
    exit;
}
if (!isset($_SESSION['idno'])) {
    die("<script>alert('Session expired. Please log in again.'); window.location='/index.php';</script>");
}

  $idno=$_SESSION['idno'];
//   if(!isset($_SESSION['idno'])){
//     echo "<script>window.location='/hris/employeeportal/dashboard.?emp_dev';</script>";
//   }
    $sqlEmployee=mysqli_query($con,"SELECT lastname,firstname FROM employee_profile WHERE idno='$_SESSION[idno]'");
    
    if(mysqli_num_rows($sqlEmployee)>0){
      $name=mysqli_fetch_array($sqlEmployee);
      $fullname=$name['lastname'].", ".$name['firstname'];
    }else{
      $fullname="";
    }
    $sqlDetails=mysqli_query($con,"SELECT ed.*,jt.* 
      FROM employee_details ed 
      INNER JOIN department d ON d.id=ed.department 
      INNER JOIN jobtitle jt ON jt.id=ed.designation 
      WHERE ed.idno='$_SESSION[idno]'");
    if(mysqli_num_rows($sqlDetails)>0){
      $det=mysqli_fetch_array($sqlDetails);
      $jobtitle=$det['jobtitle'];
      $jobtitleID=$det['designation'];
      $department=$det['department'];
      $company=$det['company'];
    }else{
      $jobtitle="";
      $jobtitleID="";
      $department="";
      $company="";
    }
    $designation = $jobtitleID;

    $count=0;
    $sqlProtocol=mysqli_query($con,"SELECT approvingofficer FROM leave_protocols GROUP BY approvingofficer");
    if(mysqli_num_rows($sqlProtocol)>0){
      while($pro=mysqli_fetch_array($sqlProtocol)){
        if($idno==$pro['approvingofficer']){
          $count++;
        }
      }
    }
    if($count > 0){
      $view="";
    }else{
      $view="style='display:none;'";
    }
    
    $approvers = []; // Initialize an empty arr
    // Fetch approvers from the database
    $sqlApprover = mysqli_query($con, "SELECT approvingofficer FROM leave_protocols");
    if (mysqli_num_rows($sqlApprover) > 0) {
        // Store each approver in the array
        while ($row = mysqli_fetch_assoc($sqlApprover)) {
            $approvers[] = $row['approvingofficer'];
        }
    }
 
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="Dashboard">
  <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
  <title>HRIS - North East Solutions Inc.</title>

  <!-- Favicons -->
  <!-- <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon"> -->
  <link rel="icon" type="image/x-icon" href="img/iconhris_2.png">

  <!-- Bootstrap core CSS -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--external css-->
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="css/zabuto_calendar.css">
  <link rel="stylesheet" type="text/css" href="lib/gritter/css/jquery.gritter.css" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
  <script src="lib/chart-master/Chart.js"></script>

  <!-- =======================================================
    Template Name: Dashio
    Template URL: https://templatemag.com/dashio-bootstrap-admin-template/
    Author: TemplateMag.com
    License: https://templatemag.com/license/
  ======================================================= -->
</head>

<body>
    <section id="container">
    <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
    <!--header start-->
    <header class="header black-bg">
      <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
      </div>
      <!--logo start-->
      <a href="?main" class="logo"><b>EMPLOYEE PORTAL</b></a>
      <!--logo end-->
   <?php
// Get user's department and designation
$user_id = $_SESSION['idno'];
$user_dept = null;
$user_desig = null;

$userQuery = mysqli_query($con, "SELECT department, designation FROM employee_details WHERE idno = '$user_id'");
if(mysqli_num_rows($userQuery) > 0) {
    $userData = mysqli_fetch_assoc($userQuery);
    $user_dept = $userData['department'];
    $user_desig = $userData['designation'];
}

// Get today's date
$today = date('Y-m-d');

// Fetch all announcements for today (both read and unread)
$sqlWidgets = mysqli_query($con, "
    SELECT w.*, ar.id as read_status 
    FROM widgets w
    LEFT JOIN announcement_reads ar ON w.id = ar.announcement_id AND ar.user_id = '$user_id'
    WHERE w.type='Announcement' 
    AND DATE(w.datearray) = '$today'
    ORDER BY w.datearray DESC, w.timearray DESC
");

$allAnnouncements = [];
$unreadAnnouncements = [];
while ($emp = mysqli_fetch_array($sqlWidgets)) {
    $targets = json_decode($emp['targets'], true);
    
    // Check if announcement is for this user
    $isForUser = false;
    
    // If targets all employees
    if(empty($targets['departments']) && empty($targets['designations'])) {
        $isForUser = true;
    }
    // Check department
    elseif(in_array($user_dept, $targets['departments'])) {
        $isForUser = true;
    }
    // Check designation
    elseif(in_array($user_desig, $targets['designations'])) {
        $isForUser = true;
    }
    
    if($isForUser) {
        $allAnnouncements[] = [
            'id' => $emp['id'],
            'title' => $emp['title'],
            'details' => $emp['details'],
            'is_read' => !empty($emp['read_status'])
        ];
        
        if(empty($emp['read_status'])) {
            $unreadAnnouncements[] = $emp['id'];
        }
    }
}

$isDetails = !empty($allAnnouncements);
$unreadCount = count($unreadAnnouncements);
$showPopup = ($unreadCount > 0);
?>

<!-- Notification sound element -->
<audio id="notificationSound" src="sound/notification.mp3" preload="auto"></audio>

<!-- Bell Icon with Notification Badge -->
<div style="position: absolute; top:15px; margin-left: 1550px;">
    <button id="announcementButton" class="btn" style="background: transparent; border: none; position: relative; cursor: pointer;">
        <i class="fa fa-bell" style="font-size: 20px; color: white;"></i>
        <?php if ($unreadCount > 0): ?>
            <span id="notificationBadge" class="badge" style="
                position: absolute;
                top: -5px;
                right: -5px;
                background: red;
                color: white;
                border-radius: 50%;
                font-size: 10px;
                padding: 3px 6px;
            "><?= $unreadCount ?></span>
        <?php endif; ?>
    </button>
</div>

<style>
    #popupContent p {
        text-align: justify;
        line-height: 1.6;
    }
    #popupContent h4 {
        font-weight: bold;
    }
    .read-announcement {
        opacity: 0.8;
    }
</style>

<!-- Announcement Pop-up -->
<div id="announcementPopup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80%; max-width: 600px; background: white; z-index: 1050; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.5);">
    <div style="padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
            <h3 style="margin: 0; color: #3f4d6b;">Announcements</h3>
            <button id="closePopup" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <div id="popupContent" style="max-height: 400px; overflow-y: auto;">
            <?php if ($isDetails): ?>
                <ul style="list-style-type: none; padding: 0;">
                    <?php foreach ($allAnnouncements as $announcement): ?>
                        <li style="padding: 15px 0; border-bottom: 1px solid #f5f5f5;" class="<?= $announcement['is_read'] ? 'read-announcement' : '' ?>">
                            <h4 style="margin: 0 0 10px 0; color: #3f4d6b;">
                                <?php echo htmlspecialchars($announcement['title']); ?>
                            </h4>
                            <p style="margin: 0; font-size: 16px; text-align: justify;">
                                <?php echo nl2br(htmlspecialchars($announcement['details'])); ?>
                            </p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No announcements available.</p>
            <?php endif; ?>
        </div>
        <div style="text-align: right; margin-top: 15px;">
            <?php if ($unreadCount > 0): ?>
                <button id="markAsReadBtn" style="background: #4CAF50; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; margin-right: 10px;">Mark as Read</button>
            <?php endif; ?>
            <button id="closePopupBtn" style="background: #3f4d6b; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">Close</button>
        </div>
    </div>
</div>

<!-- Overlay for popup -->
<div id="popupOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1040;"></div>

<script>
// Show popup automatically if there are unread announcements
document.addEventListener('DOMContentLoaded', function() {
    const popup = document.getElementById('announcementPopup');
    const overlay = document.getElementById('popupOverlay');
    const closeBtn = document.getElementById('closePopup');
    const closeBtnBottom = document.getElementById('closePopupBtn');
    const markAsReadBtn = document.getElementById('markAsReadBtn');
    const announcementButton = document.getElementById('announcementButton');
    const notificationSound = document.getElementById('notificationSound');
    
    <?php if ($showPopup): ?>
        // Show popup and overlay
        setTimeout(function() {
            popup.style.display = 'block';
            overlay.style.display = 'block';
            
            // Play notification sound
            notificationSound.play();
            
            // Prevent scrolling when popup is open
            document.body.style.overflow = 'hidden';
            
            // Add animation class for popup
            popup.style.animation = 'fadeIn 0.3s ease-out';
            
            // Add shake animation to bell icon when there are announcements
            announcementButton.querySelector('i').style.animation = 'shake 0.5s ease-in-out';
        }, 1000);
    <?php endif; ?>
    
    function closePopup() {
        popup.style.animation = 'fadeOut 0.3s ease-out';
        overlay.style.animation = 'fadeOut 0.3s ease-out';
        
        setTimeout(function() {
            popup.style.display = 'none';
            overlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 300);
    }
    
    // Close popup when X or Close button is clicked
    closeBtn.addEventListener('click', closePopup);
    closeBtnBottom.addEventListener('click', closePopup);
    
    // Close when clicking outside the popup
    overlay.addEventListener('click', closePopup);
    
    // Mark as Read button
    if(markAsReadBtn) {
        markAsReadBtn.addEventListener('click', function() {
            // Get all unread announcement IDs
            const announcementIds = <?= json_encode($unreadAnnouncements) ?>;
            
            // Send AJAX request to mark as read
            fetch('mark_announcement_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'user_id=<?= $user_id ?>&announcement_ids=' + JSON.stringify(announcementIds)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide notification badge
                    const badge = document.getElementById('notificationBadge');
                    if (badge) {
                        badge.style.display = 'none';
                    }
                    
                    // Update UI to show announcements as read
                    document.querySelectorAll('#popupContent li').forEach(li => {
                        li.classList.add('read-announcement');
                    });
                    
                    // Hide the Mark as Read button
                    if(markAsReadBtn) {
                        markAsReadBtn.style.display = 'none';
                    }
                    
                    // Close the popup
                    closePopup();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
    
    // Show the popup when announcement button is clicked
    announcementButton.addEventListener('click', function() {
        if(popup.style.display === 'none' || popup.style.display === '') {
            popup.style.display = 'block';
            overlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
            popup.style.animation = 'fadeIn 0.3s ease-out';
        } else {
            closePopup();
        }
    });
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translate(-50%, -55%); }
        to { opacity: 1; transform: translate(-50%, -50%); }
    }
    @keyframes fadeOut {
        from { opacity: 1; transform: translate(-50%, -50%); }
        to { opacity: 0; transform: translate(-50%, -55%); }
    }
    @keyframes shake {
        0% { transform: rotate(0deg); }
        25% { transform: rotate(15deg); }
        50% { transform: rotate(-15deg); }
        75% { transform: rotate(15deg); }
        100% { transform: rotate(0deg); }
    }
`;
document.head.appendChild(style);
</script>
      <div class="nav notify-row" id="top_menu">
        <!--  notification start -->
        <!--  notification end -->
      </div>
      <div class="top-menu">
    <ul class="nav pull-right top-menu">
      
      <li><a class="logout" style="border-radius: 15px 15px;" href="logout.php" onclick="return confirm('Do you wish to logout?');return false;">Logout</a></li>
    </ul>
    <li style="float: right; margin-right: 40px; margin-top: 20px; "><a class="attendance_out" href="/hris/attendance/" style=" background-color:#337ab7; padding: 5px 15px; font-size: 13px; color: white; border: 1px solid #337ab7; border-radius: 15px 15px; border-color: #337ab7;">Attendance</a></li>
  </div>
  
    </header>
    <!--header end-->
    <!-- **********************************************************************************************************************************************************
        MAIN SIDEBAR MENU
        *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
    <aside>
    <div id="sidebar" class="nav-collapse ">
    <!-- sidebar menu start-->
    <ul class="sidebar-menu" id="nav-accordion">
        <?php
        // Fetch user ID
        $userId = $_SESSION['idno'];
        
        // Check if the user has an uploaded profile picture
        $image = "path/to/default/image.jpg"; // Default image
        $target_dir = "../Employees/";

        // Check for profile picture in multiple formats
        if (file_exists($target_dir . $userId . ".png")) {
            $image = $target_dir . $userId . ".png";
        } elseif (file_exists($target_dir . $userId . ".jpg")) {
            $image = $target_dir . $userId . ".jpg";
        } elseif (file_exists($target_dir . $userId . ".jpeg")) {
            $image = $target_dir . $userId . ".jpeg";
        } else{
          $image = $target_dir . "default_image.png";
        }
        ?>
              

        <!-- Display profile picture -->
        <p class="centered">
          
            <img src="<?= $image; ?>" alt="Profile Picture" class="img-circle" width="80" height="80">
        </p>
          <h5 class="centered"><?=$fullname;?></h5>
          <p class= "centered" style=" font-size:13px; color:white;"><?= $idno; ?></p>
           <li>
            <a href="dashboard.php?main">
              <i class="fa fa-user-circle"></i>
              <span>Profile</span>
            </a>
          </li>
           <!-- Applications Dropdown (FORCE-OPENED) -->
           <li class="sub-menu active open">
            <a href="javascript:;" class="menu-toggle dcjq-parent active">
              <i class="fa fa-envelope-open"></i>
              <span>Applications</span>
            </a>
            <ul class="sub">
              <!-- Active "Manage Leave" -->
              <li>
                <a href="manageleave.php"><i class="fa fa-list"></i> Manage Leave</a>
              </li>
              <!-- Other Submenu Items -->
              <li><a href="applymissedlog.php"><i class="fa fa-clock-o"></i> Apply Missed Log</a></li>
              <li><a href="applyovertime.php"><i class="fa fa-hourglass"></i> Apply Overtime</a></li>
              <li><a href="emergencyearlyout.php"><i class="fa fa-exclamation"></i> Apply EEO</a></li>
              <?php if ($designation == 114): ?>
                <li class="active" ><a href="manageemployee.php" style="position: relative; display: inline-block;font-size: 12px;"><i class="fa fa-user"></i> Add Leave for Employee</a></li>
              <?php endif; ?>
            </ul>
          </li>
            <!-- ------------------------------------------------------------------------------- -->
            <li class="sub-menu">
            <a href="javascript:;">
              <i class="fa fa-archive"></i>
              <span>Requests</span>
              <?php if (in_array($idno, $approvers) && $designation != 77 && $designation != 97): ?>
                <span id="credit-notification-badge" class="badge" style="background:red; color:white; margin-left:60px;"></span>
              <?php endif; ?>
            </a>
            <ul class="sub">
              <?php if ($designation != 77 && $designation != 97 && $designation != 78 && $designation != 116): ?>
                <li><a href="manageleaveapplication.php">Leave Applications <span id="leave-notification-badge" class="badge" style="background:red; color:white;"></span></a></li>
                <li><a href="managemissedlogapplication.php">Missed Log <span id="ml-notification-badge" class="badge" style="background:red; color:white;"></span></a></li>
                <li><a href="manageovertimeapplication.php">OT Applications <span id="ot-notification-badge" class="badge" style="background:red; color:white;"></span></a></li>
              <?php endif; ?>
              <?php if ($designation == 77 || $designation == 97): ?>
                <li><a href="dashboard.php?monitoringmanagemissedlogapplication">Missed Log <span id="mml-notification-badge" class="badge" style="background:red; color:white;"></span></a></li>
              <?php endif; ?>
              <?php if ($designation == 78 || $designation == 116): ?>
                <li><a href="dashboard.php?EEOapplication">EEO Applications <span id="eeo-notification-badge" class="badge" style="background:red; color:white;"></span></a></li>
              <?php endif; ?>
              <?php if (in_array($idno, $approvers) && $designation != 78 && $designation != 116 && $designation != 77 && $designation != 97): ?>
                <li><a href="manageEEOapplication.php">EEO Applications <span id="eeo-notification-badge" class="badge" style="background:red; color:white;"></span></a></li>
              <?php endif; ?>
            </ul>
          </li>
          <!-- ------------------------------------------------------------------------------- -->
          <script>
// Toggle submenus (for "Requests" and others)
$(document).ready(function() {
  // Click handler for menu items with submenus
  $('.sub-menu a.menu-toggle').on('click', function(e) {
    e.preventDefault();
    var $parent = $(this).parent();
    
    // Close other open menus (optional)
    $('.sub-menu').not($parent).removeClass('active open');
    $('.sub-menu .sub').not($parent.find('.sub')).slideUp(200);
    
    // Toggle current menu
    $parent.toggleClass('active open');
    $parent.find('.sub').slideToggle(200);
  });

  // Force-open "Applications" menu on page load
  $('.sub-menu.active.open .sub').show();
});
</script>
          <li>
            <a href="dashboard.php?viewpayroll">
              <i class="fa fa-credit-card"></i>
              <span>Payslip</span>
            </a>
          </li>

          <!-- 5. Infractions -->
          <li>
            <a href="dashboard.php?infractions">
              <i class="fa fa-folder-open"></i>
              <span>Infractions</span>
              <span id="emp-infraction-notif" style="background:red; width:10px; height:10px; border-radius:50%; display:none;"></span>
            </a>
          </li>

          <!-- 6. Manage Infraction (for specific roles) -->
          <?php if ($designation == 8 || $designation == 50 || $designation == 89 || $designation == 59 || $designation == 65 || $designation == 94 || $designation == 102 || $designation == 3 || $designation == 88 || $designation == 114 || $designation == 92 || $userId == '103417'): ?>
            <li>
              <a href="dashboard.php?manageinfraction">
                <i class="fa fa-bell"></i>
                <span>Manage Infraction</span>
                <span id="notificationDot" style="background:red; width:10px; height:10px; border-radius:50%; display:none;"></span>
              </a>
            </li>
          <?php endif; ?>

          <!-- 7. HRIS Monitoring (for HR) -->
          <?php if ($designation == 97 || $designation == 77): ?>
            <li>
              <a href="dashboard.php?errorcatching">
                <i class="fa fa-eye"></i>
                <span>HRIS Monitoring</span>
              </a>
            </li>
          <?php endif; ?>

          <!-- 8. Log Details -->
          <li>
            <a href="dashboard.php?attendance">
              <i class="fa fa-clock-o"></i>
              <span>Log Details</span>
            </a>
          </li>
  <?php
// Retrieve access from session
$access = $_SESSION['access']; // Dynamically get access from session
$accessList = array_map('trim', explode(',', $access)); // Trim and explode

// Debugging: Check the access list

?>
          <!-- 9. Role-Based Portals (HR/IT/Accounting) -->
          <?php if (in_array('HR', $accessList)): ?>
            <li><a href="/hr/?main"><i class="fa fa-users"></i> HR Portal</a></li>
          <?php endif; ?>
          <?php if (in_array('IT ADMIN', $accessList)): ?>
            <li><a href="/settings/?main"><i class="fa fa-cogs"></i> IT Admin Portal</a></li>
          <?php endif; ?>
          <?php if (in_array('ACCOUNTING', $accessList)): ?>
            <li><a href="/accounting/?main"><i class="fa fa-money"></i> Accounting Portal</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </aside>
    
     <!--sidebar end-->
    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <!--main content start-->
    
    <section id="main-content">
      <section class="wrapper">
        <div class="col-lg-12">

    <div class="content-panel">
        <div class="panel-heading">
            <h4>
                <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> |
                <i class="fa fa-user"></i> EMPLOYEE LIST
            </h4>
        </div>

        <!-- Company Tabs -->
        <ul class="nav nav-tabs">
            <?php
            $active = 'active'; // Set the first tab as active
            while ($company = mysqli_fetch_array($sqlCompanies)) {
                $companyCode = htmlspecialchars($company['company']); // Sanitize output
                $sanitizedId = preg_replace('/[^A-Za-z0-9\-]/', '', $companyCode); // Unique ID
                echo "<li class='$active'><a data-toggle='tab' href='#tab-$sanitizedId'>$companyCode</a></li>";
                $active = ''; // Remove active class for subsequent tabs
            }
            ?>
        </ul>

        <div class="tab-content">
            <?php
            // Reset the result pointer for reuse
            mysqli_data_seek($sqlCompanies, 0);
            $active = 'in active'; // Set the first tab content as active
            while ($company = mysqli_fetch_array($sqlCompanies)) {
                $companyCode = htmlspecialchars($company['company']); // Sanitize output
                $sanitizedId = preg_replace('/[^A-Za-z0-9\-]/', '', $companyCode);
                echo "<div id='tab-$sanitizedId' class='tab-pane fade $active'>";

                // Fetch unique departments for the current company
                $sqlDepartments = mysqli_query($con, "SELECT DISTINCT d.department FROM employee_details ed
                    INNER JOIN department d ON d.id = ed.department
                    WHERE ed.company = '$companyCode'
                    AND ed.status != 'RESIGNED'
                    ORDER BY d.department");

                if (!$sqlDepartments) {
                    echo "Error fetching departments: " . mysqli_error($con);
                    continue;
                }

                echo "<ul class='nav nav-pills' style='margin-top: 10px;'>";
                $deptActive = 'active';
                while ($department = mysqli_fetch_array($sqlDepartments)) {
                    $departmentName = htmlspecialchars($department['department']); // Sanitize output
                    $deptId = preg_replace('/[^A-Za-z0-9\-]/', '', $departmentName); // Unique ID
                    echo "<li class='$deptActive'><a data-toggle='pill' href='#dept-$sanitizedId-$deptId'>$departmentName</a></li>";
                    $deptActive = ''; // Remove active class for subsequent department tabs
                }
                echo "</ul>";

                echo "<div class='tab-content' style='margin-top: 10px;'>";
                mysqli_data_seek($sqlDepartments, 0); // Reset department pointer
                $deptActive = 'in active';
                while ($department = mysqli_fetch_array($sqlDepartments)) {
                    $departmentName = htmlspecialchars($department['department']); // Sanitize output
                    $deptId = preg_replace('/[^A-Za-z0-9\-]/', '', $departmentName); // Unique ID
                    echo "<div id='dept-$sanitizedId-$deptId' class='tab-pane fade $deptActive'>";

                    // Fetch employees for the company and department
                    $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.*, d.department, ed.designation, jt.jobtitle 
                        FROM employee_profile ep
                        INNER JOIN employee_details ed ON ed.idno = ep.idno
                        INNER JOIN department d ON d.id = ed.department
                        INNER JOIN jobtitle jt ON jt.id = ed.designation
                        WHERE ed.company = '$companyCode' AND d.department = '$departmentName' 
                        AND ed.status NOT LIKE '%RESIGNED%'
                        ORDER BY ep.lastname ASC");

                    if (!$sqlEmployee) {
                        echo "Error fetching employees: " . mysqli_error($con);
                        continue;
                    }

                    echo '<!-- Search Bar -->';
                    echo '<div class="d-flex align-items-center mb-3" style="margin-bottom: 3px;">';
                    echo '    <div class="input-group" style="width: 300px;">';
                    echo '        <input type="text" class="form-control" placeholder="Search..." onkeyup="filterTable(this)">';
                    echo '    </div>';
                    echo '</div>';

                    echo "<table class='table table-bordered table-striped table-condensed'>
                        <thead>
                            <tr>
                                <th style = 'text-align: center;'>No.</th>
                                <th style = 'text-align: center;'>Emp ID</th>
                                <th style = 'text-align: center;'>Employee Name</th>
                                <th style = 'text-align: center;'>Date of Birth</th>
                                <th style = 'text-align: center;'>Job Title</th>
                                <th style = 'text-align: center;'>Department</th>
                                <th style = 'text-align: center;'>Company</th>
                                <th style = 'text-align: center;'>Status</th>
                                <th style = 'text-align: center;'>Date Hired</th>
                                <th style = 'text-align: center;'>Shift</th>
                                <th style = 'text-align: center;'>Work Area</th>
                                <th style = 'text-align: center;'>Action</th>
                            </tr>
                        </thead>
                        <tbody>";

                    $x = 1;
                    while ($employee = mysqli_fetch_array($sqlEmployee)) {
                        $status = $employee['status'] === "REGULAR"
                            ? "<span class='label label-success label-mini'>{$employee['status']}</span>"
                            : "<span class='label label-warning label-mini'>{$employee['status']}</span>";

                        $shift = date('h:i A', strtotime($employee['startshift'])) . " - " . date('h:i A', strtotime($employee['endshift']));
                        $dateHired = date('m/d/Y', strtotime($employee['dateofhired']));
                        $jobTitle = htmlspecialchars($employee['jobtitle']); 

                        echo "<tr>
                            <td align='center'>{$x}.</td>
                            <td align='center'>{$employee['idno']}</td>
                            <td align='center'>{$employee['lastname']}, {$employee['firstname']} {$employee['middlename']} {$employee['suffix']}</td>
                            <td align='center'>" . date('M-d-Y', strtotime($employee['birthdate'])) . "</td>
                            <td align='center'>{$jobTitle}</td>
                            <td align='center'>{$employee['department']}</td>
                            <td align='center'>{$employee['company']}</td>
                            <td align='center'>{$status}</td>
                            <td align='center'>{$dateHired}</td>
                            <td align='center'>{$shift}</td>
                            <td align='center'>{$employee['location']}</td>
                            <td align='center'>
                                <a href='?applyleaveforemp&idno={$employee['idno']}' class='btn btn-warning btn-xs' title='File Leave for Employee'><i class='fa fa-clipboard'></i></a>
                            </td>
                        </tr>";
                        $x++;
                    }

                    if ($x === 1) {
                        echo "<tr><td colspan='12' align='center'>No records found!</td></tr>";
                    }

                    echo "</tbody></table></div>"; // End department content
                    $deptActive = ''; // Remove active class for subsequent departments
                }

                echo "</div></div>"; // End company content
                $active = ''; // Remove active class for subsequent companies
            }
            ?>
        </div>
    </div>
</div>

<!-- Ensure Bootstrap JS and jQuery are included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


<script>
    $(document).ready(function() {
    // Store active tab on click
    $('.nav-tabs a').on('click', function() {
        localStorage.setItem('activeTab', $(this).attr('href'));
    });

    // Retrieve active tab on page load
    const activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
        $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
    }

    // Store active main tab on click
    $('.nav-tabs a').on('click', function() {
            localStorage.setItem('activeMainTab', $(this).attr('href'));
        });

        // Retrieve active main tab on page load
        const activeMainTab = localStorage.getItem('activeMainTab');
        if (activeMainTab) {
            $('.nav-tabs a[href="' + activeMainTab + '"]').tab('show');
        }

        // Store active inner tab on click
        $('.nav-pills a').on('click', function() {
            const companyId = $(this).closest('.tab-pane').attr('id'); // Get the company tab ID
            localStorage.setItem('activeInnerTab-' + companyId, $(this).attr('href'));
        });

        // Retrieve active inner tab on page load
        $('.tab-pane').each(function() {
            const companyId = $(this).attr('id');
            const activeInnerTab = localStorage.getItem('activeInnerTab-' + companyId);
            if (activeInnerTab) {
                $('.nav-pills a[href="' + activeInnerTab + '"]').tab('show');
            }
        });
});
$(document).ready(function() {
        // Store active main tab on click
        $('.nav-tabs a').on('click', function() {
            localStorage.setItem('activeMainTab', $(this).attr('href'));
        });

        // Retrieve active main tab on page load
        const activeMainTab = localStorage.getItem('activeMainTab');
        if (activeMainTab) {
            $('.nav-tabs a[href="' + activeMainTab + '"]').tab('show');
        }

        // Store active inner tab on click
        $('.nav-pills a').on('click', function() {
            const companyId = $(this).closest('.tab-pane').attr('id'); // Get the company tab ID
            localStorage.setItem('activeInnerTab-' + companyId, $(this).attr('href'));
        });

        // Retrieve active inner tab on page load
        $('.tab-pane').each(function() {
            const companyId = $(this).attr('id');
            const activeInnerTab = localStorage.getItem('activeInnerTab-' + companyId);
            if (activeInnerTab) {
                $('.nav-pills a[href="' + activeInnerTab + '"]').tab('show');
            }
        });

        // Select all buttons with the "confirm-action" class
        const confirmButtons = document.querySelectorAll('.confirm-post');

        // Loop through each button and add a click event listener
        confirmButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                // Display the confirmation dialog
                const confirmAction = confirm("Are you sure you want to POST this leave?");
                
                // If the user clicks "Cancel", prevent the link's default action
                if (!confirmAction) {
                    event.preventDefault();
                }
            });
        });
    });

    function filterTable(input) {
        // Get the input field and table
        const searchValue = input.value.toLowerCase();
        const table = input.closest('.tab-pane').querySelector('table');
        
        // Loop through all table rows and hide those that don't match the search query
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    }
</script>

</div>
        </div>
      </section>
    </section>
  </section>
</body>
</html>
<!-- 1. Load jQuery (REQUIRED for menu toggles) -->
<script src="lib/jquery/jquery.min.js"></script>

<!-- 2. Override Dashio's menu behavior to KEEP "Applications" open -->
<script>
$(document).ready(function() {
  // Prevent Dashio from closing the "Applications" menu
  $('.sub-menu.active.open').addClass('dont-close'); // Mark menu to stay open

  // Dashio's menu toggle (modified)
  $('.sidebar-menu a.menu-toggle').on('click', function(e) {
    e.preventDefault();
    var $parent = $(this).parent();
    
    // Close other menus (except marked ones)
    $('.sub-menu').not('.dont-close').removeClass('active open').find('.sub').slideUp(200);
    
    // Toggle current menu (if not marked)
    if (!$parent.hasClass('dont-close')) {
      $parent.toggleClass('active open');
      $parent.find('.sub').slideToggle(200);
    }
  });

  // Force "Applications" menu to stay open on load
  $('.sub-menu.active.open .sub').show();
});
</script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script class="include" type="text/javascript" src="lib/jquery.dcjqaccordion.2.7.js"></script>
  <script src="lib/jquery.scrollTo.min.js"></script>
  <script src="lib/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="lib/jquery.sparkline.js"></script>
  <!--common script for all pages-->
  <script src="lib/common-scripts.js"></script>
  <script type="text/javascript" src="lib/gritter/js/jquery.gritter.js"></script>
  <script type="text/javascript" src="lib/gritter-conf.js"></script>
  <!--script for this page-->
  <script src="lib/sparkline-chart.js"></script>
  <script src="lib/zabuto_calendar.js"></script>
  <script type="text/javascript" language="javascript" src="lib/advanced-datatable/js/jquery.dataTables.js"></script>
  <script type="text/javascript" src="lib/advanced-datatable/js/DT_bootstrap.js"></script>
   <script type="text/javascript" src="lib/bootstrap-fileupload/bootstrap-fileupload.js"></script>
  <script type="text/javascript" src="lib/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
  <script type="text/javascript" src="lib/bootstrap-daterangepicker/date.js"></script>
  <script type="text/javascript" src="lib/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script type="text/javascript" src="lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
  <script type="text/javascript" src="lib/bootstrap-daterangepicker/moment.min.js"></script>
  <script type="text/javascript" src="lib/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
  <script src="lib/advanced-form-components.js"></script>