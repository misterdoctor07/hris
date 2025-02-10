    <?php
    $id=$_GET['id'];
    $sqlCompany=mysqli_query($con,"SELECT * FROM settings WHERE id='$id'");
    $company=mysqli_fetch_array($sqlCompany);
    ?>
    <script type="text/javascript">
      function SubmitDetails(){
        var code=$('#companycode').val();
        var name=$('#companyname').val();
        var address=$('#companyaddress').val();
        var ceo=$('#companyceo').val();
        var email=$('#companyemail').val();
        var phone=$('#companyphone').val();
        if(code==''){
          alert('Please enter company code!');   
          return false;       
        }else if(name==''){
          alert('Please enter company name!');
          return false;
        }else if(address==''){
          alert('Please enter company address!');
          return false;
        }else if(ceo==''){
          alert('Please enter company CEO!');
          return false;
        }else if(email==''){
          alert('Please enter company email!');
          return false;
        }else if(phone==''){
          alert('Please enter company phone!');
          return false;
        }else{
          return confirm('Do you wish to submit details?');
        }
      }
    </script>
    <div class="col-lg-6">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?managecompany"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-building-o"></i> UPDATE COMPANY</h4>              
            </div>
            <div class="panel-body">              
              <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
                <input type="hidden" name="editcompany">
                <input type="hidden" name="id" value="<?=$id;?>">
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">Company Code</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="companycode" id="companycode" value="<?=$company['companycode'];?>">
                  </div>
                </div>                
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">Company Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="companyname" id="companyname" value="<?=$company['companyname'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">Company Address</label>
                  <div class="col-sm-10">
                    <textarea name="companyaddress" cols="20" rows="5" class="form-control" id="companyaddress"><?=$company['companyaddress'];?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">Company CEO</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="companyceo" id="companyceo" value="<?=$company['companyceo'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">Company Email</label>
                  <div class="col-sm-10">
                    <input type="email" class="form-control" name="companyemail" id="companyemail" value="<?=$company['companyemail'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">Company Phone</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control" name="companyphone" id="companyphone" value="<?=$company['companyphone'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">&nbsp;</label>
                  <div class="col-sm-10">
                    <input type="submit" class="btn btn-primary" name="submit" value="Submit Update">
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
      $name=$_GET['companyname'];
      $address=$_GET['companyaddress'];
      $ceo=$_GET['companyceo'];
      $email=$_GET['companyemail'];
      $phone=$_GET['companyphone'];
      $data="companycode='$code',companyname='$name',companyaddress='$address',companyceo='$ceo',companyemail='$email',companyphone='$phone'";
      //$values="'$code','$name','$address','$ceo','$email','$phone','Active'";
      $sqlAddCompany=mysqli_query($con,"UPDATE settings SET $data WHERE id='$id'");
      if($sqlAddCompany){
        echo "<script>";
          echo "alert('Details successfully saved!');window.location='?editcompany&id=$id';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to save details!');window.location='?editcompany&id=$id';";
        echo "</script>";
      }
    }
  ?>