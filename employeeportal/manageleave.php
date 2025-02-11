<div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-file-text"></i> LEAVE APPLICATION HISTORY <a href="?applyleave" style="float:right;" class="btn btn-primary"><i class="fa fa-plus"></i> Apply Leave</a></h4>              
            </div>
              <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed">
                  <thead>
                    <tr>
                      <th width="2%">No.</th>
                      <th width="6%" style="text-align: center;">Leave Type</th>
                      <th width="6%" style="text-align: center;">No. of Days</th>
                      <th width="6%" style="text-align: center;">From</th>
                      <th width="6%" style="text-align: center;">To</th>
                      <th style="text-align: center;">Reason</th>
                      <th width="6%" style="text-align: center;">Date Applied</th>
                      <th width="10%" style="text-align: center;">Status</th>
                      <th style="text-align: center;">HR Remarks</th>
                      <th style="text-align: center;">Approver Remarks</th>
                      <th width="5%" style="text-align: center;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
            $x = 1;
            $sqlEmployee = mysqli_query($con, "
              SELECT la.id AS leave_id, ep.idno, ep.lastname, ep.firstname, la.leavetype, la.numberofdays, la.dayfrom, la.dayto, la.reason, la.datearray, la.appstatus, la.remarks, la.approver_remarks
              FROM leave_application la
              INNER JOIN employee_profile ep ON ep.idno = la.idno   
              WHERE la.idno = '" . mysqli_real_escape_string($con, $_SESSION['idno']) . "' 
              ORDER BY 
                  CASE 
                      WHEN la.appstatus = 'Pending' THEN 1 
                      ELSE 2 
                  END, 
              la.datearray DESC");

            if (mysqli_num_rows($sqlEmployee) > 0) {
                while ($emp = mysqli_fetch_array($sqlEmployee)) {
                  $status = $emp['appstatus'];
                  $isPending = ($status === 'Pending');
                    ?>
                    <tr>
                        <td align='center'><?= $x++; ?>.</td>
                        <td align='center'><?= $emp['leavetype']?></td>
                        <td align='center'><?= $emp['numberofdays']?></td>
                        <td align='center'><?= date('m/d/Y', strtotime($emp['dayfrom'])); ?></td>
                        <td align='center'><?= date('m/d/Y', strtotime($emp['dayto'])); ?></td>
                        <td><?= $emp['reason'] ?></td>
                        <td align='center'><?= date('m/d/Y', strtotime($emp['datearray'])) ?></td>
                        <td align='center'><?= $emp['appstatus'] ?></td>
                        <td><?= $emp['remarks'] ?></td>
                        <td><?= $emp['approver_remarks'] ?></td>
                        <td align="center">
                            <?php if (strpos($emp['remarks'], 'POSTED') === false && strpos($emp['appstatus'], 'Cancelled') === false && strpos($emp['appstatus'], 'Disapproved') === false && strpos($emp['appstatus'], 'Approved') === false): ?> 
                              <a href="?editleave&id=<?= $emp['leave_id']; ?>" class="btn btn-success btn-xs" title="Edit Leave"><i class='fa fa-edit'></i></a>
                              <a href="?manageleave&id=<?= $emp['leave_id']; ?>&cancel" class="btn btn-danger btn-xs" title="Cancel Leave" onclick="return confirm('Do you wish to cancel your leave application?'); return false;"><i class='fa fa-times'></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='12' align='center'>No records found!</td></tr>";
            }
            ?>
                  </tbody>
                </table>              
                </div>  
            </div>
            </div>      
<?php
$idno = $_SESSION['idno'];
if (isset($_GET['cancel'])) {
    $id = $_GET['id'];

    // Retrieve the leave type and number of days before deletion
    $sqlRetrieve = mysqli_query($con, "SELECT leavetype, numberofdays, idno FROM leave_application WHERE id='$id'");
    
    if ($sqlRetrieve && mysqli_num_rows($sqlRetrieve) > 0) {
        $leaveData = mysqli_fetch_array($sqlRetrieve);
        $leaveType = $leaveData['leavetype'];
        $numberOfDays = $leaveData['numberofdays'];
        $employeeId = $leaveData['idno'];

        // Now proceed to cancel the leave application
        $sqlCancel = mysqli_query($con, "UPDATE leave_application SET  appstatus = 'Cancelled' WHERE id='$id'");


        if ($sqlCancel) {
            // Update leave credits based on leave type
            // switch ($leaveType) {
            //     case 'VL':
            //         $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET vlused = vlused - $numberOfDays WHERE idno = '$idno'");
            //         break;
            //     case 'SL':
            //         $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET slused = slused - $numberOfDays WHERE idno = '$idno'");
            //         break;
            //     case 'PTO':
            //         $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET ptoused = ptoused - $numberOfDays WHERE idno = '$idno'");
            //         break;
            //     case 'BLP':
            //         $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET blp_used = blp_used - $numberOfDays WHERE idno = '$idno'");
            //         break;
            //     case 'EO':
            //         $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET eo_used = eo_used - $numberOfDays WHERE idno = '$idno'");
            //         break;                                      
            //     default:
            //         echo "<script>alert('Leave type not recognized. No credits updated.');</script>";
            //         break;
            // }
            
            echo "<script>";
            echo "alert('Leave successfully cancelled!');";
            echo "window.location='?manageleave';";
            echo "</script>";
        } else {
            echo "<script>";
            echo "alert('Unable to cancel leave application!');";
            echo "window.location='?manageleave';";
            echo "</script>";
        }
    } else {
        echo "<script>";
        echo "alert('Unable to retrieve leave data!');";
        echo "window.location='?manageleave';";
        echo "</script>";
    }
}
?>
