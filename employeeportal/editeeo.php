<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['idno'])) {
        die("<script>alert('Session expired. Please log in again.'); window.location='/index.php';</script>");
    }
?>
<style>
    body{
        background-color: #f0f2f5
    }
    .centered-container {
        display: flex;
        justify-content: center;
        padding: 20px;
    }

    .content-panel {
        background-color: #fff;
        border-radius: 30px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px;
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
    function confirmCancel() {
        if (confirm("Are you sure you want to cancel? Any unsaved changes will be lost.")) {
            window.location.href = "emergencyearlyout.php";
        }
    }
</script>
<div class="centered-container">
  <form class="form-horizontal style-form" method="POST" onSubmit="return SubmitDetails();" style="width: 100%; max-width: 500px;">
      <input type="hidden" name="editeeo">            
      <input type="hidden" name="addedby" value="<?=$fullname;?>">  
      <input type="hidden" name="id" value="<?=$id;?>">  
      <div class="content-panel">
          <div class="panel-heading d-flex align-items-center justify-content-between" style="position: relative;">
              <h4 class="mb-0 mx-auto text-center" style="flex: 1;">EDIT FILED EEO</h4>
          </div>
          <div class="panel-body">   
              <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                  <label class="form-label">Type of EEO</label>
                    <div class="radio-group" style="margin-left: 8px;">
                        <input type="radio" id="medical" name="eeo_type" value="Medical" class="radio-input"
                            <?= ($type_EEO === 'Medical') ? 'checked' : ''; ?>>
                        <label for="medical" class="radio-label" style="font-size: 14px; margin-right: 15px;">Medical</label>

                        <input type="radio" id="non-medical" name="eeo_type" value="Non-medical" class="radio-input"
                            <?= ($type_EEO === 'Non-medical') ? 'checked' : ''; ?>>
                        <label for="non-medical" class="radio-label" style="font-size: 14px;">Non-medical</label>
                    </div>
              </div>                                        
              <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                  <label class="form-label">Date of EEO</label>
                  <input type="date" name="dateEEO" class="form-control" value="<?=$dateEEO;?>" required>
              </div>
              <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                  <label class="form-label">Time of EEO</label>
                  <input type="time" name="timeEEO" class="form-control" value="<?=$timeEEO;?>" required>
              </div>
              <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                  <label class="form-label">Reason(s)</label>
                  <textarea name="reason" class="form-control" rows="5" required><?=$reason;?></textarea>
              </div> 
              <div class="text-center" style="margin: 10px; margin-top: 70px;">
                  <!-- Cancel Button (no form submit) -->
                  <button type="button" class="btn btn-danger" style="width: 80px; border-radius: 20px; height: 40px;" onclick="confirmCancel()">
                      Cancel
                  </button>
              
                  <!-- Submit Button (form submit) -->
                  <input type="submit" id="submitBtn" name="submit" class="btn btn-success" value="Save Changes"
                      style="width: 200px; border-radius: 20px; height: 40px;">
              </div>               
          </div>
      </div>               
  </form>
</div>
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
            echo "alert('Details successfully saved!'); window.location='emergencyearlyout.php';";
            echo "</script>";
        } else {
            echo "<script>";
            echo "alert('Unable to save details!'); window.location='emergencyearlyout.php';";
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