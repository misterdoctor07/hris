<?php
// Fetch unique companies from the employee_details table
$sqlCompanies = mysqli_query($con, "SELECT DISTINCT company FROM employee_details ORDER BY company");

if (!$sqlCompanies) {
    echo "Query error: " . mysqli_error($con);
    exit;
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
                  <i class="fa fa-track"></i> MOVEMENT TRACKER
              </div>
              <div class="flex-item" style="margin-left: auto;">
                  <span>Select Year</span>
                  <select name="year" id="year"></select>
              </div>
              <div style="float:right;">
                    <form>
                        <button type="button" onclick="tablesToExcel('Movement Tracker')" class="btn btn-success">EXPORT TO EXCEL</button>
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
                    // Fetch employees for the company and department
                    $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.*, mt.*, d.department, ed.designation, jt.jobtitle 
                        FROM employee_profile ep
                        INNER JOIN employee_details ed ON ed.idno = ep.idno
                        INNER JOIN department d ON d.id = ed.department
                        INNER JOIN jobtitle jt ON jt.id = ed.designation
                        INNER JOIN movement_tracker mt ON mt.idno = ep.idno
                        WHERE ed.company = '$companyCode'
                        AND ed.status NOT LIKE '%RESIGNED%'
                        ORDER BY ep.lastname ASC");

                    if (!$sqlEmployee) {
                        echo "Error fetching employees: " . mysqli_error($con);
                        continue;
                    }
                    echo '<br>';
                    echo '<!-- Search Bar -->';
                    echo '<div class="d-flex align-items-center mb-3" style="margin-bottom: 3px;">';
                    echo '    <div class="input-group" style="width: 300px;">';
                    echo '        <input type="text" class="form-control" placeholder="Search..." onkeyup="filterTable(this)">';
                    echo '    </div>';
                    echo '</div>';

                    echo "<table class='table table-bordered table-striped table-condensed'>
                        <thead>
                            <tr>
                            <th width='3%' rowspan='2' style='vertical-align:middle;'>No.</th>                      
                            <th  rowspan='2' style='vertical-align:middle;'>Employee Name</th>                      
                            <th  colspan='2' align='center'>Company</th>                      
                            <th  colspan='2' align='center'>Department</th>
                            <th  colspan='2' align='center'>Job Position</th>                        
                            <th colspan='2' align='center'>Shift</th>                      
                            <th  rowspan='2' style='vertical-align:middle;'>Effectivity</th>
                            </tr>
                            <tr>
                                <th align='center'>From</th>
                                <th align='center'>To</th>
                                <th align='center'>From</th>
                                <th align='center'>To</th>
                                <th align='center'>From</th>
                                <th align='center'>To</th>
                                <th align='center'>From</th>
                                <th align='center'>To</th>
                            </tr>
                        </thead>
                        <tbody>";

                        $x=1;
                        mysqli_query($con,"SET NAMES 'utf8'");
                          $year = isset($_GET['year']) ? $_GET['year'] : null;
                          $sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' ORDER BY ep.lastname ASC");
                          if(mysqli_num_rows($sqlEmployee)>0){
                            while($company=mysqli_fetch_array($sqlEmployee)){
                                $idno=$company['idno'];
                                $display=1;
                                    $companyfrom="-";
                                    $companyto="-";
                                    $departmentfrom="-";
                                    $departmentto="-";
                                    $jobfrom="-";
                                    $jobto="-";
                                    $shiftfrom="";
                                    $shiftto="";
                                    $effectivity="";
                                $sqlMovement=mysqli_query($con,"SELECT * FROM movement_tracker WHERE idno='$idno' AND YEAR(addeddatetime)='$year' AND effectivitydate <> '0000-00-00'");
                                if(mysqli_num_rows($sqlMovement)>0){
                                    $row=mysqli_fetch_array($sqlMovement);
                                    $companyfrom=$row['companyfrom'];
                                    $companyto=$row['companyto'];
                                    $departmentfrom=$row['departmentfrom'];
                                    $departmentto=$row['departmentto'];
                                    $jobfrom=$row['jobfrom'];
                                    $jobto=$row['jobto'];
                                    $shiftfrom=$row['shiftfrom'];
                                    $shiftto=$row['shiftto'];
                                    $effectivity=$row['effectivitydate'];
                                    if($companyfrom==$companyCode || $companyfrom==''){
                                        if($company['company']==$companyCode){
                                            $display=1;
                                        }else if($company['company']!=$companyCode && $companyfrom==$companyCode){
                                            $display=1;
                                        }else{
                                            $display=0;
                                        }
                                    }else{
                                        $display=0;
                                    }
                                }else{
                                    $display=0;
                                }
                                $sqlDepartment=mysqli_query($con,"SELECT * FROM department WHERE id='$departmentfrom'");
                                if(mysqli_num_rows($sqlDepartment)>0){
                                    $dept=mysqli_fetch_array($sqlDepartment);
                                    $departmentfrom=$dept['department'];
                                }else{
                                    $departmentfrom="-";
                                }
                                $sqlDepartment=mysqli_query($con,"SELECT * FROM department WHERE id='$departmentto'");
                                if(mysqli_num_rows($sqlDepartment)>0){
                                    $dept=mysqli_fetch_array($sqlDepartment);
                                    $departmentto=$dept['department'];
                                }else{
                                    $departmentto="-";
                                }
                                $sqlDepartment=mysqli_query($con,"SELECT * FROM jobtitle WHERE id='$jobfrom'");
                                if(mysqli_num_rows($sqlDepartment)>0){
                                    $dept=mysqli_fetch_array($sqlDepartment);
                                    $jobfrom=$dept['jobtitle'];
                                }else{
                                    $jobfrom="-";
                                }
                                $sqlDepartment=mysqli_query($con,"SELECT * FROM jobtitle WHERE id='$jobto'");
                                if(mysqli_num_rows($sqlDepartment)>0){
                                    $dept=mysqli_fetch_array($sqlDepartment);
                                    $jobto=$dept['jobtitle'];
                                }else{
                                    $jobto="-";
                                }
                                if($display==1){                                
                                    if($shiftfrom != ""){
                                        $sfrom=explode('-',$shiftfrom);
                                        $shift11=date('h:i A',strtotime($sfrom[0]));
                                        $shift12=date('h:i A',strtotime($sfrom[1]));
                                        $shift1=$shift11." - ".$shift12;
                                    }else{
                                        $shift1="-";
                                    }                                
                                    if($shiftto != ""){
                                        $sto=explode('-',$shiftto);
                                        $shift21=date('h:i A',strtotime($sto[0]));
                                        $shift22=date('h:i A',strtotime($sto[1]));
                                        $shift2=$shift21." - ".$shift22;
                                    }else{
                                        $shift2="-";
                                    }
                              echo "<tr>";
                                echo "<td>$x.</td>";                            
                                echo "<td>$company[lastname], $company[firstname] $company[middlename] $company[suffix]</td>";                            
                                echo "<td align='center'>$companyfrom</td>";                            
                                echo "<td align='center'>$companyto</td>";
                                echo "<td align='center'>$departmentfrom</td>";
                                echo "<td align='center'>$departmentto</td>";
                                echo "<td align='center'>$jobfrom</td>";
                                echo "<td align='center'>$jobto</td>";
                                echo "<td align='center'>$shift1</td>";
                                echo "<td align='center'>$shift2</td>";
                                echo "<td align='center'>".date('m/d/y',strtotime($effectivity))."</td>";
                              echo "</tr>";
                              $x++;
                            }
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
        const filenames = ['NESI1_Movement-Tracker_Report.xls', 'NESI2_Movement-Tracker_Report.xls', 'NEWIND_Movement-Tracker_Report.xls'];

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
        const filename = (activeTabIndex >= 0 && activeTabIndex < filenames.length) ? filenames[activeTabIndex] : 'Movement-Tracker_Report.xls';

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

//Year Selection
const yearSelect = document.getElementById("year");
const currentYear = new Date().getFullYear();

// Add "ALL" option at the top
const allOption = document.createElement("option");
allOption.value = "all";
allOption.textContent = "ALL";
yearSelect.appendChild(allOption);

// Populate years from current year down to 2019
for (let year = currentYear; year >= 2019; year--) {
  const option = document.createElement("option");
  option.value = year;
  option.textContent = year;
  yearSelect.appendChild(option);
}

// Set default selection to current year
yearSelect.value = currentYear;

</script>