<style>
  .table-scroll {
    position:relative;
    width:100%;
    margin:auto;
    overflow:hidden;
    border:1px solid #000;
    white-space: nowrap;
  }
  .table-wrap {
    width:100%;
    overflow:auto;
  }
  .table-scroll table {
    width:max-content;
    margin-top:auto;
    border-collapse:separate;
    border-spacing:0;
  }
  .table-scroll th, .table-scroll td {
    padding:5px 10px;
    border:1px solid #000;
    background:#fff;
    white-space:nowrap;
    vertical-align:top;
  }
  .table-scroll thead, .table-scroll tfoot {
    background:#f9f9f9;
  }
  .clone {
    position:absolute;
    top:0;
    left:0;
    pointer-events:none;
  }
  .clone th, .clone td {
    visibility:hidden
  }
  .clone td, .clone th {
    border-color:transparent
  }
  .clone tbody th {
    visibility:visible;
    color:red;
  }
  .clone .fixed-side {
    border:1px solid #000;
    background:#eee;
    visibility:visible;
  }
  .tooltip {
    position: relative;
    cursor: pointer;
}

.tooltip::after {
    content: attr(data-tooltip);
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    bottom: 100%;
    background: #333;
    color: #fff;
    padding: 5px;
    border-radius: 4px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s;
    z-index: 1000;
}

.tooltip:hover::after {
    opacity: 1;
    visibility: visible;
}
.clone thead, .clone tfoot{background:transparent;}
.btn {
    padding: 10px 15px;
    background-color: #13A14D;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn:hover {
    background-color: #06753a;
}
</style>

<script src="lib/jquery/jquery.min.js"></script>
<script>
  jQuery(document).ready(function() {
    jQuery(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');
  });

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

<?php
  include('../config.php');
  $dept=$_GET["dept"];
  $startdate=$_GET['startdate'];
  $enddate=$_GET['enddate'];
  $sqlCompany=mysqli_query($con,"SELECT companyname FROM settings WHERE companycode='$dept'");
  $comp=mysqli_fetch_array($sqlCompany);
?>
    <!-- Upper Table for Incident and Corresponding Points -->
    <p style="float:left;font-size:24px; text-align:center; font-weight:bold; margin-top:-1px; text-transform:uppercase; border:2px solid; width:20%; background:#ffcccc;">
      <?=$comp['companyname'];?><br /><font style="font-size:20px; font-weight:bold;"><?=date('F Y',strtotime($startdate));?></font></p>
      <table border="1" cellspacing="1" cellpadding="1" style="float:left; margin-left:100px; font-size:12px; font-weight:bold; margin-bottom:20px;" width="50%">
        <tr style="background-color:#ff7c80;">
          <td align="center">INCIDENT</td>
          <td align="center" width="6%">POINTS</td>
          <td align="center" width="5%">CODE</td>
          <td align="center">INCIDENT</td>
          <td align="center" width="5%">POINTS</td>
          <td align="center" width="6%">CODE</td>
        </tr>
        <tr>
          <td>Absent w/ proper CI, w/ Med Cert</td>
          <td align="center">No Points</td>
          <td align="center">As is</td>
          <td>Late w/in 15 minutes</td>
          <td align="center">0.2</td>
          <td align="center">TIME-D</td>
        </tr>
        <tr>
          <td>Absent w/ proper CI</td>
          <td align="center">0.2</td>
          <td align="center">CI-A</td>
          <td>Late 16 mins and up w/ CI</td>
          <td align="center">0.3</td>
          <td align="center">TIME-E</td>
        </tr>
        <tr>
          <td>Absent w/ proper CI but invalid reason</td>
          <td align="center">1.0</td>
          <td align="center">CI-B</td>
          <td>Late w/o CI (16mins and up)</td>
          <td align="center">0.5</td>
          <td align="center">TIME-F</td>
        </tr>
        <tr>
          <td>Absent w/ CI w/in 30mins prior shift & 15 mins after</td>
          <td align="center">0.5</td>
          <td align="center">CI-C</td>
          <td colspan="3"></td>
        </tr>
      </table>
      <br />
      <br />
      <br />
      <br />
      <br />
      <br />
      <br />
      <!-- Company Name and Date Display -->
			<div style="float:left;">
        <form action="attendancemonitoringsummary.php" method="GET">
          <input type="hidden" name="dept" value="<?=$dept;?>">
          <input type="hidden" name="startdate" value="<?=$startdate;?>">
          <input type="hidden" name="enddate" value="<?=$enddate;?>">
          <!-- Search Feature -->
          <input type="text" name="searchme"> <input type="submit" name="submit" value="Search"> <a href="attendancemonitoringsummary.php?dept=<?=$dept;?>&startdate=<?=$startdate;?>&enddate=<?=$enddate;?>"><button type="button">Refresh</button></a>
        </form>
		  </div>
      <!-- Export Button -->
      <div style="float:right; margin-bottom: 20px;">
        <form>
            <input type="hidden" name="dept" value="<?=$dept;?>">
            <input type="hidden" name="startdate" value="<?=$startdate;?>">
            <input type="hidden" name="enddate" value="<?=$enddate;?>">
            <button onclick="tableToExcel('attendanceTable', '<?= date('F', strtotime($startdate)); ?>_<?= str_replace(' ', '_', $comp['companyname']); ?>_Attendance_Summary_Report')" class="btn btn-success">EXPORT TO EXCEL</button>
        </form>
    </div>
      <br><br>
          <div id="table-scroll" class="table-scroll">
            <div class="table-wrap">
              <table class="main-table" id="attendanceTable" border="1">
                <thead>
                  <tr>
                    <th colspan="3" align="center" class="fixed-side">
                     Employee Information
                    </th>
                    <?php
                      $month=date('m',strtotime($startdate));
                      $year=date('Y',strtotime($startdate));

                      $datearray=date('d',strtotime($enddate));
                      for($i=1;$i<=$datearray;$i++){
                        ?>
                        <th align="center" width="1.5%"><?=$i;?></th>
                        <?php
                      } 
                    ?>
                    <th width="1%" style="border-top:0; border-bottom:0;">
                      &nbsp;
                    </th>
                    <th colspan="6">
                      SUMMARY
                    </th>
                    <th width="1%" style="border-top:0; border-bottom:0;">
                      &nbsp;
                    </th>
                    <th colspan="16">
                      TOTAL
                    </th>
                  </tr>
                  <tr>
                    <th width="1%" style="vertical-align:middle;" class="fixed-side">No.</th>
                      <th  style="vertical-align:middle;" width="7%" class="fixed-side">Employee Name</th>
                      <th  style="vertical-align:middle;" width="4%" class="fixed-side">Department</th>
                    <?php
                      $month=date('m',strtotime($startdate));
                      $year=date('Y',strtotime($startdate));

                      $datearray=date('d',strtotime($enddate));
                      for($i=1;$i<=$datearray;$i++){
                        $rundate=$year."-".$month."-".$i;
                        $day=date('D',strtotime($rundate));
                    ?>
                      <th align="center" width="1.5%"><?=$day;?></th>
                    <?php
                      }
                    ?>
                      <th width="1%" style="border-top:0; border-bottom:0;">
                        &nbsp;
                      </th>
                      <th width="1%">
                        A
                      </th>
                      <th width="1%">
                        B
                      </th>
                      <th width="1%">
                        C
                      </th>
                      <th width="1%">
                        D
                      </th>
                      <th width="1%">
                        E
                      </th>
                      <th width="1%">
                        F
                      </th>
                      <th width="1%" style="border-top:0; border-bottom:0;">
                        &nbsp;
                      </th>
                      <th width="1.5%" style="font-size:12px;">
                        P
                      </th>
                      <th width="1.5%" style="font-size:12px;">
                        CI
                      </th>
                      <th width="1.5%" style="font-size:12px;">
                        PTO
                      </th>
                      <th width="1.5%" style="font-size:12px;">
                        VL
                      </th>
                      <th width="1.5%" style="font-size:12px;">
                        SL
                      </th>
                      <th width="1.5%" style="font-size:12px;">
                        BLP
                      </th><th width="1.5%" style="font-size:12px;">
                        EO
                      </th>
                      <th width="1.5%" style="font-size:12px;">
                        SPL
                      </th>
                      <th width="1.5%" style="font-size:12px;">
                        LTL
                      </th>
                      <th width="1.5%" style="font-size:12px;">
                        MTL
                      </th>
                      <th width="1.5%" style="font-size:12px;">
                        PTL
                      </th>
                      <th width="1.5%" style="font-size:12px;">
                        SUS
                      </th>
                      <th width="1.5%" style="font-size:12px;">
                        AWOL
                      </th>
                      <th width="1.5%" style="font-size:12px;">
                        BL
                      </th>
                      <th width="1.5%" style="font-size:12px;">
                        DO
                      </th>
                      <th width="1.5%" style="font-size:12px;">
                        MDL
                      </th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    $x=1;
                    mysqli_query($con,"SET NAMES 'utf8'");
										if(isset($_GET['submit'])){
											$dept=$_GET['dept'];
											$startdate=$_GET['startdate'];
											$enddate=$_GET['enddate'];
											$searchme=$_GET['searchme'];
											$sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep 
                      LEFT JOIN employee_details ed ON ed.idno=ep.idno 
                      WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company='$dept' AND (ep.lastname LIKE '%$searchme%' OR ep.firstname LIKE '%$searchme%') 
                      ORDER BY ed.department ASC, ep.lastname ASC");
										}else{
											$sqlEmployee=mysqli_query($con,"SELECT ep.*,ed.* FROM employee_profile ep 
                      LEFT JOIN employee_details ed ON ed.idno=ep.idno WHERE ed.status NOT LIKE '%RESIGNED%' AND ed.company='$dept' 
                      ORDER BY ed.department ASC, ep.lastname ASC");
										}
                      if(mysqli_num_rows($sqlEmployee)>0){
                        while($company=mysqli_fetch_array($sqlEmployee)){
                          $shift=date('h:i A',strtotime($company['startshift']))." - ".date('h:i A',strtotime($company['endshift']));
                          $datehired=date('m/d/Y',strtotime($company['dateofhired']));
                          $sqlDepartment=mysqli_query($con,"SELECT * FROM department WHERE id='$company[department]'");
                          if(mysqli_num_rows($sqlDepartment)>0){
                            $d=mysqli_fetch_array($sqlDepartment);
                            $department=$d['department'];
                          }else{
                            $department="";
                          }
                          echo "<tr>";
                            echo "<td class='fixed-side'>$x.</td>";
                            echo "<td class='fixed-side'>$company[lastname], $company[firstname]</td>";
                            echo "<td align='center' class='fixed-side'>$department</td>";
                            $month=date('m',strtotime($startdate));
                            $year=date('Y',strtotime($startdate));

                            $datearray=date('d',strtotime($enddate));
                            $a="";$b="";$c="";$d="";$e="";$f="";$p="";$pto="";$vl="";$sl="";$blp="";$awol="";$mtl="";$mdl="";$ptl="";$ltl="";$bl="";$sus="";$eo="";$spl=""; $ci="";
                            for($i=1;$i<=$datearray;$i++){
                              $rundate=$year."-".$month."-".$i;
                              $day=date('D',strtotime($rundate));
                              if($day=="Sun"){
                                $nowork="background-color:gray;";
                              }else{
                                $nowork="";
                              }
                              // Fetch attendance record
                              $sqlAttendance = mysqli_query($con, " SELECT a.*, o.* FROM attendance a LEFT JOIN points o ON a.idno = o.idno  WHERE a.logindate = '$rundate' AND a.idno = '{$company['idno']}'");
                              if (mysqli_num_rows($sqlAttendance) > 0) {
                              while( $rem = mysqli_fetch_array($sqlAttendance))
                              {

                               
                                $loginam = $rem['loginam'];
                               $previousRemarks = $rem['previousRemarks']; // Retrieve saved remarks
                               $remarks = $rem['remarks'];                 // Current remarks
                               $offense = $rem['offense'];                 // Offense type
                               $color = "";                                // Default color
                               $newRemark = $remarks;                      // Start with current remark


                            
                               // Update remarks and color based on conditions
                               if ($remarks == "Code D" || strpos($remarks, "-D") !== false) {
                                 // If the remark is "Code D" or already in the format "Time - D"
                                 if ($remarks == "Code D") {
                                     $remarks = date('h:i', strtotime($rem['loginam'])) . "-D";
                                 }
                                 $p++;
                                 $color = "background-color:#ffcccc;";
                                 if ($offense == 15)
                                 {
                                  $remarks == "";
                                   $d++;
                                 }
                             }elseif ($remarks == "Code F"|| strpos($remarks, "-F") !== false) {
                              if ($remarks == "Code F") {
                                $remarks = date('h:i', strtotime($rem['loginam'])) . "-F";
                            }
                           
                            $color = "background-color:#ffcccc;";
                            if ($offense == 17)
                                {
                                 $remarks == "";
                                  $f++;
                                }
                                  
                              } 
                              elseif ($remarks == "Code E"|| strpos($remarks, "-E") !== false) {
                                  if ($remarks == "Code E") {
                                    $remarks = date('h:i', strtotime($rem['loginam'])) . "-E";
                                }
                                $p++;
                                $color = "background-color:#ffcccc;";
                                if ($offense == 16)
                                    {
                                     $remarks == "";
                                      $e++;
                                    }
                                      
                                  }
                                  elseif ($remarks == "Code B") {
                                    $remarks = "CI-B";
                                    $color = "background-color:#ffcccc;";
                                    $b++;
                               }  elseif ($remarks == "Code SL-A") {
                                $remarks = "SL-A";
                                $color = "background-color:#ffcccc;";
                                $sl++;
                           } 
                           elseif ($remarks == "Code SL-B") {
                            $remarks = "SL-B";
                            $color = "background-color:#ffcccc;";
                            $sl++;
                       } 
                       elseif ($remarks == "Code SL-C") {
                        $remarks = "SL-C";
                        $color = "background-color:#ffcccc;";
                        $sl++;
                   }
                               elseif ($remarks == "Code C") {
                                   $remarks = "CI-C";
                                   $color = "background-color:#ffcccc;";
                                   $c++;
                              } 
                              elseif ($remarks == "Code A") {
                                $remarks = "CI-A";
                                $color = "background-color:#ffcccc;";
                                $a++;
                           }  
                              elseif ($remarks == "Code L") {
                                $remarks = "P";
                                $color = "";   
                           } 
                           elseif ($remarks == "Code L-") {
                            $remarks = "P";
                            $color = "";  
                            } 
                            elseif ($remarks == "Code I-") {
                              $remarks = "P";
                              $color = "";  
                              }
                              elseif ($remarks == "Code M") {
                                $remarks = "P";
                                $color = "";  
                                }
                                elseif ($remarks == "Code B-") {
                                  $remarks = "P";
                                  $color = "";  
                                  }
                                  elseif ($remarks == "Code GS" || strpos($remarks, "GS") !== false) {
                                    if ($remarks == "Code GS") {
                                        if (empty($rem['loginam']) || $rem['loginam'] === '0') { 
                                            $remarks = "CI-GS"; // If loginam is 0 or empty, set to Absent-GS
                                        } else { 
                                            $remarks = date('h:i', strtotime($rem['loginam'])) . "-GS"; // If loginam has a value, format time
                                        }
                                    }
                                    $color = "background-color:#ffcccc;";
                                }
                                elseif ($remarks == "Code PcP" || strpos($remarks, "PcP") !== false) {
                                  if ($remarks == "Code PcP") {
                                      if (empty($rem['loginam']) || $rem['loginam'] === '0') { 
                                          $remarks = "CI-PcP"; // If loginam is 0 or empty, set to Absent-GS
                                      } else { 
                                          $remarks = date('h:i', strtotime($rem['loginam'])) . "-PcP"; // If loginam has a value, format time
                                      }
                                  }
                                  $color = "background-color:#ffcccc;";
                              }
                                elseif ($remarks == "Code IO" || strpos($remarks, "IO") !== false) {
                                  if ($remarks == "Code IO") {
                                      if (empty($rem['loginam']) || $rem['loginam'] === '0') { 
                                          $remarks = "CI-IO"; // If loginam is 0 or empty, set to Absent-GS
                                      } else { 
                                          $remarks = date('h:i', strtotime($rem['loginam'])) . "-IO"; // If loginam has a value, format time
                                      }
                                  }
                                  $color = "background-color:#ffcccc;";
                              }
                              elseif ($remarks == "Code PO" || strpos($remarks, "PO") !== false) {
                                if ($remarks == "Code PO") {
                                    if (empty($rem['loginam']) || $rem['loginam'] === '0') { 
                                        $remarks = "CI-PO"; // If loginam is 0 or empty, set to Absent-GS
                                    } else { 
                                        $remarks = date('h:i', strtotime($rem['loginam'])) . "-PO"; // If loginam has a value, format time
                                    }
                                }
                                $color = "background-color:#ffcccc;";
                            }
                                     elseif ($remarks == "Code TI" || strpos($remarks, "TI") !== false) {
                                    if ($remarks == "Code TI") {
                                        if (empty($rem['loginam']) || $rem['loginam'] === '0') { 
                                            $remarks = "CI-TI"; // If loginam is 0 or empty, set to Absent-GS
                                        } else { 
                                            $remarks = date('h:i', strtotime($rem['loginam'])) . "-TI"; // If loginam has a value, format time
                                        }
                                    }
                                    $color = "background-color:#ffcccc;";
                                } elseif ($remarks == "Code NC" || strpos($remarks, "NC") !== false) {
                                    if ($remarks == "Code NC") {
                                        if (empty($rem['loginam']) || $rem['loginam'] === '0') { 
                                            $remarks = "CI-NC"; // If loginam is 0 or empty, set to Absent-GS
                                        } else { 
                                            $remarks = date('h:i', strtotime($rem['loginam'])) . "-NC"; // If loginam has a value, format time
                                        }
                                    }
                                    $color = "background-color:#ffcccc;";
                                }
                                  
                              //  elseif (in_array($remarks, ["Code L-", "Code I-", "Code L, Code M, Code B-"])) {
                              //      $remarks = "P";
                              //      $color = ""; // No color for these remarks
                              //  } 
                              elseif (in_array($remarks, ["PTO", "VL", "SL", "BLP", "AWOL","AWOL2", "A", "SL-A", "MTL", "MDL", "PTL", "BL", "EO", "SPL", "EEO"])) {

                                   if ($remarks == "PTO") $pto++;
                                   elseif ($remarks == "VL") $vl++;
                                   elseif ($remarks == "SL") $sl++;
                                   elseif ($remarks == "BLP") $blp++;
                                   elseif ($remarks == "AWOL") $awol++;
                                   elseif ($remarks == "MTL") $mtl++;
                                   elseif ($remarks == "MDL") $mdl++;
                                   elseif ($remarks == "PTL") $ptl++;
                                   elseif ($remarks == "BL") $bl++;
                                   elseif ($remarks == "EO") $eo++;
                                   elseif ($remarks == "SPL") $spl++;
                                   elseif ($remarks == "AWOL2") $awol++;
                                   elseif ($remarks == "EEO") $eo++;
                           
                                   $color = "background-color:#bdd6ee;";
                               }
                               elseif (in_array($remarks, ["SUS", "CI", "AA", "OC",])) {
                                
                                if ($remarks == "SUS") $sus++;
                                elseif ($remarks == "CI") $ci++;
                                elseif ($remarks == "AA");
                                elseif ($remarks == "OC");
                        
                                $color = "background-color:#DEFDE0;";
                            }
                           
                               // Update `previousRemarks` if it's empty
                               if (empty($previousRemarks)) {
                                   mysqli_query($con, "UPDATE attendance 
                                                       SET previousRemarks='$remarks' 
                                                       WHERE logindate='$rundate' AND idno='{$company['idno']}'");
                                   $previousRemarks = $remarks;
                               }
                           
                               // Update `remarks` if it has changed
                               if ($remarks !== $rem['remarks']) {
                                   mysqli_query($con, "UPDATE attendance 
                                                       SET remarks='$remarks' 
                                                       WHERE logindate='$rundate' AND idno='{$company['idno']}'");
                               }
                              }
                           } else {
                               $remarks = "";
                               $previousRemarks = "";
                               $color = "";
                           }
                          
                          // Increment "P" if necessary
                          if ($remarks === "P") {
                              $p++;
                          }
                   /// Fetch attendance for the specific date and employee
                   $sqlAttendance = mysqli_query($con, "SELECT * FROM attendance WHERE logindate='$rundate' AND idno='{$company['idno']}'");

                   if (mysqli_num_rows($sqlAttendance) > 0) {
                       $attendance = mysqli_fetch_assoc($sqlAttendance);
                       $remark = $attendance['remarks'];        // Current remark in attendance (VL, SL, etc.)
                       $loginam = $attendance['loginam'];
                       $logoutam = $attendance['logoutam'];
                       $loginpm = $attendance['loginpm'];
                       $logoutpm = $attendance['logoutpm'];

                       // Check if the initial remark is a leave type and any login/logout field is filled
                       if (in_array($remark, ['VL', 'SL', 'PTO', 'EO', 'BLP', 'SPL', 'SL-A', 'SL-NC', 'SL-PO', 'SL-IO', 'SL-GS']) && 
                           ($loginam != '0' || $logoutam != '0' || $loginpm != '0' || $logoutpm != '0
                           ')) {
                             $sqlUpdateRemarks = mysqli_query($con, " UPDATE attendance SET remarks = 'P', status = 'nd/work' WHERE logindate = '$rundate' AND idno = '{$company['idno']}'");

                           // Restore the leave credits based on the leave type
                           if ($remark == "VL") {
                               $sqlUpdateCredits = mysqli_query($con, "
                                   UPDATE leave_credits 
                                   SET vlused = vlused - 1 
                                   WHERE idno = '{$company['idno']}'");
                           } elseif ($remark == "SL" || $remark == "SL-A" || $remark == "SL-IO" || $remark == "SL-NC" || $remark == "SL-PO" || $remark == "SL-GS") {
                               $sqlUpdateCredits = mysqli_query($con, "
                                   UPDATE leave_credits 
                                   SET slused = slused - 1 
                                   WHERE idno = '{$company['idno']}'");
                           }elseif ($remark == "PTO") {
                             $sqlUpdateCredits = mysqli_query($con, "
                                 UPDATE leave_credits 
                                 SET ptoused = ptoused - 1 
                                 WHERE idno = '{$company['idno']}'");
                           }elseif ($remark == "EO") {
                             $sqlUpdateCredits = mysqli_query($con, "
                                 UPDATE leave_credits 
                                 SET eo_used = eo_used - 1 
                                 WHERE idno = '{$company['idno']}'");
                           }elseif ($remark == "BLP") {
                             $sqlUpdateCredits = mysqli_query($con, "
                                 UPDATE leave_credits 
                                 SET blp_used = blp_used - 1 
                                 WHERE idno = '{$company['idno']}'");
                           }elseif ($remark == "SPL") {
                             $sqlUpdateCredits = mysqli_query($con, "
                                 UPDATE leave_credits 
                                 SET spl_used = spl_used - 1 
                                 WHERE idno = '{$company['idno']}'");
                           }
                          
                       }
                   }
                   ?>
                  <td align="center" 
                    class="tooltip" 
                    style="font-size:10px; font-weight:bold; <?= $nowork; ?> <?= $color; ?>" 
                    data-tooltip="<?= htmlspecialchars($previousRemarks, ENT_QUOTES, 'UTF-8'); ?>">
                    <?= htmlspecialchars($remarks, ENT_QUOTES, 'UTF-8'); ?>
                  </td>

                  <?php
                      }
                          echo "<td style='border-top:0; border-bottom:0;'></td>";
                          echo "<td align='center'>$a</td>";
                          echo "<td align='center'>$b</td>";
                          echo "<td align='center'>$c</td>";
                          echo "<td align='center'>$d</td>";
                          echo "<td align='center'>$e</td>";
                          echo "<td align='center'>$f</td>";
                          echo "<td style='border-top:0; border-bottom:0;'></td>";
                          echo "<td align='center'>$p</td>";
                          echo "<td align='center'>$ci</td>";
                          echo "<td align='center'>$pto</td>";
                          echo "<td align='center'>$vl</td>";
                          echo "<td align='center'>$sl</td>";
                          echo "<td align='center'>$blp</td>";
                          echo "<td align='center'>$eo</td>";
                          echo "<td align='center'>$spl</td>";
                          echo "<td align='center'>$ltl</td>";
                          echo "<td align='center'>$mtl</td>";
                          echo "<td align='center'>$ptl</td>";
                          echo "<td align='center'>$sus</td>";
                          echo "<td align='center'>$awol</td>";
                          echo "<td align='center'>$bl</td>";
                          echo "<td align='center'></td>";
                          echo "<td align='center'>$mdl</td>";
                        echo "</tr>";
                        $x++;
                      }
                    }else{
                      echo "<tr><td colspan='9' align='center'>No record found!</td></tr>";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
