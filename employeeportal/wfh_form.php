<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <h4>
                <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> |
                <i class="fa fa-book"></i> WFH ARRANGEMENT APPLICATION HISTORY
                <a href="?addwfh" style="float:right;" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Apply for WFH Arrangement
                </a>
            </h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th width="2%" style="text-align: center; vertical-align: middle;">No.</th>
                        <th width="8%" style="text-align: center; vertical-align: middle;">Date of Transfer</th> 
                        <th width="15%" style="text-align: center; vertical-align: middle;">Address</th>
                        <th style="text-align: center; vertical-align: middle;">Reason</th>
                        <th width="7%" style="text-align: center; vertical-align: middle;">Status</th>
                        <th width="5%" style="text-align: center; vertical-align: middle;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $x = 1;
                    $sqlEmployee = mysqli_query($con, "
                    SELECT * 
                    FROM wfh_application w 
                    WHERE w.filedby = '" . mysqli_real_escape_string($con, $_SESSION['idno']) . "' 
                    ORDER BY 
                        CASE 
                            WHEN w.application_status = 'Pending' THEN 1 
                            ELSE 2 
                        END, 
                    w.datetime DESC");
                    
                    if (mysqli_num_rows($sqlEmployee) > 0) {
                        while ($company = mysqli_fetch_array($sqlEmployee)) {
                            // Check if the status is "Pending"
                            $status = $company['application_status'];
                            $TL_approve = $company['TL_approve'];
                            $TM_approve = $company['TM_approve'];
                            $IT_approve = $company['IT_approve'];
                            $isPending = ($status === 'Pending');
                            
                            if (strpos($TL_approve, 'Approved') !== false && strpos($TM_approve, 'Approved') !== false && strpos($IT_approve, 'Approved') !== false) {
                                $appStatus = 'Approved';
                            } else if (strpos($TL_approve, 'Approved') !== false && strpos($TM_approve, 'Approved') !== false && strpos($IT_approve, 'Disapproved') !== false) {
                                $appStatus = 'Disapproved';
                            } else if (strpos($TL_approve, 'Approved') !== false && strpos($TM_approve, 'Disapproved') !== false) {
                                $appStatus = 'Disapproved';
                            } else if (strpos($TL_approve, 'Approved') !== false && strpos($TM_approve, 'Approved') !== false) {
                                $appStatus = 'Pending (For IT Approval)';
                            } else if (strpos($TL_approve, 'Disapproved') !== false) {
                                $appStatus = 'Disapproved';
                            } else if (strpos($TL_approve, 'Approved') !== false) {
                                $appStatus = 'Pending (For TM Approval)';
                            } else {
                                $appStatus = 'Pending';
                            }
                            
                             $status = $appStatus;
                            
                            $style = ""; 
                            
                            if (strpos($status, 'Approved') !== false) {
                                $style = "class='success'";
                            } elseif (strpos($status, 'Disapproved') !== false) {
                                $style = "class='danger'";
                            } elseif (strpos($status, 'Pending') !== false) {
                                $style = "class='warning'";
                            }
                            echo "<tr $style>";
                            echo "<td style='text-align: center; vertical-align: middle;'>$x.</td>";
                            echo "<td style='text-align: center; vertical-align: middle;'>" . date('M d, Y', strtotime($company['date_effective'])) . "</td>";
                            echo "<td style='text-align: center; vertical-align: middle;'>$company[address]</td>";
                            echo "<td style='vertical-align: middle;'>$company[reasons]</td>";
                            echo "<td style='text-align: center; vertical-align: middle;'>$status</td>";
                            ?>
                            <td align="center">
                                <?php if (strpos($company['application_status'], 'Approved') === false && strpos($company['application_status'], 'Disapproved') === false): ?> 
                                    <a href="?edit_wfhform&id=<?= $company['id']; ?>" class="btn btn-primary btn-xs" title="Edit WFH Form" <?= !$isPending ? 'disabled' : ''; ?>><i class='fa fa-edit'></i></a>
                                    <a href="?wfh_form&id=<?= $company['id']; ?>&delete" class="btn btn-danger btn-xs" title="Delete WFH Application" <?= !$isPending ? 'disabled' : ''; ?> onclick="return confirm('Do you wish to delete this item?'); return false;"><i class='fa fa-trash'></i></a>
                                <?php endif; ?>
                            </td>
                            <?php
                            echo "</tr>";
                            $x++;
                        }
                    } else {
                        echo "<tr><td colspan='12' align='center'>No record found!</td></tr>";
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
    $sqlDelete = mysqli_query($con, "DELETE FROM wfh_application WHERE id='$id'");
    
    if ($sqlDelete) {
        echo "<script>";
        echo "alert('Item successfully removed!');";
        echo "window.location='?wfh_form';";
        echo "</script>";
    } else {
        echo "<script>";
        echo "alert('Unable to remove item!');";
        echo "window.location='?wfh_form';";
        echo "</script>";
    }
}
?>