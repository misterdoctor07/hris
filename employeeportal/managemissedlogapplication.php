<?php
date_default_timezone_set("Asia/Manila");

  session_start();
  include('../config.php');
  
// Restrict access if not logged in
if (!isset($_SESSION['idno'])) {
    die("<script>alert('Session expired. Please log in again.'); window.location='/index.php';</script>");
}
// Get user's access level
$fullname = $_SESSION['fullname'];
$access = $_SESSION['access'];


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
  
  <!-- jQuery (MUST come first if you're using DataTables or Bootstrap 4) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Bundle (with Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>

<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

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
           <li class="sub-menu">
            <a href="javascript:;">
              <i class="fa fa-envelope-open"></i>
              <span>Applications</span>
            </a>
            <ul class="sub">
              <!-- Active "Manage Leave" -->
              <li>
                <a href="manageleave.php"> Manage Leave</a>
              </li>
              <!-- Other Submenu Items -->
              <li><a href="applymissedlog.php"> Apply Missed Log</a></li>
              <li><a href="applyovertime.php"> Apply Overtime</a></li>
              <li><a href="emergencyearlyout.php">Apply EEO</a></li>
              <?php if ($designation == 114): ?>
                <li><a href="manageemployee.php">Add Leave for Employee</a></li>
              <?php endif; ?>
            </ul>
          </li>
            <!-- ------------------------------------------------------------------------------- -->
          <li class="sub-menu active open">
            <a <?= $view; ?> href="javascript:;"class="menu-toggle dcjq-parent active" >
             <i class="fa fa-archive"></i>
                  <span>Requests</span>
                <?php if ($designation != 77  && $designation != 97): ?>
                  <?php if (in_array($idno, $approvers)): ?>
                    <span id="credit-notification-badge" class="badge" style="color: white; background-color: red; border-radius: 10px; font-size: 11px; margin-left: 70px;"></span>
                  <?php endif; ?>
                <?php endif; ?>
              </a>
            <ul class="sub">
             <?php if ($designation != 77  && $designation != 97 && $designation != 78 && $designation != 116): ?>
                  <li<?= $view; ?>><a href="manageleaveapplication.php">Leave Requests 
                    <span id="leave-notification-badge" class="badge" style="color: white; background-color: red; border-radius: 10px; font-size: 11px; margin-left: 40px;"></span>
                  </a></li>
                  <li class="active"<?= $view; ?>><a href="managemissedlogapplication.php">Missed Log Requests 
                    <span id="ml-notification-badge" class="badge" style="color: white; background-color: red; border-radius: 10px; font-size: 11px; margin-left: 9px;"></span>
                  </a></li>
                  <li <?= $view; ?>><a href="manageovertimeapplication.php">Overtime Requests
                    <span id="ot-notification-badge" class="badge" style="color: white; background-color: red; border-radius: 10px; font-size: 11px; margin-left: 20px;"></span>
                  </a></li>
                <?php endif; ?>
                <?php if ($designation == 77 || $designation == 97): ?>
                  <li <?= $view; ?>><a href="dashboard.php?monitoringmanagemissedlogapplication">Missed Log Application 
                    <span id="mml-notification-badge" class="badge" style="color: white; background-color: red;"></span>
                  </a></li>
                <?php endif; ?>
                <?php if ($designation == 78||$designation == 116):?>
                  <li><a href="dashboard.php?EEOapplication">EEO Requests 
                    <span id="eeo-notification-badge" class="badge" style="color: white; background-color: red;"></span>
                  </a></li>
                <?php endif; ?>
                <?php if (in_array($idno, $approvers) && $designation != 78 && $designation != 116 && $designation != 77 && $designation != 97): ?>
                  <li><a href="manageEEOapplication.php">EEO Requests 
                    <span id="eeo-notification-badge" class="badge" style="color: white; background-color: red; border-radius: 10px; font-size: 11px; margin-left: 47px;"></span>
                  </a></li>
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

<?php
// Get the logged-in user ID
$userId = $_SESSION['idno'];
// Fetch user details 
$userQuery = mysqli_query($con, "SELECT ep.lastname, jt.jobtitle, ed.designation, ed.department 
                                 FROM employee_details ed 
                                 INNER JOIN employee_profile ep ON ep.idno = ed.idno 
                                 INNER JOIN jobtitle jt ON jt.id = ed.designation 
                                 WHERE ed.idno = '$userId'");
$userDetails = mysqli_fetch_assoc($userQuery);
// Extract user designation
$designation = $userDetails['designation']; 
$department = $userDetails['department'];
// Fetch requesting officers, companies, and departments
$sqlProtocol = mysqli_query($con, "SELECT requestingofficer, company, department FROM leave_protocols WHERE approvingofficer = '$userId'");
$requestingOfficers = [];
$requestingCompany = [];
$requestingDepartment = [];

if (mysqli_num_rows($sqlProtocol) > 0) {
    while ($protocol = mysqli_fetch_assoc($sqlProtocol)) {
        if ($protocol['requestingofficer']) $requestingOfficers[] = $protocol['requestingofficer'];
        if ($protocol['company']) $requestingCompany[] = $protocol['company'];
        if ($protocol['department']) $requestingDepartment[] = $protocol['department'];
    }
}

// Convert to strings for SQL IN clauses
$requestingOfficersStr = !empty($requestingOfficers) ? "'" . implode("','", $requestingOfficers) . "'" : null;
$requestingCompStr = !empty($requestingCompany) ? "'" . implode("','", $requestingCompany) . "'" : null;
$requestingDeptStr = !empty($requestingDepartment) ? "'" . implode("','", $requestingDepartment) . "'" : null;
// Convert requesting officers array into a string for SQL query
$requestingOfficersStr = implode("','", $requestingOfficers);

// Handle approval action for missed log
if (isset($_GET['approved']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    $datetime = date('M j, Y - g:i A');
    $approval = "{$userDetails['lastname']} ({$userDetails['jobtitle']})";
    $sqlUpdate = mysqli_query($con, "UPDATE missed_log_application SET applic_status='Approved - $approval [$datetime]', view_status='Unseen' WHERE id='$id'");

    if ($sqlUpdate) {
        echo "<script>alert('Missed log application successfully approved!'); window.location='?managemissedlogapplication';</script>";
    } else {
        echo "<script>alert('Unable to approve missed log application!'); window.location='?managemissedlogapplication';</script>";
    }
}

// Handle disapproval action for missed log
if (isset($_GET['disapproved']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the ID
    $approval = "{$userDetails['lastname']} ({$userDetails['jobtitle']})";
    $datetime = date('M j, Y - g:i A');
    $sqlUpdate = mysqli_query($con, "UPDATE missed_log_application SET applic_status='Disapproved - $approval [$datetime]', view_status='Unseen' WHERE id='$id'");

    if ($sqlUpdate) {
        echo "<script>alert('Missed log application successfully disapproved!'); window.location='?managemissedlogapplication';</script>";
    } else {
        echo "<script>alert('Unable to disapprove missed log application!'); window.location='?managemissedlogapplication';</script>";
    }
}

// Handle undo action for leave application
if (isset($_GET['undo']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    $sqlUpdate = mysqli_query($con, "UPDATE missed_log_application SET applic_status='Pending', view_status='Unseen' WHERE id='$id'");

    if ($sqlUpdate) {
        echo "<script>alert('Action successfully undone!'); window.location='?managemissedlogapplication';</script>";
    } else {
        echo "<script>alert('Action taken was not successful!'); window.location='?managemissedlogapplication';</script>";
    }
}
?>

<section id="main-content">
      <section class="wrapper">
        <div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <h4><a href="?main" style="text-decoration: none;"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-file-text"></i> MANAGE MISSED LOG APPLICATION</h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                <thead>
                    <tr>
                        <th width="2%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">No.</th>
                        <th width="9%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Employee ID</th>
                        <th width="12%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Employee Name</th>
                        <th width="5%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Department</th>
                        <th width="7%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Work Area</th>
                        <th width="9%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Date of Missed Time IN/OUT</th>
                        <th width="5%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Incident</th>
                        <th width="5%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Time</th>
                        <th style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Reason</th>
                        <th width="12%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Date and Time Applied</th>
                        <th width="10%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Status</th>
                        <th style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">HR's Remarks</th>
                        <th style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Monitoring's Remarks</th>
                        <th style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Remarks</th>
                        <th width="8%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $x = 1;

                    // Initialize an empty array for conditions
                    $conditions = [];

                    // Query to fetch the approver's combinations
                    $approverQuery = "SELECT company, department, requestingofficer, shift 
                                    FROM leave_protocols 
                                    WHERE approvingofficer = '$userId'";
                    $result = mysqli_query($con, $approverQuery);

                    if (!$result) {
                        die("Database query failed: " . mysqli_error($con));
                    }

                    // Loop through each row and build a condition
                    while ($row = mysqli_fetch_assoc($result)) {
                        $clauseParts = [];

                        // Build conditions based on non-null values
                        if (!empty($row['shift'])) {
                            $clauseParts[] = "ed.startshift = '{$row['shift']}'";
                        }
                        if (!empty($row['company'])) {
                            $clauseParts[] = "ed.company = '{$row['company']}'";
                        }
                        if (!empty($row['department'])) {
                            $clauseParts[] = "ed.department = '{$row['department']}'";
                        }
                        if (!empty($row['requestingofficer'])) {
                            $clauseParts[] = "ed.designation = '{$row['requestingofficer']}'";
                        }

                        // Combine the conditions for this specific row
                        if (!empty($clauseParts)) {
                            $conditions[] = '(' . implode(' AND ', $clauseParts) . ')';
                        }
                    }

                    // Join all conditions with OR to match any valid combination
                    $whereClause = !empty($conditions) ? implode(' OR ', $conditions) : '1=1';

                    // Build the final query
                    $query = "SELECT ml.*, ml.id as mlid, ep.*, ed.* 
                            FROM missed_log_application ml 
                            INNER JOIN employee_profile ep ON ep.idno = ml.idno 
                            INNER JOIN employee_details ed ON ed.idno = ep.idno 
                            WHERE ml.idno != '$userId' 
                            AND ($whereClause)
                            ORDER BY 
                                CASE WHEN ml.applic_status='Pending' THEN 1 ELSE 2 END, 
                                ml.date_applied DESC,
                                ml.time_applied DESC";

                    // Debugging: Print the final query
                    // echo "Final Query: " . $query;


                    // Execute query
                    $sqlEmployee = mysqli_query($con, $query);

                    // Check if query was successful
                    if (!$sqlEmployee) {
                        die("Query failed: " . mysqli_error($con));
                    }
                        

                    // Fetch and display results
                    if (mysqli_num_rows($sqlEmployee) > 0) {
                        $x = 1;
                        while ($company = mysqli_fetch_array($sqlEmployee)) {
                            $appStatus = $company['applic_status'];
                            $idno = $company['idno'];
                            $style = "class='primary'"; // Default style
                            
                            if (strpos($appStatus, 'Approved') !== false) {
                                $style = "class='success'";
                            } elseif (strpos($appStatus, 'Disapproved') !== false) {
                                $style = "class='danger'";
                            } elseif (strpos($appStatus, 'Pending') !== false) {
                                $style = "class='warning'";
                            }
                            $statusText = $appStatus;

                            $sqlDepartment = mysqli_query($con, "SELECT ed.department, d.department, ed.*, d.*
                                            FROM employee_details ed
                                            INNER JOIN department d ON d.id = ed.department
                                            WHERE idno = '$idno'");
                            
                            if(mysqli_num_rows ($sqlDepartment) > 0) {
                                $row = mysqli_fetch_assoc($sqlDepartment);
                                $department = $row['department'];
                            }

                                echo "<tr $style>";
                                echo "<td style='text-align: center; vertical-align: middle'>$x.</td>";
                                echo "<td style='text-align: center; vertical-align: middle'>$idno</td>";
                                echo "<td style='text-align: justify; vertical-align: middle'>
                                        <span style='font-weight: bold; font-size: 1.1em;'>{$company['lastname']}</span>, {$company['firstname']}
                                    </td>";
                                echo "<td style='text-align: center; vertical-align: middle'>$department</td>"; 
                                echo "<td style='text-align: center; vertical-align: middle'>{$company['location']}</td>";
                                echo "<td style='text-align: center; vertical-align: middle'>" . date('M j, Y', strtotime($company['datemissed'])) . "</td>";
                                echo "<td style='text-align: center; vertical-align: middle'>{$company['incident']}</td>";
                                echo "<td style='text-align: center; vertical-align: middle'>" . date("g:i A", strtotime($company['mttime'])) . "</td>";
                                echo "<td style='text-align: justify; vertical-align: middle'>{$company['reason']}</td>";
                                echo "<td style='text-align: center; vertical-align: middle'>" . date('M j, Y', strtotime($company['date_applied'])) . "<br>" . date('g:i:s A', strtotime($company['time_applied'])) . "</td>";
                                echo "<td style='text-align: center; vertical-align: middle'>$statusText</td>";
                                echo "<td style='text-align: justify; vertical-align: middle'>{$company['remarks']}</td>";
                                echo "<td style='text-align: " . (($company['monitoring_remarks'] == 'verified') ? 'center' : 'justify') . "; vertical-align: middle;'>
                                        {$company['monitoring_remarks']}
                                    </td>";
                                echo "<td style='text-align: justify; vertical-align: middle'>{$company['approver_remarks']}</td>";
                                echo "<td style='text-align: center; vertical-align: middle'>";
                                        if ($appStatus == "Pending") {
                                            echo "<a href='?managemissedlogapplication&id={$company['mlid']}&approved' class='btn btn-success btn-xs' title='Approve' onclick=\"return confirm('Do you wish to approve this missed log application?'); return false;\"><i class='fa fa-thumbs-up'></i></a>&nbsp;";
                                            echo "<a href='?managemissedlogapplication&id={$company['mlid']}&disapproved' class='btn btn-danger btn-xs' title='Disapprove' onclick=\"return confirm('Do you wish to disapprove this missed log application?'); return false;\"><i class='fa fa-thumbs-down'></i></a>&nbsp;";
                                            echo "<a href='?managemissedlogapplication&addremarks&id={$company['mlid']}&approver_remarks' class='btn btn-primary btn-xs' title='Remarks');\"><i class='fa fa-comment'></i></a>";
                                        } else {
                                            echo "<a href='?managemissedlogapplication&id={$company['mlid']}&undo' class='btn btn-warning btn-xs' title='Undo Action' onclick=\"return confirm('Do you wish to undo the action taken?'); return false;\"><i class='fa fa-undo'></i></a>";
                                        }
                                echo "</td>";
                                echo "</tr>";
                                $x++;
                            }
                        } else {
                            echo "<tr><td colspan='12' align='center'>No records found!</td></tr>";
                        }
                        ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Check if the user clicked 'Add Remarks'
if (isset($_GET['addremarks'])) {
    $id = $_GET['id'];
    $remarks = urldecode($_GET['approver_remarks']); // Use urldecode to handle special characters
    
?>
    <!-- Remarks Form -->
    <div class="modal-overlay">
    <div class="modal-container">
        <div class="content-panel">
            <div class="panel-heading-">
                <h4>
                    <a href="?managemissedlogapplication"><i class="fa fa-arrow-left"></i> Close</a> |
                    <i class="fa fa-file-text"></i> REMARKS
                </h4>
            </div>
            <div class="panel-body">
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?= $id; ?>">
                    <div class="form-group">
                        <textarea name="remarks" class="form-control" rows="5" placeholder="Add Remarks"><?= htmlspecialchars($remarks); ?></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submitRemarks" class="btn btn-primary" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
}

// Handle form submission for updating remarks
if (isset($_POST['submitRemarks'])) {
    $id = $_POST['id'];
    $remarks = mysqli_real_escape_string($con, $_POST['remarks']); // Sanitize input
    
    // Update remarks in the database
    $sqlUpdateRemarks = "UPDATE missed_log_application SET approver_remarks = '$remarks', remarks_view_status='Unseen' WHERE id = '$id'";
    if (mysqli_query($con, $sqlUpdateRemarks)) {
        echo "<script>alert('Remarks updated successfully.');</script>";
        echo "<script>window.location.href='?managemissedlogapplication';</script>"; // Redirect after update
    } else {
        echo "<script>alert('Error updating remarks: " . mysqli_error($con) . "');</script>";
    }
}
?>

<style>
/* Modal Overlay to Blur Background */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
}

/* Modal Container */
.modal-container {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    width: 400px;
    max-width: 90%;
    z-index: 1000;
}

/* Panel Heading Styling */
.panel-heading- {
    text-align: center;
    margin-bottom: 20px;
}

/* Close Button */
.panel-heading- a {
    color: #333;
    text-decoration: none;
}

/* Form Input and Button Styling */
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-group input[type="submit"] {
    width: 100%;
    padding: 10px;
    border: none;
    background-color: #007bff;
    color: white;
    cursor: pointer;
    border-radius: 4px;
    font-size: 16px;
}

/* Change button color on hover */
.form-group input[type="submit"]:hover {
    background-color: #0056b3;
}

/* Remove bottom border on table headers */
table.dataTable thead th {
  border-bottom: none !important;
}
/* Adjust font size for table headers */
table.dataTable thead th {
  font-size: 12px !important;
}

/* Adjust font size for table body */
table.dataTable tbody td {
  font-size: 12px !important;
}

/* Reset default text appearance outside the table */
.top-menu a,
.logo,
.panel-heading{
  text-decoration: none !important;
}

/* Fix 'Records per page' dropdown font size */
.dataTables_length label,
.dataTables_length select {
  font-size: 16px !important;
}

/* Fix 'Showing X to Y of Z entries' text */
.dataTables_info {
  font-size: 16px !important;
}

/* Fix pagination numbers */
.dataTables_paginate a,
.dataTables_paginate span {
  font-size: 16px !important;
  text-decoration: none !important;
}

.dataTables_paginate a {
  text-decoration: none !important;
}

/* Optional: fix search box font size */
.dataTables_filter label,
.dataTables_filter input {
  font-size: 16px !important;
}
.top-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
}

.dataTables_length {
    float: none !important;
}

.dataTables_filter {
    float: none !important;
    text-align: left !important;
}
</style>
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
    $('#hidden-table-info').DataTable({
        "dom": '<"top-wrapper"l f>rt<"bottom"ip>'
    });
});
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
$(document).ready(function() {
  $.ajax({
    type: 'GET',
    url: 'getnotifications.php',
    dataType: 'json',
    success: function(data) {
                console.log('AJAX call successful');

                // Check if data properties exist
                //For leave count
                if (data.leave_count !== undefined) {
                    if (data.leave_count > 0) {
                        $('#leave-notification-badge').html(data.leave_count).show();
                    } else {
                        $('#leave-notification-badge').hide(); // Hide if count is 0
                    }
                } else {
                    console.error('Leave count missing in response.');
                }
                //For OT count
                if (data.ot_count !== undefined) {
                    if (data.ot_count > 0) {
                        $('#ot-notification-badge').html(data.ot_count).show();
                    } else {
                        $('#ot-notification-badge').hide(); // Hide if count is 0
                    }
                } else {
                    console.error('OT count missing in response.');
                }
                //For Missed Log Count
                if (data.ml_count !== undefined) {
                    if (data.ml_count > 0) {
                        $('#ml-notification-badge').html(data.ml_count).show();
                    } else {
                        $('#ml-notification-badge').hide(); // Hide if count is 0
                    }
                } else {
                    console.error('ML count missing in response.');
                }               
                //For Monitoring Missed Log Count
                if (data.mml_count !== undefined) {
                    if (data.mml_count > 0) {
                        $('#mml-notification-badge').html(data.mml_count).show();
                    } else {
                        $('#mml-notification-badge').hide(); // Hide if count is 0
                    }
                } else {
                    console.error('MML count missing in response.');
                }
                 //For EEO Count
                 if (data.eeo_count !== undefined) {
                    if (data.eeo_count > 0) {
                        $('#eeo-notification-badge').html(data.eeo_count).show();
                    } else {
                        $('#eeo-notification-badge').hide(); // Hide if count is 0
                    }
                } else {
                    console.error('EEO count missing in response.');
                }  
                //Total
                if (data.total_count > 0) {
                    $('#credit-notification-badge').html(data.total_count).show();
                } else {
                    $('#credit-notification-badge').hide();
                }
        },
        error: function(xhr, status, error) {
            console.error('AJAX call failed: ' + status + ', ' + error);
        }
    });
});
// Function to fetch and display infraction notifications
function InfractionNotif() {
    fetch('infractionnotif.php')
        .then(response => response.json())
        .then(data => {
            console.log('Fetched notification in InfractionNotif:', data); // Debugging log

            const infractionNotif = document.getElementById('infraction-notif');
            const manageInfractionLink = document.getElementById('manage-infraction-link');

            if (!infractionNotif || !manageInfractionLink) {
                console.error("Missing elements for infraction notification");
                return;
            }

            if (data.infractionNotif > 0) {
                infractionNotif.style.display = 'inline-block';
                infractionNotif.textContent = ''; // Hide count

                if (data.infractionIds.length > 0) {
                    // Convert array to comma-separated string and pass all IDs in the URL
                    const infractionIdsString = data.infractionIds.join(',');
                    manageInfractionLink.href = `dashboard.php?manageinfraction&ids=${infractionIdsString}`;
                    manageInfractionLink.setAttribute('data-infraction-ids', infractionIdsString);
                }
            } else {
                infractionNotif.style.display = 'none';
            }
        })
        .catch(error => console.error('Error fetching notification in InfractionNotif:', error));
}

// Call on page load
window.onload = function() {
    InfractionNotif();
};

// Function to mark an infraction as viewed
function markInfViewed() {
    const manageInfractionLink = document.getElementById('manage-infraction-link');
    const infractionIds = manageInfractionLink.getAttribute('data-infraction-ids');

    if (!infractionIds) {
        console.error("No infraction IDs found in the link.");
        return;
    }

    console.log("Sending IDs to mark as viewed:", infractionIds); // Debugging

    fetch('markInfractionsAsViewed.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `ids=${encodeURIComponent(infractionIds)}` // Pass IDs
    })
    .then(response => response.json())
    .then(data => {
        console.log("Server response:", data); // Debugging

        if (data.success) {
            console.log('Infractions marked as viewed:', infractionIds);

            // Hide the notification dot
            const notifElement = document.getElementById('infraction-notif');
            if (notifElement) {
                notifElement.style.display = 'none';
            }

            // Refresh notification count
            setTimeout(InfractionNotif, 1000);
        } else {
            console.error('Failed to update view status:', data.message);
        }
    })
    .catch(error => console.error('Error updating view status:', error));
}

// Auto-refresh notifications every 5 seconds
setInterval(InfractionNotif, 5000);
InfractionNotif(); // Run immediately on page load

//Function for Employee Infraction Notif
function EmpInfractionNotif() {
  fetch('employeeinfractionnotif.php')
    .then(response => response.json())
    .then(data => {
      console.log('Fetched notification in EmpInfractionNotif:', data); // Log fetched data

      const empInfractionNotif = document.getElementById('emp-infraction-notif');
      if (!empInfractionNotif) {
        console.error("Element with ID 'emp-infraction-notif' not found.");
        return;
      }
      // Show notification indicators without displaying counts
      if (data.emp_infraction_notif > 0) {
        empInfractionNotif.style.display = 'inline-block';
        empInfractionNotif.textContent = ''; // Hide count
      } else {
        empInfractionNotif.style.display = 'none';
      }
    })
    .catch(error => console.error('Error fetching notification EmpInfractionNotif:', error));
}

window.onload = function() {
    console.log("Window fully loaded, calling EmpInfractionNotif()");
    EmpInfractionNotif();
};

// Function to mark notification as viewed when clicking menu item
function markNotifViewed() {
  fetch('markInfractionSeen.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Hide the corresponding notification dot
      document.getElementById('emp-infraction-notif').style.display = 'none';
      
      // Refresh notifications to check if any new ones remain
      setTimeout(EmpInfractionNotif, 1000);
    } else {
      console.error('Failed to update view status:', data.message);
    }
  })
  .catch(error => console.error('Error updating view status:', error));
}

// Auto-refresh notifications every 5 seconds
setInterval(EmpInfractionNotif, 5000);
EmpInfractionNotif(); // Run immediately on page load

//Function for Applications Notif
function AppNotif() {
  fetch('applicationStatusNotif.php')
    .then(response => response.json())
    .then(data => {
      console.log('Fetched notification data AppNotif:', data); // Log fetched data

      const leaveNotif = document.getElementById('leave-notif');
      const missedLogNotif = document.getElementById('missedlog-notif');
      const overtimeNotif = document.getElementById('overtime-notif');
      const eeoNotif = document.getElementById('eeo-notif');
      const appMenuNotif = document.getElementById('app-menu-notif');

      if (!leaveNotif || !missedLogNotif || !overtimeNotif || !eeoNotif || !appMenuNotif) {
        console.error("One or more notification elements are missing.");
        return;
      }

      let hasNotification = false;

      // Show notification indicators without displaying counts
      if (data.leave_notif > 0) {
        leaveNotif.style.display = 'inline-block';
        leaveNotif.textContent = ''; // Hide count
        hasNotification = true;
      } else {
        leaveNotif.style.display = 'none';
      }

      if (data.missedlog_notif > 0) {
        missedLogNotif.style.display = 'inline-block';
        missedLogNotif.textContent = ''; // Hide count
        hasNotification = true;
      } else {
        missedLogNotif.style.display = 'none';
      }

      if (data.overtime_notif > 0) {
        overtimeNotif.style.display = 'inline-block';
        overtimeNotif.textContent = ''; // Hide count
        hasNotification = true;
      } else {
        overtimeNotif.style.display = 'none';
      }

      if (data.eeo_notif > 0) {
        eeoNotif.style.display = 'inline-block';
        eeoNotif.textContent = ''; // Hide count
        hasNotification = true;
      } else {
        eeoNotif.style.display = 'none';
      }

      // Show or hide the main menu notification
      if (hasNotification) {
        appMenuNotif.style.display = 'inline-block';
        appMenuNotif.textContent = ''; // Keep an indicator like "!"
      } else {
        appMenuNotif.style.display = 'none';
      }
    })
    .catch(error => console.error('Error fetching notification in AppNotif:', error));
}


window.onload = function() {
    console.log("Window fully loaded, calling AppNotif()");
    AppNotif();
};

// Function to mark notification as seen when clicking submenu item
function markNotifseen(type) {
  fetch('updateViewStatus.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `type=${type}`,
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Hide the corresponding notification dot
      document.getElementById(type + '-notif').style.display = 'none';
      
      // Refresh notifications to check if any unseen ones remain
      setTimeout(AppNotif, 1000);
    } else {
      console.error('Failed to update view status:', data.message);
    }
  })
  .catch(error => console.error('Error updating view status:', error));
}

// Auto-refresh notifications every 5 seconds
setInterval(AppNotif, 5000);
AppNotif(); // Run immediately on page load
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