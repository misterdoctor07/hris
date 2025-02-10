          <div class="col-lg-12">  
          <div class="border-head">
              <h3><?=$_SESSION['access'];?> DASHBOARD</h3>
            </div>          
            <div class="row mt">
              <!-- SERVER STATUS PANELS -->
              <div class="col-md-3 col-sm-4 mb">
                <div class="grey-panel pn donut-chart">
                  <div class="grey-header">
                    <h5>REGULAR</h5>
                  </div>
                  <div class="row mt"><i class="fa fa-users fa-5x"></i></div>
                  <?php
                    $sqlEmployee=mysqli_query($con,"SELECT ep.idno FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status='REGULAR'");
                    $regular=mysqli_num_rows($sqlEmployee);
                  ?>
                  <h3><?=$regular;?></h3>
                  Employees
                </div>
                <!-- /grey-panel -->
              </div>
              <!-- /col-md-4-->
              <div class="col-md-3 col-sm-4 mb">
                <div class="grey-panel pn donut-chart">
                  <div class="grey-header">
                    <h5>PROBATIONARY</h5>
                  </div>
                  <div class="row mt"><i class="fa fa-users fa-5x"></i></div>
                  <?php
                    $sqlEmployee=mysqli_query($con,"SELECT ep.idno FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status='PROBATIONARY'");
                    $probationary=mysqli_num_rows($sqlEmployee);
                  ?>
                  <h3><?=$probationary;?></h3>
                  Employees
                </div>
                <!-- /grey-panel -->
              </div>
              <div class="col-md-3 col-sm-4 mb">
                <div class="grey-panel pn donut-chart">
                  <div class="grey-header">
                    <h5>RESIGNED</h5>
                  </div>
                  <div class="row mt"><i class="fa fa-users fa-5x"></i></div>
                  <?php
                    $sqlEmployee=mysqli_query($con,"SELECT ep.idno FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status='RESIGNED'");
                    $resigned=mysqli_num_rows($sqlEmployee);
                  ?>
                  <h3><?=$resigned;?></h3>
                  Employees
                </div>
                <!-- /grey-panel -->
              </div>
              <!-- /col-md-4 -->
              <div class="col-md-3 mb">
                <!-- WHITE PANEL - TOP USER -->
                <div class="white-panel pn">
                  <div class="white-header">
                    <h5>DEPARTMENT</h5>
                  </div>
                  <div class="row mt"><i class="fa fa-building-o fa-5x"></i></div>                  
                  <?php
                    $sqlEmployee=mysqli_query($con,"SELECT * FROM department");
                    $department=mysqli_num_rows($sqlEmployee);
                  ?>
                  <h3><?=$department;?></h3>
                  Department
                </div>
              </div>
              <div class="col-md-3 col-sm-4 mb">
                <!-- REVENUE PANEL -->                
                <div class="white-panel pn">
                  <div class="white-header">
                    <h5>JOB TITLE</h5>
                  </div>
                  <div class="row mt"><i class="fa fa-book fa-5x"></i></div>                  
                  <?php
                    $sqlEmployee=mysqli_query($con,"SELECT * FROM jobtitle");
                    $job=mysqli_num_rows($sqlEmployee);
                  ?>
                  <h3><?=$job;?></h3>
                  Job Titles
                </div>
              </div>
              <div class="col-md-3 col-sm-4 mb">
                <!-- REVENUE PANEL -->
                <div class="green-panel pn">
                  <div class="green-header">
                    <h5>MEMORANDUM</h5>
                  </div>
                  <div class="row mt"><i class="fa fa-file-text-o fa-5x"></i></div>                  
                  <?php
                    $sqlEmployee=mysqli_query($con,"SELECT * FROM memo");
                    $memo=mysqli_num_rows($sqlEmployee);
                  ?>
                  <h3><?=$memo;?></h3>
                  Type of Memo
                </div>
              </div>
              <!-- /col-md-4 -->
              <div class="col-md-3 mb">                
                    <div class="green-panel pn">
                  <div class="green-header">
                    <h5>OFFENSE</h5>
                  </div>
                  <div class="row mt"><i class="fa fa-file-text fa-5x"></i></div>                  
                  <?php
                    $sqlEmployee=mysqli_query($con,"SELECT * FROM offense");
                    $offense=mysqli_num_rows($sqlEmployee);
                  ?>
                  <h3><?=$offense;?></h3>
                  Type of Offense
                </div>
                <!-- /Message Panel-->
              </div>
            </div>            
          </div>          
