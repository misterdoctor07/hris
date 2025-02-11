<?php
    $sqlMemo=mysqli_query($con,"SELECT memonumber FROM infraction ORDER BY id DESC LIMIT 1");
   
    ?>
   <!-- Include Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    // Apply Select2 to dropdowns
    $('select').select2({
        placeholder: "Select an option",
        allowClear: true
    });
});

function SubmitDetails() {
    return confirm('Do you wish to submit details?');
}
</script>

    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?manageinfraction"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-text"></i> EMPLOYEE INFRACTION</h4>      
    </div>
    </div>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="addinfraction">            
      <input type="hidden" name="addedby" value="<?=$fullname;?>">          
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">                
                <input type="submit" name="submit" class="btn btn-primary" value="Save Details" style="float:right;">
              <h4><i class="fa fa-file-text"></i> ISSUE INFRACTION</h4>            
            </div>
            <div class="panel-body">                                            
            <div class="form-group">
    <label class="col-sm-4 control-label">Employee</label>
    <div class="col-sm-7">
        <div style="position: relative;">
            <input type="text" id="searchInput" onkeyup="filterFunction()" placeholder="Search employee..." class="form-control">
            <select name="idno" id="employeeDropdown" class="form-control" size="5" style="position: absolute; top: 100%; left: 0; width: 100%; display: none; z-index: 1;">
                <option value=""></option>
                <?php
                $sqlEmployee = mysqli_query($con, "SELECT ep.idno, ep.lastname, ep.firstname FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno = ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' ORDER BY ep.lastname ASC, ep.firstname ASC");
                if (mysqli_num_rows($sqlEmployee) > 0) {
                    while ($employee = mysqli_fetch_assoc($sqlEmployee)) {
                        echo "<option value='" . htmlspecialchars($employee['idno']) . "'>" . htmlspecialchars($employee['lastname']) . ", " . htmlspecialchars($employee['firstname']) . "</option>";
                    }
                }
                ?>
            </select>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById("searchInput");
    const employeeDropdown = document.getElementById("employeeDropdown");

    // Show dropdown when input is focused
    searchInput.addEventListener("focus", () => {
        employeeDropdown.style.display = "block";
    });

    // Filter dropdown options
    function filterFunction() {
        const filter = searchInput.value.toUpperCase();
        const options = employeeDropdown.getElementsByTagName("option");
        for (let i = 0; i < options.length; i++) {
            const text = options[i].innerText || options[i].textContent;
            options[i].style.display = text.toUpperCase().includes(filter) ? "" : "none";
        }
    }

    // Update input when option is selected
    employeeDropdown.addEventListener("change", () => {
        const selectedOption = employeeDropdown.options[employeeDropdown.selectedIndex];
        searchInput.value = selectedOption.innerText;
        employeeDropdown.style.display = "none"; // Hide dropdown after selection
    });

    // Hide dropdown when clicking outside
    document.addEventListener("click", (event) => {
        if (!event.target.closest(".form-group")) {
            employeeDropdown.style.display = "none";
        }
    });
</script>




                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Date Issued</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="dateissued" value="<?=date('M-d-Y');?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Date Served</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="dateserved" value="<?=date('M-d-Y');?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Type of Memo</label>
                  <div class="col-sm-7">
                    <select name="memotype" class="form-control" required>
                        <option value=""></option>
                        <?php
                        $sqlEmployee=mysqli_query($con,"SELECT * FROM memo");
                        if(mysqli_num_rows($sqlEmployee)>0){
                            while($employee=mysqli_fetch_array($sqlEmployee)){
                                echo "<option value='$employee[title]'>$employee[title]</option>";
                            }
                        }
                        ?>
                    </select>
                  </div>
                </div> 
                <div class="form-group">
    <label class="col-sm-4 col-sm-4 control-label">Type of Category</label>
    <div class="col-sm-7">
        <select name="typecat" class="form-control" required>
            <option value=""></option>
            <?php
            // Correct SQL query and variable usage
            $sqlOffense = mysqli_query($con, "SELECT * FROM offense WHERE title LIKE '%Category%'");
            if (mysqli_num_rows($sqlOffense) > 0) {
                while ($cat = mysqli_fetch_array($sqlOffense)) { // Use $sqlOffense
                    echo "<option value='{$cat['title']}'>{$cat['description']}</option>";
                }
            }
            ?>
        </select>
    </div>
</div>
                       
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Type of Offense</label>
                  <div class="col-sm-7">
                    <select name="typeofoffense" class="form-control" required>
                        <option value=""></option>
                        <?php
                        $sqlEmployee=mysqli_query($con,"SELECT * FROM offense WHERE title NOT LIKE '%Attendance%'");
                        if(mysqli_num_rows($sqlEmployee)>0){
                            while($employee=mysqli_fetch_array($sqlEmployee)){
                                echo "<option value='$employee[title]'>$employee[title]</option>";
                            }
                        }
                        ?>
                       
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Date of Incident</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="dateofincident" value="<?=date('M-d-Y');?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Points</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="points" style="text-align:center;">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Suspension Dates</label>
                  <div class="col-sm-7">
                    <textarea name="dateofsuspension" class="form-control" rows="5"></textarea>
                  </div>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>                
        </form>
        <?php
// Fetch the latest memonumber and increment it
$sqlMemo = mysqli_query($con, "SELECT memonumber FROM infraction ORDER BY id DESC LIMIT 1");
$lastMemo = mysqli_fetch_assoc($sqlMemo);
$lastMemoNumber = $lastMemo ? $lastMemo['memonumber'] : '25-0000';

// Extract the numeric part from the last memonumber and increment it
$lastNumber = (int)substr($lastMemoNumber, 3); // Remove the '24-' part and convert the remaining to integer
$nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // Increment and pad with leading zeros
$newMemoNumber = '25-' . $nextNumber;

// Handling form submission
if (isset($_GET['submit'])) {
    $idno = $_GET['idno'];
    $addedby = $_GET['addedby'];
    $datenow = date('Y-m-d H:i:s');
    $dateissued = $_GET['dateissued'];
    $dateserved = $_GET['dateserved'];
    $memotype = $_GET['memotype'];
    $memo = $newMemoNumber;  // Use the generated memonumber
    $typeofoffense = $_GET['typeofoffense'];
    $typecat = $_GET['typecat'];
    $points = $_GET['points'];
    $dateofincident = $_GET['dateofincident'];
    $dateofsuspension = $_GET['dateofsuspension'];
    
    

    // Check if the memonumber already exists
    $sqlCheck = mysqli_query($con, "SELECT * FROM infraction WHERE memonumber='$memo'");
    if (mysqli_num_rows($sqlCheck) > 0) {
        echo "<script>";
        echo "alert('Memo number already in use!');window.history.back();";
        echo "</script>";
    } else {
        // Insert the new record into the database
        $table = "infraction(idno,dateissued,dateserved,typeofmemo,dateofincident,typecat,typeofoffense,points,memonumber,dateofsuspension,status,addedby,addeddatetime, viewstatus)";
        $values = "VALUES('$idno','$dateissued','$dateserved','$memotype','$dateofincident','$typecat','$typeofoffense','$points','$memo','$dateofsuspension','pending','$addedby','$datenow', 'new')";
        $sqlAddEmployee = mysqli_query($con, "INSERT INTO $table $values");

        if ($sqlAddEmployee) {
            echo "<script>";
            echo "alert('Details successfully saved!');window.location='?addinfraction';";
            echo "</script>";
        } else {
            echo "<script>";
            echo "alert('Unable to save details!');window.location='?addinfraction';";
            echo "</script>";
        }
    }
}
?>
