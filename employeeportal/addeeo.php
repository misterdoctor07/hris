<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['idno'])) {
        die("<script>alert('Session expired. Please log in again.'); window.location='/index.php';</script>");
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
      <input type="hidden" name="addeeo">            
      <input type="hidden" name="addedby" value="<?=$fullname;?>"> 
      <div class="content-panel">
          <div class="panel-heading d-flex align-items-center justify-content-between" style="position: relative;">
                <h4 class="mb-0 mx-auto text-center" style="flex: 1;">APPLY EEO</h4>
                <a href="emergencyearlyout.php" style="color: white; position: absolute; right: 15px;">
                    <i class="fa fa-times" style="cursor: pointer;"></i>
                </a>
            </div>
          <div class="panel-body">    
              <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                  <label class="form-label">Type of EEO</label>
                  <div class="radio-group" style="margin-left: 8px;">
                      <input type="radio" id="medical" name="eeo_type" value="Medical" class="radio-input">
                      <label for="medical" class="radio-label" style="font-size: 14px; margin-right: 15px;">Medical</label>
                      <input type="radio" id="non-medical" name="eeo_type" value="Non-medical" class="radio-input">
                      <label for="non-medical" class="radio-label" style="font-size: 14px;">Non-medical</label>
                  </div>
              </div>                       
              <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                  <label class="form-label">Date of EEO</label>
                  <input type="date" name="dateEEO" class="form-control" required>
              </div>
              <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                  <label class="form-label">Time of EEO</label>
                  <input type="time" name="timeEEO" class="form-control" required>
              </div>
              <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                  <label class="form-label">Reason(s)</label>
                  <textarea name="reason" class="form-control" rows="5" required></textarea>
              </div> 
              <div class="panel-footer text-center" style="margin: 10px;">
                  <input type="submit" id="submitBtn" name="submit" class="btn btn-success" value="Submit Details" style="width: 300px; border-radius: 20px; height: 40px;">
              </div>             
          </div>
      </div>
  </form>
</div>
<?php
    if (isset($_POST['submit'])) {
        // Retrieve logged-in user's ID from the session
        $idno = $_SESSION['idno'];
        
        // Form data from the POST request
        $dateEEO = $_POST['dateEEO'];
        $timeEEO = $_POST['timeEEO'];
        $reason = isset($_POST['reason']) ? urldecode($_POST['reason']) : ''; // Decode the input
        $reason = mysqli_real_escape_string($con, $reason); // Sanitize for SQL
        $eeo_type = isset($_POST['eeo_type']) ? $_POST['eeo_type'] : null; // If nothing is selected, store NULL
    
        // Automatically get current date and time for date_applied and time_applied
        $date_applied = date('Y-m-d'); // Current date
        $time_applied = date('H:i:s'); // Current time
        $status = 'Pending'; // Default status
    
        // SQL query to insert data into the emergencyearlyout table
        $query = "INSERT INTO emergencyearlyout (idno, dateEEO, timeEEO, reason, type_EEO, date_applied, time_applied, eeo_status)
                  VALUES ('$idno', '$dateEEO', '$timeEEO', '$reason', " . ($eeo_type ? "'$eeo_type'" : "NULL") . ", '$date_applied', '$time_applied', '$status')";
    
        // Execute the query
        $sqlAddEmployee = mysqli_query($con, $query);
    
        // Check if the query was successful
        if ($sqlAddEmployee) {
            echo "<script>";
            echo "alert('Details successfully saved!'); window.location='emergencyearlyout.php';";
            echo "</script>";
        } else {
            echo "<script>";
            echo "alert('Unable to save details!'); window.location='dashboard.php?addeeo';";
            echo "</script>";
        }
    }
?>