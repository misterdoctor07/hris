<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <h4>
                <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> |
                <i class="fa fa-book"></i> MISSED LOG APPLICATION HISTORY
                <a href="?addmissedlog" style="float:right;" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Apply Missed Log
                </a>
            </h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th width="2%">No.</th>
                        <th width="12%" style="text-align: center;">Date of Missed Time IN/OUT</th> 
                        <th width="5%" style="text-align: center;">Incident</th>
                        <th width="5%" style="text-align: center;">Time</th>
                        <th style="text-align: center;">Reason</th>
                        <th width="15%" style="text-align: center;">Status</th>
                        <th style="text-align: center;">HR Remarks</th>
                        <th style="text-align: center;">Monitoring Remarks</th>
                        <th style="text-align: center;">Approver Remarks</th>
                        <th width="5%" style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $x = 1;
                    $sqlEmployee = mysqli_query($con, "
                    SELECT * 
                    FROM missed_log_application ml 
                    WHERE ml.idno = '" . mysqli_real_escape_string($con, $_SESSION['idno']) . "' 
                    ORDER BY 
                        CASE 
                            WHEN ml.applic_status = 'Pending' THEN 1 
                            ELSE 2 
                        END, 
                    ml.time_applied DESC");
                    
                    if (mysqli_num_rows($sqlEmployee) > 0) {
                        while ($company = mysqli_fetch_array($sqlEmployee)) {
                            // Check if the status is "Pending"
                            $status = $company['applic_status'];
                            $isPending = ($status === 'Pending');

                            echo "<tr>";
                            echo "<td align='center'>$x.</td>";
                            echo "<td align='center'>" . date('m/d/Y', strtotime($company['datemissed'])) . "</td>";
                            echo "<td align='center'>$company[incident]</td>";
                            echo "<td align='center'>" . date("g:i A", strtotime($company['mttime'])) . "</td>";
                            echo "<td>$company[reason]</td>";
                            echo "<td align='center'>$status</td>";
                            echo "<td align='center'>$company[remarks]</td>";
                            echo "<td align='center'>$company[monitoring_remarks]</td>";
                            echo "<td align='center'>$company[approver_remarks]</td>";
                            ?>
                            <td align="center">
                                <?php if (strpos($company['applic_status'], 'Approved') === false && strpos($company['applic_status'], 'Disapproved') === false): ?> 
                                    <a href="?editmissedlog&id=<?= $company['id']; ?>" class="btn btn-success btn-xs" title="Edit Missed Log" <?= !$isPending ? 'disabled' : ''; ?>><i class='fa fa-edit'></i></a>
                                    <a href="?applymissedlog&id=<?= $company['id']; ?>&delete" class="btn btn-danger btn-xs" title="Delete Missed Log" <?= !$isPending ? 'disabled' : ''; ?> onclick="return confirm('Do you wish to delete this item?'); return false;"><i class='fa fa-trash'></i></a>
                                <?php endif; ?>
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
if (isset($_GET['delete'])) {
    $id = $_GET['id'];
    $sqlDelete = mysqli_query($con, "DELETE FROM missed_log_application WHERE id='$id'");
    
    if ($sqlDelete) {
        echo "<script>";
        echo "alert('Item successfully removed!');";
        echo "window.location='?applymissedlog';";
        echo "</script>";
    } else {
        echo "<script>";
        echo "alert('Unable to remove item!');";
        echo "window.location='?applymissedlog';";
        echo "</script>";
    }
}
?>
