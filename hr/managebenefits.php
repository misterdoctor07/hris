<div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-file-text"></i> EMPLOYEE BENEFITS</h4>              
            </div>
              <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                  <thead>
                    <tr>
                      <th width="3%">No.</th>
                      <th>Emp ID</th>
                      <th>Employee Name</th>
                      <th>Department</th>
                      <th>Eligibility</th>
                      <th>SSS</th>
                      <th>Philhealth</th>
                      <th>Pag-ibig</th>
                      <th>Basic Salary Rate</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $x=1;
                      $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' ORDER BY ed.department ASC,ep.lastname ASC");
                      if(mysqli_num_rows($sqlEmployee)>0){
                        while($company=mysqli_fetch_array($sqlEmployee)){                                                    
                          $datehired=date('m/d/Y',strtotime('3 months',strtotime($company['dateofhired'])));
                          $sqlDept=mysqli_query($con,"SELECT department FROM department WHERE id='$company[department]'");
                          $dept=mysqli_fetch_array($sqlDept);
                          $department=$dept['department'];
                          $sqlBenefits=mysqli_query($con,"SELECT * FROM employee_payroll WHERE idno='$company[idno]'");
                          if(mysqli_num_rows($sqlBenefits)>0){
                              $benefits=mysqli_fetch_array($sqlBenefits);
                              $sss=number_format($benefits['sss'],2);
                              $phic=number_format($benefits['phic'],2);
                              $hdmf=number_format($benefits['hdmf'],2);
                              $salary=number_format($benefits['salary'],2);
                          }else{
                            $sss=number_format(0,2);
                            $phic=number_format(0,2);
                            $hdmf=number_format(0,2);
                            $salary=number_format(0,2);
                          }
                          echo "<tr>";
                            echo "<td>$x.</td>";
                            echo "<td>$company[idno]</td>";
                            echo "<td>$company[lastname], $company[firstname] $company[middlename] $company[suffix]</td>";
                            echo "<td align='center'>$department</td>";
                            echo "<td>$datehired</td>";
                            echo "<td align='right'>$sss</td>";
                            echo "<td align='right'>$phic</td>";
                            echo "<td align='right'>$hdmf</td>";
                            echo "<td align='right'>$salary</td>";
                            ?>
                            <td align="center"> 
                              <a href="?editbenefits&id=<?=$company['idno'];?>" class="btn btn-success btn-xs" title="Manage Benefits"><i class='fa fa-edit'></i></a>                                                           
                              <!-- <a href="?addleave&id=<?=$company['id'];?>" class="btn btn-primary btn-xs" title="Update Leave Credits"><i class='fa fa-pencil'></i></a> -->                              
                              <!-- <a href="?employeecontract&id=<?=$company['id'];?>" class="btn btn-danger btn-xs" title="Contract Status"><i class='fa fa-clipboard'></i></a>
                              <a href="?employeereferal&id=<?=$company['id'];?>" class="btn btn-info btn-xs" title="Referral"><i class='fa fa-mail-reply'></i></a> -->
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