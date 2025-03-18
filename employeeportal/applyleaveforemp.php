<script type="text/javascript">
      function SubmitDetails(){
          return confirm('Do you wish to submit details?');
      }
</script>
<?php 
$idno = $_GET['idno'];

$sqlProfile=mysqli_query($con,"SELECT * FROM employee_profile WHERE idno='$idno'");
$profile=mysqli_fetch_array($sqlProfile);
$lastname=$profile['lastname'];
$firstname=$profile['firstname'];
$suffix=$profile['suffix'];

$sqlCredits = mysqli_query($con, "SELECT * FROM leave_credits WHERE idno='$idno'");
$credits = []; 
if (mysqli_num_rows($sqlCredits) > 0) {
    $credit = mysqli_fetch_array($sqlCredits);
    $credits['VL'] = $credit['vacationleave'] - $credit['vlused'];
    $credits['SL'] = $credit['sickleave'] - $credit['slused'];
    $credits['PTO'] = $credit['pto'] - $credit['ptoused'];
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
// Fetch user birthdate
$sqlBirthDate = mysqli_query($con, "SELECT birthdate FROM employee_profile WHERE idno='$idno'");
if (mysqli_num_rows($sqlBirthDate) > 0) {
    $birthDate = mysqli_fetch_assoc($sqlBirthDate)['birthdate'];
    $birthMonth = date('m', strtotime($birthDate)); // Extract month of birth
} else {
    $birthMonth = null; // Handle case if birthdate is not found
}

//Fetch user start shift
$sqlStartShift = mysqli_query($con, "SELECT startshift FROM employee_details WHERE idno='$idno'");
if(mysqli_num_rows($sqlStartShift)>0){
    $startshift=mysqli_fetch_assoc($sqlStartShift)['startshift'];
}
?>

<div class="row">
    <div class="col-lg-12">
        <h4 style="text-indent: 10px;">
            <a href="?manageemployee"><i class="fa fa-arrow-left"></i> BACK</a> | 
            <i class="fa fa-file-text"></i> LEAVE APPLICATION FOR EMPLOYEE
        </h4>      
    </div>
</div>

<form class="form-horizontal" method="POST" onsubmit="return SubmitDetails();">
    <input type="hidden" name="idno" value="<?php echo $idno; ?>">          
    <div class="col-lg-4">
        <div class="content-panel">
            <div class="panel-heading">                
            <input type="submit" id="submitBtn" name="submit" class="btn btn-primary" value="Submit Details" style="float:right;">
                <h4><i class="fa fa-user"></i> <?=$lastname;?>, <?=$firstname;?> <?=$suffix;?></h4>            
            </div>
            <div class="panel-body"> 
                <div class="form-group">
                    <label class="col-sm-4 control-label">Leave Type</label>
                    <div class="col-sm-8">
                        <select name="leavetype" class="form-control" required onchange="updateCredits(this.value); checkSubmitButton(); toggleEOSelection(this.value);">
                            <option value="" disabled selected>Select Leave Type</option>
                            <option value="VL">Vacation Leave (VL)</option>
                            <option value="PTO">Unpaid Leave (PTO)</option>
                            <option value="SPL">Solo Parent Leave (SPL)</option>
                            <option value="BLP">Birthday Leave (BLP)</option>
                            <option value="EO">Early Out (EO)</option>
                            <option value="MTL">Maternity Leave (MTL)</option>
                            <option value="PTL">Paternity Leave (PTL)</option>
                            <option value="LTL">Long Term Leave (LTL)</option>
                            <option value="MDL">Medical Leave (MDL)</option>
                            <option value="BL">Bereavement Leave (BL)</option>
                        </select>
                        <small id="credit-info" class="form-text text-muted"></small>
                    </div>
                </div>

                <!-- Additional Selection for EO Month -->
                <div class="form-group" id="eo-month-group" style="display: none;">
                    <label class="col-sm-4 control-label">Select Month</label>
                    <div class="col-sm-8">
                        <select name="eo_month" class="form-control">
                            <option value="" disabled>Select Month</option>
                            <option value="jan_EO">January</option>
                            <option value="feb_EO">February</option>
                            <option value="mar_EO">March</option>
                            <option value="apr_EO">April</option>
                            <option value="may_EO">May</option>
                            <option value="jun_EO">June</option>
                            <option value="jul_EO">July</option>
                            <option value="aug_EO">August</option>
                            <option value="sep_EO">September</option>
                            <option value="oct_EO">October</option>
                            <option value="nov_EO">November</option>
                            <option value="dec_EO">December</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-4 control-label">No. of Days</label>
                  <div class="col-sm-4">
                      <input type="number" name="nofdays" id="nofdays" class="form-control" value="0" min="1" required onchange="checkCredits(); checkSubmitButton();">
                  </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Start Date</label>
                    <div class="col-sm-8">
                        <input type="date" name="startDate" class="form-control" required onchange="checkCredits(); checkSubmitButton();">
                        <span id="date-warning" style="color: red; display: none;">*You must file for leave at least 3 days in advance.</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">End Date</label>
                    <div class="col-sm-8">
                        <input type="date" name="endDate" class="form-control" required onchange="checkCredits(); checkSubmitButton();">
                        <span id="end-date-warning" style="color: red; display: none;"></span>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-4 control-label">Reason(s)</label>
                    <div class="col-sm-8">
                        <textarea name="reasons" class="form-control" rows="5" required onchange="checkSubmitButton();"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
if (isset($_POST['submit'])) {
    $idno = $_POST['idno'];
    $leavetype = $_POST['leavetype'];
    $eoMonth = ($leavetype === 'EO') ? $_POST['eo_month'] : 'NULL';
    $nofdays = $_POST['nofdays'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate']; 
    $reasons = isset($_POST['reasons']) ? urldecode($_POST['reasons']) : ''; // Decode the input
    $reasons = mysqli_real_escape_string($con, $reasons); // Sanitize for SQL    
    $datenow = date('Y-m-d H:i:s'); 

    // Check if there's already an existing record for the selected leave type within the same date range
    $sqlCheck = mysqli_query($con, "SELECT * FROM leave_application 
                                     WHERE idno='$idno' 
                                     AND leavetype='$leavetype' 
                                     AND dayfrom='$startDate' 
                                     AND dayto='$endDate'
                                     AND appstatus NOT IN ('Cancelled', 'Disapproved')");

    if (mysqli_num_rows($sqlCheck) > 0) {
        echo "<script>alert('Leave application already exists for the selected dates and leave type!');</script>";
    } else {
        // Insert the leave application
        $sqlInsertLeave = mysqli_query($con, "INSERT INTO leave_application 
                                              (idno, leavetype, eo_month, numberofdays, dayfrom, dayto, reason, datearray, appstatus) 
                                              VALUES 
                                              ('$idno', '$leavetype', '$eoMonth', '$nofdays', '$startDate', '$endDate', '$reasons', '$datenow', 'Pending')");

        // Check if the leave application was successfully inserted
        if ($sqlInsertLeave) {
                echo "<script>alert('Leave application submitted successfully!');</script>";
        }else {
                echo "<script>alert('Failed to insert leave application. Please try again.');</script>";
         }
    }
}
?>

<script>
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

    // Allow submission for non-credit leave types
    const noCreditLeaves = ["MTL", "MDL", "LTL", "PTL", "BL"];
    
    // Check if the leave type is BLP and if the start date's month is not the user's birth month
    if (leaveType === 'BLP' && startDateMonth !== parseInt(userBirthdayMonth)) {
        submitBtn.disabled = true;
        return;
    }

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

    // // Check EO credits per month properly
    // if (leaveType === 'EO') {
    //     if (!selectedEO_Month) {
    //         submitBtn.disabled = true;
    //         return;
    //     }
    //     if (!credits[selectedEO_Month] || credits[selectedEO_Month] <= 0) {
    //         submitBtn.disabled = true;
    //         return;
    //     }
    // } else if (leaveType === noCreditLeaves){
    //     submitBtn.disabled = false;
    // }else {
    //     if (!credits[leaveType] || credits[leaveType] <= 0) {
    //         submitBtn.disabled = true;
    //         return;
    //     }
    // }

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

    if (excludedLeaveTypes.includes(leaveType)) {
        creditInfo.textContent = ''; 
        creditInfo.style.color = ''; 
        
        nofdays.disabled = false; 
        startDate.disabled = false;
        endDate.disabled = false; 
        reasonField.disabled = false; 

        // Reset the attributes and styles
        nofdays.max = ''; 
        nofdays.value = 1; 
        nofdays.style.backgroundColor = '';
        startDate.style.backgroundColor = '';
        endDate.style.backgroundColor = '';
        reasonField.style.backgroundColor = '';
    }else if (leaveType === 'EO' && selectedEO_Month) {
        if (credits[selectedEO_Month] > 0) {
            creditInfo.textContent = `Remaining EO Credits for ${monthMapping[selectedEO_Month]}: ${credits[selectedEO_Month]}`;
            creditInfo.style.color = 'green';
            nofdays.disabled = false;
            startDate.disabled = false;
            endDate.disabled = false;
            reasonField.disabled = false;
            nofdays.max = credits[selectedEO_Month];
            nofdays.value = 1;
        } else {
            creditInfo.textContent = `No available EO credits for ${monthMapping[selectedEO_Month]}.`;
            creditInfo.style.color = 'red';
            nofdays.disabled = true;
            startDate.disabled = true;
            endDate.disabled = true;
            reasonField.disabled = true;
            nofdays.max = 0;
            nofdays.value = 0;
        }
    } else if (credits[leaveType] !== undefined && credits[leaveType] > 0) {
        creditInfo.textContent = `Remaining Credits: ${credits[leaveType]}`;
        creditInfo.style.color = 'green';
        nofdays.disabled = false;
        startDate.disabled = false;
        endDate.disabled = false;
        reasonField.disabled = false;
        nofdays.max = credits[leaveType];
        nofdays.value = 1;
    } else {
        creditInfo.textContent = 'No available credits for this leave type.';
        creditInfo.style.color = 'red';
        nofdays.disabled = true;
        startDate.disabled = true;
        endDate.disabled = true;
        reasonField.disabled = true;
        nofdays.max = 0;
        nofdays.value = 0;
    }

    checkSubmitButton();
}

// Add event listener for EO month selection
document.addEventListener("DOMContentLoaded", function() {
    const eoMonthSelect = document.querySelector('select[name="eo_month"]');
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
    }
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
    
    // // Check if the selected leave type requires a 3-day protocol
    // if (withdayprotocol.includes(selectedLeaveType) || withdayprotocol.includes(selectedEOMonth)) {
    //     // Check if startDate has a value
    //     if (!startDate.value) {
    //         dateWarning.style.display = 'inline';
    //         startDate.style.borderColor = 'red';
    //         return false;
    //     }

    //     // Set current date and add 3 working days to it
    //     let currentDate = new Date();
    //     let minStartDate = new Date(currentDate); // Clone the current date
    //     minStartDate.setHours(0, 0, 0, 0); // Reset time to midnight

    //     let daysAdded = 0;

    //     while (daysAdded < 3) { // Add 3 working days
    //         minStartDate.setDate(minStartDate.getDate() + 1); // Move to next day

    //         // Skip Sundays (0) and Mondays (1)
    //         if (minStartDate.getDay() !== 0 && minStartDate.getDay() !== 1) {
    //             daysAdded++;
    //         }
    //     }

    //     // Validate that the start date is at least 3 working days from today
    //     if (new Date(startDate.value) < minStartDate) {
    //         dateWarning.style.display = 'inline';
    //         startDate.style.borderColor = 'red';
    //         return false;
    //     } else {
    //         // Hide the error message if the start date is valid
    //         dateWarning.style.display = 'none';
    //         startDate.style.borderColor = '';
    //     }
    // }

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
function toggleEOSelection(selectedValue) {
    let eoMonthGroup = document.getElementById('eo-month-group');
    let eoMonthSelect = document.querySelector('select[name="eo_month"]');

    if (selectedValue === "EO") {
        eoMonthGroup.style.display = 'block';

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
    } else {
        eoMonthGroup.style.display = 'none';
        eoMonthSelect.value = ""; // Reset selection
    }
}
</script>

