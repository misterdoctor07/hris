<style>
/* Ensure proper alignment */
.checkbox-container {
  display: flex;
  align-items: center;
  gap: 10px;
}

.checkbox-group {
  display: flex;
  gap: 20px;
}

.checkbox-item {
  display: flex;
  align-items: center;
  gap: 5px;
}

/* Ensure label stays aligned with the radio button */
.checkbox-label {
  font-size: 14px;
  color: #555;
  cursor: pointer;
  margin-top: 2px; /* Ensures it aligns properly */
}
</style>

<?php
$id=$_GET["id"];
$sqlEEO=mysqli_query($con,"SELECT * FROM emergencyearlyout WHERE id='$id'");
$EEO=mysqli_fetch_array($sqlEEO);

$userId = $_SESSION['idno'];
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $logId = $_GET['id'];
    $sqlEEODetails = mysqli_query($con, "SELECT * FROM emergencyearlyout WHERE id='$logId'");
    if ($sqlEEODetails && mysqli_num_rows($sqlEEODetails) > 0) {
        $EEODetails = mysqli_fetch_array($sqlEEODetails);
        $type_EEO = $EEODetails['type_EEO'];
        $dateEEO = $EEODetails['dateEEO'];
        $timeEEO = date('H:i', strtotime($EEODetails['timeEEO'])); // Convert to HH:mm format
        $reason = $EEODetails['reason'];
    } else {
        echo "<script>alert('EEO application not found!');</script>";
        echo "<script>window.location='?emergencyearlyout';</script>";
        return;
    }
} else {
    echo "<script>alert('EEO ID not provided!');</script>";
    echo "<script>window.location='?emergencyearlyout';</script>";
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
      <h4 style="text-indent: 10px;"><a href="?emergencyearlyout"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-book"></i> UPDATE EMERGENCY EARLY OUT APPLICATION</h4>      
    </div>
    </div>
    <form class="form-horizontal style-form" method="POST" onSubmit="return SubmitDetails();">
      <input type="hidden" name="editeeo">            
      <input type="hidden" name="addedby" value="<?=$fullname;?>">  
      <input type="hidden" name="id" value="<?=$id;?>">  
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">                
                <input type="submit" name="submit" class="btn btn-primary" value="Submit Details" style="float:right;">
              <h4><i class="fa fa-file-book"></i> UPDATE EEO DETAILS</h4>            
            </div>
            <div class="panel-body">   
            <div class="form-group">
                <label class="col-sm-4 col-sm-4 control-label">Type of EEO</label>
                <div class="col-sm-8">
                    <label style="font-size: 14px;">
                        <input type="radio" name="eeo_type" value="Medical" <?= ($type_EEO == 'Medical') ? 'checked' : ''; ?> required>
                        Medical
                    </label>
                    <label style="margin-left: 15px; font-size: 14px;">
                        <input type="radio" name="eeo_type" value="Non-medical" <?= ($type_EEO == 'Non-medical') ? 'checked' : ''; ?> required>
                        Non-medical
                    </label>
                </div>
            </div>                                  
                <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Date of EEO</label>
                    <div class="col-sm-8">
                        <input type="date" name="dateEEO" class="form-control" value="<?=$dateEEO;?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Time of EEO</label>
                    <div class="col-sm-8">
                        <input type="time" name="timeEEO" class="form-control" value="<?=$timeEEO;?>" required>
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
    if(isset($_POST['submit'])) {
        // Retrieve logged-in user's ID from the session
        $idno = $_SESSION['idno'];
        
        // Form data from the POST request
        $dateEEO = $_POST['dateEEO'];
        $timeEEO = $_POST['timeEEO'];
        $reason = $_POST['reason'];
        $type_EEO = $_POST['eeo_type'];
    
        // Automatically get current date and time for date_applied and time_applied
        $date_applied = date('Y-m-d'); // Current date
        $time_applied = date('H:i:s'); // Current time
        $status = 'Pending'; // Default status
    
        // SQL query to update data into the missed_log_application table
        $sqlUpdateEEO = mysqli_query($con, "UPDATE emergencyearlyout 
                                            SET idno = '$idno',
                                                dateEEO = '$dateEEO',
                                                timeEEO = '$timeEEO',
                                                type_EEO = '$type_EEO',
                                                reason = '$reason',
                                                date_applied = '$date_applied',
                                                time_applied = '$time_applied',
                                                eeo_status = '$status'
                                            WHERE id = '$id'");
    
        // Check if the query was successful
        if($sqlUpdateEEO) {
            echo "<script>";
            echo "alert('Details successfully saved!'); window.location='?emergencyearlyout';";
            echo "</script>";
        } else {
            echo "<script>";
            echo "alert('Unable to save details!'); window.location='?emergencyearlyout';";
            echo "</script>";
        }
    }
?>

<script>
  function validateForm(event) {
    const selectedEEO = document.querySelector('input[name="eeo_type"]:checked');
    
    if (!selectedEEO) {
      alert("Please select a Type of EEO before submitting.");
      event.preventDefault(); // Prevents form submission
      return false;
    }
  }

  // Attach event listener when the DOM loads
  document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("form").addEventListener("submit", validateForm);
  });
</script>