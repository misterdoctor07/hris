<script type="text/javascript">
    function SubmitDetails(){
        return confirm('Do you wish to submit details?');
    }
</script>
<style>
    .centered-container {
        display: flex;
        justify-content: center;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }
    .content-panel-main {
        max-width: 3000px;
        width: 100%;
        border-radius: 15px;
    }

    .content-panel {
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 1000px;
        overflow: hidden;
        padding-top: 0px;
        margin-bottom:10px;
    }
    
    .panel-heading-main {
        display: flex; 
        align-items: center; 
        justify-content: space-between;
        padding-top: 20px;
    }

    .panel-heading {
        background-color: #21283a;
        color: white;
        padding: 5px 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .row {
        margin: 0px;
        padding: 5px 0 0 0;
    }

    .panel-footer {
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
        right: 13px;                /* Adjust this to control position */
        transform: translateY(-50%);
        pointer-events: none;       /* Allows click through to select */
    }

    .checkbox-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
    }
    
    .checkbox-column {
        flex: 1;
        min-width: 200px;
        max-width: 300px;
    }
    
    .form-check {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        margin-left: 55px;
    }
    
    .form-check-input {
        margin-right: 10px;
        flex-shrink: 0;
    }

    .form-check-label {
        margin: 7px 0 0 5px;
    }
</style>
<div class="centered-container">
    <div class="content-panel-main">
        <div class="panel-heading-main">
            <div class="flex-item-left" style="display: flex; align-items: center;">
                <div style="font-size: 14px; padding-left: 30px;">
                    DASHBOARD > EMPLOYEES > <strong style="font-size: 15px;">MONITOR ATTENDANCE</strong>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <!-- First Row - Two Columns -->
            <div class="row">
                <!-- Employee Logs -->
                <div class="col-lg-6">
                    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
                        <input type="hidden" name="attendancemonitoring">
                        <div class="content-panel">
                            <div class="panel-heading d-flex align-items-center justify-content-between" style="position: relative;">
                                <h4 class="mb-0 mx-auto text-center" style="flex: 1;"><i class="fa fa-user"></i> EMPLOYEE LOGS</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                                    <label class="form-label">Company</label>
                                    <div class="custom-select-wrapper">
                                        <select name="company" class="form-control custom-select" id="company" style="cursor: pointer;">
                                            <option value="">Select a company</option>
                                            <?php
                                            $sqlCompany=mysqli_query($con,"SELECT companycode,companyname FROM settings GROUP BY companycode");
                                            if(mysqli_num_rows($sqlCompany)>0){
                                                while($row = mysqli_fetch_array($sqlCompany)){
                                                echo "<option value='$row[companycode]'>$row[companyname]</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                        <span class="custom-dropdown-icon">&#9662;</span>
                                    </div>
                                </div>
                                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                                    <label class="form-label">Department</label>
                                    <div class="checkbox-container">
                                        <div class="checkbox-column">
                                            <?php
                                                $sqlDepartment = mysqli_query($con, "SELECT id, department FROM department GROUP BY id");
                                                if ($sqlDepartment === false) {
                                                    echo "Error in SQL query: " . mysqli_error($con);
                                                } elseif (mysqli_num_rows($sqlDepartment) > 0) {
                                                    $count = 0;
                                                    $total = mysqli_num_rows($sqlDepartment);
                                                    $itemsPerColumn = ceil($total / 3);
                                                    
                                                    while ($row = mysqli_fetch_array($sqlDepartment)) {
                                                        if ($count > 0 && $count % $itemsPerColumn === 0) {
                                                            echo '</div><div class="checkbox-column">';
                                                        }
                                                        ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="departments[]" 
                                                                id="dept-<?php echo $row['id']; ?>" 
                                                                value="<?php echo htmlspecialchars($row['id']); ?>">
                                                            <label class="form-check-label" for="dept-<?php echo $row['id']; ?>">
                                                                <?php echo htmlspecialchars($row['department']); ?>
                                                            </label>
                                                        </div>
                                                        <?php
                                                        $count++;
                                                    }
                                                } else {
                                                    echo "No departments found.";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                                    <label class="form-label">Date From</label>
                                    <input type="date" name="startdate" class="form-control">
                                </div>
                                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                                    <label class="form-label">Date To</label>
                                    <input type="date" name="enddate" class="form-control">
                                </div>
                                <div class="panel-footer text-center" style="margin: 10px;">
                                    <input type="submit" id="submitBtn" name="submit" class="btn btn-primary" value="Proceed" style="width: 200px; border-radius: 20px; height: 40px;">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Over Break Monitoring -->
                <div class="col-lg-6">
                    <form class="form-horizontal style-form" method="GET" action="breakmonitoringreport.php" onSubmit="return SubmitDetails();" target="_blank">
                        <div class="content-panel">
                            <div class="panel-heading d-flex align-items-center justify-content-between" style="position: relative;">
                                <h4 class="mb-0 mx-auto text-center" style="flex: 1;"><i class="fa fa-clock-o"></i> BREAK MONITORING</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                                    <label class="form-label">Company</label>
                                    <div class="custom-select-wrapper">
                                        <select name="company" class="form-control custom-select" id="company" style="cursor: pointer;">
                                            <option value="">Select a company</option>
                                            <?php
                                            $sqlCompany=mysqli_query($con,"SELECT companycode,companyname FROM settings GROUP BY companycode");
                                            if(mysqli_num_rows($sqlCompany)>0){
                                                while($row = mysqli_fetch_array($sqlCompany)){
                                                echo "<option value='$row[companycode]'>$row[companyname]</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                        <span class="custom-dropdown-icon">&#9662;</span>
                                    </div>
                                </div>
                                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                                    <label class="form-label">Department</label>
                                    <div class="checkbox-container">
                                        <div class="checkbox-column">
                                            <?php
                                                $sqlDepartment = mysqli_query($con, "SELECT id, department FROM department GROUP BY id");
                                                if ($sqlDepartment === false) {
                                                    echo "Error in SQL query: " . mysqli_error($con);
                                                } elseif (mysqli_num_rows($sqlDepartment) > 0) {
                                                    $count = 0;
                                                    $total = mysqli_num_rows($sqlDepartment);
                                                    $itemsPerColumn = ceil($total / 3);
                                                    
                                                    while ($row = mysqli_fetch_array($sqlDepartment)) {
                                                        if ($count > 0 && $count % $itemsPerColumn === 0) {
                                                            echo '</div><div class="checkbox-column">';
                                                        }
                                                        ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="departments[]" 
                                                                id="dept-<?php echo $row['id']; ?>" 
                                                                value="<?php echo htmlspecialchars($row['id']); ?>">
                                                            <label class="form-check-label" for="dept-<?php echo $row['id']; ?>">
                                                                <?php echo htmlspecialchars($row['department']); ?>
                                                            </label>
                                                        </div>
                                                        <?php
                                                        $count++;
                                                    }
                                                } else {
                                                    echo "No departments found.";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                                    <label class="form-label">Date From</label>
                                    <input type="date" name="startdate" class="form-control">
                                </div>
                                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                                    <label class="form-label">Date To</label>
                                    <input type="date" name="enddate" class="form-control">
                                </div>
                                <div class="panel-footer text-center" style="margin: 10px;">
                                    <input type="submit" id="submitBtn" name="submit" class="btn btn-primary" value="Proceed" style="width: 200px; border-radius: 20px; height: 40px;">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Second Row - Two Columns -->
            <div class="row">
                <!-- Missed Logs/OB Summary -->
                <div class="col-lg-6">
                    <form class="form-horizontal style-form" method="GET" action="attendancemonitoringsummarymissed.php" onSubmit="return SubmitDetails();" target="_blank">
                        <div class="content-panel">
                            <div class="panel-heading d-flex align-items-center justify-content-between" style="position: relative;">
                                <h4 class="mb-0 mx-auto text-center" style="flex: 1;"><i class="fa fa-calendar"></i> MISSED LOGS/OB SUMMARY</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                                    <label class="form-label">DEPARTMENT</label>
                                    <div class="custom-select-wrapper">
                                        <select name="dept" class="form-control custom-select" style="cursor: pointer;">
                                            <?php
                                                $sqlDepartment=mysqli_query($con,"SELECT companycode,companyname FROM settings GROUP BY companycode");
                                                if(mysqli_num_rows($sqlDepartment)>0){
                                                while($row=mysqli_fetch_array($sqlDepartment)){
                                                    echo "<option value='$row[companycode]'>$row[companyname]</option>";
                                                }
                                                }
                                            ?>
                                        </select>
                                        <span class="custom-dropdown-icon">&#9662;</span>
                                    </div>
                                </div>
                                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                                    <label class="form-label">START DATE</label>
                                    <input type="date" name="startdate" class="form-control">
                                </div>
                                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                                    <label class="form-label">END DATE</label>
                                    <input type="date" name="enddate" class="form-control">
                                </div>
                                <div class="panel-footer text-center" style="margin: 10px;">
                                    <input type="submit" id="submitBtn" name="submit" class="btn btn-primary" value="Proceed" style="width: 200px; border-radius: 20px; height: 40px;">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Attendance Summary (Absences/Late) -->
                <div class="col-lg-6">
                    <form class="form-horizontal style-form" method="GET" action="attendancemonitoringsummary.php" onSubmit="return SubmitDetails();" target="_blank">
                        <div class="content-panel">
                            <div class="panel-heading d-flex align-items-center justify-content-between" style="position: relative;">
                                <h4 class="mb-0 mx-auto text-center" style="flex: 1;"><i class="fa fa-calendar"></i> ATTENDANCE SUMMARY(ABSENCES/LATE)</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                                    <label class="form-label">DEPARTMENT</label>
                                    <div class="custom-select-wrapper">
                                        <select name="dept" class="form-control custom-select" style="cursor: pointer;">
                                            <?php
                                                $sqlDepartment=mysqli_query($con,"SELECT companycode,companyname FROM settings GROUP BY companycode");
                                                if(mysqli_num_rows($sqlDepartment)>0){
                                                while($row=mysqli_fetch_array($sqlDepartment)){
                                                    echo "<option value='$row[companycode]'>$row[companyname]</option>";
                                                }
                                                }
                                            ?>
                                        </select>
                                        <span class="custom-dropdown-icon">&#9662;</span>
                                    </div>
                                </div>
                                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                                    <label class="form-label">START DATE</label>
                                    <input type="date" name="startdate" class="form-control">
                                </div>
                                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                                    <label class="form-label">END DATE</label>
                                    <input type="date" name="enddate" class="form-control">
                                </div>
                                <div class="panel-footer text-center" style="margin: 10px;">
                                    <input type="submit" id="submitBtn" name="submit" class="btn btn-primary" value="Proceed" style="width: 200px; border-radius: 20px; height: 40px;">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>