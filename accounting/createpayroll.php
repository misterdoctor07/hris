<script type="text/javascript">
  function SubmitDetails() {        
    return confirm('Do you wish to submit details?');        
  }
</script>

<div class="row">
  <div class="col-lg-12">
    <h4 style="text-indent: 10px;"><a href="?main"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-money"></i> CREATE PAYROLL</h4>      
  </div>
</div>

<form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
  <input type="hidden" name="createpayroll">            
  <input type="hidden" name="addedby" value="<?=$fullname;?>">          

  <div class="col-lg-4 mt">
    <div class="content-panel">
      <div class="panel-heading">                
        <input type="submit" name="submit" class="btn btn-primary" value="Proceed" style="float:right;">
        <h4><i class="fa fa-file-text-o"></i> PAYROLL DETAILS</h4>            
      </div>
      <div class="panel-body">                                                                        
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Period From</label>
          <div class="col-sm-5">
            <input type="date" class="form-control" name="startdate" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Period To</label>
          <div class="col-sm-5">
            <input type="date" class="form-control" name="enddate" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Period</label>
          <div class="col-sm-5">
            <select name="period" class="form-control">
              <option value="mid">Mid of the Month</option>
              <option value="end">End of the Month</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Company</label>
          <div class="col-sm-8">
            <select name="company" class="form-control" required>
              <option value=""></option>
              <?php
              $sqlPeriod = mysqli_query($con,"SELECT * FROM settings");
              if (mysqli_num_rows($sqlPeriod) > 0) {
                  while ($period = mysqli_fetch_array($sqlPeriod)) {
                      echo "<option value='$period[companycode]'>$period[companyname]</option>";
                  }
              }
              ?>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>                
</form>

<?php
if (isset($_GET['submit'])) {                
  $addedby = $_GET['addedby'];
  $datenow = date('Y-m-d H:i:s');
  $dateissued = $_GET['startdate'];
  $dateserved = $_GET['enddate'];  
  $selectedPeriod = $_GET['period'];
  $company = $_GET['company'];

  $startDate = new DateTime($dateissued);
  $endDate = new DateTime($dateserved);
  $endDate->modify('+1 day');

  $interval = new DateInterval('P1D');
  $datePeriod = new DatePeriod($startDate, $interval, $endDate);

  $count = 0;
  foreach ($datePeriod as $date) {
      $dayOfWeek = $date->format('N');
      if ($dayOfWeek != 1 && $dayOfWeek != 7) {
          $count++;
      }
  }

  $sqlCheck = mysqli_query($con,"SELECT * FROM payroll WHERE periodfrom='$dateissued' AND periodto='$dateserved'");
  if (mysqli_num_rows($sqlCheck) > 0) {
      $payroll = mysqli_fetch_array($sqlCheck);
      echo "<script>window.location='?managepayroll&period={$payroll['id']}&company=$company';</script>";
  } else {
      $table = "payroll(periodfrom,periodto,period,days,addedby,addeddatetime)";
      $values = "VALUES('$dateissued','$dateserved','$selectedPeriod','$count','$addedby','$datenow')";
      $sqlAddEmployee = mysqli_query($con,"INSERT INTO $table $values");

      if ($sqlAddEmployee) {
          $sqlCheck = mysqli_query($con,"SELECT * FROM payroll WHERE periodfrom='$dateissued' AND periodto='$dateserved'");
          if (mysqli_num_rows($sqlCheck) > 0) {
              $payroll = mysqli_fetch_array($sqlCheck);
              echo "<script>window.location='?managepayroll&period={$payroll['id']}&company=$company';</script>";
          }
      } else {
          echo "<script>alert('Unable to save details!');window.location='?createpayroll';</script>";
      }
  }
}
?>