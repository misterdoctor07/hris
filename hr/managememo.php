<?php
// Handle delete request
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']); // Sanitize the ID

    // Delete query
    $deleteQuery = "DELETE FROM memos WHERE id='$id'";

    if (mysqli_query($con, $deleteQuery)) {
        echo "<script>alert('Memo deleted successfully!'); window.location.href='?managememo';</script>";
    } else {
        echo "<script>alert('Error deleting memo: " . mysqli_error($con) . "');</script>";
    }
}
?>

<div class="col-lg-8">
    <div class="content-panel">
        <div class="panel-heading">
            <h4>
                <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-bullhorn"></i> MEMO
                <div style="float:right;">
                    <a href="?addmemo" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add Memo</a>
                </div>
            </h4>
        </div>
        <div class="panel-body">
            <div class="adv-table">
                <table class="display table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Memo Number</th>
                            <th>Company</th>
                            <th>Subject</th>
                            <th>Date Issued</th>
                            <th>Time Served</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sqlQuery = "SELECT * FROM memos";
                    $sqlCompany = mysqli_query($con, $sqlQuery);

                    if (mysqli_num_rows($sqlCompany) > 0) {
                        $x = 1;
                        while ($company = mysqli_fetch_array($sqlCompany)) {
                            echo "<tr>";
                            echo "<td width='3%'>$x.</td>";
                            echo "<td >{$company['memonumber']}</td>";
                            echo "<td width='10%'>$company[company]</td>";
                            echo "<td align='left'>{$company['memotext']}</td>";
                            echo "<td>{$company['dateissued']}</td>";
                            echo "<td>{$company['dateserved']}</td>";
                            echo "<td align='center'>
                                <a href='?managememo&id={$company['id']}&editnew' class='btn btn-primary btn-xs' title='Edit Memo'>
                                    <i class='fa fa-pencil'></i>
                                </a>
                                <a href='?managememo&id={$company['id']}&delete' 
                                   class='btn btn-danger btn-xs' 
                                   title='Delete Memo' 
                                   onclick=\"return confirm('Do you wish to delete this announcement?');\">
                                    <i class='fa fa-trash-o'></i>
                                </a>
                            </td>";
                            echo "</tr>";
                            $x++;
                        }
                    } else {
                        echo "<tr><td colspan='6' align='center'>No record found!</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// Edit Announcement Form
if (isset($_GET['editnew']) && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $sqlMemo = mysqli_query($con, "SELECT * FROM memos WHERE id='$id'");
    $memo = mysqli_fetch_array($sqlMemo);
    $company = $memo['company'];
    $details = $memo['memotext'];
    $dateissued = $memo['dateissued'];
    $dateserved = $memo['dateserved'];

    if (isset($_POST['updateMemo'])) {
        $company = mysqli_real_escape_string($con, $_POST['company']);
        $memotext = mysqli_real_escape_string($con, $_POST['memotext']);
        $dateissued = mysqli_real_escape_string($con, $_POST['dateissued']);
        $dateserved = mysqli_real_escape_string($con, $_POST['dateserved']);

        $updateQuery = "UPDATE memos SET memotext='$memotext', dateissued='$dateissued', dateserved='$dateserved', company='$company' WHERE id='$id'";

        if (mysqli_query($con, $updateQuery)) {
            echo "<script>alert('Memo updated successfully!'); window.location.href='?managememo';</script>";
        } else {
            echo "<script>alert('Error updating memo: " . mysqli_error($con) . "');</script>";
        }
    }
?>
<div class="col-lg-4">
    <div class="content-panel">
        <div class="panel-heading">
            <h4>
                <a href="?managememo"><i class="fa fa-arrow-left"></i> Close</a> | <i class="fa fa-file-text"></i> Edit Memo
            </h4>
        </div>
        <div class="panel-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $id; ?>">
                <div class="form-group">
                    <label for="company">Company:</label>
                    <textarea name="company" class ="form-control" rows="1"><?= $company; ?></textarea>
                </div>  
                <div class="form-group">
                    <label for="memotext">Subject:</label>
                    <textarea name="memotext" class="form-control" rows="4"><?= $details; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="dateissued">Date Issued</label>
                    <input type="date" name="dateissued" class="form-control" value="<?= $dateissued; ?>">
                </div>
                <div class="form-group">
                    <label for="dateserved">Date Served</label>
                    <input type="date" name="dateserved" class="form-control" value="<?= $dateserved; ?>">
                </div>
                <div class="form-group">
                    <input type="submit" name="updateMemo" class="btn btn-success" value="Save Changes">
                </div>
            </form>
        </div>
    </div>
</div>
<?php
}
?>
