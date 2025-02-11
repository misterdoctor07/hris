<?php
// Fetch unique companies from the employee_details table
$sqlCompanies = mysqli_query($con, "SELECT DISTINCT company FROM employee_details ORDER BY company");

if (!$sqlCompanies) {
    echo "Query error: " . mysqli_error($con);
    exit;
}
$type=$_GET["type"]??'';
if($type=="pending"){
  $labeltype="Pending List";
}else{
  $labeltype="Served List";
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
th.sortable {
    cursor: pointer;
    position: relative;
}

th.sortable.asc::after {
    content: '↑'; /* Ascending arrow icon */
    color: #000;
}

th.sortable.desc::after {
    content: '↓'; /* Descending arrow icon */
    color: #000;
}
</style>

<div class="col-lg-12">
    <div class="content-panel">
      <div class="panel-heading">
          <div class="flex-container" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
              <!-- Left Section -->
              <div class="flex-item-left" style="display: flex; align-items: center; gap: 10px;">
                  <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> |
                  <i class="fa fa-user"></i> INFRACTION LIST
              </div>

              <!-- Date Filter Section -->
              <div class="date-filter" style="display: flex; align-items: center; gap: 10px;">
                  <label for="fromDate">From:</label>
                  <input type="date" id="fromDate" class="form-control" value="<?php echo isset($_GET['fromDate']) ? $_GET['fromDate'] : ''; ?>" style="width: 150px;">
                  <label for="toDate">To:</label>
                  <input type="date" id="toDate" class="form-control" value="<?php echo isset($_GET['toDate']) ? $_GET['toDate'] : ''; ?>" style="width: 150px;">
                  <button type="button" onclick="filterByDate()" class="btn btn-primary">Filter</button>
                  <button type="button" onclick="resetFilter()" class="btn btn-default">Reset</button>
              </div>

              <!-- Issue Infraction Button -->
              <div class="flex-item" style="margin-left: auto;">
                  <a href="?addinfraction" class="btn btn-primary">
                      <i class="fa fa-plus-circle"></i> Issue Infraction
                  </a>
              </div>

              <!-- Export to Excel Button -->
              <div class="export-btn">
                  <form>
                      <button type="button" onclick="tablesToExcel('Infraction_Report')" class="btn btn-success">EXPORT TO EXCEL</button>
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
                    $fromDate = isset($_GET['fromDate']) ? $_GET['fromDate'] : null;
                    $toDate = isset($_GET['toDate']) ? $_GET['toDate'] : null;
                    $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.*, ep.*,i.id,i.dateserved,i.dateissued,i.typeofoffense,i.typeofmemo,i.points,i.memonumber,i.dateofsuspension,i.status, d.department, ed.designation, jt.jobtitle 
                        FROM employee_profile ep
                        INNER JOIN employee_details ed ON ed.idno = ep.idno
                        INNER JOIN department d ON d.id = ed.department
                        INNER JOIN jobtitle jt ON jt.id = ed.designation
                        INNER JOIN infraction i ON i.idno=ep.idno
                        WHERE ed.company = '$companyCode' 
                        AND d.department = '$departmentName'
                        AND (i.dateserved BETWEEN '$fromDate' AND '$toDate' OR '$fromDate' = '' OR '$toDate' = '')  
                        AND ed.status NOT LIKE '%RESIGNED%'
                        ORDER BY 
                          CASE 
                              WHEN i.status = 'pending' THEN 1
                              WHEN i.status = 'Served' THEN 2
                              ELSE 3
                          END,
                          i.dateissued ASC");

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
                              <th class='sortable' data-column='0' style='text-align: center;'>No.</th>
                              <th class='sortable' data-column='1' style='text-align: center;'>Emp ID</th>
                              <th class='sortable' data-column='2' style='text-align: center;'>Employee Name</th>
                              <th class='sortable' data-column='3' style='text-align: center;'>Job Title</th>
                              <th class='sortable' data-column='3' style='text-align: center;'>Team</th>
                              <th class='sortable' data-column='4' style='text-align: center;'>Company</th>
                              <th class='sortable' data-column='5' style='text-align: center;'>Date Issued</th>
                              <th class='sortable' data-column='6' style='text-align: center;'>Date Served</th>
                              <th class='sortable' data-column='7' style='text-align: center;'>Type of Memo</th>
                              <th class='sortable' data-column='8' style='text-align: center;'>Type of Offense</th>
                              <th class='sortable' data-column='9' style='text-align: center;'>Points</th>
                              <th class='sortable' data-column='10' style='text-align: center;'>Memo No.</th>
                              <th class='sortable' data-column='11' style='text-align: center;'>Date of Incident</th>
                              <th class='sortable' data-column='12' style='text-align: center;'>Suspension Dates</th>
                              <th class='sortable' data-column='13' style='text-align: center;'>Status</th>
                              <th style='text-align: center;'>Action</th>
                            </tr>
                        </thead>
                        <tbody>";

                    $x = 1;
                    $sqlEmployee=mysqli_query($con,"SELECT ep.*, ed.*, ep.*,i.id,i.dateserved,i.dateofincident,i.dateissued,i.typeofoffense,i.typeofmemo,i.points,i.memonumber,i.dateofsuspension,i.status, d.department, ed.designation, jt.jobtitle 
                        FROM employee_profile ep
                        INNER JOIN employee_details ed ON ed.idno = ep.idno
                        INNER JOIN department d ON d.id = ed.department
                        INNER JOIN jobtitle jt ON jt.id = ed.designation
                        INNER JOIN infraction i ON i.idno=ep.idno
                        WHERE ed.company = '$companyCode' 
                          AND d.department = '$departmentName' 
                          AND ed.status NOT LIKE '%RESIGNED%'
                          AND (i.dateserved OR i.dateissued BETWEEN '$fromDate' AND '$toDate' OR '$fromDate' = '' OR '$toDate' = '')  
                        ORDER BY 
                          CASE 
                              WHEN i.status = 'pending' THEN 1
                              WHEN i.status = 'Served' THEN 2
                              ELSE 3
                          END,
                          i.dateissued ASC");
                    if(mysqli_num_rows($sqlEmployee)>0){
                      while($company=mysqli_fetch_array($sqlEmployee)){
                        $idno=$company['idno'];
                        $lastname=$company['lastname'];
                        $firstname=$company['firstname'];
                        $middlename=$company['middlename'];
                        $suffix=$company['suffix'];
                        $jobtitle=$company['jobtitle'];
                        $dateissued=$company['dateissued'];
                        $dateserved=$company['dateserved'];
                        $dateofincident=$company['dateofincident'];
                        $typeofoffense=$company['typeofoffense'];
                        $typeofmemo=$company['typeofmemo'];
                        $points=$company['points'];
                        $memonumber=$company['memonumber'];
                        $dateofsuspension=$company['dateofsuspension'];
                        $status=$company['status'];
                        $sqlDept=mysqli_query($con,"SELECT d.department FROM department d LEFT JOIN employee_details ed ON ed.department=d.id WHERE ed.idno='$idno'");
                        $dept=mysqli_fetch_array($sqlDept);
                        $sqlDept=mysqli_query($con,"SELECT company FROM employee_details WHERE idno='$idno'");
                        $comp=mysqli_fetch_array($sqlDept);
                        if($status=="Void"){
                          $style="class='danger'";
                        }elseif($status=="pending"){
                          $style="class='warning'";
                        }else{
                          $style="class='success'";
                        }
                        echo "<tr $style>";
                          echo "<td align='center'>$x.</td>";
                          echo "<td align='center'>$idno</td>";
                          echo "<td>$lastname, $firstname $middlename $suffix</td>";
                          echo "<td>$jobtitle</td>";
                          echo "<td align='center'>$dept[department]</td>";
                          echo "<td align='center'>$comp[company]</td>";
                          echo "<td align='center'>$dateissued</td>";
                          echo "<td align='center'>$dateserved</td>";
                          echo "<td align='center'>$typeofmemo</td>";
                          echo "<td align='center'>$typeofoffense</td>";                            
                          echo "<td align='center'>$points</td>";
                          echo "<td align='center'>$memonumber</td>";
                          echo "<td align='center'>$dateofincident</td>";
                          echo "<td align='center'>$dateofsuspension</td>";
                          echo "<td align='center'>$status</td>";
                          ?>
                          <td align="center">
                          <?php
                            if($status=="pending"){
                            ?>                              
                            <a href="?manageinfraction&id=<?=$company['id'];?>&serve" class="btn btn-success btn-xs" title="Serve Infraction" onclick="return confirm('Do you wish to serve this infraction?'); return false;"><i class='fa fa-check'></i></a>
                            <a href="?editinfraction&id=<?=$company['id'];?>" class="btn btn-primary btn-xs" title="Edit Infraction"><i class='fa fa-pencil'></i></a>
                            <?php
                            }                              
                            if($status=="pending"){
                            ?>
                            <a href="?manageinfraction&id=<?=$company['id'];?>&delete" class="btn btn-danger btn-xs" title="Void Infraction" onclick="return confirm('Do you wish to void this infraction?'); return false;"><i class='fa fa-trash'></i></a>
                            <?php
                            }else{
                              ?>
                              <a href="?manageinfraction&id=<?=$company['id'];?>&undo" class="btn btn-info btn-xs" title="Restore Infraction" onclick="return confirm('Do you wish to restore this infraction?'); return false;"><i class='fa fa-exchange'></i></a>
                              <?php
                            }
                            ?>
                          </td>
                          <?php
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

<?php
    if(isset($_GET['delete'])){
      $id=$_GET['id'];
      $datenow=date('Y-m-d H:i:s');
      $sqlDelete=mysqli_query($con,"UPDATE infraction SET `status`='Void',updatedby='$fullname',updateddatetime='$datenow' WHERE id='$id'");
      if($sqlDelete){
        echo "<script>alert('Infraction successfully void!');window.location='?manageinfraction';</script>";
      }else{
        echo "<script>alert('Unable to void infraction!');window.location='?manageinfraction';</script>";
      }
    }
    if(isset($_GET['undo'])){
      $id=$_GET['id'];
      $datenow=date('Y-m-d H:i:s');
      $sqlDelete=mysqli_query($con,"UPDATE infraction SET `status`='pending',updatedby='$fullname',updateddatetime='$datenow' WHERE id='$id'");
      if($sqlDelete){
        echo "<script>alert('Infraction successfully restored!');window.location='?manageinfraction';</script>";
      }else{
        echo "<script>alert('Unable to restore infraction!');window.location='?manageinfraction';</script>";
      }
    }
    if(isset($_GET['serve'])){
      $id=$_GET['id'];              
      $datenow=date('Y-m-d H:i:s');
      $sqlDelete=mysqli_query($con,"UPDATE infraction SET `status`='Served',updatedby='$fullname',updateddatetime='$datenow' WHERE id='$id'");
      if($sqlDelete){
        echo "<script>alert('Infraction successfully served!');window.location='?manageinfraction';</script>";
      }else{
        echo "<script>alert('Unable to serve infraction!');window.location='?manageinfraction';</script>";
      }
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
document.addEventListener('DOMContentLoaded', function() {
    // Set default to "pending"
    document.querySelector('button[onclick="filterData(\'pending\')"]').classList.add('btn-primary');
    document.querySelector('button[onclick="filterData(\'served\')"]').classList.add('btn-default');
    filterData('pending');
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
});
    function tablesToExcel() {
        const dataType = 'application/vnd.ms-excel';
        let tableHTML = '';

        // Define filenames based on the outer tab index
        const filenames = ['NESI1_Infraction_Report.xls', 'NESI2_Infraction_Report.xls', 'NEWIND_Infraction_Report.xls'];

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
        const filename = (activeTabIndex >= 0 && activeTabIndex < filenames.length) ? filenames[activeTabIndex] : 'Infraction_Report.xls';

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
    labelType.textContent = type === 'pending' ? 'Pending List' : 'Served List';

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
    const insuranceButton = document.querySelector('button[onclick="filterData(\'pending\')"]');
    const hmoButton = document.querySelector('button[onclick="filterData(\'served\')"]');
    if (type === 'pending') {
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
    filterData('pending');  // Set the default to insurance when the page loads
});

//Filter Button for Date Filter
function filterByDate() {
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;

    if (fromDate && toDate) {
            window.location.href = `?manageinfraction&fromDate=${fromDate}&toDate=${toDate}`;
        } else {
            alert('Please select both "From" and "To" dates.');
    }
}
//Reset Button for Date Filter
function resetFilter() {
    window.location.href = '?manageinfraction';
}

//Sorting
document.addEventListener("DOMContentLoaded", function () {
    const headers = document.querySelectorAll(".sortable");
    headers.forEach(header => {
        header.addEventListener("click", function () {
            const table = header.closest("table");
            const tbody = table.querySelector("tbody");
            const columnIndex = parseInt(header.getAttribute("data-column"));
            const isAscending = header.classList.contains("asc");
            
            // Clear existing sorting classes
            headers.forEach(h => h.classList.remove("asc", "desc"));

            // Toggle sorting order
            header.classList.toggle("asc", !isAscending);
            header.classList.toggle("desc", isAscending);

            const rows = Array.from(tbody.querySelectorAll("tr"));
            rows.sort((a, b) => {
                const aText = a.cells[columnIndex].innerText.trim();
                const bText = b.cells[columnIndex].innerText.trim();

                // Handle numeric vs. string comparison
                return isAscending
                    ? compareValues(bText, aText)
                    : compareValues(aText, bText);
            });

            // Append sorted rows back to the table body
            rows.forEach(row => tbody.appendChild(row));
        });
    });

    function compareValues(a, b) {
        if (!isNaN(a) && !isNaN(b)) {
            return parseFloat(a) - parseFloat(b); // Numeric comparison
        }
        return a.localeCompare(b); // String comparison
    }
});
</script>