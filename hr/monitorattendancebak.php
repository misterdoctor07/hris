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
              <h4><i class="fa fa-user"></i> SELECT COMPANY</h4>            
            </div>
            <div class="panel-body">                                                              
                <div class="form-group">
                  <label class="col-sm-3 col-sm-3 control-label">Company</label>
                  <div class="col-sm-7">
                    <select name="company" class="form-control" required>
                      <option value=""></option>
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