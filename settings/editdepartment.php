    <?php
      $id=$_GET['id'];
      $sqlDepartment=mysqli_query($con,"SELECT * FROM department WHERE id='$id'");
      $department=mysqli_fetch_array($sqlDepartment);
    ?>
    <script type="text/javascript">
      function SubmitDetails(){
        var code=$('#companycode').val();        
        if(code==''){
          alert('Please enter department!');   
          return false;       
        }else{
          return confirm('Do you wish to submit details?');
        }
      }
    </script>
    <div class="col-lg-6">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?managedepartment"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-building-o"></i> UPDATE DEPARTMENT</h4>              
            </div>
            <div class="panel-body">              
              <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
                <input type="hidden" name="editdepartment">
                <input type="hidden" name="id" value="<?=$id;?>">
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">Department</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="companycode" id="companycode" value="<?=$department['department'];?>">
                  </div>
                </div>                                
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">&nbsp;</label>
                  <div class="col-sm-10">
                    <input type="submit" class="btn btn-primary" name="submit" value="Submit Details">
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>      
    </div>
  <?php
    if(isset($_GET['submit'])){
      $id=$_GET['id'];
      $code=$_GET['companycode'];      
      $data="department='$code'";      
      $sqlAddCompany=mysqli_query($con,"UPDATE department SET $data WHERE id='$id'");
      if($sqlAddCompany){
        echo "<script>";
          echo "alert('Details successfully updated!');window.location='?editdepartment&id=$id';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to update details!');window.location='?editdepartment&id=$id';";
        echo "</script>";
      }
    }
  ?>