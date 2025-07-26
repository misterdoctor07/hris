<?php
    include '../config.php';
    
    $today = date('Y-m-d');
    
    // Get all movements effective today
    $query = mysqli_query($con, "
        SELECT * FROM movement_tracker 
        WHERE effectivitydate = '$today'
    ");
    
    while ($row = mysqli_fetch_assoc($query)) {
        $idno = $row['idno'];
    
        // Apply company movement
        if (!empty($row['companyto'])) {
            $newCompany = $row['companyto'];
            mysqli_query($con, "
                UPDATE employee_details 
                SET company = '$newCompany' 
                WHERE idno = '$idno'
            ");
        }
    
        // Apply department movement
        if (!empty($row['departmentto'])) {
            $newDepartment = $row['departmentto'];
            mysqli_query($con, "
                UPDATE employee_details 
                SET department = '$newDepartment' 
                WHERE idno = '$idno'
            ");
        }
    
        // Apply job title (designation) movement
        if (!empty($row['jobto'])) {
            $newDesignation = $row['jobto'];
            mysqli_query($con, "
                UPDATE employee_details 
                SET designation = '$newDesignation' 
                WHERE idno = '$idno'
            ");
        }
    
        // Apply shift movement
        if (!empty($row['shiftto']) && !empty($row['shift_type'])) {
            $shiftParts = explode('-', $row['shiftto']);
            $startShift = trim($shiftParts[0]);
            $endShift = trim($shiftParts[1]);
            $shiftType = $row['shift_type'];
    
            mysqli_query($con, "
                UPDATE employee_details 
                SET startshift = '$startShift', endshift = '$endShift', shift_type = '$shiftType'
                WHERE idno = '$idno'
            ");
        }
    }
?>