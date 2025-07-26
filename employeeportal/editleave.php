<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['idno'])) {
        die("<script>alert('Session expired. Please log in again.'); window.location='/index.php';</script>");
    }

    $userId = $_SESSION['idno'];
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $leaveId = $_GET['id'];
        $sqlLeaveDetails = mysqli_query($con, "SELECT * FROM leave_application WHERE id='$leaveId'");
        if ($sqlLeaveDetails && mysqli_num_rows($sqlLeaveDetails) > 0) {
            $leaveDetails = mysqli_fetch_array($sqlLeaveDetails);
            $leavetype = $leaveDetails['leavetype'];
            $eoMonth = $leaveDetails['eo_month'];
            $numberofdays = $leaveDetails['numberofdays'];
            $dayfrom = $leaveDetails['dayfrom'];
            $dayto = $leaveDetails['dayto'];
            $reason = $leaveDetails['reason'];
            $leaveId = $leaveDetails['id'];
            $datearray = $leaveDetails['datearray'];
        } else {
            echo "<script>alert('Leave application not found!');</script>";
            echo "<script>window.location='manageleave.php';</script>";
            return;
        }
    } else {
        echo "<script>alert('Leave ID not provided!');</script>";
        echo "<script>window.location='manageleave.php';</script>";
        return;
    }
    
    $sqlCredits = mysqli_query($con, "SELECT * FROM leave_credits WHERE idno='$userId'");
    $credits = [];
    if (mysqli_num_rows($sqlCredits) > 0) {
        $credit = mysqli_fetch_array($sqlCredits);
        $credits['VL'] = $credit['vacationleave'] - $credit['vlused'];
        $credits['SL'] = $credit['sickleave'] - $credit['slused'];
        $credits['PTO'] = ($credit['pto'] - $credit['ptoused']);
        $credits['BLP'] = $credit['bdayleave'] - $credit['blp_used'];
        $credits['jan_EO'] = $credit['jan_earlyout'] - $credit['jan_eo_used'];
        $credits['feb_EO'] = $credit['feb_earlyout'] - $credit['feb_eo_used'];
        $credits['mar_EO'] = $credit['mar_earlyout'] - $credit['mar_eo_used'];
        $credits['apr_EO'] = $credit['apr_earlyout'] - $credit['apr_eo_used'];
        $credits['may_EO'] = $credit['may_earlyout'] - $credit['may_eo_used'];
        $credits['jun_EO'] = $credit['jun_earlyout'] - $credit['jun_eo_used'];
        $credits['jul_EO'] = $credit['jul_earlyout'] - $credit['jul_eo_used'];
        $credits['aug_EO'] = $credit['aug_earlyout'] - $credit['aug_eo_used'];
        $credits['sep_EO'] = $credit['sep_earlyout'] - $credit['sep_eo_used'];
        $credits['oct_EO'] = $credit['oct_earlyout'] - $credit['oct_eo_used'];
        $credits['nov_EO'] = $credit['nov_earlyout'] - $credit['nov_eo_used'];
        $credits['dec_EO'] = $credit['dec_earlyout'] - $credit['dec_eo_used'];
        $credits['SPL'] = $credit['spl'] - $credit['spl_used'];
    }
    
    $monthMap = [
        'January' => 'jan',
        'February' => 'feb',
        'March' => 'mar',
        'April' => 'apr',
        'May' => 'may',
        'June' => 'jun',
        'July' => 'jul',
        'August' => 'aug',
        'September' => 'sep',
        'October' => 'oct',
        'November' => 'nov',
        'December' => 'dec'
    ];
    // Temporarily restore leave credits used in the current application
    if (!empty($leavetype) && !empty($numberofdays)) {
        if ($leavetype === 'EO' && !empty($eoMonth)) {
            if (isset($credits[$eoMonth])) {
                $credits[$eoMonth] += $numberofdays;
            }
        } else {
            if (isset($credits[$leavetype])) {
                $credits[$leavetype] += $numberofdays;
            }
        }
    }

    // Fetch user birthdate
    $sqlBirthDate = mysqli_query($con, "SELECT birthdate FROM employee_profile WHERE idno='$userId'");
    if (mysqli_num_rows($sqlBirthDate) > 0) {
        $birthDate = mysqli_fetch_assoc($sqlBirthDate)['birthdate'];
        $birthMonth = date('m', strtotime($birthDate)); // Extract month of birth
    } else {
        $birthMonth = null; // Handle case if birthdate is not found
    }
    
    //Fetch user start shift
    $sqlStartShift = mysqli_query($con, "SELECT startshift FROM employee_details WHERE idno='$userId'");
    if(mysqli_num_rows($sqlStartShift)>0){
        $startshift=mysqli_fetch_assoc($sqlStartShift)['startshift'];
    }
?>
<style>
    body{
        background-color: #f0f2f5
    }
    .centered-container {
        display: flex;
        justify-content: center;
        padding: 20px;
    }

    .content-panel {
        background-color: #fff;
        border-radius: 30px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px;
        overflow: hidden;
        padding-top: 0px;
    }

    .panel-heading {
        background-color: #21283a;
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .panel-heading h4 {
        margin: 0;
        font-weight: bold;
        flex-grow: 1;
        text-align: center;
    }

    .panel-body {
        padding: 20px;
    }

    .panel-footer {
        padding: 15px 20px;
        text-align: center;
        border-top: none;
        background-color: #fff;
    }

    .form-label {
        font-weight: bold;
    }

    .form-control {
        border: none;
        border-bottom: 1px solid #ccc; /* Adjust color as needed */
        border-radius: 0;
        background-color: transparent; /* This lets the group-box background show through */
        color: inherit;
        box-shadow: none; /* Removes Bootstrap focus shadow */
    }
    
    .form-control:focus {
        outline: none;
        border-bottom-color: #007bff; /* Optional: Change on focus */
        box-shadow: none;
    }
    .form-group {
        border-bottom: none !important;
    }
    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        position: relative;
        right: 3px;
    }

    .custom-select-wrapper {
        position: relative;
        width: 100%;
    }

    .custom-select {
        width: 100%;
        appearance: none;           /* Hide default arrow (WebKit) */
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 30px;        /* Leave space for custom icon */
    }

    .custom-dropdown-icon {
        font-size: 25px;
        position: absolute;
        top: 50%;
        right: 15px;                /* Adjust this to control position */
        transform: translateY(-50%);
        pointer-events: none;       /* Allows click through to select */
    }
</style>
<div class="centered-container">
    <form class="form-horizontal" method="GET" onsubmit="return validateForm(this);" style="width: 100%; max-width: 500px;">
        <input type="hidden" name="editleave">            
        <input type="hidden" name="addedby" value="<?=$fullname;?>">  
        <input type="hidden" name="id" value="<?=$leaveId;?>">    
        <input type="hidden" name="datearray" value="<?=$datearray;?>">
        
        <div class="content-panel">
            <div class="panel-heading d-flex align-items-center justify-content-between" style="position: relative;">
                <h4 class="mb-0 mx-auto text-center" style="flex: 1;">EDIT FILED LEAVE</h4>
            </div>
            <div class="panel-body"> 
                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                    <label class="form-label">Leave Type</label>
                    <div class="custom-select-wrapper">
                        <select id="leaveTypeSelect" name="leavetype" class="form-control custom-select" required style="cursor: pointer;" onchange="toggleEOSelection(this)">
                            <option value="" selected>Select Leave Type</option>
                            <option value="VL" <?= ($leavetype == 'VL') ? 'selected' : ''; ?>>Vacation Leave (VL)</option>
                            <option value="PTO" <?= ($leavetype == 'PTO') ? 'selected' : ''; ?>>Unpaid Leave (PTO)</option>
                            <option value="SPL" <?= ($leavetype == 'SPL') ? 'selected' : ''; ?>>Solo Parent Leave (SPL)</option>
                            <option value="BLP" <?= ($leavetype == 'BLP') ? 'selected' : ''; ?>>Birthday Leave (BLP)</option>
                            <option value="EO" <?= ($leavetype == 'EO') ? 'selected' : ''; ?>>Early Out (EO)</option>
                            <option value="MTL" <?= ($leavetype == 'MTL') ? 'selected' : ''; ?>>Maternity Leave (MTL)</option>
                            <option value="PTL" <?= ($leavetype == 'PTL') ? 'selected' : ''; ?>>Paternity Leave (PTL)</option>
                            <option value="LTL" <?= ($leavetype == 'LTL') ? 'selected' : ''; ?>>Long Term Leave (LTL)</option>
                            <option value="MDL" <?= ($leavetype == 'MDL') ? 'selected' : ''; ?>>Medical Leave (MDL)</option>
                            <option value="BL" <?= ($leavetype == 'BL') ? 'selected' : ''; ?>>Bereavement Leave (BL)</option>
                        </select>
                        <span class="custom-dropdown-icon">&#9662;</span>
                    </div>
                    <small id="credit-info" class="form-text text-muted"></small>
                </div>
                <!-- Additional Selection for EO Month -->
                <div class="form-group mb-3" id="eo-month-group" style="display: none; margin: 10px; margin-bottom: 30px;">
                    <label class="form-label">Select Month</label>
                    <div class="custom-select-wrapper">
                        <?php
                            $fetchedEO_Month = isset($fetchedLeaveData['eo_month']) ? $fetchedLeaveData['eo_month'] : ''; 
                        ?>
                        <select name="eo_month"  data-fetched-month="<?= $fetchedEO_Month ?>" class="form-control custom-select" required style="cursor: pointer;" onchange="updateCredits(this.value)">
                            <option value="" selected>Select Month</option>
                            <option value="jan_EO" <?= ($eoMonth == 'jan_EO') ? 'selected' : ''; ?>>January</option>
                            <option value="feb_EO" <?= ($eoMonth == 'feb_EO') ? 'selected' : ''; ?>>February</option>
                            <option value="mar_EO" <?= ($eoMonth == 'mar_EO') ? 'selected' : ''; ?>>March</option>
                            <option value="apr_EO" <?= ($eoMonth == 'apr_EO') ? 'selected' : ''; ?>>April</option>
                            <option value="may_EO" <?= ($eoMonth == 'may_EO') ? 'selected' : ''; ?>>May</option>
                            <option value="jun_EO" <?= ($eoMonth == 'jun_EO') ? 'selected' : ''; ?>>June</option>
                            <option value="jul_EO" <?= ($eoMonth == 'jul_EO') ? 'selected' : ''; ?>>July</option>
                            <option value="aug_EO" <?= ($eoMonth == 'aug_EO') ? 'selected' : ''; ?>>August</option>
                            <option value="sep_EO" <?= ($eoMonth == 'sep_EO') ? 'selected' : ''; ?>>September</option>
                            <option value="oct_EO" <?= ($eoMonth == 'oct_EO') ? 'selected' : ''; ?>>October</option>
                            <option value="nov_EO" <?= ($eoMonth == 'nov_EO') ? 'selected' : ''; ?>>November</option>
                            <option value="dec_EO" <?= ($eoMonth == 'dec_EO') ? 'selected' : ''; ?>>December</option>
                        </select>
                        <span class="custom-dropdown-icon">&#9662;</span>
                    </div>
                </div>
                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                    <label class="form-label">No. of Days</label>
                    <input type="number" name="nofdays" id="nofdays" class="form-control" min="1" value="<?=$numberofdays;?>" required onchange="checkCredits();">
                </div>
                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="startDate" class="form-control" value="<?=$dayfrom;?>" required onchange="checkCredits();">
                    <span id="date-warning" style="color: red; display: none;">*You must file for leave at least 3 days in advance.</span>
                </div>
                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                    <label class="form-label">End Date</label>
                    <input type="date" name="endDate" class="form-control" value="<?=$dayto;?>" required onchange="checkCredits();">
                    <span id="end-date-warning" style="color: red; display: none;"></span>
                </div>
                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                    <label class="form-label">Reason(s)</label>
                    <textarea name="reasons" class="form-control" required><?=$reason;?></textarea>
                </div>
                <div class="text-center" style="margin: 10px; margin-top: 70px;">
                    <!-- Cancel Button (no form submit) -->
                    <button type="button" class="btn btn-danger" style="width: 80px; border-radius: 20px; height: 40px;" onclick="confirmCancel()">
                        Cancel
                    </button>
                
                    <!-- Submit Button (form submit) -->
                    <input type="submit" id="submitBtn" name="submit" class="btn btn-success" value="Save Changes"
                        style="width: 200px; border-radius: 20px; height: 40px;">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
    if (isset($_GET['submit']) && isset($_GET['editleave'])) {
        $leaveId = $_GET['id']; // Get the ID from the hidden input
        $idno = $_SESSION['idno'];
        $leavetype = $_GET['leavetype'] ?? '';
        $eoMonth = ($leavetype === 'EO') ? $_GET['eo_month'] : 'NULL';
        $nofdays = $_GET['nofdays'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'] ?? '';   
        $reasons = isset($_GET['reasons']) ? urldecode($_GET['reasons']) : ''; // Decode the input
        $reasons = mysqli_real_escape_string($con, $reasons); // Sanitize for SQL      
        $datenow = $_GET['datearray']; 
        $editDateTime = date('Y-m-d H:i:s');
        $startMonth = date('n', strtotime($startDate));
        // First check if the leave already exists
        $sqlCheck = mysqli_query($con, "SELECT * FROM leave_application 
                                       WHERE idno='$idno' 
                                       AND leavetype='$leavetype' 
                                       AND dayfrom='$startDate' 
                                       AND dayto='$endDate'
                                       AND id != '$leaveId'  /* Exclude current record */
                                       AND appstatus NOT IN ('Cancelled', 'Disapproved')");
    
        if (mysqli_num_rows($sqlCheck) > 0) {
            echo "<script>alert('Leave application already exists for the selected dates and leave type!');</script>";
        } else {
            if (!is_numeric($nofdays) || $nofdays <= 0) {
                echo "<script>alert('Invalid number of days!');</script>";
                return;
            }
            if (strtotime($endDate) < strtotime($startDate)) {
                echo "<script>alert('End date cannot be earlier than start date!');</script>";
                return;
            }
             // Retrieve the leave details
            $sqlRetrieve = mysqli_query($con, "SELECT leavetype, numberofdays, idno, dayfrom FROM leave_application WHERE id='$leaveId'");
            if ($sqlRetrieve && mysqli_num_rows($sqlRetrieve) > 0) {
                $leaveData = mysqli_fetch_array($sqlRetrieve);
                $prevleavetype = $leaveData['leavetype'];
                $prevnofdays = $leaveData['numberofdays'];
                $prevstartDate = $leaveData['dayfrom'];
                $prevstartMonth = date('n', strtotime($startDate));
            }
            // Correct UPDATE query syntax
            $sqlUpdateLeave = mysqli_query($con, "UPDATE leave_application 
                                                SET idno = '$idno',
                                                    leavetype = '$leavetype',
                                                    eo_month = '$eoMonth',
                                                    numberofdays = '$nofdays',
                                                    dayfrom = '$startDate',
                                                    dayto = '$endDate',
                                                    reason = '$reasons',
                                                    datearray = '$datenow',
                                                    edited_datetime = '$editDateTime',
                                                    appstatus = 'Pending'
                                                WHERE id = '$leaveId'");
                                                
            if ($sqlUpdateLeave) {
                if ($prevleavetype !== $leavetype || $prevnofdays != $nofdays || $prevstartDate != $startDate) {
                    // Update prev leave credits based on leave type
                    switch ($prevleavetype) {
                        case 'VL':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET vlused = vlused - $prevnofdays WHERE idno = '$idno'");
                            break;
                        case 'SL':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET slused = slused - $prevnofdays WHERE idno = '$idno'");
                            break;
                        case 'PTO':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET ptoused = ptoused - $prevnofdays WHERE idno = '$idno'");
                            break;
                        case 'BLP':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET blp_used = blp_used - $prevnofdays WHERE idno = '$idno'");
                            break;
                        case 'EO':
                            // Ensure $startMonth is an integer
                            $prevstartMonth = (int) $prevstartMonth;
                            $monthNames = [
                                1 => "jan", 2 => "feb", 3 => "mar", 4 => "apr", 5 => "may", 6 => "jun",
                                7 => "jul", 8 => "aug", 9 => "sep", 10 => "oct", 11 => "nov", 12 => "dec"
                            ];
                            
                            if (isset($monthNames[$prevstartMonth])) {
                                $columnName = $monthNames[$prevstartMonth] . "_eo_used";
                                $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET $columnName = $columnName - $prevnofdays WHERE idno = '$idno'");
                            }
                            break;
                        case 'SPL':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET spl_used = spl_used - $prevnofdays WHERE idno = '$idno'");
                            break;
                        case 'MTL':
                        case 'LTL':
                        case 'MDL':
                        case 'PTL':
                        case 'BL':
                            break;
                        default:
                            echo "<script>alert('Leave type not recognized. No credits updated.');</script>";
                            break;
                    }
                    // Update new leave credits based on leave type
                    switch ($leavetype) {
                        case 'VL':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET vlused = vlused + $nofdays WHERE idno = '$idno'");
                            break;
                        case 'SL':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET slused = slused + $nofdays WHERE idno = '$idno'");
                            break;
                        case 'PTO':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET ptoused = ptoused + $nofdays WHERE idno = '$idno'");
                            break;
                        case 'BLP':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET blp_used = blp_used + $nofdays WHERE idno = '$idno'");
                            break;
                        case 'EO':
                            // Ensure $startMonth is an integer
                            $startMonth = (int) $startMonth;
                            $monthNames = [
                                1 => "jan", 2 => "feb", 3 => "mar", 4 => "apr", 5 => "may", 6 => "jun",
                                7 => "jul", 8 => "aug", 9 => "sep", 10 => "oct", 11 => "nov", 12 => "dec"
                            ];
                            
                            if (isset($monthNames[$startMonth])) {
                                $columnName = $monthNames[$startMonth] . "_eo_used";
                                $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET $columnName = $columnName + $nofdays WHERE idno = '$idno'");
                            }
                            break;
                        case 'SPL':
                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET spl_used = spl_used + $nofdays WHERE idno = '$idno'");
                            break;
                        case 'MTL':
                        case 'LTL':
                        case 'MDL':
                        case 'PTL':
                        case 'BL':
                            break;
                        default:
                            echo "<script>alert('Leave type not recognized. No credits updated.');</script>";
                            break;
                    }
                }
                echo "<script>alert('Leave application updated successfully!');
                      window.location.href='manageleave.php';</script>";
            } else {
                echo "<script>alert('Error updating leave application: " . mysqli_error($con) . "');</script>";
            }
        }
    }
?>
<script>
    function confirmCancel() {
        if (confirm("Are you sure you want to cancel? Any unsaved changes will be lost.")) {
            window.location.href = "manageleave.php";
        }
    }
    function checkSubmitButton() {
        const submitBtn = document.getElementById('submitBtn');
        const leaveType = document.querySelector('select[name="leavetype"]').value;
        const eoMonthSelect = document.querySelector('select[name="eo_month"]');
        const selectedEO_Month = eoMonthSelect ? eoMonthSelect.value : '';
        const userBirthdayMonth = <?= json_encode($birthMonth); ?>; // PHP variable passed to JS
    
        // Get the start date value
        const startDateField = document.getElementsByName('startDate')[0];
        const startDateValue = new Date(startDateField.value);
        const startDateMonth = startDateValue.getMonth() + 1; // Get the month (1-12)
    
        // Check if the leave type is BLP and if the start date's month is not the user's birth month
        if (leaveType === 'BLP' && startDateMonth !== parseInt(userBirthdayMonth)) {
            submitBtn.disabled = true; // Disable the submit button
            return; // Exit the function
        }
    
        // Allow submission for non-credit leave types
        const excludedLeaveTypes = ["MTL", "MDL", "LTL", "PTL", "BL"];
    
        // Define month-based EO credits
        const credits = {
            VL: <?= isset($credits['VL']) ? $credits['VL'] : 0; ?>,
            PTO: <?= isset($credits['PTO']) ? $credits['PTO'] : 0; ?>,
            BLP: <?= isset($credits['BLP']) ? $credits['BLP'] : 0; ?>,
            SPL: <?= isset($credits['SPL']) ? $credits['SPL'] : 0; ?>,
            jan_EO: <?= isset($credits['jan_EO']) ? $credits['jan_EO'] : 0; ?>,
            feb_EO: <?= isset($credits['feb_EO']) ? $credits['feb_EO'] : 0; ?>,
            mar_EO: <?= isset($credits['mar_EO']) ? $credits['mar_EO'] : 0; ?>,
            apr_EO: <?= isset($credits['apr_EO']) ? $credits['apr_EO'] : 0; ?>,
            may_EO: <?= isset($credits['may_EO']) ? $credits['may_EO'] : 0; ?>,
            jun_EO: <?= isset($credits['jun_EO']) ? $credits['jun_EO'] : 0; ?>,
            jul_EO: <?= isset($credits['jul_EO']) ? $credits['jul_EO'] : 0; ?>,
            aug_EO: <?= isset($credits['aug_EO']) ? $credits['aug_EO'] : 0; ?>,
            sep_EO: <?= isset($credits['sep_EO']) ? $credits['sep_EO'] : 0; ?>,
            oct_EO: <?= isset($credits['oct_EO']) ? $credits['oct_EO'] : 0; ?>,
            nov_EO: <?= isset($credits['nov_EO']) ? $credits['nov_EO'] : 0; ?>,
            dec_EO: <?= isset($credits['dec_EO']) ? $credits['dec_EO'] : 0; ?>
        };
        
        // Check if any required fields are disabled
        const nofdays = document.getElementById('nofdays');
        const startDate = document.getElementsByName('startDate')[0];
        const endDate = document.getElementsByName('endDate')[0];
        const reasonField = document.getElementsByName('reasons')[0];
    
        if (nofdays.disabled || startDate.disabled || endDate.disabled || reasonField.disabled) {
            submitBtn.disabled = true;
        } else {
            submitBtn.disabled = false;
        }
    }
    
    // JavaScript function to update displayed leave credits
    function updateCredits(leaveType) {
        const eoMonthSelect = document.querySelector('select[name="eo_month"]');
        const leaveTypeSelect = document.querySelector('select[name="leavetype"]');
        let selectedLeaveType = leaveTypeSelect.value; // Get the selected leave type
    
        // If no leave type is selected, do not update credits
        if (!selectedLeaveType) {
            document.getElementById('credit-info').textContent = ""; // Clear any existing text
            return;
        }
    
        // Get current month index (0 = January, 1 = February, etc.)
        const monthIndex = new Date().getMonth(); 
    
        // Month mapping
        const monthKeys = ["jan_EO", "feb_EO", "mar_EO", "apr_EO", "may_EO", "jun_EO",
                           "jul_EO", "aug_EO", "sep_EO", "oct_EO", "nov_EO", "dec_EO"];
    
        const monthMapping = {
            jan_EO: "January", feb_EO: "February", mar_EO: "March",
            apr_EO: "April", may_EO: "May", jun_EO: "June",
            jul_EO: "July", aug_EO: "August", sep_EO: "September",
            oct_EO: "October", nov_EO: "November", dec_EO: "December"
        };
    
        // Set default month for EO only if no value is selected
        if (leaveType === 'EO' && !eoMonthSelect.value) {
            eoMonthSelect.value = monthKeys[monthIndex]; 
        }
    
        console.log("Final Selected EO Month:", eoMonthSelect.value);
    
        let selectedEO_Month = eoMonthSelect.value;  
        let creditInfo = document.getElementById('credit-info');
        let nofdays = document.getElementById('nofdays');
        let startDate = document.getElementsByName('startDate')[0];
        let endDate = document.getElementsByName('endDate')[0];
        let reasonField = document.getElementsByName('reasons')[0];
        let prevLeaveType = "<?= $leavetype; ?>"; // Get the previously selected leave type 
    
        // Define leave types that should not be disabled even with 0 credits
        const excludedLeaveTypes = ['MTL', 'PTL', 'BL', 'MDL', 'EEO', 'LTL'];
    
        const credits = {
            VL: <?= isset($credits['VL']) ? $credits['VL'] : 0; ?>,
            PTO: <?= isset($credits['PTO']) ? $credits['PTO'] : 0; ?>,
            BLP: <?= isset($credits['BLP']) ? $credits['BLP'] : 0; ?>,
            SPL: <?= isset($credits['SPL']) ? $credits['SPL'] : 0; ?>,
            jan_EO: <?= isset($credits['jan_EO']) ? $credits['jan_EO'] : 0; ?>,
            feb_EO: <?= isset($credits['feb_EO']) ? $credits['feb_EO'] : 0; ?>,
            mar_EO: <?= isset($credits['mar_EO']) ? $credits['mar_EO'] : 0; ?>,
            apr_EO: <?= isset($credits['apr_EO']) ? $credits['apr_EO'] : 0; ?>,
            may_EO: <?= isset($credits['may_EO']) ? $credits['may_EO'] : 0; ?>,
            jun_EO: <?= isset($credits['jun_EO']) ? $credits['jun_EO'] : 0; ?>,
            jul_EO: <?= isset($credits['jul_EO']) ? $credits['jul_EO'] : 0; ?>,
            aug_EO: <?= isset($credits['aug_EO']) ? $credits['aug_EO'] : 0; ?>,
            sep_EO: <?= isset($credits['sep_EO']) ? $credits['sep_EO'] : 0; ?>,
            oct_EO: <?= isset($credits['oct_EO']) ? $credits['oct_EO'] : 0; ?>,
            nov_EO: <?= isset($credits['nov_EO']) ? $credits['nov_EO'] : 0; ?>,
            dec_EO: <?= isset($credits['dec_EO']) ? $credits['dec_EO'] : 0; ?>
        };
        
        if (selectedLeaveType == prevLeaveType) {
            if (excludedLeaveTypes.includes(leaveType)) {
                // Reset fields for excluded leave types
                creditInfo.textContent = ''; 
                creditInfo.style.color = ''; 
                nofdays.disabled = false; 
                startDate.disabled = false;
                endDate.disabled = false; 
                reasonField.disabled = false; 
                nofdays.max = ''; 
                // Reset background colors
                nofdays.style.backgroundColor = '';
                startDate.style.backgroundColor = '';
                endDate.style.backgroundColor = '';
                reasonField.style.backgroundColor = '';
            } else if (leaveType === 'EO' && selectedEO_Month) {
                if (credits[selectedEO_Month] > 0) {
                    creditInfo.textContent = `Remaining EO Credits for ${monthMapping[selectedEO_Month]}: ${credits[selectedEO_Month]}`;
                    creditInfo.style.color = 'green';
                    nofdays.disabled = false;
                    startDate.disabled = false;
                    endDate.disabled = false;
                    reasonField.disabled = false;
                    nofdays.max = credits[selectedEO_Month];
                } else {
                    creditInfo.textContent = `No available EO credits for ${monthMapping[selectedEO_Month]}.`;
                    creditInfo.style.color = 'red';
                    nofdays.disabled = true;
                    startDate.disabled = true;
                    endDate.disabled = true;
                    reasonField.disabled = true;
                    nofdays.max = 0;
                }
            } else if (credits[leaveType] !== undefined && credits[leaveType] > 0) {
                creditInfo.textContent = `Remaining Credits: ${credits[leaveType]}`;
                creditInfo.style.color = 'green';
                nofdays.disabled = false;
                startDate.disabled = false;
                endDate.disabled = false;
                reasonField.disabled = false;
                nofdays.max = credits[leaveType];
            } else {
                creditInfo.textContent = 'No available credits for this leave type.';
                creditInfo.style.color = 'red';
                nofdays.disabled = true;
                startDate.disabled = true;
                endDate.disabled = true;
                reasonField.disabled = true;
                nofdays.max = 0;
            }
        } else {
            if (excludedLeaveTypes.includes(leaveType)) {
                // Reset fields for excluded leave types
                creditInfo.textContent = ''; 
                creditInfo.style.color = ''; 
                nofdays.disabled = false; 
                startDate.disabled = false;
                endDate.disabled = false; 
                reasonField.disabled = false; 
                nofdays.max = ''; 
                // Reset background colors
                nofdays.style.backgroundColor = '';
                startDate.style.backgroundColor = '';
                endDate.style.backgroundColor = '';
                reasonField.style.backgroundColor = '';
            } else if (leaveType === 'EO' && selectedEO_Month) {
                if (credits[selectedEO_Month] > 0) {
                    creditInfo.textContent = `Remaining EO Credits for ${monthMapping[selectedEO_Month]}: ${credits[selectedEO_Month]}`;
                    creditInfo.style.color = 'green';
                    nofdays.disabled = false;
                    startDate.disabled = false;
                    endDate.disabled = false;
                    reasonField.disabled = false;
                    nofdays.max = credits[selectedEO_Month];
                } else {
                    creditInfo.textContent = `No available EO credits for ${monthMapping[selectedEO_Month]}.`;
                    creditInfo.style.color = 'red';
                    nofdays.disabled = true;
                    startDate.disabled = true;
                    endDate.disabled = true;
                    reasonField.disabled = true;
                    nofdays.max = 0;
                }
            } else if (credits[leaveType] !== undefined && credits[leaveType] > 0) {
                creditInfo.textContent = `Remaining Credits: ${credits[leaveType]}`;
                creditInfo.style.color = 'green';
                nofdays.disabled = false;
                startDate.disabled = false;
                endDate.disabled = false;
                reasonField.disabled = false;
                nofdays.max = credits[leaveType];
            } else {
                creditInfo.textContent = 'No available credits for this leave type.';
                creditInfo.style.color = 'red';
                nofdays.disabled = true;
                startDate.disabled = true;
                endDate.disabled = true;
                reasonField.disabled = true;
                nofdays.max = 0;
            }
        }
    
        checkSubmitButton();
    }
    
    // Event listener for EO month selection
    document.addEventListener("DOMContentLoaded", function() {
        const eoMonthSelect = document.querySelector('select[name="eo_month"]');
        const leaveTypeSelect = document.querySelector('select[name="leavetype"]');
    
        if (eoMonthSelect) {
            // Get current month index
            const monthIndex = new Date().getMonth();
            const monthKeys = ["jan_EO", "feb_EO", "mar_EO", "apr_EO", "may_EO", "jun_EO",
                               "jul_EO", "aug_EO", "sep_EO", "oct_EO", "nov_EO", "dec_EO"];
    
            // Set the default value explicitly, overriding any old value
            eoMonthSelect.value = monthKeys[monthIndex]; 
        
            // Update credits after setting the correct month
            updateCredits('EO');
    
            // Add event listener for changes
            eoMonthSelect.addEventListener("change", function() {
                updateCredits('EO');
            });
    
            // eoMonthSelect.style.display = "none"; // Hide EO month dropdown initially
            eoMonthSelect.addEventListener("change", function() {
                updateCredits('EO');
            });
        }
    
        if (leaveTypeSelect) {
            leaveTypeSelect.addEventListener("change", function() {
                updateCredits(this.value);
            });
        }
    
        // Call updateCredits initially for the fetched leave type
        updateCredits(leaveTypeSelect.value);
    });
    
    function checkCredits() {
        const withdayprotocol = ['VL', 'SPL', 'BLP', 'PTO', 'jan_EO', 'feb_EO', 'mar_EO', 'apr_EO', 'may_EO', 'jun_EO', 'jul_EO', 'aug_EO', 'sep_EO', 'oct_EO', 'nov_EO', 'dec_EO'];
        let startDate = document.getElementsByName('startDate')[0];
        let endDate = document.getElementsByName('endDate')[0];
        let nofdays = document.getElementById('nofdays');
        let dateWarning = document.getElementById('date-warning');
        let endDateWarning = document.getElementById('end-date-warning');
        let creditInfo = document.getElementById('credit-info');
        let selectedLeaveType = document.querySelector('select[name="leavetype"]').value;
        let selectedEOMonth = document.querySelector('select[name="eo_month"]').value;
        let userBirthdayMonth = "<?= $birthMonth; ?>"; // Extracted from PHP
        let selectedStartDate = new Date(startDate.value);
        let startshift = "<?=$startshift;?>";
        let datearray = "<?= $datearray; ?>";
        let datenow = new Date(datearray);
        // Get the weekday name
        datenow = datenow.toLocaleDateString('en-US', { weekday: 'long' });
        let submitBtn = document.getElementById('submitBtn');
        
        // Check if the selected leave type requires a 3-day protocol
        if (withdayprotocol.includes(selectedLeaveType) || withdayprotocol.includes(selectedEOMonth)) {
            if((startshift == "23:00:00" || startshift == "00:00:00") && datenow == "Friday" ) {
                // Check if startDate has a value
                if (!startDate.value) {
                    dateWarning.style.display = 'inline';
                    startDate.style.borderColor = 'red';
                    endDate.disabled = true; 
                    submitBtn.disabled = true;
                    return false;
                } else {
                    endDate.disabled = false; 
                    submitBtn.disabled = false;
                }
    
                // Set current date and add 3 working days to it
                let currentDate = new Date(datearray);
                let minStartDate = new Date(currentDate); // Clone the current date
                minStartDate.setHours(0, 0, 0, 0); // Reset time to midnight
    
                let daysAdded = 0;
    
                while (daysAdded < 2) { // Add 3 working days
                    minStartDate.setDate(minStartDate.getDate() + 1); // Move to next day
    
                    // Skip Sundays (0) and Mondays (1)
                    if (minStartDate.getDay() !== 0 && minStartDate.getDay() !== 1) {
                        daysAdded++;
                    }
                }
    
                // Validate that the start date is at least 3 working days from today
                if (new Date(startDate.value) < minStartDate) {
                    dateWarning.style.display = 'inline';
                    startDate.style.borderColor = 'red';
                    endDate.disabled = true; 
                    submitBtn.disabled = true;
                    return false;
                } else {
                    // Hide the error message if the start date is valid
                    dateWarning.style.display = 'none';
                    startDate.style.borderColor = '';
                    endDate.disabled = false; 
                    submitBtn.disabled = false;
                }
            }else{
                // Check if startDate has a value
                if (!startDate.value) {
                    dateWarning.style.display = 'inline';
                    startDate.style.borderColor = 'red';
                    endDate.disabled = true; 
                    submitBtn.disabled = true;
                    return false;
                } else {
                    endDate.disabled = false; 
                    submitBtn.disabled = false;
                }
    
                // Set current date and add 3 working days to it
                let currentDate = new Date(datearray);
                let minStartDate = new Date(currentDate); // Clone the current date
                minStartDate.setHours(0, 0, 0, 0); // Reset time to midnight
    
                let daysAdded = 0;
    
                while (daysAdded < 3) { // Add 3 working days
                    minStartDate.setDate(minStartDate.getDate() + 1); // Move to next day
    
                    // Skip Sundays (0) and Mondays (1)
                    if (minStartDate.getDay() !== 0 && minStartDate.getDay() !== 1) {
                        daysAdded++;
                    }
                }
    
                // Validate that the start date is at least 3 working days from today
                if (new Date(startDate.value) < minStartDate) {
                    dateWarning.style.display = 'inline';
                    startDate.style.borderColor = 'red';
                    endDate.disabled = true; 
                    submitBtn.disabled = true;
                    return false;
                } else {
                    // Hide the error message if the start date is valid
                    dateWarning.style.display = 'none';
                    startDate.style.borderColor = '';
                    endDate.disabled = false; 
                    submitBtn.disabled = false;
                }
            }
        }
    
        // For all leave types: check if the number of days is 1
        if (nofdays.value == 1) {
            endDate.value = startDate.value;
            endDateWarning.style.display = 'none';
        } else {
            endDate.disabled = false;
            updateEndDate();
        }
    
        let selectedMonth = selectedStartDate.getMonth() + 1; // JS months are 0-indexed
    
        // Check if it's a birthday leave (BLP) and if the application is within the user's birth month
        if (selectedLeaveType === 'BLP') {
            if (selectedMonth !== parseInt(userBirthdayMonth)) {
                creditInfo.textContent = 'Birthday Leave can only be applied within your birthday month.';
                creditInfo.style.color = 'red';
                return false;
            }else{
                creditInfo.textContent = '';
                creditInfo.style.color = '';
            }
    
            if (<?= $credits['BLP'] ?? 0 ?> <= 0) { // Check if the user has birthday leave credits
                creditInfo.textContent = 'You do not have enough Birthday Leave credits.';
                creditInfo.style.color = 'red';
                return false;
            }
        }
        // Check if number of days is 1 (BLP is for one day only)
        if (nofdays.value == 1) {
            endDate.value = startDate.value;
            endDateWarning.style.display = 'none';
        } else {
            endDate.disabled = false;
            updateEndDate();
        }
    
        return true; 
    }
    
    function updateEndDate() {
        let startDate = document.getElementsByName('startDate')[0];
        let endDate = document.getElementsByName('endDate')[0];
        let nofdays = document.getElementById('nofdays');
        let selectedLeaveType = document.querySelector('select[name="leavetype"]').value;
        let endDateWarning = document.getElementById('end-date-warning');
        let startshift = "<?=$startshift;?>";
    
        // Check if startDate has a value
        if (!startDate.value) {
            endDateWarning.textContent = '*Start date is required.';
            endDateWarning.style.display = 'inline';
            endDate.style.borderColor = 'red';
            return; 
        }
    
        // Convert start date value to a Date object
        let selectedStartDate = new Date(startDate.value);
        let totalDaysToAdd = parseInt(nofdays.value);
    
        let endDateValue = new Date(selectedStartDate);
        let daysAdded = 0;
    
        // Start counting from the selected start date
        if (selectedLeaveType == 'MTL' || selectedLeaveType == 'MDL' || selectedLeaveType == 'LTL') {
            while (daysAdded < totalDaysToAdd - 1) {
                endDateValue.setDate(endDateValue.getDate() + 1);
                daysAdded++;
            }
        }else{
            if (totalDaysToAdd > 0) {
            daysAdded = 1; 
    
                if(startshift == "23:00:00" || startshift == "00:00:00") {
                    while (daysAdded < totalDaysToAdd) {
                        endDateValue.setDate(endDateValue.getDate() + 1); 
                        // Check if it's a weekday (Monday to Friday)
                        if (endDateValue.getDay() !== 6 && endDateValue.getDay() !== 0) { // 0 = Sunday
                            daysAdded++; 
                        }
                    }
                }else{  
                    while (daysAdded < totalDaysToAdd) {
                        endDateValue.setDate(endDateValue.getDate() + 1); 
                        // Check if it's a weekday (Tuesday to Saturday)
                        if (endDateValue.getDay() !== 0 && endDateValue.getDay() !== 1) { // 0 = Sunday, 1 = Monday
                            daysAdded++; 
                        }
                    }
                }
            }
        }
        // Convert expected end date to a format that matches the input type="date"
        let expectedEndDateStr = endDateValue.toISOString().split('T')[0]; 
        endDate.value = expectedEndDateStr;
        endDateWarning.style.display = 'none';
        endDate.style.borderColor = '';
    }
    
    function checkEndDate() {
        let startDate = document.getElementsByName('startDate')[0];
        let endDate = document.getElementsByName('endDate')[0];
        let endDateWarning = document.getElementById('end-date-warning');
        
        if (new Date(endDate.value) < new Date(startDate.value)) {
            endDateWarning.textContent = '*End date cannot be earlier than start date.';
            endDateWarning.style.display = 'inline';
            endDate.style.borderColor = 'red';
        } else {
            endDateWarning.style.display = 'none';
            endDate.style.borderColor = '';
        }
    }
    //Function for EO month
    // Function to handle EO selection
    function toggleEOSelection(selectElement) {
        let eoMonthGroup = document.getElementById('eo-month-group');
        let eoMonthSelect = document.querySelector('select[name="eo_month"]');
    
        if (selectElement.value === "EO") {
            eoMonthGroup.style.display = 'block';
            eoMonthSelect.setAttribute("required", "required");
    
            // Define months with corresponding formatted values
            const monthMap = {
                "01": "jan_EO",
                "02": "feb_EO",
                "03": "mar_EO",
                "04": "apr_EO",
                "05": "may_EO",
                "06": "jun_EO",
                "07": "jul_EO",
                "08": "aug_EO",
                "09": "sep_EO",
                "10": "oct_EO",
                "11": "nov_EO",
                "12": "dec_EO"
            };
    
            // Default to the current month
            let currentMonth = new Date().getMonth() + 1; // getMonth() is 0-based
            let formattedMonth = currentMonth < 10 ? "0" + currentMonth : currentMonth;
            
            // Set the default value to the current month formatted as "jan_EO", "feb_EO", etc.
            eoMonthSelect.value = monthMap[formattedMonth];
    
            // Disable the dropdown when EO is selected
            // selectElement.disabled = true;
        } else {
            eoMonthGroup.style.display = 'none';
            eoMonthSelect.value = ""; // Reset selection
            eoMonthSelect.removeAttribute("required");
        }
    }
    
    // Disable leave type field if value is EO when the page loads
    document.addEventListener("DOMContentLoaded", function () {
        let leaveTypeSelect = document.getElementById("leaveTypeSelect");
        let eoMonthGroup = document.getElementById('eo-month-group');
        let eoMonthSelect = document.querySelector('select[name="eo_month"]');
    
        // Fetch the stored EO month value
        let eoMonthValue = eoMonthSelect.value.trim(); // Trim to ensure no whitespace issues
    
        if (leaveTypeSelect.value === "EO") {
            // leaveTypeSelect.disabled = true; // Disable the dropdown
    
            // Ensure EO month dropdown is shown even if fetched value is NULL
            eoMonthGroup.style.display = 'block';
    
            // If eoMonth is empty/null, set the current month as default
            if (!eoMonthValue) {
                const monthMap = {
                    "01": "jan_EO", "02": "feb_EO", "03": "mar_EO", "04": "apr_EO",
                    "05": "may_EO", "06": "jun_EO", "07": "jul_EO", "08": "aug_EO",
                    "09": "sep_EO", "10": "oct_EO", "11": "nov_EO", "12": "dec_EO"
                };
    
                let currentMonth = new Date().getMonth() + 1; // getMonth() is 0-based
                let formattedMonth = currentMonth < 10 ? "0" + currentMonth : currentMonth;
                eoMonthSelect.value = monthMap[formattedMonth]; // Set default value
            }
        } else {
            eoMonthGroup.style.display = 'none';
        }
    });
</script>