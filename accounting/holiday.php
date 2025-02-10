<?php
// Connect to the database
include('../config.php'); // Replace with your DB connection file

// Handle form submission for adding a new holiday
// Prepared statement for adding a new holiday
// Prepared statement for adding a new holiday
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_selected'])) {
    $stmt = $con->prepare("INSERT INTO holidays (date, type,location, description) VALUES (?, ?, ?, ? )");
    $stmt->bind_param("ssss", $date, $type,$location, $description);

    $date = $_POST['holiday_date'];
    $type = $_POST['holiday_type'];
    $location=$_POST['holiday_location'];
    $description = $_POST['holiday_description'];

    if ($stmt->execute()) {
        echo "<script>alert('Holiday added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding holiday: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Prepared statement for deleting selected holidays
if (isset($_POST['delete_selected']) && isset($_POST['selected_holidays'])) {
    $ids = $_POST['selected_holidays'];
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $con->prepare("DELETE FROM holidays WHERE id IN ($placeholders)");

    $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);

    if ($stmt->execute()) {
        echo "<script>alert('Selected holidays deleted successfully!');</script>";
        echo "<script>window.location.href='?holiday';</script>";
        exit;
    } else {
        echo "<script>alert('Error deleting holidays: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}


// Fetch all holidays from the database
$result = mysqli_query($con, "SELECT * FROM holidays");
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holiday Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 10px;
        }

        
        .panel-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        h4 {
            margin: 0;
            font-size: 1.9rem;
        }

        form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        label {
            width: 5%;
            font-weight: bold;
        }

        .form-control {
            width: 70%;
            padding: 7px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        select.form-control {
            background-color: #fff;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
        input[type="checkbox"] {
    transform: scale(1.2);
    margin-right: 5px;
}
/* Main container for layout */
.main-container {
    display: flex;
    gap: 20px;
    align-items: stretch;
}


/* Left panel (form) */
.right-panel {
    flex: 1; /* Keep the right panel narrower */
    max-width: none; /* Remove width restrictions */
}

.right-panel {
    flex: 1; /* Keep the right panel narrower */
}


/* Content panel styling */
.content-panel {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    width: 100%; /* Full width */
    max-width: none; /* Remove max-width limitation */
}

/* Optional styling for buttons and tables */
button {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table th, table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

table th {
    background-color: #f4f4f9;
}



@media (max-width: 768px) {
    .main-container {
        flex-direction: column; /* Stack panels vertically on small screens */
    }

    .right-panel, .left-panel {
        max-width: 100%; /* Full width for both panels */
    }
}



    </style>
</head>
<body>
<div class="main-container">
    <!-- Left Content (e.g., Add Holiday Form) -->
    <div class="left-panel">
        <div class="content-panel">
            <div class="panel-heading">
                <h4><i class="fa fa-calendar"></i> Manage Holidays</h4>
            </div>
            <form method="POST">
                <div class="form-group">
                    <label for="holiday_date">Date:</label>
                    <input type="date" id="holiday_date" name="holiday_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="holiday_type">Type:</label>
                    <select id="holiday_type" name="holiday_type" class="form-control" required>
                        <option value="">-- Select Holiday Type --</option>
                        <option value="rh">Regular Holiday</option>
                        <option value="snwh">Special Non-Working Holiday</option>
                    </select>
                </div>
                <div class="form-group">
                <label for="holiday_location">Location:</label>
                  <select id="holiday_location" name="holiday_location" class="form-control" required>
                  <option value=""></option>
                  <option value="allbranch">All Branch</option>
                      <option value="Davao">Davao City</option>
                      <option value="Iloilo">Ilo-ilo City</option>
                      <option value="Kidapawan">Kidapawan City</option>
                      <option value="Panabo">Panabo City</option>
                      <option value="Digos">Digos City</option>
                    </select>
                
              </div>
                <div class="form-group">
                    <label for="holiday_description">Description:</label>
                    <input type="text" id="holiday_description" name="holiday_description" class="form-control" required>
                </div>
                <button type="submit"><i class="fa fa-plus"></i> Add Holiday</button>
            </form>
        </div>
    </div>

    <!-- Right Content (Holiday List) -->
    <div class="right-panel">
        <div class="content-panel">
            <div class="panel-heading">
                <h4><i class="fa fa-list"></i> Holiday List</h4>
            </div>
            <form method="POST" id="bulkDeleteForm">
                <table>
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" id="holiday_<?php echo $row['id']; ?>" name="selected_holidays[]" value="<?php echo $row['id']; ?>">
                                    <label for="holiday_<?php echo $row['id']; ?>">Select</label>
                                </td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['type'] === 'rh' ? 'Regular Holiday' : 'Special Non-Working Holiday'; ?></td>
                                <td><?php echo $row['location']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No holidays found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <button type="submit" name="delete_selected" onclick="return confirm('Are you sure you want to delete the selected holidays?');">
                    <i class="fa fa-trash"></i> Delete Selected
                </button>
            </form>
        </div>
    </div>


                
</body>
</html>
