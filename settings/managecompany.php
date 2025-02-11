            <div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-building-o"></i> COMPANY PROFILE<div style="float:right;"><a href="?addcompany" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add Company</a></div></h4>              
            </div>
              <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed">
                  <thead>
                    <tr>
                      <th>Code</th>
                      <th>Company</th>
                      <th>Address</th>
                      <th>CEO</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Status</th>                      
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sqlCompany=mysqli_query($con,"SELECT * FROM settings ORDER BY companycode ASC");
                      if(mysqli_num_rows($sqlCompany)>0){
                        while($company=mysqli_fetch_array($sqlCompany)){
                          if($company['status']=="Active"){
                            $status="<span class='label label-success label-mini'>$company[status]</span>";
                          }else{
                            $status="<span class='label label-danger label-mini'>$company[status]</span>";
                          }
                          echo "<tr>";
                            echo "<td>$company[companycode]</td>";
                            echo "<td>$company[companyname]</td>";
                            echo "<td>$company[companyaddress]</td>";
                            echo "<td>$company[companyceo]</td>";
                            echo "<td>$company[companyemail]</td>";
                            echo "<td>$company[companyphone]</td>";
                            echo "<td align='center'>$status</td>";
                            ?>
                            <td align="center">                              
                              <a href="?editcompany&id=<?=$company['id'];?>" class="btn btn-primary btn-xs" title="Edit Company"><i class='fa fa-pencil'></i></a>
                              <a href="?managecompany&id=<?=$company['id'];?>&delete" class="btn btn-danger btn-xs" title="Delete Company" onclick="return confirm('Do you wish to delete this company?'); return false;"><i class='fa fa-trash-o'></i></a>
                            </td>
                            <?php
                          echo "</tr>";
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