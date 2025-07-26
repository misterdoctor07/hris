<style>
    /*Date Filter Button*/
    .filter-btn {
        background-color: #3f4d6a;
        color: white;
        border: none;
        padding: 7px 20px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }
    
    .filter-btn:hover {
        background-color: #181e2e;
    }
    
    .reset-btn {
        border: #eaeaea 0.5px solid;
        padding: 7px 20px;
        border-radius: 5px;
    }
    /*Announcement*/
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
        width: 1000px;
        max-width: 100%;
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
<?php
    date_default_timezone_set("Asia/Manila");
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['idno'])) {
        die("<script>alert('Session expired. Please log in again.'); window.location='/index.php';</script>");
    }
    include('../config.php');
  
    // Restrict access if not logged in
    if (!isset($_SESSION['idno'])) {
        die("<script>alert('Session expired. Please log in again.'); window.location='/index.php';</script>");
    }
    // Get user's access level
    $fullname = $_SESSION['fullname'];
    $access = $_SESSION['access'];
    
    $idno=$_SESSION['idno'];
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
        <link rel="icon" type="image/x-icon" href="img/iconhris_2.png">
    
        <!-- Bootstrap core CSS -->
        <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap 3 CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
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
        <header class="header black-bg">
            <div class="sidebar-toggle-box">
                <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
            </div>
            <a href="?main" class="logo"><b>EMPLOYEE PORTAL</b></a>
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
                $sqlWidgets = mysqli_query($con, "SELECT w.*, ar.id as read_status 
                    FROM widgets w
                    LEFT JOIN announcement_reads ar ON w.id = ar.announcement_id AND ar.user_id = '$user_id'
                    WHERE w.type='Announcement' 
                    AND DATE(w.datearray) = '$today'
                    ORDER BY w.datearray DESC, w.timearray DESC");

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
                            <span id="app-menu-notif" style="width: 10px; height: 10px; background: red; border-radius: 50%; display: none; position: absolute; top: 8px; right: 25px;"></span>
                        </a>
                        <ul class="sub">
                            <li>
                                <a href="manageleave.php"> Manage Leave <span id="leave-notif" style="width: 10px; height: 10px; background: red; border-radius: 50%; display: none; position: absolute; top: 10px; left: 155px;"></span></a>
                            </li>
                            <li><a href="applymissedlog.php"> Apply Missed Log <span id="missedlog-notif" style="width: 10px; height: 10px; background: red; border-radius: 50%; display: none; position: absolute; top: 10px; left: 155px;"></span></a></li>
                            <li><a href="applyovertime.php"> Apply Overtime <span id="overtime-notif" style="width: 10px; height: 10px; background: red; border-radius: 50%; display: none; position: absolute; top: 10px; left: 155px;"></span></a></li>
                            <li><a href="emergencyearlyout.php">Apply EEO <span id="eeo-notif" style="width: 10px; height: 10px; background: red; border-radius: 50%; display: none; position: absolute; top: 10px; left: 155px;"></span></a></li>
                            <?php if ($designation == 114): ?>
                                <li><a href="manageemployee.php">Add Leave for Employee</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
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
                                    <span id="leave-notification-badge" class="badge" style="color: white; background-color: red; border-radius: 10px; font-size: 11px; margin-left: 40px;"></span></a>
                                </li>
                                <li class="active"<?= $view; ?>><a href="managemissedlogapplication.php">Missed Log Requests 
                                    <span id="ml-notification-badge" class="badge" style="color: white; background-color: red; border-radius: 10px; font-size: 11px; margin-left: 9px;"></span></a>
                                </li>
                                <li <?= $view; ?>><a href="manageovertimeapplication.php">Overtime Requests
                                    <span id="ot-notification-badge" class="badge" style="color: white; background-color: red; border-radius: 10px; font-size: 11px; margin-left: 20px;"></span></a>
                                </li>
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
                                 <?php if ($designation == 97 || $designation == 50 || $designation == 65 || $designation == 89 || $designation == 104 || $designation == 105 || $designation == 114 || $designation == 93 || $designation == 115): ?>
                  <li class="active"><a class="active" href="manageEEOapplication.php">Movement Requests
                  </a></li>
                <?php endif; ?>
                        </ul>
                    </li>
                    <li>
                        <a href="dashboard.php?viewpayroll">
                            <i class="fa fa-credit-card"></i>
                            <span>Payslip</span>
                        </a>
                    </li>
                    <li>
                        <a href="dashboard.php?infractions">
                            <i class="fa fa-folder-open"></i>
                            <span>Infractions</span>
                            <span id="emp-infraction-notif" style="width: 10px; height: 10px; background: red; border-radius: 50%; display: none; position: absolute; top: 10px; left: 157px;"></span>
                        </a>
                    </li>
                    <?php if ($designation == 8 || $designation == 50 || $designation == 89 || $designation == 59 || $designation == 65 || $designation == 94 || $designation == 102 || $designation == 3 || $designation == 88 || $designation == 114 || $designation == 92 || $userId == '103417'): ?>
                        <li>
                            <a href="dashboard.php?manageinfraction">
                                <i class="fa fa-bell"></i>
                                <span>Manage Infraction</span>
                                <span id="notificationDot" style="background:red; width:10px; height:10px; border-radius:50%; display:none;"></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($designation == 97 || $designation == 77): ?>
                        <li>
                            <a href="dashboard.php?errorcatching">
                                <i class="fa fa-eye"></i>
                                <span>HRIS Monitoring</span>
                            </a>
                        </li>
                    <?php endif; ?>
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
                    ?>
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
                $approval = "{$userDetails['lastname']} ({$userDetails['jobtitle']})";
                $datetime = date('M j, Y - g:i A');
                
                if($designation == '59' || $userId == '111111'){
                    $sqlUpdate = mysqli_query($con, "UPDATE wfh_application SET TL_approve='Approved - $approval [$datetime]', view_status='Unseen' WHERE id='$id'");
                } else if($designation == '65'){
                    $sqlUpdate = mysqli_query($con, "UPDATE wfh_application SET TM_approve='Approved - $approval [$datetime]', view_status='Unseen' WHERE id='$id'");
                } else if($designation == '93'){
                    $sqlUpdate = mysqli_query($con, "UPDATE wfh_application SET IT_approve='Approved - $approval [$datetime]', view_status='Unseen', application_status='Approved' WHERE id='$id'");
                } else {
                    echo "<script>alert('You are not allowed to approve this application!'); window.location='?manage_wfhapplication';</script>";
                }
            
                if ($sqlUpdate) {
                    echo "<script>alert('Application successfully approved!'); window.location='?manage_wfhapplication';</script>";
                } else {
                    echo "<script>alert('Unable to approve application!'); window.location='?manage_wfhapplication';</script>";
                }
            }
            
            // Handle disapproval action for missed log
            if (isset($_GET['disapproved']) && isset($_GET['id'])) {
                $id = intval($_GET['id']); // Sanitize the ID
                $approval = "{$userDetails['lastname']} ({$userDetails['jobtitle']})";
                $datetime = date('M j, Y - g:i A');
                
                if($designation == '59'){
                    $sqlUpdate = mysqli_query($con, "UPDATE wfh_application SET TL_approve='Disapproved - $approval [$datetime]', application_status='Disapproved', view_status='Unseen' WHERE id='$id'");
                } else if($designation == '65'){
                    $sqlUpdate = mysqli_query($con, "UPDATE wfh_application SET TM_approve='Disapproved - $approval [$datetime]', application_status='Disapproved', view_status='Unseen' WHERE id='$id'");
                } else if($designation == '93'){
                    $sqlUpdate = mysqli_query($con, "UPDATE wfh_application SET IT_approve='Disapproved - $approval [$datetime]', application_status='Disapproved', view_status='Unseen' WHERE id='$id'");
                } else {
                    echo "<script>alert('You are not allowed to disapprove this application!'); window.location='?manage_wfhapplication';</script>";
                }
            
                if ($sqlUpdate) {
                    echo "<script>alert('Application successfully disapproved!'); window.location='?manage_wfhapplication';</script>";
                } else {
                    echo "<script>alert('Unable to disapprove application!'); window.location='?manage_wfhapplication';</script>";
                }
            }
            
            // Handle undo action for leave application
            if (isset($_GET['undo']) && isset($_GET['id'])) {
                $id = intval($_GET['id']); 
            
                $sqlUpdate = mysqli_query($con, "UPDATE missed_log_application SET applic_status='Pending', view_status='Unseen' WHERE id='$id'");
                
                if($designation == '59' || $userId == '111111'){
                    $sqlUpdate = mysqli_query($con, "UPDATE wfh_application SET TL_approve = NULL, view_status='Unseen' WHERE id='$id'");
                } else if($designation == '65'){
                    $sqlUpdate = mysqli_query($con, "UPDATE wfh_application SET TM_approve = NULL, view_status='Unseen' WHERE id='$id'");
                } else if($designation == '93'){
                    $sqlUpdate = mysqli_query($con, "UPDATE wfh_application SET IT_approve = NULL, view_status='Unseen', application_status='Pending' WHERE id='$id'");
                } else {
                    echo "<script>alert('You are not allowed to do this action in this application!'); window.location='?manage_wfhapplication';</script>";
                }
            
                if ($sqlUpdate) {
                    echo "<script>alert('Action successfully undone!'); window.location='?manage_wfhapplication';</script>";
                } else {
                    echo "<script>alert('Action taken was not successful!'); window.location='?manage_wfhapplication';</script>";
                }
            }
        ?>
        <section id="main-content">
            <section class="wrapper">
                <div class="col-lg-12">
                    <div class="content-panel">
                        <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                            <h4 style="margin: 0;">
                                <a href="?main" style="text-decoration: none;">
                                    <i class="fa fa-arrow-left"></i> HOME
                                </a> |
                                <i class="fa fa-suitcase"></i> MANAGE WFH ARRANGEMENT APPLICATION
                            </h4>
                            <!-- Date Filter Section -->
                            <div class="date-filter" style="display: flex; align-items: center; gap: 15px; font-size: 14px; font-weight: 500; margin-top: 10px; margin-right: 5px">
                                <h5 style="margin: 0; font-size: 14px; font-weight: 600;">Filter Date of Transfer</h5>
                            
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <label for="fromDate" style="margin-bottom: 0;">From:</label>
                                    <input type="date" id="fromDate" class="form-control"
                                           value="<?php echo isset($_GET['fromDate']) ? $_GET['fromDate'] : ''; ?>"
                                           style="width: 150px; height: 35px; font-size: 14px;">
                                </div>
                            
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <label for="toDate" style="margin-bottom: 0;">To:</label>
                                    <input type="date" id="toDate" class="form-control"
                                           value="<?php echo isset($_GET['toDate']) ? $_GET['toDate'] : ''; ?>"
                                           style="width: 150px; height: 35px; font-size: 14px;">
                                </div>
                            
                                <button id="filterButton" type="button" onclick="filterByDate()" class="filter-btn" style="font-size: 14px; font-weight: 500;">Filter</button>
                                <button type="button" onclick="resetFilter()" class="reset-btn" style="font-size: 14px; font-weight: 500;">Reset</button>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                                <thead>
                                    <tr>
                                        <th width="1%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">No.</th>
                                        <th width="9%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Employee ID</th>
                                        <th width="12%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Employee Name</th>
                                        <th width="9%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Date of Transfer</th>
                                        <th width="12%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Date and Time Applied</th>
                                        <th width="7%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Status</th>
                                        <th style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">TL's Note</th>
                                        <th style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">TM's Note</th>
                                        <th style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">IT's Note</th>
                                        <th width="7%" style="text-align: center; vertical-align: middle; background-color:#20273a; color: white;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $x = 1;
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
                                        
                                        if ($designation == '59' || $userId == '111111') {
                                            // Build the final query
                                            $fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : null;
                                            $toDate = isset($_GET['toDate']) ? $_GET['toDate'] : null;
                                            $query = "SELECT wfh.*, wfh.id as wfhid, ep.*, ed.* 
                                                    FROM wfh_application wfh 
                                                    INNER JOIN employee_profile ep ON ep.idno = wfh.idno 
                                                    INNER JOIN employee_details ed ON ed.idno = ep.idno 
                                                    WHERE ed.department = '$department'
                                                    AND (wfh.date_effective BETWEEN '$fromDate' AND '$toDate' OR '$fromDate' = '' OR '$toDate' = '')
                                                    ORDER BY 
                                                        CASE WHEN wfh.application_status='Pending' THEN 1 ELSE 2 END, 
                                                        wfh.datetime DESC";
                                        } else if ($designation == '65') {
                                            // Build the final query
                                            $fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : null;
                                            $toDate = isset($_GET['toDate']) ? $_GET['toDate'] : null;
                                            $query = "SELECT wfh.*, wfh.id as wfhid, ep.*, ed.* 
                                                    FROM wfh_application wfh 
                                                    INNER JOIN employee_profile ep ON ep.idno = wfh.idno 
                                                    INNER JOIN employee_details ed ON ed.idno = ep.idno 
                                                    WHERE wfh.idno != '$userId' 
                                                    AND ed.department = '$department'
                                                    AND wfh.TL_approve LIKE '%Approved%'
                                                    AND (wfh.date_effective BETWEEN '$fromDate' AND '$toDate' OR '$fromDate' = '' OR '$toDate' = '')
                                                    ORDER BY 
                                                        CASE WHEN wfh.application_status='Pending' THEN 1 ELSE 2 END, 
                                                        wfh.datetime DESC";
                                        } else if ($designation == '93') {
                                            // Build the final query
                                            $fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : null;
                                            $toDate = isset($_GET['toDate']) ? $_GET['toDate'] : null;
                                            $query = "SELECT wfh.*, wfh.id as wfhid, ep.*, ed.* 
                                                    FROM wfh_application wfh 
                                                    INNER JOIN employee_profile ep ON ep.idno = wfh.idno 
                                                    INNER JOIN employee_details ed ON ed.idno = ep.idno 
                                                    WHERE wfh.idno != '$userId' 
                                                    AND wfh.TL_approve LIKE '%Approved%'
                                                    AND wfh.TM_approve LIKE '%Approved%'
                                                    AND (wfh.date_effective BETWEEN '$fromDate' AND '$toDate' OR '$fromDate' = '' OR '$toDate' = '')
                                                    ORDER BY 
                                                        CASE WHEN wfh.application_status='Pending' THEN 1 ELSE 2 END, 
                                                        wfh.datetime DESC";
                                        } else {
                                            die("No valid designation match. Cannot build query.");
                                        }
                                        
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
                                                $appstatus = $company['application_status'];
                                                $TL_approve = $company['TL_approve'];
                                                $TM_approve = $company['TM_approve'];
                                                $IT_approve = $company['IT_approve'];
                                                $idno = $company['idno'];
                                                
                                                if (strpos($TL_approve, 'Approved') !== false && strpos($TM_approve, 'Approved') !== false && strpos($IT_approve, 'Approved') !== false) {
                                                    $appStatus = 'Approved';
                                                } else if (strpos($TL_approve, 'Approved') !== false && strpos($TM_approve, 'Approved') !== false && strpos($IT_approve, 'Disapproved') !== false) {
                                                    $appStatus = 'Disapproved';
                                                } else if (strpos($TL_approve, 'Approved') !== false && strpos($TM_approve, 'Disapproved') !== false) {
                                                    $appStatus = 'Disapproved';
                                                } else if (strpos($TL_approve, 'Approved') !== false && strpos($TM_approve, 'Approved') !== false) {
                                                    $appStatus = 'Pending (For IT Approval)';
                                                } else if (strpos($TL_approve, 'Disapproved') !== false) {
                                                    $appStatus = 'Disapproved';
                                                } else if (strpos($TL_approve, 'Approved') !== false) {
                                                    $appStatus = 'Pending (For TM Approval)';
                                                } else {
                                                    $appStatus = 'Pending';
                                                }
                                                
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
                                                        <a href='?manage_wfhapplication&viewdetails&id={$company['wfhid']}' class='btn btn-secondary btn-xs' title='View Details'><i class='fa fa-info'></i></a>
                                                    </td>";
                                                echo "<td style='text-align: center; vertical-align: middle'>" . date('M d, Y', strtotime($company['date_effective'])) . "</td>";
                                                echo "<td style='text-align: center; vertical-align: middle'>" . date('M d, Y', strtotime($company['datetime'])) . "</td>";
                                                echo "<td style='text-align: center; vertical-align: middle'>$statusText</td>";
                                                echo "<td style='text-align: justify; vertical-align: middle'>{$company['TL_remarks']}</td>";
                                                echo "<td style='text-align: " . (($company['TM_remarks'] == 'verified') ? 'center' : 'justify') . "; vertical-align: middle;'>
                                                        {$company['TM_remarks']}
                                                    </td>";
                                                echo "<td style='text-align: justify; vertical-align: middle'>{$company['IT_remarks']}</td>";
                                                echo "<td style='text-align: center; vertical-align: middle'>";
                                                    echo "<a href='?manage_wfhapplication&addremarks&id={$company['wfhid']}&IT_remarks' class='btn btn-primary btn-xs' title='Remarks'><i class='fa fa-comment'></i></a> ";
                                                    if (($statusText === "Pending (For TM Approval)" && $designation === '65') || ($statusText === "Pending (For IT Approval)" && $designation === '93')) {
                                                        echo "<a href='?manage_wfhapplication&id={$company['wfhid']}&approved' class='btn btn-success btn-xs' title='Approve' onclick=\"return confirm('Do you wish to approve this missed log application?'); return false;\"><i class='fa fa-thumbs-up'></i></a>&nbsp;";
                                                        echo "<a href='?manage_wfhapplication&id={$company['wfhid']}&disapproved' class='btn btn-danger btn-xs' title='Disapprove' onclick=\"return confirm('Do you wish to disapprove this missed log application?'); return false;\"><i class='fa fa-thumbs-down'></i></a>&nbsp;";
                                                    } else {
                                                        echo "<a href='?manage_wfhapplication&id={$company['wfhid']}&undo' class='btn btn-warning btn-xs' title='Undo Action' onclick=\"return confirm('Do you wish to undo the action taken?'); return false;\"><i class='fa fa-undo'></i></a>";
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
                        $remarks = urldecode($_GET['remarks']);
                ?>
                        <!-- Remarks Form -->
                        <div class="modal-overlay">
                            <div class="modal-container">
                                <div class="content-panel">
                                    <div class="panel-heading-">
                                        <h4>
                                            <a href="?manage_wfhapplication"><i class="fa fa-arrow-left"></i> Close</a> |
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
                        
                        if ($designation == '59' || $userId == '111111') {
                            // Update remarks in the database
                            $sqlUpdateRemarks = "UPDATE wfh_application SET TL_remarks = '$remarks', remarks_view_status='Unseen' WHERE id = '$id'";
                            if (mysqli_query($con, $sqlUpdateRemarks)) {
                                echo "<script>alert('Remarks updated successfully.');</script>";
                                echo "<script>window.location.href='?manage_wfhapplication';</script>"; // Redirect after update
                            } else {
                                echo "<script>alert('Error updating remarks: " . mysqli_error($con) . "');</script>";
                            } 
                        } else if ($designation == '65') {
                            // Update remarks in the database
                            $sqlUpdateRemarks = "UPDATE wfh_application SET TM_remarks = '$remarks', remarks_view_status='Unseen' WHERE id = '$id'";
                            if (mysqli_query($con, $sqlUpdateRemarks)) {
                                echo "<script>alert('Remarks updated successfully.');</script>";
                                echo "<script>window.location.href='?manage_wfhapplication';</script>"; // Redirect after update
                            } else {
                                echo "<script>alert('Error updating remarks: " . mysqli_error($con) . "');</script>";
                            } 
                        } else if ($designation == '93') {
                            // Update remarks in the database
                            $sqlUpdateRemarks = "UPDATE wfh_application SET IT_remarks = '$remarks', remarks_view_status='Unseen' WHERE id = '$id'";
                            if (mysqli_query($con, $sqlUpdateRemarks)) {
                                echo "<script>alert('Remarks updated successfully.');</script>";
                                echo "<script>window.location.href='?manage_wfhapplication';</script>"; // Redirect after update
                            } else {
                                echo "<script>alert('Error updating remarks: " . mysqli_error($con) . "');</script>";
                            } 
                        } else {
                            
                        }
                    }
                    
                    if (isset($_GET['viewdetails'])) {
                        $id = $_GET['id'];
                        
                        $sqlAppDetails = mysqli_query($con, "SELECT * FROM wfh_application WHERE id = '$id'");
                        if ($sqlAppDetails && mysqli_num_rows($sqlAppDetails) > 0) {
                            $appdetails = mysqli_fetch_assoc($sqlAppDetails);
                            $idno = $appdetails['idno'];
                            $address = $appdetails['address'];
                            $schedule = $appdetails['schedule'];
                            $contactnum = $appdetails['contactnum'];
                            $call = $appdetails['work_call'];
                            $conn_internet = $appdetails['conn_internet'];
                            $type_internet = $appdetails['type_internet'];
                            $speed_internet = $appdetails['speed_internet'];
                            $reasons = $appdetails['reasons'];
                            $date_transfer = $appdetails['date_effective'];
                            
                            $sqlUserDetails = mysqli_query($con, "
                                SELECT 
                                    ep.firstname, ep.lastname, 
                                    d.department AS department_name, 
                                    jt.jobtitle AS jobtitle_name
                                FROM employee_profile ep 
                                INNER JOIN employee_details ed ON ed.idno = ep.idno
                                INNER JOIN department d ON d.id = ed.department
                                INNER JOIN jobtitle jt ON jt.id = ed.designation
                                WHERE ep.idno = '$idno'");
                            
                            if ($sqlUserDetails && mysqli_num_rows($sqlUserDetails) > 0) {
                                $details = mysqli_fetch_assoc($sqlUserDetails);
                                $empName = $details['firstname'] . ' ' . $details['lastname'];
                                $department = $details['department_name'];
                                $jobtitle = $details['jobtitle_name'];
                            }
                        }
                ?>
                <div class="modal-overlay">
                    <div class="modal-container">
                        <div class="content-panel">
                            <div class="panel-heading">
                                <h4>
                                    <a href="?manage_wfhapplication"><i class="fa fa-arrow-left"></i> Close</a> |
                                    <i class="fa fa-file-text"></i> APPLICATION DETAILS
                                </h4>
                            </div>
                
                            <style>
                                .group-wrapper {
                                    display: flex;
                                    gap: 20px;
                                    flex-wrap: wrap;
                                }
                                .group-box {
                                    flex: 1;
                                    min-width: 300px;
                                    border: 1px solid #ccc;
                                    border-radius: 8px;
                                    padding: 15px;
                                }
                                .group-title {
                                    font-weight: bold;
                                    margin-bottom: 10px;
                                    font-size: 16px;
                                    display: block;
                                }
                            </style>
                
                            <div class="panel-body">
                                <div class="group-wrapper">
                                    <!-- Employee Info Section -->
                                    <div class="group-box">
                                        <label class="group-title">Employee Information</label>
                
                                        <div class="form-group row">
                                            <label class="col-sm-4 control-label">Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" value="<?= $empName; ?>" readonly>
                                            </div>
                                        </div>
                
                                        <div class="form-group row">
                                            <label class="col-sm-4 control-label">Department</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" value="<?= $department; ?>" readonly>
                                            </div>
                                        </div>
                
                                        <div class="form-group row">
                                            <label class="col-sm-4 control-label">Job Title</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" value="<?= $jobtitle; ?>" readonly>
                                            </div>
                                        </div>
                
                                        <div class="form-group row">
                                            <label class="col-sm-4 control-label">Address of WFH</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" value="<?= $address; ?>" readonly>
                                            </div>
                                        </div>
                
                                        <div class="form-group row">
                                            <label class="col-sm-4 control-label">Schedule</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" value="<?= $schedule; ?>" readonly>
                                            </div>
                                        </div>
                
                                        <div class="form-group row">
                                            <label class="col-sm-4 control-label">Contact No.</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" value="<?= $contactnum; ?>" readonly>
                                            </div>
                                        </div>
                
                                        <div class="form-group row">
                                            <label class="col-sm-4 control-label">Conditions</label>
                                            <div class="col-sm-8">
                                                <ul style="padding-left: 20px;">
                                                    <li><strong><em>• When conducting Training & Feedbacking once a week, should be ONSITE.</em></strong></li>
                                                    <li><strong><em><u>This is a temporary arrangement. To be back onsite upon request per management.</u></em></strong></li>
                                                    <li><strong><em>• Any power/internet issues must be reported to TL. Urgent tasks may require ONSITE or be considered UNDERTIME.</em></strong></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                
                                    <!-- Internet Info Section -->
                                    <div class="group-box">
                                        <label class="group-title">Internet and Other Information</label>
                                    
                                        <div class="form-group row">
                                            <label class="col-sm-4 control-label">Do you take work calls?</label>
                                            <div class="col-sm-8">
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" value="Yes" <?= ($call == 'Yes') ? 'checked' : ''; ?> disabled>
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" value="No" <?= ($call == 'No') ? 'checked' : ''; ?> disabled>
                                                    <label class="form-check-label">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="form-group row">
                                            <label class="col-sm-4 control-label">Internet Connection Type</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" value="<?= $conn_internet; ?>" readonly>
                                            </div>
                                        </div>
                                    
                                        <div class="form-group row">
                                            <label class="col-sm-4 control-label">Internet Service Provider</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" value="<?= $type_internet; ?>" readonly>
                                            </div>
                                        </div>
                                    
                                        <div class="form-group row">
                                            <label class="col-sm-4 control-label">Internet Plan (must be above 15mbps)</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" value="<?= $speed_internet; ?>" readonly>
                                            </div>
                                        </div>
                                    
                                        <div class="form-group row">
                                            <label class="col-sm-4 control-label">Reason(s)</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" rows="5" readonly><?= $reasons; ?></textarea>
                                            </div>
                                        </div>
                                    
                                        <div class="form-group row">
                                            <label class="col-sm-4 control-label">Date of Transfer</label>
                                            <div class="col-sm-8">
                                                <input type="date" class="form-control" value="<?= $date_transfer; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- /.group-wrapper -->
                            </div> <!-- /.panel-body -->
                        </div> <!-- /.content-panel -->
                    </div> <!-- /.modal-container -->
                </div> <!-- /.modal-overlay -->
                <?php
                    }
                ?>
            </section>
        </section>
    </body>
</html>
<script src="lib/jquery/jquery.min.js"></script>
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
          const leavebadge = document.getElementById('leave-notif');
          const missedlogbadge = document.getElementById('missedlog-notif');
          const overtimebadge = document.getElementById('overtime-notif');
          const eeobadge = document.getElementById('eeo-notif');
    
          //Leave section
          if (data.approve_leave_notif > 0 || data.disapprove_leave_notif > 0 || data.leave_remark_notif > 0) {
            leavebadge.style.display = 'inline-block';
            leavebadge.textContent = ''; // optional, or set to a number
    
            if (data.leave_remark_notif > 0) {
              leavebadge.style.backgroundColor = 'gold';
              leavebadge.style.color = 'black';
              leavebadge.title = 'New remarks added';
              hasNotification = true;
            } else if(data.disapprove_leave_notif > 0){
              leavebadge.style.backgroundColor = 'red';
              leavebadge.style.color = 'white';
              leavebadge.title = 'New Disapproved Application';
              hasNotification = true;
            } else {
              leavebadge.style.backgroundColor = '#00FF00';
              leavebadge.style.color = 'white';
              leavebadge.title = 'New Approved Application';
              hasNotification = true;
            }
          } else {
            leavebadge.style.display = 'none';
          }
          //Missed Log Section
          if (data.approve_missedlog_notif > 0 || data.disapprove_missedlog_notif > 0 || data.log_remark_notif > 0) {
            missedlogbadge.style.display = 'inline-block';
            missedlogbadge.textContent = ''; // optional, or set to a number
    
            if (data.log_remark_notif > 0) {
              missedlogbadge.style.backgroundColor = 'gold';
              missedlogbadge.style.color = 'black';
              missedlogbadge.title = 'New remarks added';
              hasNotification = true;
            } else if(data.disapprove_missedlog_notif > 0){
              missedlogbadge.style.backgroundColor = 'red';
              missedlogbadge.style.color = 'white';
              missedlogbadge.title = 'New Disapproved Application';
              hasNotification = true;
            } else {
              missedlogbadge.style.backgroundColor = '#00FF00';
              missedlogbadge.style.color = 'white';
              missedlogbadge.title = 'New Approved Application';
              hasNotification = true;
            }
          } else {
            missedlogbadge.style.display = 'none';
          }
          //Overtime Section
          if (data.approve_overtime_notif > 0 || data.disapprove_overtime_notif > 0 || data.overtime_remark_notif > 0) {
            overtimebadge.style.display = 'inline-block';
            overtimebadge.textContent = ''; // optional, or set to a number
    
            if (data.overtime_remark_notif > 0) {
              overtimebadge.style.backgroundColor = 'gold';
              overtimebadge.style.color = 'black';
              overtimebadge.title = 'New remarks added';
              hasNotification = true;
            } else if(data.disapprove_overtime_notif > 0){
              overtimebadge.style.backgroundColor = 'red';
              overtimebadge.style.color = 'white';
              overtimebadge.title = 'New Disapproved Application';
              hasNotification = true;
            } else {
              overtimebadge.style.backgroundColor = '#00FF00';
              overtimebadge.style.color = 'white';
              overtimebadge.title = 'New Approved Application';
              hasNotification = true;
            }
          } else {
            overtimebadge.style.display = 'none';
          }
          //EEO Section
          if (data.approve_eeo_notif > 0 || data.disapprove_eeo_notif > 0 || data.eeo_remark_notif > 0) {
            eeobadge.style.display = 'inline-block';
            eeobadge.textContent = ''; // optional, or set to a number
    
            if (data.eeo_remark_notif > 0) {
              eeobadge.style.backgroundColor = 'gold';
              eeobadge.style.color = 'black';
              eeobadge.title = 'New remarks added';
              hasNotification = true;
            } else if(data.disapprove_eeo_notif > 0){
              eeobadge.style.backgroundColor = 'red';
              eeobadge.style.color = 'white';
              eeobadge.title = 'New Disapproved Application';
              hasNotification = true;
            } else {
              eeobadge.style.backgroundColor = '#00FF00';
              eeobadge.style.color = 'white';
              eeobadge.title = 'New Approved Application';
              hasNotification = true;
            }
          } else {
            eeobadge.style.display = 'none';
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

    //Filter Button for Date Filter
    function filterByDate() {
        const fromDate = document.getElementById('fromDate').value;
        const toDate = document.getElementById('toDate').value;
    
        if (fromDate && toDate) {
            // Save dates in sessionStorage
            sessionStorage.setItem('fromDate', fromDate);
            sessionStorage.setItem('toDate', toDate);
    
            // Redirect with query params
            window.location.href = `?manage_wfhapplication&fromDate=${fromDate}&toDate=${toDate}`;
        } else {
            alert('Please select both "From" and "To" dates.');
        }
    }

    window.onload = function () {
        const searchValue = sessionStorage.getItem('searchValue');
        const companyTabId = sessionStorage.getItem('companyTabId');
        const deptTabId = sessionStorage.getItem('deptTabId');
        const fromDate = sessionStorage.getItem('fromDate');
        const toDate = sessionStorage.getItem('toDate');
    
        // Restore date filters
        if (fromDate && toDate) {
            document.getElementById('fromDate').value = fromDate;
            document.getElementById('toDate').value = toDate;
        }
    
        // Restore tab and search filters
        if (searchValue && companyTabId && deptTabId) {
            const companyTabLink = document.querySelector(`a[href="#${companyTabId}"]`);
            if (companyTabLink) companyTabLink.click();
    
            setTimeout(() => {
                const deptTabLink = document.querySelector(`a[href="#${deptTabId}"]`);
                if (deptTabLink) deptTabLink.click();
    
                setTimeout(() => {
                    const deptPane = document.getElementById(deptTabId);
                    const searchInput = deptPane.querySelector('input[type="text"]');
                    if (searchInput) {
                        searchInput.value = searchValue;
                        filterTable(searchInput);
                    }
                }, 200);
            }, 200);
        }
    };

    //Reset button for Date Filter
    function resetFilter() {
        window.location.href = '?manage_wfhapplication';
        sessionStorage.removeItem('fromDate');
        sessionStorage.removeItem('toDate');
    }
    //Announcement
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
    // Toggle Submenus
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