<?php
$id=$_GET['id'];
$company=$_GET['company'];
$sqlProfile=mysqli_query($con,"SELECT * FROM employee_profile WHERE idno='$id'");
$profile=mysqli_fetch_array($sqlProfile);
$idno=$profile['idno'];
$lastname=$profile['lastname'];
$firstname=$profile['firstname'];
$suffix=$profile['suffix'];

$sqlChecklist=mysqli_query($con,"SELECT * FROM employee_payroll WHERE idno='$idno'");
if(mysqli_num_rows($sqlChecklist)>0){
    $checklist=mysqli_fetch_array($sqlChecklist);
    $sss=$checklist['sss'];
    $phic=$checklist['phic'];
    $hdmf=$checklist['hdmf'];
    $salary=$checklist['salary'];
    $philcare=$checklist['philcare'];
    $generali=$checklist['generali'];
    $fwd=$checklist['fwd'];
    $tax=$checklist['tax'];
    $otherbenefits=$checklist['otherbenefits'];
    $salary_type = $checklist['salary_type']; // Fetch the salary type
}else{
    $sss="";
    $phic="";
    $hdmf="";
    $salary="";
    $philcare="";
    $generali="";
    $fwd="";
    $tax="";
    $otherbenefits="";
    $salary_type = 'Rated'; // Default value
}
?>
<script type="text/javascript">
      function SubmitDetails(){
          return confirm('Do you wish to submit details?');
      }
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?managebenefits&company=<?=$company;?>"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-text"></i> EMPLOYEE BENEFITS</h4>
    </div>
    </div>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="editbenefits">
      <input type="hidden" name="addedby" value="<?=$fullname;?>">
      <input type="hidden" name="id" value="<?=$id;?>">
      <input type="hidden" name="company" value="<?=$company;?>">
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
                <input type="submit" name="submit" class="btn btn-primary" value="Save Details" style="float:right;">
              <h4><i class="fa fa-user"></i> <?=$lastname;?>, <?=$firstname;?> <?=$suffix;?></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">SSS Benefits</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="sss" style="text-align:right;" value="<?=$sss;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">PHIC Benefits</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="phic" style="text-align:right;" value="<?=$phic;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Pag-ibig Benefits</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="hdmf" style="text-align:right;" value="<?=$hdmf;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">PhilCare Benefits</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="philcare" style="text-align:right;" value="<?=$philcare;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Generali Benefits</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="generali" style="text-align:right;" value="<?=$generali;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">FWD Retirement Benefits</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="fwd" style="text-align:right;" value="<?=$fwd;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Withholding Tax</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="tax" style="text-align:right;" value="<?=$tax;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Other Benefits</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="otherbenefits" style="text-align:right;" value="<?=$otherbenefits;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Salary Rate/Day</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="salary" style="text-align:right;" value="<?=$salary;?>">
                  </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Salary Type</label>
                    <div class="col-sm-8">
                        <label class="radio-inline">
                            <input type="radio" name="salary_type" value="Rated" <?= ($checklist['salary_type'] ?? 'Rated') === 'Rated' ? 'checked' : ''; ?>> Rated
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="salary_type" value="Fixed" <?= ($checklist['salary_type'] ?? 'Rated') === 'Fixed' ? 'checked' : ''; ?>> Fixed
                        </label>
                    </div>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>
        </form>
  <?php
   if(isset($_GET['submit'])){
    $id=$_GET['id'];
    $addedby=$_GET['addedby'];
    $datenow=date('Y-m-d H:i:s');
    $sss=$_GET['sss'];
    $phic=$_GET['phic'];
    $hdmf=$_GET['hdmf'];
    $philcare=$_GET['philcare'];
    $generali=$_GET['generali'];
    $fwd=$_GET['fwd'];
    $tax=$_GET['tax'];
    $otherbenefits=$_GET['otherbenefits'];
    $salary=$_GET['salary'];
    $salary_type = $_GET['salary_type']; // Capture the salary type
    $sqlCheck=mysqli_query($con,"SELECT * FROM employee_payroll WHERE idno='$id'");
    if(mysqli_num_rows($sqlCheck)>0){
        $table="employee_payroll";
        $values="SET sss='$sss',phic='$phic',hdmf='$hdmf',philcare='$philcare',generali='$generali', fwd='$fwd', tax='$tax', otherbenefits='$otherbenefits',salary='$salary',salary_type='$salary_type',updatedby='$addedby',updateddatetime='$datenow' WHERE idno='$id'";
        $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");
    }else{
        $table="employee_payroll(idno,sss,phic,hdmf,philcare,generali,fwd,tax,otherbenefits,salary,salary_type,addedby,addeddatetime)";
        $values="VALUES('$idno','$sss','$phic','$hdmf','$philcare','$generali','$fwd','$tax','$otherbenefits','$salary','$salary_type','$addedby','$datenow')";
        $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");
    }
    if($sqlAddEmployee){
        echo "<script>";
        echo "alert('Details successfully saved!');window.location='?editbenefits&id=$id&company=$company';";
        echo "</script>";
    }else{
        echo "<script>";
        echo "alert('Unable to save details!');window.location='?editbenefits&id=$id&company=$company';";
        echo "</script>";
    }
}
  ?>
