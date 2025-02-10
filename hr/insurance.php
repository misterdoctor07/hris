<?php
// Fetch unique companies from the employee_details table
$sqlCompanies = mysqli_query($con, "SELECT DISTINCT company FROM employee_details ORDER BY company");

if (!$sqlCompanies) {
    echo "Query error: " . mysqli_error($con);
    exit;
}
$type=$_GET["type"]??'';
if($type=="insurance"){
  $labeltype="Life Insurance Effectivity";
}else{
  $labeltype="HMO Effectivity";
}
?>

<style>
.badge-switch {
    display: inline-block;
    margin-top: 5px;
}

.badge-primary {
    background-color: #007bff;
}

.badge-secondary {
    background-color: #6c757d;
}

.badge.active {
    background-color: #28a745;
}
.flex-container {
    display: flex;
    align-items: center; /* Vertically align items */
    justify-content: space-between; /* Add spacing between items */
    flex-wrap: nowrap; /* Prevent items from wrapping */
    gap: 10px; /* Optional: spacing between items */
}

.flex-item {
    display: flex;
    align-items: center;
    gap: 5px; /* Optional: spacing within each item group */
}

.flex-item-left {
    display: flex;
    align-items: center;
    gap: 5px; /* Optional: spacing within each item group */
    font-size: large;
    margin-right: 20px;
}

.badge-switch button {
    margin-top: 0; /* Remove unnecessary top margin */
}
</style>

<div class="col-lg-12">
    <div class="content-panel">
      <div class="panel-heading">
          <div class="flex-container">
              <div class="flex-item-left">
                  <a href="?main"><i class="fa fa-arrow-left"></i> BACK</a> |
                  <i class="fa fa-user"></i> EMPLOYEE LIST
              </div>
              <div class="flex-item">
                  <span id="filterLabel">Life Insurance Effectivity</span>
                  <span class="badge-switch">
                      <button type="button" onclick="filterData('insurance')" class="btn btn-primary">Insurance</button>
                      <button type="button" onclick="filterData('hmo')" class="btn btn-default">HMO</button>
                  </span>
              </div>
              <div class="flex-item" style="margin-left: auto;">
                  <a href="?addemployee" class="btn btn-primary">
                      <i class="fa fa-plus-circle"></i> Add Employee
                  </a>
              </div>
              <div style="float:right;">
                    <form>
                        <button type="button" onclick="tablesToExcel('Insurance/HMO_Report')" class="btn btn-success">EXPORT TO EXCEL</button>
                    </form>
              </div>
          </div>
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
                    $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.*, eb.*, eb.insurance, eb.hmo, d.department, ed.designation, jt.jobtitle 
                        FROM employee_profile ep
                        INNER JOIN employee_details ed ON ed.idno = ep.idno
                        INNER JOIN department d ON d.id = ed.department
                        INNER JOIN jobtitle jt ON jt.id = ed.designation
                        INNER JOIN employee_benefits eb ON eb.idno = ep.idno
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
                                <th>No.</th>
                                <th>Emp ID</th>
                                <th>Employee Name</th>
                                <th class='insurance-hmo-header' style='text-align:center;'>Life Insurance Effectivity</th>
                                <th style='text-align:center;'>SSS</th>
                                <th style='text-align:center;'>TIN</th>
                                <th style='text-align:center;'>PHIC</th>
                                <th style='text-align:center;'>PAG-IBIG</th>
                            </tr>
                        </thead>
                        <tbody>";

                    $x = 1;
                    mysqli_query($con,"SET NAMES 'utf8'");
                    if($type=="insurance"){
                      $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.*,eb.*,eb.insurance 
                        FROM employee_profile ep 
                        INNER JOIN employee_details ed ON ed.idno=ep.idno 
                        INNER JOIN department d ON d.id = ed.department
                        INNER JOIN jobtitle jt ON jt.id = ed.designation
                        INNER JOIN employee_benefits eb ON eb.idno=ep.idno 
                        WHERE ed.company = '$companyCode' AND d.department = '$departmentName' AND ed.status NOT LIKE '%RESIGNED%' 
                        ORDER BY ep.lastname ASC");                      
                    }else if($type=="hmo"){
                      $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.*,eb.*,eb.hmo as insurance 
                        FROM employee_profile ep 
                        INNER JOIN employee_details ed ON ed.idno=ep.idno
                        INNER JOIN department d ON d.id = ed.department
                        INNER JOIN jobtitle jt ON jt.id = ed.designation 
                        INNER JOIN employee_benefits eb ON eb.idno=ep.idno 
                        WHERE ed.company = '$companyCode' AND d.department = '$departmentName' AND ed.status NOT LIKE '%RESIGNED%' 
                        ORDER BY ep.lastname ASC");
                    }
                    if(mysqli_num_rows($sqlEmployee)>0){
                        while($company=mysqli_fetch_array($sqlEmployee)){
                            $insurance = date('m/d/Y', strtotime($company['insurance']));
                            $hmo = date('m/d/Y', strtotime($company['hmo']));
                            
                            echo "<tr data-insurance='$insurance' data-hmo='$hmo'>";
                            echo "<td>$x.</td>";
                            echo "<td>$company[idno]</td>";
                            echo "<td>$company[lastname], $company[firstname] $company[middlename] $company[suffix]</ td>";
                            echo "<td align='center' class='insurance-hmo-data'>$insurance</td>";
                            echo "<td align='center'>$company[sss]</td>";
                            echo "<td align='center'>$company[tin]</td>";
                            echo "<td align='center'>$company[phic]</td>";
                            echo "<td align='center'>$company[hdmf]</td>";
                            echo "</tr>";
                            $x++;
                      }
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
});
document.addEventListener('DOMContentLoaded', function() {
    // Set default to "insurance"
    document.querySelector('button[onclick="filterData(\'insurance\')"]').classList.add('btn-primary');
    document.querySelector('button[onclick="filterData(\'hmo\')"]').classList.add('btn-default');
    filterData('insurance');
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
    function tablesToExcel() {
        const dataType = 'application/vnd.ms-excel';
        let tableHTML = '';

        // Define filenames based on the outer tab index
        const filenames = ['NESI1_Insurance/HMO_Report.xls', 'NESI2_Insurance/HMO_Report.xls', 'NEWIND_Insurance/HMO_Report.xls'];

        // Get all outer tabs
        const outerTabs = document.querySelectorAll('.nav-tabs li a');
        let activeTabIndex = -1;

        // Find the index of the active outer tab
        outerTabs.forEach((tab, index) => {
            if (tab.parentElement.classList.contains('active')) {
                activeTabIndex = index; // Set the index of the active tab
            }
        });

        // Set the filename based on the active tab index
        const filename = (activeTabIndex >= 0 && activeTabIndex < filenames.length) ? filenames[activeTabIndex] : 'Insurance/HMO_Report.xls';

        // Get the currently active outer tab
        const activeOuterTab = outerTabs[activeTabIndex];
        if (activeOuterTab) {
            const outerTabHref = activeOuterTab.getAttribute('href'); // Get the href of the active outer tab
            const activeOuterTabPane = document.querySelector(outerTabHref); // Get the corresponding tab pane

            // Gather all inner tabs and their corresponding tables from the active outer tab pane
            const innerTabs = activeOuterTabPane.querySelectorAll('.nav-pills li a');
            innerTabs.forEach(innerTab => {
                // Get the inner tab name and remove any trailing numbers
                let innerTabName = innerTab.textContent.trim();
                innerTabName = innerTabName.replace(/\s+\d+$/, ''); // Remove trailing space and number

                const innerTabContent = document.querySelector(innerTab.getAttribute('href')); // Get the corresponding inner tab content

                // Check if the inner tab content has a table
                const table = innerTabContent.querySelector('table');
                if (table) {
                    // Add inner tab name as a header before the table
                    tableHTML += `<h3>${innerTabName}</h3>`; // Add header for the table

                    // Clone the table to modify it
                    const clonedTable = table.cloneNode(true);
                    
                    // Add inline styles for borders
                    clonedTable.style.borderCollapse = 'collapse'; // Collapse borders
                    clonedTable.querySelectorAll('th, td').forEach(cell => {
                        cell.style.border = '1px solid black'; // Add border to each cell
                        cell.style.padding = '5px'; // Optional: Add padding for better spacing
                    });

                    tableHTML += clonedTable.outerHTML + '<br>'; // Append each table's HTML
                }
            });

            // Create a download link
            const downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);

            // Create a Blob with the combined table HTML
            const blob = new Blob([tableHTML], {
                type: dataType
            });

            // Create a URL for the Blob
            const url = URL.createObjectURL(blob);
            downloadLink.href = url;
            downloadLink.download = filename; // Set the correct filename

            // Trigger the download
            downloadLink.click();

            // Clean up
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
    function filterData(type) {
    // Update the filter label (top label text)
    const labelType = document.querySelector('#filterLabel');
    labelType.textContent = type === 'insurance' ? 'Life Insurance Effectivity' : 'HMO Effectivity';

    // Update column headers across all visible tables (using the 'insurance-hmo-header' class)
    const columnHeaders = document.querySelectorAll('th.insurance-hmo-header');
    columnHeaders.forEach(header => {
        header.textContent = type === 'insurance' ? 'Life Insurance Effectivity' : 'HMO Effectivity';
    });

    // Update the column content across all visible rows
    const allRows = document.querySelectorAll('table tbody tr');
    allRows.forEach(row => {
        const insuranceHmoCell = row.querySelector('.insurance-hmo-data');
        if (insuranceHmoCell) {
            const insurance = row.getAttribute('data-insurance');
            const hmo = row.getAttribute('data-hmo');
            insuranceHmoCell.textContent = type === 'insurance' ? insurance : hmo;
        }
    });

    // Update button colors: Add "btn-primary" to the active button, remove from the other
    const insuranceButton = document.querySelector('button[onclick="filterData(\'insurance\')"]');
    const hmoButton = document.querySelector('button[onclick="filterData(\'hmo\')"]');
    if (type === 'insurance') {
        insuranceButton.classList.remove('btn-default');
        insuranceButton.classList.add('btn-primary');
        hmoButton.classList.remove('btn-primary');
        hmoButton.classList.add('btn-default');
    } else {
        hmoButton.classList.remove('btn-default');
        hmoButton.classList.add('btn-primary');
        insuranceButton.classList.remove('btn-primary');
        insuranceButton.classList.add('btn-default');
    }
}

// Initialize with "insurance" as the default view
document.addEventListener('DOMContentLoaded', function () {
    filterData('insurance');  // Set the default to insurance when the page loads
});
</script>