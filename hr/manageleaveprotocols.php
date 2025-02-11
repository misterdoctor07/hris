<?php
  // Fetch the ID and name of the approving officer
  $approvingofficer = $_POST['approvingofficer'] ?? $_GET['approvingofficer'] ?? '';
  $sqlProtocol = $con->prepare("SELECT * FROM employee_profile WHERE idno = ?");
  $sqlProtocol->bind_param("s", $approvingofficer);
  $sqlProtocol->execute();
  $result = $sqlProtocol->get_result();
  $appofficer = $result->fetch_assoc();
  $appofficername = $appofficer ? $appofficer['lastname'] . ', ' . $appofficer['firstname'] : "";
  $sqlProtocol->close();
?>
<script type="text/javascript">
    function SubmitDetails(){        
      return confirm('Do you wish to submit details?');        
    }
</script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?leaveprotocols"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-text"></i> LEAVE PROTOCOLS</h4>      
    </div>
    </div>
    <form class="form-horizontal style-form" method="POST" onSubmit="return SubmitDetails();">
      <input type="hidden" name="manageleaveprotocols">            
      <input type="hidden" name="approvingofficer" value="<?= htmlspecialchars($approvingofficer); ?>">
    <div class="col-lg-10 mt">
            <div class="content-panel">
              <div class="panel-heading">                
                <input type="submit" name="submit" class="btn btn-primary" value="Save Details" style="float:right;">
                <h4><i class="fa fa-file-text"></i> UPDATE LEAVE PROTOCOLS</h4>            
              </div>
              <div class="panel-body">                                                        
                  <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Approving Officer  </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($appofficername); ?>" readonly>
                    </div>
                  </div>
                  <!-- Company -->
                  <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Company</label>
                    <div class="col-sm-7">
                      <div class="col-sm-12">
                        <table width="100%" border="0">
                          <tr>
                          <?php
                            $x = 1;

                            // Fetch selected company for the approving officer
                            $selectedCompany = [];
                            $sqlSelected = mysqli_query($con, "SELECT company FROM leave_protocols WHERE approvingofficer='$approvingofficer'");
                            while ($row = mysqli_fetch_array($sqlSelected)) {
                                $selectedCompany[] = $row['company'];
                            }

                            // Fetch all company 
                            $sqlCompany = mysqli_query($con, "SELECT * FROM settings ORDER BY companycode ASC");
                            if (mysqli_num_rows($sqlCompany) > 0) {
                                while ($company = mysqli_fetch_array($sqlCompany)) {
                                    // Check if this company id company is in the selectedRequestors array
                                    $status = in_array($company['companycode'], $selectedCompany) ? "checked" : "";
                                    
                                    echo "<td><input type='checkbox' name='company[]' value='$company[companycode]' $status> $company[companycode]</td>";
                                    
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
                   <!-- Shift -->
                   <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Shift</label>
                    <div class="col-sm-7">
                      <div class="col-sm-12">
                        <table width="100%" border="0">
                          <tr>
                          <?php
                            $x = 1;

                            // Fetch selected shifts for the approving officer
                            $selectedShift = [];
                            $sqlSelected = mysqli_query($con, "SELECT shift FROM leave_protocols WHERE approvingofficer='$approvingofficer'");
                            while ($row = mysqli_fetch_array($sqlSelected)) {
                                $selectedShift[] = $row['shift'];
                            }

                            // Fetch all Shift 
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

                            // Fetch selected department for the approving officer
                            $selectedDepartments = [];
                            $sqlSelected = mysqli_query($con, "SELECT department FROM leave_protocols WHERE approvingofficer='$approvingofficer'");
                            while ($row = mysqli_fetch_array($sqlSelected)) {
                                $selectedDepartments[] = $row['department'];
                            }

                            // Fetch all department
                            $sqlDepartment = mysqli_query($con, "SELECT * FROM department ORDER BY department ASC");
                            if (mysqli_num_rows($sqlDepartment) > 0) {
                                while ($department = mysqli_fetch_array($sqlDepartment)) {
                                    // Check if this department id is in the selectedRequestors array
                                    $status = in_array($department['id'], $selectedDepartments) ? "checked" : "";
                                    
                                    echo "<td><input type='checkbox' name='department[]' value='$department[id]' $status> $department[department]</td>";
                                    
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

                  <!-- Subordinates -->
                  <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Subordinates</label>
                    <div class="col-sm-5">
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <!-- <label class="col-sm-4 col-sm-4 control-label"></label> -->
                    <div class="col-sm-12">
                      <table width="100%" border="0">
                        <tr>
                          <?php
                          $x = 1;

                          // Fetch selected subordinates for the approving officer
                          $selectedRequestors = [];
                          $sqlSelected = mysqli_query($con, "SELECT requestingofficer FROM leave_protocols WHERE approvingofficer='$approvingofficer'");
                          while ($row = mysqli_fetch_array($sqlSelected)) {
                              $selectedRequestors[] = $row['requestingofficer'];
                          }

                          // Fetch all job titles
                          $sqlJobtitle = mysqli_query($con, "SELECT * FROM jobtitle ORDER BY jobtitle ASC");
                          if (mysqli_num_rows($sqlJobtitle) > 0) {
                              while ($jobtitle = mysqli_fetch_array($sqlJobtitle)) {
                                  // Check if this jobtitle id is in the selectedRequestors array
                                  $status = in_array($jobtitle['id'], $selectedRequestors) ? "checked" : "";
                                  
                                  echo "<td><input type='checkbox' name='requestor[]' value='$jobtitle[id]' $status> $jobtitle[jobtitle]</td>";
                                  
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
          </div>
          <!-- col-lg-12-->
        </div>                
        </form>
<?php
if (isset($_POST['submit'])) {
  // Force the inputs to always be arrays
  $approvingofficers = isset($_POST['approvingofficer']) ? (array)$_POST['approvingofficer'] : [];
  $requestors = isset($_POST['requestor']) ? (array)$_POST['requestor'] : [];
  $companies = isset($_POST['company']) ? (array)$_POST['company'] : [];
  $departments = isset($_POST['department']) ? (array)$_POST['department'] : [];
  $shifts = isset($_POST['shift']) ? (array)$_POST['shift'] : [];

  // Check if there are any approving officers
  if (!empty($approvingofficers)) {
      // Ensure non-empty arrays for looping
      $requestors = !empty($requestors) ? $requestors : [null];
      $companies = !empty($companies) ? $companies : [null];
      $departments = !empty($departments) ? $departments : [null];
      $shifts = !empty($shifts) ? $shifts : [null];

      foreach ($approvingofficers as $officer) {
          // Escape officer input
          $officer = mysqli_real_escape_string($con, $officer);

          // Fetch the full name of the officer
          $nameQuery = "SELECT CONCAT(lastname, ', ', firstname) AS fullname FROM employee_profile WHERE idno = '$officer'";
          $nameResult = mysqli_query($con, $nameQuery);
          $fullname = $nameResult && mysqli_num_rows($nameResult) > 0 ? mysqli_fetch_assoc($nameResult)['fullname'] : $officer;

          // Delete all existing combinations for this approver
          $deleteQuery = "DELETE FROM leave_protocols WHERE approvingofficer = '$officer'";
          mysqli_query($con, $deleteQuery);

          // Insert new combinations
          $success = false; // Track if any new combinations are added
          foreach ($companies as $comp) {
              foreach ($departments as $dept) {
                  foreach ($requestors as $subordinate) {
                    foreach ($shifts as $shift) {
                      $comp = $comp ? mysqli_real_escape_string($con, $comp) : null;
                      $dept = $dept ? mysqli_real_escape_string($con, $dept) : null;
                      $subordinate = $subordinate ? mysqli_real_escape_string($con, $subordinate) : null;
                      $shift = $shift ? mysqli_real_escape_string($con, $shift) : null;

                      $insertQuery = "INSERT INTO leave_protocols (approvingofficer, company, department, requestingofficer, shift) 
                                      VALUES ('$officer', " . ($comp ? "'$comp'" : "NULL") . ", " . ($dept ? "'$dept'" : "NULL") . ", " . ($subordinate ? "'$subordinate'" : "NULL") . ", " . ($shift ? "'$shift'" : "NULL") . ")";
                      if (mysqli_query($con, $insertQuery)) {
                          $success = true;
                      } else {
                          error_log("Error inserting combination: " . mysqli_error($con));
                      }
                    }
                  }
              }
          }

          // Feedback for each officer
          if ($success) {
              echo "<script>alert('Details for approving officer $fullname successfully updated!');</script>";
          } else {
              echo "<script>alert('No combinations were added for approving officer $fullname.');</script>";
          }
      }

      // Redirect after all operations
      echo "<script>window.location='?manageleaveprotocols';</script>";
  } else {
      echo "<script>alert('No approving officers selected!');window.history.back();</script>";
  }
}
?>





