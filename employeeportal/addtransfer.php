<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    include '../config.php';
    
    if (!isset($_SESSION['idno'])) {
        die("<script>alert('Session expired. Please log in again.'); window.location='/index.php';</script>");
    }
    $userID = $_SESSION['idno'];
?>
<script type="text/javascript">
function SubmitDetails() {
    return confirm('Do you wish to submit details?');
}
</script>
<style>
    body{
        background-color: #f0f2f5
    }
    .centered-container {
        display: flex;
        justify-content: center;
    }

    .content-panel {
        background-color: #fff;
        border-radius: 30px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
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
        margin: 0px;
        font-weight: bold;
        flex-grow: 1;
        text-align: center;
    }
    
    .panel-body {
        padding-left, padding-right: 30px;
    }
    
    .panel-footer {
        padding: 15px 20px;
        text-align: center;
        border-top: none;
    }

    .form-label {
        font-weight: bold;
    }
    
    .form-group {
        border-bottom: none !important;
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
    
    input.form-control[readonly] {
        background-color: transparent;
        border: none;
        border-bottom: 2px solid #ccc;
        border-radius: 0;
        box-shadow: none;
        color: #000;
    }
    
    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        position: relative;
        right: -12px;
    }
</style>
<div class="centered-container">
    <form class="form-horizontal style-form" method="POST" onSubmit="return SubmitDetails();" style="width: 100%; max-width: 500px;">
        <input type="hidden" name="addwfhapplication">
        <div class="content-panel">
            <div class="panel-heading d-flex align-items-center justify-content-between" style="position: relative;">
                <h4 class="mb-0 mx-auto text-center" style="flex: 1;">TRANSFER EMPLOYEE</h4>
                <a href="dashboard.php?transfer_form" onclick="return confirmExit()" style="color: white; position: absolute; right: 15px;">
                    <i class="fa fa-times" style="cursor: pointer;"></i>
                </a>
            </div>
            <div class="panel-body">
                <div class="form-group mb-3" style="margin: 10px;">
                    <label class="form-label">Employee ID</label>
                    <input type="text" name="empID" id="empID" class="form-control" required placeholder="Enter Employee ID then hit ENTER">
                </div>
                <div class="form-group mb-3" style="margin: 10px;">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" readonly style="cursor: not-allowed;">
                </div>
                <div class="form-group mb-3" style="margin: 10px;">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" readonly style="cursor: not-allowed;">
                </div>
                <div class="form-group mb-3" style="margin: 10px;">
                    <label class="form-label">Job Title</label>
                    <input type="text" name="jobtitle" class="form-control" readonly style="cursor: not-allowed;">
                </div>
                <div class="form-group mb-3" style="margin: 10px;">
                    <label class="form-label">Branch/Satellite Office</label>
                    <select name="location" class="form-control" required style="cursor: pointer;">
                        <option value="" disabled selected>Select a location</option>
                        <option value="Davao">Davao</option>
                        <option value="Digos">Digos</option>
                        <option value="Panabo">Panabo</option>
                        <option value="Kidapawan">Kidapawan</option>
                        <option value="Iloilo">Iloilo</option>
                    </select>
                </div>
                <div class="form-group mb-3" style="margin: 10px;">
                    <label class="form-label">Reason</label>
                    <textarea name="reason" class="form-control" rows="5" required placeholder="Add your reason here"></textarea>
                </div> 
                <div class="form-group mb-3" style="margin: 10px;">
                    <label class="form-label">Effective Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="text-center" style="margin: 10px; margin-top: 50px;">
                    <input type="submit" id="submitBtn" name="submit" class="btn btn-success" value="Submit Details"
                        style="width: 300px; border-radius: 20px; height: 40px;">
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    document.getElementById('empID').addEventListener('change', function () {
        const empID = this.value.trim();
        if (empID === '') return;
    
        fetch('get_employee_info.php?empID=' + encodeURIComponent(empID))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('input[name="name"]').value = data.name;
                    document.querySelector('input[name="department"]').value = data.department;
                    document.querySelector('input[name="jobtitle"]').value = data.jobtitle;
                } else {
                    alert("Employee not found.");
                    document.querySelector('input[name="name"]').value = '';
                    document.querySelector('input[name="department"]').value = '';
                    document.querySelector('input[name="jobtitle"]').value = '';
                }
            })
            .catch(err => {
                console.error(err);
                alert("Failed to fetch employee data.");
            });
    });
</script>
<?php
    if (isset($_POST['submit'])) {
        $idno = mysqli_real_escape_string($con, $_POST['empID']);
        $location = mysqli_real_escape_string($con, $_POST['location']);
        $date_transfer = mysqli_real_escape_string($con, $_POST['date']);
        $reason = isset($_POST['reason']) ? urldecode($_POST['reason']) : ''; // Decode the input
        $reason = mysqli_real_escape_string($con, $reason); // Sanitize for SQL
        $filedby = $_SESSION['idno'];
        $datetime = date('Y-m-d H:i:s');
        $status = 'Approved';
    
        $query = "INSERT INTO work_transfer 
            (idno, new_loc, reason, date_transfer, datetime, filedby, application_status)
            VALUES 
            ('$idno', '$location', '$reason', '$date_transfer', '$datetime', '$filedby', '$status')";
    
        $result = mysqli_query($con, $query);
    
        if ($result) {
            echo "<script>alert('Transfer Application submitted successfully!'); window.location='?transfer_form';</script>";
        } else {
            echo "<script>alert('Failed to submit application.');</script>";
        }
    }
?>