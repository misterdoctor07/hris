<?php
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

  if (!isset($_SESSION['idno'])) {
      die("<script>alert('Session expired. Please log in again.'); window.location='/index.php';</script>");
  }
  $id=$_GET["id"];
  $sqlOvertime=mysqli_query($con,"SELECT * FROM overtime_application WHERE id='$id'");
  $overtime=mysqli_fetch_array($sqlOvertime);
?>
<style>
    /* Hide the checkbox */
    .toggle {
        display: none;
    }

    /* Toggle container */
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

    /* Tooltip styles */
    .tooltip {
        position: absolute;
        bottom: 35px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.85);
        color: #fff;
        padding: 6px 10px;
        border-radius: 5px;
        font-size: 12px;
        white-space: nowrap;
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        z-index: 10;
    }

    /* Arrow for tooltip */
    .tooltip::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border-width: 6px;
        border-style: solid;
        border-color: rgba(0, 0, 0, 0.85) transparent transparent transparent;
    }

    /* Show tooltip on hover */
    .tooltip-container:hover .tooltip {
        visibility: visible;
        opacity: 1;
    }

    /* Show tooltip when clicked */
    .tooltip.active {
        visibility: visible;
        opacity: 1;
    }

    /* Ensuring no cropping issue */
    .tooltip-container {
        position: relative;
        display: inline-block;
        overflow: visible;
    }

        body{
        background-color: #f0f2f5
    }
    .centered-container {
        display: flex;
        justify-content: center;
        padding: 20px;
    }

    .content-panel {
        background-color: #fff;
        border-radius: 30px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px;
        overflow: hidden;
        padding-top: 0px;
    }

    .panel-heading {
        background-color: #21283a;
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .panel-heading h4 {
        margin: 0;
        font-weight: bold;
        flex-grow: 1;
        text-align: center;
    }

    .panel-body {
        padding: 20px;
    }

    .panel-footer {
        padding: 15px 20px;
        text-align: center;
        border-top: none;
        background-color: #fff;
    }

    .form-label {
        font-weight: bold;
    }

    .form-control {
        border: none;
        border-bottom: 1px solid #ccc; /* Adjust color as needed */
        border-radius: 0;
        background-color: transparent; /* This lets the group-box background show through */
        color: inherit;
        box-shadow: none; /* Removes Bootstrap focus shadow */
    }
    
    .form-control:focus {
        outline: none;
        border-bottom-color: #007bff; /* Optional: Change on focus */
        box-shadow: none;
    }
    .form-group {
        border-bottom: none !important;
    }
    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        position: relative;
        right: 3px;
    }

    .custom-select-wrapper {
        position: relative;
        width: 100%;
    }

    .custom-select {
        width: 100%;
        appearance: none;           /* Hide default arrow (WebKit) */
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 30px;        /* Leave space for custom icon */
    }

    .custom-dropdown-icon {
        font-size: 25px;
        position: absolute;
        top: 50%;
        right: 13px;                /* Adjust this to control position */
        transform: translateY(-50%);
        pointer-events: none;       /* Allows click through to select */
    }
</style>
<script type="text/javascript">
      function SubmitDetails(){        
          return confirm('Do you wish to submit details?');        
      }
      function confirmCancel() {
          if (confirm("Are you sure you want to cancel? Any unsaved changes will be lost.")) {
              window.location.href = "applyovertime.php";
          }
      }
</script>
<div class="centered-container">
  <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();" style="width: 100%; max-width: 500px;">
      <input type="hidden" name="editovertime">            
      <input type="hidden" name="addedby" value="<?=$fullname;?>">  
      <input type="hidden" name="id" value="<?=$id;?>">  
            <div class="content-panel">
              <div class="panel-heading d-flex align-items-center justify-content-between" style="position: relative;">
              <h4 class="mb-0 mx-auto text-center" style="flex: 1;">EDIT FILED OVERTIME</h4>
          </div>
            <div class="panel-body">
                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                    <label class="form-label" style="margin-right: 75px;">Type of OT</label>
                    <div class="tooltip-container">
                        <input id="toggle"
                              class="toggle"
                              type="checkbox"
                              name="ot_type"
                              value="IT-related"
                              onchange="toggleTooltip(); updateLabelText(this);"
                              <?= ($overtime['ot_type'] === 'IT-related') ? 'checked' : '' ?>>
                        <label for="toggle" class="slot"></label>
                        <span id="label-text" class="label-text">
                            <?= ($overtime['ot_type'] === 'IT-related') ? 'IT-related' : 'Not IT-related' ?>
                        </span>
                        <span id="tooltip" class="tooltip">Click only if you are an IT staff from Satellite Office and will be performing IT-related tasks.</span>
                    </div>
                </div>                                              
                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                  <label class="form-label">Date of OT</label>
                  <input type="date" name="otdate" class="form-control" required value="<?=$overtime['otdate'];?>">
                </div>
                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                  <label class="form-label">Time of OT</label>
                  <input type="time" name="ottime" class="form-control" required value="<?= date('H:i', strtotime($overtime['ottime'])); ?>">
                </div>
                <div class="form-group mb-3" style="margin: 10px; margin-bottom: 30px;">
                  <label class="form-label">Reason(s)</label>
                  <textarea name="reasons" class="form-control" rows="5" required><?=$overtime['reasons'];?></textarea>
                </div>  
                <div class="text-center" style="margin: 10px; margin-top: 70px;">
                    <!-- Cancel Button (no form submit) -->
                    <button type="button" class="btn btn-danger" style="width: 80px; border-radius: 20px; height: 40px;" onclick="confirmCancel()">
                        Cancel
                    </button>
                
                    <!-- Submit Button (form submit) -->
                    <input type="submit" id="submitBtn" name="submit" class="btn btn-success" value="Save Changes"
                        style="width: 200px; border-radius: 20px; height: 40px;">
                </div>              
            </div>
          </div>
          <!-- col-lg-12-->
        </div>                
  </form>
</div>
  <?php
    if(isset($_GET['submit'])){        
        $idno=$_SESSION['idno'];
        $id=$_GET['id'];
        $addedby=$_GET['addedby'];
        $datenow=date('Y-m-d');
        $timenow=date('H:i:s');       
        $ot_type=$_GET['ot_type'];        
        $otdate=$_GET['otdate'];
        $reasons=$_GET['reasons'];
        $ottime=$_GET['ottime'];        
            $table="overtime_application";
            $values="SET otdate='$otdate', ot_type='$ot_type',ottime='$ottime',reasons='$reasons',datearray='$datenow',timearray='$timenow' WHERE id='$id'";
            $sqlAddEmployee=mysqli_query($con,"UPDATE $table $values");
            if($sqlAddEmployee){
                echo "<script>";
                echo "alert('Details successfully saved!');window.location='applyovertime.php'";
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

    function toggleTooltip() {
        const tooltip = document.getElementById("tooltip");
        
        // Show tooltip
        tooltip.classList.add("active");

        // Hide after 3 seconds
        setTimeout(() => {
            tooltip.classList.remove("active");
        }, 1000);
    }
</script>