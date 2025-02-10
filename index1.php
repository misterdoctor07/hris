<?php
date_default_timezone_set("Asia/Manila");
include('config.php');
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
      <a href="index.php" class="logo"><b>Integrated Human Resource Information System</b></a>
      <!--logo end-->
      <div class="nav notify-row" id="top_menu">
        <!--  notification start -->

        <!--  notification end -->
      </div>
      <div class="top-menu">

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
          <li class="mt" align="center">
              <span style="color:white; font-size: 18px;">HRIS Services</span>
          </li>
          <li>
            <a href="" data-toggle="modal" data-target="#myModal" data-id="HR" draggable="true" class="login">
              <i class="fa fa-users"></i>
              <span>Human Resource</span>
              </a>
          </li>
          <li>
            <a href="" data-toggle="modal" data-target="#myModal" data-id="ACCOUNTING" draggable="true" class="login">
              <i class="fa fa-bar-chart-o"></i>
              <span>Payroll & Accounting</span>
              </a>
          </li>
          <li>
            <a href="employeeportal/" target="_blank">
              <i class="fa fa-user"></i>
              <span>Employee Portal</span>
              </a>
          </li>
          <li>
            <a href="attendance/" target="_blank">
              <i class="fa fa-clock-o"></i>
              <span>Attendance</span>
              </a>
          </li>
          <li>
            <a href="" data-toggle="modal" data-target="#myModal" data-id="IT ADMIN" draggable="true" class="login">
              <i class="fa fa-gear"></i>
              <span>Settings</span>
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
        <div class="row mt mb">
          <div class="col-lg-12">
            <div class="col-lg-3 col-md-12 col-sm-12">
              <div class="dmbox">
                <div class="service-icon">
                  <a class="" href="?announcement"><i class="dm-icon fa fa-bullhorn fa-3x"></i></a>
                </div>
                <h4>Announcements</h4>
              </div>
            </div>
            <!-- end dmbox -->
            <div class="col-lg-3 col-md-12 col-sm-12">
              <div class="dmbox">
                <div class="service-icon">
                  <a class="" href="?safety"><i class="dm-icon fa fa-shield fa-3x"></i></a>
                </div>
                <h4>Safety Protocols</h4>
              </div>
            </div>
            <!-- end dmbox -->
            <div class="col-lg-3 col-md-12 col-sm-12">
              <div class="dmbox">
                <div class="service-icon">
                  <a class="" href="?recruitment"><i class="dm-icon fa fa-github fa-3x"></i></a>
                </div>
                <h4>Recruitment</h4>
              </div>
            </div>
            <!-- end dmbox -->
            <div class="col-lg-3 col-md-12 col-sm-12">
              <div class="dmbox">
                <div class="service-icon">
                  <a class="" href="?birthday"><i class="dm-icon fa fa-calendar fa-3x"></i></a>
                </div>
                <h4>Birthdays</h4>
              </div>
            </div>
            <!-- end dmbox -->
          </div>
          <!--  /col-lg-12 -->
        </div>
        <!-- /row -->
        <div class="row content-panel">
          <?php
          function weekOfMonth($date) {
            // estract date parts
            list($y, $m, $d) = explode('-', date('Y-m-d', strtotime($date)));

            // current week, min 1
            $w = 1;

            // for each day since the start of the month
            for ($i = 1; $i <= $d; ++$i) {
                // if that day was a sunday and is not the first day of month
                if ($i > 1 && date('w', strtotime("$y-$m-$i")) == 0) {
                    // increment current week
                    ++$w;
                }
            }

            // now return
            return $w;
        }
          mysqli_query($con,"SET NAMES 'utf8'");
          if(isset($_GET['safety'])){
            $title="SAFETY PROTOCOLS";
            $align="";
            $sqlResult=mysqli_query($con,"SELECT * FROM widgets WHERE `type`='Safety' ORDER BY datearray DESC");
          }elseif(isset($_GET['recruitment'])){
            $title="RECRUITMENT DETAILS";
            $align="";
            $sqlResult=mysqli_query($con,"SELECT * FROM widgets WHERE `type`='Recruitment' ORDER BY datearray DESC");
          }elseif(isset($_GET['birthday'])){
            $title=date('F')." BIRTHDAY CELEBRANTS";
            $align="centered";
            $sqlResult=mysqli_query($con,"SELECT id,CONCAT(lastname,', ',firstname) AS details,birthdate as datearray FROM employee_profile WHERE MONTH(birthdate)='".date('m')."' GROUP BY DAY(birthdate),lastname ORDER BY DAY(birthdate) ASC");
          }else{
            $title="ANNOUNCEMENTS";
            $align="";
            $sqlResult=mysqli_query($con,"SELECT * FROM widgets WHERE `type`='Announcement' ORDER BY datearray DESC");
          }
          ?>
          <div class="col-lg-12 <?=$align;?>">
          <h2 class="centered" style="text-transform:uppercase;"><?=$title;?></h2>
          <?php
              if(isset($_GET['birthday'])){
            ?>
            <center>
          <div class="col-md-6 col-md-offset-3">
            <h3 style="color:red;">
              <?php
              $greetings="";
              $sqlGreetings=mysqli_query($con,"SELECT details FROM widgets WHERE `type`='Birthday' ORDER BY datearray DESC");
              if(mysqli_num_rows($sqlGreetings)>0){
                while($greet=mysqli_fetch_array($sqlGreetings)){
                  $greetings .=$greet['details']."<br><br><br>";
                }
                echo "<i><marquee direction='up' behavior='scroll' height='100px' scrollamount='3'  style='text-align:center;'>".$greetings."</marquee></i>";
              }
              ?>
            </h3>
            </div>
            </center>
            <?php
              }
              ?>
          <div class="col-md-10 col-md-offset-1 mt mb">
            <div class="accordion" id="accordion2">
                <?php
                $x=1;
                if(mysqli_num_rows($sqlResult)>0){
                  while($result=mysqli_fetch_array($sqlResult)){
                    if(isset($_GET['birthday'])){
                      $today=date('Y-m-d');
                      $age=date_diff(date_create($result['datearray']), date_create($today));
                      $age="@ ".$age->format('%y');
                      $col="col-lg-2";
                      if(date('d',strtotime($result['datearray']))==date('d')){
                        $color="style='background-color:pink;'";
                      }else{
                        $color="";
                      }
                    }else{
                      $age="";
                      $col="";
                      $color="";
                    }
                    if(isset($_GET["birthday"])){
                    $weeknumber=weekOfMonth(date('Y-m-d'));
                    $weekofmonth=weekOfMonth(date('m/d',strtotime($result['datearray'])));
                    if($weekofmonth==$weeknumber){
                ?>
                <div class="accordion-group <?=$col;?>" <?=$color;?>>
                <div class="accordion-heading"  <?=$color;?>>
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="index.php#collapse<?=$x;?>"  <?=$color;?>>
                    <em class="glyphicon glyphicon-chevron-right icon-fixed-width"></em> <?=date('M d',strtotime($result['datearray']));?>
                    </a>
                </div>
                <div id="collapse<?=$x;?>" class="accordion-body collapse in">
                  <div class="accordion-inner">
                    <h5><?=$result['details'];?></h5>
                  </div>
                </div>
                </div>
                <?php
                $x++;
                    }
                  }else{
                    ?>
                <div class="accordion-group <?=$col;?>" <?=$color;?>>
                <div class="accordion-heading"  <?=$color;?>>
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="index.php#collapse<?=$x;?>"  <?=$color;?>>
                    <em class="glyphicon glyphicon-chevron-right icon-fixed-width"></em> <?=date('m/d/Y',strtotime($result['datearray']));?>
                    </a>
                </div>
                <div id="collapse<?=$x;?>" class="accordion-body collapse in">
                  <div class="accordion-inner">
                    <pre style="border:0; background-color:white;white-space:pre-wrap;">
                    <h4><?=$result['details'];?></h4>
                  </pre>
                  </div>
              </div>
              </div>
                <?php
                $x++;
                  }
                  }
                }
                ?>
              </div>
            <!-- end accordion -->
            </div>
          </div>
          <!-- col-md-10 -->
        </div>
        <!--  /row -->
      </section>
      <!-- /wrapper -->
    </section>
    <!-- <section id="main-content">
      <section class="wrapper site-min-height">
        <div class="row">
          <div class="col-lg-12">
          </div>
        </div>
        <div id="morris">
          <div class="row mt">
            <div class="col-lg-8">
              <div class="content-panel">
                <h4>Employee Statistics</h4>
                <div class="panel-body">
                  <div id="hero-bar" class="graph"></div>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-sm-4 mb">
                <div class="green-panel pn">
                  <div class="green-header">
                    <h5>DEPARTMENT</h5>
                  </div>
                  <div class="panel-body">
                    <?php
                    $sqlCompany=mysqli_query($con,"SELECT * FROM department");
                    $company=mysqli_num_rows($sqlCompany);
                    ?>
                  <h2><?=$company;?></h2>
                </div>
                  <h3>TOTAL DEPARTMENT</h3>
                </div>
              </div>
              <div class="col-md-4 col-sm-4 mb">
                <div class="grey-panel pn donut-chart">
                  <div class="grey-header">
                    <h5>JOB TITLE</h5>
                  </div>
                  <div class="panel-body">
                    <?php
                    $sqlCompany=mysqli_query($con,"SELECT * FROM jobtitle");
                    $company=mysqli_num_rows($sqlCompany);
                    ?>
                  <h2><?=$company;?></h2>
                </div>
                  <h4>TOTAL JOB TITLES</h4>
                </div>
          </div>

        </div>                       -->
          <!-- /col-lg-9 END SECTION MIDDLE -->
          <!-- **********************************************************************************************************************************************************
              RIGHT SIDEBAR CONTENT
              *********************************************************************************************************************************************************** -->
          <!-- /col-lg-3 -->
        <!-- </div> -->
        <!-- /row -->
      <!-- </section>
    </section> -->
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
              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form name="f12" action="authentication.php" method="POST">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title" id="myModalLabel">USER LOGIN <input type="button" id="dept" style="border:0; width:250px;color:white; background-color:transparent; text-align:left;"/></h4>
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
          <input type="text" class="form-control" placeholder="User ID" name="username" autofocus>
          <br>
          <input type="password" class="form-control" placeholder="Password" name="password">
          <input type="hidden" name="access" id="dept">
          <label class="checkbox">

            </label>
          <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-lock"></i> SIGN IN</button> <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button>
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

  <script type="text/javascript">
    $(document).ready(function() {
      var unique_id = $.gritter.add({
        // (string | mandatory) the heading of the notification
        title: 'Welcome to Human Resource Information System!',
        // (string | mandatory) the text inside the notification
        text: 'Hello! It`s a beautiful day, isn`t it?',
        // (string | optional) the image to display on the left
        image: 'img/hris.jfif',
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

    function myNavFunction(id) {
      $("#date-popover").hide();
      var nav = $("#" + id).data("navigation");
      var to = $("#" + id).data("to");
      console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
    }
  </script>
  <script>
                  $(document).on("click", ".login", function () {
             var myBookId = $(this).data('id');
             $(".modal-body #dept").val( myBookId );
             $(".modal-header #dept").val("("+myBookId+" DEPARTMENT)");
             // As pointed out in comments,
             // it is unnecessary to have to manually call the modal.
             // $('#addBookDialog').modal('show');
          });
        </script>
        <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script class="include" type="text/javascript" src="lib/jquery.dcjqaccordion.2.7.js"></script>
  <script src="lib/jquery.scrollTo.min.js"></script>
  <script src="lib/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="lib/raphael/raphael.min.js"></script>
  <script src="lib/morris/morris.min.js"></script>
  <!--common script for all pages-->
  <script src="lib/common-scripts.js"></script>
  <!--script for this page-->
  <!--script src="lib/morris-conf.js"></!--script-->
  <?php
          $probationary=0;
          $regular=0;
          $resigned=0;
          $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.company FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%resigned%'");
          if(mysqli_num_rows($sqlEmployee)>0){
            while($row=mysqli_fetch_array($sqlEmployee)){
              if($row['company']=="NEWIND"){
                $probationary++;
              }
              if($row['company']=="NESI1"){
                $regular++;
              }
              if($row['company']=="NESI2"){
                $resigned++;
              }
            }
          }
          $totalemployee=mysqli_num_rows($sqlEmployee);
        ?>
  <script>
    Morris.Bar({
        element: 'hero-bar',
        data: [
          {device: 'NEWIND', geekbench: <?=$probationary;?>},
          {device: 'SOLUTIONS', geekbench: <?=$regular;?>},
          {device: 'STRATEGIES', geekbench: <?=$resigned;?>},
        ],
        xkey: 'device',
        ykeys: ['geekbench'],
        labels: ['Total Employees'],
        barRatio: 0.4,
        xLabelAngle: 0,
        hideHover: 'auto',
        barColors: ['#ac92ec']
      });
  </script>
</body>

</html>
