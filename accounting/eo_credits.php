<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<?php
include ('../config.php');
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
        <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0;">
                <a href="?leavecredits"><i class="fa fa-arrow-left"></i> BACK</a> |
                <i class="fa fa-user"></i> EMPLOYEE EO CREDITS
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
                    ?>
                    <div class="d-flex justify-content-between align-items-center mb-3" style="margin-bottom: 3px;">
                        <!-- Search Bar -->
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" placeholder="Search..." onkeyup="filterTable(this)">
                        </div>
                    </div>
                    <!-- <style>
                        .used-col {
                            background-color: #AEC6CF;
                        }
                        .rem-col {
                            background-color: #FFF4BC; 
                        }
                    </style> -->
                    <table class='table table-bordered table-striped table-condensed'>
                        <thead>
                            <tr>
                                <th rowspan='2' style = 'vertical-align:middle; text-align: center;'>No.</th>
                                <th rowspan='2' style = 'vertical-align:middle; text-align: center;'>Emp ID</th>
                                <th rowspan='2' style = 'vertical-align:middle; text-align: center;'>Employee Name</th>
                                <th colspan='2' style = 'vertical-align:middle; text-align: center;'>January</th>
                                <th colspan='2' style = 'vertical-align:middle; text-align: center;'>February</th>
                                <th colspan='2' style = 'vertical-align:middle; text-align: center;'>March</th>
                                <th colspan='2' style = 'vertical-align:middle; text-align: center;'>April</th>
                                <th colspan='2' style = 'vertical-align:middle; text-align: center;'>May</th>
                                <th colspan='2' style = 'vertical-align:middle; text-align: center;'>June</th>
                                <th colspan='2' style = 'vertical-align:middle; text-align: center;'>July</th>
                                <th colspan='2' style = 'vertical-align:middle; text-align: center;'>August</th>
                                <th colspan='2' style = 'vertical-align:middle; text-align: center;'>September</th>
                                <th colspan='2' style = 'vertical-align:middle; text-align: center;'>October</th>
                                <th colspan='2' style = 'vertical-align:middle; text-align: center;'>November</th>
                                <th colspan='2' style = 'vertical-align:middle; text-align: center;'>December</th>
                            </tr>
                            <tr>
                                <th style = 'vertical-align:middle; text-align: center;'>Used</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Rem</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Used</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Rem</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Used</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Rem</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Used</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Rem</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Used</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Rem</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Used</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Rem</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Used</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Rem</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Used</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Rem</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Used</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Rem</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Used</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Rem</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Used</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Rem</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Used</th>
                                <th style = 'vertical-align:middle; text-align: center;'>Rem</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                    $x = 1;
                    while ($employee = mysqli_fetch_array($sqlEmployee)) {

                        $jobTitle = htmlspecialchars($employee['jobtitle']); 
                        $dateRegular = date('M d, Y', strtotime($employee['dateofregular']));
                        $dateFulltime = date('M d, Y', strtotime($employee['dateoffulltime']));
                        $hireDate = new DateTime($employee['dateofhired']);
                        $thresholdDate = new DateTime('2020-07-31'); // End of July 2020
                        $jan_earlyout = $employee['jan_earlyout'];
                        $jan_eo_used = $employee['jan_eo_used'];
                        $janrem = $jan_earlyout - $jan_eo_used;
                        $feb_earlyout = $employee['feb_earlyout'];
                        $feb_eo_used = $employee['feb_eo_used'];
                        $febrem = $feb_earlyout - $feb_eo_used;
                        $mar_earlyout = $employee['mar_earlyout'];
                        $mar_eo_used = $employee['mar_eo_used'];
                        $marrem = $mar_earlyout - $mar_eo_used;
                        $apr_earlyout = $employee['apr_earlyout'];
                        $apr_eo_used = $employee['apr_eo_used'];
                        $aprrem = $apr_earlyout - $apr_eo_used;
                        $may_earlyout = $employee['may_earlyout'];
                        $may_eo_used = $employee['may_eo_used'];
                        $mayrem = $may_earlyout - $may_eo_used;
                        $jun_earlyout = $employee['jun_earlyout'];
                        $jun_eo_used = $employee['jun_eo_used'];
                        $junrem = $jun_earlyout - $jun_eo_used;
                        $jul_earlyout = $employee['jul_earlyout'];
                        $jul_eo_used = $employee['jul_eo_used'];
                        $julrem = $jul_earlyout - $jul_eo_used;
                        $aug_earlyout = $employee['aug_earlyout'];
                        $aug_eo_used = $employee['aug_eo_used'];
                        $augrem = $aug_earlyout - $aug_eo_used;
                        $sep_earlyout = $employee['sep_earlyout'];
                        $sep_eo_used = $employee['sep_eo_used'];
                        $seprem = $sep_earlyout - $sep_eo_used;
                        $oct_earlyout = $employee['oct_earlyout'];
                        $oct_eo_used = $employee['oct_eo_used'];
                        $octrem = $oct_earlyout - $oct_eo_used;
                        $nov_earlyout = $employee['nov_earlyout'];
                        $nov_eo_used = $employee['nov_eo_used'];
                        $novrem = $nov_earlyout - $nov_eo_used;
                        $dec_earlyout = $employee['dec_earlyout'];
                        $dec_eo_used = $employee['dec_eo_used'];
                        $decrem = $dec_earlyout - $dec_eo_used;

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
                            <td width='5%' align='center'>{$employee['idno']}</td>
                            <td width='15%'><strong>{$employee['lastname']}</strong>, {$employee['firstname']} {$employee['middlename']} {$employee['suffix']}</td>
                            <td class='text-center align-middle'>{$jan_eo_used}</td>
                            <td class='text-center align-middle'>{$janrem}</td>
                            <td class='text-center align-middle'>{$feb_eo_used}</td>
                            <td class='text-center align-middle'>{$febrem}</td>
                            <td class='text-center align-middle'>{$mar_eo_used}</td>
                            <td class='text-center align-middle'>{$marrem}</td>
                            <td class='text-center align-middle'>{$apr_eo_used}</td>
                            <td class='text-center align-middle'>{$aprrem}</td>
                            <td class='text-center align-middle'>{$may_eo_used}</td>
                            <td class='text-center align-middle'>{$mayrem}</td>
                            <td class='text-center align-middle'>{$jun_eo_used}</td>
                            <td class='text-center align-middle'>{$junrem}</td>
                            <td class='text-center align-middle'>{$jul_eo_used}</td>
                            <td class='text-center align-middle'>{$julrem}</td>
                            <td class='text-center align-middle'>{$aug_eo_used}</td>
                            <td class='text-center align-middle'>{$augrem}</td>
                            <td class='text-center align-middle'>{$sep_eo_used}</td>
                            <td class='text-center align-middle'>{$seprem}</td>
                            <td class='text-center align-middle'>{$oct_eo_used}</td>
                            <td class='text-center align-middle'>{$octrem}</td>
                            <td class='text-center align-middle'>{$nov_eo_used}</td>
                            <td class='text-center align-middle'>{$novrem}</td>
                            <td class='text-center align-middle'>{$dec_eo_used}</td>
                            <td class='text-center align-middle'>{$decrem}</td>
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