<?php
date_default_timezone_set("Asia/Manila");
?>
<?php
  session_start();
  include('../config.php');
  $idno=$_SESSION['idno'];
  if(!isset($_SESSION['idno'])){
    echo "<script>window.location='../employeeportal/';</script>";
  }
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
  <link rel="icon" type="image/x-icon" href="img/nesi.jpg">

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
      <a href="index.html" class="logo"><b>EMPLOYEE PORTAL</b></a>
      <!--logo end-->
      <div class="nav notify-row" id="top_menu">
        <!--  notification start -->
        <!--  notification end -->
      </div>
      <div class="top-menu">
    <ul class="nav pull-right top-menu">
      
      <li><a class="logout" style="border-radius: 15px 15px;" href="logout.php" onclick="return confirm('Do you wish to logout?');return false;">Logout</a></li>
    </ul>
    <li style="float: right; margin-right: 40px; margin-top: 20px; "><a class="attendance_out" href="/hris/attendance/" style=" background-color:#7BCCB5; padding: 5px 15px; font-size: 13px; color: white; border: 1px solid #007bff; border-radius: 15px 15px; border-color: #7BCCB5;">Attendance</a></li>
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
        }
        ?>

        <!-- Display profile picture -->
        <p class="centered">
            <img src="<?= $image; ?>" alt="Profile Picture" class="img-circle" width="80" height="80">
        </p>
          <h5 class="centered"><?=$fullname;?></h5>
          <li class="mt">
            <a href="dashboard.php?main">
              <i class="fa fa-user-circle"></i>
              <span>Profile</span>
              </a>
          </li>
          <li class="sub-menu">
              <a href="javascript:;">
                <i class="fa fa-envelope-open"></i>
                <span>Applications</span>
              </a>
              <ul class="sub">
                  <li><a href="dashboard.php?manageleave">Apply Leave</a></li>
                  <li><a href="dashboard.php?applymissedlog">Apply Missed Log</a></li>
                  <li><a href="dashboard.php?applyovertime">Apply Overtime</a></li>
                  <li><a href="dashboard.php?emergencyearlyout">Apply Emergency Early Out</a></li>
              </ul>
          </li>
          <li class="sub-menu">
              <a  <?= $view; ?> href="javascript:;">
                <i class="fa fa-archive"></i>
                  <span>Requests</span>
                <?php if ($designation != 77  && $designation != 97): ?>
                  <?php if (in_array($idno, $approvers)): ?>
                    <span id="credit-notification-badge" class="badge" style="color: white; background-color: red; margin-left: 60px;"></span>
                  <?php endif; ?>
                <?php endif; ?>
              </a>
              <ul class="sub">
                <?php if ($designation != 77  && $designation != 97 && $designation != 78 && $designation != 116): ?>
                  <li <?= $view; ?>><a href="dashboard.php?manageleaveapplication">Leave Applications 
                    <span id="leave-notification-badge" class="badge" style="color: white; background-color: red;"></span>
                  </a></li>
                  <li <?= $view; ?>><a href="dashboard.php?manageovertimeapplication">OT Applications 
                    <span id="ot-notification-badge" class="badge" style="color: white; background-color: red;"></span>
                  </a></li>
                  <li <?= $view; ?>><a href="dashboard.php?managemissedlogapplication">Missed Log Application 
                    <span id="ml-notification-badge" class="badge" style="color: white; background-color: red;"></span>
                  </a></li>
                <?php endif; ?>
                <?php if ($designation == 77 || $designation == 97): ?>
                  <li <?= $view; ?>><a href="dashboard.php?monitoringmanagemissedlogapplication">Missed Log Application 
                    <span id="mml-notification-badge" class="badge" style="color: white; background-color: red;"></span>
                  </a></li>
                <?php endif; ?>
                <?php if ($designation == 78||$designation == 116):?>
                  <li><a href="dashboard.php?EEOapplication">EEO Applications 
                    <span id="eeo-notification-badge" class="badge" style="color: white; background-color: red;"></span>
                  </a></li>
                <?php endif; ?>
                <?php if (in_array($idno, $approvers) && $designation != 78 && $designation != 116 && $designation != 77 && $designation != 97): ?>
                  <li><a href="dashboard.php?manageEEOapplication">EEO Applications 
                    <span id="eeo-notification-badge" class="badge" style="color: white; background-color: red;"></span>
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
            <div class="infnotification-wrapper" style="position: relative;">
              <a href="dashboard.php?infractions" class="infnotification-link" id="infractionnotifLink" style="position: relative;">
                <i class="fa fa-folder-open"></i>
                <span>Infractions</span>
                <span id="infnotificationDot" style="
                  width: 10px;
                  height: 10px;
                  background: red;
                  border-radius: 50%;
                  position: absolute;
                  top: 5px;
                  right: 50px;
                  display: none;"></span>
              </a>
            </div>
          </li>
          <li>
              <?php if ($designation == 8||$designation == 50||$designation == 89 || $designation == 59|| $designation == 65|| $designation == 94||$designation == 102 || $designation == 3 || $designation == 88|| $designation == 114||$designation == 92): ?>
                <div class="notification-wrapper" style="position: relative;">
                  <a href="dashboard.php?manageinfraction" class="notification-link" id="infractionLink" style="position: relative;">
                    <i class="fa fa-bell"></i>
                    <span>Manage Infraction</span>
                    <span id="notificationDot" style="
                      width: 10px;
                      height: 10px;
                      background: red;
                      border-radius: 50%;
                      position: absolute;
                      top: 5px;
                      right: 50px;
                      display: none;"></span>
                  </a>
                </div>
              <?php endif; ?>
          </li>
          <li>
            <?php if ($designation == 97||$designation == 77):?>
              <a href="dashboard.php?errorcatching">
                <i class="fa fa-eye"></i>
                <span>HRIS Monitoring</span>
              </a>
            <?php endif; ?>
          </li>
          <li>
            <a href="dashboard.php?attendance">
              <i class="fa fa-clock-o"></i>
              <span>Log Details</span>
              </a>
          </li>
          <li>
            <?php if ($idno == 103563):?>
              <a href="dashboard.php?emp_dev">
              <i class="fa fa-android"></i>
                <span>HRIS Monitoring</span>
              </a>
            <?php endif; ?>
          </li>
        </ul>
       
        <!-- sidebar menu end-->
      </div>
    </aside>
    <!--sidebar end-->
    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
      <section class="wrapper site-min-height">
        <div class="row">
          <div class="col-lg-12">
          </div>
        </div>
        <div class="row mt">
          <?php
            if(isset($_GET['main'])){include('main.php');}
            if(isset($_GET['attendance'])){include('attendance.php');}
            if(isset($_GET['infractions'])){include('infractions.php');}
            if(isset($_GET['applyleave'])){include('applyleave.php');}
            if(isset($_GET['editleave'])){include('editleave.php');}
            if(isset($_GET['employeechecklist'])){include('employeechecklist.php');}
            if(isset($_GET['employeecontract'])){include('employeecontract.php');}
            if(isset($_GET['employeemovement'])){include('employeemovement.php');}
            if(isset($_GET['manageleave'])){include('manageleave.php');}
            if(isset($_GET['applyovertime'])){include('applyovertime.php');}
            if(isset($_GET['applymissedlog'])){include('applymissedlog.php');}
            if(isset($_GET['manageleaveapplication'])){include('manageleaveapplication.php');}
            if(isset($_GET['addovertime'])){include('addovertime.php');}
            if(isset($_GET['addmissedlog'])){include('addmissedlog.php');}
            if(isset($_GET['editovertime'])){include('editovertime.php');}
            if(isset($_GET['manageovertimeapplication'])){include('manageovertimeapplication.php');}
            if(isset($_GET['managemissedlogapplication'])){include('managemissedlogapplication.php');}
            if(isset($_GET['monitoringmanagemissedlogapplication'])){include('monitoringmanagemissedlogapplication.php');}
            if(isset($_GET['viewpayroll'])){include('viewpayroll.php');}
            if(isset($_GET['editmissedlog'])){include('editmissedlog.php');}
            if(isset($_GET['addinfraction'])){include('addinfraction.php');}
            if(isset($_GET['editinfraction'])){include('editinfraction.php');}
            if(isset($_GET['monitorinfraction'])){include('monitorinfraction.php');}
            if(isset($_GET['employeereferral'])){include('employeereferral.php');}
            if(isset($_GET['editoffense'])){include('editoffense.php');}
            if(isset($_GET['manageuser'])){include('manageuser.php');}
            if(isset($_GET['manageinfraction'])){include('manageinfraction.php');}
            if(isset($_GET['getnotifications'])){include('getnotifications.php');}  
            if(isset($_GET['infractionnotif'])){include('infractionnotif.php');}  
            if(isset($_GET['employeeinfractionnotif'])){include('employeeinfractionnotif.php');}
            if(isset($_GET['errorcatching'])){include('errorcatching.php');}
            if(isset($_GET['export_excel'])){include('export_excel.php');}
            if(isset($_GET['emergencyearlyout'])){include('emergencyearlyout.php');}
            if(isset($_GET['addeeo'])){include('addeeo.php');}
            if(isset($_GET['editeeo'])){include('editeeo.php');}
            if(isset($_GET['manageEEOapplication'])){include('manageEEOapplication.php');}
            if(isset($_GET['EEOapplication'])){include('EEOapplication.php');}
            if(isset($_GET['emp_dev'])){include('emp_dev.php');}


          ?>
          <!-- /col-lg-3 -->
        </div>
        <!-- /row -->
      </section>
    </section>
    <!--main content end-->

    <!--footer start-->
    <footer class="site-footer fixed">
      <div class="text-center">
        <p>
          &copy; Copyrights <strong>iHRIS</strong>. All Rights Reserved
        </p>
        <div class="credits">
          <!--
            You are NOT allowed to delete the credit link to TemplateMag with free version.
            You can delete the credit link only if you bought the pro version.
            Buy the pro version with working PHP/AJAX contact form: https://templatemag.com/dashio-bootstrap-admin-template/
            Licensing information: https://templatemag.com/license/
          -->
          Created with Dashio template by <a href="#">Eczekiel H. Aboy</a>
          <p>
            Updated by • 
            <a href="https://facebook.com/MackGwapo07" target="_blank">M.I.Misa</a> & 
            <a href="https://facebook.com/misterdoctor07" target="_blank">J.M.Lapeceros</a>
          </p>
        </div>
        <a href="index.html#" class="go-top">
          <i class="fa fa-angle-up"></i>
          </a>
      </div>
    </footer>
    <!--footer end-->
  </section>
  <!-- js placed at the end of the document so the pages load faster -->
  <script src="lib/jquery/jquery.min.js"></script>
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
  <script type="application/javascript">
    $(document).ready(function() {
      $("#date-popover").popover({
        html: true,
        trigger: "manual"
      });
      $("#date-popover").hide();
      $("#date-popover").click(function(e) {
        $(this).hide();
      });

      $("#my-calendar").zabuto_calendar({
        action: function() {
          return myDateFunction(this.id, false);
        },
        action_nav: function() {
          return myNavFunction(this.id);
        },
        ajax: {
          url: "show_data.php?action=1",
          modal: true
        },
        legend: [{
            type: "text",
            label: "Special event",
            badge: "00"
          },
          {
            type: "block",
            label: "Regular event",
          }
        ]
      });
    });

    $(document).ready(function() {
      var oTable = $('#hidden-table-info').dataTable({
        "aoColumnDefs": [{
          "bSortable":false,
          "aTargets": [0]
        }],
        "aaSorting": [
          [0, 'asc']
        ]
      });
      /* Add event listener for opening and closing details
       * Note that the indicator for showing which row is open is not controlled by DataTables,
       * rather it is done here
       */
      });


    function myNavFunction(id) {
      $("#date-popover").hide();
      var nav = $("#" + id).data("navigation");
      var to = $("#" + id).data("to");
      console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
    }

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
//Notifications for infractions for supervisors
function fetchInfractions() {
  fetch('infractionnotif.php') // Replace with the correct PHP file path
    .then(response => response.json())
    .then(data => {
      const notificationDot = document.getElementById('notificationDot');
      if (data.infractions && data.infractions.length > 0) {
        // Show the notification dot if there are infractions
        notificationDot.style.display = 'inline-block';
      } else {
        // Hide the notification dot if no infractions
        notificationDot.style.display = 'none';
      }
    })
    .catch(error => console.error('Error fetching infractions:', error));
}

// Function to mark infractions as viewed
function markInfractionsAsViewed() {
  fetch('infractionnotif.php', { // Replace with your PHP file path
    method: 'POST'
  })
    .then(response => response.json())
    .then(data => {
      console.log(data.message); // Handle success response
      fetchInfractions(); // Refresh notification status
    })
    .catch(error => console.error('Error marking infractions as viewed:', error));
}

// Event listener to mark infractions as viewed when clicking the notification link
document.getElementById('infractionLink').addEventListener('click', () => {
  markInfractionsAsViewed();
});

// Fetch infractions periodically (e.g., every 5 seconds)
setInterval(fetchInfractions, 5000);

// Initial fetch
fetchInfractions();

//Notifications for infractions for all employees
function fetchInfractions() {
  fetch('employeeinfractionnotif.php')
    .then(response => response.json())
    .then(data => {
      const infnotificationDot = document.getElementById('infnotificationDot');
      if (infnotificationDot) {
        if (data.infractions && data.infractions.length > 0) {
          infnotificationDot.style.display = 'inline-block';
        } else {
          infnotificationDot.style.display = 'none';
        }
      }
    })
    .catch(error => console.error('Error fetching infractions:', error));
}

// Mark infractions as viewed
function markInfractionsAsViewed() {
  fetch('employeeinfractionnotif.php', { method: 'POST' })
    .then(response => response.json())
    .then(data => {
      console.log(data.message);
      fetchInfractions();
    })
    .catch(error => console.error('Error marking infractions as viewed:', error));
}

// Event listener for clicking the notification link
document.getElementById('infractionnotifLink').addEventListener('click', markInfractionsAsViewed);

// Poll infractions periodically
setInterval(fetchInfractions, 5000);
fetchInfractions();
</script>

</body>

</html>
