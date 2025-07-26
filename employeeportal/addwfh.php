<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    include '../config.php';
    
    if (!isset($_SESSION['idno'])) {
        die("<script>alert('Session expired. Please log in again.'); window.location='/index.php';</script>");
    }
    $userID = $_SESSION['idno'];
?>
<script type="text/javascript">
function SubmitDetails() {
    return confirm('Do you wish to submit details?');
}
</script>
<div class="row">
    <div class="col-lg-12">
        <h4 style="text-indent: 10px;">
            <a href="?wfh_form"><i class="fa fa-arrow-left"></i> BACK</a> |
            <i class="fa fa-file-book"></i> WFH APPLICATION
        </h4>
    </div>
</div>
<form class="form-horizontal style-form" method="POST" onSubmit="return SubmitDetails();">
    <input type="hidden" name="addwfhapplication">        
    <div class="col-lg-10 mt">
        <div class="content-panel">
            <div class="panel-heading">                
                <input type="submit" id="submitBtn" name="submit" class="btn btn-primary" value="Submit Details" style="float:right;">
                <h4>APPLY FOR WFH ARRANGEMENT</h4>            
            </div>
            <style>
                .group-wrapper {
                    display: flex;
                    gap: 20px; /* space between the two sections */
                    flex-wrap: wrap; /* allow wrapping on smaller screens */
                }
                .group-box {
                    flex: 1;
                    min-width: 250px; /* adjust as needed */
                    border: 1px solid #ccc;
                    border-radius: 8px;
                    padding: 15px;
                    margin-top: -20px;
                }
                .group-title {
                    font-weight: bold;
                    margin-bottom: 10px;
                    font-size: 16px;
                    display: block;
                }
            </style>
            <div class="panel-body">
                <div class="group-wrapper">
                    <!-- Employee Info Section -->
                    <div class="group-box">
                        <label class="group-title">Employee Information</label>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Employee ID</label>
                            <div class="col-sm-8">
                                <input type="text" name="empID" id="empID" class="form-control" placeholder="Enter Employee ID then hit ENTER">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Name</label>
                            <div class="col-sm-8">
                                <input type="text" name="name" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Department</label>
                            <div class="col-sm-8">
                                <input type="text" name="department" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Job Title</label>
                            <div class="col-sm-8">
                                <input type="text" name="jobtitle" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Address of WFH</label>
                            <div class="col-sm-8">
                                <input type="text" name="address" class="form-control" required placeholder="House No., Street, Barangay, City, Province">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Schedule</label>
                            <div class="col-sm-8">
                                <input type="text" name="schedule" class="form-control" required placeholder="Ex: 01/01/2026, 05:00 AM - 02:00 PM">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Contact No.</label>
                            <div class="col-sm-8">
                                <input type="text" name="contactnum" class="form-control" required placeholder="09*********">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 col-sm-4 control-label">Reason(s)</label>
                            <div class="col-sm-8">
                                <textarea name="reason" class="form-control" rows="5" required placeholder="Add your reason here"></textarea>
                            </div>
                        </div> 
                    </div>
                    <!-- Internet Info Section -->
                    <div class="group-box">
                        <label class="group-title">Internet and Other Information</label>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Do you take work calls?</label>
                            <div class="col-sm-8">
                                <label class="radio-inline">
                                    <input type="radio" name="work_calls" value="Yes" required> Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="work_calls" value="No" required> No
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Internet Connection Type</label>
                            <div class="col-sm-8">
                                <select name="conn_internet" class="form-control" id="conn_internet_select" required>
                                    <option value="">-- Select Connection Type --</option>
                                    <option value="Wired connection">Wired connection</option>
                                    <option value="Others">Others</option>
                                </select>
                        
                                <!-- Hidden field for custom input if "Others" is selected -->
                                <input type="text" name="conn_internet_other" id="conn_internet_other" class="form-control mt-2"
                                       style="display:none; margin-top:10px;" placeholder="Please specify your connection type">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Internet Service Provider</label>
                            <div class="col-sm-8">
                                <input type="text" name="type_internet" class="form-control" required placeholder="Globe/PLDT/Converge/etc.">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="mbpsLabel">Internet Plan (must be above 15Mbps)</label>
                            <div class="col-sm-8">
                                <input type="text" name="mbps" class="form-control" required placeholder="Enter Internet Speed (Mbps)">
                                <small id="mbpsWarning" class="text-danger" style="display: none;"></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Backup Internet</label>
                            <div class="col-sm-8">
                                <select name="backup_internet" class="form-control" id="backup_internet_select" required>
                                    <option value="">-- Select Backup Internet --</option>
                                    <option value="N/A">N/A</option>
                                    <option value="Wired connection">Other Wired Connection</option>
                                    <option value="WIFI with personal dongle">WIFI with personal dongle</option>
                                    <option value="Phone USB Tethering">Phone USB Tethering</option>
                                </select>
                        
                                <!-- Indented and aligned hidden details -->
                                <div id="wiredDetails" style="display: none; margin-top: 15px; padding-left: 20px; margin-right: 15px;">
                                    <div class="form-group">
                                        <label class="control-label">Internet Service Provider</label>
                                        <input type="text" name="isp" class="form-control" placeholder="Globe/PLDT/Converge/etc.">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Internet Plan (Mbps)</label>
                                        <input type="text" name="internet_speed" id="backup_mbps" class="form-control" placeholder="Enter Internet Speed (Mbps)">
                                        <small id="backupMbpsWarning" class="text-danger" style="display: none;"></small>
                                    </div>
                                </div>
                            </div>
                        </div>          
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Date of Transfer</label>
                            <div class="col-sm-8">
                                <input type="date" name="date" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const workCallRadios = document.querySelectorAll('input[name="work_calls"]');
        const mbpsInput = document.querySelector('input[name="mbps"]');
        const label = document.querySelector('label[for="mbpsLabel"]') || document.querySelectorAll('.form-group')[3].querySelector('label');
        const mbpsWarning = document.getElementById('mbpsWarning');
        const submitBtn = document.getElementById('submitBtn');
    
        const backupMbpsInput = document.getElementById('backup_mbps');
        const backupMbpsWarning = document.getElementById('backupMbpsWarning');
        const wiredSection = document.getElementById("wiredDetails");
    
        const select = document.getElementById('conn_internet_select');
        const otherInput = document.getElementById('conn_internet_other');
    
        let currentRequirement = 15;
    
        function updateLabelText(value) {
            currentRequirement = value === 'Yes' ? 20 : 15;
            label.textContent = `Internet Plan (must be above ${currentRequirement}mbps)`;
            validateMbps();
            validateBackupMbps();
        }
    
        function validateMbps() {
            const mbpsRaw = mbpsInput.value.trim().toLowerCase().replace('mbps', '').replace(/\s/g, '');
            const mbpsValue = parseFloat(mbpsRaw);
    
            if (mbpsRaw === '') {
                mbpsWarning.style.display = 'none';
                submitBtn.disabled = true;
                return false;
            }
    
            if (isNaN(mbpsValue)) {
                mbpsWarning.textContent = "Please enter a valid numeric value (e.g., 20 or 20mbps).";
                mbpsWarning.style.display = 'block';
                submitBtn.disabled = true;
                return false;
            }
    
            if (mbpsValue < currentRequirement) {
                mbpsWarning.textContent = `Your internet plan must be at least ${currentRequirement} Mbps.`;
                mbpsWarning.style.display = 'block';
                submitBtn.disabled = true;
                return false;
            }
    
            mbpsWarning.style.display = 'none';
            return true;
        }
    
        function validateBackupMbps() {
            if (!backupMbpsInput || wiredSection.style.display === 'none') {
                backupMbpsWarning.style.display = 'none';
                return true;
            }
    
            const mbpsRaw = backupMbpsInput.value.trim().toLowerCase().replace('mbps', '').replace(/\s/g, '');
            const mbpsValue = parseFloat(mbpsRaw);
    
            if (mbpsRaw === '') {
                backupMbpsWarning.style.display = 'none';
                submitBtn.disabled = true;
                return false;
            }
    
            if (isNaN(mbpsValue)) {
                backupMbpsWarning.textContent = "Please enter a valid numeric value (e.g., 20 or 20mbps).";
                backupMbpsWarning.style.display = 'block';
                submitBtn.disabled = true;
                return false;
            }
    
            if (mbpsValue < currentRequirement) {
                backupMbpsWarning.textContent = `Your backup internet plan must be at least ${currentRequirement} Mbps.`;
                backupMbpsWarning.style.display = 'block';
                submitBtn.disabled = true;
                return false;
            }
    
            backupMbpsWarning.style.display = 'none';
            return true;
        }
    
        workCallRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                updateLabelText(this.value);
            });
        });
    
        mbpsInput.addEventListener('input', () => {
            validateMbps();
            validateBackupMbps(); // validate both in case requirement changes
            submitBtn.disabled = !(validateMbps() && validateBackupMbps());
        });
    
        if (backupMbpsInput) {
            backupMbpsInput.addEventListener('input', () => {
                validateBackupMbps();
                submitBtn.disabled = !(validateMbps() && validateBackupMbps());
            });
        }
    
        // Toggle backup internet section visibility
        document.getElementById("backup_internet_select").addEventListener("change", function () {
            wiredSection.style.display = this.value === "Wired connection" ? "block" : "none";
            validateBackupMbps();
            submitBtn.disabled = !(validateMbps() && validateBackupMbps());
        });
    
        // Toggle custom connection type input
        select.addEventListener('change', function () {
            if (this.value === 'Others') {
                otherInput.style.display = 'block';
                otherInput.required = true;
            } else {
                otherInput.style.display = 'none';
                otherInput.required = false;
            }
        });
    
        document.querySelector('form').addEventListener('submit', function (e) {
            const selected = Array.from(workCallRadios).find(r => r.checked);
            if (selected) updateLabelText(selected.value);
    
            const validPrimary = validateMbps();
            const validBackup = validateBackupMbps();
    
            if (!validPrimary) {
                mbpsInput.focus();
                e.preventDefault();
                return;
            }
    
            if (!validBackup) {
                backupMbpsInput?.focus();
                e.preventDefault();
                return;
            }
        });
    
        // Initial button disable
        submitBtn.disabled = true;
    });
    document.getElementById('empID').addEventListener('change', function () {
        const empID = this.value.trim();
        if (empID === '') return;
    
        fetch('get_employee_info.php?empID=' + encodeURIComponent(empID))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('input[name="name"]').value = data.name;
                    document.querySelector('input[name="department"]').value = data.department;
                    document.querySelector('input[name="jobtitle"]').value = data.jobtitle;
                } else {
                    alert("Employee not found.");
                    document.querySelector('input[name="name"]').value = '';
                    document.querySelector('input[name="department"]').value = '';
                    document.querySelector('input[name="jobtitle"]').value = '';
                }
            })
            .catch(err => {
                console.error(err);
                alert("Failed to fetch employee data.");
            });
    });
</script>
<?php
    if (isset($_POST['submit'])) {
        $idno = mysqli_real_escape_string($con, $_POST['empID']);
        $address = mysqli_real_escape_string($con, $_POST['address']);
        $schedule = mysqli_real_escape_string($con, $_POST['schedule']);
        $contactnum = mysqli_real_escape_string($con, $_POST['contactnum']);
        $call = mysqli_real_escape_string($con, $_POST['work_calls']);
        $conn_internet = $_POST['conn_internet'] === 'Others' 
                ? mysqli_real_escape_string($con, $_POST['conn_internet_other']) 
                : mysqli_real_escape_string($con, $_POST['conn_internet']);
        $type_internet = mysqli_real_escape_string($con, $_POST['type_internet']);
        $mbps = mysqli_real_escape_string($con, $_POST['mbps']);
        $date = mysqli_real_escape_string($con, $_POST['date']);
        $reason = isset($_POST['reason']) ? urldecode($_POST['reason']) : ''; // Decode the input
        $reason = mysqli_real_escape_string($con, $reason); // Sanitize for SQL
        $backup_internet = mysqli_real_escape_string($con, $_POST['backup_internet']);
        $filedby = $_SESSION['idno'];
        $datetime = date('Y-m-d H:i:s');
        $status = 'Pending';
    
        $query = "INSERT INTO wfh_application 
            (idno, address, schedule, contactnum, work_call, conn_internet, type_internet, speed_internet, reasons, date_effective, datetime, filedby, application_status)
            VALUES 
            ('$idno', '$address', '$schedule', '$contactnum', '$call', '$conn_internet', '$type_internet', '$mbps', '$reason', '$date', '$datetime', '$filedby', '$status')";
    
        $result = mysqli_query($con, $query);
    
        if ($result) {
            echo "<script>alert('WFH Application submitted successfully!'); window.location='?wfh_form';</script>";
        } else {
            echo "<script>alert('Failed to submit application.');</script>";
        }
    }
?>