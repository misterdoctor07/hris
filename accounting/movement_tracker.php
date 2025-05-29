<?php
// Fetch unique companies from the employee_details table
$sqlCompanies = mysqli_query($con, "SELECT DISTINCT company FROM employee_details ORDER BY company");

if (!$sqlCompanies) {
    echo "Query error: " . mysqli_error($con);
    exit;
}

$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Get distinct years from movement_tracker
$yearResult = mysqli_query($con, "SELECT YEAR(addeddatetime) AS year
    FROM movement_tracker
    WHERE addeddatetime IS NOT NULL AND addeddatetime != '0000-00-00 00:00:00'
    GROUP BY YEAR(addeddatetime)
    ORDER BY year DESC");
?>
<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading">
            <div class="flex-container" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                
                <!-- Left Section -->
                <div class="flex-item-left" style="display: flex; align-items: center; gap: 10px;">
                    <h4>
                        <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | 
                        <i class="fa fa-suitcase"></i> MOVEMENT TRACKER
                    </h4>
                </div>

                <!-- Right Section: Year Selector + Export Button -->
                <div class="right-controls" style="display: flex; align-items: center; gap: 10px; margin-left: auto;">
                    <h4 for="year" class="mb-0" style="font-weight: bold;">Select Year:</h4>
                    <select name="year" id="year" class="form-control" style="width:auto;">
                        <option value="ALL" <?= $selectedYear === 'ALL' ? 'selected' : '' ?>>ALL</option>
                        <?php
                        while ($row = mysqli_fetch_assoc($yearResult)) {
                            $year = $row['year'];
                            $selected = ($year == $selectedYear) ? "selected" : "";
                            echo "<option value='$year' $selected>$year</option>";
                        }
                        ?>
                    </select>
                    <button type="button" onclick="tablesToExcel('Movement_Tracker')" class="btn btn-success">EXPORT TO EXCEL</button>
                </div>

            </div>
        </div>

        <!-- Company Tabs -->
        <ul class="nav nav-tabs">
            <?php
            $active = 'active';
            while ($company = mysqli_fetch_array($sqlCompanies)) {
                $companyCode = htmlspecialchars($company['company']);
                $sanitizedId = preg_replace('/[^A-Za-z0-9\-]/', '', $companyCode);
                echo "<li class='$active'><a data-toggle='tab' href='#tab-$sanitizedId'>$companyCode</a></li>";
                $active = '';
            }
            ?>
        </ul>

        <div class="tab-content">
        <?php
        mysqli_data_seek($sqlCompanies, 0);
        $active = 'in active';
        while ($company = mysqli_fetch_array($sqlCompanies)) {
            $companyCode = htmlspecialchars($company['company']);
            $sanitizedId = preg_replace('/[^A-Za-z0-9\-]/', '', $companyCode);
            echo "<div id='tab-$sanitizedId' class='tab-pane fade $active'>";

            $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.*, d.department, jt.jobtitle 
                FROM employee_profile ep
                INNER JOIN employee_details ed ON ed.idno = ep.idno
                INNER JOIN department d ON d.id = ed.department
                INNER JOIN jobtitle jt ON jt.id = ed.designation
                WHERE ed.company = '$companyCode'
                AND ed.status NOT LIKE '%RESIGNED%'
                ORDER BY ep.lastname ASC
            ");

            if (!$sqlEmployee) {
                echo "Error fetching employees: " . mysqli_error($con);
                continue;
            }

            echo '<br>';
            echo '<div class="d-flex align-items-center mb-3" style="margin-bottom: 3px;">';
            echo '    <div class="input-group" style="width: 300px;">';
            echo '        <input type="text" class="form-control" placeholder="Search..." onkeyup="filterTable(this)">';
            echo '    </div>';
            echo '</div>';

            echo "<table class='table table-bordered table-striped table-condensed'>
                <thead>
                    <tr>
                        <th width='3%' rowspan='2' style='vertical-align:middle; text-align: center;'>No.</th>                      
                        <th rowspan='2' style='vertical-align:middle; text-align: center;'>Employee Name</th>                      
                        <th colspan='2' style='vertical-align:middle; text-align: center;'>Company</th>                      
                        <th colspan='2' style='vertical-align:middle; text-align: center;'>Department</th>
                        <th colspan='2' style='vertical-align:middle; text-align: center;'>Job Position</th>                        
                        <th colspan='2' style='vertical-align:middle; text-align: center;'>Shift</th>                      
                        <th rowspan='2' style='vertical-align:middle; text-align: center;'>Effectivity</th>
                    </tr>
                    <tr>
                        <th style='vertical-align:middle; text-align: center;''>From</th>
                        <th style='vertical-align:middle; text-align: center;''>To</th>
                        <th style='vertical-align:middle; text-align: center;''>From</th>
                        <th style='vertical-align:middle; text-align: center;''>To</th>
                        <th style='vertical-align:middle; text-align: center;''>From</th>
                        <th style='vertical-align:middle; text-align: center;''>To</th>
                        <th style='vertical-align:middle; text-align: center;''>From</th>
                        <th style='vertical-align:middle; text-align: center;''>To</th>
                    </tr>
                </thead>
                <tbody>";

                $x = 1;
                mysqli_query($con, "SET NAMES 'utf8'");
                $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
                $yearFilter = $year === 'ALL' ? '' : "AND YEAR(effectivitydate) = '$year'";

                while ($employee = mysqli_fetch_array($sqlEmployee)) {
                    $idno = $employee['idno'];

                    $companyfrom = $companyto = $departmentfrom = $departmentto = $jobfrom = $jobto = $shift1 = $shift2 = $effectivity = "-";

                    $yearFilter = $year === 'ALL' ? '' : "AND YEAR(effectivitydate) = '$year'";
                    $sqlMovement = mysqli_query($con, "SELECT * FROM movement_tracker 
                        WHERE idno = '$idno' 
                        $yearFilter
                        AND effectivitydate <> '0000-00-00'
                        ORDER BY effectivitydate DESC");

                    while ($row = mysqli_fetch_array($sqlMovement)) {
                        $companyfrom = $row['companyfrom'] ?: "-";
                        $companyto = $row['companyto'] ?: "-";
                        $departmentfrom = $row['departmentfrom'] ?: "-";
                        $departmentto = $row['departmentto'] ?: "-";
                        $jobfrom = $row['jobfrom'] ?: "-";
                        $jobto = $row['jobto'] ?: "-";
                        $shiftfrom = $row['shiftfrom'];
                        $shiftto = $row['shiftto'];
                        $effectivity = date('m/d/y', strtotime($row['effectivitydate']));

                        $shift1 = $shiftfrom ? date('h:i A', strtotime(explode('-', $shiftfrom)[0])) . ' - ' . date('h:i A', strtotime(explode('-', $shiftfrom)[1])) : "-";
                        $shift2 = $shiftto ? date('h:i A', strtotime(explode('-', $shiftto)[0])) . ' - ' . date('h:i A', strtotime(explode('-', $shiftto)[1])) : "-";

                        $dept = mysqli_fetch_array(mysqli_query($con, "SELECT department FROM department WHERE id = '$departmentfrom'"));
                        $departmentfrom = $dept['department'] ?? $departmentfrom;
                        $dept = mysqli_fetch_array(mysqli_query($con, "SELECT department FROM department WHERE id = '$departmentto'"));
                        $departmentto = $dept['department'] ?? $departmentto;
                        $job = mysqli_fetch_array(mysqli_query($con, "SELECT jobtitle FROM jobtitle WHERE id = '$jobfrom'"));
                        $jobfrom = $job['jobtitle'] ?? $jobfrom;
                        $job = mysqli_fetch_array(mysqli_query($con, "SELECT jobtitle FROM jobtitle WHERE id = '$jobto'"));
                        $jobto = $job['jobtitle'] ?? $jobto;

                        echo "<tr>";
                        echo "<td align='center'>$x.</td>";
                        echo "<td><strong style='font-weight: 900;'>{$employee['lastname']}</strong>, {$employee['firstname']} {$employee['middlename']} {$employee['suffix']}</td>";
                        echo "<td align='center'>$companyfrom</td>";
                        echo "<td align='center'>$companyto</td>";
                        echo "<td align='center'>$departmentfrom</td>";
                        echo "<td align='center'>$departmentto</td>";
                        echo "<td align='center'>$jobfrom</td>";
                        echo "<td align='center'>$jobto</td>";
                        echo "<td align='center'>$shift1</td>";
                        echo "<td align='center'>$shift2</td>";
                        echo "<td align='center'>$effectivity</td>";
                        echo "</tr>";
                        $x++;
                    }
                }

                if ($x === 1) {
                    echo "<tr><td colspan='12' align='center'>No records found!</td></tr>";
                }

                echo "</tbody></table></div>";
                $active = '';
            }
        ?>
        </div>
    </div>
</div>

<!-- Ensure Bootstrap JS and jQuery are included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const yearSelect = document.getElementById("year");
        yearSelect.addEventListener("change", function () {
            const selectedYear = this.value;
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('year', selectedYear);
            window.location.search = urlParams.toString();
        });
    });
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
function filterByYear(year) {
    const url = new URL(window.location.href);
    url.searchParams.set('year', year);
    window.location.href = url.toString(); // Redirects with new year param
}
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
    });
    function tablesToExcel() {
        const dataType = 'application/vnd.ms-excel';
        let tableHTML = '';

        // Define filenames based on company tab index
        const filenames = ['NESI1_Movement-Tracker_Report.xls', 'NESI2_Movement-Tracker_Report.xls', 'NEWIND_Movement-Tracker_Report.xls'];

        // Get all outer tabs
        const outerTabs = document.querySelectorAll('.nav-tabs li a');
        let activeTabIndex = -1;

        // Find the index of the active tab
        outerTabs.forEach((tab, index) => {
            if (tab.parentElement.classList.contains('active')) {
                activeTabIndex = index;
            }
        });

        // Set the filename based on the active tab index
        const filename = (activeTabIndex >= 0 && activeTabIndex < filenames.length) ? filenames[activeTabIndex] : 'Movement-Tracker_Report.xls';

        // Get the active tab content pane
        const activeTabHref = outerTabs[activeTabIndex].getAttribute('href');
        const activeTabPane = document.querySelector(activeTabHref);

        // Find the table inside the active tab
        const table = activeTabPane.querySelector('table');
        if (table) {
            // Clone and style table
            const clonedTable = table.cloneNode(true);
            clonedTable.style.borderCollapse = 'collapse';
            clonedTable.querySelectorAll('th, td').forEach(cell => {
                cell.style.border = '1px solid black';
                cell.style.padding = '5px';
            });

            // Add to HTML string
            tableHTML += `<h3>${outerTabs[activeTabIndex].textContent.trim()}</h3>`;
            tableHTML += clonedTable.outerHTML;

            // Create blob and download
            const blob = new Blob([tableHTML], { type: dataType });
            const downloadLink = document.createElement('a');
            downloadLink.href = URL.createObjectURL(blob);
            downloadLink.download = filename;
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    }

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