
          <div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-file-text"></i> OVERTIME APPLICATION
              <div style="float:right; width:40%">
                <form method="GET">
                    <input type="hidden" name="overtimeapplication">
                    <table border="0" width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                            <td>From</td>
                            <td><input type="date" name="startdate" class="form-control"></td>
                            <td>&nbsp;To</td>
                            <td><input type="date" name="enddate" class="form-control"></td>
                            <td>&nbsp;<input type="submit" name="submit" value="Search" class="btn btn-primary"></td>
                        </tr>
                    </table>
                </form>
              </div>
            </h4>
            </div>
              <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                  <thead>
                    <tr>
                      <th width="3%">No.</th>
                      <th>Employee</th>
                      <th>OT Date</th>
                      <th>OT Time</th>
                      <th>Reasons</th>
                      <th>TL Approval</th>
                      <th>OS Approval</th>
                      <th>Remarks</th>
                      <th>Date/Time</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $x=1;
                    if(isset($_GET['submit'])){
                        $startdate=$_GET['startdate'];
                        $enddate=$_GET['enddate'];
                        $sqlEmployee=mysqli_query($con,"SELECT ot.*,ot.id as otid,ep.*,ed.* FROM overtime_application ot INNER JOIN employee_profile ep ON ep.idno=ot.idno INNER JOIN employee_details ed ON ed.idno=ep.idno WHERE ot.otdate BETWEEN '$startdate' AND '$enddate'  ORDER BY ot.datearray ASC");
                    }else{
                        $sqlEmployee=mysqli_query($con,"SELECT ot.*,ot.id as otid,ep.*,ed.* FROM overtime_application ot INNER JOIN employee_profile ep ON ep.idno=ot.idno INNER JOIN employee_details ed ON ed.idno=ep.idno WHERE YEAR(ot.otdate)='".date('Y')."' ORDER BY ot.datearray ASC");
                    }

                      if(mysqli_num_rows($sqlEmployee)>0){
                        while($company=mysqli_fetch_array($sqlEmployee)){
                            $tl="";
                            $os="";
                            if($company['tlapproval'] <> ''){
                                $tl1=explode('-',$company['tlapproval']);
                                $tl=$tl1[0];
                            }
                            if($company['osapproval'] <> ''){
                                $os1=explode('-',$company['osapproval']);
                                $os=$os1[0];
                            }
                          echo "<tr>";
                            echo "<td>$x.</td>";
                            echo "<td>$company[lastname], $company[firstname]</td>";
                            echo "<td>".date('m/d/Y',strtotime($company['otdate']))."</td>";
                            echo "<td>$company[ottime]</td>";
                            echo "<td align='left'>$company[reasons]</td>";
                            echo "<td align='left'>$company[tlapproval]</td>";
                            echo "<td align='left'>$company[osapproval]</td>";
                            echo "<td align='left'>$company[remarks]</td>";
                            echo "<td align='left'>$company[datearray] $company[timearray]</td>";
                            ?>
                            <td align="center">
                              <a href="?overtimeapplication&id=<?=$company['otid'];?>&approved" class="btn btn-success btn-xs" title="Approved" onclick="return confirm('Do you wish to sign approve this overtime application?');return false;"><i class='fa fa-thumbs-up'></i></a>
                              <a href="?overtimeapplication&id=<?=$company['otid'];?>&disapproved" class="btn btn-danger btn-xs" title="Disapproved" onclick="return confirm('Do you wish to disapprove this overtime application?');return false;"><i class='fa fa-thumbs-down'></i></a>
                              <a href="?overtimeapplication&addremarks&id=<?=$company['otid'];?>&remarks=<?=$company['remarks'];?>" class="btn btn-primary btn-xs" title="Remarks"><i class='fa fa-edit'></i></a>
                            </td>
                            <?php
                          echo "</tr>";
                          $x++;
                            }
                      }else{
                        echo "<tr><td colspan='10' align='center'>No record found!</td></tr>";
                      }
                    ?>
                  </tbody>
                </table>
                </div>
            </div>
          </div>
          <?php
          if(isset($_GET['addremarks'])){
              $id=$_GET['id'];
              $remarks=$_GET['remarks'];
          ?>
          <div class="col-lg-3">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?overtimeapplication"><i class="fa fa-arrow-left"></i> Close</a> | <i class="fa fa-file-text"></i> REMARKS</h4>
            </div>
              <div class="panel-body">
                  <form name="f1" method="GET">
                      <input type="hidden" name="overtimeapplication">
                      <input type="hidden" name="id" value="<?=$id;?>">
                      <div class="form-group">
                          <textarea name="remarks" class="form-control" rows="5" placeholder="Add Remarks"><?=$remarks;?></textarea>
                      </div>
                      <div class="form-group">
                          <input type="submit" name="submitRemarks" class="btn btn-primary" value="Save">
                      </div>
                  </form>
                </div>
            </div>
          </div>
          <?php
          }
            ?>
            <?php
            if(isset($_GET['approved'])){
                $id=$_GET['id'];
                $sqlCheck=mysqli_query($con,"SELECT * FROM overtime_application WHERE id='$id'");
                if(mysqli_num_rows($sqlCheck)>0){
                    $check=mysqli_fetch_array($sqlCheck);
                    if($check['tlapproval']==""){
                        $sqlUpdate=mysqli_query($con,"UPDATE overtime_application SET tlapproval='Approved-$_SESSION[idno]' WHERE id='$id'");
                    }elseif($check['osapproval']==""){
                        $sqlUpdate=mysqli_query($con,"UPDATE overtime_application SET osapproval='Approved-$_SESSION[idno]' WHERE id='$id'");
                    }
                }
                if($sqlUpdate){
                    echo "<script>";
                        echo "alert('Overtime application successfully approved!');";
                        echo "window.location='?overtimeapplication';";
                    echo "</script>";
                }else{
                    echo "<script>";
                        echo "alert('Unable to approve overtime application!');";
                        echo "window.location='?overtimeapplication';";
                    echo "</script>";
                }
            }

            if(isset($_GET['disapproved'])){
                $id=$_GET['id'];
                $sqlUpdate=mysqli_query($con,"UPDATE overtime_application SET tlapproval='Disapproved',osapproval='Disapproved' WHERE id='$id'");
                if($sqlUpdate){
                    echo "<script>";
                        echo "alert('Overtime application successfully disapproved!');";
                        echo "window.location='?overtimeapplication';";
                    echo "</script>";
                }else{
                    echo "<script>";
                        echo "alert('Unable to disapprove overtime application!');";
                        echo "window.location='?overtimeapplication';";
                    echo "</script>";
                }
            }
            if(isset($_GET['submitRemarks'])){
                $id=$_GET['id'];
                $remarks=$_GET['remarks'];
                $sqlUpdate=mysqli_query($con,"UPDATE overtime_application SET remarks='$remarks' WHERE id='$id'");
                if($sqlUpdate){
                    echo "<script>";
                        echo "alert('Remarks successfully saved!');";
                        echo "window.location='?overtimeapplication';";
                    echo "</script>";
                }else{
                    echo "<script>";
                        echo "alert('Unable to save remarks!');";
                        echo "window.location='?overtimeapplication';";
                    echo "</script>";
                }
            }
            ?>
