<div class="col-lg-8">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-comments-o"></i> BIRTHDAY GREETINGS<div style="float:right;"><a href="?birthday&addnew" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add Greetigns</a></div></h4>
            </div>
              <div class="panel-body">
                <div class="adv-table">
                <table class="display table table-bordered">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Details</th>
                      <th>Date Posted</th>
                      <th>Time Posted</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $x=1;
                      $sqlCompany=mysqli_query($con,"SELECT * FROM widgets WHERE `type`='Birthday' ORDER BY datearray DESC");
                      if(mysqli_num_rows($sqlCompany)>0){
                        while($company=mysqli_fetch_array($sqlCompany)){                          
                          echo "<tr>";
                            echo "<td width='3%'>$x.</td>";
                            echo "<td align='left'>$company[details]</td>";
                            echo "<td>$company[datearray]</td>";
                            echo "<td>$company[timearray]</td>";
                            ?>
                            <td align="center">                              
                              <a href="?birthday&id=<?=$company['id'];?>&editnew&details=<?=$company['details'];?>" class="btn btn-primary btn-xs" title="Edit Memo"><i class='fa fa-pencil'></i></a>
                              <a href="?birthday&id=<?=$company['id'];?>&delete" class="btn btn-danger btn-xs" title="Delete Memo" onclick="return confirm('Do you wish to delete this birthday?'); return false;"><i class='fa fa-trash-o'></i></a>
                            </td>
                            <?php
                          echo "</tr>";
                          $x++;
                        }
                      }else{
                        echo "<tr><td colspan='5' align='center'>No record found!</td></tr>";
                      }
                    ?>
                  </tbody>
                </table>  
                </div>            
                </div>  
            </div>
            </div>
            <?php
            if(isset($_GET['addnew'])){                
            ?>
            <div class="col-lg-4">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?birthday"><i class="fa fa-arrow-left"></i> Close</a> | <i class="fa fa-bullhorn"></i> ADD birthday</h4>              
            </div>
              <div class="panel-body">
                <div class="adv-table">
                 <form name="f1" method="GET">
                     <div class="form-group">
                         <textarea name="birthday" class="form-control" rows="4" placeholder="birthday details"></textarea>
                     </div>
                     <div class="form-group">
                         <input type="submit" name="submitbirthday" class="btn btn-success" value="Save Details">
                     </div>
                 </form>
                </div>            
                </div>  
            </div>
            </div>
            <?php
            }
            ?>
            <?php
            if(isset($_GET['editnew'])){  
                $id=$_GET['id']; 
                $details=$_GET['details'];             
            ?>
            <div class="col-lg-4">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?birthday"><i class="fa fa-arrow-left"></i> Close</a> | <i class="fa fa-bullhorn"></i> UPDATE birthday</h4>              
            </div>
              <div class="panel-body">
                <div class="adv-table">
                 <form name="f1" method="GET">
                     <input type="hidden" name="id" value="<?=$id;?>">
                     <div class="form-group">
                         <textarea name="birthday" class="form-control" rows="4" placeholder="birthday details"><?=$details;?></textarea>
                     </div>
                     <div class="form-group">
                         <input type="submit" name="updatebirthday" class="btn btn-success" value="Save Details">
                     </div>
                 </form>
                </div>            
                </div>  
            </div>
            </div>
            <?php
            }
            ?>
            <?php
              if(isset($_GET['delete'])){
                $id=$_GET['id'];
                $sqlDelete=mysqli_query($con,"DELETE FROM widgets WHERE id='$id'");
                if($sqlDelete){
                    echo "<script>";
                      echo "alert('birthday successfully deleted!');window.location='?birthday';";
                    echo "</script>";
                  }else{
                    echo "<script>";
                    echo "alert('Unable to delete birthday!');window.location='?birthday';";
                    echo "</script>";
                  }
              }
              if(isset($_GET['submitbirthday'])){
                $details=$_GET['birthday'];
                $datenow=date('Y-m-d');
                $timenow=date('H:i:s');
                $type="Birthday";
                $sqlDelete=mysqli_query($con,"INSERT INTO widgets(details,`type`,datearray,timearray) VALUES('$details','$type','$datenow','$timenow')");
                if($sqlDelete){
                  echo "<script>";
                    echo "alert('birthday successfully added!');window.location='?birthday';";
                  echo "</script>";
                }else{
                  echo "<script>";
                  echo "alert('Unable to add birthday!');window.location='?birthday';";
                  echo "</script>";
                }
              }
              if(isset($_GET['updatebirthday'])){
                  $id=$_GET['id'];
                $details=$_GET['birthday'];
                $datenow=date('Y-m-d');
                $timenow=date('H:i:s');                
                $sqlDelete=mysqli_query($con,"UPDATE widgets SET details='$details',datearray='$datenow',timearray='$timenow' WHERE id='$id'");
                if($sqlDelete){
                  echo "<script>";
                    echo "alert('birthday successfully updated!');window.location='?birthday';";
                  echo "</script>";
                }else{
                  echo "<script>";
                  echo "alert('Unable to update birthday!');window.location='?birthday';";
                  echo "</script>";
                }
              }
          ?>