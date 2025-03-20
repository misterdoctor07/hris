<div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-file-text"></i> LEAVE APPLICATION HISTORY <a href="?applyleave" style="float:right;" class="btn btn-primary"><i class="fa fa-plus"></i> Apply Leave</a></h4>              
            </div>
              <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed">
                  <thead>
                    <tr>
                      <th width="2%" style="text-align: center; background-color:#20273a; color: white;">No.</th>
                      <th width="6%" style="text-align: center; background-color:#20273a; color: white;">Leave Type</th>
                      <th width="6%" style="text-align: center; background-color:#20273a; color: white;">No. of Days</th>
                      <th width="6%" style="text-align: center; background-color:#20273a; color: white;">From</th>
                      <th width="6%" style="text-align: center; background-color:#20273a; color: white;">To</th>
                      <th style="text-align: center; background-color:#20273a; color: white;">Reason</th>
                      <th width="10%" style="text-align: center; background-color:#20273a; color: white;">Date and Time Applied</th>
                      <th width="10%" style="text-align: center; background-color:#20273a; color: white;">Status</th>
                      <th style="text-align: center; background-color:#20273a; color: white;">HR's Remarks</th>
                      <th style="text-align: center; background-color:#20273a; color: white;">Approver's Remarks</th>
                      <th width="5%" style="text-align: center; background-color:#20273a; color: white;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
            $x = 1;
            $sqlEmployee = mysqli_query($con, "SELECT la.id AS leave_id, ep.idno, ep.lastname, ep.firstname, la.leavetype, la.numberofdays, la.dayfrom, la.dayto, la.reason, la.datearray, la.timearray, la.edited_datetime, la.appstatus, la.remarks, la.approver_remarks
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
                  $style = "";
                    
                    if (strpos($status, 'Approved') !== false) {
                        $style = "class='success'";
                    } elseif (strpos($status, 'Disapproved') !== false) {
                        $style = "class='danger'";
                    } elseif (strpos($status, 'Pending') !== false) {
                        $style = "class='warning'";
                    }
                    ?>
                    <tr <?= $style ?>>
                        <td align='center'><?= $x++; ?>.</td>
                        <td align='center'><?= $emp['leavetype']?></td>
                        <td align='center'><?= $emp['numberofdays']?></td>
                        <td align='center'><?= date('M d, Y', strtotime($emp['dayfrom'])); ?></td>
                        <td align='center'><?= date('M d, Y', strtotime($emp['dayto'])); ?></td>
                        <td><?= $emp['reason'] ?></td>
                        <td align='center'>
                            <?= date('M d, Y', strtotime($emp['datearray'])) . "<br>" . 
                              (!empty($emp['timearray']) ? date('g:i A', strtotime($emp['timearray'])) : ""); ?>

                            <?php if (!empty($emp['edited_datetime'])): ?>
                                <br><strong>Latest Edit:</strong><br><?= date('M d, Y', strtotime($emp['edited_datetime'])) . "<br>" . 
                                            date('g:i A', strtotime($emp['edited_datetime'])); ?>
                            <?php endif; ?>
                        </td>
                        <td align='center'><?= $emp['appstatus'] ?></td>
                        <td style="text-align: <?= ($emp['remarks'] == 'POSTED') ? 'center' : 'justify' ?>; vertical-align: middle;">
                            <?= $emp['remarks'] ?>
                        </td>
                        <td><?= $emp['approver_remarks'] ?></td>
                        <td align="center">
                            <?php if (strpos($emp['remarks'], 'POSTED') === false && strpos($emp['appstatus'], 'Cancelled') === false && strpos($emp['appstatus'], 'Disapproved') === false && strpos($emp['appstatus'], 'Approved') === false): ?> 
                              <a href="?editleave&id=<?= $emp['leave_id']; ?>" class="btn btn-primary btn-xs" title="Edit Leave"><i class='fa fa-edit'></i></a>
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