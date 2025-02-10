            <div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-building-o"></i> USER PROFILE<div style="float:right;"></h4>
            </div>
              <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                  <thead>
                    <tr>
                      <th width='4%'>No.</th>
                      <th  width='6%'>Emp ID</th>
                      <th>Employee Name</th>
                      <th>Nickname</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $x=1;
                      $sqlCompany=mysqli_query($con,"SELECT * FROM employee_profile ORDER BY lastname ASC");
                      if(mysqli_num_rows($sqlCompany)>0){
                        while($company=mysqli_fetch_array($sqlCompany)){
                          echo "<tr>";
                            echo "<td>$x.</td>";
                            echo "<td>$company[idno]</td>";
                            echo "<td>$company[lastname], $company[firstname]</td>";
                            echo "<td>$company[nickname]</td>";
                            ?>
                            <td align="center">
                              <a href="?edituser&id=<?=$company['id'];?>" class="btn btn-primary btn-xs" title="Edit User Account"><i class='fa fa-pencil'></i></a>
                            </td>
                            <?php
                          echo "</tr>";
                          $x++;
                        }
                      }else{
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
                $sqlDelete=mysqli_query($con,"DELETE FROM settings WHERE id='$id'");
                if($sqlDelete){
                  echo "<script>";
                    echo "alert('Item successfully deleted!');window.location='?managecompany';";
                  echo "</script>";
                }else{
                  echo "<script>";
                    echo "alert('Unable to delete item!');window.location='?managecompany';";
                  echo "</script>";
                }
              }
          ?>
