<style>
  /* Hide the checkbox */
  .toggle {
    display: none;
  }

  /* Toggle container with proper alignment */
  .slot {
    display: inline-block;
    width: 50px;
    height: 24px;
    background: #ddd;
    border-radius: 30px;
    position: relative;
    cursor: pointer;
    vertical-align: middle;
    transition: background-color 0.3s;
  }

  /* Circle inside the toggle */
  .slot::before {
    content: '';
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    position: absolute;
    top: 2px;
    left: 2px;
    transition: all 0.3s ease;
  }

  /* Checked state styles */
  input.toggle:checked + .slot {
    background: #1e90ff;
  }

  input.toggle:checked + .slot::before {
    left: 28px;
  }

  /* Label styles */
  .label-text {
    font-size: 14px;
    color: #555;
    margin-left: 12px;
    vertical-align: middle;
    display: inline-block;
  }
</style>
<?php
$id=$_GET["id"];
$sqlOvertime=mysqli_query($con,"SELECT * FROM overtime_application WHERE id='$id'");
$overtime=mysqli_fetch_array($sqlOvertime);
$userId = $_SESSION['idno'];
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $logId = $_GET['id'];
    $sqlOTDetails = mysqli_query($con, "SELECT * FROM overtime_application WHERE id='$logId'");
    if ($sqlOTDetails && mysqli_num_rows($sqlOTDetails) > 0) {
        $OTDetails = mysqli_fetch_array($sqlOTDetails);
        $ottype = $OTDetails['ot_type'];
        $otdate = $OTDetails['otdate'];
        $ottime = date('H:i', strtotime($OTDetails['ottime'])); // Convert to HH:mm format
        $reason = $OTDetails['reasons'];
    } else {
        echo "<script>alert('EEO application not found!');</script>";
        echo "<script>window.location='?emergencyearlyout';</script>";
        return;
    }
} else {
    echo "<script>alert('EEO ID not provided!');</script>";
    echo "<script>window.location='?emergencyearlyout';</script>";
    return;
}
?>
<script type="text/javascript">
      function SubmitDetails(){        
          return confirm('Do you wish to submit details?');        
      }
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?applyovertime"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-text"></i> UPDATE OVERTIME APPLICATION</h4>      
    </div>
    </div>
    <form class="form-horizontal style-form" method="POST" onSubmit="return SubmitDetails();">
      <input type="hidden" name="editovertime">            
      <input type="hidden" name="addedby" value="<?=$fullname;?>">  
      <input type="hidden" name="id" value="<?=$id;?>">  
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">                
                <input type="submit" name="submit" class="btn btn-primary" value="Submit Details" style="float:right;">
              <h4><i class="fa fa-file-text"></i> UPDATE OVERTIME DETAILS</h4>            
            </div>
            <div class="panel-body">  
                <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Type of OT</label>
                    <div class="col-sm-8">
                        <!-- Hidden input to handle unchecked state -->
                        <input type="hidden" name="ot_type" value="Not IT-related">
                        
                        <input id="toggle" 
                            class="toggle" 
                            type="checkbox" 
                            name="ot_type" 
                            value="IT-related" 
                            <?= ($ottype == 'IT-related') ? 'checked' : ''; ?> 
                            onchange="updateLabelText(this)">
                        <label for="toggle" class="slot"></label>
                        <span id="label-text" class="label-text">
                        <?= ($ottype == 'IT-related') ? 'IT-related' : 'Not IT-related'; ?>
                        </span>
                    </div>
                </div>                                            
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Date of OT</label>
                  <div class="col-sm-8">
                    <input type="date" name="otdate" class="form-control" required value="<?=$overtime['otdate'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Time of OT</label>
                  <div class="col-sm-8">
                    <input type="text" name="ottime" class="form-control" required value="<?=$overtime['ottime'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Reason(s)</label>
                  <div class="col-sm-8">
                    <textarea name="reasons" class="form-control" rows="5" required><?=$overtime['reasons'];?></textarea>
                  </div>
                </div>                
            </div>
          </div>
          <!-- col-lg-12-->
        </div>                
        </form>
  <?php
    if(isset($_POST['submit'])){        
        $idno=$_SESSION['idno'];
        $id=$_POST['id'];
        $addedby=$_POST['addedby'];
        $datenow=date('Y-m-d');
        $timenow=date('H:i:s');        
        $otdate=$_POST['otdate'];
        $reasons=$_POST['reasons'];
        $ottime=$_POST['ottime'];    
        $ottype = $_POST['ot_type'];    
            $table="overtime_application";
            $values="SET otdate='$otdate', ot_type='$ottype',ottime='$ottime',reasons='$reasons',datearray='$datenow',timearray='$timenow' WHERE id='$id'";
            $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");
            if($sqlAddEmployee){
                echo "<script>";
                echo "alert('Details successfully saved!');window.location='?editovertime&id=$id';";
                echo "</script>";
            }else{
                echo "<script>";
                echo "alert('Unable to saved details!');window.location='?editovertime&id=$id;";
                echo "</script>";
            }            
    }
  ?>

<script>
  function updateLabelText(toggle) {
    const labelText = document.getElementById("label-text");
    labelText.textContent = toggle.checked ? "IT-related" : "Not IT-related";
  }
</script>