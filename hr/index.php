<?php
date_default_timezone_set("Asia/Manila");
?>
<?php
  session_start();
  include('../config.php');
  if(!isset($_SESSION['username'])){
    echo "<script>window.location='../';</script>";
  }
  if($_SESSION['fullname']==""){
    $sqlEmployee=mysqli_query($con,"SELECT lastname,firstname FROM employee_profile WHERE idno='$_SESSION[idno]'");
    if(mysqli_num_rows($sqlEmployee)>0){
      $name=mysqli_fetch_array($sqlEmployee);
      $fullname=$name['lastname'].", ".$name['firstname'];
    }else{
      $fullname="";
    }
  }else{
    $fullname=$_SESSION['fullname'];
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
      <a href="index.html" class="logo"><b>Integrated Human Resource Information System</b></a>
      <!--logo end-->
      <div class="nav notify-row" id="top_menu">
        <!--  notification start -->
        <!--  notification end -->
      </div>
      <div class="top-menu">
        <ul class="nav pull-right top-menu">
          <li><a class="logout" href="../logout.php" onclick="return confirm('Do you wish to logout?');return false;">Logout</a></li>
        </ul>
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
          <p class="centered"><i class="fa fa-user fa-4x"></i></p>
          <h5 class="centered"><?=$fullname;?></h5>
          <li class="mt">
            <a href="?main">
              <i class="fa fa-dashboard"></i>
              <span>Dashboard</span>
              </a>
          </li>
          <li class="sub-menu">
            <a href="javascript:;">
              <i class=" fa fa-users"></i>
              <span>Employee</span>
              </a>
            <ul class="sub">
              <li><a href="?manageemployee">Manage Employee</a></li>
              <li><a href="?manageinfraction">Manage Infraction</a></li>
              <li><a href="?infractionmonitoring">Infraction Monitoring</a></li>
              <li><a href="?monitorattendance">Employee Attendance</a></li>
            </ul>
          </li>
          
          <!--Fetch the number of pending rows inside the table-->
          <?php
              // Count pending leave applications
              $query = "SELECT COUNT(*) AS total 
                FROM leave_application 
                WHERE appstatus != 'Pending' 
                AND appstatus != 'Cancelled' 
                AND appstatus NOT LIKE 'Disapproved%' 
                AND appstatus NOT LIKE '*Approved%'
                AND appstatus NOT LIKE '*Disapproved%'
                AND remarks NOT LIKE 'POSTED%'";
              $result = mysqli_query($con, $query);
              $row = mysqli_fetch_assoc($result);
              $leave_count = $row['total'];
          
              // Count pending overtime applications
              $query = "SELECT COUNT(*) AS total 
                FROM overtime_application 
                WHERE app_status != 'Pending' 
                AND app_status != 'Cancelled' 
                AND app_status != 'Disapproved' 
                AND hr_remarks != 'POSTED'";
              $result = mysqli_query($con, $query);
              $row = mysqli_fetch_assoc($result);
              $overtime_count = $row['total'];

              // Count pending missed log applications for the same company
              $query = "SELECT COUNT(*) AS total FROM missed_log_application 
                        WHERE applic_status NOT LIKE '*Approved%'
                        AND applic_status NOT LIKE '*Disapproved%' 
                        AND applic_status != 'Pending' 
                        AND applic_status != 'Cancelled'";
              $result = mysqli_query($con, $query);
              $row = mysqli_fetch_assoc($result);
              $missedlog_count = $row['total'];

              // Count pending EEO applications for the same company
              $query = "SELECT COUNT(*) AS total FROM emergencyearlyout 
                        WHERE eeo_status NOT LIKE '%*%' 
                        AND eeo_status != 'Cancelled'";
              $result = mysqli_query($con, $query);
              $row = mysqli_fetch_assoc($result);
              $EEO_count = $row['total'];

              $totalCount = $leave_count + $EEO_count + $missedlog_count + $overtime_count;
              ?>




          <li class="sub-menu">
              <a href="javascript:;">
                <i class="fa fa-file-text"></i>
                <span>Leave & Benefits</span>
                <?php if ($leave_count > 0) { ?>
                    <span class="badge" style="color: white; background-color: red;"><?php echo $totalCount; ?></span>
                  <?php } ?>
              </a>
              <ul class="sub">
                <li><a href="?manageleave">Manage Leave Credits</a></li>
                <li><a href="?leaveapplication">Leave Applications
                  <?php if ($leave_count > 0) { ?>
                    <span class="badge" style="color: white; background-color: red;"><?php echo $leave_count; ?></span>
                  <?php } ?>
                </a></li>
                <li><a href="?missedloginapplication">Missed Log Application
                  <?php if ($missedlog_count > 0) { ?>
                    <span class="badge" style="color:white; background-color:red;"><?php echo $missedlog_count; ?></span>
                  <?php } ?>
                </a></li>
                <li><a href="?overtimeapplication">Overtime Applications
                  <?php if ($overtime_count > 0) { ?>
                    <span class="badge" style="color:white; background-color:red;"><?php echo $overtime_count; ?></span>
                  <?php } ?>
                </a></li>
                <li><a href="?EEOapplication">EEO Applications
                  <?php if ($EEO_count > 0) { ?>
                    <span class="badge" style="color:white; background-color:red;"><?php echo $EEO_count; ?></span>
                  <?php } ?>
                </a></li>
                <li><a href="?leaveprotocols">Leave Protocols</a></li>
                <li><a href="?insurance">HMO & Insurance</a></li>
              </ul>
          </li>
          <li>
            <a href="?movement">
              <i class="fa fa-road"></i>
              <span>Movement Tracker</span>
              </a>
          </li>
          <li class="sub-menu">
            <a href="javascript:;">
              <i class=" fa fa-clipboard"></i>
              <span>Widgets</span>
              </a>
            <ul class="sub">
              <li><a href="?announcement">Announcement</a></li>
            </ul>
            <ul class="sub">
              <li><a href="?managememo">Manage Memo</a></li>
            </ul>
          </li>
          <li>
            <a href="?errorcatching">
              <i class="fa fa-eye"></i>
              <span>HRIS Monitoring</span>
              </a>
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
             if(isset($_GET['announcement'])){include('announcement.php');}
            if(isset($_GET['main'])){include('main.php');}
            if(isset($_GET['manageemployee'])){include('manageemployee.php');}
            if(isset($_GET['addemployee'])){include('addemployee.php');}
            if(isset($_GET['editemployee'])){include('editemployee.php');}
            if(isset($_GET['viewemployee'])){include('viewemployee.php');}
            if(isset($_GET['employeechecklist'])){include('employeechecklist.php');}
            if(isset($_GET['employeecontract'])){include('employeecontract.php');}
            if(isset($_GET['employeemovement'])){include('employeemovement.php');}
            if(isset($_GET['manageleave'])){include('manageleave.php');}
            if(isset($_GET['viewleave'])){include('viewleave.php');}
            if(isset($_GET['manageinfraction'])){include('manageinfraction.php');}
            if(isset($_GET['addinfraction'])){include('addinfraction.php');}
            if(isset($_GET['editinfraction'])){include('editinfraction.php');}
            if(isset($_GET['monitorinfraction'])){include('monitorinfraction.php');}
            if(isset($_GET['infractionmonitoring'])){include('infractionmonitoring.php');}
            if(isset($_GET['monitorinfractionsummary'])){include('monitorinfractionsummary.php');}
            if(isset($_GET['employeereferral'])){include('employeereferral.php');}
            if(isset($_GET['viewemployeemasterlist'])){include('viewemployeemasterlist.php');}
            if(isset($_GET['leaveprotocols'])){include('leaveprotocols.php');}
            if(isset($_GET['addleaveprotocols'])){include('addleaveprotocols.php');}
            if(isset($_GET['manageleaveprotocols'])){include('manageleaveprotocols.php');}
            if(isset($_GET['monitorattendance'])){include('monitorattendance.php');}
            if(isset($_GET['attendancemonitoring'])){include('summaryattendancemonitoring.php');}
            if(isset($_GET['attendancemonitoringsummary'])){include('attendancemonitoring.php');}
            // if(isset($_GET['attendancemonitoringsummary'])){include('attendancemonitoringsummary.php');}
            if(isset($_GET['viewresignedemployee'])){include('viewresignedemployee.php');}
            if(isset($_GET['viewdepartment'])){include('viewdepartment.php');}
            if(isset($_GET['movement'])){include('movement.php');}
            if(isset($_GET['movementtracker'])){include('movementtracker.php');}
            if(isset($_GET['insurance'])){include('insurance.php');}
            if(isset($_GET['insurancetracker'])){include('insurancetracker.php');}
            if(isset($_GET['addresign'])){include('addresign.php');}
            if(isset($_GET['leaveapplication'])){include('leaveapplication.php');}
            if(isset($_GET['missedloginapplication'])){include('missedloginapplication.php');}
            if(isset($_GET['applyleave'])){include('applyleave.php');}
            if(isset($_GET['edittime'])){include('edittime.php');}
            if(isset($_GET['overtimeapplication'])){include('overtimeapplication.php');}
            if(isset($_GET['applyovertime'])){include('applyovertime.php');}
            if(isset($_GET['editresignation'])){include('editresignation.php');}
            if(isset($_GET['managememo'])){include('managememo.php');}
            if(isset($_GET['addmemo'])){include('addmemo.php');}
            if(isset($_GET['applyleaveforemp'])){include('applyleaveforemp.php');}
            if(isset($_GET['errorcatching'])){include('errorcatching.php');}
            if(isset($_GET['fetch_employees'])){include('fetch_employees.php');}
            if(isset($_GET['EEOapplication'])){include('EEOapplication.php');}
            if(isset($_GET['applyEEOforemp'])){include('applyEEOforemp.php');}
          ?>
          <!-- /col-lg-3 -->
        </div>
        <!-- /row -->
      </section>
    </section>
    <!--main content end-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form name="f12" method="GET">
                      <input type="hidden" name="viewdepartment">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title" id="myModalLabel">SELECT DEPARTMENT <input type="button" id="dept" style="border:0; width:250px;color:white; background-color:transparent; text-align:left;"/></h4>
                    </div>
                    <div class="modal-body">
                      <!-- <fieldset>
                        <div class="form-group">
                          <input type="text" class="form-control" placeholder="Username" name="username" autofocus style="height: 50px; font-size: 20px;" required>
                        </div>
                        <div class="form-group">
                          <input type="password" class="form-control" placeholder="Password" name="password" style="height: 50px; font-size: 20px;" required>
                        </div>

                      </fieldset> -->
                      <div id="login-page">
        <div class="login-wrap">
          <select class="form-control" name="department" required>
            <option value="">Select Department</option>
            <?php
            $sqlDepartment=mysqli_query($con,"SELECT * FROM department");
            if(mysqli_num_rows($sqlDepartment)>0){
              while($dept=mysqli_fetch_array($sqlDepartment)){
                echo "<option value='$dept[id]'>$dept[department]</option>";
              }
            }
            ?>
          </select>
          <label class="checkbox">

            </label>
          <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-building"></i> SELECT</button> <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button>
        </div>
  </div>
                    </div>
                    <!-- <div class="modal-footer">

                      <button type="button" class="btn btn-primary">Save changes</button>
                    </div> -->
                    </form>
                  </div>
                </div>
              </div>
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
  </script>
  <script type="text/javascript">
var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()
</script>
</body>

</html>
