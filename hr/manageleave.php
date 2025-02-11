<?php
// Fetch unique companies from the employee_details table
$sqlCompanies = mysqli_query($con, "SELECT DISTINCT company FROM employee_details ORDER BY company");

if (!$sqlCompanies) {
    echo "Query error: " . mysqli_error($con);
    exit;
}
?>

<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <h4>
                <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> |
                <i class="fa fa-file-text"></i> EMPLOYEE LEAVE CREDITS
            </h4>
        </div>

        <!-- Company Tabs -->
        <ul class="nav nav-tabs">
            <?php
            $active = 'active'; // Set the first tab as active
            while ($company = mysqli_fetch_array($sqlCompanies)) {
                $companyCode = htmlspecialchars($company['company']); // Sanitize output
                $sanitizedId = preg_replace('/[^A-Za-z0-9\-]/', '', $companyCode); // Unique ID
                echo "<li class='$active'><a data-toggle='tab' href='#tab-$sanitizedId'>$companyCode</a></li>";
                $active = ''; // Remove active class for subsequent tabs
            }
            ?>
        </ul>

        <div class="tab-content">
            <?php
            // Reset the result pointer for reuse
            mysqli_data_seek($sqlCompanies, 0);
            $active = 'in active'; // Set the first tab content as active
            while ($company = mysqli_fetch_array($sqlCompanies)) {
                $companyCode = htmlspecialchars($company['company']); // Sanitize output
                $sanitizedId = preg_replace('/[^A-Za-z0-9\-]/', '', $companyCode);
                echo "<div id='tab-$sanitizedId' class='tab-pane fade $active'>";

                // Fetch unique departments for the current company
                $sqlDepartments = mysqli_query($con, "SELECT DISTINCT d.department FROM employee_details ed
                    INNER JOIN department d ON d.id = ed.department
                    WHERE ed.company = '$companyCode' ORDER BY d.department");

                if (!$sqlDepartments) {
                    echo "Error fetching departments: " . mysqli_error($con);
                    continue;
                }

                echo "<ul class='nav nav-pills' style='margin-top: 10px;'>";
                $deptActive = 'active';
                while ($department = mysqli_fetch_array($sqlDepartments)) {
                    $departmentName = htmlspecialchars($department['department']); // Sanitize output
                    $deptId = preg_replace('/[^A-Za-z0-9\-]/', '', $departmentName); // Unique ID
                    echo "<li class='$deptActive'><a data-toggle='pill' href='#dept-$sanitizedId-$deptId'>$departmentName</a></li>";
                    $deptActive = ''; // Remove active class for subsequent department tabs
                }
                echo "</ul>";

                echo "<div class='tab-content' style='margin-top: 10px;'>";
                mysqli_data_seek($sqlDepartments, 0); // Reset department pointer
                $deptActive = 'in active';
                while ($department = mysqli_fetch_array($sqlDepartments)) {
                    $departmentName = htmlspecialchars($department['department']); // Sanitize output
                    $deptId = preg_replace('/[^A-Za-z0-9\-]/', '', $departmentName); // Unique ID
                    echo "<div id='dept-$sanitizedId-$deptId' class='tab-pane fade $deptActive'>";

                    // Fetch employees for the company and department
                    $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.*, d.department, ed.designation, jt.jobtitle 
                        FROM employee_profile ep
                        INNER JOIN employee_details ed ON ed.idno = ep.idno
                        INNER JOIN department d ON d.id = ed.department
                        INNER JOIN jobtitle jt ON jt.id = ed.designation
                        WHERE ed.company = '$companyCode' AND d.department = '$departmentName' 
                        AND ed.status NOT LIKE '%RESIGNED%'
                        ORDER BY ep.lastname ASC");

                    if (!$sqlEmployee) {
                        echo "Error fetching employees: " . mysqli_error($con);
                        continue;
                    }

                    echo '<!-- Search Bar -->';
                    echo '<div class="d-flex align-items-center mb-3" style="margin-bottom: 3px;">';
                    echo '    <div class="input-group" style="width: 300px;">';
                    echo '        <input type="text" class="form-control" placeholder="Search..." onkeyup="filterTable(this)">';
                    echo '    </div>';
                    echo '</div>';

                    echo "<table class='table table-bordered table-striped table-condensed'>
                        <thead>
                            <tr>
                                <th style='text-align: center;'>No.</th>
                                <th>Emp ID</th>
                                <th>Employee Name</th>
                                <th style='text-align: center;'>Department</th>
                                <th style='text-align: center;'>Eligibility</th>
                                <th style='text-align: center;'>Length of Service</th>
                                <th style='text-align: center;'>Period (From - Through)</th>
                                <th style='text-align: center;'>Action</th>
                            </tr>
                        </thead>
                        <tbody>";
                            $x=1;
                            $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.*, d.department, ed.designation, jt.jobtitle 
                                FROM employee_profile ep
                                INNER JOIN employee_details ed ON ed.idno = ep.idno
                                INNER JOIN department d ON d.id = ed.department
                                INNER JOIN jobtitle jt ON jt.id = ed.designation
                                WHERE ed.company = '$companyCode' AND d.department = '$departmentName' 
                                AND ed.status NOT LIKE '%RESIGNED%'
                                ORDER BY ep.lastname ASC");
                             if(mysqli_num_rows($sqlEmployee)>0){
                                while($company=mysqli_fetch_array($sqlEmployee)){
                                  if($company['status']=="REGULAR"){
                                    $status="<span class='label label-success label-mini'>$company[status]</span>";
                                  }else{
                                    $status="<span class='label label-warning label-mini'>$company[status]</span>";
                                  }
                                  $shift=date('h:i A',strtotime($company['startshift']))." - ".date('h:i A',strtotime($company['endshift'])); 
                                  $dateregular=date('F d, Y',strtotime($company['dateofhired'])); // dria mag update
                                  $dateofregular=date('F d, Y',strtotime(strtotime($company['dateofregular']))); 
                                  $dateofhired=date('F d, Y',strtotime(strtotime($company['dateofhired'])));
                                
        
                                  $sqlDept=mysqli_query($con,"SELECT department FROM department WHERE id='$company[department]'");
                                  if(mysqli_num_rows($sqlDept)>0){
                                    $dept=mysqli_fetch_array($sqlDept);
                                    $department=$dept['department'];    
                                  }else{
                                    $department="";
                                  }                         
                                  // Assuming $company['dateofhire'] is in 'Y-m-d' format
        
                                    $hireDate = new DateTime($company['dateofhired']);
                                    $thresholdDate = new DateTime('2020-07-31'); // End of July 2020
        
                                                if ($hireDate <= $thresholdDate) {
                                                    // Logic for dateofhire on or before July 2020
                                                    $dhire = new DateTime($company['dateofregular']); 
                                                    $dnow = new DateTime(date('Y-m-d'));
                                                    $interval = $dhire->diff($dnow);
                                                    $years = $interval->y;
                                                    $month = $interval->m;
                                                    $days = $interval->d;
                                                    $periodfrom = date('F d, Y', strtotime($years . " years", strtotime($company['dateofregular'])));
                                                    $periodto = date('F d, Y', strtotime('1 years', strtotime($periodfrom)));
                                                    $currentMonth = date('n'); // get the current month (1-12)
                                                    $currentDay = date('j'); // get the current day (1-31)
                                                    
                                                    $dateHiredMonth = $dhire->format('n'); // get the month of dateofhired (1-12)
                                                    $dateHiredDay = $dhire->format('j'); // get the day of dateofhired (1-31)
                                                    if ($currentMonth == $dateHiredMonth && $currentDay == $dateHiredDay) {
                                                        // reset vlused, ptoused, slused, blp_used, and eo_used to zero
                                                        $sqlResetCredits = mysqli_query($con, "UPDATE leave_credits SET vlused = 0, slused = 0, blp_used = 0, spl_used = 0 WHERE idno = '$company[idno]'");
                                                    }
                                                } else {
                                                    // Logic for dateofhire on or after August 2020
                                                    $dhire = new DateTime($company['dateofhired']);
                                                    $dnow = new DateTime(date('Y-m-d'));
                                                    $interval = $dhire->diff($dnow);
                                                    $years = $interval->y;
                                                    $month = $interval->m;
                                                    $days = $interval->d;
                                                    $periodfrom = date('F d, Y', strtotime($years . " years", strtotime($company['dateofhired'])));
                                                    $periodto = date('F d, Y', strtotime('1 years', strtotime($periodfrom)));
                                                    
                                                    $currentMonth = date('n'); // get the current month (1-12)
                                                    $currentDay = date('j'); // get the current day (1-31)
                                                    
                                                    $dateHiredMonth = $dhire->format('n'); // get the month of dateofhired (1-12)
                                                    $dateHiredDay = $dhire->format('j'); // get the day of dateofhired (1-31)
                                                    if ($currentMonth == $dateHiredMonth && $currentDay == $dateHiredDay) {
                                                        // reset vlused, ptoused, slused, blp_used, and eo_used to zero
                                                        $sqlResetCredits = mysqli_query($con, "UPDATE leave_credits SET vlused = 0, slused = 0, blp_used = 0, spl_used = 0 WHERE idno = '$company[idno]'");
                                                    }
                                                }
                                
                    
                                  
                                    
                                
        
                                  
                                                if ($currentDay == 1) {
                                                    // Reset eo_used every month, on the 1st day of each month
                                                    $sqlResetEOUsed = mysqli_query($con, "UPDATE leave_credits SET eo_used = 0, ptoused = 0 WHERE idno = '$company[idno]'");
                                                    
                                                    if (!$sqlResetEOUsed) {
                                                        // Handle the error, e.g., log it or display a message
                                                        die("Error resetting eo_used and ptoused: " . mysqli_error($con));
                                                    }
                                                }
                                    $dhire = new DateTime($company['dateofhired']); // Date of hire
                                    $drhire = new DateTime($company['dateofregular']); // Date of hire
                                        $dnow = new DateTime(date('Y-m-d')); // Current date
                                        $interval = $dhire->diff($dnow); // Difference between dates
        
                                      
                                        $bdayleave = 0; // Default value
                                    $pfrom=date('Y-m-d',strtotime($periodfrom));
                                    $pto=date('Y-m-d',strtotime($periodto));
                                    if ($years <= 0) {
                                    $vacationleaves = 0; // Less than 1 year
                                } elseif ($years >= 1 && $years <= 2) {
                                    $vacationleaves = 5; // 1 to 2 years
                                } elseif ($years >= 3 && $years <= 4) {
                                    $vacationleaves = 8; // 3 to 4 years
                                } elseif ($years >= 5 && $years <= 6) {
                                    $vacationleaves = 12; // 5 to 6 years
                                } elseif ($years >= 7 && $years <= 8) {
                                    $vacationleaves = 15; // 7 to 8 years
                                } elseif ($years == 9) {
                                    $vacationleaves = 25; // Exactly 9 years
                                } else {
                                    $vacationleaves = 30; // 10 or more years
                                }
                                
                                if ($years < 1) {
                                    $sickleave = 0; // Less than 1 year of service
                                } elseif ($years >= 1 && $years <= 5) {
                                    $sickleave = 5; // 1 to 5 years of service
                                } elseif ($years >= 6 && $years <= 100) {
                                    $sickleave = 7; // 6 to 10 years of service
                                } else {
                                    $sickleave = 0; // Default or other conditions (e.g., beyond 10 years not specified)
                                }
                                
                                $monthsEmployed = ($interval->y * 12) + $interval->m; // Total months employed
        
                                if($company['status']=="REGULAR") {
                                    $currentYear = (int)date('Y'); // Current year
                                    $hireYear = (int)$drhire->format('Y'); // Extract the hire year
                                    $hireMonth = (int)$drhire->format('n'); // Extract the hire month
                                
                                    if ($currentYear == $hireYear) {
                                        // First year of service: Determine PTO based on hire month
                                        switch ($hireMonth) {
                                            case 1: // January
                                                $pto = 5;
                                                break;
                                            case 2: // February
                                            case 3: // March
                                                $pto = 4;
                                                break;
                                            case 4: // April
                                            case 5: // May
                                            case 6: // June
                                                $pto = 3;
                                                break;
                                            case 7: // July
                                            case 8: // August
                                            case 9: // September
                                                $pto = 2;
                                                break;
                                            case 10: // October
                                            case 11: // November
                                            case 12: // December
                                                $pto = 1;
                                                break;
                                            default:
                                                $pto = 0; // Handle invalid months
                                                break;
                                        }
                                    } else {
                                        // After the first year, PTO is always 5
                                        $pto = 5;
                                    }
                                } else {
                                    // Non-regular employees
                                    $pto = 0;
                                }
                                
                        
                                if ($years >= 1) {
                                $bdayleave = 1; // Set bdayleave to 1 if the employee has completed 1 year
                            }
                                    $earlyout = 0;
                                    if ($monthsEmployed >= 0){
                                    $earlyout = 2;
                                    }
        
        
        
                                $sqlCheckCredits = mysqli_query($con, "SELECT * FROM leave_credits WHERE idno = '$company[idno]'");
        
                                        if (mysqli_num_rows($sqlCheckCredits) > 0) {
                                            // Update the existing record
                                            $sqlUpdateCredits = mysqli_query($con, "UPDATE leave_credits SET vacationleave = '$vacationleaves', sickleave = '$sickleave', pto = '$pto', bdayleave = '$bdayleave', earlyout = '$earlyout' WHERE idno = '$company[idno]'");
        
        
        
                                        } else {
                                            // Insert a new record
                                            $sqlInsertCredits = mysqli_query($con, "INSERT INTO leave_credits (idno, vacationleave) VALUES ('$company[idno]', '$vacationleaves')");
        
        
                                        }
        
                                echo "<tr>";
                                    echo "<td align='center'>$x.</td>";
                                    echo "<td>$company[idno]</td>";
                                    echo "<td>$company[lastname], $company[firstname] $company[middlename] $company[suffix]</td>";
                                    echo "<td align='center'>$company[department]</td>";
                                    echo "<td align='center'>$dateregular</td>"; // dria mag base pag update date of hire
                                    echo "<td align='center'>$years years $month months $days days</td>";
                                    echo "<td align='center'>$periodfrom - $periodto </td>";
                                    ?>
                                    <td align="center">
                                    <a href="?viewleave&id=<?=$company['id'];?>" class="btn btn-success btn-xs" title="Manage Leave Credits"><i class='fa fa-edit'></i></a>
                                    </td>
                                    <?php
                                echo "</tr>";
                                $x++;
                                }
                            }else{
                                echo "<tr><td colspan='8' align='center'>No record found!</td></tr>";
                            }
                    echo "</tbody>";
                    echo "</table></div>"; // End department content
                    $deptActive = ''; // Remove active class for subsequent departments
                }

                echo "</div></div>"; // End company content
                $active = ''; // Remove active class for subsequent companies
            }
            ?>
        </div>
    </div>
</div>

<?php
    if(isset($_GET['update'])){
        $idno=$_GET['idno'];
        $periodfrom=$_GET['periodfrom'];
        $periodto=$_GET['periodto'];
        $sqlInsert=mysqli_query($con,"INSERT INTO credits_eligibility(idno,periodfrom,periodto) VALUES('$idno','$periodfrom','$periodto')");
        echo "<script>window.location='?manageleave';</script>";
     }
?>

<!-- Ensure Bootstrap JS and jQuery are included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


<script>
    $(document).ready(function() {
    // Store active tab on click
    $('.nav-tabs a').on('click', function() {
        localStorage.setItem('activeTab', $(this).attr('href'));
    });

    // Retrieve active tab on page load
    const activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
        $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
    }
});
$(document).ready(function() {
        // Store active main tab on click
        $('.nav-tabs a').on('click', function() {
            localStorage.setItem('activeMainTab', $(this).attr('href'));
        });

        // Retrieve active main tab on page load
        const activeMainTab = localStorage.getItem('activeMainTab');
        if (activeMainTab) {
            $('.nav-tabs a[href="' + activeMainTab + '"]').tab('show');
        }

        // Store active inner tab on click
        $('.nav-pills a').on('click', function() {
            const companyId = $(this).closest('.tab-pane').attr('id'); // Get the company tab ID
            localStorage.setItem('activeInnerTab-' + companyId, $(this).attr('href'));
        });

        // Retrieve active inner tab on page load
        $('.tab-pane').each(function() {
            const companyId = $(this).attr('id');
            const activeInnerTab = localStorage.getItem('activeInnerTab-' + companyId);
            if (activeInnerTab) {
                $('.nav-pills a[href="' + activeInnerTab + '"]').tab('show');
            }
        });

        // Select all buttons with the "confirm-action" class
        const confirmButtons = document.querySelectorAll('.confirm-post');

        // Loop through each button and add a click event listener
        confirmButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                // Display the confirmation dialog
                const confirmAction = confirm("Are you sure you want to POST this leave?");
                
                // If the user clicks "Cancel", prevent the link's default action
                if (!confirmAction) {
                    event.preventDefault();
                }
            });
        });
    });

    function filterTable(input) {
        // Get the input field and table
        const searchValue = input.value.toLowerCase();
        const table = input.closest('.tab-pane').querySelector('table');
        
        // Loop through all table rows and hide those that don't match the search query
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
            row.style.display = rowText.includes(searchValue) ? '' : 'none';
        });
    }
</script>