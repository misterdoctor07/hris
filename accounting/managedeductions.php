<script type="text/javascript">
      function SubmitDetails(){
          return confirm('Do you wish to submit details?');
      }
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?main"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-credit-card"></i> Deductions Manager</h4>
    </div>
    </div>
    <div class="col-lg-6 mt">
            <div class="content-panel">
              <div class="panel-heading">
                <a href="?managedeductions&addnew" class="btn btn-primary" style="float:right;"><i class="fa fa-plus"> Add New</i></a>
              <h4><i class="fa fa-money"></i> DEDUCTIONS</h4>
            </div>
            <div class="panel-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>
                      Deductions Details
                    </th>
                    <th>
                      Amount
                    </th>
                    <th>
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sqlAddons=mysqli_query($con,"SELECT * FROM deductions");
                  if(mysqli_num_rows($sqlAddons)>0){
                    while($addons=mysqli_fetch_array($sqlAddons)){
                      echo "<tr>";
                      echo "<td>$addons[deduction]</td>";
                      echo "<td align='right'>".number_format($addons['amount'],2)."</td>";
                      echo "<td width='20%'><a href='?managedeductions&editdeductions&id=$addons[id]' class='btn btn-success'>Edit</a> <a href='?managedeductions&delete&id=$addons[id]' class='btn btn-danger'>Delete</a></td>";
                      echo "</tr>";
                    }
                  }else{
                    echo "<tr>";
                    echo "<td colspan='3' align='center'>No record found!</td>";
                    echo "</tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <!-- col-lg-12-->
        </div>
        <?php
        if(isset($_GET['addnew'])){
        ?>
        <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
          <input type="hidden" name="managedeductions">
        <div class="col-lg-4 mt">
                <div class="content-panel">
                  <div class="panel-heading">
                    <input type="submit" name="submitSave" class="btn btn-primary" value="Save" style="float:right;">
                  <h4><i class="fa fa-plus"></i> NEW DEDUCTIONS DETAILS</h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                      <label class="col-sm-2 col-sm-3 control-label">Description</label>
                      <div class="col-sm-10">
                        <input type="text" name="description" class="form-control" required />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 col-sm-3 control-label">Amount</label>
                      <div class="col-sm-6">
                        <input type="text" name="amount" class="form-control" required />
                      </div>
                    </div>
                </div>
              </div>
              <!-- col-lg-12-->
            </div>
            </form>
          <?php }

          if(isset($_GET['editdeductions'])){
            $id=$_GET['id'];
            $sqlAddons=mysqli_query($con,"SELECT * FROM deductions WHERE id='$id'");
            $add=mysqli_fetch_array($sqlAddons);
          ?>
          <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
            <input type="hidden" name="managedeductions">
            <input type="hidden" name="id" value="<?=$id;?>">
          <div class="col-lg-4 mt">
                  <div class="content-panel">
                    <div class="panel-heading">
                      <input type="submit" name="submitUpdate" class="btn btn-primary" value="Save" style="float:right;">
                    <h4><i class="fa fa-plus"></i>EDIT ADDONS DETAILS</h4>
                  </div>
                  <div class="panel-body">
                      <div class="form-group">
                        <label class="col-sm-2 col-sm-3 control-label">Description</label>
                        <div class="col-sm-10">
                          <input type="text" name="description" class="form-control" value="<?=$add['deduction'];?>" required />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 col-sm-3 control-label">Amount</label>
                        <div class="col-sm-6">
                          <input type="text" name="amount" class="form-control" value="<?=$add['amount'];?>" required />
                        </div>
                      </div>
                  </div>
                </div>
                <!-- col-lg-12-->
              </div>
              </form>
            <?php } ?>

          <?php
          if(isset($_GET['submitSave'])){
            $description=$_GET['description'];
            $amount=$_GET['amount'];
            $sqlInsert=mysqli_query($con,"INSERT INTO deductions(deduction,amount) VALUES('$description','$amount')");
            if($sqlInsert){
              echo "<script>alert('Deduction successfully saved!');window.location='?managedeductions';</script>";
            }else{
              echo "<script>alert('Unable to save deduction!');window.location='?managedeductions&addnew';</script>";
            }
          }
          ?>

          <?php
          if(isset($_GET['submitUpdate'])){
            $id=$_GET['id'];
            $description=$_GET['description'];
            $amount=$_GET['amount'];
            $sqlInsert=mysqli_query($con,"UPDATE deductions SET deduction='$description',amount='$amount' WHERE id='$id'");
            if($sqlInsert){
              echo "<script>alert('Deduction successfully updated!');window.location='?managedeductions';</script>";
            }else{
              echo "<script>alert('Unable to update deduction!');window.location='?managedeductions&editdeductions';</script>";
            }
          }
          ?>

          <?php
          if(isset($_GET['delete'])){
            $id=$_GET['id'];
            $sqlInsert=mysqli_query($con,"DELETE FROM deductions WHERE id='$id'");
            if($sqlInsert){
              echo "<script>alert('Deductions successfully deleted!');window.location='?managedeductions';</script>";
            }else{
              echo "<script>alert('Unable to delete deductions!');window.location='?managedeductions';</script>";
            }
          }
          ?>
