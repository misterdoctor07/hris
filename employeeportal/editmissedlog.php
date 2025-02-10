<?php
$id=$_GET["id"];
$sqlMissedlog=mysqli_query($con,"SELECT * FROM missed_log_application WHERE id='$id'");
$missedlog=mysqli_fetch_array($sqlMissedlog);

$userId = $_SESSION['idno'];
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $logId = $_GET['id'];
    $sqlMissedLogDetails = mysqli_query($con, "SELECT * FROM missed_log_application WHERE id='$logId'");
    if ($sqlMissedLogDetails && mysqli_num_rows($sqlMissedLogDetails) > 0) {
        $MissedLogDetails = mysqli_fetch_array($sqlMissedLogDetails);
        $datemissed = $MissedLogDetails['datemissed'];
        $incident = $MissedLogDetails['incident'];
        $time = date('H:i', strtotime($MissedLogDetails['mttime'])); // Convert to HH:mm format
        $reason = $MissedLogDetails['reason'];
    } else {
        echo "<script>alert('Missedlog application not found!');</script>";
        echo "<script>window.location='?applymissedlog';</script>";
        return;
    }
} else {
    echo "<script>alert('Missed log ID not provided!');</script>";
    echo "<script>window.location='?applymissedlog';</script>";
    return;
}
?>
<script type="text/javascript">
      function SubmitDetails(){        
          return confirm('Do you wish to submit details?');        
      }
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?applymissedlog"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-book"></i> UPDATE MISSED LOG APPLICATION</h4>      
    </div>
    </div>
    <form class="form-horizontal style-form" method="POST" onSubmit="return SubmitDetails();">
      <input type="hidden" name="editmissedlog">            
      <input type="hidden" name="addedby" value="<?=$fullname;?>">  
      <input type="hidden" name="id" value="<?=$id;?>">  
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">                
                <input type="submit" name="submit" class="btn btn-primary" value="Submit Details" style="float:right;">
              <h4><i class="fa fa-file-book"></i> UPDATE MISSED LOG DETAILS</h4>            
            </div>
            <div class="panel-body">                                            
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Date of Missed Time IN/OUT</label>
          <div class="col-sm-8">
            <input type="date" name="datemissed" class="form-control" value="<?=$datemissed;?>" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label" for="incident">Incident:</label>
          <div class="col-sm-8">
            <select class="form-control" name="incident" id="incident" required>
              <option value="" disabled selected>Select incident</option>
              <option value="IN" <?= ($incident == 'IN') ? 'selected' : ''; ?>>IN</option>
              <option value="Lunch Out" <?= ($incident == 'Lunch Out') ? 'selected' : ''; ?>>Lunch Out</option>
              <option value="Lunch In" <?= ($incident == 'Lunch In') ? 'selected' : ''; ?>>Lunch In</option>
              <option value="OUT" <?= ($incident == 'OUT') ? 'selected' : ''; ?>>Out</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Time</label>
          <div class="col-sm-8">
            <input type="time" name="mttime" class="form-control" value="<?=$time;?>" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Reason(s)</label>
          <div class="col-sm-8">
            <textarea name="reason" class="form-control" rows="5" required><?=$reason;?></textarea>
          </div>
        </div>                
      </div>
          </div>
          <!-- col-lg-12-->
        </div>                
        </form>
<?php
    if(isset($_POST['submit']) && isset($_POST['editmissedlog'])) {
        // Retrieve logged-in user's ID from the session
        $idno = $_SESSION['idno'];
        $logId = $_GET['id']; // Get the ID from the hidden input
        // Form data from the POST request
        $addedby = $_POST['addedby'];
        $datemissed = $_POST['datemissed'];
        $incident = $_POST['incident'];
        $mttime = $_POST['mttime'];
        $reason = $_POST['reason'];
    
        // Automatically get current date and time for date_applied and time_applied
        $date_applied = date('Y-m-d'); // Current date
        $time_applied = date('H:i:s'); // Current time
    
        $sqlUpdateMissedLog = mysqli_query($con, "UPDATE missed_log_application 
                                            SET idno = '$idno',
                                                datemissed = '$datemissed',
                                                incident = '$incident',
                                                mttime = '$mttime',
                                                reason = '$reason',
                                                date_applied = '$date_applied',
                                                time_applied = '$time_applied',
                                                applic_status = 'Pending'
                                            WHERE id = '$logId'");

        if ($sqlUpdateMissedLog) {
            echo "<script>alert('Missed Log application updated successfully!');
                  window.location.href='?applymissedlog';</script>";
        } else {
            echo "<script>alert('Error updating missed log application: " . mysqli_error($con) . "');</script>";
        }
    }
?>