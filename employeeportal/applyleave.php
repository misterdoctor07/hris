<script type="text/javascript">
      function SubmitDetails(){
          return confirm('Do you wish to submit details?');
      }
</script>
<?php 
$userId = $_SESSION['idno'];
$sqlCredits = mysqli_query($con, "SELECT * FROM leave_credits WHERE idno='$userId'");
$credits = []; 
if (mysqli_num_rows($sqlCredits) > 0) {
    $credit = mysqli_fetch_array($sqlCredits);
    $credits['VL'] = $credit['vacationleave'] - $credit['vlused'];
    $credits['SL'] = $credit['sickleave'] - $credit['slused'];
    $credits['PTO'] = $credit['pto'] - $credit['ptoused'];
    $credits['BLP'] = $credit['bdayleave'] - $credit['blp_used'];
    $credits['EO'] = $credit['jan_earlyout'] - $credit['jan_eo_used'];
    $credits['SPL'] = $credit['spl'] - $credit['spl_used'];
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

<div class="row">
    <div class="col-lg-12">
        <h4 style="text-indent: 10px;">
            <a href="?manageleave"><i class="fa fa-arrow-left"></i> BACK</a> | 
            <i class="fa fa-file-text"></i> LEAVE APPLICATION
        </h4>      
    </div>
</div>

<form class="form-horizontal" method="GET" onsubmit="return SubmitDetails();">
    <input type="hidden" name="applyleave">            
    <input type="hidden" name="addedby" value="<?=$fullname;?>">          
    <div class="col-lg-4">
        <div class="content-panel">
            <div class="panel-heading">                
            <input type="submit" id="submitBtn" name="submit" class="btn btn-primary" value="Submit Details" style="float:right;">
                <h4><i class="fa fa-file-text"></i> APPLY FOR LEAVE</h4>            
            </div>
            <div class="panel-body"> 
                <div class="form-group">
                    <label class="col-sm-4 control-label">Leave Type</label>
                    <div class="col-sm-8">
                        <select name="leavetype" class="form-control" required onchange="updateCredits(this.value); checkSubmitButton();">
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
if (isset($_GET['submit'])) {
    $idno = $_SESSION['idno'];
    $leavetype = $_GET['leavetype'];
    $nofdays = $_GET['nofdays'];
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate']; 
    $reasons = isset($_GET['reasons']) ? urldecode($_GET['reasons']) : ''; // Decode the input
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
                                              (idno, leavetype, numberofdays, dayfrom, dayto, reason, datearray, appstatus) 
                                              VALUES 
                                              ('$idno', '$leavetype', '$nofdays', '$startDate', '$endDate', '$reasons', '$datenow', 'Pending')");

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

    // Check if the selected leave type has available credits
    const credits = {
        VL: <?= isset($credits['VL']) ? $credits['VL'] : 0; ?>,
        PTO: <?= isset($credits['PTO']) ? $credits['PTO'] : 0; ?>,
        BLP: <?= isset($credits['BLP']) ? $credits['BLP'] : 0; ?>,
        EO: <?= isset($credits['EO']) ? $credits['EO'] : 0; ?>,
        SPL: <?= isset($credits['SPL']) ? $credits['SPL'] : 0; ?>
    };

    // Check if the selected leave type has no credits
    if (credits[leaveType] <= 0) {
        submitBtn.disabled = true; // Disable the submit button
        return; // Exit the function
    }

    // Check if any required fields are disabled
    const nofdays = document.getElementById('nofdays');
    const startDate = document.getElementsByName('startDate')[0];
    const endDate = document.getElementsByName('endDate')[0];
    const reasonField = document.getElementsByName('reasons')[0];

    if (nofdays.disabled || startDate.disabled || endDate.disabled || reasonField.disabled) {
        submitBtn.disabled = true; // Disable the submit button
    } else {
        submitBtn.disabled = false; // Enable the submit button
    }
}
// JavaScript function to update displayed leave credits
function updateCredits(leaveType) {
    const credits = {
        VL: <?= isset($credits['VL']) ? $credits['VL'] : 0; ?>,
        PTO: <?= isset($credits['PTO']) ? $credits['PTO'] : 0; ?>,
        BLP: <?= isset($credits['BLP']) ? $credits['BLP'] : 0; ?>,
        EO: <?= isset($credits['EO']) ? $credits['EO'] : 0; ?>,
        SPL: <?=isset($credits['SPL']) ? $credits['SPL'] :0; ?>
    };

    let creditInfo = document.getElementById('credit-info');
    let nofdays = document.getElementById('nofdays');
    let startDate = document.getElementsByName('startDate')[0];
    let endDate = document.getElementsByName('endDate')[0];
    let reasonField = document.getElementsByName('reasons')[0];

    // Define leave types that should not be disabled even with 0 credits
    const excludedLeaveTypes = ['MTL', 'PTL', 'BL', 'MDL', 'EEO', 'LTL'];

    // Check if the selected leave type is in the excluded list
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
    } else if (credits[leaveType] !== undefined && credits[leaveType] > 0) {
        creditInfo.textContent = `Remaining Credits: ${credits[leaveType]}`; 
        creditInfo.style.color = '';
        nofdays.disabled = false; 
        startDate.disabled = false; 
        endDate.disabled = false; 
        reasonField.disabled = false; 

        // Set max attribute of "No. of Days" to remaining credits
        nofdays.max = credits[leaveType];
        nofdays.value = 1;  
        nofdays.style.backgroundColor = ''; 
        startDate.style.backgroundColor = '';
        endDate.style.backgroundColor = '';
        reasonField.style.backgroundColor = '';
    } else {
        // No remaining credits for selected leave type, disable all fields
        creditInfo.textContent = 'No available credits for this leave type.';
        creditInfo.style.color = 'red';
        nofdays.disabled = true; 
        startDate.disabled = true; 
        endDate.disabled = true; 
        reasonField.disabled = true; 
        nofdays.style.backgroundColor = '#f0f0f0';
        startDate.style.backgroundColor = '#f0f0f0';
        endDate.style.backgroundColor = '#f0f0f0';
        reasonField.style.backgroundColor = '#f0f0f0';
        nofdays.max = 0;
        nofdays.value = 0; 
    }
    checkSubmitButton();
}

function checkCredits() {
    const withdayprotocol = ['VL', 'SPL', 'BLP', 'EO', 'PTO'];
    let startDate = document.getElementsByName('startDate')[0];
    let endDate = document.getElementsByName('endDate')[0];
    let nofdays = document.getElementById('nofdays');
    let dateWarning = document.getElementById('date-warning');
    let endDateWarning = document.getElementById('end-date-warning');
    let creditInfo = document.getElementById('credit-info');
    let selectedLeaveType = document.querySelector('select[name="leavetype"]').value;
    let userBirthdayMonth = "<?= $birthMonth; ?>"; // Extracted from PHP
    let selectedStartDate = new Date(startDate.value);
    let startshift = "<?=$startshift;?>";
    
    // Check if the selected leave type requires a 3-day protocol
    if (withdayprotocol.includes(selectedLeaveType)) {
        // Check if startDate has a value
        if (!startDate.value) {
            dateWarning.style.display = 'inline';
            startDate.style.borderColor = 'red';
            return false;
        }

        // Set current date and add 3 days to it
        let currentDate = new Date();
            let minStartDate = new Date(currentDate); // Clone the current date
            let lastPossibleDate = new Date(selectedStartDate); // Assuming this is already a Date object
            let secondsAdded = 0;

            while (secondsAdded < 172800) { // 172800 seconds = 2 days
             minStartDate.setSeconds(minStartDate.getSeconds() + 1);

            // Ensure it's not Sunday (0) or Monday (1)
                if (minStartDate.getDay() !== 0 && minStartDate.getDay() !== 1) {
                    secondsAdded++;
                }
            }
        // Validate that the start date is at least 3 working days from today
        if (new Date(startDate.value) < minStartDate) {
            dateWarning.style.display = 'inline';
            startDate.style.borderColor = 'red';
            return false;
        } else {
            // Hide the error message if the start date is valid
            dateWarning.style.display = 'none';
            startDate.style.borderColor = '';
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
</script>

