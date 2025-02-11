<?php
$id=$_GET['id'];
    $sqlMemo=mysqli_query($con,"SELECT * FROM infraction WHERE id='$id'");
    if(mysqli_num_rows($sqlMemo)>0){
      $mem=mysqli_fetch_array($sqlMemo);      
    }
    $sqlEmployee=mysqli_query($con,"SELECT * FROM employee_profile WHERE idno='$mem[idno]'");
    $employee=mysqli_fetch_array($sqlEmployee);
    ?>
    <script type="text/javascript">
      function SubmitDetails(){        
          return confirm('Do you wish to submit details?');        
      }
    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?manageinfraction"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-text"></i> EMPLOYEE INFRACTION</h4>      
    </div>
    </div>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="editinfraction">            
      <input type="hidden" name="id" value="<?=$id;?>">
      <input type="hidden" name="addedby" value="<?=$fullname;?>">          
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">                
                <input type="submit" name="submit" class="btn btn-primary" value="Save Details" style="float:right;">
              <h4><i class="fa fa-file-text"></i> UPDATE INFRACTION</h4>            
            </div>
            <div class="panel-body">                                            
            <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Memo No.</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" name="memonumber" value="<?=$mem['memonumber'];?>"readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Employee</label>
                  <div class="col-sm-7">
                    <select name="idno" class="form-control" required>
                        <option value="<?=$employee['idno'];?>"><?=$employee['lastname'];?>, <?=$employee['firstname'];?></option>
                        <?php
                        $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%'");
                        if(mysqli_num_rows($sqlEmployee)>0){
                            while($employee=mysqli_fetch_array($sqlEmployee)){
                                echo "<option value='$employee[idno]'>$employee[lastname], $employee[firstname]</option>";
                            }
                        }
                        ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Date Issued</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="dateissued" value="<?=$mem['dateissued'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Date Served</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="dateserved" value="<?=$mem['dateserved'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Type of Memo</label>
                  <div class="col-sm-7">
                    <select name="memotype" class="form-control" required>
                        <option value="<?=$mem['typeofmemo'];?>"><?=$mem['typeofmemo'];?></option>
                        <?php
                        $sqlEmployee=mysqli_query($con,"SELECT * FROM memo");
                        if(mysqli_num_rows($sqlEmployee)>0){
                            while($employee=mysqli_fetch_array($sqlEmployee)){
                                echo "<option value='$employee[title]'>$employee[title]</option>";
                            }
                        }
                        ?>
                    </select>
                  </div>
                </div>                
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Type of Offense</label>
                  <div class="col-sm-7">
                    <select name="offense" class="form-control" required>
                        <option value="<?=$mem['typeofoffense'];?>"><?=$mem['typeofoffense'];?></option>
                        <?php
                        $sqlEmployee=mysqli_query($con,"SELECT * FROM offense");
                        if(mysqli_num_rows($sqlEmployee)>0){
                            while($employee=mysqli_fetch_array($sqlEmployee)){
                                echo "<option value='$employee[title]'>$employee[title]</option>";
                            }
                        }
                        ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Date Incident</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="dateofincident" value="<?=$mem['dateofincident'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Points</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="points" style="text-align:center;" value="<?=$mem['points'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Suspension Date</label>
                  <div class="col-sm-7">
                    <textarea name="datesuspension" class="form-control" rows="5"><?=$mem['dateofsuspension'];?></textarea>
                  </div>
                </div>                
            </div>
          </div>
          
          <!-- col-lg-12-->
        </div>                
        </form>
  <?php
    if(isset($_GET['submit'])){        
        $id=$_GET['id'];
        $idno=$_GET['idno'];
        $addedby=$_GET['addedby'];
        $datenow=date('Y-m-d H:i:s');
        $dateissued=$_GET['dateissued'];
        $dateserved=$_GET['dateserved'];
        $memotype=$_GET['memotype'];  
        $memo=$_GET['memonumber'];  
        $offense=$_GET['offense'];      
        $points=$_GET['points'];   
        $dateofincident=$_GET['dateofincident'];
        $datesuspension=$_GET['datesuspension'];     
        $sqlCheck=mysqli_query($con,"SELECT * FROM infraction WHERE memonumber='$memo' AND id <> '$id'");
        if(mysqli_num_rows($sqlCheck)>0){
          echo "<script>";
          echo "alert('Memo number already in used!');window.history.back();";
        echo "</script>";
        }else{
            $table="infraction";
            $values="SET idno='$idno', dateissued='$dateissued', dateserved='$dateserved', typeofmemo='$memotype', typeofoffense='$offense', dateofincident='$dateofincident', points='$points', memonumber='$memo', dateofsuspension='$datesuspension', updatedby='$addedby', updateddatetime='$datenow' WHERE id='$id'";
            $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");

            $table2="infraction_view_status";
            $values2="SET view_status='updated' WHERE infraction_id='$id'";
            $sqlAddEmployee2=mysqli_query($con,"UPDATE $table2 $values2");
        }
      if($sqlAddEmployee){
        echo "<script>";
          echo "alert('Details successfully saved!');window.location='?editinfraction&id=$id';";
        echo "</script>";
      }else{
        echo "<script>";
          echo "alert('Unable to saved details!');window.location='?editinfraction&id=$id;";
        echo "</script>";
      }
    }
  ?>