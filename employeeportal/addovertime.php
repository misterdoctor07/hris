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
<script type="text/javascript">
    function SubmitDetails(){        
        return confirm('Do you wish to submit details?');        
    }
</script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?applyovertime"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-text"></i> OVERTIME APPLICATION</h4>      
    </div>
    </div>
    <form class="form-horizontal style-form" method="POST" onSubmit="return SubmitDetails();">
      <input type="hidden" name="addovertime">            
      <input type="hidden" name="addedby" value="<?=$fullname;?>">          
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">                
                <input type="submit" name="submit" class="btn btn-primary" value="Submit Details" style="float:right;">
              <h4><i class="fa fa-file-text"></i> APPLY FOR OVERTIME</h4>            
            </div>
            <div class="panel-body">  
                <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Type of OT</label>
                    <div class="col-sm-8">
                        <input id="toggle" class="toggle" type="checkbox" name="ot_type" value="IT-related" onchange="updateLabelText(this)">
                        <label for="toggle" class="slot"></label>
                        <span id="label-text" class="label-text">Not IT-related</span>
                    </div>
                </div>                                              
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Date of OT</label>
                  <div class="col-sm-8">
                    <input type="date" name="otdate" class="form-control" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Time of OT</label>
                  <div class="col-sm-8">
                    <input type="time" name="ottime" class="form-control" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Reason(s)</label>
                  <div class="col-sm-8">
                    <textarea name="reasons" class="form-control" rows="5" required></textarea>
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
        $addedby=$_POST['addedby'];
        $datenow=date('Y-m-d');
        $timenow=date('H:i:s');        
        $otdate=$_POST['otdate'];
        $reasons=$_POST['reasons'];
        $ottime=$_POST['ottime'];        
        $ot_type = isset($_POST['ot_type']) ? $_POST['ot_type'] : 'Not IT-related';
            $table="overtime_application(idno,ot_type,otdate,ottime,reasons, app_status, datearray,timearray)";
            $values="VALUES('$idno','$ot_type','$otdate','$ottime','$reasons', 'Pending', '$datenow','$timenow')";
            $sqlAddEmployee=mysqli_query($con,"INSERT INTO $table $values");
            if($sqlAddEmployee){
                echo "<script>";
                echo "alert('Details successfully saved!');window.location='?addovertime';";
                echo "</script>";
            }else{
                echo "<script>";
                echo "alert('Unable to saved details!');window.location='?addovertime;";
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