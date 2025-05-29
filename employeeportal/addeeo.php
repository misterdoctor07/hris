<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['idno'])) {
    die("<script>alert('Session expired. Please log in again.'); window.location='/index.php';</script>");
}
?><script>
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

<div class="row">
  <div class="col-lg-12">
    <h4 style="text-indent: 10px;"><a href="?emergencyearlyout"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-book"></i> EMERGENCY EARLY OUT APPLICATION</h4>      
  </div>
</div>

<!-- Form starts here -->
<form class="form-horizontal style-form" method="POST" onSubmit="return SubmitDetails();">
  <input type="hidden" name="addeeo">            
  <input type="hidden" name="addedby" value="<?=$fullname;?>">          
  <div class="col-lg-4 mt">
    <div class="content-panel">
      <div class="panel-heading">                
        <input type="submit" name="submit" class="btn btn-primary" value="Submit Details" style="float:right;">
        <h4><i class="fa fa-book"></i> APPLY FOR EMERGENCY EARLY OUT</h4>            
      </div>
      <div class="panel-body">    
        <div class="form-group">
            <label class="col-sm-4 control-label">Type of EEO</label>
            <div class="col-sm-8">
                <div class="radio-group">
                    <input type="radio" id="medical" name="eeo_type" value="Medical" class="radio-input">
                    <label for="medical" class="radio-label" style="font-size: 14px; margin-right: 15px;">Medical</label>

                    <input type="radio" id="non-medical" name="eeo_type" value="Non-medical" class="radio-input">
                    <label for="non-medical" class="radio-label" style="font-size: 14px;">Non-medical</label>
                </div>
            </div>
        </div>                       

        <div class="form-group">
          <label class="col-sm-4 control-label">Date of EEO</label>
          <div class="col-sm-8">
            <input type="date" name="dateEEO" class="form-control" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Time of EEO</label>
          <div class="col-sm-8">
            <input type="time" name="timeEEO" class="form-control" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Reason(s)</label>
          <div class="col-sm-8">
            <textarea name="reason" class="form-control" rows="5" required></textarea>
          </div>
        </div>            
      </div>
    </div>
  </div>
</form>

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