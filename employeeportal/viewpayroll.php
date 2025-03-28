<!-- Enhanced PIN Modal -->
<div id="pinModal" class="modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background: #fff; padding: 30px; margin: 10% auto; width: 35%; border-radius: 15px; text-align: center; box-shadow: 0px 0px 20px #000;">
        <h2 id="modalTitle">Enter PIN</h2>
        <form id="pinFormModal" onsubmit="submitForm(event); return false;">
            <div style="position: relative; display: inline-block; width: 100%; margin-bottom: 15px;">
                <input type="password" id="pinInput" class="form-control" placeholder="Enter your 6-digit PIN" required style="width: 100%; padding-right: 40px;">
                <span onclick="togglePIN()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                    <i id="eyeIcon" class="fa fa-eye-slash"></i>
                </span>
            </div>
            <div id="confirmPinDiv" style="position: relative; display: none; width: 100%; margin-bottom: 15px;">
                <input type="password" id="confirmPinInput" class="form-control" placeholder="Confirm your PIN" required>
            </div>
            <button type="submit" class="btn btn-primary">Confirm</button>
        </form>
    </div>
</div>

<!-- Notification Modal -->
<div id="notificationModal" class="modal" style="display:none; position: fixed; z-index: 1001; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background: #4CAF50; padding: 30px; margin: 10% auto; width: 30%; border-radius: 15px; text-align: center; box-shadow: 0px 0px 20px #000; color: #fff;">
        <h2 id="notificationTitle">Notification</h2>
        <p id="notificationMessage"></p>
        <button onclick="closeNotification()" class="btn btn-primary">OK</button>
    </div>
</div>

<script>
document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") closeModal();
    if (event.key === "Enter" && document.getElementById("pinModal").style.display === "block") {
        submitForm(event);
    }
});

function openModal() {
    var payrollPeriod = document.getElementById("payrollPeriod").value;
    document.getElementById("modalTitle").textContent = "Enter PIN";

    if (payrollPeriod === "") {
        alert("Please select a payroll period first.");
        return;
    }

    <?php
    $idno = $_SESSION['idno'];
    $sqlUser = mysqli_query($con, "SELECT pin FROM users WHERE idno = '$idno'");
    $row = mysqli_fetch_array($sqlUser);
    $storedPin = $row['pin'];
    echo "var hasPin = " . (is_null($storedPin) || $storedPin === "" ? 'false' : 'true') . ";";
    ?>

    if (!hasPin) {
        document.getElementById("modalTitle").textContent = "Register PIN";
        document.getElementById("confirmPinDiv").style.display = "block";
    }

    document.getElementById("pinModal").style.display = "block";
    document.getElementById("pinInput").focus();
}

function closeModal() {
    document.getElementById("pinModal").style.display = "none";
    document.getElementById("pinInput").value = "";
    document.getElementById("confirmPinInput").value = "";
}

function closeNotification() {
    document.getElementById("notificationModal").style.display = "none";
}

function togglePIN() {
    var pinInput = document.getElementById("pinInput");
    var eyeIcon = document.getElementById("eyeIcon");

    if (pinInput.type === "password") {
        pinInput.type = "text";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    } else {
        pinInput.type = "password";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    }
}

function submitForm(event) {
    event.preventDefault();

    var pin = document.getElementById("pinInput").value;
    var confirmPin = document.getElementById("confirmPinInput").value;
    var isRegistrationMode = document.getElementById("confirmPinDiv").style.display === "block";

    if (pin.trim() === "") {
        alert("PIN is required.");
        return;
    }

    if (isRegistrationMode && pin !== confirmPin) {
        alert("PINs do not match. Please try again.");
        return;
    }

    document.getElementById("hiddenPin").value = pin;
    document.getElementById("payrollForm").submit();
}

    // Function to show the notification modal
    function showNotification(title, message) {
        document.getElementById("notificationTitle").innerText = title;
        document.getElementById("notificationMessage").innerText = message;
        document.getElementById("notificationModal").style.display = "block";
        document.getElementById("modalOkButton").focus(); // Focus OK button
    }

    // Function to close the notification modal and refresh the page
    function closeNotification() {
        document.getElementById("notificationModal").style.display = "none";
        location.reload(); // Refreshes the page
    }

    // Enable Enter key to confirm modal
    document.addEventListener('keydown', function(event) {
        if (document.getElementById("notificationModal").style.display === "block") {
            if (event.key === 'Enter') {
                closeNotification();
            } else if (event.key === 'Escape') {
                closeNotification();
            }
        }
    });
</script>

<div class="row">
    <div class="col-lg-12">
        <h4 style="text-indent: 10px;"><a href="?main"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-money"></i> PAYROLL PERIOD</h4>
    </div>
</div>
<form id="payrollForm" class="form-horizontal style-form" method="POST">
    <input type="hidden" name="viewpayroll">
    <input type="hidden" name="pin" id="hiddenPin">
    <div class="col-lg-4 mt">
        <div class="content-panel">
            <div class="panel-heading">
                <button type="button" onclick="openModal()" class="btn btn-primary" style="float:right;">Select</button>
                <h4><i class="fa fa-file-text-o"></i> SELECT PERIOD</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-4 col-sm-4 control-label">Payroll Period</label>
                    <div class="col-sm-8">
                        <select name="id" id="payrollPeriod" class="form-control" required>
                            <option value="">-- Select Payroll Period --</option>
                            <?php
                            $sqlPeriod = mysqli_query($con, "SELECT * FROM payroll ORDER BY id DESC");
                            while ($period = mysqli_fetch_array($sqlPeriod)) {
                                echo "<option value='$period[id]'>".date('F d, Y', strtotime($period['periodfrom']))." to ".date('F d, Y', strtotime($period['periodto']))."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
if (isset($_POST['pin'])) {
    $id = $_POST['id'];
    $pin = $_POST['pin'];
    $idno = $_SESSION['idno'];

    $sqlUser = mysqli_query($con, "SELECT u.pin FROM users u WHERE idno = '$idno'");
    $row = mysqli_fetch_array($sqlUser);
    $storedPin = $row['pin'];

    if (is_null($storedPin) || $storedPin == "") {
        $registerPin = mysqli_query($con, "UPDATE users SET pin='$pin' WHERE idno='$idno'");
        if ($registerPin) {
            echo "<script>
                    showNotification('Success', 'PIN registered successfully.');
                  </script>";
        } else {
            echo "<script>
                    showNotification('Error', 'Failed to register PIN.');
                  </script>";
        }
    } else if ($pin === $storedPin) {
        $sqlCheck = mysqli_query($con, "SELECT pd.*, ep.*, pd.id AS pd_id FROM payroll_details pd INNER JOIN employee_payroll ep ON ep.idno = pd.idno WHERE pd.payrollperiod = '$id' AND pd.idno = '$idno' AND pd.status = 'posted'");

        if (mysqli_num_rows($sqlCheck) > 0) {
            $pay = mysqli_fetch_array($sqlCheck);
            $salary_type = $pay['salary_type'];
            $pay_id = $pay['pd_id'];

            echo $salary_type == 'Rated' ? "<script>window.location='/accounting/payslipRated.php?id=$pay_id';</script>" : "<script>window.location='/accounting/payslip.php?id=$pay_id';</script>";
        } else {
            echo "<script>window.location='paysliperror.php';</script>";
        }
    } else {
        echo "<script>alert('Incorrect PIN. Access denied.');</script>";
    }
}
?>