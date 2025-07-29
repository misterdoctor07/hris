<?php
    include('../config.php');
    
    // Initialize variables
    $employeeDetails = [
        'company' => 'N/A',
        'department' => 'N/A',
        'jobtitle' => 'N/A',
        'shift' => 'N/A'
    ];
    
    // Process form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && isset($_POST['addmovement'])) {
        $idno = mysqli_real_escape_string($con, $_POST['idno']);
        $movement_type = mysqli_real_escape_string($con, $_POST['movement_type']);
        $current_company = mysqli_real_escape_string($con, $_POST['current_company']);
        $current_department = mysqli_real_escape_string($con, $_POST['current_department']);
        $current_jobtitle = mysqli_real_escape_string($con, $_POST['current_jobtitle']);
        $current_shift = mysqli_real_escape_string($con, $_POST['current_shift']);
        $new_company = mysqli_real_escape_string($con, $_POST['new_company']);
        $new_department = mysqli_real_escape_string($con, $_POST['new_department']);
        $new_jobtitle = mysqli_real_escape_string($con, $_POST['new_jobtitle']);
        $new_shift = mysqli_real_escape_string($con, $_POST['new_shift']);
        $datenow = mysqli_real_escape_string($con, date('Y-m-d H:i:s'));
        $reason = mysqli_real_escape_string($con, $_POST['reason']);
        $other_reason = isset($_POST['other_reason']) ? mysqli_real_escape_string($con, $_POST['other_reason']) : null;
        $custom_shift = isset($_POST['custom_shift']) ? mysqli_real_escape_string($con, $_POST['custom_shift']) : null;
        $effectivity_date = mysqli_real_escape_string($con, $_POST['effectivity_date']);
        $addedby = mysqli_real_escape_string($con, $_POST['addedby']);
        
        $query = "INSERT INTO employee_movements (
            employee_idno, movement_type, 
            current_company, current_department, current_jobtitle, current_shift,
            new_company, new_department, new_jobtitle, new_shift,created_at,
            reason, other_reason,custom_shift, effectivity_date, created_by
        ) VALUES (
            '$idno', '$movement_type',
            '$current_company', '$current_department', '$current_jobtitle', '$current_shift',
            '$new_company', '$new_department', '$new_jobtitle', '$new_shift','$datenow',
            '$reason', " . ($other_reason ? "'$other_reason'" : "NULL") . ", " . ($custom_shift ? "'$custom_shift'" : "NULL") . ",
            '$effectivity_date', '$addedby'
        )";
        
        if (mysqli_query($con, $query)) {
            echo "<script>alert('Employee movement submitted successfully!'); window.location='movementApp.php';</script>";
            exit();
        } else {
            $error = "Error: " . mysqli_error($con);
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
            background-color: #f5f5f5;
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
            background-color: #f0f0f0;  /* light gray background */
            color: #20283a;             /* muted text color */
            border: 1px solid #ced4da;  /* subtle border */
            cursor: not-allowed;        /* show that it's not editable */
        }
        body{
            background-color: #f0f2f5
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
            right: 13px;                /* Adjust this to control position */
            transform: translateY(-50%);
            pointer-events: none;       /* Allows click through to select */
        }
    </style>
</head>
<body style="background-image: url('3.png'); background-size: cover; background-repeat: no-repeat; background-position: center;">
    <div class="centered-container">
        <?php if (isset($error)): ?>
            <div style="color: red; margin-bottom: 15px;"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form class="form-horizontal style-form" method="POST" onsubmit="return confirm('Do you wish to submit details?');" style="width: 100%; max-width: 500px;">
            <input type="hidden" name="addmovement">
            <input type="hidden" name="addedby" value="<?=htmlspecialchars($fullname);?>">
            <input type="hidden" name="current_company" id="currentCompanyHidden">
            <input type="hidden" name="current_department" id="currentDepartmentHidden">
            <input type="hidden" name="current_jobtitle" id="currentJobtitleHidden">
            <input type="hidden" name="current_shift" id="currentShiftHidden">

            <div class="content-panel">
                <div class="panel-heading d-flex align-items-center justify-content-between" style="position: relative;">
                    <h4 class="mb-0 mx-auto text-center" style="flex: 1;">EMPLOYEE MOVEMENT FORM</h4>
                    <a href="movementApp.php" style="color: white; position: absolute; right: 15px;">
                        <i class="fa fa-times" style="cursor: pointer;"></i>
                    </a>
                </div>
                <div class="panel-body">  
                    <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                        <label class="form-label">Employee</label>
                        <div style="position: relative; background-color: white;">
                            <input type="text" id="searchInput" onkeyup="filterFunction()" placeholder="Search employee..." class="form-control">
                            <select id="employeeSelect" name="idno" required onchange="updateEmployeeFields()" class="form-control" size="5" style="position: absolute; top: 100%; left: 0; width: 100%; display: none; z-index: 1; background-color: white;">
                                <option value="">Select an employee</option>
                                <?php
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
                                        $idno       = htmlspecialchars($emp['idno']);
                                        $name       = htmlspecialchars($emp['name']);
                                        $company    = htmlspecialchars($emp['company_name'] ?? 'N/A');
                                        $jobtitle   = htmlspecialchars($emp['jobtitle_name'] ?? 'N/A');
                                        $department = htmlspecialchars($emp['department_name'] ?? 'N/A');
                                        // Format the shifts
                                        $shift_display = 'N/A';
                                        if (!empty($emp['startshift']) && !empty($emp['endshift'])) {
                                            $start = date("h:i A", strtotime($emp['startshift']));
                                            $end = date("h:i A", strtotime($emp['endshift']));
                                            $shift_display = "$start to $end";
                                        }
                                        
                                        echo "<option 
                                                value='$idno' 
                                                data-company='$company' 
                                                data-jobtitle='$jobtitle'
                                                data-shift='$shift_display'
                                                data-department='$department'
                                            >$name</option>";
                                    }
                                ?>
                            </select>
                    </div>
                    <div class="form-group mb-3" style="margin: 1px; margin-top: 15px;">
                        <label for="companys" class="form-label">Current Company</label>
                        <input type="text" id="companys" name="current_company" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label for="jobtitle" class="form-label">Current Job Title</label>
                        <input type="text" id="jobtitle" name="current_jobtitle" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label for="department" class="form-label">Current Department</label>
                        <input type="text" id="department" name="current_department" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label for="shift" class="form-label">Current Shift</label>
                        <input type="text" id="shift" name="current_shift" class="form-control" readonly>
                    </div>
                    <script>
                        function updateEmployeeFields() {
                            const select = document.getElementById('employeeSelect');
                            const selected = select.options[select.selectedIndex];
                
                            document.getElementById('companys').value   = selected.getAttribute('data-company') || '';
                            document.getElementById('jobtitle').value   = selected.getAttribute('data-jobtitle') || '';
                            document.getElementById('department').value = selected.getAttribute('data-department') || '';
                            document.getElementById('shift').value      = selected.getAttribute('data-shift') || '';
                        }
                    </script>
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
                    
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label class="form-label">New Company</label>
                        <div class="custom-select-wrapper">
                            <select class="form-control custom-select" name="new_company" id="newCompany" style="cursor: pointer;">
                                <option value="">-- Same Company --</option>
                                <?php
                                $sqlCompany = mysqli_query($con, "SELECT * FROM settings ORDER BY companycode ASC");
                                while($company = mysqli_fetch_array($sqlCompany)) {
                                    echo "<option value='".$company['companycode']."'>".$company['companycode']."</option>";
                                }
                                ?>
                            </select>
                            <span class="custom-dropdown-icon">&#9662;</span>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label class="form-label">New Department</label>
                        <div class="custom-select-wrapper">
                            <select class="form-control custom-select" name="new_department" id="newDepartment" style="cursor: pointer;">
                                <option value="">-- Same Department --</option>
                                <?php
                                $sqlDept = mysqli_query($con, "SELECT * FROM department ORDER BY department ASC");
                                while($dept = mysqli_fetch_array($sqlDept)) {
                                    echo "<option value='".$dept['id']."'>".$dept['department']."</option>";
                                }
                                ?>
                            </select>
                            <span class="custom-dropdown-icon">&#9662;</span>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label class="form-label">New Job Title</label>
                        <div class="custom-select-wrapper">
                            <select class="form-control custom-select" name="new_jobtitle" id="newJobtitle" style="cursor: pointer;">
                                <option value="">-- Same Job Title --</option>
                                <?php
                                $sqlJob = mysqli_query($con, "SELECT * FROM jobtitle ORDER BY jobtitle ASC");
                                while($job = mysqli_fetch_array($sqlJob)) {
                                    echo "<option value='".$job['id']."'>".$job['jobtitle']."</option>";
                                }
                                ?>
                            </select>
                            <span class="custom-dropdown-icon">&#9662;</span>
                        </div>
                    </div>
                    
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
                                <option value="12:00:00-09:00:00">12:00 PM to 9:00 AM</option>
                                <option value="12:30:00-09:30:00">12:30 PM to 9:30 AM</option>
                                <option value="other">Other (Specify)</option>
                            </select>
                            <span class="custom-dropdown-icon">&#9662;</span>
                        </div>
                    </div>
                        
                    <div class="form-group mb-3" id="customShiftGroup" style="display:none; margin: 1px;">
                        <label class="form-label">Custom Shift (e.g., 01:00:00 - 10:00:00)</label>
                        <input type="text" name="custom_shift" class="form-control" id="customShift" placeholder="HH:MM:SS - HH:MM:SS">
                    </div>
                    
                    <div class="form-group mb-3" style="margin: 1px;">
                        <label class="form-label">Reason for Change</label>
                        <div class="custom-select-wrapper">
                            <select class="form-control custom-select" name="reason" onchange="toggleCustomReason(this)" required style="cursor: pointer;">
                                <option value="">-- Select Reason --</option>
                                <option value="Employee Request">Employee Request</option>
                                <option value="Management Discretion">Management Discretion</option>
                                <option value="Organizational Restructuring">Organizational Restructuring</option>
                                <option value="Performance Related">Performance Related</option>
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
                
    // Update input when option is selected
    employeeSelect.addEventListener("change", () => {
        const selectedOption = employeeSelect.options[employeeSelect.selectedIndex];
        searchInput.value = selectedOption.innerText;
        employeeSelect.style.display = "none"; // Hide dropdown after selection
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
</script>