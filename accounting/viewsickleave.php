<?php
$cmpy=$_GET['company'];
?>
    <div class="col-lg-12">
        <div class="content-panel">
            <div class="panel-heading">
                <h4><a href="?sickleave"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-text"></i> EMPLOYEE BENEFITS</h4>
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
                        <th>Sick Leave</th>
                        <th>Used Sick Leave</th>
                        <th>Unused Sick Leave</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $x=1;
                    $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company='$cmpy' ORDER BY ed.department ASC,ep.lastname ASC");
                    if(mysqli_num_rows($sqlEmployee)>0){
                        while($company=mysqli_fetch_array($sqlEmployee)){
                            $datehired=date('m/d/Y',strtotime('3 months',strtotime($company['dateofhired'])));
                            $dateleaveeffective=date('m/d/Y',strtotime($company['dateleaveeffective']));
                            $sqlDept=mysqli_query($con,"SELECT department FROM department WHERE id='$company[department]'");
                            $dept=mysqli_fetch_array($sqlDept);
                            $department=$dept['department'];
                            $sickleave=0;
                            $sickleaveused=0;
                            $slremain=0;
                            $sqlBenefits=mysqli_query($con,"SELECT * FROM leave_credits WHERE idno='$company[idno]'");
                            if(mysqli_num_rows($sqlBenefits)>0){
                                $benefits=mysqli_fetch_array($sqlBenefits);
                                $sickleave=$benefits['sickleave'];
                                $sickleaveused=$benefits['slused'];
                                $slremain=$sickleave-$sickleaveused;
                            }
                            if($slremain>0){
                                echo "<tr>";
                                echo "<td>$x.</td>";
                                echo "<td>$company[idno]</td>";
                                echo "<td>$company[lastname], $company[firstname] $company[middlename] $company[suffix]</td>";
                                echo "<td align='center'>$department</td>";
                                echo "<td>$dateleaveeffective</td>";
                                echo "<td align='center'>$sickleave</td>";
                                echo "<td align='center'>$sickleaveused</td>";
                                echo "<td align='center'>$slremain</td>";
                                echo "</tr>";
                                $x++;
                            }
                        }
                    }else{
                        echo "<tr><td colspan='8' align='center'>No record found!</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>                    <?php
