<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['idno'])) {
    die("<script>alert('Session expired. Please log in again.'); window.location='/index.php';</script>");
}
?>
<?php
          $id=$_SESSION['idno'];

          $sqlProfile=mysqli_query($con,"SELECT * FROM employee_profile WHERE idno='$id'");
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
          $eligible=date('F d, Y',strtotime($details['dateleaveeffective']));

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

            $sqlContract = mysqli_query($con, "SELECT * FROM employee_contract WHERE idno='$idno'");
            if (mysqli_num_rows($sqlContract) > 0) {
                $contract = mysqli_fetch_array($sqlContract);
            
                // Function to format date safely
                function formatDate($date) {
                    return $date ? date('M-d-Y', strtotime($date)) : 'N/A';
                }
            
                // Format probationary
                $probationary = $contract['probationary'] . " / " . formatDate($contract['probationarydate']);
            
                // Format regular
                $regular = $contract['regular'] . " / " . formatDate($contract['regulardate']);
            
                // Format fulltime
                $fulltime = $contract['fulltime'] . " / " . formatDate($contract['fulltimedate']);
            } else {
                $probationary = "";
                $regular = "";
                $fulltime = "";
            }

          $sqlReferral=mysqli_query($con,"SELECT ep.lastname,ep.firstname,er.effectivity 
          FROM employee_referral er 
          LEFT JOIN employee_profile ep ON ep.idno=er.referredby
          WHERE er.idno='$idno'");

          if (mysqli_num_rows($sqlReferral) > 0) {
              $referral = mysqli_fetch_array($sqlReferral);
              $referredby = $referral['firstname'] . " " . $referral['lastname'];
          
              if (($referral['effectivity']) == "0001-01-01") {
                  $effectivity = "";
              } else {
                  $effectivity = $referral['effectivity'];
              }
          } else {
              $referredby = "";
              $effectivity = "";
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
                $monthz = $interval->m;
                $days = $interval->d;
                $periodfrom = date('F d, Y', strtotime($years . " years", strtotime($details['dateofregular'])));
                $periodto = date('F d, Y', strtotime('1 years', strtotime($periodfrom)));
            } else {
                // Logic for dateofhire on or after August 2020
                $dhire = new DateTime($details['dateofhired']);
                $dnow = new DateTime(date('Y-m-d'));
                $interval = $dhire->diff($dnow);
                $years = $interval->y;
                $monthz = $interval->m;
                $days = $interval->d;
                $periodfrom = date('F d, Y', strtotime($years . " years", strtotime($details['dateofhired'])));
                $periodto = date('F d, Y', strtotime('1 years', strtotime($periodfrom)));
            }
        } else {
            // Fallback logic if dates are missing
            $years = $monthz = $days = 0;
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
            $jan_earlyout=$leave['jan_earlyout']??0;
            $jan_eo_used=$leave['jan_eo_used']??0;
            $feb_earlyout=$leave['feb_earlyout']??0;
            $feb_eo_used=$leave['feb_eo_used']??0;
            $mar_earlyout=$leave['mar_earlyout']??0;
            $mar_eo_used=$leave['mar_eo_used']??0;
            $apr_earlyout=$leave['apr_earlyout']??0;
            $apr_eo_used=$leave['apr_eo_used']??0;
            $may_earlyout=$leave['may_earlyout']??0;
            $may_eo_used=$leave['may_eo_used']??0;
            $jun_earlyout=$leave['jun_earlyout']??0;
            $jun_eo_used=$leave['jun_eo_used']??0;
            $jul_earlyout=$leave['jul_earlyout']??0;
            $jul_eo_used=$leave['jul_eo_used']??0;
            $aug_earlyout=$leave['aug_earlyout']??0;
            $aug_eo_used=$leave['aug_eo_used']??0;
            $sep_earlyout=$leave['sep_earlyout']??0;
            $sep_eo_used=$leave['sep_eo_used']??0;
            $oct_earlyout=$leave['oct_earlyout']??0;
            $oct_eo_used=$leave['oct_eo_used']??0;
            $nov_earlyout=$leave['nov_earlyout']??0;
            $nov_eo_used=$leave['nov_eo_used']??0;
            $dec_earlyout=$leave['dec_earlyout']??0;
            $dec_eo_used=$leave['dec_eo_used']??0;
            $spl=$leave['spl']??0;
            $splused=$leave['spl_used']??0;
            $vlrem=$vl-$vlused;
            $slrem=$sl-$slused;
            $ptorem=$pto-$ptoused;
            $blprem=$bday-$bdayused;
            $jan_eorem=$jan_earlyout-$jan_eo_used;
            $feb_eorem=$feb_earlyout-$feb_eo_used;
            $mar_eorem=$mar_earlyout-$mar_eo_used;
            $apr_eorem=$apr_earlyout-$apr_eo_used;
            $may_eorem=$may_earlyout-$may_eo_used;
            $jun_eorem=$jun_earlyout-$jun_eo_used;
            $jul_eorem=$jul_earlyout-$jul_eo_used;
            $aug_eorem=$aug_earlyout-$aug_eo_used;
            $sep_eorem=$sep_earlyout-$sep_eo_used;
            $oct_eorem=$oct_earlyout-$oct_eo_used;
            $nov_eorem=$nov_earlyout-$nov_eo_used;
            $dec_eorem=$dec_earlyout-$dec_eo_used;
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
            $jan_earlyout="";
            $jan_eo_used="";
            $feb_earlyout="";
            $feb_eo_used="";
            $mar_earlyout="";
            $mar_eo_used="";
            $apr_earlyout="";
            $apr_eo_used="";
            $may_earlyout="";
            $may_eo_used="";
            $jun_earlyout="";
            $jun_eo_used="";
            $jul_earlyout="";
            $jul_eo_used="";
            $aug_earlyout="";
            $aug_eo_used="";
            $sep_earlyout="";
            $sep_eo_used="";
            $oct_earlyout="";
            $oct_eo_used="";
            $nov_earlyout="";
            $nov_eo_used="";
            $dec_earlyout="";
            $dec_eo_used="";
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
    "19" => "Over-Break (lunch)",
    "63" => "Forgot to clock in/out (Lunch) w/ non-work related reason",
    "62" => "Absent w/ CI w/in 30mins prior shift & 15 mins after",
    "66" => "Forgot to clock in (first shift) and failed to submit form and Missed Out/In (Lunch)",
    "78" => "Sick leave w/o supporting documents",
    "72" => "Government Sanction",
    "67" => "Internet Outage",
    "68" => "Natural Calamity",
    "77" => "PC Problem",
    "64" => "Power Outage",
    "73" => "Transportation Issue",
    "79" => "SL with proper CI, invalid reason",
    "83" => "Server Down/HRIS unreachable",
    "80" => "SL w/ CI w/in 30 mins prior shift & 15 mins",
    "84" => "Required Duty"
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
     <div class="col-lg-12 mt">
    <div class="row content-panel">
        <!-- Left Column - Profile Card -->
        <div class="col-md-4">
        <div class="profile-card-with-cover" style="margin-top: 0;">
        <!-- Cover Photo -->
        <?php
// Check if cover photo exists with any extension
$cover_extensions = ['jpg', 'jpeg', 'png'];
$cover_found = false;

foreach ($cover_extensions as $ext) {
    $cover_path = "/hris/covers/{$idno}.{$ext}";
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $cover_path)) {
        $cover_found = true;
        break;
    }
}

$cover_photo_url = $cover_found ? $cover_path : '/hris/Employees/default_image.png';
?>
        <div class="cover-photo" 
     style="background: url('<?= htmlspecialchars($cover_photo_url) ?>') center/cover; 
            background-color: #f4f4f4;">
    <!-- Cover Photo Upload Form -->
    <form method="POST" enctype="multipart/form-data" class="cover-form" id="coverForm" style="display: inline;">
        <input type="hidden" name="idno" value="<?= htmlspecialchars($idno ?? '') ?>">
        <input type="hidden" name="upload_cover" value="1">
        <input type="file" name="cover_photo" id="cover_photo" style="display: none;">
        <button type="button" class="btn btn-xs btn-default cover-upload-btn" 
                onclick="document.getElementById('cover_photo').click()">
            <i class="fa fa-image"></i> Change Cover
        </button>
    </form>
</div>
        
        <!-- Profile Content -->
        <div class="profile-card">
            <div class="profile-header text-center">
                <div class="profile-header-content">
                    <div class="profile-pic-container">
                        <img src="<?= $image; ?>" class="img-circle" alt="Profile Picture">
                        <div class="camera-icon" onclick="document.getElementById('profile_pic').click();">
                            <i class="fa fa-camera" aria-hidden="true"></i>
                        </div>
                    </div>
                    <!-- Profile Picture Form -->
                    <form method="POST" enctype="multipart/form-data" class="profile-form">
                        <input type="hidden" name="idno" value="<?= $idno; ?>">
                        <input type="file" name="profile_pic" id="profile_pic" style="display: none;">
                        <button type="submit" name="submit" class="btn btn-xs btn-primary" onclick="return confirm('Upload this picture?');">
                            <i class="fa fa-upload"></i> Upload
                        </button>
                        <button name="delete" class="btn btn-xs btn-danger" onclick="return confirm('Delete this picture?');">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                    
                    <h3><?=$empname;?></h3>
                    <p class="title"><?=$designation;?></p>
                    <p class="company"><?=$companyname;?></p>
                    <p class= "centered"><?= $idno; ?></p>
                    
                    <div class="stats">
                        <div class="stat-item">
                            <span class="stat-number" style="color:red;"><?=$total_points;?></span>
                            <span class="stat-label" style="color:red;">POINTS</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?=$years;?></span>
                            <span class="stat-label">YEARS</span>
                        </div>
                    </div>
                </div>
                
                <!-- Employee Information Tabs -->
                <div class="profile-tabs">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#personal" data-toggle="tab">Personal</a></li>
                        <li><a href="#employment" data-toggle="tab">Employment</a></li>
                        <li><a href="#benefits" data-toggle="tab">Benefits</a></li>
                    </ul>
                    
                    <div class="tab-content">
                        <!-- Personal Tab -->
                        <div class="tab-pane active" id="personal">
                            <div class="detail-item">
                                <i class="fa fa-user"></i>
                                <span>Nickname</span>
                                <span class="value">"<?=$nickname;?>"</span>
                            </div>
                            <div class="detail-item">
                                <i class="fa fa-birthday-cake"></i>
                                <span>Birthdate</span>
                                <span class="value"><?=$birthdate;?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fa fa-venus-mars"></i>
                                <span>Gender</span>
                                <span class="value"><?=$gender;?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fa fa-heart"></i>
                                <span>Civil Status</span>
                                <span class="value"><?=$civilstatus;?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fa fa-certificate"></i>
                                <span>Eligibility</span>
                                <span class="value"><?=$eligibility;?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fa fa-home"></i>
                                <span>Address</span>
                                <span class="value"><?=$address;?></span>
                            </div>
                        </div>
                        
                        <!-- Employment Tab -->
                        <div class="tab-pane" id="employment">
                            <div class="detail-item">
                                <i class="fa fa-building"></i>
                                <span>Department</span>
                                <span class="value"><?=$department;?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fa fa-briefcase"></i>
                                <span>Status</span>
                                <span class="value"><?=$status;?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fa fa-clock-o"></i>
                                <span>Shift</span>
                                <span class="value"><?=$shift;?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fa fa-map-marker"></i>
                                <span>Location</span>
                                <span class="value"><?=$location;?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fa fa-calendar-check-o"></i>
                                <span>Date Oriented</span>
                                <span class="value"><?=$dateoriented;?></span>
                            </div>
                        </div>
                        
                        <!-- Benefits Tab -->
                        <div class="tab-pane" id="benefits">
                            <div class="detail-item">
                                <i class="fa fa-id-card"></i>
                                <span>Temp ID</span>
                                <span class="value"><?=$tempid;?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fa fa-id-card-o"></i>
                                <span>Permanent ID</span>
                                <span class="value"><?=$permanentid;?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fa fa-shield"></i>
                                <span>LC Effectivity</span>
                                <span class="value"><?=$eligible;?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fa fa-heartbeat"></i>
                                <span>HMO Effectivity</span>
                                <span class="value"><?=$hmo;?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fa fa-umbrella"></i>
                                <span>Insurance</span>
                                <span class="value"><?=$insurance;?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            
            <!-- Government IDs Section -->
            <div class="ids-card mt-20">
                <h4>Government IDs</h4>
                <div class="detail-item">
                    <i class="fa fa-credit-card"></i>
                    <span>SSS</span>
                    <span class="value"><?=$sss;?></span>
                </div>
                <div class="detail-item">
                    <i class="fa fa-credit-card"></i>
                    <span>TIN</span>
                    <span class="value"><?=$tin;?></span>
                </div>
                <div class="detail-item">
                    <i class="fa fa-credit-card"></i>
                    <span>PHIC</span>
                    <span class="value"><?=$phic;?></span>
                </div>
                <div class="detail-item">
                    <i class="fa fa-credit-card"></i>
                    <span>PAG-IBIG</span>
                    <span class="value"><?=$hdmf;?></span>
                    </div>
            </div>
        </div>
    </div>
</div>

        <!-- Right Column - Main Content -->
        <div class="col-md-8">
            <!-- Contract Status Cards -->
            <div class="row">
                <div class="col-md-4">
                    <div class="status-card probationary">
                        <i class="fa fa-hourglass-start"></i>
                        <span class="status-title">Probationary</span>
                        <span class="status-date"><?=$datehired;?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="status-card regular">
                        <i class="fa fa-thumbs-up"></i>
                        <span class="status-title">Regular</span>
                        <span class="status-date"><?=$dateregular?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="status-card fulltime">
                        <i class="fa fa-star"></i>
                        <span class="status-title">Full-Time</span>
                        <span class="status-date"><?=$datefulltime;?></span>
                    </div>
                </div>
            </div>
            
            <!-- Leave Metrics -->
            <div class="row mt-20">
                <div class="col-md-4">
                    <div class="metric-card">
                        <span class="metric-number"><?=$vlrem;?></span>
                        <span class="metric-label">VACATION DAYS LEFT</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <span class="metric-number"><?=$slrem;?></span>
                        <span class="metric-label">SICK DAYS LEFT</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <span class="metric-number"><?=$ptorem;?></span>
                        <span class="metric-label">PTO AVAILABLE</span>
                    </div>
                </div>
            </div>
<div class="attendance-card mt-20">
    <h4>Today's Attendance (<?= date('F d, Y') ?>)</h4>
    <?php
    // Get employee's shift information safely
    $shift_query = mysqli_query($con, "SELECT startshift, endshift FROM employee_details WHERE idno='$idno'");
    
    if (!$shift_query) {
        // If shift query fails, assume regular shift
        $is_night_shift = false;
    } else {
        $shift_data = mysqli_fetch_assoc($shift_query) ?? ['startshift' => '00:00:00', 'endshift' => '09:00:00'];
        $startshift = $shift_data['startshift'];
        $endshift = $shift_data['endshift'];
        $is_night_shift = (strtotime($startshift) > strtotime($endshift));
    }

    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    
    // Simplified query for GY shift - just get previous day's data
    if ($is_night_shift) {
        $query = "SELECT * FROM attendance 
                 WHERE idno='$idno' AND logindate = '$yesterday'
                 ORDER BY 
                     CASE 
                         WHEN loginam IS NOT NULL THEN TIME(loginam)
                         WHEN loginpm IS NOT NULL THEN TIME(loginpm)
                         ELSE '00:00:00'
                     END";
    } else {
        // Regular shift - get today's data
        $query = "SELECT * FROM attendance 
                 WHERE idno='$idno' AND logindate = '$today'
                 ORDER BY 
                     CASE 
                         WHEN loginam IS NOT NULL THEN TIME(loginam)
                         WHEN loginpm IS NOT NULL THEN TIME(loginpm)
                         ELSE '00:00:00'
                     END";
    }
    
    // Execute query
    $sqlAttendance = mysqli_query($con, $query);
    
    if (!$sqlAttendance) {
        // If query fails, try a more basic query as fallback
        $query = "SELECT * FROM attendance 
                 WHERE idno='$idno' AND logindate = '".($is_night_shift ? $yesterday : $today)."' 
                 ORDER BY logindate DESC LIMIT 1";
        $sqlAttendance = mysqli_query($con, $query);
    }
    
    $num_rows = $sqlAttendance ? mysqli_num_rows($sqlAttendance) : 0;
    ?>
    
    <div class="attendance-table-container">
        <table class="attendance-table">
            <thead>
                <tr>
                    <th width="15%">Date</th>
                    <th width="17%">Time In</th>
                    <th width="17%">Lunch Out</th>
                    <th width="17%">Lunch In</th>
                    <th width="17%">Time Out</th>
                    <th width="17%">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($num_rows > 0) {
                    while ($attend = mysqli_fetch_assoc($sqlAttendance)) {
                        $is_previous_day = ($attend['logindate'] == $yesterday);
                        $row_color = $is_previous_day ? "style='background-color: #f8f9fa;'" : "";
                        
                        echo "<tr $row_color>";
                            echo "<td>" . date('M j, Y', strtotime($attend['logindate'])) . "</td>";
                            echo "<td>" . formatTimeDisplay($attend['loginam']) . "</td>";
                            echo "<td>" . formatTimeDisplay($attend['logoutam']) . "</td>";
                            echo "<td>" . formatTimeDisplay($attend['loginpm']) . "</td>";
                            echo "<td>" . formatTimeDisplay($attend['logoutpm']) . "</td>";
                            echo "<td>" . htmlspecialchars($attend['remarks'] ?? '-') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' align='center'>No attendance data found for " . ($is_night_shift ? "yesterday" : "today") . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="text-right mt-10">
        <a href="?attendance" style="background:#70C3FF;" class="btn btn-sm btn-default">View Full Attendance</a>
    </div>
</div>

<?php
// Helper function to format time display
function formatTimeDisplay($time) {
    if (empty($time) || $time == '00:00:00') {
        return '-';
    }
    try {
        return date('h:i A', strtotime($time));
    } catch (Exception $e) {
        return '-';
    }
}
?>
            <!-- Workload Section -->
          <!-- In the right column (col-md-8), replace the Workload section with this: -->
          <div class="service-card mt-20">
    <div class="row">
        <!-- Leave Credits (Bigger Section - Takes 8 columns) -->
        <div class="col-md-8">
            <h4 class="section-title">Leave Credits</h4>
            <table class="leave-table table-bordered">
                <tr>
                    <td class="leave-type"><strong>Vacation Leave:</strong></td>
                    <td class="leave-details">
                        <span class="credit">Credit: <?=$vl;?></span>
                        <span class="used">Used: <?=$vlused;?></span>
                        <span class="available">Available: <?=$vlrem;?></span>
                    </td>
                </tr>
                <tr>
                    <td class="leave-type"><strong>Sick Leave:</strong></td>
                    <td class="leave-details">
                        <span class="credit">Credit: <?=$sl;?></span>
                        <span class="used">Used: <?=$slused;?></span>
                        <span class="available">Available: <?=$slrem;?></span>
                    </td>
                </tr>
                <tr>
                    <td class="leave-type"><strong>PTO Credits:</strong></td>
                    <td class="leave-details">
                        <span class="credit">Credit: <?=$pto;?></span>
                        <span class="used">Used: <?=$ptoused;?></span>
                        <span class="available">Available: <?=$ptorem;?></span>
                    </td>
                </tr>
                <tr>
                    <td class="leave-type">
                        <strong>EO Credits:</strong>
                        <select id="monthSelect" class="month-select" onchange="updateEOCredits()">
                            <?php
                            $currentMonth = strtolower(date("M"));
                            $months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
                            foreach ($months as $month) {
                                $selected = ($currentMonth == $month) ? "selected" : "";
                                echo "<option value='$month' $selected>" . ucfirst($month) . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td class="leave-details">
                        <span class="credit">Credit: <span id="eoCredits"><?=$jan_earlyout;?></span></span>
                        <span class="used">Used: <span id="eoUsed"><?=$jan_eo_used;?></span></span>
                        <span class="available">Available: <span id="eoRemaining"><?=$jan_eorem;?></span></span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Length of Service and Referral (Smaller Sections - Each takes 2 columns) -->
        <div class="col-md-2">
            <h4 class="section-title">Length of Service</h4>
            <div class="service-details">
                <div class="service-item">
                    <span class="service-label">Years:</span>
                    <span class="service-value"><?=$years;?></span>
                </div>
                <div class="service-item">
                    <span class="service-label">Months:</span>
                    <span class="service-value"><?=$monthz;?></span>
                </div>
                <div class="service-item">
                    <span class="service-label">Days:</span>
                    <span class="service-value"><?=$days;?></span>
                </div>
                <div class="service-item">
                    <span class="service-label">Period From:</span>
                    <span class="service-value"><?=date('M d, Y', strtotime($periodfrom));?></span>
                </div>
                <div class="service-item">
                    <span class="service-label">Period Through:</span>
                    <span class="service-value"><?=date('M d, Y', strtotime($periodto));?></span>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <h4 class="section-title">Referral Info</h4>
            <div class="referral-details">
                <div class="referral-item">
                    <span class="referral-label">Referred By:</span>
                    <span class="referral-value"><?=$referredby;?></span>
                </div>
                <div class="referral-item">
                    <span class="referral-label">Effectivity:</span>
                    <span class="referral-value"><?=$effectivity;?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    
    .attendance-card
    {
        margin-bottom:10px;
    }
    .mt {
	margin-top: 0px;
}
    /* Attendance Card Styles */
.attendance-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 20px;
}

.attendance-card h4 {
    color: #333;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 1px solid #eee;
}

.attendance-table-container {
    overflow-x: auto;
}

.attendance-table {
    width: 100%;
    border-collapse: collapse;
}

.attendance-table th {
    background-color: #f8f9fa;
    padding: 10px;
    text-align: left;
    border-bottom: 2px solid #dee2e6;
}

.attendance-table td {
    padding: 10px;
    border-bottom: 1px solid #dee2e6;
}

.attendance-table tr:last-child td {
    border-bottom: none;
}

.text-right {
    text-align: right;
}

.mt-10 {
    margin-top: 10px;
}
    /* Custom Styling */
    .section-title {
        font-size: 1.2rem;
        margin-bottom: 15px;
        color: #333;
    }
    
    .leave-table {
        width: 100%;
        font-size: 1.1rem;
    }
    
    .leave-table td {
        padding: 10px;
    }
    
    .leave-type {
        width: 30%;
        font-weight: bold;
        font-size:12px;
    }
    
    
    .leave-details {
    display: flex;
    justify-content: space-between;
    text-align: center;
    font-size:12px;
}

.leave-details span {
    flex: 1;
    padding: 5px;
    border-right: 1px solid #f4f4f4;
}

.leave-details span:last-child {
    border-right: none;
}
    
    .credit { color: #28a745; }
    .used { color: #dc3545; }
    .available { color: #17a2b8; }
    
    .service-details, .referral-details {
        font-size: 0.9rem;
    }
    
    .service-item, .referral-item {
        margin-bottom: 8px;
    }
    
    .month-select {
        padding: 3px;
        margin-left: 5px;
        font-size: 0.9rem;
    }
</style>
<script>
// Auto-submit cover photo form when file is selected
document.getElementById('cover_photo').addEventListener('change', function() {
    // Show loading indicator
    const btn = document.querySelector('.cover-upload-btn');
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Uploading...';
    
    // Submit form
    document.getElementById('coverForm').submit();
});

// For profile picture (if needed)
document.getElementById('profile_pic')?.addEventListener('change', function() {
    this.form.submit();
});
</script>

<style>
     /* Cover Photo Styles */
     .profile-card-with-cover {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    
    .cover-photo {
        height: 120px;
        position: relative;
        background-size: cover;
        background-position: center;
    }
    
    .cover-upload-btn {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(255,255,255,0.8);
        color: #333;
    }
    
    /* Profile picture positioning */
    .profile-pic-container {
        margin-top: -80px;
       
    }
    /* Service and Referral Card Styles */
    .service-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom:10px;
    }
    
    .service-card h4 {
        color: #333;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 1px solid #eee;
    }
    
    /* Service Details */
    .service-details {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .service-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f5f5f5;
        font-size:11px;
    }
    
    .service-label {
        color: #666;
        font-weight: 500;
    }
    
    .service-value {
        color: #333;
        font-weight: 600;
    }
    
    /* Referral Details */
    .referral-details {
        display: flex;
        flex-direction: column;
        gap: 10px;
        font-size:11px;
    }
    
    .referral-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .referral-label {
        color: #666;
        font-weight: 500;
    }
    
    .referral-value {
        color: #333;
        font-weight: 600;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .service-card .row > div {
            width: 100%;
        }
        
        .service-card .col-md-6 {
            margin-bottom: 20px;
        }
    }
</style>
            <!-- Leave Credits -->
         
            <!-- Recent Activity -->
           <!-- Replace the Recent Activity section with this Points Breakdown section -->
<div class="points-card mt-20">
    <h4>Points Breakdown <small>(Current Year: <?=date('Y');?>)</small></h4>
    <div class="points-breakdown">
        <?php if (!empty($breakdown_by_month)): ?>
            <div class="points-scroll-container">
                <?php foreach ($breakdown_by_month as $month => $offenses): ?>
                    <div class="month-section">
                        <h5><?=$month;?></h5>
                        <table class="points-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Points</th>
                                    <th>Offense</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($offenses as $offense): ?>
                                    <tr>
                                        <td><?=date("F d", strtotime($offense['logindate']));?></td>
                                        <td class="points-value"><?=number_format($offense['points'], 1);?></td>
                                        <td><?=htmlspecialchars($offense['offense']);?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-points">
                <i class="fa fa-check-circle"></i>
                <p>No points recorded for <?=date('Y');?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="announcements-container mt-20">
    <h4>Announcements</h4>
    
    <?php
    // Get all announcements (not just today's)
    $sqlWidgets = mysqli_query($con, "
        SELECT w.*
        FROM widgets w
        WHERE w.type='Announcement'
        ORDER BY w.datearray DESC, w.timearray DESC
    ");

    $filteredAnnouncements = [];
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
        
        if($isForUser) {
            $filteredAnnouncements[] = [
                'id' => $emp['id'],
                'title' => $emp['title'],
                'details' => $emp['details'],
                'date' => $emp['datearray'],
                'time' => $emp['timearray']
            ];
        }
    }
    
    $hasAnnouncements = !empty($filteredAnnouncements);
    ?>
    
    <?php if ($hasAnnouncements): ?>
        <div class="announcements-table-container">
            <table class="announcements-table">
                <thead>
                    <tr>
                        <th width="25%">Date</th>
                        <th width="30%">Title</th>
                        <th width="40%">Preview</th>
                        <th width="5%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($filteredAnnouncements as $announcement): ?>
                        <tr class="announcement-row" data-details="<?= htmlspecialchars($announcement['details']) ?>">
                            <td><?= date("M d, Y", strtotime($announcement['date'])) ?></td>
                            <td><strong><?= htmlspecialchars($announcement['title']) ?></strong></td>
                            <td><?= substr(htmlspecialchars($announcement['details']), 0, 100) ?>...</td>
                            <td><i class="fa fa-chevron-right"></i></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="no-announcements">
            <i class="fa fa-check-circle"></i>
            <p>No announcements available for your department</p>
        </div>
    <?php endif; ?>
</div>

<!-- Announcement Zoom Modal -->
<div id="announcementModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="modal-header" style="background:#22242a;">
            <h3 id="modalTitle" style="color:#f4f4f4;"></h3>
            <div id="modalDate" class="text-muted" style="color:white;"></div>
        </div>
        <div id="modalContent" class="modal-body"></div>
    </div>
</div>

<style>
    .col-md-4 {
        margin-bottom:10px;
    }
    .announcements-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-top:10px;
    }
    
    .announcements-table-container {
        max-height: 500px;
        overflow-y: auto;
        border: 1px solid #eee;
        border-radius: 4px;
    }
    
    .announcements-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .announcements-table th {
        background: #f8f9fa;
        padding: 12px 15px;
        text-align: left;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .announcements-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }
    
    .announcement-row:hover {
        background-color: #f5f5f5;
        cursor: pointer;
    }
    
    .no-announcements {
        text-align: center;
        padding: 30px;
        color: #6c757d;
    }
    
    .no-announcements i {
        font-size: 40px;
        color: #28a745;
        margin-bottom: 15px;
    }
    
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 100;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.7);
    }
    
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 25px;
        border-radius: 8px;
        width: 70%;
        max-width: 800px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    
    .modal-header {
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    
    .modal-body {
        line-height: 1.6;
        white-space: pre-line;
    }
    
    .close-modal {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .close-modal:hover {
        color: #333;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('announcementModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalDate = document.getElementById('modalDate');
    const modalContent = document.getElementById('modalContent');
    const closeBtn = document.querySelector('.close-modal');
    
    // Add click event to all announcement rows
    document.querySelectorAll('.announcement-row').forEach(row => {
        row.addEventListener('click', function() {
            const date = this.querySelector('td:first-child').textContent;
            const title = this.querySelector('td:nth-child(2)').textContent;
            const details = this.getAttribute('data-details');
            
            modalTitle.textContent = title;
            modalDate.textContent = date;
            modalContent.textContent = details;
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
    });
    
    // Close modal when X is clicked
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
});
</script>

<style>
    /* Points Breakdown Styles */
    .points-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 20px;
    }
    
    .points-card h4 {
        margin-bottom: 15px;
        color: #333;
    }
    
    .points-card h4 small {
        color: #888;
        font-size: 14px;
    }
    
    .points-scroll-container {
        max-height: 300px;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    .month-section {
        margin-bottom: 20px;
    }
    
    .month-section h5 {
        color: #20283a;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px solid #eee;
    }
    
    .points-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }
    
    .points-table th {
        background: #f5f5f5;
        padding: 8px;
        text-align: left;
        font-weight: 600;
        color: #555;
    }
    
    .points-table td {
        padding: 8px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .points-table tr:hover {
        background-color: #f9f9f9;
    }
    
    .points-value {
        color: #e74c3c;
        font-weight: bold;
    }
    
    .no-points {
        text-align: center;
        padding: 20px;
        color: #20283a;
    }
    
    .no-points i {
        font-size: 40px;
        margin-bottom: 10px;
    }
    
    /* Scrollbar styling */
    .points-scroll-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .points-scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .points-scroll-container::-webkit-scrollbar-thumb {
        background: #20283a;
        border-radius: 3px;
    }
</style>

<script>
    // You can keep the same modal JavaScript from before
    document.addEventListener("DOMContentLoaded", function () {
        const pointsContainer = document.getElementById('points-container');
        const breakdownModal = document.getElementById('breakdown-modal');
        const closeModal = document.getElementById('close-modal');

        if (pointsContainer) {
            pointsContainer.addEventListener('click', function () {
                breakdownModal.style.display = 'block';
            });
        }

        closeModal.addEventListener('click', function () {
            breakdownModal.style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            if (event.target === breakdownModal) {
                breakdownModal.style.display = 'none';
            }
        });
    });
</script>
        </div>
    </div>
</div>
<script>
document.getElementById('cover_photo').addEventListener('change', function () {
    document.getElementById('coverForm').submit();
});
</script>

<style>
    /* Alignment Fixes */
    .profile-card-with-cover {
        height: 100%; /* Make it fill the column */
        display: flex;
        flex-direction: column;
    }
    
    .profile-card {
        flex-grow: 1; /* Takes remaining space */
        display: flex;
        flex-direction: column;
    }
    
    /* Ensure right and left panels align */
    .content-panel > .row {
        align-items: flex-start; /* Align tops of columns */
    }
    
    /* Cover photo adjustments */
    .cover-photo {
        height: 150px; /* Slightly larger */
        position: relative;
    }
    
    /* Profile picture positioning */
    .profile-pic-container {
        margin-top: -75px; /* Adjusted for new cover height */
    }
</style>
<style>
    /* Main Card Styles */
    .profile-card, .skills-card, .metric-card, 
    .workload-card, .leave-card, .activity-card,
    .ids-card, .status-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 20px;
       
    }
    
    /* Profile Picture */
    .profile-pic-container {
        position: relative;
        display: inline-block;
        margin-bottom: 15px;
    }
    
    .profile-pic-container img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #e0e0e0;
    }
    
    .camera-icon {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #20283a;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    
    /* Profile Header */
    .profile-header h3 {
        margin: 10px 0 5px;
        color: #333;
    }
    
    .title {
        color: #666;
        font-size: 16px;
        margin-bottom: 5px;
    }
    
    .company {
        color: #888;
        font-style: italic;
        margin-bottom: 15px;
    }
    
    /* Stats */
    .stats {
        display: flex;
        justify-content: space-around;
        margin: 20px 0;
        padding: 15px 0;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        display: block;
        font-size: 24px;
        font-weight: bold;
        color: #20283a;
    }
    
    .stat-label {
        font-size: 12px;
        color: #888;
        text-transform: uppercase;
    }
    
    /* Profile Tabs */
    .profile-tabs {
        margin-top: 15px;
    }
    
    .nav-tabs {
        border-bottom: 1px solid #ddd;
    }
    
    .nav-tabs>li>a {
        padding: 8px 12px;
        color: #666;
    }
    
    .nav-tabs>li.active>a {
        color: #20283a;
        font-weight: bold;
    }
    
    /* Detail Items */
    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .detail-item i {
        color: #20283a;
        width: 20px;
    }
    
    .detail-item .value {
        color: #333;
        font-weight: 500;
        text-align: right;
        flex: 1;
    }
    
    /* Status Cards */
    .status-card {
        text-align: center;
        padding: 15px;
        height: 100%;
    }
    
    .status-card i {
        font-size: 30px;
        margin-bottom: 10px;
        display: block;
    }
    
    .status-title {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .status-date {
        color: #666;
    }
    
    .probationary i { color: #FF9800; }
    .regular i { color: #4CAF50; }
    .fulltime i { color: #2196F3; }
    
    /* Metric Cards */
    .metric-card {
        text-align: center;
        padding: 15px;
    }
    
    .metric-number {
        display: block;
        font-size: 28px;
        font-weight: bold;
        color: #20283a;
    }
    
    .metric-label {
        font-size: 12px;
        color: #888;
        text-transform: uppercase;
    }
    
    /* Workload */
    .progress-container {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }
    
    .progress {
        height: 10px;
        border-radius: 5px;
        margin-right: 10px;
    }
    
    /* Activity */
    .activity-item {
        padding: 10px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .activity-time {
        font-size: 12px;
        color: #888;
    }
    
    .activity-text {
        margin-top: 3px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .col-md-4, .col-md-8 {
            width: 100%;
        }
        
        .stats {
            flex-direction: column;
            gap: 15px;
        }
        
        .status-card {
            margin-bottom: 15px;
        }
    }
</style>

<script>
    function updateEOCredits() {
        // AJAX implementation would go here
        console.log("Month changed - update EO credits");
    }
    
    // Initialize tabs
    $(document).ready(function(){
        $('.nav-tabs a').click(function(e){
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>

<script>
    function updateEOCredits() {
        // AJAX implementation would go here
        console.log("Month changed - update EO credits");
    }
</script>



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
                    $image = "../Employees/default_image.png"; // Default image if no profile pic exists
                }
            }
            
            //Handle Delete 
            if (isset($_POST['delete'])) {
                $idno = $_POST['idno']; // User's ID
                $target_dir = "../Employees/";
            
                // Get the file extensions to check for different formats
                $file_exts = array("jpg", "jpeg", "png");
            
                foreach ($file_exts as $ext) {
                    $file_path = $target_dir . $idno . "." . $ext;
            
                    // Check if the file exists, then delete it
                    if (file_exists($file_path)) {
                        if (unlink($file_path)) {
                            $message = "Profile picture deleted successfully.";
                        } else {
                            $error = "Error deleting the file.";
                        }
                    }
                }
              
                // Optionally, set a default image if none exists
                $image = "../Employees/default_image.png"; // Path to the default image
            }  
            // Initialize cover_photo_url
            $cover_photo_url = "";
            
            if (isset($_POST['upload_cover'])) {
                $idno = $_POST['idno'];
                $target_dir = __DIR__ . "/../covers/"; // Make sure this is correct
            
            
                if (!empty($_FILES['cover_photo']['name'])) {
                    $file_ext = strtolower(pathinfo($_FILES['cover_photo']['name'], PATHINFO_EXTENSION));
                    $target_file = $target_dir . $idno . "." . $file_ext;
                    $valid_extensions = ["jpg", "png", "jpeg"];
            
                    if (in_array($file_ext, $valid_extensions)) {
                        if (is_uploaded_file($_FILES['cover_photo']['tmp_name'])) {
                            // Delete existing cover photos
                            array_map('unlink', glob($target_dir . $idno . ".*"));
            
                            if (move_uploaded_file($_FILES["cover_photo"]["tmp_name"], $target_file)) {
                                $cover_photo_url = "/hris/covers/".$idno.".jpg";
                                echo "<script>alert('Cover photo uploaded successfully!');</script>";
                            } else {
                                echo "<script>alert('Error moving cover photo.');</script>";
                            }
                        } else {
                            echo "<script>alert('Invalid file upload attempt.');</script>";
                        }
                    } else {
                        echo "<script>alert('Only JPG, JPEG, and PNG files are allowed.');</script>";
                    }
                } else {
                    echo "<script>alert('Please select a cover photo to upload.');</script>";
                }
            }
            
            // Check for existing cover photo if none was just uploaded
            if (empty($cover_photo_url)) {
                $idno = $_POST['idno'] ?? $idno ?? ''; // Make sure idno is available
                if ($idno) {
                    $cover_path = __DIR__ . '/../covers/';
                    $existing_covers = glob($cover_path . $idno . ".*");
                    
                    if (!empty($existing_covers)) {
                        $ext = pathinfo($existing_covers[0], PATHINFO_EXTENSION);
                        $cover_photo_url = '/../covers/' . $idno . "." . $ext;
                    } else {
                        $cover_photo_url = "/hris/Employees/default_image.png"; // Default image
                    }
                }
            }
             ?>
             
            
        </div>



       
          <script>
document.addEventListener("DOMContentLoaded", function () {
    updateEOCredits(); // Load data for the default month on page load
});

function updateEOCredits() {
    var selectedMonth = document.getElementById("monthSelect").value;

    // Fetch data dynamically using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_eo_credits.php?month=" + selectedMonth, true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            var data = JSON.parse(xhr.responseText);
            document.getElementById("eoCredits").innerText = data.credits;
            document.getElementById("eoUsed").innerText = data.used;
            document.getElementById("eoRemaining").innerText = data.remaining;
        }
    };
    xhr.send();
}
// JavaScript function to update the values based on the selected month
function updateEOCredits() {
    var selectedMonth = document.getElementById('monthSelect').value;

    // Define the month values for earlyout, used, and remaining
    var eoCredits, eoUsed, eoRemaining;

    switch(selectedMonth) {
        case "jan":
            eoCredits = <?=$jan_earlyout;?>;
            eoUsed = <?=$jan_eo_used;?>;
            eoRemaining = <?=$jan_eorem;?>;
            break;
        case "feb":
            eoCredits = <?=$feb_earlyout;?>;
            eoUsed = <?=$feb_eo_used;?>;
            eoRemaining = <?=$feb_eorem;?>;
            break;
        case "mar":
            eoCredits = <?=$mar_earlyout;?>;
            eoUsed = <?=$mar_eo_used;?>;
            eoRemaining = <?=$mar_eorem;?>;
            break;
        case "apr":
            eoCredits = <?=$apr_earlyout;?>;
            eoUsed = <?=$apr_eo_used;?>;
            eoRemaining = <?=$apr_eorem;?>;
            break;
        case "may":
            eoCredits = <?=$may_earlyout;?>;
            eoUsed = <?=$may_eo_used;?>;
            eoRemaining = <?=$may_eorem;?>;
            break;
        case "jun":
            eoCredits = <?=$jun_earlyout;?>;
            eoUsed = <?=$jun_eo_used;?>;
            eoRemaining = <?=$jun_eorem;?>;
            break;
        case "jul":
            eoCredits = <?=$jul_earlyout;?>;
            eoUsed = <?=$jul_eo_used;?>;
            eoRemaining = <?=$jul_eorem;?>;
            break;
        case "aug":
            eoCredits = <?=$aug_earlyout;?>;
            eoUsed = <?=$aug_eo_used;?>;
            eoRemaining = <?=$aug_eorem;?>;
            break;
        case "sep":
            eoCredits = <?=$sep_earlyout;?>;
            eoUsed = <?=$sep_eo_used;?>;
            eoRemaining = <?=$sep_eorem;?>;
            break;
        case "oct":
            eoCredits = <?=$oct_earlyout;?>;
            eoUsed = <?=$oct_eo_used;?>;
            eoRemaining = <?=$oct_eorem;?>;
            break;
        case "nov":
            eoCredits = <?=$nov_earlyout;?>;
            eoUsed = <?=$nov_eo_used;?>;
            eoRemaining = <?=$nov_eorem;?>;
            break;
        case "dec":
            eoCredits = <?=$dec_earlyout;?>;
            eoUsed = <?=$dec_eo_used;?>;
            eoRemaining = <?=$dec_eorem;?>;
            break;
        // Add other months here if needed
        default:
    }

    // Update the table with the selected month's values
    document.getElementById('eoCredits').innerText = eoCredits;
    document.getElementById('eoUsed').innerText = eoUsed;
    document.getElementById('eoRemaining').innerText = eoRemaining;
}
</script>
