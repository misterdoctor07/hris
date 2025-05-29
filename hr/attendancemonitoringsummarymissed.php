<style>
  .attendance-container {
    font-family: Arial, sans-serif;
    margin: 20px;
  }
  
  .header-section {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
  }
  
  .company-header {
    font-size: 24px;
    font-weight: bold;
    text-align: center;
    text-transform: uppercase;
    border: 2px solid #000;
    width: 20%;
    background: #ffcccc;
    padding: 10px;
    margin-right: 50px;
  }
  
  .legend-table {
    border-collapse: collapse;
    font-size: 12px;
    font-weight: bold;
    width: 60%;
  }
  
  .legend-table th, .legend-table td {
    border: 1px solid #000;
    padding: 5px;
    text-align: center;
  }
  
  .legend-header {
    background-color: #ff7c80;
  }
  
  .controls {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
  }
  
  .search-box {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }
  
  .search-btn {
    padding: 8px 15px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  .search-btn:hover {
    background-color: #45a049;
  }
  
  .export-btn {
    padding: 10px 15px;
    background-color: #13A14D;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .export-btn:hover {
    background-color: #06753a;
  }
  
  .attendance-table-container {
    overflow-x: auto;
    border: 1px solid #ddd;
    margin-bottom: 20px;
  }
  
  .attendance-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
  }
  
  .attendance-table th, .attendance-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
    white-space: nowrap;
  }
  
  .attendance-table th {
    background-color: #f2f2f2;
    position: sticky;
    top: 0;
  }
  
  .fixed-column {
    position: sticky;
    left: 0;
    background-color: #f9f9f9;
    z-index: 1;
  }
  
  .fixed-column-2 {
    position: sticky;
    left: 40px;
    background-color: #f9f9f9;
    z-index: 1;
  }
  
  .fixed-column-3 {
    position: sticky;
    left: 200px;
    background-color: #f9f9f9;
    z-index: 1;
  }
  
  .weekend-cell {
    background-color: #f5f5f5;
  }
  
  .incident-cell {
    background-color: #f4c7c3;
  }
  
  .summary-cell {
    font-weight: bold;
    background-color: #e6f3ff;
  }
</style>
<?php
include('../config.php');
$dept=$_GET["dept"];
$startdate=$_GET['startdate'];
$enddate=$_GET['enddate'];
$sqlCompany=mysqli_query($con,"SELECT companyname FROM settings WHERE companycode='$dept'");
$comp=mysqli_fetch_array($sqlCompany);
?>
<div class="attendance-container">
  <div class="header-section">
    <div class="company-header">
      <?=$comp['companyname'];?><br>
      <span style="font-size:20px;"><?=date('F Y',strtotime($startdate));?></span>
    </div>
    
    <table class="legend-table">
      <tr>
        <th class="legend-header">INCIDENT</th>
        <th class="legend-header" width="5%">POINTS</th>
        <th class="legend-header" width="5%">CODE</th>
        <th class="legend-header">INCIDENT</th>
        <th class="legend-header" width="5%">POINTS</th>
        <th class="legend-header" width="5%">CODE</th>
        <th class="legend-header">INCIDENT</th>
        <th class="legend-header" width="5%">POINTS</th>
        <th class="legend-header" width="5%">CODE</th>
      </tr>
      <tr>
        <td>2 mins over-break (lunch)</td>
        <td align="center">0.2</td>
        <td align="center">L</td>
        <td>Forgot to clock in (first shift) and failed to submit form and Over-break</td>
        <td align="center">0.4</td>
        <td align="center">B-</td>
        <td>Forgot to clock in (first shift) and failed to submit form</td>
        <td align="center">0.2</td>
        <td align="center">I-</td>
      </tr>
      <tr>
        <td>Forgot to clock in/out (Lunch) w/ non-work related reason</td>
        <td align="center">0.2</td>
        <td align="center">L-</td>
        <td>Forgot to clock in (first shift) and failed to submit form and Missed Out/In (Lunch)</td>
        <td align="center">0.4</td>
        <td align="center">M</td>
        <td colspan="3"></td>
      </tr>
    </table>
  </div>
  
  <div class="controls">
    <div>
      <form action="attendancemonitoringsummarymissed.php" method="GET">
        <input type="hidden" name="dept" value="<?=$dept;?>">
        <input type="hidden" name="startdate" value="<?=$startdate;?>">
        <input type="hidden" name="enddate" value="<?=$enddate;?>">
        <input type="text" name="searchme" class="search-box" placeholder="Search employee...">
        <input type="submit" name="submit" value="Search" class="search-btn">
        <a href="attendancemonitoringsummarymissed.php?dept=<?=$dept;?>&startdate=<?=$startdate;?>&enddate=<?=$enddate;?>">
          <button type="button" class="search-btn">Refresh</button>
        </a>
      </form>
    </div>
    <div>
      <form>
        <input type="hidden" name="dept" value="<?=$dept;?>">
        <input type="hidden" name="startdate" value="<?=$startdate;?>">
        <input type="hidden" name="enddate" value="<?=$enddate;?>">
        <button onclick="tableToExcel('attendanceTable', 'Attendance_Summary_Missed_Report')" class="export-btn">EXPORT TO EXCEL</button>
      </form>
    </div>
  </div>
  
  <div class="attendance-table-container">
    <table class="attendance-table" id="attendanceTable">
      <thead>
        <tr>
          <th colspan="3" class="fixed-column">
            Employee Information
          </th>
          <?php
          $month=date('m',strtotime($startdate));
          $year=date('Y',strtotime($startdate));

          $datearray=date('d',strtotime($enddate));
          for($i=1;$i<=$datearray;$i++){
            ?>
            <th align="center" width="2%"><?=$i;?></th>
            <?php
          }
          ?>
          <th rowspan="2" width="1.5%">
            &nbsp;
          </th>
          <th width="1.5%" rowspan="2" class="summary-cell">
            M
          </th>
          <th width="1.5%" rowspan="2" class="summary-cell">
            L
          </th>
          <th width="1.5%" rowspan="2" class="summary-cell">
            B-
          </th>
          <th width="1.5%" rowspan="2" class="summary-cell">
            Total
          </th>
          <th width="1.5%" rowspan="2" class="summary-cell">
            Freq(-)
          </th>
          <th width="1.5%" rowspan="2" class="summary-cell">
            I-
          </th>
          <th width="1.5%" rowspan="2" class="summary-cell">
            L-
          </th>
        </tr>
        <tr>
          <th width="1%" class="fixed-column">No.</th>
          <th class="fixed-column-2">Employee Name</th>
          <th class="fixed-column-3">Department</th>
          <?php
          $month=date('m',strtotime($startdate));
          $year=date('Y',strtotime($startdate));

          $datearray=date('d',strtotime($enddate));
          for($i=1;$i<=$datearray;$i++){
            $rundate=$year."-".$month."-".$i;
            $day=date('D',strtotime($rundate));
            ?>
            <th align="center" width="1%"><?=$day;?></th>
            <?php
          }
          ?>
        </tr>
      </thead>
      <tbody>
        <?php
        $x = 1;
        mysqli_query($con, "SET NAMES 'utf8'");

        if (isset($_GET['submit'])) {
            $dept = $_GET['dept'];
            $startdate = $_GET['startdate'];
            $enddate = $_GET['enddate'];
            $searchme = $_GET['searchme'];
            $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.* FROM employee_profile ep 
                LEFT JOIN employee_details ed ON ed.idno = ep.idno 
                WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company = '$dept' AND (ep.lastname LIKE '%$searchme%' OR ep.firstname LIKE '%$searchme%') 
                ORDER BY ed.department ASC, ep.lastname ASC");
        } else {
            $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.* FROM employee_profile ep 
                LEFT JOIN employee_details ed ON ed.idno = ep.idno 
                WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company = '$dept' 
                ORDER BY ed.department ASC, ep.lastname ASC");
        }

        if (mysqli_num_rows($sqlEmployee) > 0) {
            while ($company = mysqli_fetch_array($sqlEmployee)) {
                $shift = date('h:i A', strtotime($company['startshift'])) . " - " . date('h:i A', strtotime($company['endshift']));
                $datehired = date('m/d/Y', strtotime($company['dateofhired']));

                $sqlDepartment = mysqli_query($con, "SELECT * FROM department WHERE id = '$company[department]'");
                if (mysqli_num_rows($sqlDepartment) > 0) {
                    $d = mysqli_fetch_array($sqlDepartment);
                    $department = $d['department'];
                } else {
                    $department = "";
                }

                echo "<tr>";
                echo "<td class='fixed-column'>$x.</td>";
                echo "<td class='fixed-column-2'>$company[lastname], $company[firstname] $company[middlename] $company[suffix]</td>";
                echo "<td class='fixed-column-3'>$department</td>";

                $month = date('m', strtotime($startdate));
                $year = date('Y', strtotime($startdate));
                $datearray = date('d', strtotime($enddate));

                $m = ""; $l = ""; $b = ""; $il = ""; $li = "";

                for ($i = 1; $i <= $datearray; $i++) {
                    $rundate = $year . "-" . $month . "-" . $i;
                    $day = date('D', strtotime($rundate));

                    $cellClass = ($day == "Sun") ? "weekend-cell" : "";

                    $sqlAttendance = mysqli_query($con, "SELECT * FROM points a LEFT JOIN attendance o ON a.idno = o.idno WHERE a.logindate='$rundate' AND a.idno='$company[idno]'");
                    if (mysqli_num_rows($sqlAttendance) > 0) {
                        while ($rem = mysqli_fetch_array($sqlAttendance)) {
                            $offense = $rem['offense'];
                        
                            $remarks1 = $rem['remarks1']; // Special codes
                            $incidentClass = "";

                            if ($offense == 19) {
                              $l++;
                              $remarks1 = str_replace('Code ', '', $remarks1) . "L";
                              $incidentClass = "incident-cell";
                              break;
                            }
                            elseif ($offense == 22) {
                              $il++;
                              $remarks1 = str_replace('Code ', '', $remarks1) . "I-";
                              $incidentClass = "incident-cell";
                              break;
                            }
                            elseif ($offense == 66) {
                              $m++;
                              $remarks1 = str_replace('Code ', '', $remarks1) . "M";
                              $incidentClass = "incident-cell";
                              break;
                            }
                            elseif ($offense == 65) {
                              $b++;
                              $remarks1 = str_replace('Code ', '', $remarks1) . "B-";
                              $incidentClass = "incident-cell";
                              break;
                            }
                            elseif ($offense == 63) {
                              $li++;
                              $remarks1 = str_replace('Code ', '', $remarks1) . "L-";
                              $incidentClass = "incident-cell";
                              break;
                            }
                        }
                    } else {
                        $remarks1 = "";
                        $incidentClass = "";
                    }

                    echo "<td align='center' class='$cellClass $incidentClass'>$remarks1</td>";
                }

                echo "<td></td>";
                echo "<td align='center' class='summary-cell'>$m</td>";
                echo "<td align='center' class='summary-cell'>$l</td>";
                echo "<td align='center' class='summary-cell'>$b</td>";
                echo "<td align='center' class='summary-cell'></td>";
                echo "<td align='center' class='summary-cell'></td>";
                echo "<td align='center' class='summary-cell'>$il</td>";
                echo "<td align='center' class='summary-cell'>$li</td>";
                echo "</tr>";

                $x++;
            }
        } else {
            echo "<tr><td colspan='9' align='center'>No record found!</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  function tableToExcel(tableID, filename = '') {
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML;

    // Create a download link
    var downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);

    // Set the file name
    filename = filename ? filename + '.xls' : 'attendance_summary.xls';

    // Create a Blob with the table HTML
    var blob = new Blob([tableHTML], {
        type: dataType
    });

    // Create a URL for the Blob
    var url = URL.createObjectURL(blob);
    downloadLink.href = url;
    downloadLink.download = filename;

    // Trigger the download
    downloadLink.click();

    // Clean up
    document.body.removeChild(downloadLink);
  }
</script>