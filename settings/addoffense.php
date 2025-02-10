    <script type="text/javascript">
      function SubmitDetails(){
        var code=$('#companycode').val();        
        var name=$('#companyname').val();
        if(code==''){
          alert('Please enter Type of Memo!');   
          return false;       
        }else if(name==''){
          alert('Please enter Description!');   
          return false;       
        }else{
          return confirm('Do you wish to submit details?');
        }
      }
    </script>
    <div class="col-lg-6">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?manageoffense"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-building-o"></i> ADD OFFENSE TYPE</h4>              
            </div>
            <div class="panel-body">              
              <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
                <input type="hidden" name="addoffense">
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">Title</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="companycode" id="companycode">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">Description</label>
                  <div class="col-sm-10">
                    <textarea name="companyname" class="form-control" cols="20" rows="10" id="companyname"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">Points</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="points" id="points">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">Instance</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="instance" id="points">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">Category</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="category" id="points">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">Infraction Points</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="penalty" id="points">
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
      $code=$_GET['companycode'];      
      $name=$_GET['companyname'];   
      $points=$_GET['points']; 
      $instance=$_GET['instance'];
      $category=$_GET['category'];
      $penalty=$_GET['penalty'];
      $data="title,description,points,frequency,category,fpoints";
      $values="'$code','$name','$points','$instance','$category','$penalty'";
      $sqlAddCompany=mysqli_query($con,"INSERT INTO offense($data) VALUES($values)");
      if($sqlAddCompany){
        echo "<script>";
          echo "alert('Details successfully added!');window.location='?addoffense';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to add details!');window.location='?addoffense';";
        echo "</script>";
      }
    }
  ?>