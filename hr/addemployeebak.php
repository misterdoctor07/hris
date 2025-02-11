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
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
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
                    <textarea name="address" class="form-control" required rows="5"></textarea>
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
                  <div class="col-sm-7">
                    <select name="company" class="form-control" required>
                      <option value=""></option>
                      <?php
                      $sqlCompany=mysqli_query($con,"SELECT companycode,companyname FROM settings WHERE status='Active' ORDER BY companycode ASC");
                      if(mysqli_num_rows($sqlCompany)>0){
                        while($comp=mysqli_fetch_array($sqlCompany)){
                          echo "<option value='$comp[companycode]'>$comp[companycode] - $comp[companyname]</option>";
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

            </div>
          </div>          
          <!-- col-lg-12-->
        </div>  
        <div class="col-lg-4 mt">
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
          <!-- col-lg-12-->
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
      
      $table="employee_profile(idno,lastname,firstname,middlename,suffix,nickname,birthdate,civilstatus,sex,eligibility,address,addedby,addeddatetime)";
      $values="VALUES('$idno','$lastname','$firstname','$middlename','$suffix','$nickname','$birthdate','$civilstatus','$gender','$eligible','$address','$addedby','$datenow')";
      $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");

      $company=$_GET['company'];
      $department=$_GET['department'];
      $jobtitle=$_GET['jobtitle'];
      $status=$_GET['status'];
      $datehired=$_GET['datehired'];
      $startshift=$_GET['from'];
      $endshift=$_GET['to'];
      $location=$_GET['location'];
      $regular=date('Y-m-d',strtotime('3 months',strtotime($datehired)));
      $fulltime=date('Y-m-d',strtotime('1 years',strtotime($regular)));

      $table="employee_details(idno,company,department,designation,status,dateofhired,dateofregular,dateoffulltime,startshift,endshift,location,addedby,addeddatetime)";
      $values="VALUES('$idno','$company','$department','$jobtitle','$status','$datehired','$regular','$fulltime','$startshift','$endshift','$location','$addedby','$datenow')";
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