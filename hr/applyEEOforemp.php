
<script type="text/javascript">
  function SubmitDetails() {        
      return confirm('Do you wish to submit details?');        
  }
</script>

<?php
include '../config.php'; 
$idno = $_GET['idno'] ?? '';
?>
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

<div class="row">
  <div class="col-lg-12">
    <h4 style="text-indent: 10px;"><a href="?manageemployee"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-book"></i> EMERGENCY EARLY OUT APPLICATION</h4>      
  </div>
</div>

<!-- Form starts here -->
<form class="form-horizontal style-form" method="POST" onSubmit="return SubmitDetails();">
  <input type="hidden" name="idno" value="<?php echo $idno; ?>">  
  <input type="hidden" name="applyEEOforemp">            
  <input type="hidden" name="addedby" value="<?=$fullname;?>">          
  <div class="col-lg-4 mt">
    <div class="content-panel">
      <div class="panel-heading">                
        <input type="submit" name="submit" class="btn btn-primary" value="Submit Details" style="float:right;">
        <h4><i class="fa fa-book"></i> APPLY FOR EMERGENCY EARLY OUT</h4>            
      </div>
      <div class="panel-body">    
        <div class="form-group">
            <label class="col-sm-4 col-sm-4 control-label">Type of EEO</label>
            <div class="col-sm-8">
                <input id="toggle" class="toggle" type="checkbox" name="eeo_type" value="Medical" onchange="updateLabelText(this)">
                <label for="toggle" class="slot"></label>
                <span id="label-text" class="label-text">Non-medical</span>
            </div>
        </div>                                      
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Date of EEO</label>
          <div class="col-sm-8">
            <input type="date" name="dateEEO" class="form-control" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label" for="incident">Time of EEO</label>
          <div class="col-sm-8">
            <input type="time" name="timeEEO" class="form-control" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Reason(s)</label>
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
    // Retrieve logged-in user's ID
    $idno = $_POST['idno'];
    
    // Form data from the POST request
    $dateEEO = $_POST['dateEEO'];
    $timeEEO = $_POST['timeEEO'];
    $reason = $_POST['reason'];
    $eeo_type = isset($_POST['eeo_type']) ? $_POST['eeo_type'] : 'Non-medical'; // Default to Non-medical if unchecked

    // Automatically get current date and time for date_applied and time_applied
    $date_applied = date('Y-m-d'); // Current date
    $time_applied = date('H:i:s'); // Current time
    $status = 'Pending'; // Default status

    // SQL query to insert data into the emergencyearlyout table
    $query = "INSERT INTO emergencyearlyout (idno, dateEEO, timeEEO, reason, type_EEO, date_applied, time_applied, eeo_status)
              VALUES ('$idno', '$dateEEO', '$timeEEO', '$reason', '$eeo_type', '$date_applied', '$time_applied', '$status')";

    // Execute the query
    $sqlAddEmployee = mysqli_query($con, $query);

    // Check if the query was successful
    if ($sqlAddEmployee) {
        echo "<script>";
        echo "alert('Details successfully saved!'); window.location='?applyEEOforemp';";
        echo "</script>";
    } else {
        echo "<script>";
        echo "alert('Unable to save details!'); window.location='?applyEEOforemp';";
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