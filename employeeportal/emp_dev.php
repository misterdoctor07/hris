<?php
session_start();
include('../config.php');

// Handle AJAX request to check if username exists
if (isset($_POST['check_username'])) {
    $newUsername = trim($_POST['check_username']);

    $sqlCheckUsername = "SELECT idno FROM users WHERE username = ?";
    $stmtCheck = mysqli_prepare($con, $sqlCheckUsername);
    mysqli_stmt_bind_param($stmtCheck, "s", $newUsername);
    mysqli_stmt_execute($stmtCheck);
    mysqli_stmt_store_result($stmtCheck);

    if (mysqli_stmt_num_rows($stmtCheck) > 0) {
        echo "exists"; // Username already taken
    } else {
        echo "available"; // Username is available
    }

    mysqli_stmt_close($stmtCheck);
    exit(); // Stop further execution for AJAX request
}

// Ensure user is logged in
if (!isset($_SESSION['idno'])) {
    die("Unauthorized access!");
}

// Default values
$message = ""; 
$disabled = "";

// Check password status
$password_status = $_SESSION['password_status'] ?? "";

if ($password_status === 'changed') {
    $disabled = "disabled";
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['check_username'])) {
    $empid = $_SESSION['idno'];
    $newUsername = $_SESSION['idno'];
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);
if (strlen($newPassword) < 8) {
        $message = "Password must be at least 8 characters long!";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Passwords do not match!";
    } else
    {
    // Check if the new username already exists
    $sqlCheckUsername = "SELECT idno FROM users WHERE username = ? AND idno != ?";
    $stmtCheck = mysqli_prepare($con, $sqlCheckUsername);
    mysqli_stmt_bind_param($stmtCheck, "ss", $newUsername, $empid);
    mysqli_stmt_execute($stmtCheck);
    mysqli_stmt_store_result($stmtCheck);

    if (mysqli_stmt_num_rows($stmtCheck) > 0) {
        $message = "Username already exists! Please choose a different username.";
    } else {
        // Validate password confirmation
        if ($newPassword !== $confirmPassword) {
            $message = "Passwords do not match!";
        } else {
            // Update username & password
            $sqlUpdate = "UPDATE users SET username = ?, password = ?, password_status = 'changed' WHERE idno = ?";
            $stmt = mysqli_prepare($con, $sqlUpdate);
            mysqli_stmt_bind_param($stmt, "sss", $newUsername, $newPassword, $empid);

            if (mysqli_stmt_execute($stmt)) {
                $message = "Username and password updated successfully!";
                $disabled = "disabled"; 
                $_SESSION['password_status'] = 'changed';

                // Log out the user and redirect to login
                session_destroy();
                echo "<script>alert('Password changed successfully! Please log in again.'); window.location='/index.php';</script>";
                exit();
            } else {
                $message = "Error updating username and password: " . mysqli_error($con);
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_stmt_close($stmtCheck);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 95%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .message {
            margin-top: 15px;
            text-align: center;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Change Password</h2>
        <form method="POST" onsubmit="return validatePassword()">
            <div>
                <label for="new_username">New Username:</label>
                <input type="text" id="new_username" name="new_username" value="<?= $_SESSION['idno']; ?>" readonly>
                <span id="username-status" style="color: red;"></span> <!-- Display username validation status -->
            </div>
            <div>
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div>
                <input type="submit" value="Change Password">
            </div>
        </form>
        <div class="message"><?php echo $message; ?></div>
    </div>

    <script>
        document.getElementById('new_username').addEventListener('keyup', function() {
            const username = this.value.trim();
            const usernameStatus = document.getElementById('username-status');

            if (username.length < 3) {
                usernameStatus.textContent = "Username must be at least 3 characters.";
                return;
            }

            // Send AJAX request to check username
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true); // Same PHP file
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (xhr.responseText === "exists") {
                        usernameStatus.textContent = "Username already exists! Choose another.";
                    } else {
                        usernameStatus.textContent = "✔ Username available!";
                        usernameStatus.style.color = "green";
                    }
                }
            };
            xhr.send("check_username=" + encodeURIComponent(username));
        });

        function validatePassword() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const errorMessage = document.querySelector('.message');

            if (newPassword !== confirmPassword) {
                errorMessage.textContent = "Passwords do not match!";
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
