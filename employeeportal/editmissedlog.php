<?php
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

  if (!isset($_SESSION['idno'])) {
      die("<script>alert('Session expired. Please log in again.'); window.location='/index.php';</script>");
  }

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
      function confirmCancel() {
          if (confirm("Are you sure you want to cancel? Any unsaved changes will be lost.")) {
              window.location.href = "applymissedlog.php";
          }
      }
</script>
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
<div class="centered-container">
  <form class="form-horizontal style-form" method="POST" onSubmit="return SubmitDetails();" style="width: 100%; max-width: 500px;">
      <input type="hidden" name="editmissedlog">            
      <input type="hidden" name="addedby" value="<?=$fullname;?>">  
      <input type="hidden" name="id" value="<?=$id;?>">  
            <div class="content-panel">
              <div class="panel-heading d-flex align-items-center justify-content-between" style="position: relative;">
                <h4 class="mb-0 mx-auto text-center" style="flex: 1;">EDIT FILED MISSED LOG</h4>
            </div>
            <div class="panel-body">                                            
        <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
          <label class="form-label">Date of Missed Time IN/OUT</label>
          <input type="date" name="datemissed" class="form-control" value="<?=$datemissed;?>" required>
        </div>
        <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
          <label class="form-label" for="incident">Incident:</label>
          <div class="custom-select-wrapper">
            <select class="form-control custom-select" name="incident" id="incident" required style="cursor: pointer;">
              <option value="" disabled selected>Select incident</option>
              <option value="IN" <?= ($incident == 'IN') ? 'selected' : ''; ?>>IN</option>
              <option value="Lunch Out" <?= ($incident == 'Lunch Out') ? 'selected' : ''; ?>>Lunch Out</option>
              <option value="Lunch In" <?= ($incident == 'Lunch In') ? 'selected' : ''; ?>>Lunch In</option>
              <option value="OUT" <?= ($incident == 'OUT') ? 'selected' : ''; ?>>Out</option>
            </select>
            <span class="custom-dropdown-icon">&#9662;</span>
          </div>
        </div>
        <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
          <label class="form-label">Time</label>
          <input type="time" name="mttime" class="form-control" value="<?=$time;?>" required>
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
                  window.location.href='applymissedlog.php';</script>";
        } else {
            echo "<script>alert('Error updating missed log application: " . mysqli_error($con) . "');</script>";
        }
    }
?>