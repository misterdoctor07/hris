<script type="text/javascript">
      function SubmitDetails(){
          return confirm('Do you wish to submit details?');
      }
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?manageemployee"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-users"></i> ADD EMPLOYEE</h4>
    </div>
    </div>
    <form class="form-horizontal tasi-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="addemployee">
      <input type="hidden" name="addedby" value="<?=$fullname;?>">
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
                <input type="submit" name="submit" class="btn btn-primary" value="Save Details" style="float:right;">
              <h4><i class="fa fa-user"></i> EMPLOYEE PROFILE</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Employee No.</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="idno" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Last Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="lastname" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">First Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="firstname" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Middle Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="middlename" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Suffix</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="suffix">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Nickname</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="nickname" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Date of Birth</label>
                  <div class="col-sm-5">
                    <input type="date" name="birthdate" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Civil Status</label>
                  <div class="col-sm-5">
                    <select name="civilstatus" class="form-control" required>
                      <option value=""></option>
                      <option value="S">Single</option>
                      <option value="M">Married</option>
                      <option value="W">Widowed</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Gender</label>
                  <div class="col-sm-3">
                    <select name="gender" class="form-control" required>
                      <option value=""></option>
                      <option value="M">Male</option>
                      <option value="F">Female</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Eligibility</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="eligibility">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Address</label>
                  <div class="col-sm-9">
                    <textarea name="address" class="form-control" required rows="2"></textarea>
                  </div>
                </div>
                <h4 style="margin-top:57px; margin-bottom:40px"><i class="fa fa-address-book"></i> EMERGENCY CONTACT DETAILS</h4>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Contact Person</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="contactperson" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Contact Number</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="contactnumber" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Relationship</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="relationship" required>
                  </div>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>
        <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><i class="fa fa-user"></i> EMPLOYEE DETAILS</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Company</label>
                  <div class="col-sm-5">
                    <select name="company" class="form-control" required>
                      <option value=""></option>
                      <?php
                      $sqlCompany=mysqli_query($con,"SELECT companycode FROM settings WHERE status='Active' ORDER BY companycode ASC");
                      if(mysqli_num_rows($sqlCompany)>0){
                        while($comp=mysqli_fetch_array($sqlCompany)){
                          echo "<option value='$comp[companycode]'>$comp[companycode]</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Department</label>
                  <div class="col-sm-5">
                    <select name="department" class="form-control" required>
                      <option value=""></option>
                      <?php
                      $sqlCompany=mysqli_query($con,"SELECT * FROM department ORDER BY department ASC");
                      if(mysqli_num_rows($sqlCompany)>0){
                        while($comp=mysqli_fetch_array($sqlCompany)){
                          echo "<option value='$comp[id]'>$comp[department]</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Job Title</label>
                  <div class="col-sm-8">
                    <select name="jobtitle" class="form-control" required>
                      <option value=""></option>
                      <?php
                      $sqlCompany=mysqli_query($con,"SELECT * FROM jobtitle ORDER BY jobtitle ASC");
                      if(mysqli_num_rows($sqlCompany)>0){
                        while($comp=mysqli_fetch_array($sqlCompany)){
                          echo "<option value='$comp[id]'>$comp[jobtitle]</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Status</label>
                  <div class="col-sm-4">
                    <select name="status" class="form-control" required>
                      <option value=""></option>
                      <option value="PROBATIONARY">PROBATIONARY</option>
                      <option value="REGULAR">REGULAR</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Date Hired</label>
                  <div class="col-sm-5">
                    <input type="date" name="datehired" class="form-control" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Shift</label>
                    <div class="input-group input-large col-lg-8" data-date="01/01/2014" data-date-format="mm/dd/yyyy">
                      <input type="time" class="form-control" name="from">
                      <span class="input-group-addon">To</span>
                      <input type="time" class="form-control" name="to">
                    </div>
                  </div>
                  <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Work Area</label>
                  <div class="col-sm-5">
                    <select name="location" class="form-control" required>
                      <option value=""></option>
                      <option value="OS">On-site</option>
                      <option value="WFH">Work From Home</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                <label class="col-sm-3 col-sm-3 control-label">Branch Location</label>
                <div class="col-sm-5">
                  <select name="work_area" class="form-control" required>
                  <option value=""></option>
                      <option value="Davao">Davao City</option>
                      <option value="Iloilo">Ilo-ilo City</option>
                      <option value="Kidapawan">Kidapawan City</option>
                      <option value="Panabo">Panabo City</option>
                      <option value="Digos">Digos City</option>
                    </select>
                </div>
              </div>

            </div>
          </div>
          <!-- col-lg-12-->
          <div class="content-panel">
              <div class="panel-heading">
              <h4><i class="fa fa-stack-overflow"></i> EMPLOYEE BENEFITS</h4>
            </div>
            <div class="panel-body">
                <input type="hidden" name="addcompany">
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Life Insurance Effectivity</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="insurance" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">HMO Effectivity</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="hmo" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">SSS No.</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="sss" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">TIN</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="tin" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">PHIC</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="phic" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Pag-ibig</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="hdmf" required>
                  </div>
                </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><i class="fa fa-user"></i> PROBATIONARY CHECKLIST</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Date Oriented</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="dateoriented" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Released Temporary ID</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="tempid" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Released Permanent ID</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="permanentid" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">NBI/Police Clearance</label>
                  <div class="col-sm-8">
                    <input type="radio" name="clearance" required value="Police Clearance"> Police Clearance &nbsp;&nbsp;&nbsp;<input type="radio" name="clearance" value="NBI Clearance"> NBI Clearance &nbsp;&nbsp;&nbsp;<input type="radio" name="clearance" value="To Follow"> To Follow
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">City Health Card</label>
                  <div class="col-sm-8">
                    <input type="radio" name="healthcard" required value="Yes"> Yes &nbsp;&nbsp;&nbsp;<input type="radio" name="healthcard" value="No"> No &nbsp;&nbsp;&nbsp;<input type="radio" name="healthcard" value="To Follow"> To Follow
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Birth Certificate</label>
                  <div class="col-sm-8">
                    <input type="radio" name="birthcertificate" required value="Yes"> Yes &nbsp;&nbsp;&nbsp;<input type="radio" name="birthcertificate" value="No"> No &nbsp;&nbsp;&nbsp;<input type="radio" name="birthcertificate" value="To Follow"> To Follow
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">1x1 Picture</label>
                  <div class="col-sm-8">
                    <input type="radio" name="idpicture1" required value="Yes"> Yes &nbsp;&nbsp;&nbsp;<input type="radio" name="idpicture1" value="No"> No &nbsp;&nbsp;&nbsp;<input type="radio" name="idpicture1" value="To Follow"> To Follow
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">2x2 Picture</label>
                  <div class="col-sm-8">
                    <input type="radio" name="idpicture2" required value="Yes"> Yes &nbsp;&nbsp;&nbsp;<input type="radio" name="idpicture2" value="No"> No &nbsp;&nbsp;&nbsp;<input type="radio" name="idpicture2" value="To Follow"> To Follow
                  </div>
                </div>
            </div>
          </div>
          <!-- col-lg-12-->
          <div class="content-panel">
              <div class="panel-heading">
              <h4><i class="fa fa-user"></i> CONTRACT STATUS</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Probationary Status</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="probationary">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Probationary Date</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="probationarydate">
                  </div>
                </div>
            </div>
          </div>

          <div class="content-panel">
            <div class="panel-heading">
            <h4><i class="fa fa-user"></i> REFERRAL
          </div>
          <div class="panel-body">
              <div class="form-group">
              <label class="col-sm-4 col-sm-4 control-label">Referred By</label>
<div class="col-sm-7">
    <div class="custom-dropdown">
        <input type="text" id="searchEmployee" class="form-control" value="N/A" onfocus="showDropdown()" autocomplete="off" required>
        <select name="referredby" class="form-control" id="referredby-select" required style="display: none;">
            <option value="N/A">N/A</option>
            <?php
            $sqlEmployee = mysqli_query($con, "
                SELECT ep.idno, ep.lastname, ep.firstname 
                FROM employee_profile ep 
                LEFT JOIN employee_details ed ON ed.idno = ep.idno 
                WHERE ed.status != 'RESIGNED' AND ep.idno != '$idno' 
                ORDER BY ep.lastname ASC
            ");
            if (mysqli_num_rows($sqlEmployee) > 0) {
                while ($employee = mysqli_fetch_assoc($sqlEmployee)) {
                    echo "<option value='{$employee['idno']}'>{$employee['lastname']}, {$employee['firstname']}</option>";
                }
            }
            ?>
        </select>
        <div id="dropdown-options" class="dropdown-options" style="display: none;">
            <div class='dropdown-item' data-value="N/A">N/A</div>
            <?php
            // Reset the result pointer to the beginning
            mysqli_data_seek($sqlEmployee, 0); // Move pointer to the first row
            if (mysqli_num_rows($sqlEmployee) > 0) {
                while ($employee = mysqli_fetch_assoc($sqlEmployee)) {
                    echo "<div class='dropdown-item' data-value='{$employee['idno']}'>{$employee['lastname']}, {$employee['firstname']}</div>";
                }
            }
            ?>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchEmployee');
    const dropdownOptions = document.getElementById('dropdown-options');
    const selectElement = document.getElementById('referredby-select');

    function showDropdown() {
        dropdownOptions.style.display = 'block';
        filterOptions(searchInput.value.toLowerCase());
    }

    searchInput.addEventListener('focus', function() {
        showDropdown();
    });

    searchInput.addEventListener('input', function() {
        filterOptions(this.value.toLowerCase());
        if (this.value === '') {
            // Show all options when the input is cleared
            showDropdown();
        }
    });

    function filterOptions(filter) {
        const items = dropdownOptions.querySelectorAll('.dropdown-item');
        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(filter) ? '' : 'none';
        });
    }

    dropdownOptions.addEventListener('click', function(e) {
        if (e.target.classList.contains('dropdown-item')) {
            searchInput.value = e.target.textContent; // Set the input to the selected value
            selectElement.value = e.target.getAttribute('data-value'); // Set the select value
            dropdownOptions.style.display = 'none'; // Hide options after selection
        }
    });

    // Hide dropdown if clicked outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.custom-dropdown')) {
            dropdownOptions.style.display = 'none';
        }
    });
</script>

<style>
    .custom-dropdown {
        position: relative;
        display: inline-block;
    }
    .dropdown-options {
        position: absolute;
        background: white;
        border: 1px solid #ccc;
        z-index: 10;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .dropdown-item {
        padding: 10px;
        cursor: pointer;
    }
    .dropdown-item:hover {
        background: #f1f1f1;
    }
</style>

              </div>
              <div class="form-group">
                <label class="col-sm-4 col-sm-4 control-label">Effectivity</label>
                <div class="col-sm-5">
                  <input type="date" class="form-control" name="referreddate">
                </div>
              </div>
          </div>
        </div>
        </div>
        </form>
  <?php
    if(isset($_GET['submit'])){
      $idno=$_GET['idno'];
      $addedby=$_GET['addedby'];
      $datenow=date('Y-m-d H:i:s');
      $lastname=$_GET['lastname'];
      $firstname=$_GET['firstname'];
      $middlename=$_GET['middlename'];
      $suffix=$_GET['suffix'];
      $nickname=$_GET['nickname'];
      $address=$_GET['address'];
      $birthdate=$_GET['birthdate'];
      $gender=$_GET['gender'];
      $civilstatus=$_GET['civilstatus'];
      $eligible=$_GET['eligibility'];
      $referredby=$_GET['referredby'];
      $referreddate=$_GET['referreddate'];
      $contactperson=$_GET['contactperson'];
      $contactnumber=$_GET['contactnumber'];
      $relationship=$_GET['relationship'];

      $table="employee_profile(idno,lastname,firstname,middlename,suffix,nickname,birthdate,civilstatus,sex,eligibility,address,contactperson,contactnumber,relationship,addedby,addeddatetime)";
      $values="VALUES('$idno','$lastname','$firstname','$middlename','$suffix','$nickname','$birthdate','$civilstatus','$gender','$eligible','$address','$contactperson','$contactnumber','$relationship','$addedby','$datenow')";
      $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");

      $company=$_GET['company'];
      $department=$_GET['department'];
      $jobtitle=$_GET['jobtitle'];
      $status=$_GET['status'];
      $datehired=$_GET['datehired'];
      $startshift=$_GET['from'];
      $endshift=$_GET['to'];
      $location=$_GET['location'];
      $work_area=$_GET['work_area'];
      $regular=date('Y-m-d',strtotime('6 months',strtotime($datehired)));
      $fulltime=date('Y-m-d',strtotime('1 years',strtotime($regular)));

      $table="employee_details(idno,company,department,designation,status,dateofhired,dateofregular,dateoffulltime,startshift,endshift,location,work_area,addedby,addeddatetime)";
      $values="VALUES('$idno','$company','$department','$jobtitle','$status','$datehired','$regular','$fulltime','$startshift','$endshift','$location','$work_area','$addedby','$datenow')";
      $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");

      $insurance=$_GET['insurance'];
      $hmo=$_GET['hmo'];
      $sss=$_GET['sss'];
      $tin=$_GET['tin'];
      $phic=$_GET['phic'];
      $hdmf=$_GET['hdmf'];
      $table="employee_benefits(idno,insurance,hmo,sss,tin,phic,hdmf,addedby,addeddatetime)";
      $values="VALUES('$idno','$insurance','$hmo','$sss','$tin','$phic','$hdmf','$addedby','$datenow')";
      $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");

        $dateoriented=$_GET['dateoriented'];
        $tempid=$_GET['tempid'];
        $permanentid=$_GET['permanentid'];
        $clearance=$_GET['clearance'];
        $healthcard=$_GET['healthcard'];
        $birthcertificate=$_GET['birthcertificate'];
        $idpicture1=$_GET['idpicture1'];
        $idpicture2=$_GET['idpicture2'];
        if(($clearance=="Police Clearance" || $clearance=="NBI Clearance") && $healthcard=="Yes" && $birthcertificate=="Yes" && $idpicture1=="Yes" && $idpicture2=="Yes"){
            $status="Cleared";
        }else{
            $status="";
        }
            $table="employee_checklist(idno,dateoriented,releasedtempid,releasedpermanentid,clearance,healthcard,birthcertificate,idpicture1,idpicture2,status,addedby,addeddatetime)";
            $values="VALUES('$idno','$dateoriented','$tempid','$permanentid','$clearance','$healthcard','$birthcertificate','$idpicture1','$idpicture1','$status','$addedby','$datenow')";
            $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");

            $probationary=$_GET['probationary'];
            $probationarydate=$_GET['probationarydate'];
                $table="employee_contract(idno,probationary,probationarydate,addedby,addeddatetime)";
                $values="VALUES('$idno','$probationary','$probationarydate','$addedby','$datenow')";
                $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");

                $table="employee_referral(idno,referredby,effectivity,addedby,addeddatetime)";
                $values="VALUES('$idno','$referredby','$referreddate','$addedby','$datenow')";
                $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");

      if($sqlAddEmployee){
        echo "<script>";
          echo "alert('Details successfully saved!');window.location='?addemployee';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to saved details!');window.location='?addemployee';";
        echo "</script>";
      }
    }
  ?>
