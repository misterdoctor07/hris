            <div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-building"></i> OFFENSE MANAGEMENT<div style="float:right;"><a href="?addoffense" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add Offense Type</a></div></h4>              
            </div>
              <div class="panel-body">
                <div class="adv-table">
                <table class="display table table-bordered">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>ID</th>
                      <th>Type of Offense</th>
                      <th>Description</th>
                      <th>Points</th>
                      <th>Instance</th>
                      <th>Category</th>
                      <th>Freq Points</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $x=1;
                      $sqlCompany=mysqli_query($con,"SELECT * FROM offense ORDER BY `title` ASC");
                      if(mysqli_num_rows($sqlCompany)>0){
                        while($company=mysqli_fetch_array($sqlCompany)){                        
                          echo "<tr>";
                            echo "<td width='3%'>$x.</td>";
                            echo "<td width='5%' align='center'>$company[id]</td>";
                            echo "<td>$company[title]</td>";
                            echo "<td>$company[description]</td>";
                            echo "<td align='center'>$company[points]</td>";
                            echo "<td align='center'>$company[frequency]</td>";
                            echo "<td align='center'>$company[category]</td>";
                            echo "<td align='center'>$company[fpoints]</td>";
                            ?>
                            <td align="center">                              
                              <a href="?editoffense&id=<?=$company['id'];?>" class="btn btn-primary btn-xs" title="Edit Memo"><i class='fa fa-pencil'></i></a>
                              <a href="?manageoffense&id=<?=$company['id'];?>&delete" class="btn btn-danger btn-xs" title="Delete Memo" onclick="return confirm('Do you wish to delete this type of offense?'); return false;"><i class='fa fa-trash-o'></i></a>
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
              if(isset($_GET['delete'])){
                $id=$_GET['id'];
                $sqlDelete=mysqli_query($con,"DELETE FROM offense WHERE id='$id'");
                if($sqlDelete){
                  echo "<script>";
                    echo "alert('Item successfully deleted!');window.location='?manageoffense';";
                  echo "</script>";
                }else{
                  echo "<script>";
                    echo "alert('Unable to delete item!');window.location='?manageoffense';";
                  echo "</script>";
                }
              }
          ?>