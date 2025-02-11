<?php
$id=$_GET["id"];
$sqlOvertime=mysqli_query($con,"SELECT * FROM overtime_application WHERE id='$id'");
$overtime=mysqli_fetch_array($sqlOvertime);
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
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
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
    if(isset($_GET['submit'])){        
        $idno=$_SESSION['idno'];
        $id=$_GET['id'];
        $addedby=$_GET['addedby'];
        $datenow=date('Y-m-d');
        $timenow=date('H:i:s');        
        $otdate=$_GET['otdate'];
        $reasons=$_GET['reasons'];
        $ottime=$_GET['ottime'];        
            $table="overtime_application";
            $values="SET otdate='$otdate',ottime='$ottime',reasons='$reasons',datearray='$datenow',timearray='$timenow' WHERE id='$id'";
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