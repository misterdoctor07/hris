<?php
          $id=$_GET['id'];

          $sqlProfile=mysqli_query($con,"SELECT * FROM employee_profile WHERE id='$id'");
          $profile=mysqli_fetch_array($sqlProfile);
          $idno=$profile['idno'];
          $empname=$profile['lastname'].", ".$profile['firstname']." ".$profile['middlename']." ".$profile['suffix'];
          $birthdate=date('F d, Y',strtotime($profile['birthdate']));
          $nickname=$profile['nickname'];
          $gender=$profile['sex'];
          if($gender=="M"){
            $gender="Male";
          }else{
            $gender="Female";
          }
          $civilstatus=$profile['civilstatus'];
          if($civilstatus=="S"){
              $civilstatus="Single";
          }elseif($civilstatus=="M"){
              $civilstatus="Married";
          }else{
              $civilstatus="Widowed";
          }
          $eligibility=$profile['eligibility'];
          $address=$profile['address'];

          $sqlDetails=mysqli_query($con,"SELECT * FROM employee_details WHERE idno='$idno'");
          $details=mysqli_fetch_array($sqlDetails);
          $jobid=$details['designation'];
          $deptid=$details['department'];
          $compid=$details['company'];
          $status=$details['status'];
          $shift=date('h:i A',strtotime($details['startshift']))." - ".date('h:i A',strtotime($details['endshift']));
          $location=$details['location'];

          $eligible=date('m/d/Y',strtotime($details['dateleaveeffective']));

          $datehired=date('F d, Y',strtotime($details['dateofhired']));
          $dateregular=date('F d, Y',strtotime($details['dateofregular']));
          $datefulltime=date('F d, Y',strtotime($details['dateoffulltime']));


          $sqlJobTitle=mysqli_query($con,"SELECT * FROM jobtitle WHERE id='$jobid'");
          $jobtitle=mysqli_fetch_array($sqlJobTitle);
          $designation=$jobtitle['jobtitle'];

          $sqlDepartment=mysqli_query($con,"SELECT * FROM department WHERE id='$deptid'");
          $dept=mysqli_fetch_array($sqlDepartment);
          $department=$dept['department'];

          $sqlCompany=mysqli_query($con,"SELECT * FROM settings WHERE companycode='$compid'");
          $company=mysqli_fetch_array($sqlCompany);
          $companyname=$company['companyname'];

          $sqlBenefits=mysqli_query($con,"SELECT * FROM employee_benefits WHERE idno='$idno'");
          if(mysqli_num_rows($sqlBenefits)>0){
            $benefits=mysqli_fetch_array($sqlBenefits);
            $insurance=date('F d, Y',strtotime($benefits['insurance']));
            $hmo=date('F d, Y',strtotime($benefits['hmo']));
            $sss=$benefits['sss'];
            $tin=$benefits['tin'];
            $phic=$benefits['phic'];
            $hdmf=$benefits['hdmf'];
          }else{
            $insurance="";
            $hmo="";
            $sss="";
            $tin="";
            $phic="";
            $hdmf="";
          }


          $sqlChecklist=mysqli_query($con,"SELECT * FROM employee_checklist WHERE idno='$idno'");
          if(mysqli_num_rows($sqlChecklist)>0){
            $checklist=mysqli_fetch_array($sqlChecklist);
            $dateoriented=date('F d, Y',strtotime($checklist['dateoriented']));
            $tempid=$checklist['releasedtempid'];
            $permanentid=$checklist['releasedpermanentid'];
            $statuschecklist=$checklist['status'];
          }else{
            $dateoriented="";
            $tempid="";
            $permanentid="";
            $statuschecklist="";
          }

          $sqlContract=mysqli_query($con,"SELECT * FROM employee_contract WHERE idno='$idno'");
          if(mysqli_num_rows($sqlContract)>0){
            $contract=mysqli_fetch_array($sqlContract);
            $probationary=$contract['probationary']." / ".date('M-d-Y',strtotime($contract['probationarydate']));
            $regular=$contract['regular']." / ".date('M-d-Y',strtotime($contract['regulardate']));
            $fulltime=$contract['fulltime']." / ".date('M-d-Y',strtotime($contract['fulltimedate']));
          }else{
            $probationary="";
            $regular="";
            $fulltime="";
          }

          $sqlReferral=mysqli_query($con,"SELECT ep.lastname,ep.firstname,er.effectivity FROM employee_referral er LEFT JOIN employee_profile ep ON ep.idno=er.referredby WHERE er.idno='$idno'");
          if(mysqli_num_rows($sqlReferral)>0){
            $referral=mysqli_fetch_array($sqlReferral);
            $referredby=$referral['firstname']." ".$referral['lastname'];
            $effectivity=$referral['effectivity'];
          }else{
            $referredby="";
            $effectivity="";
          }
          if (!empty($details['dateofhired']) && !empty($details['dateofregular'])) {
            $hireDate = new DateTime($details['dateofhired']);
            $thresholdDate = new DateTime('2020-07-31'); // End of July 2020
        
            if ($hireDate <= $thresholdDate) {
                // Logic for dateofhire on or before July 2020
                $dhire = new DateTime($details['dateofregular']);
                $dnow = new DateTime(date('Y-m-d'));
                $interval = $dhire->diff($dnow);
                $years = $interval->y;
                $month = $interval->m;
                $days = $interval->d;
                $periodfrom = date('F d, Y', strtotime($years . " years", strtotime($details['dateofregular'])));
                $periodto = date('F d, Y', strtotime('1 years', strtotime($periodfrom)));
            } else {
                // Logic for dateofhire on or after August 2020
                $dhire = new DateTime($details['dateofhired']);
                $dnow = new DateTime(date('Y-m-d'));
                $interval = $dhire->diff($dnow);
                $years = $interval->y;
                $month = $interval->m;
                $days = $interval->d;
                $periodfrom = date('F d, Y', strtotime($years . " years", strtotime($details['dateofhired'])));
                $periodto = date('F d, Y', strtotime('1 years', strtotime($periodfrom)));
            }
        } else {
            // Fallback logic if dates are missing
            $years = $month = $days = 0;
            $periodfrom = $periodto = '';
        }
        

          $sqlLeaveCredits=mysqli_query($con,"SELECT * FROM leave_credits WHERE idno='$idno'");
          if(mysqli_num_rows($sqlLeaveCredits)>0){
            $leave=mysqli_fetch_array($sqlLeaveCredits);
            $vl=$leave['vacationleave']??0;
            $vlused=$leave['vlused']??0;
            $sl=$leave['sickleave']??0;
            $slused=$leave['slused']??0;
            $pto=$leave['pto']??0;
            $ptoused=$leave['ptoused']??0;
            $bday=$leave['bdayleave']??0;
            $bdayused=$leave['blp_used']??0;
            $earlyout=$leave['earlyout']??0;
            $eo_used=$leave['eo_used']??0;
            $spl=$leave['spl']??0;
            $splused=$leave['spl_used']??0;
            $vlrem=$vl-$vlused;
            $slrem=$sl-$slused;
            $ptorem=$pto-$ptoused;
            $blprem=$bday-$bdayused;
            $eorem=$earlyout-$eo_used;
            $splrem=$spl-$splused;
          }else{
            $vl="";
            $vlused="";
            $sl="";
            $slused="";
            $pto="";
            $ptoused="";
            $bday="";
            $bdayused="";
            $earlyout="";
            $eo_used="";
            $vlrem="";
            $slrem="";
            $ptorem="";
            $blprem="";
            $eorem="";
            $spl="";
            $splused="";
          }
          $currentYear = date('Y');

          // Query to calculate total points for the current year
          $sqlPoints = mysqli_query($con, "
              SELECT SUM(points) as total_points 
              FROM points 
              WHERE idno='$idno' AND YEAR(logindate) = '$currentYear'
          ");
          
          if (mysqli_num_rows($sqlPoints) > 0) {
              $point = mysqli_fetch_array($sqlPoints);
              $points = $point['total_points'] ?? 0; // Default to 0 if no points
          } else {
              $points = 0; // Default to 0 if no records found
          }
          
          // Format points to 1 decimal place
          $points = number_format((float)$points, 1, '.', '');
          ?>
        <div class="col-lg-12">
            <a href="?manageemployee"><h4><i class="fa fa-arrow-left"></i> Back</h4></a>
        </div>
          <div class="col-lg-12 mt">
            <div class="row content-panel">
              <div class="col-md-4 profile-text mt mb centered">
                <div class="right-divider hidden-sm hidden-xs">
                  <h4><?=$datehired;?></h4>
                  <h6>DATE HIRED</h6>
                  <h4><?=$dateregular;?></h4>
                  <h6>DATE OF REGULARIZATION</h6>
                  <h4><?=$datefulltime;?></h4>
                  <h6>DATE OF FULLTIME</h6>
                </div>
              </div>
              <!-- /col-md-4 -->
              <div class="col-md-4 profile-text">
                <h3><?=$empname;?></h3>
                <h6><?=$designation;?></h6>
                <p><?=$companyname;?></p>
                <br>
                <?php
// Fetch the current year (or set a specific year if needed)
$currentYear = date('Y');

// Initialize variables
$total_points = 0;
$breakdown_by_month = [];
$breakdown_html = ""; // Initialize $breakdown_html to avoid warnings

// Translation mapping
$translations = [
    "12" => "Absent with proper called-in",
    "13" => "Absent with proper called-in, Invalid reason",
    "65" => "Forgot to clock in (first shift) and failed to submit form and Over-break",
    "15" => "Late within 15 minutes",
    "16" => "Late 16 minutes and up with called-in",
    "17" => "Late 16 minutes and up without called-in",
    "22" => "Forgot to clock in (first shift) and failed to submit form",
    "19" => "Over - Break (2 minutes and up)",
    "63" => "Forgot to clock in/out (Lunch) w/ non-work related reason",
    "62" => "Absence Without Leave",
    "66" => "Forgot to clock in (first shift) and failed to submit form and Missed Out/In (Lunch)",
    "78" => "Sick leave w/o supporting documents",
    "72" => "Government Sanction",
    "67" => "Internet Outrage",
    "68" => "Natural Calamity",
    "77" => "PC Problem",
    "64" => "Power Outage",
    "73" => "Transportation Issue",
    "79" => "SL with proper CI, invalid reason",
    "80" => "SL w/ CI w/in 30 mins prior shift & 15 mins"
];

// Query to fetch points breakdown for the current year
$sqlPointsBreakdown = mysqli_query($con, "
    SELECT offense, points, logindate 
    FROM points 
    WHERE idno='$idno' AND YEAR(logindate) = '$currentYear'
");

if (mysqli_num_rows($sqlPointsBreakdown) > 0) {
    while ($row = mysqli_fetch_assoc($sqlPointsBreakdown)) {
        // Translate offense if a match is found
        $translated_offense = isset($translations[$row['offense']]) ? $translations[$row['offense']] : $row['offense'];

        // Extract month from the logindate
        $months = date("F", strtotime($row['logindate'])); // Full month name (e.g., January)

        // Group offenses by month
        $breakdown_by_month[$months][] = [
            'offense' => $translated_offense,
            'points' => (float)$row['points'],
            'logindate' => $row['logindate']
        ];

        // Accumulate total points
        $total_points += (float)$row['points'];
    }
} else {
    $total_points = 0;
}

// Format total points to 1 decimal place
$total_points = number_format((float)$total_points, 1, '.', '');

// Sort offenses within each month by points in descending order
foreach ($breakdown_by_month as &$offenses) {
    usort($offenses, function ($a, $b) {
        return $b['points'] <=> $a['points'];
    });
}
unset($offenses); // Unset reference to avoid issues

// Generate the breakdown HTML
$breakdown_html = "<ul style='margin: 0; padding: 0; list-style: none;'>";
if (!empty($breakdown_by_month)) {
    foreach ($breakdown_by_month as $months => $offenses) {
        $breakdown_html .= "<li><strong>$months</strong><ul>";
        foreach ($offenses as $offense) {
            $formatted_date = date("M-d-Y", strtotime($offense['logindate']));
            $breakdown_html .= "<li>" . number_format($offense['points'], 1, '.', '') . " : " . htmlspecialchars($offense['offense']) . " (Date: " . $formatted_date . ")</li>";
        }
        $breakdown_html .= "</ul></li>";
    }
} else {
    $breakdown_html .= "<li>No points recorded for $currentYear.</li>";
}
$breakdown_html .= "</ul>";
?>

<!-- HTML Section -->
<p style="color:red; font-size: 16px;">
    <span id="points-container" style="cursor: pointer;">
        Points: <?= $total_points; ?>
    </span>
</p>

<!-- Modal Structure -->
<div id="breakdown-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000;">
    <div style="position: relative; margin: 10% auto; padding: 20px; background: white; width: 50%; border-radius: 8px;">
        <h3>Points Breakdown</h3>
        <div id="breakdown-content">
            <?= $breakdown_html; ?>
        </div>
        <button id="close-modal" style="margin-top: 20px; background: red; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Close</button>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const pointsContainer = document.getElementById('points-container');
        const breakdownModal = document.getElementById('breakdown-modal');
        const closeModal = document.getElementById('close-modal');

        // Open the modal
        pointsContainer.addEventListener('click', function () {
            breakdownModal.style.display = 'block';
        });

        // Close the modal
        closeModal.addEventListener('click', function () {
            breakdownModal.style.display = 'none';
        });

        // Close the modal if the user clicks outside the modal content
        window.addEventListener('click', function (event) {
            if (event.target === breakdownModal) {
                breakdownModal.style.display = 'none';
            }
        });
    });
</script>

</div>
              <!-- /col-md-4 -->
              <?php
            
            // Check if the form is submitted
            
            if (isset($_POST['submit'])) {
               $idno = $_POST['idno']; // User's ID
               $target_dir = "../Employees/";
           
               // Handle file upload
               if (!empty($_FILES['profile_pic']['name'])) {
                   $file_ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
                   $target_file = $target_dir . $idno . "." . $file_ext;
           
                   // Check if it's a valid image file (you can extend this)
                   $valid_extensions = array("jpg", "png", "jpeg");
                   if (in_array($file_ext, $valid_extensions)) {
                       // Check if temporary file exists and is readable
                       if (file_exists($_FILES['profile_pic']['tmp_name']) && is_readable($_FILES['profile_pic']['tmp_name'])) {
                           // Delete existing profile picture
                           $existing_files = glob($target_dir . $idno . ".*");
                           foreach ($existing_files as $existing_file) {
                               unlink($existing_file);
                           }
           
                           // Upload the new file
                           if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                               $image = $target_file;
                           } else {
                               $error = "Error uploading file.";
                               error_log("Failed to move uploaded file: " . $_FILES['profile_pic']['tmp_name']);
                           }
                       } else {
                           $error = "Temporary file not found or not readable.";
                           error_log("Temporary file issue: " . $_FILES['profile_pic']['tmp_name']);
                       }
                   } else {
                       $error = "Invalid file format. Only JPG, JPEG, and PNG allowed.";
                   }
               } else {
                   $error = "No file selected.";
               }
           } else {
               // Default image if no file is uploaded
               if (file_exists("../Employees/".$idno.".png")) {
                   $image = "../Employees/".$idno.".png";
               } elseif (file_exists("../Employees/".$idno.".jpg")) {
                   $image = "../Employees/".$idno.".jpg";
               } else {
                   $image = "path/to/default/image.jpg"; // Default image if no profile pic exists
               }
           }
            ?>
              <div class="col-md-4 centered">
                <div class="profile-pic">
                  <p><img src="<?=$image;?>" class="img-circle"></p>
                  <p>
                    <!-- <button class="btn btn-theme"><i class="fa fa-check"></i> Follow</button>
                    <button class="btn btn-theme02">Add</button> -->
                    <?=$idno;?>
                  </p>
                </div>
              </div>
              <!-- /col-md-4 -->
            </div>
            <!-- /row -->
          </div>
          <!-- /col-lg-12 -->
          <div class="col-lg-12 mt">
            <div class="row content-panel">
              <div class="panel-heading">
                <ul class="nav nav-tabs nav-justified">
                  <li class="active">
                    <a data-toggle="tab" href="#overview">Overview</a>
                  </li>
                  <!-- <li>
                    <a data-toggle="tab" href="#contact" class="contact-map">Contact</a>
                  </li>
                  <li>
                    <a data-toggle="tab" href="#edit">Edit Profile</a>
                  </li> -->
                </ul>
              </div>
              <!-- /panel-heading -->
              <div class="panel-body">
                <div class="tab-content">
                  <div id="overview" class="tab-pane active">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="detailed">
                        <h4>Employee Information</h4>
                        <div class="col-lg-12">
                            <table width="100%">
                                <tr>
                                    <td width="15%">Nickname:</td>
                                    <td><i>"<?=$nickname;?>"</i></td>
                                </tr>
                                <tr>
                                    <td width="15%">Date of Birth:</td>
                                    <td><?=$birthdate;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Gender:</td>
                                    <td><?=$gender;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Civil Status:</td>
                                    <td><?=$civilstatus;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Eligibility:</td>
                                    <td><?=$eligibility;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Address:</td>
                                    <td><?=$address;?></td>
                                </tr>
                            </table>
                        </div>
                        </div>
                        <div class="detailed">
                        <h4>Employment Information</h4>
                        <div class="col-lg-12">
                            <table width="100%">
                                <tr>
                                    <td width="15%">Department:</td>
                                    <td><?=$department;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Status:</td>
                                    <td><?=$status;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Shift:</td>
                                    <td><?=$shift;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Work Location:</td>
                                    <td><?=$location;?></td>
                                </tr>
                            </table>
                        </div>
                        </div>
                        <div class="detailed">
                        <h4>Employee Benefits Information</h4>
                        <div class="col-lg-12">
                            <table width="100%">
                              <tr>
                                  <td width="15%">LC Effectivity:</td>
                                  <td><?=$eligible;?></td>
                              </tr>
                                <tr>
                                    <td width="25%">Life Insurance Effectivity:</td>
                                    <td><?=$insurance;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">HMO Effectivity:</td>
                                    <td><?=$hmo;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">SSS:</td>
                                    <td><?=$sss;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">TIN:</td>
                                    <td><?=$tin;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">PHIC:</td>
                                    <td><?=$phic;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">PAG-IBIG:</td>
                                    <td><?=$hdmf;?></td>
                                </tr>
                            </table>
                        </div>
                        </div>
                        <div class="detailed">
                        <h4>Employee Checklist</h4>
                        <div class="col-lg-12">
                            <table width="100%">
                                <tr>
                                    <td width="25%">Date Oriented:</td>
                                    <td><?=$dateoriented;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Temp ID:</td>
                                    <td><?=$tempid;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Permanent ID:</td>
                                    <td><?=$permanentid;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Requirement/s:</td>
                                    <td><?=$statuschecklist;?></td>
                                </tr>
                            </table>
                        </div>
                        </div>
                        <div class="detailed">
                        <h4>Employee Contract Status</h4>
                        <div class="col-lg-12">
                            <table width="100%">
                                <tr>
                                    <td width="25%">Probationary:</td>
                                    <td><?=$probationary;?></td>
                                </tr>
                            </table>
                        </div>
                        </div>
                        <div class="detailed">
                        <h4>Referral Information</h4>
                        <div class="col-lg-12">
                            <table width="100%">
                                <tr>
                                    <td width="25%">Referred By:</td>
                                    <td><?=$referredby;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Effectivity:</td>
                                    <td><?=$effectivity;?></td>
                                </tr>
                            </table>
                        </div>
                        </div>
                        <!-- /detailed -->
                      </div>
                      <!-- /col-md-6 -->
                      <div class="col-md-6 detailed">
                        <h4>Leave Credits</h4>
                        <div class="col-lg-12">
                            <table width="100%">
                                <tr>
                                    <td width="25%">Length of Service (yrs):</td>
                                    <td><?=$years;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Length of Service (months):</td>
                                    <td><?=$month;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Length of Service (days):</td>
                                    <td><?=$days;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Period (from):</td>
                                    <td><?=$periodfrom;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Period (through):</td>
                                    <td><?=$periodto;?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4 detailed">
                        <h4>Vacation Leave Credits</h4>
                        <div class="col-lg-12">
                            <table width="100%">
                                <tr>
                                    <td width="50%">Credits:</td>
                                    <td><?=$vl;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Used:</td>
                                    <td><?=$vlused;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Remaining:</td>
                                    <td><?=$vlrem;?></td>
                                </tr>
                            </table>
                        </div>
                      </div>
                      <div class="col-md-4 detailed">
                        <h4>Sick Leave Credits</h4>
                        <div class="col-lg-12">
                            <table width="100%">
                                <tr>
                                    <td width="50%">Credits:</td>
                                    <td><?=$sl;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Used:</td>
                                    <td><?=$slused;?></td>
                                </tr>
                                <tr>
                                    <td width="15%">Remaining:</td>
                                    <td><?=$slrem;?></td>
                                </tr>
                            </table>
                        </div>
                      </div>
                      <div class="col-md-4 detailed">
                        <h4>PTO Credits</h4>
                        <div class="col-lg-12">
                            <table width="100%">
                                <tr>
                                    <td width="50%">Credits:</td>
                                    <td><?=$pto;?></td>
                                </tr>
                                <tr>
                                    <td width="30%">Used:</td>
                                    <td><?=$ptoused;?></td>
                                </tr>
                                <tr>
                                    <td width="30%">Remaining:</td>
                                    <td><?=$ptorem;?></td>
                                </tr>
                            </table>
                        </div>
                      </div>
                      <div class="col-md-4 detailed" style="margin-top:10px">
                        <h4>BLP Credits</h4>
                        <div class="col-lg-12">
                            <table width="100%">
                                <tr>
                                    <td width="50%">Credits:</td>
                                    <td><?=$bday;?></td>
                                </tr>
                                <tr>
                                    <td width="30%">Used:</td>
                                    <td><?=$bdayused;?></td>
                                </tr>
                                <tr>
                                    <td width="30%">Remaining:</td>
                                    <td><?=$blprem;?></td>
                                </tr>
                            </table>
                        </div>
                      </div>
                      <div class="col-md-4 detailed" style="margin-top:10px">
                        <h4>EO Credits</h4>
                        <div class="col-lg-12">
                            <table width="100%">
                                <tr>
                                    <td width="50%">Credits:</td>
                                    <td><?=$earlyout;?></td>
                                </tr>
                                <tr>
                                    <td width="30%">Used:</td>
                                    <td><?=$eo_used;?></td>
                                </tr>
                                <tr>
                                    <td width="30%">Remaining:</td>
                                    <td><?=$eorem;?></td>
                                </tr>
                            </table>
                        </div>
                      </div>
                      <div class="col-md-4 detailed" style="margin-top:10px">
                        <h4>SPL CREDITS</h4>
                        <div class="col-lg-12">
                            <table width="100%">
                                <tr>
                                    <td width="50%">Credits: </td>
                                    <td><?=$spl;?></td>
                                </tr>
                                <tr>
                                    <td width="30%">Used: </td>
                                    <td><?=$splused;?></td>
                                </tr>
                                <tr>
                                    <td width="30%">Remaining: </td>
                                    <td><?=$splrem;?></td>
                                </tr>
                            </table>
                        </div>
                      </div>


                      <!-- /col-md-6 -->
                    </div>
                    <!-- /OVERVIEW -->
                  </div>
                  <!-- /tab-pane -->
                </div>
                <!-- /tab-content -->
              </div>
              <!-- /panel-body -->
            </div>
            <!-- /col-lg-12 -->
          </div>
