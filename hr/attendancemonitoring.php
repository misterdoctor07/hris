<?php
$comp=$_GET['company'];
$startdate=$_GET['startdate'];
$enddate=$_GET['enddate'];
$sqlName=mysqli_query($con,"SELECT companyname FROM settings WHERE companycode='$comp'");
$companyname=mysqli_fetch_array($sqlName);
?>
            <?php
            if(isset($_GET['edit'])){
                $comp=$_GET['company'];
                $startdate=$_GET['startdate'];
                $enddate=$_GET['enddate'];
                $idno=$_GET['idno'];
                $logindate=$_GET['logindate'];
                $sqlProfile=mysqli_query($con,"SELECT * FROM points WHERE idno='$idno' AND logindate='$logindate'");
                if(mysqli_num_rows($sqlProfile)>0){
                  $point=mysqli_fetch_array($sqlProfile);
                  $logindate=$point['logindate'];
                  $offense=$point['offense'];
                  $point_id=$point['id'];
                }else{
                  $offense="";
                  $point_id="";
                }
                $sqlOffense=mysqli_query($con,"SELECT * FROM offense WHERE id='$offense'");
                if(mysqli_num_rows($sqlOffense)>0){
                  $off=mysqli_fetch_array($sqlOffense);
                  $offdescription=$off['description'];
                }else{
                  $offdescription="";
                }
            ?>
            <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">
              <h4>
                <a href="javascript:history.back();"><i class="fa fa-times"></i></a> | Manage Attendance Infractions
            </h4>
            </div>
            <div class="panel-body">
                <form name="f2" method="GET">
                  <input type="hidden" name="attendancemonitoringsummary">
                  <input type="hidden" name="company" value="<?=$comp;?>">
                  <input type="hidden" name="startdate" value="<?=$startdate;?>">
                  <input type="hidden" name="enddate" value="<?=$enddate;?>">
                  <input type="hidden" name="idno" value="<?=$idno;?>">
                  <input type="hidden" name="logindate" value="<?=$logindate;?>">
                  <input type="hidden" name="point_id" value="<?=$point_id;?>">
                  <div class="form-group">
                    <label>Date</label>
                      <input type="date" class="form-control" value="<?=$logindate;?>" disabled>
                  </div>
                  <div class="form-group">
    <label>Offense</label>
    <select class="form-control" name="offense" required>
        <option value="">Select Offense</option> <!-- Blank option to reset selection -->
        <?php
        // Check if the form was submitted
        $selectedOffense = isset($_GET['offense']) ? $_GET['offense'] : ''; // Get the selected offense ID from the form
        
        // Fetch all offenses from the database
        $sqlOffense = mysqli_query($con, "SELECT * FROM offense WHERE title LIKE '%Attendance%'");
        if(mysqli_num_rows($sqlOffense) > 0){
            while($off = mysqli_fetch_array($sqlOffense)){
                $isSelected = ($off['id'] == $selectedOffense) ? 'selected' : ''; // Check if this offense is selected
                echo "<option value='$off[id]' $isSelected>$off[description]</option>";
            }
        }
        ?>
    </select>
</div>
                    <div class="form-group">
                      <input type="submit" value="submit" name="submitInfraction" class="btn btn-primary">
                  </div>
                </form>
            </div>
            </div>
            </div>
            <?php
            }
            ?>
         <?php
         if (isset($_GET['submitInfraction'])) {
          $comp = $_GET['company'];
          $startdate = $_GET['startdate'];
          $enddate = $_GET['enddate'];
          $idno = $_GET['idno'];
          $logindate = $_GET['logindate'];
          $offense = $_GET['offense'];
      
          // Fetch offense details
          $sqlOffense = mysqli_query($con, "SELECT * FROM offense WHERE id='$offense'");
          $off = mysqli_fetch_array($sqlOffense);
      
          $code = str_replace('Attendance Infraction ', '', $off['title']);
          $penalty = $off['fpoints']; // Penalty points
          $frequency = $off['frequency'] - 1; // Frequency threshold
          $points = $off['points']; // Default points for the offense
      
          // Calculate the frequency of the offense in the current year
          $yearStart = date('Y') . "-01-01"; // Start of the current year
          $yearEnd = date('Y') . "-12-31";   // End of the current year
          $freq = 0;
      
          $sqlCheckInstance = mysqli_query($con, "SELECT * FROM offense WHERE category='$off[category]'");
          if (mysqli_num_rows($sqlCheckInstance) > 0) {
              while ($ins = mysqli_fetch_array($sqlCheckInstance)) {
                  $sqlInstance = mysqli_query($con, "SELECT * 
                      FROM points 
                      WHERE logindate BETWEEN '$yearStart' AND '$yearEnd' 
                        AND offense = '$ins[id]' 
                        AND idno = '$idno'
                  ");
                  $freq += mysqli_num_rows($sqlInstance);
              }
          }
      
          // Adjust points if frequency exceeds the threshold
          if ($freq >= $frequency) {
              $points += $penalty;
          }
      
          // Insert a new record for the infraction
          $sqlInsert = mysqli_query($con, "INSERT INTO points (idno, logindate, points, offense) VALUES ('$idno', '$logindate', '$points', '$offense')");
      
          
      if ($sqlInsert) {
        // Retrieve the current remarks
        $sqlRemarks = mysqli_query($con, "SELECT remarks FROM attendance WHERE logindate='$logindate' AND idno='$idno'");
        $existingRemarks = mysqli_fetch_array($sqlRemarks);
        
        // Store existing remarks in $previousRemarks
        $previousRemarks = $existingRemarks['remarks'];
    
        // Update remarks by appending the new offense
        $newRemarks = $code; // Start with the new code
        if (!empty($previousRemarks)) {
            $newRemarks = $code; // Append if there are existing remarks
        }
    
        // Update the attendance table with new remarks
        $sqlUpdateRemarks = mysqli_query($con, "UPDATE attendance SET remarks='$newRemarks', previousRemarks='$previousRemarks' WHERE logindate='$logindate' AND idno='$idno'");
    
        if ($sqlUpdateRemarks) {
          echo "<script>alert('Remarks Updated!');window.history.back();</script>";
        } else {
            echo "<script>alert('Unable to update remarks!');window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Unable to insert infraction!');window.history.back();</script>";
    }
    
                }
      

            if(isset($_GET['deletetime'])){
              $idno=$_GET['idno'];
              $id=$_GET['id'];
              $company=$_GET['company'];
              $startdate=$_GET['startdate'];
              $enddate=$_GET['enddate'];
              $logindate=$_GET['logindate'];
              $sqlDelete=mysqli_query($con,"DELETE FROM attendance WHERE id='$id'");
              if($sqlDelete){
                $delete=mysqli_query($con,"DELETE FROM points WHERE idno='$idno' AND logindate='$logindate'");
              echo "<script>";
                echo "alert('Item successfully removed!');window.history.back();</script>";
              echo "</script>";
            }else{
              echo "<script>";
                echo "alert('Unable to delete time!');window.history.back();</script>";
              echo "</script>";
              }
            }
         
          
            ?>
