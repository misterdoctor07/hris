<script type="text/javascript">
  function SubmitDetails() {        
      return confirm('Do you wish to submit details?');        
  }
</script>

<div class="row">
  <div class="col-lg-12">
    <h4 style="text-indent: 10px;">
      <a href="?leaveprotocols"><i class="fa fa-arrow-left"></i> BACK</a> | 
      <i class="fa fa-file-text"></i> LEAVE PROTOCOLS
    </h4>      
  </div>
</div>

<form class="form-horizontal style-form" method="POST" onSubmit="return SubmitDetails();">
  <input type="hidden" name="addleaveprotocols">            
  <input type="hidden" name="addedby" value="<?= htmlspecialchars($fullname); ?>">

  <div class="col-lg-10 mt">
    <div class="content-panel">
      <div class="panel-heading">                
        <input type="submit" name="submit" class="btn btn-primary" value="Save Details" style="float:right;">
        <h4><i class="fa fa-file-text"></i> ADD LEAVE PROTOCOLS</h4>            
      </div>
      <div class="panel-body">                                                        

        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Title of Approving Officer</label>
          <div class="col-sm-7">
            <select name="titleapprovingofficer" id="titleapprovingofficer" class="form-control" required>
              <option value="">Select Title</option>
              <?php
              $sqlEmployee = mysqli_query($con, "SELECT * FROM jobtitle ORDER BY jobtitle ASC");
              while ($employee = mysqli_fetch_array($sqlEmployee)) {
                  echo "<option value='". htmlspecialchars($employee['id']) ."'>". htmlspecialchars($employee['jobtitle']) ."</option>";
              }
              ?>
            </select>
          </div>
        </div>

        <!-- Approving Officer -->
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Approving Officer</label>
          <div class="col-sm-7">
            <select name="approvingofficer[]" id="approvingofficer" class="form-control" multiple required>
              <!-- Options will be loaded dynamically via AJAX -->
            </select>
          </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
          $(document).ready(function() {
            $('#titleapprovingofficer').change(function() {
              var jobTitleId = $(this).val();
              if (jobTitleId) {
                console.log("Job Title ID: " + jobTitleId); // Debugging statement
                $.ajax({
                  url: 'fetch_employees.php',
                  method: 'POST',
                  data: { jobTitleId: jobTitleId },
                  success: function(data) {
                console.log("Response Data: " + data); // Debugging statement
                $('#approvingofficer').html(data);

                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error: " + textStatus + ": " + errorThrown);
                    $('#approvingofficer').html('<option value="">Error loading officers</option>');
                  }
                });
              } else {
                $('#approvingofficer').html('<option value="">Select Approving Officer</option>');
              }
            });
          });
        </script>
        <!-- Company -->
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Company</label>
          <div class="col-sm-7">
            <div class="col-sm-12">
              <table width="100%" border="0">
                <tr>
                  <?php
                  $x = 1;
                  $sqlCompany = mysqli_query($con, "SELECT * FROM settings ORDER BY companycode ASC");
                  while ($company = mysqli_fetch_array($sqlCompany)) {                                                         
                      echo "<td><input type='checkbox' name='company[]' value='". htmlspecialchars($company['companycode']) ."'> ". htmlspecialchars($company['companycode']) ."</td>";
                      if ($x == 3) {
                          echo "</tr><tr>";
                          $x = 1;
                      } else {
                          $x++;   
                      }                            
                  }
                  ?>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <!-- Shift -->
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Shift</label>
          <div class="col-sm-7">
            <div class="col-sm-12">
              <table width="100%" border="0">
                <tr>
                  <?php
                  $x = 1;
                  $displayedShifts = []; // Array to track displayed shifts
                  $sqlShift = mysqli_query($con, "SELECT DISTINCT startshift FROM employee_details ORDER BY startshift ASC"); // DISTINCT to filter duplicates in query
                  while ($shift = mysqli_fetch_array($sqlShift)) {
                      if (!in_array($shift['startshift'], $displayedShifts)) { // Check if shift is already displayed
                          echo "<td><input type='checkbox' name='shift[]' value='" . htmlspecialchars($shift['startshift']) . "'> " . htmlspecialchars($shift['startshift']) . "</td>";
                          $displayedShifts[] = $shift['startshift']; // Add shift to displayed array
                          if ($x == 3) {
                              echo "</tr><tr>";
                              $x = 1;
                          } else {
                              $x++;
                          }
                      }
                  }
                  ?>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <!-- Department -->
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Department</label>
          <div class="col-sm-7">
            <div class="col-sm-12">
              <table width="100%" border="0">
                <tr>
                  <?php
                  $x = 1;
                  $sqlDepartments = mysqli_query($con, "SELECT * FROM department ORDER BY department ASC");
                  while ($departments = mysqli_fetch_array($sqlDepartments)) {                                                         
                      echo "<td><input type='checkbox' name='department[]' value='". htmlspecialchars($departments['id']) ."'> ". htmlspecialchars($departments['department']) ."</td>";
                      if ($x == 3) {
                          echo "</tr><tr>";
                          $x = 1;
                      } else {
                          $x++;   
                      }                            
                  }
                  ?>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <!-- Subordinates -->
        <div class="form-group">
          <label class="col-sm-4 col-sm-4 control-label">Subordinates</label>
          <div class="col-sm-5">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-12">
            <table width="100%" border="0">
              <tr>
                <?php
                $x=1;
                $sqlJobtitle=mysqli_query($con,"SELECT * FROM jobtitle ORDER BY jobtitle ASC");
                if(mysqli_num_rows($sqlJobtitle)>0){
                    while($jobtitle=mysqli_fetch_array($sqlJobtitle)){                                                         
                        echo "<td><input type='checkbox' name='requestor[]' value='$jobtitle[id]'> $jobtitle[jobtitle]</td>";
                        if($x==3){
                            echo "</tr>";
                            $x=1;
                        }else{
                            $x++;   
                        }                            
                    }
                }
                ?>
              </tr>
            </table>
          </div>
        </div>                 
      </div>
    </div>
  </div>                
</form>

<?php
if (isset($_POST['submit'])) {
    $approvingofficers = $_POST['approvingofficer'] ?? [];
    $requestors = $_POST['requestor'] ?? [];
    $companies = $_POST['company'] ?? [];
    $departments = $_POST['department'] ?? [];
    $shifts = $_POST['shift'] ?? [];

    if (!empty($approvingofficers)) {
        $success = false; // Track success

        // Ensure non-empty arrays for looping
        $requestors = !empty($requestors) ? $requestors : [null];
        $companies = !empty($companies) ? $companies : [null];
        $departments = !empty($departments) ? $departments : [null];
        $shifts = !empty($shifts) ? $shifts : [null];

        // Generate all possible combinations
        foreach ($approvingofficers as $officer) {
            foreach ($companies as $comp) {
                foreach ($departments as $dept) {
                    foreach ($requestors as $subordinate) {
                      foreach ($shifts as $shift) {
                        // Escape strings for SQL
                        $officer = mysqli_real_escape_string($con, $officer);
                        $comp = !is_null($comp) ? mysqli_real_escape_string($con, $comp) : null;
                        $dept = !is_null($dept) ? mysqli_real_escape_string($con, $dept) : null;
                        $subordinate = !is_null($subordinate) ? mysqli_real_escape_string($con, $subordinate) : null;
                        $shift = !is_null($shift) ? mysqli_real_escape_string($con, $shift) : null;

                        // Insert combination into database
                        $sql = "INSERT INTO leave_protocols (approvingofficer, company, department, requestingofficer, shift) 
                                VALUES ('$officer', " . ($comp ? "'$comp'" : "NULL") . ", " . ($dept ? "'$dept'" : "NULL") . ", " . ($subordinate ? "'$subordinate'" : "NULL") . ", " . ($shift ? "'$shift'" : "NULL") . ")";
                        if (mysqli_query($con, $sql)) {
                            $success = true;
                        } else {
                            error_log("Error: " . mysqli_error($con));
                        }
                      }
                    }
                }
            }
        }

        // Final success/failure message
        if ($success) {
            echo "<script>alert('Details successfully saved!');window.location='?addleaveprotocols';</script>";
        } else {
            echo "<script>alert('Error saving details!');window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No approving officers selected!');window.history.back();</script>";
    }
}
?>


