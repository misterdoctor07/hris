<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <h4>
                <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> |
                <i class="fa fa-book"></i> EMERGENCY EARLY OUT APPLICATION HISTORY
                <a href="?addeeo" style="float:right;" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Apply Emergency Early Out
                </a>
            </h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th width="2%">No.</th>
                        <th width="6%">Type of EEO</th>
                        <th width="12%" style="text-align: center;">Date</th> 
                        <th width="5%" style="text-align: center;">Time</th>
                        <th width="15%" style="text-align: center;">Reason</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Approver's Remarks</th>
                        <th style="text-align: center;">Acknowledged by: </th>
                        <th width="5%" style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $x = 1;
                    $sqlEmployee = mysqli_query($con, "SELECT * 
                    FROM emergencyearlyout eeo 
                    WHERE eeo.idno = '" . mysqli_real_escape_string($con, $_SESSION['idno']) . "' 
                    ORDER BY 
                        CASE 
                            WHEN eeo.eeo_status = 'Pending' THEN 1 
                            ELSE 2 
                        END, 
                    eeo.date_applied,
                    eeo.time_applied DESC");
                    
                    if (mysqli_num_rows($sqlEmployee) > 0) {
                        while ($company = mysqli_fetch_array($sqlEmployee)) {
                            // Check if the status is "Pending"
                            $status = $company['eeo_status'];
                            $isPending = ($status === 'Pending');

                            $idno = $company['idno'];
                            $style = "class='primary'"; // Default style
                            
                            if (strpos($status, 'Approved') !== false) {
                                $style = "class='success'";
                            } elseif (strpos($status, 'Disapproved') !== false) {
                                $style = "class='danger'";
                            } elseif (strpos($status, 'Pending') !== false) {
                                $style = "class='warning'";
                            }

                            echo "<tr $style>";
                            echo "<td align='center'>$x.</td>";
                            echo "<td>$company[type_EEO]</td>";
                            echo "<td align='center'>" . date('m/d/Y', strtotime($company['dateEEO'])) . "</td>";
                            echo "<td align='center'>" . date("g:i A", strtotime($company['timeEEO'])) . "</td>";
                            echo "<td>$company[reason]</td>";
                            echo "<td align='center'>$status</td>";
                            echo "<td align='center'>$company[approvers_remarks]</td>";
                            echo "<td align='center'>$company[acknowledged]</td>";
                            ?>
                            <td align="center">
                                <?php if (strpos($company['eeo_status'], 'Approved') === false && strpos($company['eeo_status'], 'Disapproved') === false): ?> 
                                    <a href="?editeeo&id=<?= $company['id']; ?>" class="btn btn-success btn-xs" title="Edit Emergency Early Out" <?= !$isPending ? 'disabled' : ''; ?>><i class='fa fa-edit'></i></a>
                                    <a href="?emergencyearlyout&id=<?= $company['id']; ?>&delete" class="btn btn-danger btn-xs" title="Delete Early Out" <?= !$isPending ? 'disabled' : ''; ?> onclick="return confirm('Do you wish to delete this item?'); return false;"><i class='fa fa-trash'></i></a>
                                <?php endif; ?>
                            </td>
                            <?php
                            echo "</tr>";
                            $x++;
                        }
                    } else {
                        echo "<tr><td colspan='9' align='center'>No record found!</td></tr>";
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
