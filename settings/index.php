<?php
date_default_timezone_set("Asia/Manila");
?>
<?php
  session_start();
  include('../config.php');
  if(!isset($_SESSION['username'])){
    echo "<script>window.location='../';</script>";
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
          <h5 class="centered"><?=$_SESSION['fullname'];?></h5>
          <li class="mt">
            <a href="?main">
              <i class="fa fa-dashboard"></i>
              <span>Dashboard</span>
              </a>
          </li>
          <li>
            <a href="?manageuser">
              <i class="fa fa-users"></i>
              <span>User Management</span>
              </a>
          </li>
          <li>
            <a href="?managecompany">
              <i class="fa fa-building"></i>
              <span>Company Settings</span>
              </a>
          </li>
          <li>
            <a href="?managedepartment">
              <i class="fa fa-building-o"></i>
              <span>Department Settings</span>
              </a>
          </li>
          <li>
            <a href="?managejobtitle">
              <i class="fa fa-book"></i>
              <span>Job Title Management</span>
              </a>
          </li>
          <li>
            <a href="?managememo">
              <i class="fa fa-file-text-o"></i>
              <span>Memorandum</span>
              </a>
          </li>
          <li>
            <a href="?manageoffense">
              <i class="fa fa-file-text"></i>
              <span>Offense Management</span>
              </a>
          </li>
          <li class="sub-menu">
            <a href="javascript:;">
              <i class=" fa fa-clipboard"></i>
              <span>Widgets</span>
              </a>
            <ul class="sub">
              <li><a href="?announcement">Announcement</a></li>
              <li><a href="?safety">Safety Protocols</a></li>
              <li><a href="?recruitment">Recruitment</a></li>              
              <li><a href="?birthday">Birthday Greetings</a></li>
            </ul>
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
            if(isset($_GET['managecompany'])){include('managecompany.php');}
            if(isset($_GET['addcompany'])){include('addcompany.php');}
            if(isset($_GET['editcompany'])){include('editcompany.php');}
            if(isset($_GET['managedepartment'])){include('managedepartment.php');}
            if(isset($_GET['adddepartment'])){include('adddepartment.php');}
            if(isset($_GET['editdepartment'])){include('editdepartment.php');}
            if(isset($_GET['managejobtitle'])){include('managejobtitle.php');}
            if(isset($_GET['addjobtitle'])){include('addjobtitle.php');}
            if(isset($_GET['editjobtitle'])){include('editjobtitle.php');}
            if(isset($_GET['managememo'])){include('managememo.php');}
            if(isset($_GET['addmemo'])){include('addmemo.php');}
            if(isset($_GET['editmemo'])){include('editmemo.php');}
            if(isset($_GET['manageoffense'])){include('manageoffense.php');}
            if(isset($_GET['addoffense'])){include('addoffense.php');}
            if(isset($_GET['editoffense'])){include('editoffense.php');}
            if(isset($_GET['manageuser'])){include('manageuser.php');}
            if(isset($_GET['edituser'])){include('edituser.php');}
            if(isset($_GET['announcement'])){include('announcement.php');}
            if(isset($_GET['safety'])){include('safety.php');}
            if(isset($_GET['recruitment'])){include('recruitment.php');}
            if(isset($_GET['birthday'])){include('birthday.php');}
            
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
  <?php
    if(isset($_GET['main'])){
  ?>
  <script type="text/javascript">
    $(document).ready(function() {
      var unique_id = $.gritter.add({
        // (string | mandatory) the heading of the notification
        title: 'Welcome Administrator!',
        // (string | mandatory) the text inside the notification
        text: 'You can manage setting here.',
        // (string | optional) the image to display on the left
        image: 'img/nesi.jpg',
        // (bool | optional) if you want it to fade out on its own or just sit there
        sticky: false,
        // (int | optional) the time you want it to be alive for before fading out
        time: 8000,
        // (string | optional) the class name you want to apply to that specific message
        class_name: 'my-sticky-class'
      });

      return false;
    });
  </script>
  <?php
  }
  ?>
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
</body>

</html>
