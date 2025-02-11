<!DOCTYPE html>
<html lang="en">
<?php
date_default_timezone_set("Asia/Manila");
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="Dashboard">
  <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="css/zabuto_calendar.css">
  <link rel="stylesheet" type="text/css" href="lib/gritter/css/jquery.gritter.css" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
  <script src="lib/chart-master/Chart.js"></script>
  <title>Employee Portal - North East Solutions Inc.</title>

  <!-- Favicons -->
  <!-- <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon"> -->
  <link rel="icon" type="image/x-icon" href="img/nesi.jpg">
  <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>
  <!-- Bootstrap core CSS -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--external css-->
  <link rel="stylesheet" type="text/css" href="lib/gritter/css/jquery.gritter.css" />   
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
  
  <!-- =======================================================
    Template Name: Dashio
    Template URL: https://templatemag.com/dashio-bootstrap-admin-template/
    Author: TemplateMag.com
    License: https://templatemag.com/license/
  ======================================================= -->
</head>
<style>
    /* Additional styles for positioning the button */
   
    
  </style>
<body>
  <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
</div>
  <div id="login-page">
  <div class="dashboard-button">
    <a href="/hris/" class="btn btn-dashboard">
      <i class="fa fa-dashboard"></i> Dashboard
    </a>
    <a href="/hris/attendance/" class="btn btn-dashboard">
      <i class="fa fa-clock-o"></i> Attendance
    </a>
  
  </div>
  
    <div class="form-container">
     
      <form class="form-login" method="POST" action="authenticate.php">   
      
        <h1 style="top:100px;">
     Login to your account
    </h1>
        <div class="login-wrap">
       
          <input type="password" class="form-control" placeholder="Employee No" autofocus name="empid">
          <br>
          <input type="date" class="form-control" name="birthdate">
         
          <label class="checkbox">

            <!-- <input type="checkbox" value="remember-me"> Remember me -->
            <!-- <span class="pull-right">                
            <a data-toggle="modal" href="login.html#myModal"> Forgot Password?</a>
            </span> -->
            </label>
          <button class="btn btn-theme btn-block" type="submit" name="submit"><i class="fa fa-lock"></i> SIGN IN</button>          
          <hr>
           <div class="registration">
            Please enter your employee number and birthdate.<br/>            
          </div>
   </div>
   

          <!-- <div class="login-social-link centered">
            <p>or you can sign in via your social network</p>
            <button class="btn btn-facebook" type="submit"><i class="fa fa-facebook"></i> Facebook</button>
            <button class="btn btn-twitter" type="submit"><i class="fa fa-twitter"></i> Twitter</button>
          </div> -->
          
       
    </div>
    <div class="logos" style="margin-left: 70px; ">
    © Intern UM 2024 Batch 2 • M.I.Misa - J.M.Lapeceros - A.J.Lawagon
  </div>
        </div> 
         
        <div class="rightsss">
    <img alt="I am Nesi logo with circular text around it" height="200" src="img/iamnesi_logo.JPG" width="200"/>
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
    <button1 id="announcementButton" class="btn btn-primary" style="background:#3079b6; border-radius: 40px 40px; ">
        Announcements 
        <span id="notificationBadge" class="badge" style="background:red; color:white;"><?= $unreadCount ?></span>
    </button1>
</div>

<!-- Announcement Modal -->
<div aria-hidden="true" aria-labelledby="announcementModalLabel" role="dialog1" tabindex="-1" id="announcementModal" class="modal fade">
    <div class="modal-dialog1">
        <div class="modal-content1 " style="text-align: center;">
            <div class="modal-header1" style="text-align: center;">
                <button1 type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button1>
                <h4 class="modal-title1" >Announcements</h4>
            </div>
            <div class="modal-body" id="announcementBody">
             
                <?php if ($isDetails): ?>
                    <ul id="announcementList">
                      
                       <pre style="border:0; background-color:white;white-space:pre-wrap;">  <?php foreach ($announcements as $index => $announcement): ?>
                         
                            <li>
                                <p class="announcementText"><?php echo htmlspecialchars($announcement); ?></p>
                            </li>
                           
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No announcements available.</p>
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
    </div>
        <!-- Modal -->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Forgot Password ?</h4>
              </div>
              <div class="modal-body">
                <p>Enter your e-mail address below to reset your password.</p>
                <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
              </div>
              <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                <button class="btn btn-theme" type="button">Submit</button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- modal -->
      </form>
      
    </div>
    
  </div>
  
  <!-- js placed at the end of the document so the pages load faster -->
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <!--BACKSTRETCH-->
  <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
  <script type="text/javascript" src="lib/jquery.backstretch.min.js"></script>
  <script src="lib/common-scripts.js"></script>
  <script type="text/javascript" src="lib/gritter/js/jquery.gritter.js"></script>
  <script type="text/javascript" src="lib/gritter-conf.js"></script>
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
</body>
</html>
<style>
  .dashboard-button {
  position: absolute;
  top: 10px;
  left: 10px;
}

.dashboard-button .btn-dashboard {
  background-color: #337ab7; /* or any other color you prefer */
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.dashboard-button .btn-dashboard:hover {
  background-color: #23527c; /* or any other hover color you prefer */
}
</style>