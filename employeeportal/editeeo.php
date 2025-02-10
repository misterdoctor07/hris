<style>
  /* Hide the checkbox */
  .toggle {
    display: none;
  }

  /* Toggle container with proper alignment */
  .slot {
    display: inline-block;
    width: 50px;
    height: 24px;
    background: #ddd;
    border-radius: 30px;
    position: relative;
    cursor: pointer;
    vertical-align: middle;
    transition: background-color 0.3s;
  }

  /* Circle inside the toggle */
  .slot::before {
    content: '';
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    position: absolute;
    top: 2px;
    left: 2px;
    transition: all 0.3s ease;
  }

  /* Checked state styles */
  input.toggle:checked + .slot {
    background: #1e90ff;
  }

  input.toggle:checked + .slot::before {
    left: 28px;
  }

  /* Label styles */
  .label-text {
    font-size: 14px;
    color: #555;
    margin-left: 12px;
    vertical-align: middle;
    display: inline-block;
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
                        <!-- Hidden input to handle unchecked state -->
                        <input type="hidden" name="eeo_type" value="Non-medical">
                        
                        <input id="toggle" 
                            class="toggle" 
                            type="checkbox" 
                            name="eeo_type" 
                            value="Medical" 
                            <?= ($type_EEO == 'Medical') ? 'checked' : ''; ?> 
                            onchange="updateLabelText(this)">
                        <label for="toggle" class="slot"></label>
                        <span id="label-text" class="label-text">
                        <?= ($type_EEO == 'Medical') ? 'Medical' : 'Non-medical'; ?>
                        </span>
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
  function updateLabelText(toggle) {
    const labelText = document.getElementById("label-text");
    labelText.textContent = toggle.checked ? "Medical" : "Non-medical";
  }
</script>