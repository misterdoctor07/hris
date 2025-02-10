<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<div class="col-lg-12">
    <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-sign-out"></i> RESIGNED EMPLOYEE</h4>
</div>
<div class="col-lg-12">
    <!-- Tab Navigation -->
    <ul class="nav nav-tabs">
        <?php
        mysqli_query($con, "SET NAMES 'utf8'");
        $sqlCompanies = mysqli_query($con, "SELECT DISTINCT ed.company FROM employee_details ed WHERE ed.status LIKE '%RESIGNED%'");
        $activeClass = 'active';
        if (mysqli_num_rows($sqlCompanies) > 0) {
            while ($companyRow = mysqli_fetch_array($sqlCompanies)) {
                $companyName = $companyRow['company'];
                echo "<li class='$activeClass'><a data-toggle='tab' href='#$companyName'>$companyName</a></li>";
                $activeClass = ''; // Set active only for the first tab
            }
        }
        ?>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <?php
        mysqli_data_seek($sqlCompanies, 0); // Reset pointer for reuse
        $activeClass = 'active';
        while ($companyRow = mysqli_fetch_array($sqlCompanies)) {
            $companyName = $companyRow['company'];
        ?>
            <div id="<?= $companyName ?>" class="tab-pane fade in <?= $activeClass ?>">
                <div class="content-panel">
                    <div class="panel-heading">
                        <h5>
                            <i class="fa fa-calendar-o"></i> Filter by Year
                            <select id="yearFilter-<?= $companyName ?>" class="form-control" style="width: auto; display: inline-block; margin-left: 20px;">
                                <option value="all">All Years</option>
                                <?php
                                // Fetch distinct resignation years
                                $sqlYears = mysqli_query($con, "SELECT DISTINCT YEAR(r.dateresigned) AS year FROM resignation r 
                                                                LEFT JOIN employee_details ed ON r.idno = ed.idno 
                                                                WHERE ed.company = '$companyName' ORDER BY year DESC");
                                while ($yearRow = mysqli_fetch_array($sqlYears)) {
                                    echo "<option value='{$yearRow['year']}'>{$yearRow['year']}</option>";
                                }
                                ?>
                            </select>
                            <div style="float:right; margin-bottom: 20px;">
                                <form>
                                    <button type="button" onclick="tablesToExcel('Resigned_Employees')" class="btn btn-success">EXPORT TO EXCEL</button>
                                </form>
                            </div>
                            <span id="visibleCount-<?= $companyName ?>" style="margin-left: 20px;">Total Records: 0</span>
                        </h5>
                    </div>
                    <div class="panel-body">
                        <!-- Search Bar -->
                        <div class="d-flex align-items-center mb-3" style="margin-bottom: 3px;">
                            <div class="input-group" style="width: 300px;">
                                <input type="text" class="form-control" placeholder="Search..." onkeyup="filterTable(this)">
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-condensed">
                            <thead>
                                <tr>
                                    <th width="3%">No.</th>
                                    <th>Emp ID</th>
                                    <th>Employee Name</th>
                                    <th>Date of Birth</th>
                                    <th>Job Title</th>
                                    <th>Department</th>
                                    <th>Date Hired</th>
                                    <th>Date Resigned</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="employeeList-<?= $companyName ?>">
                                <?php
                                    $x = 1; // Initialize the row counter
                                    $sqlEmployee = mysqli_query(
                                        $con,
                                        "SELECT ep.*, ed.*, r.* 
                                        FROM employee_profile ep 
                                        LEFT JOIN employee_details ed ON ed.idno = ep.idno 
                                        LEFT JOIN resignation r ON r.idno = ep.idno 
                                        WHERE ed.status LIKE '%RESIGNED%' AND ed.company = '$companyName'
                                        ORDER BY r.dateresigned DESC" // Sort by date resigned (most recent first)
                                    );

                                    if (mysqli_num_rows($sqlEmployee) > 0) {
                                        while ($employee = mysqli_fetch_array($sqlEmployee)) {
                                            $jobtitle = $department = '';
                                            $empname = $employee['lastname'] . ", " . $employee['firstname'];

                                            // Fetch Job Title
                                            $sqlJobTitle = mysqli_query($con, "SELECT jobtitle FROM jobtitle WHERE id = '$employee[designation]'");
                                            if (mysqli_num_rows($sqlJobTitle) > 0) {
                                                $job = mysqli_fetch_array($sqlJobTitle);
                                                $jobtitle = $job['jobtitle'];
                                            }

                                            // Fetch Department
                                            $sqlDepartment = mysqli_query($con, "SELECT department FROM department WHERE id = '$employee[department]'");
                                            if (mysqli_num_rows($sqlDepartment) > 0) {
                                                $dept = mysqli_fetch_array($sqlDepartment);
                                                $department = $dept['department'];
                                            }

                                            echo "<tr data-year='" . date('Y', strtotime($employee['dateresigned'])) . "'>";
                                            echo "<td>$x.</td>"; // Use $x for numbering
                                            echo "<td>$employee[idno]</td>";
                                            echo "<td>$employee[lastname], $employee[firstname] $employee[middlename] $employee[suffix]</td>";
                                            echo "<td>" . date('M-d-Y', strtotime($employee['birthdate'])) . "</td>";
                                            echo "<td>$jobtitle</td>";
                                            echo "<td>$department</td>";
                                            echo "<td>" . date('m/d/Y', strtotime($employee['dateofhired'])) . "</td>";
                                            echo "<td>" . date('m/d/Y', strtotime($employee['dateresigned'])) . "</td>";
                                            echo "<td align='center'>$employee[reason]</td>";
                                            echo "<td align='center'>
                                                <a href='?editresignation&id=$employee[id]&empname=$empname' title='Edit Details'><i class='fa fa-pencil fa-fw'></i></a>
                                                </td>";
                                            echo "</tr>";
                                            $x++; // Increment the counter
                                        }
                                    } else {
                                        echo "<tr><td colspan='10' align='center'>No record found!</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php
            $activeClass = ''; // Remove active class for remaining tabs
        }
        ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Filter employees by year
        $('[id^=yearFilter-]').on('change', function () {
            const companyId = $(this).attr('id').split('-')[1];
            const selectedYear = $(this).val();
            let visibleCount = 0;

            // Show/hide rows based on the selected year
            $(`#employeeList-${companyId} tr`).each(function () {
                const rowYear = $(this).data('year');
                if (selectedYear === 'all' || rowYear == selectedYear) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });

            // Display the count of visible items
            $(`#visibleCount-${companyId}`).text(`Total Records: ${visibleCount}`);
        });

        // Trigger the change event on page load to show the initial count
        $('[id^=yearFilter-]').trigger('change');
    });

    // Function to initialize tables and sort by recent entries when "All" is selected
    function initializeTables() {
        $('[id^=employeeList-]').each(function () {
            const $tableBody = $(this);
            const rows = $tableBody.find('tr').get();

            // Sort rows by the date resigned in descending order
            rows.sort(function (a, b) {
                const dateA = new Date($(a).find('td:nth-child(8)').text());
                const dateB = new Date($(b).find('td:nth-child(8)').text());
                return dateB - dateA;
            });

            // Append sorted rows back to the table
            $.each(rows, function (index, row) {
                $tableBody.append(row);
            });
        });
    }

    // Run initialization
    $(document).ready(function () {
        initializeTables();
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

    function tablesToExcel() {
    const dataType = 'application/vnd.ms-excel';
    let tableHTML = '';
    const filenames = ['NESI1_Resigned_Employees.xls', 'NESI2_Resigned_Employees.xls', 'NEWIND_Resigned_Employees.xls'];

    const outerTabs = document.querySelectorAll('.nav-tabs li a');
    let activeTabIndex = -1;

    // Identify the active outer tab
    outerTabs.forEach((tab, index) => {
        if (tab.parentElement.classList.contains('active')) {
            activeTabIndex = index;
        }
    });

    const filename = (activeTabIndex >= 0 && activeTabIndex < filenames.length) ? filenames[activeTabIndex] : 'Resigned_Employees.xls';
    const activeOuterTab = outerTabs[activeTabIndex];

    if (activeOuterTab) {
        const outerTabHref = activeOuterTab.getAttribute('href');
        const activeOuterTabPane = document.querySelector(outerTabHref);

        if (activeOuterTabPane) {
            const yearFilter = document.querySelector(`#yearFilter-${activeOuterTab.textContent.trim()}`);
            const selectedYear = yearFilter ? yearFilter.value : 'All Years';
            const companyName = activeOuterTab.textContent.trim();

            const tables = activeOuterTabPane.querySelectorAll('table');

            tables.forEach((table, idx) => {
                // Add company name and year as a header
                tableHTML += `
                    <table>
                        <tr>
                            <td colspan="${table.rows[0].cells.length}" style="font-weight: bold; text-align: center;">
                                ${companyName} - ${selectedYear}
                            </td>
                        </tr>
                    </table>
                `;

                // Clone the table to add borders
                const clonedTable = table.cloneNode(true);

                // Add inline styles for borders
                clonedTable.style.borderCollapse = 'collapse';
                clonedTable.querySelectorAll('th, td').forEach(cell => {
                    cell.style.border = '1px solid black';
                    cell.style.padding = '5px';
                });

                // Append the styled table
                tableHTML += clonedTable.outerHTML + '<br>';
            });

            if (tableHTML.trim() === '') {
                alert('No data found in the tables to export.');
                return;
            }

            const downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);

            const blob = new Blob([tableHTML], { type: dataType });
            const url = URL.createObjectURL(blob);
            downloadLink.href = url;
            downloadLink.download = filename;
            downloadLink.click();

            document.body.removeChild(downloadLink);
        } else {
            alert('No tables found in the active tab.');
        }
    } else {
        alert('No active tab detected.');
    }
}
</script>
