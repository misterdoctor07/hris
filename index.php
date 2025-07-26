<?php
    date_default_timezone_set('Asia/Manila');
    include 'config.php';
    
    $today = date('Y-m-d');
    $updateddatetime = date('Y-m-d H:i:s');
    
    // Get all movements effective today
    $query = mysqli_query($con, "
        SELECT * FROM movement_tracker 
        WHERE effectivitydate = '$today'
    ");
    
    while ($row = mysqli_fetch_assoc($query)) {
        $idno = $row['idno'];
    
        // Apply company movement
        if (!empty($row['companyto'])) {
            $newCompany = $row['companyto'];
            mysqli_query($con, "
                UPDATE employee_details 
                SET company = '$newCompany', updateddatetime = '$updateddatetime' 
                WHERE idno = '$idno'
            ");
        }
    
        // Apply department movement
        if (!empty($row['departmentto'])) {
            $newDepartment = $row['departmentto'];
            mysqli_query($con, "
                UPDATE employee_details 
                SET department = '$newDepartment', updateddatetime = '$updateddatetime'  
                WHERE idno = '$idno'
            ");
        }
    
        // Apply job title (designation) movement
        if (!empty($row['jobto'])) {
            $newDesignation = $row['jobto'];
            mysqli_query($con, "
                UPDATE employee_details 
                SET designation = '$newDesignation', updateddatetime = '$updateddatetime'  
                WHERE idno = '$idno'
            ");
        }
    
        // Apply shift movement
        if (!empty($row['shiftto']) && !empty($row['shift_type'])) {
            $shiftParts = explode('-', $row['shiftto']);
            $startShift = trim($shiftParts[0]);
            $endShift = trim($shiftParts[1]);
            $shiftType = $row['shift_type'];

            mysqli_query($con, "
                UPDATE employee_details 
                SET startshift = '$startShift', endshift = '$endShift', shift_type = '$shiftType', updateddatetime = '$updateddatetime' 
                WHERE idno = '$idno'
            ");
        }

        $checkPeriod = mysqli_query($con, "
                SELECT periodto FROM leave_credits 
                WHERE idno = '$idno' 
                AND periodto = '$today'
            ") or die("Check Period Error: " . mysqli_error($con));
            
            if (mysqli_num_rows($checkPeriod) > 0) {
            
                // Backup old credits
                mysqli_query($con, "
                    INSERT INTO leave_credits_previous (
                        idno, vlused, slused, 
                        blp_used, spl_used, updatedtime
                    )
                    SELECT idno, vlused, slused, blp_used, spl, spl_used, NOW()
                    FROM leave_credits 
                    WHERE idno = '$idno'
                ") or die("Backup Query Error: " . mysqli_error($con));
            
                // Reset all credits
                mysqli_query($con, "
                    UPDATE leave_credits 
                    SET
                        vlused = 0, 
                        slused = 0,
                        blp_used = 0, 
                        spl_used = 0,
                        last_reset_date = '$updateddatetime'
                    WHERE idno = '$idno'
                    AND periodto = '$today'
                ") or die("Reset Query Error: " . mysqli_error($con));
            }
      }
      
    // Get all WFH effective today
    $queryWFH = mysqli_query($con, "
        SELECT * FROM wfh_application 
        WHERE date_effective = '$today'
    ");
    
    while ($row = mysqli_fetch_assoc($queryWFH)) {
        $idno = $row['idno'];
        $status = $row['application_status'];
    
        if ($status === 'Approved') {
            mysqli_query($con, "
                UPDATE employee_details 
                SET location = 'WFH'
                WHERE idno = '$idno'
            ") or die("WFH Update Error: " . mysqli_error($con));
        }
    }
    
    // Get all transfer effective today
    $queryTransfer = mysqli_query($con, "
        SELECT * FROM work_transfer 
        WHERE date_transfer = '$today'
    ");
    
    while ($row = mysqli_fetch_assoc($queryTransfer)) {
        $idno = $row['idno'];
        $status = $row['application_status'];
        $new_loc = $row['new_loc'];
    
        if ($status === 'Approved') {
            mysqli_query($con, "
                UPDATE employee_details 
                SET work_area = '$new_loc'
                WHERE idno = '$idno'
            ") or die("Transfer Update Error: " . mysqli_error($con));
        }
    }
?>
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
        <link href="css/style-responsive.css" rel="stylesheet">
        <script src="lib/chart-master/Chart.js"></script>
        <title>Employee Portal - North East Solutions Inc.</title>

        <link rel="icon" type="image/x-icon" href="img/iconhris_2.png">
        <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>
        <!-- Bootstrap core CSS -->
        <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!--external css-->
        <link rel="stylesheet" type="text/css" href="lib/gritter/css/jquery.gritter.css" />   
        <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <!-- Custom styles for this template -->
        <link href="css/style.css" rel="stylesheet">
        <link href="css/style-responsive.css" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ruda:400,700,900">

        <!-- =======================================================
            Template Name: Dashio
            Template URL: https://templatemag.com/dashio-bootstrap-admin-template/
            Author: TemplateMag.com
            License: https://templatemag.com/license/
        ======================================================= -->
    </head>
    <style>
        /* Additional styles for positioning the button */
        body {
            background-image: url('/hris/employeeportal/img/3.png');
            background-size:1920px 1020px;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Ruda', sans-serif;
            font-size: 13px;
            color: #000000;
        }
    </style>
    <body>
        <div id="login-page">
            <div class="form-container">
                <form class="form-login" method="POST" action="authenticate.php">   
                    <h1 style="top:100px;">Login to your account</h1>
                    <div class="login-wrap">
                        <!-- Username Field -->
                        <input type="text" class="form-control" placeholder="Username" name="username" required autofocus>
                        <br>
                        <!-- Password Field with Peek Toggle -->
                        <div class="password-container">
                            <input type="password" class="form-control" id="password" placeholder="Password" name="password" autocomplete="off" required>
                            <span class="toggle-password" onclick="togglePasswordVisibility()">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        <br>
                        <button class="btn btn-theme btn-block" type="submit" name="submit">
                            <i class="fa fa-lock"></i> SIGN IN
                        </button>
                        <hr>
                        <div class="registration">
                            Please enter your username and password.<br/>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                function togglePasswordVisibility() {
                    const passwordField = document.getElementById("password");
                    const toggleIcon = document.querySelector(".toggle-password i");
            
                    if (passwordField.type === "password") {
                        passwordField.type = "text";
                        toggleIcon.classList.remove("fa-eye");
                        toggleIcon.classList.add("fa-eye-slash");
                    } else {
                        passwordField.type = "password";
                        toggleIcon.classList.remove("fa-eye-slash");
                        toggleIcon.classList.add("fa-eye");
                    }
                }
            </script>
            <style>
                .password-container {
                    position: relative;
                }
            
                .toggle-password {
                    position: absolute;
                    right: 10px;
                    top: 50%;
                    transform: translateY(-50%);
                    cursor: pointer;
                    color: #777;
                }
            
                .toggle-password:hover {
                    color: #333;
                }
            </style>
            <div class="logos" style="margin-left: 70px; color:white; ">
                © Engr. Misa & Engr. Lapeceros.
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
            //Disallow autoforward
            window.onload = function () {
                if (performance.getEntriesByType("navigation")[0].type === "back_forward") {
                  location.href = "https://nesistaff.com/"; // Force logout if navigating forward
                }
            };
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