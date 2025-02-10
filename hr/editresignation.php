<?php
$idno=$_GET['id'];
$empname=$_GET['empname'];
$sqlResignation=mysqli_query($con,"SELECT * FROM resignation WHERE id='$idno'");
$resign=mysqli_fetch_array($sqlResignation);
?>
<div class="row">
  <div class="col-lg-12">
  <h4 style="text-indent: 10px;"><a href="?viewresignedemployee"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-text"></i> EDIT RESIGNATION DETAILS</h4>
</div>
</div>
<form class="form-horizontal style-form" method="GET" onSubmit="return confirm('Do you wish to submit details?');return false;">
  <input type="hidden" name="editresignation">
  <input type="hidden" name="addedby" value="<?=$empname;?>">
  <input type="hidden" name="id" value="<?=$idno;?>">
<div class="col-lg-4">
        <div class="content-panel">
          <div class="panel-heading">
            <input type="submit" name="submit" class="btn btn-primary" value="Save Details" style="float:right;">
          <h4><i class="fa fa-user"></i> <?=$empname;?></h4>
        </div>
        <div class="panel-body">
        <div class="form-group">
              <label class="col-sm-4 col-sm-4 control-label">Resignation Date</label>
              <div class="col-sm-5">
                <input type="date" class="form-control" name="dateresigned" value="<?=$resign['dateresigned'];?>">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4 col-sm-4 control-label">Reasons</label>
              <div class="col-sm-7">
                <textarea name="reason" class="form-control" rows="5"><?=$resign['reason'];?></textarea>
              </div>
            </div>
        </div>
      </div>
      <!-- col-lg-12-->
    </div>
    </form>

    <?php
    if(isset($_GET['submit'])){
      $dateresigned=$_GET['dateresigned'];
      $reason=$_GET['reason'];
      $id=$_GET['id'];
      $empname=$_GET['addedby'];
      $sqlResign=mysqli_query($con,"UPDATE resignation SET dateresigned='$dateresigned',reason='$reason' WHERE id='$id'");
      if($sqlResign){
        echo "<script>alert('Details successfuly saved!'); window.location='?editresignation&id=$id&empname=$empname';</script>";
      }else{
        echo "<script>alert('Unable to save details!'); window.location='?editresignation&id=$id&empname=$empname';</script>";
      }
    }
    ?>
