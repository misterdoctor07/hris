<?php
// Fetch unique companies from the employee_details table
$sqlCompanies = mysqli_query($con, "SELECT DISTINCT company FROM employee_details ORDER BY company");

if (!$sqlCompanies) {
    echo "Query error: " . mysqli_error($con);
    exit;
}
$pointsRanges = [
    ['min' => 1.0, 'max' => 1.9, 'action' => 'Verbal Warning', 'requires_nte' => false],
    ['min' => 2.0, 'max' => 2.9, 'action' => 'Written Warning', 'requires_nte' => false],
    ['min' => 3.0, 'max' => 3.9, 'action' => 'Notice to Explain', 'requires_nte' => false],
    ['min' => 4.0, 'max' => 4.9, 'action' => 'Suspension 1 Day', 'requires_nte' => true],
    ['min' => 5.0, 'max' => 5.9, 'action' => 'Suspension 2 Days', 'requires_nte' => true],
    ['min' => 6.0, 'max' => 6.9, 'action' => 'Suspension 3 Days', 'requires_nte' => true],
    ['min' => 7.0, 'max' => 7.9, 'action' => 'Suspension 4 Days', 'requires_nte' => true],
    ['min' => 8.0, 'max' => 8.9, 'action' => 'Suspension 5 Days', 'requires_nte' => true],
    ['min' => 9.0, 'max' => 9.9, 'action' => 'Suspension 6 Days', 'requires_nte' => true],
    ['min' => 10.0, 'max' => PHP_FLOAT_MAX, 'action' => 'Termination', 'requires_nte' => false]
];
function getFormattedRemarks($rem) {
    // Initialize variables with default values to prevent undefined index warnings
    $loginam = $rem['loginam'] ?? '';
    $previousRemarks = $rem['previousRemarks'] ?? '';
    $remarks = $rem['remarks'] ?? '';
    $offense = $rem['offense'] ?? '';
    $color = "";
    $newRemark = $remarks;

  if ($remarks == "Code D" || strpos($remarks, "-D") !== false) {
    if ($remarks == "Code D") {
        $remarks = (!empty($loginam) && $loginam !== '0') ? 
                  date('h:i', strtotime($loginam)) . "-D" : 
                  "CI-D";  // Changed from just "-D" to "CI-D"
    }
    $color = "background-color:#ffcccc;";
    // Removed the empty remarks for offense 15 to always show it
} elseif ($remarks == "Code F" || strpos($remarks, "-F") !== false) {
    if ($remarks == "Code F") {
        $remarks = (!empty($loginam) && $loginam !== '0') ? 
                  date('h:i', strtotime($loginam)) . "-F" : 
                  "CI-F";  // Changed from just "-F" to "CI-F"
    }
    $color = "background-color:#ffcccc;";
    // Removed the empty remarks for offense 17 to always show it
} elseif ($remarks == "Code E" || strpos($remarks, "-E") !== false) {
    if ($remarks == "Code E") {
        $remarks = (!empty($loginam) && $loginam !== '0') ? 
                  date('h:i', strtotime($loginam)) . "-E" : 
                  "CI-E";  // Changed from just "-E" to "CI-E"
    }
    $color = "background-color:#ffcccc;";
    // Removed the empty remarks for offense 16 to always show it
}
     elseif ($remarks == "Code B") {
        $remarks = "CI-B";
        $color = "background-color:#ffcccc;";
    } elseif ($remarks == "Code SL-A") {
        $remarks = "SL-A";
        $color = "background-color:#ffcccc;";
    } elseif ($remarks == "Code SL-B") {
        $remarks = "SL-B";
        $color = "background-color:#ffcccc;";
    } elseif ($remarks == "Code SL-C") {
        $remarks = "SL-C";
        $color = "background-color:#ffcccc;";
    } elseif ($remarks == "Code C") {
        $remarks = "CI-C";
        $color = "background-color:#ffcccc;";
    } elseif ($remarks == "Code A") {
        $remarks = "CI-A";
        $color = "background-color:#ffcccc;";
    } elseif ($remarks == "Code SD") {
        $remarks = "SD";
        $color = "background-color:#ffcccc;";
    } elseif ($remarks == "Code GS" || strpos($remarks, "GS") !== false) {
        if ($remarks == "Code GS") {
            $remarks = (empty($loginam) || $loginam === '0') ? "CI-GS" : date('h:i', strtotime($loginam)) . "-GS";
        }
        $color = "background-color:#ffcccc;";
    } elseif ($remarks == "Code RD" || strpos($remarks, "RD") !== false) {
        if ($remarks == "Code RD") {
            $remarks = (empty($loginam) || $loginam === '0') ? "CI-RD" : date('h:i', strtotime($loginam)) . "-RD";
        }
        $color = "background-color:#ffcccc;";
    } elseif ($remarks == "Code PcP" || strpos($remarks, "PcP") !== false) {
        if ($remarks == "Code PcP") {
            $remarks = (empty($loginam) || $loginam === '0') ? "CI-PcP" : date('h:i', strtotime($loginam)) . "-PcP";
        }
        $color = "background-color:#ffcccc;";
    } elseif ($remarks == "Code IO" || strpos($remarks, "IO") !== false) {
        if ($remarks == "Code IO") {
            $remarks = (empty($loginam) || $loginam === '0') ? "CI-IO" : date('h:i', strtotime($loginam)) . "-IO";
        }
        $color = "background-color:#ffcccc;";
    } elseif ($remarks == "Code PO" || strpos($remarks, "PO") !== false) {
        if ($remarks == "Code PO") {
            $remarks = (empty($loginam) || $loginam === '0') ? "CI-PO" : date('h:i', strtotime($loginam)) . "-PO";
        }
        $color = "background-color:#ffcccc;";
    } elseif ($remarks == "Code TI" || strpos($remarks, "TI") !== false) {
        if ($remarks == "Code TI") {
            $remarks = (empty($loginam) || $loginam === '0') ? "CI-TI" : date('h:i', strtotime($loginam)) . "-TI";
        }
        $color = "background-color:#ffcccc;";
    } elseif ($remarks == "Code NC" || strpos($remarks, "NC") !== false) {
        if ($remarks == "Code NC") {
            $remarks = (empty($loginam) || $loginam === '0') ? "CI-NC" : date('h:i', strtotime($loginam)) . "-NC";
        }
        $color = "background-color:#ffcccc;";
    }

    return [
        'remarks' => $remarks,
        'color' => $color
    ];
}

// Function to get formatted remarks1
function getFormattedRemarks1($rem) {
    $offense = $rem['offense'] ?? '';
    $remarks1 = $rem['remarks1'] ?? '';
    $color = "";
    
    // Helper function to add time details
    $addTimeDetails = function($remarks, $rem) {
        $timeDetails = [];
        if (!empty($rem['loginpm']) && $rem['loginpm'] !== '0') {
            $timeDetails[] = "LoginPM: " . date('h:i A', strtotime($rem['loginpm']));
        }
        if (!empty($rem['logoutam']) && $rem['logoutam'] !== '0') {
            $timeDetails[] = "LogoutAM: " . date('h:i A', strtotime($rem['logoutam']));
        }
        if (!empty($timeDetails)) {
            $remarks .= " (" . implode(", ", $timeDetails) . ")";
        }
        return $remarks;
    };

    if ($offense == 19) {
        $remarks1 = $addTimeDetails(str_replace('Code ', '', $remarks1) . "L", $rem);
        $color = "background-color:#f4c7c3;";
    } elseif ($offense == 22) {
        $remarks1 = $addTimeDetails(str_replace('Code ', '', $remarks1) . "I-", $rem);
        $color = "background-color:#f4c7c3;";
    } elseif ($offense == 66) {
        $remarks1 = $addTimeDetails(str_replace('Code ', '', $remarks1) . "M", $rem);
        $color = "background-color:#f4c7c3;";
    } elseif ($offense == 65) {
        $remarks1 = $addTimeDetails(str_replace('Code ', '', $remarks1) . "B-", $rem);
        $color = "background-color:#f4c7c3;";
    } elseif ($offense == 63) {
        $remarks1 = $addTimeDetails(str_replace('Code ', '', $remarks1) . "L-", $rem);
        $color = "background-color:#f4c7c3;";
    }

    return [
        'remarks1' => $remarks1,
        'color' => $color
    ];
}
?>

<div class="col-lg-12">
    <div class="content-panel">
        <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0;">
                <a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> |
                <i class="fa fa-user"></i> EMPLOYEE LIST
            </h4>
            <div>
                <form>
                    <button type="button" onclick="tablesToExcel('Points_Report')" class="btn btn-success">EXPORT TO EXCEL</button>
                </form>
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

                $sqlDepartments = mysqli_query($con, "SELECT DISTINCT d.department FROM employee_details ed
                    INNER JOIN department d ON d.id = ed.department
                    WHERE ed.company = '$companyCode' 
                    AND ed.status NOT LIKE '%RESIGNED%'
                    ORDER BY d.department");

                if (!$sqlDepartments) {
                    echo "Error fetching departments: " . mysqli_error($con);
                    continue;
                }

                echo "<ul class='nav nav-pills' style='margin-top: 10px;'>";
                $deptActive = 'active';
                while ($department = mysqli_fetch_array($sqlDepartments)) {
                    $departmentName = htmlspecialchars($department['department']);
                    $deptId = preg_replace('/[^A-Za-z0-9\-]/', '', $departmentName);
                    echo "<li class='$deptActive'><a data-toggle='pill' href='#dept-$sanitizedId-$deptId'>$departmentName</a></li>";
                    $deptActive = '';
                }
                echo "</ul>";

                echo "<div class='tab-content' style='margin-top: 10px;'>";
                mysqli_data_seek($sqlDepartments, 0);
                $deptActive = 'in active';
                while ($department = mysqli_fetch_array($sqlDepartments)) {
                    $departmentName = htmlspecialchars($department['department']);
                    $deptId = preg_replace('/[^A-Za-z0-9\-]/', '', $departmentName);
                    echo "<div id='dept-$sanitizedId-$deptId' class='tab-pane fade $deptActive'>";

                    $sqlEmployee = mysqli_query($con, "SELECT ep.*, ed.*
                        FROM employee_profile ep
                        INNER JOIN employee_details ed ON ed.idno = ep.idno
                        INNER JOIN department d ON d.id = ed.department
                        WHERE ed.company = '$companyCode' AND d.department = '$departmentName' 
                        AND ed.status NOT LIKE '%RESIGNED%'
                        ORDER BY ep.lastname ASC");

                    if (!$sqlEmployee) {
                        echo "Error fetching employees: " . mysqli_error($con);
                        continue;
                    }

                    echo "<table class='table table-bordered table-striped table-condensed'>
                        <thead>
                            <tr>
                                <th style='vertical-align: middle; text-align: center;'>No.</th>
                                <th style='vertical-align: middle; text-align: center;'>Emp ID</th>
                                <th style='vertical-align: middle; text-align: center;'>Employee Name</th>
                                <th style='vertical-align: middle; text-align: center;'>Current Points</th>";
                    
                    // Add headers for each range
                    foreach ($pointsRanges as $range) {
                        $rangeLabel = $range['min'] . ($range['max'] == PHP_FLOAT_MAX ? '+' : ' - ' . $range['max']);
                        echo "<th style='vertical-align: middle; text-align: center;'>$rangeLabel<br><small>{$range['action']}</small></th>";
                    }
                    
                    echo "</tr>
                        </thead>
                        <tbody>";

                    $x = 1;
                    if(mysqli_num_rows($sqlEmployee) > 0) {
                    while($employee = mysqli_fetch_array($sqlEmployee)) {
                            $idno = $employee['idno'];
                            
                            // Calculate points for current employee
                            $currentYear = date('Y');
                            $sqlPoints = mysqli_query($con, "
                                SELECT SUM(points) as total_points 
                                FROM points 
                                WHERE idno='$idno' AND YEAR(logindate) = '$currentYear'
                            ");
                            
                            $points = 0;
                            if (mysqli_num_rows($sqlPoints) > 0) {
                                $point = mysqli_fetch_array($sqlPoints);
                                $points = (float)$point['total_points'] ?? 0;
                            }
                            
                            $formattedPoints = number_format($points, 1, '.', '');
                            $translations = [
                                "12" => "Absent with proper called-in",
                                "13" => "Absent with proper called-in, Invalid reason",
                                "65" => "Forgot to clock in (first shift) and failed to submit form and Over-break",
                                "15" => "Late within 15 minutes",
                                "16" => "Late 16 minutes and up with called-in",
                                "17" => "Late 16 minutes and up without called-in",
                                "22" => "Forgot to clock in (first shift) and failed to submit form",
                                "19" => "Over-Break (lunch)",
                                "63" => "Forgot to clock in/out (Lunch) w/ non-work related reason",
                                "62" => "Absent w/ CI w/in 30mins prior shift & 15 mins after",
                                "66" => "Forgot to clock in (first shift) and failed to submit form and Missed Out/In (Lunch)",
                                "78" => "Sick leave w/o supporting documents",
                                "72" => "Government Sanction",
                                "67" => "Internet Outage",
                                "68" => "Natural Calamity",
                                "77" => "PC Problem",
                                "64" => "Power Outage",
                                "73" => "Transportation Issue",
                                "79" => "SL with proper CI, invalid reason",
                                "83" => "Server Down/HRIS unreachable",
                                "80" => "SL w/ CI w/in 30 mins prior shift & 15 mins",
                                "84" => "Required Duty"
                                
                            ];
                            $sqlPointsBreakdown = mysqli_query($con, "
                                SELECT p.offense, p.points, p.logindate, a.remarks, a.remarks1, a.loginam, a.previousRemarks 
                                FROM points p
                                LEFT JOIN attendance a ON p.idno = a.idno AND p.logindate = a.logindate
                                WHERE p.idno='$idno' AND YEAR(p.logindate) = '$currentYear'
                                ORDER BY p.logindate ASC
                            ");
                        
                            // Initialize breakdown_html with default value
                            $breakdown_html = "<ul style='margin: 0; padding: 0; list-style: none;'><li>No points recorded for $currentYear.</li></ul>";
                        
                            if ($sqlPointsBreakdown && mysqli_num_rows($sqlPointsBreakdown) > 0) {
                                $breakdown_by_month = [];
                                
                                while ($row = mysqli_fetch_assoc($sqlPointsBreakdown)) {
                                    $translated_offense = isset($translations[$row['offense']]) ? $translations[$row['offense']] : $row['offense'];
                                    $points_value = (float)$row['points'];
                                    
                                    if ($points_value > 0) {
                                        $month = date("F", strtotime($row['logindate']));
                                        $formattedRemarks = getFormattedRemarks($row);
                                        $formattedRemarks1 = getFormattedRemarks1($row);
                        
                                        $breakdown_by_month[$month][] = [
                                            'offense' => $translated_offense,
                                            'points' => $points_value,
                                            'logindate' => $row['logindate'],
                                            'remarks' => $formattedRemarks['remarks'],
                                            'remarks1' => $formattedRemarks1['remarks1'],
                                            'remarks_color' => $formattedRemarks['color'],
                                            'remarks1_color' => $formattedRemarks1['color']
                                        ];
                                    }
                                }
                        
                                if (!empty($breakdown_by_month)) {
                                    $breakdown_html = "<ul style='margin: 0; padding: 0; list-style: none;'>";
                                    foreach ($breakdown_by_month as $month => $offenses) {
                                        $breakdown_html .= "<li><strong>$month</strong><ul>";
                                        foreach ($offenses as $offense) {
                                            $formatted_date = date("M-d-Y", strtotime($offense['logindate']));
                                            $breakdown_html .= "<li>" . number_format($offense['points'], 1, '.', '') . " : " . 
                                                htmlspecialchars($offense['offense']) . " (Date: " . $formatted_date . ")" .
                                                ($offense['remarks'] ? "<br>Remarks: <span style='{$offense['remarks_color']}'>{$offense['remarks']}</span>" : "") .
                                                ($offense['remarks1'] ? "<br>Remarks1: <span style='{$offense['remarks1_color']}'>{$offense['remarks1']}</span>" : "") .
                                                "</li>";
                                        }
                                        $breakdown_html .= "</ul></li>";
                                    }
                                    $breakdown_html .= "</ul>";
                                }
                            }
                            
                           $rangeFirstReached = [];
                                foreach ($pointsRanges as $range) {
                                    $sqlFirstReached = mysqli_query($con, "
                                    SELECT logindate as first_reached, 
                                           (SELECT SUM(points) FROM points p2 
                                            WHERE p2.idno = '$idno' 
                                            AND YEAR(p2.logindate) = '$currentYear'
                                            AND p2.logindate <= p1.logindate) as points_at_date
                                    FROM points p1
                                    WHERE idno='$idno' 
                                    AND YEAR(logindate) = '$currentYear'
                                    HAVING points_at_date >= {$range['min']}
                                    ORDER BY logindate ASC
                                    LIMIT 1
                                ");
                                    
                                    if (mysqli_num_rows($sqlFirstReached) > 0) {
                                        $reached = mysqli_fetch_assoc($sqlFirstReached);
                                        $rangeFirstReached[$range['min']] = [
                                            'date' => $reached['first_reached'],
                                            'points' => $reached['points_at_date']
                                        ];
                                    }
                                }
                                
                               $sqlServedInfractions = mysqli_query($con, "
                                SELECT points_min, points_max, dateserved, points_served, typeofmemo
                                FROM infraction 
                                WHERE idno='$idno' 
                                AND status != 'pending'
                                ORDER BY dateserved
                            ");
                            
                            // Check if query failed
                            if ($sqlServedInfractions === false) {
                                echo "Error in infraction query: " . mysqli_error($con);
                                $servedInfractions = []; // Set empty array to prevent errors
                            } else {
                                $servedInfractions = [];
                                while ($infraction = mysqli_fetch_assoc($sqlServedInfractions)) {
                                    $servedInfractions[] = $infraction;
                                }
                            }
                            
                                
                                echo "<tr>";
                                echo "<td style='text-align: center; vertical-align: middle;'>$x.</td>";
                                echo "<td style='text-align: center; vertical-align: middle;'>$employee[idno]</td>";
                                echo "<td style='vertical-align: middle;'><strong>$employee[lastname]</strong>, $employee[firstname] $employee[middlename] $employee[suffix]</td>";
                                echo "<td style='text-align: center; vertical-align: middle;' class='points-info' data-breakdown='".htmlspecialchars($breakdown_html, ENT_QUOTES)."'>$formattedPoints</td>";
                                
                                // Display each range status - UPDATED LOGIC
                               // Display each range status - UPDATED LOGIC
                            // Display each range status - UPDATED LOGIC
                                foreach ($pointsRanges as $range) {
                                    $rangeMin = $range['min'];
                                    $rangeMax = $range['max'];
                                    $requiresNTE = ($rangeMin >= 4.0 && $rangeMin < 10.0); // Only 4.0-9.9 need NTE first
                                    $rangeClass = '';
                                    $rangeStatus = '';
                                    $serveButton = '';
                                    $serveButtonText = 'Serve';
                                
                                    // Check if points have reached this range
                                    $inRange = ($points >= $rangeMin);
                                
                                    // Check if this range has been served (any infraction that covers this range)
                                    $servedInfo = null;
                                    $nteServed = false;
                                    $suspensionServed = false;
                                    
                                    foreach ($servedInfractions as $infraction) {
                                        if ($infraction['points_min'] <= $rangeMax && $infraction['points_max'] >= $rangeMin) {
                                            $servedInfo = $infraction;
                                            
                                            // For ranges 1.0-3.9, ANY served infraction means it's fully served
                                            if (!$requiresNTE) {
                                                $rangeClass = 'range-served';
                                                $rangeStatus = "<span class='served-indicator'>Served: " . date('M d, Y', strtotime($infraction['dateserved'])) . "<br>Points when served: " . number_format($infraction['points_served'], 1) . "</span>";
                                                break; // No need to check further
                                            }
                                            
                                            // For ranges 4.0-9.9, check if NTE or Suspension was served
                                            if ($infraction['typeofmemo'] == 'Notice to Explain') {
                                                $nteServed = true;
                                            } elseif (strpos($infraction['typeofmemo'], 'Notice of Suspension') !== false || $infraction['typeofmemo'] == 'Notice of Termination') {
                                                $suspensionServed = true;
                                            }
                                        }
                                    }
                                
                                    // If range is 1.0-3.9 and already served, skip further checks
                                    if ($servedInfo && !$requiresNTE) {
                                        echo "<td class='$rangeClass'>$rangeStatus</td>";
                                        continue;
                                    }
                                
                                    // For ranges 4.0+ that require NTE
                                    if ($suspensionServed) {
                                        // Fully served (both NTE and suspension)
                                        $rangeClass = 'range-served';
                                        $firstReachedInfo = $rangeFirstReached[$rangeMin] ?? null;
                                        $reachedDate = $firstReachedInfo ? date('M d, Y', strtotime($firstReachedInfo['date'])) : 'N/A';
                                        $servedPoints = number_format($servedInfo['points_served'], 1);
                                        $rangeStatus = "<span class='served-indicator'>Served: " . date('M d, Y', strtotime($servedInfo['dateserved'])) . "<br>Points when served: $servedPoints<br>Reached: $reachedDate</span>";
                                    } elseif ($nteServed && $requiresNTE) {
                                        // NTE served but suspension pending
                                        $rangeClass = 'range-nte-served';
                                        $firstReachedInfo = $rangeFirstReached[$rangeMin] ?? null;
                                        $reachedDate = $firstReachedInfo ? date('M d, Y', strtotime($firstReachedInfo['date'])) : 'N/A';
                                        $rangeStatus = "<span class='nte-served-indicator'>NTE Served<br>Pending Suspension</span>";
                                        $serveButtonText = 'Serve Suspension';
                                    } elseif ($inRange) {
                                        // Range reached but nothing served yet
                                        $rangeClass = 'range-active';
                                        $firstReachedInfo = $rangeFirstReached[$rangeMin] ?? null;
                                        $reachedDate = $firstReachedInfo ? date('M d, Y', strtotime($firstReachedInfo['date'])) : 'N/A';
                                        $reachedPoints = $firstReachedInfo ? number_format($firstReachedInfo['points'], 1) : 'N/A';
                                        
                                        if ($requiresNTE) {
                                            $rangeStatus = "<span class='pending-indicator'>Reached: $reachedDate<br>Points: $reachedPoints<br>Requires NTE First</span>";
                                            $serveButtonText = 'Serve NTE';
                                        } else {
                                            $rangeStatus = "<span class='pending-indicator'>Reached: $reachedDate<br>Points: $reachedPoints</span>";
                                        }
                                    }
                                    
                                    // Only show serve button if in range and not fully served
                                    if ($inRange && !($servedInfo && !$requiresNTE) && !$suspensionServed) {
                                        $serveButton = "<button class='btn btn-warning btn-xs serve-btn' 
                                                      data-idno='$idno' 
                                                      data-action='{$range['action']}' 
                                                      data-points='{$range['min']}-{$range['max']}'
                                                      data-range-min='{$range['min']}'
                                                      data-range-max='{$range['max']}'
                                                      data-requires-nte='".($requiresNTE ? 'true' : 'false')."'
                                                      data-nte-served='".($nteServed ? 'true' : 'false')."'
                                                      data-actual-points='$points'>$serveButtonText</button>";
                                    }
                                    
                                    echo "<td class='$rangeClass'>";
                                    echo $rangeStatus;
                                    echo $serveButton;
                                    echo "</td>";
                                }
                                                            
                                     echo "</tr>";
                                     $x++;
                            }
                            } else {
                                echo "<tr><td colspan='".(4 + count($pointsRanges))."' align='center'>No records found!</td></tr>";
                            }
            
                            echo "</tbody></table></div>";
                            $deptActive = '';
                        }
            
                        echo "</div></div>";
                        $active = '';
                    }
               ?>
    </div>
</div>
</div>
<!-- Modal for serving disciplinary action -->
<div class="modal fade" id="serveModal" tabindex="-1" role="dialog" aria-labelledby="serveModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="serveModalLabel">Serve Disciplinary Action</h4>
            </div>
            <form action="?addinfraction" method="GET" class="form-horizontal">
                <input type="hidden" name="addinfraction">
                <div class="modal-body">
                    <input type="hidden" id="serveIdno" name="idno">
                    <input type="hidden" id="serveRangeMin" name="range_min">
                    <input type="hidden" id="serveRangeMax" name="range_max">
                    <input type="hidden" name="typecat" value="Category A">
                    <input type="hidden" name="addedby" value="<?=$fullname;?>">
                    <input type="hidden" name="typeofoffense" value="Category A">
                    <input type="hidden" name="points_served" id="servePointsServed">

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Employee ID:</label>
                        <div class="col-sm-8">
                            <p class="form-control-static" id="serveIdnoDisplay"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Employee Name:</label>
                        <div class="col-sm-8">
                            <p class="form-control-static" id="serveNameDisplay"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Points When Reached:</label>
                        <div class="col-sm-8">
                            <p class="form-control-static" id="servePointsDisplay"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Current Points:</label>
                        <div class="col-sm-8">
                            <p class="form-control-static" id="serveCurrentPointsDisplay"></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Action to Serve:</label>
                        <div class="col-sm-8">
                            <p class="form-control-static" id="serveActionDisplay"></p>
                            <input type="hidden" id="serveAction" name="memotype">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="serveDateIssued">Date Issued:</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="serveDateIssued" name="dateissued"  value="<?=date('Y-m-d');?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="serveDateServed">Date Served:</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="serveDateServed" name="dateserved" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="serveDateIncident">Date of Incident:</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="serveDateIncident" name="dateofincident" value="<?=date('Y-m-d');?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="servePoints">Points:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="servePoints" name="points" required readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="serveSuspension">Suspension Dates:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="serveSuspension" name="dateofsuspension" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="submit" class="btn btn-primary">Confirm & Submit Infraction</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="pointsContextMenu" style="display:none; position:absolute; background:white; border:1px solid #ccc; padding:10px; z-index:1000; box-shadow:0 0 10px rgba(0,0,0,0.1);">
    <h4 style="margin-top:0;">Points Breakdown for <?php echo $currentYear; ?></h4>
    <div id="pointsBreakdownContent"></div>
</div>

<style>
.served-indicator {
    color: #28a745;
    font-size: 0.8em;
    font-style: italic;
    display: block;
}
.pending-indicator {
    color: #dc3545;
    font-size: 0.8em;
    font-style: italic;
    display: block;
}
.range-served {
    background-color: #e8f5e9; /* Light green for served ranges */
}
.range-active {
    background-color: #ffebee; /* Light red for active ranges */
}
.points-info {
    font-weight: bold;
}
.serve-btn {
    padding: 3px 8px;
    font-size: 12px;
    margin-top: 5px;
}
.range-nte-served {
    background-color: #fff3e0; /* Light orange for NTE served but suspension pending */
}
.nte-served-indicator {
    color: #ff9800;
    font-size: 0.8em;
    font-style: italic;
    display: block;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
// Make sure jQuery is loaded first
if (typeof jQuery == 'undefined') {
    console.error('jQuery is not loaded!');
} else {
    console.log('jQuery version:', jQuery.fn.jquery);
}

$(document).ready(function() {
    console.log('Document ready - setting up event handlers');
    
    // Improved event delegation for serve buttons
    $(document).on('click', '.serve-btn', function(e) {
        console.log('Serve button clicked');
        e.preventDefault();
        
        var idno = $(this).data('idno');
        var action = $(this).data('action');
        var pointsRange = $(this).data('points');
        var rangeMin = $(this).data('range-min');
        var rangeMax = $(this).data('range-max');
        var currentPoints = $(this).closest('tr').find('td:eq(3)').text().trim();
        var requiresNTE = $(this).data('requires-nte') === 'true' || $(this).data('requires-nte') === true;
        var nteServed = $(this).data('nte-served') === 'true' || $(this).data('nte-served') === true;
        
        console.log('Button data:', {
            idno: idno,
            action: action,
            pointsRange: pointsRange,
            rangeMin: rangeMin,
            rangeMax: rangeMax,
            currentPoints: currentPoints,
            requiresNTE: requiresNTE,
            nteServed: nteServed
        });

        // Get the points when they first reached this range
        var reachedPoints = $(this).siblings('.pending-indicator').text().match(/Points: ([\d.]+)/);
        reachedPoints = reachedPoints ? reachedPoints[1] : rangeMin;
        
        // Determine the memo type
        var memotype, displayAction;
        if (requiresNTE && !nteServed) {
            memotype = 'Notice to Explain';
            displayAction = 'Notice to Explain (Pre-Suspension)';
        } else {
            memotype = action.includes('Suspension') ? 'Notice of Suspension' : 
                      action.includes('Termination') ? 'Notice of Termination' : action;
            displayAction = action;
        }
        
        // Find the employee name
        var employeeName = $(this).closest('tr').find('td:eq(2)').text().trim();
        
        // Set modal values
        $('#serveIdno').val(idno);
        $('#serveIdnoDisplay').text(idno);
        $('#serveNameDisplay').text(employeeName);
        $('#servePointsDisplay').text(reachedPoints);
        $('#serveCurrentPointsDisplay').text(currentPoints);
        $('#serveActionDisplay').text(displayAction);
        $('#serveAction').val(memotype);
        $('#servePoints').val(reachedPoints);
        $('#serveRangeMin').val(rangeMin);
        $('#serveRangeMax').val(rangeMax);
        $('#servePointsServed').val(reachedPoints);
        
        // Handle suspension dates
        if (action.includes('Suspension') && (!requiresNTE || nteServed)) {
            $('#serveSuspension').closest('.form-group').show();
            
            // Calculate suspension dates (skip weekends)
            var days = parseInt(action.match(/\d+/)[0]);
            var dates = [];
            var date = new Date();
            var daysAdded = 0;
            
            while (daysAdded < days) {
                date.setDate(date.getDate() + 1);
                // Skip weekends (0 = Sunday, 6 = Saturday)
                if (date.getDay() !== 0 && date.getDay() !== 6) {
                    dates.push(date.toLocaleDateString('en-US', { 
                        month: 'short', 
                        day: '2-digit', 
                        year: 'numeric' 
                    }));
                    daysAdded++;
                }
            }
            $('#serveSuspension').val(dates.join('\n'));
        } else {
            $('#serveSuspension').closest('.form-group').hide();
        }
        
        // Show modal
        $('#serveModal').modal('show');
    });

    // After successful form submission
    $(document).ajaxComplete(function(event, xhr, settings) {
        if (settings.url.includes('?addinfraction')) {
            // Check if we just served an NTE and need to serve the suspension now
            var requiresNTE = $('#serveRangeMin').data('requires-nte');
            if (requiresNTE) {
                // Update the button to now serve the suspension
                var btn = $('.serve-btn[data-idno="' + $('#serveIdno').val() + '"][data-range-min="' + $('#serveRangeMin').val() + '"]');
                btn.data('requires-nte', false);
                btn.text('Serve Suspension');
                
                // Mark the NTE as served in the UI
                var cell = btn.closest('td');
                cell.append("<span class='served-indicator'>NTE Served: <?=date('M d, Y')?></span>");
            } else {
                // Mark the full action as served
                var btn = $('.serve-btn[data-idno="' + $('#serveIdno').val() + '"][data-range-min="' + $('#serveRangeMin').val() + '"]');
                btn.hide();
                var cell = btn.closest('td');
                cell.addClass('range-served');
                cell.find('.pending-indicator').remove();
                cell.append("<span class='served-indicator'>Served: <?=date('M d, Y')?></span>");
            }
        }
    });
});
</script>
            
<script>
$(document).ready(function() {
    // Right-click handler for points cells
    $(document).on('contextmenu', '.points-info', function(e) {
        e.preventDefault();
        
        // Get the breakdown HTML from data attribute
        var breakdownHTML = $(this).data('breakdown');
        
        // Position and show the context menu
        $('#pointsContextMenu').css({
            'display': 'block',
            'left': e.pageX + 'px',
            'top': e.pageY + 'px'
        });
        
        // Set the content
        $('#pointsBreakdownContent').html(breakdownHTML);
    });
    
    // Close the context menu when clicking elsewhere
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#pointsContextMenu').length && !$(e.target).hasClass('points-info')) {
            $('#pointsContextMenu').hide();
        }
    });
    
    // Prevent the context menu from closing when clicking inside it
    $('#pointsContextMenu').on('click', function(e) {
        e.stopPropagation();
    });
});
</script>

<style>
#pointsContextMenu {
    max-width: 500px;
    max-height: 600px;
    overflow-y: auto;
    font-size: 14px;
}
#pointsContextMenu h4 {
    color: #333;
    border-bottom: 1px solid #eee;
    padding-bottom: 5px;
}
#pointsContextMenu ul {
    padding-left: 20px;
}
#pointsContextMenu li {
    margin-bottom: 5px;
}
</style>
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
        const filenames = ['NESI1_PointsTracker.xls', 'NESI2_PointsTracker.xls', 'NEWIND_PointsTracker.xls'];

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
        const filename = (activeTabIndex >= 0 && activeTabIndex < filenames.length) ? filenames[activeTabIndex] : 'PointsTracker.xls';

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
   
</script>