            <div class="col-lg-8">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-building"></i> DEPARTMENT<div style="float:right;"><a href="?adddepartment" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add Department</a></div></h4>              
            </div>
              <div class="panel-body">
                <div class="adv-table">
                <table class="display table table-bordered" id="hidden-table-info">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>ID</th>
                      <th>Department</th>                                          
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $x=1;
                      $sqlCompany=mysqli_query($con,"SELECT * FROM department ORDER BY department ASC");
                      if(mysqli_num_rows($sqlCompany)>0){
                        while($company=mysqli_fetch_array($sqlCompany)){                          
                          echo "<tr>";
                            echo "<td width='3%'>$x.</td>";
                            echo "<td width='5%' align='center'>$company[id]</td>";
                            echo "<td>$company[department]</td>";                            
                            ?>
                            <td align="center">                              
                              <a href="?editdepartment&id=<?=$company['id'];?>" class="btn btn-primary btn-xs" title="Edit Department"><i class='fa fa-pencil'></i></a>
                              <a href="?managedepartment&id=<?=$company['id'];?>&delete" class="btn btn-danger btn-xs" title="Delete Department" onclick="return confirm('Do you wish to delete this department?'); return false;"><i class='fa fa-trash-o'></i></a>
                            </td>
                            <?php
                          echo "</tr>";
                          $x++;
                        }
                      }else{
                        echo "<tr><td colspan='4' align='center'>No record found!</td></tr>";
                      }
                    ?>
                  </tbody>
                </table>              
                </div>  
              </div>
            </div>
            </div>        
            <?php
              if(isset($_GET['delete'])){
                $id=$_GET['id'];
                $sqlDelete=mysqli_query($con,"DELETE FROM department WHERE id='$id'");
                if($sqlDelete){
                  echo "<script>";
                    echo "alert('Item successfully deleted!');window.location='?managedepartment';";
                  echo "</script>";
                }else{
                  echo "<script>";
                    echo "alert('Unable to delete item!');window.location='?managedepartment';";
                  echo "</script>";
                }
              }
          ?>