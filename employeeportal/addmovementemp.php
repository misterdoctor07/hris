<?php
    // Stronger mysqli error reporting so we can catch issues in try/catch
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    include('../config.php');

    // Initialize defaults (kept from your original; not used directly below but harmless)
    $employeeDetails = [
        'company' => 'N/A',
        'department' => 'N/A',
        'jobtitle' => 'N/A',
        'shift' => 'N/A'
    ];

    $error = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && isset($_POST['addmovement'])) {
        try {
            // Collect & sanitize
            $idno               = mysqli_real_escape_string($con, $_POST['idno']);
            $movement_type      = mysqli_real_escape_string($con, $_POST['movement_type']);
            $current_company    = mysqli_real_escape_string($con, $_POST['current_company']);   // from hidden
            $current_department = mysqli_real_escape_string($con, $_POST['current_department']); // from hidden
            $current_jobtitle   = mysqli_real_escape_string($con, $_POST['current_jobtitle']);   // from hidden
            $current_shift      = mysqli_real_escape_string($con, $_POST['current_shift']);      // from hidden

            $new_company        = !empty($_POST['new_company']) ? mysqli_real_escape_string($con, $_POST['new_company']) : null;
            $new_department     = !empty($_POST['new_department']) ? mysqli_real_escape_string($con, $_POST['new_department']) : null;
            $new_jobtitle       = !empty($_POST['new_jobtitle']) ? mysqli_real_escape_string($con, $_POST['new_jobtitle']) : null;
            $new_shift          = !empty($_POST['new_shift']) ? mysqli_real_escape_string($con, $_POST['new_shift']) : null;

            $datenow            = date('Y-m-d H:i:s');

            $reason             = mysqli_real_escape_string($con, $_POST['reason']);
            $other_reason       = !empty($_POST['other_reason']) ? mysqli_real_escape_string($con, $_POST['other_reason']) : null;
            $custom_shift       = !empty($_POST['custom_shift']) ? mysqli_real_escape_string($con, $_POST['custom_shift']) : null;

            $effectivity_date   = mysqli_real_escape_string($con, $_POST['effectivity_date']);
            $addedby            = mysqli_real_escape_string($con, $_POST['addedby']);
            $remarks            = !empty($_POST['remarks']) ? mysqli_real_escape_string($con, $_POST['remarks']) : null;

            $location           = mysqli_real_escape_string($con, $_POST['location']);
            $transfer_reason    = !empty($_POST['transfer_reason']) ? mysqli_real_escape_string($con, urldecode($_POST['transfer_reason'])) : null;
            // ✅ FIX: use the correct field name from your form
            $date_transfer      = mysqli_real_escape_string($con, $_POST['transfer_date']);

            // Start transaction
            mysqli_begin_transaction($con);

            // --- INSERT employee_movements ---
            $sql1 = "INSERT INTO employee_movements (
                employee_idno, movement_type, 
                current_company, current_department, current_jobtitle, current_shift,
                new_company, new_department, new_jobtitle, new_shift, created_at,
                reason, other_reason, custom_shift, effectivity_date, created_by, remarks
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

            $stmt1 = mysqli_prepare($con, $sql1);

            // Bind params (all strings; NULLs will be sent as NULL)
            mysqli_stmt_bind_param(
                $stmt1,
                "sssssssssssssssss",
                $idno,
                $movement_type,
                $current_company,
                $current_department,
                $current_jobtitle,
                $current_shift,
                $new_company,
                $new_department,
                $new_jobtitle,
                $new_shift,
                $datenow,
                $reason,
                $other_reason,
                $custom_shift,
                $effectivity_date,
                $addedby,
                $remarks
            );
            mysqli_stmt_execute($stmt1);
            mysqli_stmt_close($stmt1);

            // --- INSERT work_transfer ---
            $sql2 = "INSERT INTO work_transfer (idno, new_loc, reason, date_transfer)
                     VALUES (?,?,?,?)";
            $stmt2 = mysqli_prepare($con, $sql2);
            mysqli_stmt_bind_param(
                $stmt2,
                "ssss",
                $idno,
                $location,
                $transfer_reason,
                $date_transfer
            );
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);

            // Commit if both succeed
            mysqli_commit($con);

            echo "<script>alert('Employee movement submitted successfully!'); window.location='dashboard.php?movementApp';</script>";
            exit();

        } catch (Throwable $e) {
            // Rollback if anything fails
            if ($con && mysqli_errno($con)) {
                mysqli_rollback($con);
            }
            $error = "Error saving movement: " . $e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Movement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            background-color: #f0f2f5
        }
        .employee-details {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .employee-details h3 {
            margin-top: 0;
        }
        .btn {
            background: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background: #45a049;
            color: white;
        }
        input[readonly] {
            background-color: #f0f0f0;
            color: #20283a;
            border: 1px solid #ced4da;
            cursor: not-allowed;
        }
        .centered-container {
            display: flex;
            justify-content: center;
            padding: 20px;
            padding-bottom: 0px;
        }

        .content-panel {
            background-color: #fff;
            border-radius: 30px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
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
            padding-bottom: 0px;
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
        
        .form-control:focus {
            outline: none;
            border-bottom-color: #007bff;
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
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 30px;
        }

        .custom-dropdown-icon {
            font-size: 25px;
            position: absolute;
            top: 50%;
            right: 13px;
            transform: translateY(-50%);
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="centered-container">
        <?php if (!empty($error)): ?>
            <div style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form class="form-horizontal style-form" method="POST" onsubmit="return confirm('Do you wish to submit details?');" style="width: 100%; max-width: 500px;">
            <input type="hidden" name="addmovement" value="1">
            <input type="hidden" name="addedby" value="<?= isset($fullname) ? htmlspecialchars($fullname) : '' ?>">
            <!-- Hidden fields actually POSTED to PHP -->
            <input type="hidden" name="current_company" id="currentCompanyHidden">
            <input type="hidden" name="current_department" id="currentDepartmentHidden">
            <input type="hidden" name="current_jobtitle" id="currentJobtitleHidden">
            <input type="hidden" name="current_shift" id="currentShiftHidden">

            <div class="content-panel">
                <div class="panel-heading d-flex align-items-center justify-content-between" style="position: relative;">
                    <h4 class="mb-0 mx-auto text-center" style="flex: 1;">EMPLOYEE MOVEMENT FORM</h4>
                    <a href="dashboard.php?movementApp" style="color: white; position: absolute; right: 15px;">
                        <i class="fa fa-times" style="cursor: pointer;"></i>
                    </a>
                </div>
                <div class="panel-body">  

                    <!-- Employee Search/Select -->
                    <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                        <label class="form-label">Employee</label>
                        <div style="position: relative; background-color: white;">
                            <input type="text" id="searchInput" onkeyup="filterFunction()" placeholder="Search employee..." class="form-control">
                            <select id="employeeSelect" name="idno" required onchange="updateEmployeeFields()" class="form-control" size="5" style="position: absolute; top: 100%; left: 0; width: 100%; display: none; z-index: 1; background-color: white;">
                                <option value="">Select an employee</option>
                                <?php
                                    // Load employees (same as your original joins/labels)
                                    $employees = mysqli_query($con, "
                                        SELECT 
                                            p.idno,
                                            CONCAT(p.lastname, ', ', p.firstname) AS name,
                                            d.company,
                                            d.designation,
                                            d.startshift,
                                            d.endshift,
                                            e.department AS department_name,
                                            a.jobtitle AS jobtitle_name,
                                            ed.companyname AS company_name
                                        FROM employee_profile p
                                        LEFT JOIN employee_details d ON d.idno = p.idno
                                        LEFT JOIN department e ON d.department = e.id
                                        LEFT JOIN jobtitle a ON d.designation = a.id
                                        LEFT JOIN settings ed ON d.company = ed.companycode
                                        ORDER BY p.lastname
                                    ");
                                    
                                    while ($emp = mysqli_fetch_assoc($employees)) {
                                        $idnoOpt     = htmlspecialchars($emp['idno']);
                                        $name        = htmlspecialchars($emp['name']);
                                        $companyTxt  = htmlspecialchars($emp['company_name'] ?? 'N/A');
                                        $jobtitleTxt = htmlspecialchars($emp['jobtitle_name'] ?? 'N/A');
                                        $deptTxt     = htmlspecialchars($emp['department_name'] ?? 'N/A');

                                        $shift_display = 'N/A';
                                        if (!empty($emp['startshift']) && !empty($emp['endshift'])) {
                                            $start = date("h:i A", strtotime($emp['startshift']));
                                            $end   = date("h:i A", strtotime($emp['endshift']));
                                            $shift_display = "$start to $end";
                                        }
                                        $shiftTxt = htmlspecialchars($shift_display);

                                        echo "<option 
                                                value='{$idnoOpt}' 
                                                data-company='{$companyTxt}' 
                                                data-jobtitle='{$jobtitleTxt}'
                                                data-shift='{$shiftTxt}'
                                                data-department='{$deptTxt}'
                                            >{$name}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Current Details (visible only; NO name attributes to avoid overwriting) -->
                    <div class="form-group mb-3" style="margin: 1px; margin-top: 15px;">
                        <label for="companys" class="form-label">Current Company</label>
                        <input type="text" id="companys" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label for="jobtitle" class="form-label">Current Job Title</label>
                        <input type="text" id="jobtitle" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label for="department" class="form-label">Current Department</label>
                        <input type="text" id="department" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label for="shift" class="form-label">Current Shift</label>
                        <input type="text" id="shift" class="form-control" readonly>
                    </div>

                    <!-- Movement Type -->
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label class="form-label">Type of Movement</label>
                        <div class="custom-select-wrapper">
                            <select class="form-control custom-select" name="movement_type" required style="cursor: pointer;">
                                <option value="">-- Select Movement Type --</option>
                                <option value="Transfer">Transfer</option>
                                <option value="Promotion">Promotion</option>
                                <option value="Demotion">Demotion</option>
                                <option value="Lateral">Lateral</option>
                            </select>
                            <span class="custom-dropdown-icon">&#9662;</span>
                        </div>
                    </div>
                    
                    <!-- New Company -->
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label class="form-label">New Company</label>
                        <div class="custom-select-wrapper">
                            <select class="form-control custom-select" name="new_company" id="newCompany" style="cursor: pointer;">
                                <option value="">-- Same Company --</option>
                                <?php
                                $sqlCompany = mysqli_query($con, "SELECT * FROM settings ORDER BY companycode ASC");
                                while($company = mysqli_fetch_array($sqlCompany)) {
                                    echo "<option value='".htmlspecialchars($company['companycode'])."'>".htmlspecialchars($company['companycode'])."</option>";
                                }
                                ?>
                            </select>
                            <span class="custom-dropdown-icon">&#9662;</span>
                        </div>
                        <small class="text-danger" style="font-style: italic;">If same Company, leave this selection blank. It's already indicated as 'Same'.</small>
                    </div>
                    
                    <!-- New Department -->
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label class="form-label">New Department</label>
                        <div class="custom-select-wrapper">
                            <select class="form-control custom-select" name="new_department" id="newDepartment" style="cursor: pointer;">
                                <option value="">-- Same Department --</option>
                                <?php
                                $sqlDept = mysqli_query($con, "SELECT * FROM department ORDER BY department ASC");
                                while($dept = mysqli_fetch_array($sqlDept)) {
                                    echo "<option value='".htmlspecialchars($dept['id'])."'>".htmlspecialchars($dept['department'])."</option>";
                                }
                                ?>
                            </select>
                            <span class="custom-dropdown-icon">&#9662;</span>
                        </div>
                        <small class="text-danger" style="font-style: italic;">If same Department, leave this selection blank. It's already indicated as 'Same'.</small>
                    </div>
                    
                    <!-- New Job Title -->
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label class="form-label">New Job Title</label>
                        <div class="custom-select-wrapper">
                            <select class="form-control custom-select" name="new_jobtitle" id="newJobtitle" style="cursor: pointer;">
                                <option value="">-- Same Job Title --</option>
                                <?php
                                $sqlJob = mysqli_query($con, "SELECT * FROM jobtitle ORDER BY jobtitle ASC");
                                while($job = mysqli_fetch_array($sqlJob)) {
                                    echo "<option value='".htmlspecialchars($job['id'])."'>".htmlspecialchars($job['jobtitle'])."</option>";
                                }
                                ?>
                            </select>
                            <span class="custom-dropdown-icon">&#9662;</span>
                        </div>
                        <small class="text-danger" style="font-style: italic;">If same Jobtitle, leave this selection blank. It's already indicated as 'Same'.</small>
                    </div>
                    
                    <!-- New Shift -->
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label class="form-label">New Shift</label>
                        <div class="custom-select-wrapper">
                            <select class="form-control custom-select" name="new_shift" id="newShift" onchange="toggleCustomShift(this)" style="cursor: pointer;">
                                <option value="">-- Same Shift --</option>
                                <option value="02:00:00-11:00:00">2:00 AM to 11:00 AM</option>
                                <option value="03:00:00-12:00:00">3:00 AM to 12:00 PM</option>
                                <option value="04:00:00-13:00:00">4:00 AM to 1:00 PM</option>
                                <option value="05:00:00-14:00:00">5:00 AM to 2:00 PM</option>
                                <option value="06:00:00-15:00:00">6:00 AM to 3:00 PM</option>
                                <option value="23:00:00-08:00:00">11:00 PM to 8:00 AM</option>
                                <option value="23:30:00-08:30:00">11:30 PM to 8:30 AM</option>
                                <option value="00:00:00-09:00:00">12:00 AM to 9:00 AM</option>
                                <option value="00:30:00-09:30:00">12:30 AM to 9:30 AM</option>
                                <option value="other">Other (Specify)</option>
                            </select>
                            <span class="custom-dropdown-icon">&#9662;</span>
                        </div>
                        <small class="text-danger" style="font-style: italic;">If same Shift, leave this selection blank. It's already indicated as 'Same'.</small>
                    </div>
                        
                    <div class="form-group mb-3" id="customShiftGroup" style="display:none; margin: 1px;">
                        <label class="form-label">Custom Shift (e.g., 01:00:00 - 10:00:00)</label>
                        <input type="text" name="custom_shift" class="form-control" id="customShift" placeholder="HH:MM:SS - HH:MM:SS">
                    </div>
                    
                    <!-- Reason -->
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label class="form-label">Reason for Change</label>
                        <div class="custom-select-wrapper">
                            <select class="form-control custom-select" name="reason" onchange="toggleCustomReason(this)" required style="cursor: pointer;">
                                <option value="">-- Select Reason --</option>
                                <option value="Employee Request">Employee Request</option>
                                <option value="Management Discretion">Management Discretion</option>
                                <option value="other">Other (Specify Below)</option>
                            </select>
                            <span class="custom-dropdown-icon">&#9662;</span>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3" id="otherReasonContainer" style="display:none; margin: 1px;">
                        <label class="form-label">Specify Reason</label>
                        <input type="text" name="other_reason" class="form-control" id="customReason" placeholder="Please specify the reason">
                    </div>
                    
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label class="form-label">Date of Effectivity</label>
                        <input type="date" name="effectivity_date" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label class="form-label">Notes</label>
                        <textarea name="remarks" class="form-control" rows="5" placeholder="Add notes here"></textarea>
                    </div>

                    <!-- Work Transfer (branch relocation) -->
                    <div class="form-group mb-3" style="margin: 10px;">
                        <label class="form-label">Branch/Satellite Office</label>
                        <select name="location" class="form-control" required style="cursor: pointer;">
                            <option value="" disabled selected>Select a location</option>
                            <option value="Davao">Davao</option>
                            <option value="Digos">Digos</option>
                            <option value="Panabo">Panabo</option>
                            <option value="Kidapawan">Kidapawan</option>
                            <option value="Iloilo">Iloilo</option>
                        </select>
                    </div>
                    <div class="form-group mb-3" style="margin: 10px;">
                        <label class="form-label">Reason</label>
                        <textarea name="transfer_reason" class="form-control" rows="5" required placeholder="Add your reason here"></textarea>
                    </div> 
                    <div class="form-group mb-3" style="margin: 10px;">
                        <label class="form-label">Effective Date</label>
                        <!-- ✅ FIX: field name must be transfer_date -->
                        <input type="date" name="transfer_date" class="form-control" required>
                    </div>
                    
                    <div class="panel-footer text-center" style="margin: 1px;">
                        <button type="submit" name="submit" class="btn" style="width: 300px; border-radius: 20px; height: 40px;">Submit Movement</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>

<script>
    const searchInput = document.getElementById("searchInput");
    const employeeSelect = document.getElementById("employeeSelect");
                
    // Show dropdown when input is focused
    searchInput.addEventListener("focus", () => {
        employeeSelect.style.display = "block";
    });
                
    // Filter dropdown options
    function filterFunction() {
        const filter = searchInput.value.toUpperCase();
        const options = employeeSelect.getElementsByTagName("option");
        for (let i = 0; i < options.length; i++) {
            const text = options[i].innerText || options[i].textContent;
            options[i].style.display = text.toUpperCase().includes(filter) ? "" : "none";
        }
    }
                
    // Update input when option is selected; also update hidden+visible fields
    employeeSelect.addEventListener("change", () => {
        const selectedOption = employeeSelect.options[employeeSelect.selectedIndex];
        searchInput.value = selectedOption.innerText;
        employeeSelect.style.display = "none"; // Hide dropdown after selection
        updateEmployeeFields();
    });
                
    // Hide dropdown when clicking outside
    document.addEventListener("click", (event) => {
        if (!event.target.closest(".form-group")) {
            employeeSelect.style.display = "none";
        }
    });
    
    function toggleCustomShift(select) {
        var customGroup = document.getElementById('customShiftGroup');
        if (select.value === 'other') {
            customGroup.style.display = 'block';
        } else {
            customGroup.style.display = 'none';
            document.getElementById('customShift').value = '';
        }
    }

    function toggleCustomReason(select) {
        var customGroup = document.getElementById('otherReasonContainer');
        if (select.value === 'other') {
            customGroup.style.display = 'block';
        } else {
            customGroup.style.display = 'none';
            document.getElementById('customReason').value = '';
        }
    }

    // ✅ Updates both visible (no-name) and hidden (POSTed) fields
    function updateEmployeeFields() {
        const select = document.getElementById('employeeSelect');
        const selected = select.options[select.selectedIndex];
        if (!selected) return;

        const company   = selected.getAttribute('data-company') || '';
        const jobtitle  = selected.getAttribute('data-jobtitle') || '';
        const department= selected.getAttribute('data-department') || '';
        const shift     = selected.getAttribute('data-shift') || '';

        // Visible (no name attrs)
        document.getElementById('companys').value   = company;
        document.getElementById('jobtitle').value   = jobtitle;
        document.getElementById('department').value = department;
        document.getElementById('shift').value      = shift;

        // Hidden (these are posted to PHP)
        document.getElementById('currentCompanyHidden').value   = company;
        document.getElementById('currentJobtitleHidden').value  = jobtitle;
        document.getElementById('currentDepartmentHidden').value= department;
        document.getElementById('currentShiftHidden').value     = shift;
    }
</script>