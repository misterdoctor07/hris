            <div class="col-lg-8">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-bullhorn"></i> ANNOUNCEMENT<div style="float:right;"><a href="?announcement&addnew" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add Announcement</a></div></h4>
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
                      $sqlCompany=mysqli_query($con,"SELECT * FROM widgets WHERE `type`='Announcement' ORDER BY datearray DESC");
                      if(mysqli_num_rows($sqlCompany)>0){
                        while($company=mysqli_fetch_array($sqlCompany)){
                          echo "<tr>";
                            echo "<td width='3%'>$x.</td>";
                            echo "<td align='left'>$company[details]</td>";
                            echo "<td>$company[datearray]</td>";
                            echo "<td>$company[timearray]</td>";
                            ?>
                            <td align="center">
                              <a href="?announcement&id=<?=$company['id'];?>&editnew" class="btn btn-primary btn-xs" title="Edit Memo"><i class='fa fa-pencil'></i></a>
                              <a href="?announcement&id=<?=$company['id'];?>&delete" class="btn btn-danger btn-xs" title="Delete Memo" onclick="return confirm('Do you wish to delete this announcement?'); return false;"><i class='fa fa-trash-o'></i></a>
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
              <h4><a href="?announcement"><i class="fa fa-arrow-left"></i> Close</a> | <i class="fa fa-bullhorn"></i> ADD ANNOUNCEMENT</h4>
            </div>
              <div class="panel-body">
                <div class="adv-table">
                 <form name="f1" method="GET">
                     <div class="form-group">
                         <textarea name="announcement" class="form-control" rows="4" placeholder="Announcement details"></textarea>
                     </div>
                     <div class="form-group">
                         <input type="submit" name="submitAnnouncement" class="btn btn-success" value="Save Details">
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
                $sqlSafety=mysqli_query($con,"SELECT * FROM widgets WHERE id='$id'");
                $safety=mysqli_fetch_array($sqlSafety);
                $details=$safety['details'];
            ?>
            <div class="col-lg-4">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?announcement"><i class="fa fa-arrow-left"></i> Close</a> | <i class="fa fa-bullhorn"></i> UPDATE ANNOUNCEMENT</h4>
            </div>
              <div class="panel-body">
                <div class="adv-table">
                 <form name="f1" method="GET">
                     <input type="hidden" name="id" value="<?=$id;?>">
                     <div class="form-group">
                         <textarea name="announcement" class="form-control" rows="20" placeholder="Announcement details"><?=$details;?></textarea>
                     </div>
                     <div class="form-group">
                         <input type="submit" name="updateAnnouncement" class="btn btn-success" value="Save Details">
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
                      echo "alert('Announcement successfully deleted!');window.location='?announcement';";
                    echo "</script>";
                  }else{
                    echo "<script>";
                    echo "alert('Unable to delete announcement!');window.location='?announcement';";
                    echo "</script>";
                  }
              }
              if(isset($_GET['submitAnnouncement'])){
                $details=$_GET['announcement'];
                $datenow=date('Y-m-d');
                $timenow=date('H:i:s');
                $type="Announcement";
                $sqlDelete=mysqli_query($con,"INSERT INTO widgets(details,`type`,datearray,timearray) VALUES('$details','$type','$datenow','$timenow')");
                if($sqlDelete){
                  echo "<script>";
                    echo "alert('Announcement successfully added!');window.location='?announcement';";
                  echo "</script>";
                }else{
                  echo "<script>";
                  echo "alert('Unable to add announcement!');window.location='?announcement';";
                  echo "</script>";
                }
              }
              if(isset($_GET['updateAnnouncement'])){
                  $id=$_GET['id'];
                $details=$_GET['announcement'];
                $datenow=date('Y-m-d');
                $timenow=date('H:i:s');
                $sqlDelete=mysqli_query($con,"UPDATE widgets SET details='$details',datearray='$datenow',timearray='$timenow' WHERE id='$id'");
                if($sqlDelete){
                  echo "<script>";
                    echo "alert('Announcement successfully updated!');window.location='?announcement';";
                  echo "</script>";
                }else{
                  echo "<script>";
                  echo "alert('Unable to update announcement!');window.location='?announcement';";
                  echo "</script>";
                }
              }
          ?>
