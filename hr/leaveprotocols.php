<div class="col-lg-12">
            <div class="content-panel">
              <div class="panel-heading">
              <h4><a href="?main"><i class="fa fa-arrow-left"></i> HOME</a> | <i class="fa fa-file-text"></i> LEAVE PROTOCOLS<div style="float:right;"><a href="?addleaveprotocols" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add Leave Protocol</a></div></h4>
            </div>
              <div class="panel-body">
                <table class="table table-bordered table-striped table-condensed">
                  <thead>
                    <tr>
                      <th  style="text-align: center; vertical-align: middle;" width="3%">No.</th>
                      <th style="text-align: center; vertical-align: middle;">Approving Officer</th>
                      <th style="text-align: center; vertical-align: middle;">Shift</th>
                      <th style="text-align: center; vertical-align: middle;">Company</th>
                      <th style="text-align: center; vertical-align: middle;">Department</th>
                      <th style="text-align: center; vertical-align: middle;">Specific Job Title</th>                      
                      <th style="text-align: center; vertical-align: middle;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $x = 1;
                      $sqlEmployee = mysqli_query($con, "SELECT lp.approvingofficer, ep.lastname, ep.firstname, jt.jobtitle, ed.startshift,
                              GROUP_CONCAT(DISTINCT lp.shift ORDER BY lp.shift ASC) AS shifts,
                              GROUP_CONCAT(DISTINCT s.companycode ORDER BY s.companycode ASC) AS companies,
                              GROUP_CONCAT(DISTINCT d.department ORDER BY d.department ASC) AS departments,
                              GROUP_CONCAT(DISTINCT jt_requestor.jobtitle ORDER BY jt_requestor.jobtitle ASC) AS requestors
                          FROM leave_protocols lp
                          INNER JOIN employee_profile ep ON ep.idno = lp.approvingofficer
                          INNER JOIN employee_details ed ON ed.idno = ep.idno
                          INNER JOIN jobtitle jt ON jt.id = ed.designation
                          LEFT JOIN settings s ON s.companycode = lp.company
                          LEFT JOIN department d ON d.id = lp.department
                          LEFT JOIN jobtitle jt_requestor ON jt_requestor.id = lp.requestingofficer
                          GROUP BY lp.approvingofficer
                          ORDER BY ep.lastname ASC");

                      if (mysqli_num_rows($sqlEmployee) > 0) {
                          while ($row = mysqli_fetch_array($sqlEmployee)) {
                            $approvingofficer = '<span style="font-weight: bold; font-size: 15px">' 
                            . strtoupper($row['lastname']) 
                            . '</span>, ' . $row['firstname'] 
                            . '<br>[' . $row['jobtitle'] . ']';
       
                              $shifts = $row['shifts'] ? $row['shifts'] : '-';
                              $companies = $row['companies'] ? $row['companies'] : '-';
                              $departments = $row['departments'] ? $row['departments'] : '-';
                              $requestors = $row['requestors'] ? $row['requestors'] : '-';

                              echo "<tr>";
                              echo "<td style='text-align: center; vertical-align: middle'>$x.</td>";
                              echo "<td style='text-align: justified; vertical-align: middle'>$approvingofficer</td>";
                              echo "<td style='text-align: center; vertical-align: middle'>$shifts</td>";
                              echo "<td style='text-align: center; vertical-align: middle'>$companies</td>";
                              echo "<td style='text-align: center; vertical-align: middle'>$departments</td>";
                              echo "<td style='text-align: justified; vertical-align: middle'>$requestors</td>";
                              ?>
                              <td align="center">
                                  <a href="?manageleaveprotocols&approvingofficer=<?= $row['approvingofficer']; ?>" class="btn btn-success btn-xs" title="Edit Leave Protocol"><i class='fa fa-pencil'></i></a>
                                  <a href="?leaveprotocols&approvingofficer=<?= $row['approvingofficer']; ?>&delete" class="btn btn-primary btn-xs" title="Delete Leave Protocol" onclick="return confirm('Do you wish to remove this item?'); return false;"><i class='fa fa-trash'></i></a>
                              </td>
                              <?php
                              echo "</tr>";
                              $x++;
                          }
                      } else {
                          echo "<tr><td colspan='6' align='center'>No record found!</td></tr>";
                      }
                    ?>
                  </tbody>
                </table>              
                </div>  
            </div>
            </div>                    
            <?php
            if(isset($_GET['delete'])){
              $appofficer=$_GET['approvingofficer'];              
              $sqlDelete=mysqli_query($con,"DELETE FROM leave_protocols WHERE approvingofficer='$appofficer'");
              if($sqlDelete){
                echo "<script>alert('Leave protocol successfully removed!');window.location='?leaveprotocols';</script>";
              }else{
                echo "<script>alert('Unable to remove leave protocol!');window.location='?leaveprotocols';</script>";
              }
            }            
            ?>

<script>
  $(document).ready(function() {
    $('#titleapprovingofficer').change(function() {
      var jobTitleId = $(this).val();
      if (jobTitleId) {
        console.log("Job Title ID: " + jobTitleId); // Debugging statement
        $.ajax({
          url: 'fetch_employees.php',
          method: 'POST',
          data: { jobTitleId: jobTitleId },
          success: function(data) {
            console.log("Response Data: " + data); // Debugging statement
            $('#approvingofficer').html(data);
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error: " + textStatus + ": " + errorThrown);
            $('#approvingofficer').html('<option value="">Error loading officers</option>');
            alert("An error occurred while fetching the approving officers. Please try again.");
          }
        });
      } else {
        $('#approvingofficer').html('<option value="">Select Approving Officer</option>');
      }
    });
  });
</script>