<script type="text/javascript">
  function SubmitDetails(){        
      return confirm('Do you wish to submit details?');        
  }
</script>

<div class="row">
  <div class="col-lg-12">
    <h4 style="text-indent: 10px;"><a href="?applymissedlog"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-book"></i> MISSED LOG APPLICATION</h4>      
  </div>
</div>

<!-- Form starts here -->
<form class="form-horizontal style-form" method="POST" onSubmit="return SubmitDetails();">
  <input type="hidden" name="addmissedlog">            
  <input type="hidden" name="addedby" value="<?=$fullname;?>">          
  <div class="col-lg-4 mt">
    <div class="content-panel">
      <div class="panel-heading">                
        <input type="submit" name="submit" class="btn btn-primary" value="Submit Details" style="float:right;">
        <h4><i class="fa fa-file-book"></i> APPLY FOR MISSED LOG</h4>            
      </div>
      <div class="panel-body">                                            
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Date of Missed Time IN/OUT</label>
          <div class="col-sm-8">
            <input type="date" name="datemissed" class="form-control" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label" for="incident">Incident:</label>
          <div class="col-sm-8">
            <select class="form-control" name="incident" id="incident" required>
              <option value="" disabled selected>Select incident</option>
              <option value="IN">IN</option>
              <option value="Lunch Out">Lunch Out</option>
              <option value="Lunch In">Lunch In</option>
              <option value="Out">Out</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Time</label>
          <div class="col-sm-8">
            <input type="time" name="mttime" class="form-control" required>
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
if(isset($_POST['submit'])) {
    // Retrieve logged-in user's ID from the session
    $idno = $_SESSION['idno'];
    
    // Form data from the POST request
    $addedby = $_POST['addedby'];
    $datemissed = $_POST['datemissed'];
    $incident = $_POST['incident'];
    $mttime = $_POST['mttime'];
    $reason = isset($_POST['reason']) ? urldecode($_POST['reason']) : ''; // Decode the input
    $reason = mysqli_real_escape_string($con, $reason); // Sanitize for SQL
    
    // Automatically get current date and time for date_applied and time_applied
    $date_applied = date('Y-m-d'); // Current date
    $time_applied = date('H:i:s'); // Current time
    $status = 'Pending'; // Default status
    
    // SQL query to insert data into the missed_log_application table
    $query = "INSERT INTO missed_log_application (idno, datemissed, incident, mttime, reason, date_applied, time_applied, applic_status)
              VALUES ('$idno', '$datemissed', '$incident', '$mttime', '$reason', '$date_applied', '$time_applied', 'Pending')";
    
    // Execute the query
    $sqlAddEmployee = mysqli_query($con, $query);
    
    // Check if the query was successful
    if($sqlAddEmployee) {
        echo "<script>";
        echo "alert('Details successfully saved!'); window.location='?addmissedlog';";
        echo "</script>";
    } else {
        echo "<script>";
        echo "alert('Unable to save details!'); window.location='?addmissedlog';";
        echo "</script>";
    }
}
?>
