<?php
    $sqlMemo=mysqli_query($con,"SELECT memonumber FROM memos ORDER BY id DESC LIMIT 1");
   
    ?>
   <!-- Include Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    // Apply Select2 to dropdowns
    $('select').select2({
        placeholder: "Select an option",
        allowClear: true
    });
});

function SubmitDetails() {
    return confirm('Do you wish to submit details?');
}
</script>

    </script>
    <div class="row">
      <div class="col-lg-12">
      <h4 style="text-indent: 10px;"><a href="?managememo"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-file-text"></i> COMPANY MEMO</h4>      
    </div>
    </div>
    <form class="form-horizontal style-form" method="GET" onSubmit="return SubmitDetails();">
      <input type="hidden" name="addmemo">            
      <input type="hidden" name="addedby" value="<?=$fullname;?>">          
    <div class="col-lg-4 mt">
            <div class="content-panel">
              <div class="panel-heading">                
                <input type="submit" name="submit" class="btn btn-primary" value="Save Details" style="float:right;">
              <h4><i class="fa fa-file-text"></i> ISSUE A MEMO</h4>            
            </div>
            <div class="panel-body"> 
            <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">COMPANY</label>
                  <div class="col-sm-7">
                    <textarea name="company" class="form-control" rows="1"></textarea>
                  </div>
                </div>                                           
            <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">SUBJECT:</label>
                  <div class="col-sm-7">
                    <textarea name="memotext" class="form-control" rows="5"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Date Issued</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="dateissued" value="<?=date('M-d-Y');?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-4 col-sm-4 control-label">Date Served</label>
                  <div class="col-sm-5">
                    <input type="date" class="form-control" name="dateserved" value="<?=date('M-d-Y');?>">
                  </div>
                </div>
                
            </div>
          </div>
          <!-- col-lg-12-->
        </div>                
        </form>
        <?php
// Fetch the latest memonumber and increment it
$sqlMemo = mysqli_query($con, "SELECT memonumber FROM memos ORDER BY id DESC LIMIT 1");
$lastMemo = mysqli_fetch_assoc($sqlMemo);
$lastMemoNumber = $lastMemo ? $lastMemo['memonumber'] : '25-0000';

// Extract the numeric part from the last memonumber and increment it
$lastNumber = (int)substr($lastMemoNumber, 3); // Remove the '24-' part and convert the remaining to integer
$nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // Increment and pad with leading zeros
$newMemoNumber = '25-' . $nextNumber;

// Handling form submission
// Handling form submission
if (isset($_GET['submit'])) {
    $datenow = date('Y-m-d H:i:s');
    $id = isset($_GET['id']) ? $_GET['id'] : null; // Check if 'id' exists
    $company=$_GET['company'];
    $dateissued = $_GET['dateissued'];
    $dateserved = $_GET['dateserved'];
    $memo = $newMemoNumber;  // Use the generated memo number
    $memotext = $_GET['memotext'];

    // Check if the memonumber already exists
    $sqlCheck = mysqli_query($con, "SELECT * FROM memos WHERE memonumber='$memo'");
    if (mysqli_num_rows($sqlCheck) > 0) {
        echo "<script>";
        echo "alert('Memo number already in use!');window.history.back();";
        echo "</script>";
    } else {
        // Insert the new record into the database
        $table = "memos(dateissued,company, dateserved, memonumber, memotext)"; // Ensure columns are correct
        $values = "VALUES('$dateissued','$company','$dateserved','$memo','$memotext')"; // Use $memo instead of $memonumber
        $sqlAddEmployee = mysqli_query($con, "INSERT INTO $table $values");

        if ($sqlAddEmployee) {
            echo "<script>";
            echo "alert('Details successfully saved!');window.location='?addmemo';";
            echo "</script>";
        } else {
            echo "<script>";
            echo "alert('Unable to save details!');window.location='?addmemo';";
            echo "</script>";
        }
    }
}


