<?php
$id=$_GET['id'];
$sqlProfile=mysqli_query($con,"SELECT * FROM employee_profile WHERE id='$id'");
$profile=mysqli_fetch_array($sqlProfile);
$idno=$profile['idno'];
$lastname=$profile['lastname'];
$firstname=$profile['firstname'];
$suffix=$profile['suffix'];

$sqlChecklist=mysqli_query($con,"SELECT * FROM leave_credits WHERE idno='$idno'");
if(mysqli_num_rows($sqlChecklist)>0){
    $checklist=mysqli_fetch_array($sqlChecklist);
    $vacation=$checklist['vacationleave']??0;
    $vlused=$checklist['vlused']??0;
    $sick=$checklist['sickleave']??0;
    $slused=$checklist['slused']??0;
    $pto=$checklist['pto']??0;
    $ptoused=$checklist['ptoused']??0; 
    $bdleave=$checklist['bdayleave']??0;
    $bdused=$checklist['blp_used']??0;
    $jan_eaout=$checklist['jan_earlyout']??0;
    $jan_eaused=$checklist['jan_eo_used']??0;
    $feb_eaout=$checklist['feb_earlyout']??0;
    $feb_eaused=$checklist['feb_eo_used']??0;
    $mar_eaout=$checklist['mar_earlyout']??0;
    $mar_eaused=$checklist['mar_eo_used']??0;
    $apr_eaout=$checklist['apr_earlyout']??0;
    $apr_eaused=$checklist['apr_eo_used']??0;
    $may_eaout=$checklist['may_earlyout']??0;
    $may_eaused=$checklist['may_eo_used']??0;
    $jun_eaout=$checklist['jun_earlyout']??0;
    $jun_eaused=$checklist['jun_eo_used']??0;
    $jul_eaout=$checklist['jul_earlyout']??0;
    $jul_eaused=$checklist['jul_eo_used']??0;
    $aug_eaout=$checklist['aug_earlyout']??0;
    $aug_eaused=$checklist['aug_eo_used']??0;
    $sep_eaout=$checklist['sep_earlyout']??0;
    $sep_eaused=$checklist['sep_eo_used']??0;
    $oct_eaout=$checklist['oct_earlyout']??0;
    $oct_eaused=$checklist['oct_eo_used']??0;
    $nov_eaout=$checklist['nov_earlyout']??0;
    $nov_eaused=$checklist['nov_eo_used']??0;
    $dec_eaout=$checklist['dec_earlyout']??0;
    $dec_eaused=$checklist['dec_eo_used']??0;
   $spl=$checklist['spl']??0;
    $splused=$checklist['spl_used']??0;
} else {
  $vacation="";
  $vlused="";
  $sick="";
  $slused="";
  $pto="";
  $ptoused="";
  $bdleave="";
  $bdused="";
  $jan_eaout="";
  $jan_eaused= "";
  $feb_eaout="";
  $feb_eaused= "";
  $mar_eaout="";
  $mar_eaused= "";
  $apr_eaout="";
  $apr_eaused= "";
  $may_eaout="";
  $may_eaused= "";
  $jun_eaout="";
  $jun_eaused= "";
  $jul_eaout="";
  $jul_eaused= "";
  $aug_eaout="";
  $aug_eaused= "";
  $sep_eaout="";
  $sep_eaused= "";
  $oct_eaout="";
  $oct_eaused= "";
  $nov_eaout="";
  $nov_eaused= "";
  $dec_eaout="";
  $dec_eaused= "";
  $spl="";
  $splused="";
}

// Re-run the query to retrieve the updated data
$sql = "SELECT * FROM leave_credits WHERE idno = '$idno'";
$result = mysqli_query($con, $sql);
$checklist = mysqli_fetch_array($result);

// $vacation = $checklist['vacationleave'] - $checklist['vlused'];
// $sick = $checklist['sickleave'] - $checklist['slused'];
// $pto = $checklist['pto'] - $checklist['ptoused'];
// $bdleave = $checklist['bdayleave'] - $checklist['blp_used'];
// $eaout = $checklist['earlyout'] - $checklist['eo_used'];
// $spls = $checklist['spl'] - $checklist['spl_used'];

?>
<script type="text/javascript">
      function SubmitDetails(){        
          return confirm('Do you wish to submit details?');        
      }
    </script>
    
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="javascript:history.back();"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-text"></i> EMPLOYEE LEAVE CREDITS</h4>      
    </div>
    </div>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="viewleave">            
      <input type="hidden" name="addedby" value="<?=$fullname;?>">
      <input type="hidden" name="id" value="<?=$id;?>">
      <input type="hidden" name="idno" value="<?=$idno;?>">
      <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">                
                <input type="submit" name="submit" class="btn btn-primary" value="Save Details" style="float:right;">
              <h4><i class="fa fa-user"></i> <?=$lastname;?>, <?=$firstname;?> <?=$suffix;?></h4>            
            </div>
            <div class="panel-body">                                            
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">VL Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="vacation" style="text-align:center;" value="<?=$vacation;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">VL Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="vacationused" style="text-align:center;" value="<?=$vlused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">SL Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="sick" style="text-align:center;" value="<?=$sick;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">SL Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="sickused" style="text-align:center;" value="<?=$slused;?>">
                  </div>
                </div>                
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">PTO Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="pto" style="text-align:center;" value="<?=$pto;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">PTO Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="ptoused" style="text-align:center;" value="<?=$ptoused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">BLP Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="bdayleave" style="text-align:center;" value="<?=$bdleave;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">BLP Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="blp_used" style="text-align:center;" value="<?=$bdused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Spl Credits</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="spl" style="text-align:center;" value="<?=$spl;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Spl Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="spl_used" style="text-align:center;" value="<?=$splused;?>">
                  </div>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
      </div> 
      
      <div class="col-lg-4 mt">
          <div class="content-panel">
            <div class="panel-body">                                            
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">January EO Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="jan_earlyout" style="text-align:center;" value="<?=$jan_eaout;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">January EO Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="jan_eo_used" style="text-align:center;" value="<?=$jan_eaused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">February EO Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="feb_earlyout" style="text-align:center;" value="<?=$feb_eaout;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">February EO Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="feb_eo_used" style="text-align:center;" value="<?=$feb_eaused;?>">
                  </div>
                </div>                
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">March EO Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="mar_earlyout" style="text-align:center;" value="<?=$mar_eaout;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">March EO Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="mar_eo_used" style="text-align:center;" value="<?=$mar_eaused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">April EO Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="apr_earlyout" style="text-align:center;" value="<?=$apr_eaout;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">April EO Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="apr_eo_used" style="text-align:center;" value="<?=$apr_eaused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">May EO Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="may_earlyout" style="text-align:center;" value="<?=$may_eaout;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">May EO Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="may_eo_used" style="text-align:center;" value="<?=$may_eaused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">June EO Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="jun_earlyout" style="text-align:center;" value="<?=$jun_eaout;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">June EO Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="jun_eo_used" style="text-align:center;" value="<?=$jun_eaused;?>">
                  </div>
                </div>
            </div>
          </div>
        </div>

          <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-body">                                            
              <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">July EO Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="jul_earlyout" style="text-align:center;" value="<?=$jul_eaout;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">July EO Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="jul_eo_used" style="text-align:center;" value="<?=$jul_eaused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">August EO Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="aug_earlyout" style="text-align:center;" value="<?=$aug_eaout;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">August EO Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="aug_eo_used" style="text-align:center;" value="<?=$aug_eaused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">September EO Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="sep_earlyout" style="text-align:center;" value="<?=$sep_eaout;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">September EO Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="sep_eo_used" style="text-align:center;" value="<?=$sep_eaused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">October EO Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="oct_earlyout" style="text-align:center;" value="<?=$oct_eaout;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">October EO Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="oct_eo_used" style="text-align:center;" value="<?=$oct_eaused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">November EO Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="nov_earlyout" style="text-align:center;" value="<?=$nov_eaout;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">November EO Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="nov_eo_used" style="text-align:center;" value="<?=$nov_eaused;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">December EO Credit</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="dec_earlyout" style="text-align:center;" value="<?=$dec_eaout;?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">December EO Used</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="dec_eo_used" style="text-align:center;" value="<?=$dec_eaused;?>">
                  </div>
                </div>
              </div>
            </div>
          </div>
    </form>
  <?php
    if(isset($_GET['submit'])){
        $id=$_GET['id'];
        $idno=$_GET['idno'];
        $addedby=$_GET['addedby'];
        $datenow=date('Y-m-d H:i:s');
        $vecay=$_GET['vacation'];
        $sicky=$_GET['sick'];
        $pty=$_GET['pto'];  
        $vlused=$_GET['vacationused'];      
        $slused=$_GET['sickused'];
        $ptoused=$_GET['ptoused'];
        $bdayleave=$_GET['bdayleave'];
        $blp_used=$_GET['blp_used'];
        $jan_earlyout=$_GET['jan_earlyout'];
        $jan_eo_used=$_GET['jan_eo_used'];
        $feb_earlyout=$_GET['feb_earlyout'];
        $feb_eo_used=$_GET['feb_eo_used'];
        $mar_earlyout=$_GET['mar_earlyout'];
        $mar_eo_used=$_GET['mar_eo_used'];
        $apr_earlyout=$_GET['apr_earlyout'];
        $apr_eo_used=$_GET['apr_eo_used'];
        $may_earlyout=$_GET['may_earlyout'];
        $may_eo_used=$_GET['may_eo_used'];
        $jun_earlyout=$_GET['jun_earlyout'];
        $jun_eo_used=$_GET['jun_eo_used'];
        $jul_earlyout=$_GET['jul_earlyout'];
        $jul_eo_used=$_GET['jul_eo_used'];
        $aug_earlyout=$_GET['aug_earlyout'];
        $aug_eo_used=$_GET['aug_eo_used'];
        $sep_earlyout=$_GET['sep_earlyout'];
        $sep_eo_used=$_GET['sep_eo_used'];
        $oct_earlyout=$_GET['oct_earlyout'];
        $oct_eo_used=$_GET['oct_eo_used'];
        $nov_earlyout=$_GET['nov_earlyout'];
        $nov_eo_used=$_GET['nov_eo_used'];
        $dec_earlyout=$_GET['dec_earlyout'];
        $dec_eo_used=$_GET['dec_eo_used'];
        $spl=$_GET['spl'];
        $spl_used=$_GET['spl_used'];

        $sqlCheck=mysqli_query($con,"SELECT * FROM leave_credits WHERE idno='$idno'");
        if(mysqli_num_rows($sqlCheck)>0){
            $table="leave_credits";
            $values="SET vacationleave='$vecay',vlused='$vlused',sickleave='$sicky',slused='$slused',pto='$pty',ptoused='$ptoused',bdayleave='$bdayleave', 
            blp_used='$blp_used', jan_earlyout='$jan_earlyout', jan_eo_used='$jan_eo_used', spl='$spl',  spl_used='$spl_used'

            ,updatedby='$addedby',updateddatetime='$datenow' WHERE idno='$idno'";
            $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");
        }else{
            $table="leave_credits(idno,vacationleave,vlused,sickleave,slused,pto,ptoused,bdayleave,blp_used,jan_earlyout,jan_eo_used,spl,spl_used,addedby,addeddatetime)";
            $values="VALUES('$idno','$vecay','$vlused','$sicky','$slused','$pty','$ptoused','$bdayleave','$blp_used','$jan_earlyout','$jan_eo_used','$spl','$spl_used','$addedby','$datenow')";
            $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");
        }
      if($sqlAddEmployee){
        echo "<script>";
          echo "alert('Details successfully saved!');window.location='?viewleave&id=$id';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to saved details!');window.location='?viewleave&id=$id';";
        echo "</script>";
      }
    }
  ?>