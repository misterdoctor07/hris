<?php
$comp=$_GET["company"]; 
$type=$_GET["type"];
if($type=="insurance"){
  $labeltype="Life Insurance Effectivity";
}else{
  $labeltype="HMO Effectivity";
}
?>
        <div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?insurance"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-user"></i> EMPLOYEE LIST (<?=$comp;?>)</h4>
            </div>
              <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed" id="hidden-table-info">
                  <thead>
                    <tr>
                      <th width="3%"  style="vertical-align:middle;">No.</th>
                      <th style="vertical-align:middle;">Emp ID</th>
                      <th style="vertical-align:middle;">Employee Name</th>                      
                      <th style="vertical-align:middle;"><?=$labeltype;?></th>
                      <th style="vertical-align:middle;">SSS</th>  
                      <th style="vertical-align:middle;">TIN</th>
                      <th style="vertical-align:middle;">PHIC</th>  
                      <th style="vertical-align:middle;">PAG-IBIG</th>
                    </tr>                    
                  </thead>
                  <tbody>
                    <?php
                    $x=1;
                    mysqli_query($con,"SET NAMES 'utf8'");
                    if($type=="insurance"){
                      $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.*,eb.*,eb.insurance FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno INNER JOIN employee_benefits eb ON eb.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company='$comp' AND YEAR(eb.insurance) >= '".date('Y')."' ORDER BY ep.lastname ASC");                      
                    }else{
                      $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.*,eb.*,eb.hmo as insurance FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno INNER JOIN employee_benefits eb ON eb.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company='$comp' AND YEAR(eb.hmo) >= '".date('Y')."' ORDER BY ep.lastname ASC");
                    }
                      if(mysqli_num_rows($sqlEmployee)>0){
                        while($company=mysqli_fetch_array($sqlEmployee)){
                            $insurance=date('m/d/Y',strtotime($company['insurance']));                            
                          echo "<tr>";
                            echo "<td>$x.</td>";
                            echo "<td>$company[idno]</td>";
                            echo "<td>$company[lastname], $company[firstname] $company[middlename] $company[suffix]</td>";                                                                                 
                            echo "<td align='center'>$insurance</td>";                            
                            echo "<td align='center'>$company[sss]</td>";
                            echo "<td align='center'>$company[tin]</td>";
                            echo "<td align='center'>$company[phic]</td>";
                            echo "<td align='center'>$company[hdmf]</td>";
                          echo "</tr>";
                          $x++;
                        }
                      }else{
                        echo "<tr><td colspan='9' align='center'>No record found!</td></tr>";
                      }
                    ?>
                  </tbody>
                </table>
                </div>
            </div>
            </div>            
