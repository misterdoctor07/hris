<!-- Enhanced PIN Modal -->
<div id="pinModal" class="modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background: #fff; padding: 30px; margin: 10% auto; width: 35%; border-radius: 15px; text-align: center; box-shadow: 0px 0px 20px #000;">
        <h2 id="modalTitle">Enter PIN</h2>
        <form id="pinFormModal" onsubmit="submitForm(event);" action="authenticate_pin.php">
            <div style="position: relative; display: inline-block; width: 100%; margin-bottom: 15px;">
                <input type="password" id="pinInput" class="form-control" placeholder="Enter your 6-digit PIN" required style="width: 100%; padding-right: 40px;">
                <span onclick="togglePIN()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                    <i id="eyeIcon" class="fa fa-eye-slash"></i>
                </span>
            </div>
            <div id="confirmPinDiv" style="position: relative; display: none; width: 100%; margin-bottom: 15px;">
                <input type="password" id="confirmPinInput" class="form-control" placeholder="Confirm your PIN" required>
            </div>
            
            <!-- Moved toggle switch inside the modal -->
          <div class="view-option-toggle">
              <label>
                  <input type="checkbox" id="viewOptionToggle" checked>
                  <span>QR Mode (uncheck for Direct PC View)</span>
              </label>
          </div>

            
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

<!-- Confidential clause modal -->
<div id="confidentialModal" class="modal" style="display:none; position: fixed; z-index: 1001; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7);">
    <div class="modal-content" style="background: #fff; padding: 40px 30px; margin: 10% auto; width: 35%; border-radius: 20px; text-align: center; box-shadow: 0 0 30px rgba(0,0,0,0.7); color: #333; border: 4px solid #d9534f;">
        <h2 id="notificationTitle" style="color: #d9534f; font-size: 28px; margin-bottom: 20px;">⚠️ CONFIDENTIAL</h2>
        <p style="font-size: 18px; line-height: 1.5;">
            Please be reminded that your <strong>payslip and other payroll materials are STRICTLY CONFIDENTIAL</strong>. <br><br>
            For your privacy, it is <strong>strongly advised</strong> to access these documents <strong>privately</strong> and avoid using your company PC or viewing them in public places.
        </p>
        <button onclick="closeConfidentialModal()" class="btn btn-danger" style="margin-top: 30px; padding: 10px 25px; font-size: 18px; border-radius: 8px;">I Understand</button>
    </div>
</div>
<!-- QR Code Modal -->
<div id="qrModal" class="modal" style="display:none; position: fixed; z-index: 1002; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7);">
    <div class="modal-content" style="background: #fff; padding: 30px; margin: 5% auto; width: 30%; border-radius: 15px; text-align: center; box-shadow: 0px 0px 20px #000;">
        <h2>Scan Payslip QR Code</h2>
        <div id="qrCodeContainer" style="display: flex; justify-content: center; align-items: center; margin: 5px auto; width: 100%;"></div>
        <p>Scan this QR code with your mobile device to view your payslip</p>
        <button onclick="closeQRModal()" class="btn btn-primary">Close</button>
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

<!-- Incorrect Notification Modal -->
<div id="incorrectnotificationModal" class="modal" style="display:none; position: fixed; z-index: 1001; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="inc-modal-content" style="background: #A91B0D; padding: 30px; margin: 10% auto; width: 30%; border-radius: 15px; text-align: center; box-shadow: 0px 0px 20px #000; color: #fff;">
        <h2 id="incnotificationTitle">Notification</h2>
        <p id="incnotificationMessage"></p>
        <button onclick="closeIncPinNotification()" class="btn btn-primary">OK</button>
    </div>
</div>
<style>
.view-option-toggle {
    margin: 15px 0;
    text-align: center;
}
.view-option-toggle label {
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.view-option-toggle input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}
#pinInput:disabled, #confirmPinInput:disabled {
    background-color: #f5f5f5;
    cursor: not-allowed;
    opacity: 0.8;
}

.view-option-toggle input[type="checkbox"] {
    margin-right: 8px;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('keydown', function(event) {
    if (document.getElementById("confidentialModal").style.display === "block") {
        if (event.key === 'Enter') {
            closeConfidentialModal();
        } else if (event.key === 'Escape') {
            closeConfidentialModal();
        }
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") closeModal();
    if (event.key === "Enter" && document.getElementById("pinModal").style.display === "block") {
        submitForm(event);
    }
});

function closeQRModal() {
    document.getElementById('qrModal').style.display = 'none';
}

function openModal() {
    var payrollPeriod = document.getElementById("payrollPeriod").value;

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

    // Show modal
    document.getElementById("pinModal").style.display = "block";
    document.getElementById("pinInput").focus();

    updatePINFields(); // This handles the title now
}

function closeModal() {
    document.getElementById("pinModal").style.display = "none";
    document.getElementById("pinInput").value = "";
    document.getElementById("confirmPinInput").value = "";
}

function updatePINFields() {
    var useQRCode = document.getElementById('viewOptionToggle').checked;
    document.getElementById('useQRField').value = useQRCode ? '1' : '0';

    const pinInput = document.getElementById("pinInput");
    const confirmPin = document.getElementById("confirmPinInput");
    const pinFieldDiv = document.getElementById("pinInput").parentElement;
    const confirmPinDiv = document.getElementById("confirmPinDiv");

    pinInput.disabled = useQRCode;
    pinInput.required = !useQRCode;

    if (confirmPin) {
        confirmPin.disabled = useQRCode;
        confirmPin.required = !useQRCode;
    }

    if (useQRCode) {
        pinInput.style.backgroundColor = "#f5f5f5";
        pinFieldDiv.style.display = "none";
        confirmPinDiv.style.display = "none";
        document.getElementById("modalTitle").textContent = "Generate QR";
    } else {
        pinInput.style.backgroundColor = "";
        pinFieldDiv.style.display = "";

        if (confirmPinDiv.dataset.visible === "true") {
            confirmPinDiv.style.display = "";
            document.getElementById("modalTitle").textContent = "Register PIN";
        } else {
            confirmPinDiv.style.display = "none";
            document.getElementById("modalTitle").textContent = "Enter PIN";
        }
    }
}

function closeNotification() {
    document.getElementById("notificationModal").style.display = "none";
    location.reload();
}

function closeConfidentialModal() {
    document.getElementById("confidentialModal").style.display = "none";
    openModal(); // Now open PIN modal after confidentiality notice
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
    
    const useQR = document.getElementById('viewOptionToggle').checked;
    
    // Only validate PIN if not in QR mode
    if (!useQR) {
        const pin = document.getElementById("pinInput").value;
        const confirmPin = document.getElementById("confirmPinInput").value;
        const isRegistrationMode = document.getElementById("confirmPinDiv").style.display === "block";

        if (pin.trim() === "") {
            alert("PIN is required for Direct View.");
            return false;
        }

        if (isRegistrationMode && pin !== confirmPin) {
            alert("PINs do not match. Please try again.");
            return false;
        }
    }

    document.getElementById("hiddenPin").value = document.getElementById("pinInput").value;
    handleFormSubmit(event);
    return false;
}

//Function to show the confidential clause modal
function showConfidentialNotice() {
    var payrollPeriod = document.getElementById("payrollPeriod").value;
    if (payrollPeriod === "") {
        alert("Please select a payroll period first.");
        return;
    }

    // Open confidentiality modal first
    document.getElementById("confidentialModal").style.display = "block";
    
    // Focus OK button (if you have one — otherwise no harm)
    document.querySelector("#confidentialModal .btn-primary").focus();
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

// Function to show the incorrect PIN notification modal
function showIncPinNotification(title, message) {
    document.getElementById("incnotificationTitle").innerText = title;
    document.getElementById("incnotificationMessage").innerText = message;
    document.getElementById("incorrectnotificationModal").style.display = "block";
    document.getElementById("incmodalOkButton").focus(); // Focus OK button
}

// Function to close the notification modal and refresh the page
function closeIncPinNotification() {
    document.getElementById("incorrectnotificationModal").style.display = "none";
}

// Enable Enter key to confirm modal
document.addEventListener('keydown', function(event) {
    if (window.getComputedStyle(document.getElementById("incorrectnotificationModal")).display === "block") {
        if (event.key === 'Enter') {
            event.preventDefault();
            closeIncPinNotification();
        } else if (event.key === 'Escape') {
            closeIncPinNotification();
        } 
    }
});
// Initialize based on current toggle state
document.getElementById('viewOptionToggle').addEventListener('change', updatePINFields);

function handleFormSubmit(event) {
    event.preventDefault();

    const formData = new FormData(document.getElementById('payrollForm'));
    const useQR = document.getElementById('viewOptionToggle').checked;

    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Evaluate returned scripts if any
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = data;
        const scripts = tempDiv.getElementsByTagName('script');

        for (let script of scripts) {
            if (script.src) {
                const newScript = document.createElement('script');
                newScript.src = script.src;
                document.body.appendChild(newScript);
            } else {
                eval(script.innerHTML);
            }
        }

        // **Open the payslip window or QR code after successful form submission**
        const tokenMatch = data.match(/token=([\w\d]+)/);
        if (tokenMatch) {
            const token = tokenMatch[1];
            const salaryType = data.includes('Rated') ? 'Rated' : 'Monthly'; // fallback if needed
            handlePayslipView(token, salaryType);
        }

    }).catch(err => {
        console.error("Form submission failed", err);
    });

    return false;
}

function generateQRCode(token) {
    console.log('Generating QR for token:', token);
    var container = document.getElementById('qrCodeContainer');
    container.innerHTML = '';
    
    try {
        new QRCode(container, {
            text: `${window.location.origin}/hris/employeeportal/public_access.php?token=${token}`,
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
        document.getElementById('qrModal').style.display = 'block';
    } catch (e) {
        console.error('QR generation failed:', e);
        alert('Failed to generate QR code. Please try again.');
    }
}

function handlePayslipView(token, salaryType) {
    const useQRCode = document.getElementById('viewOptionToggle').checked;
    console.log('Handling view - useQRCode:', useQRCode);
    if (useQRCode) {
        generateQRCode(token);
    } else {
        var url = salaryType === 'Rated' 
            ? '/hris/employeeportal/payslipRated.php?token=' + token
            : '/hris/employeeportal/payslip.php?token=' + token;
        window.open(url, '_blank');
        closeModal();
    }
}
</script>

<div class="row">
    <div class="col-lg-12">
        <h4 style="text-indent: 10px;"><a href="?main"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-money"></i> PAYROLL PERIOD</h4>
    </div>
</div>
<form id="payrollForm" class="form-horizontal style-form" method="POST" onsubmit="return handleFormSubmit(event)">
    <input type="hidden" name="viewpayroll">
    <input type="hidden" name="pin" id="hiddenPin">
    <input type="hidden" name="useQR" id="useQRField">
    <div class="col-lg-4 mt">
        <div class="content-panel">
            <div class="panel-heading">
                <button type="button" onclick="showConfidentialNotice()" class="btn btn-primary" style="float:right;">Select</button>
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
    $useQR = isset($_POST['useQR']) && $_POST['useQR'] == '1';
    $id = $_POST['id'];
    $pin = $useQR ? '' : $_POST['pin']; // Allow empty PIN for QR mode
    $idno = $_SESSION['idno'];

    // Skip PIN verification if in QR mode
    if (!$useQR) {
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
                exit;
            }
        } else if ($pin !== $storedPin) {
            echo "<script>
                    showIncPinNotification('Failed', 'Incorrect PIN. Access Denied!');
                  </script>";
            exit;
        }
    }

    $sqlCheck = mysqli_query($con, "SELECT pd.*, ep.*, pd.id AS pd_id FROM payroll_details pd 
                                  INNER JOIN employee_payroll ep ON ep.idno = pd.idno 
                                  WHERE pd.payrollperiod = '$id' AND pd.idno = '$idno' AND pd.status = 'posted'");

    if (mysqli_num_rows($sqlCheck) > 0) {
        $pay = mysqli_fetch_array($sqlCheck);
        $pay_id = $pay['pd_id'];
        date_default_timezone_set("Asia/Manila");
        
        $token = bin2hex(random_bytes(16));
        $currentTime = new DateTime('now', new DateTimeZone('Asia/Manila'));
        $expiry = $currentTime->add(new DateInterval('PT1H'))->format('Y-m-d H:i:s');
        $created_at = date('Y-m-d H:i:s');
        $salary_type = $pay['salary_type'];
        
        $insert = mysqli_query($con, "INSERT INTO payslip_tokens 
            (token, payslip_id, idno, salary_type, expiry, created_at) 
            VALUES ('$token', '$pay_id', '$idno', '$salary_type', '$expiry', '$created_at')");
        
        if ($insert) {
            echo "<script>
                    useQRCode = " . ($useQR ? 'true' : 'false') . ";
                    handlePayslipView('$token', '$salary_type');
                  </script>";
        } else {
            error_log("Token insert failed: " . mysqli_error($con));
            echo "<script>
                    showIncPinNotification('Error', 'System error. Please try again.');
                  </script>";
        }
    } else {
        echo "<script>window.location='paysliperror.php';</script>";
    }
}
?>