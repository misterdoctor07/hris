<div class="col-lg-8">
    <div class="content-panel">
        <div class="panel-heading">
            <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-bullhorn"></i> ANNOUNCEMENT<div style="float:right;"><a href="?announcement&addnew" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add Announcement</a></div></h4>
        </div>
        <div class="panel-body">
            <div class="adv-table">
                <?php
                // Add/edit announcement form
                if(isset($_GET['addnew']) || isset($_GET['editnew'])){
                    // Fetch departments and designations for dropdowns
                    $departments = [];
                    $designations = [];

                    $deptQuery = mysqli_query($con, "SELECT id, department FROM department");
                    while($row = mysqli_fetch_assoc($deptQuery)) {
                        $departments[$row['id']] = $row['department'];
                    }

                    $desigQuery = mysqli_query($con, "SELECT id, jobtitle FROM jobtitle");
                    while($row = mysqli_fetch_assoc($desigQuery)) {
                        $designations[$row['id']] = $row['jobtitle'];
                    }

                    $details = '';
                    $targets = ['departments' => [], 'designations' => []];
                    $isEdit = false;

                  if(isset($_GET['editnew'])) {
                $id = $_GET['id'];
                $sqlSafety = mysqli_query($con, "SELECT * FROM widgets WHERE id='$id'");
                $safety = mysqli_fetch_array($sqlSafety);
                $title = $safety['title'];
                $details = $safety['details'];
                $targets = json_decode($safety['targets'], true) ?: ['departments' => [], 'designations' => []];
                $isEdit = true;
            }
                ?>
                <div class="col-lg-12">
                    <div class="content-panel" style = "padding-top: 10px; padding-bottom: 5px; 	box-shadow: 0px 2px 2px #aab2bd;">
                        <div class="panel-heading">
                            <h4><a href="?announcement"><i class="fa fa-arrow-left"></i> Close</a> | <i class="fa fa-bullhorn"></i> <?= $isEdit ? 'UPDATE' : 'ADD' ?> ANNOUNCEMENT</h4>
                        </div>
                        <div class="panel-body">
                            <form name="f1" method="POST">
    <?php if($isEdit): ?>
        <input type="hidden" name="id" value="<?= $id ?>">
    <?php endif; ?>

    <!-- Title Input -->
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" name="title" class="form-control" placeholder="Enter announcement title" required value="<?= htmlspecialchars($title ?? '') ?>">
    </div>

    <!-- Announcement Details (Text Area) -->
    <div class="form-group">
        <label for="announcement">Details</label>
        <textarea name="announcement" class="form-control" rows="4" placeholder="Announcement details" required><?= htmlspecialchars($details) ?></textarea>
    </div>
                                <div class="form-group">
                        <label>Target Audience</label>
                        <div class="checkbox">
                            <label><input type="checkbox" name="target_all" id="targetAll" <?= empty($targets['departments']) && empty($targets['designations']) ? 'checked' : '' ?>> All Employees</label>
                        </div>
                    
                        <div id="specificTargets" style="<?= empty($targets['departments']) && empty($targets['designations']) ? 'display:none;' : '' ?>">
                            <div class="form-group">
                                <label style="font-weight:bold;">Departments:</label>
                                <div class="checkbox-container">
                                    <?php foreach($departments as $id => $name): ?>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="departments[]" value="<?= $id ?>" <?= in_array($id, $targets['departments']) ? 'checked' : '' ?>> <?= $name ?>
                                        </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-group" style="margin-top: 15px;">
            <label style="font-weight:bold;">Designations:</label>
            <div class="checkbox-container">
                <?php foreach($designations as $id => $name): ?>
                    <label class="checkbox-inline">
                        <input type="checkbox" name="designations[]" value="<?= $id ?>" <?= in_array($id, $targets['designations']) ? 'checked' : '' ?>> <?= $name ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .checkbox-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        max-width: 70%;
        padding: 5px;
        overflow-x: auto;
    }
    
    .checkbox-inline {
        white-space: nowrap;
        display: flex;
        align-items: center;
        padding: 5px 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background: #f8f9fa;
        cursor: pointer;
    }

    .checkbox-inline input {
        margin-right: 5px;
    }
</style>

<script>
document.getElementById('targetAll').addEventListener('change', function() {
    document.getElementById('specificTargets').style.display = this.checked ? 'none' : 'block';
});
</script>
                                
                                <div class="form-group">
                                    <input type="submit" name="<?= $isEdit ? 'updateAnnouncement' : 'submitAnnouncement' ?>" class="btn btn-success" value="Save Details">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                document.getElementById('targetAll').addEventListener('change', function() {
                    document.getElementById('specificTargets').style.display = this.checked ? 'none' : 'block';
                });
                </script>
                <?php } ?>

                <!-- Announcements Table -->
                <table class="display table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Details</th>
                            <th>Target Audience</th>
                            <th>Date Posted</th>
                            <th>Time Posted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $x=1;
                        $sqlCompany=mysqli_query($con,"SELECT * FROM widgets WHERE `type`='Announcement' ORDER BY datearray DESC");
                        if(mysqli_num_rows($sqlCompany)>0){
                            while($company=mysqli_fetch_array($sqlCompany)){
                                $targets = json_decode($company['targets'], true) ?: ['departments' => [], 'designations' => []];
                                $targetText = "All Employees";
                                
                                if(!empty($targets['departments']) || !empty($targets['designations'])) {
                                    $targetText = "";
                                    
                                    if(!empty($targets['departments'])) {
                                        $deptNames = [];
                                        foreach($targets['departments'] as $deptId) {
                                            $deptQuery = mysqli_query($con, "SELECT department FROM department WHERE id = '$deptId'");
                                            if(mysqli_num_rows($deptQuery) > 0) {
                                                $deptData = mysqli_fetch_assoc($deptQuery);
                                                $deptNames[] = $deptData['department'];
                                            }
                                        }
                                        $targetText .= "<div><strong>Departments:</strong> " . implode(", ", $deptNames) . "</div>";
                                    }
                                    
                                    if(!empty($targets['designations'])) {
                                        $desigNames = [];
                                        foreach($targets['designations'] as $desigId) {
                                            $desigQuery = mysqli_query($con, "SELECT jobtitle FROM jobtitle WHERE id = '$desigId'");
                                            if(mysqli_num_rows($desigQuery) > 0) {
                                                $desigData = mysqli_fetch_assoc($desigQuery);
                                                $desigNames[] = $desigData['jobtitle'];
                                            }
                                        }
                                        $targetText .= "<div><strong>Designations:</strong> " . implode(", ", $desigNames) . "</div>";
                                    }
                                }
                                
                                echo "<tr>";
                                echo "<td width='3%'>$x.</td>";
                                echo "<td>".htmlspecialchars($company['title'])."</td>";
                                echo "<td align='left'>$company[details]</td>";
                                echo "<td>$targetText</td>";
                                echo "<td>$company[datearray]</td>";
                                echo "<td>$company[timearray]</td>";
                                ?>
                                <td align="center">
                                    <a href="?announcement&id=<?=$company['id'];?>&editnew" class="btn btn-primary btn-xs" title="Edit Announcement"><i class='fa fa-pencil'></i></a>
                                    <a href="?announcement&id=<?=$company['id'];?>&delete" class="btn btn-danger btn-xs" title="Delete Announcement" onclick="return confirm('Do you wish to delete this announcement?'); return false;"><i class='fa fa-trash-o'></i></a>
                                </td>
                                <?php
                                echo "</tr>";
                                $x++;
                            }
                        }else{
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
// Handle form submissions
if (isset($_POST['submitAnnouncement'])) {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $details = mysqli_real_escape_string($con, $_POST['announcement']);
    $datenow = date('Y-m-d');
    $timenow = date('H:i:s');
    $type = "Announcement";
    
    $target_all = isset($_POST['target_all']) ? 1 : 0;
    $targets = [
        'departments' => $target_all ? [] : ($_POST['departments'] ?? []),
        'designations' => $target_all ? [] : ($_POST['designations'] ?? [])
    ];
    $targets_json = json_encode($targets);
    
    $sqlInsert = mysqli_query($con, "INSERT INTO widgets(title, details, `type`, datearray, timearray, targets) 
                                    VALUES('$title', '$details', '$type', '$datenow', '$timenow', '$targets_json')");
    
    if ($sqlInsert) {
        echo "<script>alert('Announcement successfully added!');window.location='?announcement';</script>";
    } else {
        echo "<script>alert('Unable to add announcement!');window.location='?announcement';</script>";
    }
}

if (isset($_POST['updateAnnouncement'])) {
    $id = $_POST['id'];
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $details = mysqli_real_escape_string($con, $_POST['announcement']);
    $datenow = date('Y-m-d');
    $timenow = date('H:i:s');
    
    $target_all = isset($_POST['target_all']) ? 1 : 0;
    $targets = [
        'departments' => $target_all ? [] : ($_POST['departments'] ?? []),
        'designations' => $target_all ? [] : ($_POST['designations'] ?? [])
    ];
    $targets_json = json_encode($targets);
    
    $sqlUpdate = mysqli_query($con, "UPDATE widgets SET title='$title', details='$details', datearray='$datenow', timearray='$timenow', targets='$targets_json' WHERE id='$id'");
    
    if ($sqlUpdate) {
        echo "<script>alert('Announcement successfully updated!');window.location='?announcement';</script>";
    } else {
        echo "<script>alert('Unable to update announcement!');window.location='?announcement';</script>";
    }
}


if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure ID is valid

    // Step 1: Delete related records in announcement_reads first
    $deleteReads = mysqli_query($con, "DELETE FROM announcement_reads WHERE announcement_id='$id'");
    
    if (!$deleteReads) {
        die("<script>alert('Error deleting related records: " . mysqli_error($con) . "');window.location='?announcement';</script>");
    }

    // Step 2: Now delete from widgets
    $sqlDelete = mysqli_query($con, "DELETE FROM widgets WHERE id='$id'");

    if ($sqlDelete) {
        echo "<script>alert('Announcement successfully deleted!');window.location='?announcement';</script>";
    } else {
        die("<script>alert('Unable to delete announcement! Error: " . mysqli_error($con) . "');window.location='?announcement';</script>");
    }
}


?>