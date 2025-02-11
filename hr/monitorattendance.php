<script type="text/javascript">
      function SubmitDetails(){
          return confirm('Do you wish to submit details?');
      }
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?main"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-calendar"></i> Attendance Monitoring</h4>
    </div>
    </div>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="attendancemonitoring">
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
                <input type="submit" name="submit" class="btn btn-primary" value="Proceed" style="float:right;">
              <h4><i class="fa fa-user"></i> EMPLOYEE LOGS</h4>
            </div>
            <div class="panel-body">
            <div class="form-group">
  <label class="col-sm-3 col-sm-3 control-label">Company</label>
  <div class="col-sm-7">
  <select name="company" class="form-control" id="company">
  <option value="">Select a company</option>
  <?php
  $sqlCompany=mysqli_query($con,"SELECT companycode,companyname FROM settings GROUP BY companycode");
  if(mysqli_num_rows($sqlCompany)>0){
      while($row = mysqli_fetch_array($sqlCompany)){
        echo "<option value='$row[companycode]'>$row[companyname]</option>";
      }
  }
  ?>
</select>
  </div>
</div>

<div class="form-group">
  <label class="col-sm-3 col-sm-3 control-label">Department</label>
  <div class="col-sm-7">
    <?php
    // Assuming $con is your database connection
    $sqlDepartment = mysqli_query($con, "SELECT id, department FROM department GROUP BY id");
    
    if ($sqlDepartment === false) {
        // Error handling for the query
        echo "Error in SQL query: " . mysqli_error($con);
    } elseif (mysqli_num_rows($sqlDepartment) > 0) {
        while ($row = mysqli_fetch_array($sqlDepartment)) {
            ?>
            <input type="checkbox" name="departments[]" value="<?php echo htmlspecialchars($row['id']); ?>">
            <?php echo htmlspecialchars($row['department']); ?><br>
            <?php
        }
    } else {
        echo "No departments found.";
    }
    ?>
  </div>
</div>

                
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Date From</label>
                  <div class="col-sm-7">
                    <input type="date" name="startdate" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Date To</label>
                  <div class="col-sm-7">
                    <input type="date" name="enddate" class="form-control">
                  </div>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>
        </form>
        

        <form class="form-horizontal style-form" method="GET" action="attendancemonitoringsummary.php" onSubmit="return SubmitDetails();" target="_blank">
      <!-- <input type="hidden" name="attendancemonitoringsummary"> -->
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
                <input type="submit" name="submit" class="btn btn-primary" value="Proceed" style="float:right;">
              <h4><i class="fa fa-calendar"></i> ATTENDANCE SUMMARY(ABSENCES/LATE) </h4>
            </div>
            <div class="panel-body">
            <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Company</label>
                  <div class="col-sm-7">
                    <select name="dept" class="form-control">
                      <?php
                      // $sqlDepartment=mysqli_query($con,"SELECT * FROM department");
                      $sqlDepartment=mysqli_query($con,"SELECT companycode,companyname FROM settings GROUP BY companycode");
                      if(mysqli_num_rows($sqlDepartment)>0){
                        while($row=mysqli_fetch_array($sqlDepartment)){
                          echo "<option value='$row[companycode]'>$row[companyname]</option>";
                        }
                      }
                      
                      ?>

                    </select>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">START DATE</label>
                  <div class="col-sm-7">
                    <input type="date" name="startdate" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">END DATE</label>
                  <div class="col-sm-7">
                    <input type="date" name="enddate" class="form-control">
                  </div>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>
        </form>

        <form class="form-horizontal style-form" method="GET" action="attendancemonitoringsummarymissed.php" onSubmit="return SubmitDetails();" target="_blank">
      <!-- <input type="hidden" name="attendancemonitoringsummary"> -->
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
                <input type="submit" name="submit" class="btn btn-primary" value="Proceed" style="float:right;">
              <h4><i class="fa fa-calendar"></i> MISSED LOGS/OB SUMMARY</h4>
            </div>
            <div class="panel-body">
            <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">DEPARTMENT</label>
                  <div class="col-sm-7">
                    <select name="dept" class="form-control">
                      <?php
                      // $sqlDepartment=mysqli_query($con,"SELECT * FROM department");
                      $sqlDepartment=mysqli_query($con,"SELECT companycode,companyname FROM settings GROUP BY companycode");
                      if(mysqli_num_rows($sqlDepartment)>0){
                        while($row=mysqli_fetch_array($sqlDepartment)){
                          echo "<option value='$row[companycode]'>$row[companyname]</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">START DATE</label>
                  <div class="col-sm-7">
                    <input type="date" name="startdate" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">END DATE</label>
                  <div class="col-sm-7">
                    <input type="date" name="enddate" class="form-control">
                  </div>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>
        </form>
<?php
if (isset($_GET['submit'])) {
    $company = $_GET['company'];
    $departments = isset($_GET['departments']) ? $_GET['departments'] : [];
    $startdate = $_GET['startdate'];
    $enddate = $_GET['enddate'];

    $sql = "SELECT * FROM attendance_monitoring";
    $conditions = [];

    // Add company condition if selected
    if (!empty($company)) {
        $conditions[] = "companycode = '$company'";
    }

    // Add department condition if selected
    if (!empty($departments)) {
        // Convert the departments array to a comma-separated string for SQL
        $departmentsList = implode(",", array_map('intval', $departments)); // Sanitizing input for safety
        $conditions[] = "department_id IN ($departmentsList)";
    }

    // Add date range condition if both dates are selected
    if (!empty($startdate) && !empty($enddate)) {
        $conditions[] = "date BETWEEN '$startdate' AND '$enddate'";
    }

    // Combine all conditions into the SQL query
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    // Execute the SQL query and display the results
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        // Display the results
        while ($row = mysqli_fetch_assoc($result)) {
            // Example display code (customize as needed)
            echo "<p>" . $row['some_column'] . "</p>";
        }
    } else {
        echo "No records found";
    }
}
?>
