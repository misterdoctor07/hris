<?php
// Fetch unique companies from the employee_details table
$sqlCompanies = mysqli_query($con, "SELECT DISTINCT company FROM employee_details ORDER BY company");

if (!$sqlCompanies) {
    echo "Query error: " . mysqli_error($con);
    exit;
}


?>
<head>
<meta charset="utf-8">
</head>
<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <h4>
                <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> |
                <i class="fa fa-user"></i> EMPLOYEE LIST
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
                    WHERE ed.company = '$companyCode'
                    AND ed.status != 'RESIGNED'
                    ORDER BY d.department");

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
                    $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.*, lc.*, d.department, ed.designation, jt.jobtitle
                        FROM employee_profile ep
                        INNER JOIN employee_details ed ON ed.idno = ep.idno
                        INNER JOIN department d ON d.id = ed.department
                        INNER JOIN jobtitle jt ON jt.id = ed.designation
                        INNER JOIN leave_credits lc ON lc.idno = ep.idno
                        WHERE ed.company = '$companyCode'
                        AND d.department = '$departmentName'
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
                                <th style = 'text-align: center;'>No.</th>
                                <th style = 'text-align: center;'>Emp ID</th>
                                <th style = 'text-align: center;'>Employee Name</th>
                                <th style = 'text-align: center;'>Length of Service</th>
                                <th style = 'text-align: center;'>Period (From - Through)</th>
                                <th style = 'text-align: center;'>VL Credits</th>
                                <th style = 'text-align: center;'>VL Used</th>
                                <th style = 'text-align: center;'>VL Remaining</th>
                                <th style = 'text-align: center;'>SL Credits</th>
                                <th style = 'text-align: center;'>SL Used</th>
                                <th style = 'text-align: center;'>SL Remaining</th>
                                <th style = 'text-align: center;'>PTO Credits</th>
                                <th style = 'text-align: center;'>PTO Used</th>
                                <th style = 'text-align: center;'>PTO Remaining</th>
                                <th style = 'text-align: center;'>BLP Credits</th>
                                <th style = 'text-align: center;'>BLP Used</th>
                                <th style = 'text-align: center;'>BLP Remaining</th>
                                <th style = 'text-align: center;'>SPL Credits</th>
                                <th style = 'text-align: center;'>SPL Used</th>
                                <th style = 'text-align: center;'>SPL Remaining</th>
                                <th style = 'text-align: center;'>EO Credits</th>
                                <th style = 'text-align: center;'>EO Used</th>
                                <th style = 'text-align: center;'>EO Remaining</th>
                            </tr>
                        </thead>
                        <tbody>";

                    $x = 1;
                    while ($employee = mysqli_fetch_array($sqlEmployee)) {

                        $jobTitle = htmlspecialchars($employee['jobtitle']); 
                        $dateRegular = date('M d, Y', strtotime($employee['dateofregular']));
                        $dateFulltime = date('M d, Y', strtotime($employee['dateoffulltime']));
                        $hireDate = new DateTime($employee['dateofhired']);
                        $thresholdDate = new DateTime('2020-07-31'); // End of July 2020
        
                        if ($hireDate <= $thresholdDate) {
                            // Logic for dateofhire on or before July 2020
                            $dhire = new DateTime($employee['dateofregular']); 
                            $dnow = new DateTime(date('Y-m-d'));
                            $interval = $dhire->diff($dnow);
                            $years = $interval->y;
                            $month = $interval->m;
                            $days = $interval->d;
                            $periodfrom = date('M d, Y', strtotime($years . " years", strtotime($employee['dateofregular'])));
                            $periodto = date('M d, Y', strtotime('1 years', strtotime($periodfrom)));
                            $eligibility = date('M d, Y', strtotime($employee['dateofregular']));
                            $currentMonth = date('n'); // get the current month (1-12)
                            $currentDay = date('j'); // get the current day (1-31)
                        } else {
                            // Logic for dateofhire on or after August 2020
                            $dhire = new DateTime($employee['dateofhired']);
                            $dnow = new DateTime(date('Y-m-d'));
                            $interval = $dhire->diff($dnow);
                            $years = $interval->y;
                            $month = $interval->m;
                            $days = $interval->d;
                            $periodfrom = date('M d, Y', strtotime($years . " years", strtotime($employee['dateofhired'])));
                            $periodto = date('M d, Y', strtotime('1 years', strtotime($periodfrom)));
                            $eligibility = date('M d, Y', strtotime($employee['dateofhired']));
                            $currentMonth = date('n'); // get the current month (1-12)
                            $currentDay = date('j'); // get the current day (1-31)
                        }

                        echo "<tr>
                            <td align='center'>{$x}.</td>
                            <td align='center'>{$employee['idno']}</td>
                            <td align='center'>{$employee['lastname']}, {$employee['firstname']} {$employee['middlename']} {$employee['suffix']}</td>
                            <td align='center'>{$eligibility}</td>
                            <td align='center'>{$status}</td>
                            <td align='center'>{$dateHired}</td>
                            <td align='center'>{$dateRegular}</td>
                            <td align='center'>{$dateFulltime}</td>
                            <td align='center'>{$shift}</td>
                            <td align='center'>{$employee['location']}</td>
                            <td align='center'>{$referrerDetails}</td>
                            <td align='center'>{$referralfee}</td>
                        </tr>";
                        $x++;
                    }

                    if ($x === 1) {
                        echo "<tr><td colspan='12' align='center'>No records found!</td></tr>";
                    }

                    echo "</tbody></table></div>"; // End department content
                    $deptActive = ''; // Remove active class for subsequent departments
                }

                echo "</div></div>"; // End company content
                $active = ''; // Remove active class for subsequent companies
            }
            ?>
        </div>
    </div>
</div>

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