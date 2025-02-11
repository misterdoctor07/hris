<div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-calendar"></i> OVERTIME APPLICATION HISTORY <a href="?addovertime" style="float:right;" class="btn btn-primary"><i class="fa fa-plus"></i> Apply Overtime</a></h4>              
            </div>
              <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed">
                  <thead>
                    <tr>
                      <th width="2%" style="text-align: center;">No.</th>
                      <th width="10%" style="text-align: center;">Date of OT</th>
                      <th width="10%" style="text-align: center;">Time of OT</th>
                      <th style="text-align: center;">Reasons</th>
                      <th width="15%" style="text-align: center;">Status</th>
                      <th style="text-align: center;">Remarks</th>
                      <th width="5%" style="text-align: center;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $x=1;
                    $sqlEmployee = mysqli_query($con, "
                    SELECT * 
                    FROM overtime_application ot 
                    WHERE ot.idno = '" . mysqli_real_escape_string($con, $_SESSION['idno']) . "' 
                    ORDER BY 
                        CASE 
                            WHEN ot.app_status = 'Pending' THEN 1 
                            ELSE 2 
                        END, 
                    ot.datearray DESC");
                
                      if (mysqli_num_rows($sqlEmployee) > 0) {
                        while ($company = mysqli_fetch_array($sqlEmployee)) {
                            // Check if the status is "Pending"
                            $status = $company['app_status'];
                            $isPending = ($status === 'Pending');

                            echo "<tr>";
                            echo "<td align='center'>$x.</td>";
                            echo "<td align='center'>" . date('m/d/Y', strtotime($company['otdate'])) . "</td>";
                            echo "<td align='center'>" . date("g:i A", strtotime($company['ottime'])) . "</td>";
                            echo "<td>$company[reasons]</td>";
                            echo "<td align='center'>$company[app_status]</td>";
                            echo "<td>$company[remarks]</td>";
                            ?>
                            <td align="center">                                 
                              <a href="?editovertime&id=<?= $company['id']; ?>" class="btn btn-success btn-xs" title="Edit Overtime" <?= !$isPending ? 'disabled' : ''; ?>><i class='fa fa-edit'></i></a>
                              <a href="?applyovertime&id=<?= $company['id']; ?>&delete" class="btn btn-danger btn-xs" title="Delete Overtime" <?= !$isPending ? 'disabled' : ''; ?> onclick="return confirm('Do you wish to delete this item?'); return false;"><i class='fa fa-trash'></i></a>
                            </td>
                            <?php
                          echo "</tr>";
                            $x++;
                        }
                    } else {
                      echo "<tr><td colspan='8' align='center'>No record found!</td></tr>";
                  }
                    ?>
                  </tbody>
                </table>              
                </div>  
            </div>
            </div>      
            <?php
            if(isset($_GET['delete'])){
                $id=$_GET['id'];
                $sqlDelete=mysqli_query($con,"DELETE FROM overtime_application WHERE id='$id'");
                if($sqlDelete){
                    echo "<script>";
                        echo "alert('Item successfully removed!');";
                        echo "window.location='?applyovertime';";
                    echo "</script>";
                }else{
                    echo "<script>";
                        echo "alert('Unable to remove item!');";
                        echo "window.location='?applyovertime';";
                    echo "</script>";
                }
            }
            ?>