<?php
    $idno=$_GET['idno'];
    $sqlProfile=mysqli_query($con,"SELECT * FROM employee_profile WHERE idno='$idno'");
    $profile=mysqli_fetch_array($sqlProfile);
    $lastname=$profile['lastname'];
    $firstname=$profile['firstname'];
    $suffix=$profile['suffix'];
    
    $sqlChecklist=mysqli_query($con,"SELECT * FROM employee_details WHERE idno='$idno'");
    if(mysqli_num_rows($sqlChecklist)>0){
        $checklist=mysqli_fetch_array($sqlChecklist);
        $company=$checklist['company'];
        $department=$checklist['department'];
        $designation=$checklist['designation'];
        $startshift=$checklist['startshift'];
        $endshift=$checklist['endshift'];    
        $current_shift_type = $checklist['shift_type'];
    }else{
        $company="";
        $department="";
        $designation="";
        $startshift="";
        $endshift="";    
        $current_shift_type="";
    }
    $sqlDeparment=mysqli_query($con,"SELECT department FROM department WHERE id='$department'");
    if(mysqli_num_rows($sqlDeparment)>0){
        $dept=mysqli_fetch_array($sqlDeparment);
        $deptname=$dept['department'];
    }else{
        $deptname="";
    }
    $sqlDeparment=mysqli_query($con,"SELECT companyname FROM settings WHERE companycode='$company'");
    if(mysqli_num_rows($sqlDeparment)>0){
        $dept=mysqli_fetch_array($sqlDeparment);
        $companyname=$dept['companyname'];
    }else{
        $companyname="";
    }
    $sqlDeparment=mysqli_query($con,"SELECT jobtitle FROM jobtitle WHERE id='$designation'");
    if(mysqli_num_rows($sqlDeparment)>0){
        $dept=mysqli_fetch_array($sqlDeparment);
        $jobtitle=$dept['jobtitle'];
    }else{
        $jobtitle="";
    }
?>
<script type="text/javascript">
    function SubmitDetails(){        
        return confirm('Do you wish to submit details?');        
    }
</script>
<div class="row">
    <div class="col-lg-12">
        <h4 style="text-indent: 10px;"><a href="?manageemployee"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-users"></i> EMPLOYEE MOVEMENT TRACKER (<i class="fa fa-user"></i> <?=$lastname;?>, <?=$firstname;?> <?=$suffix;?>)</h4>      
    </div>
</div>
<form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
    <input type="hidden" name="employeemovement">            
    <input type="hidden" name="addedby" value="<?=$fullname;?>">      
    <input type="hidden" name="idno" value="<?=$idno;?>">
    <input type="hidden" name="companyid" value="<?=$company;?>">      
    <div class="col-lg-4 mt">
        <div class="content-panel">
            <div class="panel-heading">                
                <input type="submit" name="submitCompany" class="btn btn-primary" value="Save Details" style="float:right;"> 
                <h4><i class="fa fa-building-o"></i> COMPANY MOVEMENT</h4>             
            </div>
            <div class="panel-body">                                            
                <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Previous Company</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="companyname" value="<?=$companyname;?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">New Company</label>
                    <div class="col-sm-7">
                        <select name="companynew" class="form-control" required>
                            <option value=""></option>
                        <?php
                            $sqlCompany=mysqli_query($con,"SELECT companycode,companyname FROM settings WHERE status='Active' ORDER BY companycode ASC");
                            if(mysqli_num_rows($sqlCompany)>0){
                                while($comp=mysqli_fetch_array($sqlCompany)){
                                    echo "<option value='$comp[companycode]'>$comp[companyname]</option>";
                                }
                            }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Effectivity</label>
                    <div class="col-sm-7">
                        <input type="date" class="form-control" name="effectivity" required>
                    </div>
                </div>                                          
            </div>
        </div>
    </div>                
</form>
<form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
    <input type="hidden" name="employeemovement">            
    <input type="hidden" name="addedby" value="<?=$fullname;?>">      
    <input type="hidden" name="idno" value="<?=$idno;?>">
    <input type="hidden" name="deptid" value="<?=$department;?>">
    <div class="col-lg-4 mt">
        <div class="content-panel">
            <div class="panel-heading">                
                <input type="submit" name="submitDepartment" class="btn btn-primary" value="Save Details" style="float:right;">
                <h4><i class="fa fa-building"></i> DEPARTMENT MOVEMENT</h4>            
            </div>
            <div class="panel-body">                                            
                <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Previous Department</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="deptname" value="<?=$deptname;?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">New Department</label>
                    <div class="col-sm-7">
                        <select name="departmentnew" class="form-control" required>
                            <option value=""></option>
                            <?php
                                $sqlCompany=mysqli_query($con,"SELECT * FROM department ORDER BY department ASC");
                                if(mysqli_num_rows($sqlCompany)>0){
                                    while($comp=mysqli_fetch_array($sqlCompany)){
                                        echo "<option value='$comp[id]'>$comp[department]</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Effectivity</label>
                    <div class="col-sm-7">
                        <input type="date" class="form-control" name="effectivity" required>
                    </div>
                </div>                
            </div>
        </div>
    </div>                
</form>
<form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
    <input type="hidden" name="employeemovement">            
    <input type="hidden" name="addedby" value="<?=$fullname;?>">      
    <input type="hidden" name="idno" value="<?=$idno;?>">
    <input type="hidden" name="jobid" value="<?=$designation;?>">
    <div class="col-lg-4 mt">
        <div class="content-panel">
            <div class="panel-heading">                
                <input type="submit" name="submitJobTitle" class="btn btn-primary" value="Save Details" style="float:right;">
                <h4><i class="fa fa-book"></i> JOB TITLE MOVEMENT</h4>            
            </div>
            <div class="panel-body">                                            
                <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Previous Job Position</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="jobname" value="<?=$jobtitle;?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">New Job Position</label>
                    <div class="col-sm-7">
                        <select name="jobtitle" class="form-control" required>
                            <option value=""></option>
                            <?php
                                $sqlCompany=mysqli_query($con,"SELECT * FROM jobtitle ORDER BY jobtitle ASC");
                                if(mysqli_num_rows($sqlCompany)>0){
                                    while($comp=mysqli_fetch_array($sqlCompany)){
                                        echo "<option value='$comp[id]'>$comp[jobtitle]</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Effectivity</label>
                    <div class="col-sm-7">
                        <input type="date" class="form-control" name="effectivity" required>
                    </div>
                </div> 
            </div>
        </div>
    </div>                
</form>
<form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
    <input type="hidden" name="employeemovement">            
    <input type="hidden" name="addedby" value="<?=$fullname;?>">      
    <input type="hidden" name="idno" value="<?=$idno;?>">      
    <div class="col-lg-4 mt">
        <div class="content-panel">
            <div class="panel-heading">                
                <input type="submit" name="submitShift" class="btn btn-primary" value="Save Details" style="float:right;">
                <h4><i class="fa fa-clock-o"></i> SHIFT MOVEMENT</h4>            
            </div>
            <div class="panel-body">                                            
                <div class="form-group">
                    <label class="col-sm-3 control-label">Previous Shift</label>
                    <div class="input-group input-large col-lg-8" data-date="01/01/2014" data-date-format="mm/dd/yyyy">
                        <input type="time" class="form-control" name="previousfrom" value="<?=$startshift;?>">
                        <span class="input-group-addon">To</span>
                        <input type="time" class="form-control" name="previousto" value="<?=$endshift;?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">New Shift</label>
                    <div class="input-group input-large col-lg-8">
                        <input type="time" class="form-control" name="newfrom" id="newfrom" required>
                        <span class="input-group-addon">To</span>
                        <input type="time" class="form-control" name="newto" id="newto" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 col-sm-3 control-label">Shift Type</label>
                    <div class="col-sm-5">
                        <select name="shift_type" class="form-control" id="shift_type" required>
                            <option value="morning" <?=($current_shift_type == 'morning') ? 'selected' : ''?>>Morning Shift</option>
                            <option value="night" <?=($current_shift_type == 'night') ? 'selected' : ''?>>Night Shift</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Effectivity</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" name="effectivity" required>
                    </div>
                </div> 
            </div>
        </div>
    </div>                
</form>
<form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
    <input type="hidden" name="employeemovement">            
    <input type="hidden" name="addedby" value="<?=$fullname;?>">      
    <input type="hidden" name="idno" value="<?=$idno;?>">      
    <div class="col-lg-4 mt">
        <div class="content-panel">
            <div class="panel-heading">                
                <input type="submit" name="submitResignation" class="btn btn-primary" value="Save Details" style="float:right;">
                <h4><i class="fa fa-sign-out"></i> RESIGN</h4>            
            </div>
            <div class="panel-body">      
                <div class="form-group">
                    <label class="col-sm-3 control-label">Resignation Date</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" name="dateresigned">
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-3 control-label">Reason/s</label>
                    <div class="col-sm-8">
                        <textarea name="reason" class="form-control" rows="4"></textarea>
                    </div>
                </div> 
            </div>
        </div>
    </div>                
</form>
<script>
    function applySuggestedShift(select) {
        if(select.value) {
            var parts = select.value.split('-');
            document.getElementById('newfrom').value = parts[0];
            document.getElementById('newto').value = parts[1];
            
            // Automatically set the shift type
            var shiftType = select.options[select.selectedIndex].getAttribute('data-type');
            document.getElementById('shift_type').value = shiftType;
        }
    }
</script>
<?php
    if (isset($_GET['submitCompany'])) {
        $idno = $_GET['idno'];
        $addedby = $_GET['addedby'];
        $datenow = date('Y-m-d H:i:s');
        $previous = $_GET['companyid'];
        $new = $_GET['companynew'];
        $effectivity = $_GET['effectivity'];
    
        // Insert into movement tracker
        $sqlInsert = mysqli_query($con, "
            INSERT INTO movement_tracker (idno, companyfrom, companyto, effectivitydate, addedby, addeddatetime)
            VALUES ('$idno', '$previous', '$new', '$effectivity', '$addedby', '$datenow')
        ");
    
        if ($sqlInsert) {
            // If effectivity is today or earlier, apply immediately
            $today = date('Y-m-d');
            if ($effectivity <= $today) {
                mysqli_query($con, "
                    UPDATE employee_details 
                    SET company = '$new' 
                    WHERE idno = '$idno'
                ");
            }
    
            echo "<script>alert('Movement scheduled!'); window.location='?employeemovement&idno=$idno';</script>";
        } else {
            echo "<script>alert('Unable to save movement!'); window.location='?employeemovement&idno=$idno';</script>";
        }
    }

    if (isset($_GET['submitDepartment'])) {
        $idno = $_GET['idno'];
        $addedby = $_GET['addedby'];
        $datenow = date('Y-m-d H:i:s');
        $previous = $_GET['deptid'];
        $new = $_GET['departmentnew'];
        $effectivity = $_GET['effectivity'];
    
        $sqlInsert = mysqli_query($con, "
            INSERT INTO movement_tracker (idno, departmentfrom, departmentto, effectivitydate, addedby, addeddatetime)
            VALUES ('$idno', '$previous', '$new', '$effectivity', '$addedby', '$datenow')
        ");
    
        if ($sqlInsert) {
            $today = date('Y-m-d');
            if ($effectivity <= $today) {
                mysqli_query($con, "
                    UPDATE employee_details 
                    SET department = '$new' 
                    WHERE idno = '$idno'
                ");
            }
    
            echo "<script>alert('Department movement scheduled!'); window.location='?employeemovement&idno=$idno';</script>";
        } else {
            echo "<script>alert('Unable to save department movement!'); window.location='?employeemovement&idno=$idno';</script>";
        }
    }
    
    if (isset($_GET['submitJobTitle'])) {
        $idno = $_GET['idno'];
        $addedby = $_GET['addedby'];
        $datenow = date('Y-m-d H:i:s');
        $previous = $_GET['jobid'];
        $new = $_GET['jobtitle'];
        $effectivity = $_GET['effectivity'];
    
        $sqlInsert = mysqli_query($con, "
            INSERT INTO movement_tracker (idno, jobfrom, jobto, effectivitydate, addedby, addeddatetime)
            VALUES ('$idno', '$previous', '$new', '$effectivity', '$addedby', '$datenow')
        ");
    
        if ($sqlInsert) {
            $today = date('Y-m-d');
            if ($effectivity <= $today) {
                mysqli_query($con, "
                    UPDATE employee_details 
                    SET designation = '$new' 
                    WHERE idno = '$idno'
                ");
            }
    
            echo "<script>alert('Job title movement scheduled!'); window.location='?employeemovement&idno=$idno';</script>";
        } else {
            echo "<script>alert('Unable to save job title movement!'); window.location='?employeemovement&idno=$idno';</script>";
        }
    }
    
    if (isset($_GET['submitShift'])) {
        $idno = $_GET['idno'];
        $addedby = $_GET['addedby'];
        $datenow = date('Y-m-d H:i:s');
        $previousfrom = $_GET['previousfrom'];
        $previousto = $_GET['previousto'];
        $newfrom = $_GET['newfrom'];
        $newto = $_GET['newto'];
        $shift_type = $_GET['shift_type'];
        $effectivity = $_GET['effectivity'];
    
        $shiftfrom = "$previousfrom-$previousto";
        $shiftto = "$newfrom-$newto";
    
        $sqlInsert = mysqli_query($con, "
            INSERT INTO movement_tracker (idno, shiftfrom, shiftto, shift_type, effectivitydate, addedby, addeddatetime)
            VALUES ('$idno', '$shiftfrom', '$shiftto', '$shift_type', '$effectivity', '$addedby', '$datenow')
        ");
    
        if ($sqlInsert) {
            $today = date('Y-m-d');
            if ($effectivity <= $today) {
                mysqli_query($con, "
                    UPDATE employee_details 
                    SET startshift = '$newfrom', endshift = '$newto', shift_type = '$shift_type'
                    WHERE idno = '$idno'
                ");
            }
    
            echo "<script>alert('Shift movement scheduled!'); window.location='?employeemovement&idno=$idno';</script>";
        } else {
            echo "<script>alert('Unable to save shift movement! ".mysqli_error($con)."'); window.location='?employeemovement&idno=$idno';</script>";
        }
    }
    
    if(isset($_GET['submitResignation'])){        
        $idno=$_GET['idno'];
        $addedby=$_GET['addedby'];        
        $dateresigned=$_GET['dateresigned'];
        $reasons=$_GET['reason'];             
            $table="resignation(idno,dateresigned,reason)";
            $values="VALUES('$idno','$dateresigned','$reasons')";
            $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");        
        if($sqlAddEmployee){
            $sqlResign=mysqli_query($con,"UPDATE employee_details SET `status`='RESIGNED' WHERE idno='$idno'");
            echo "<script>";
                echo "alert('Details successfully saved!');window.location='?employeemovement&idno=$idno';";
            echo "</script>";
        }else{
            echo "<script>";
                echo "alert('Unable to save details!');window.location='?employeemovement&idno=$idno';";
            echo "</script>";
        }
    }
  ?>