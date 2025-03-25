<script type="text/javascript">
        function SubmitDetails(){
            return confirm('Do you wish to submit details?');
        }
    </script>
    <div class="row">
        <div class="col-lg-12">
            <h4 style="text-indent: 10px;"><a href="?main"><i class="fa fa-arrow-left"></i> BACK</a> | <i class="fa fa-money"></i> PAYROLL PERIOD</h4>
        </div>
    </div>
    <form class="form-horizontal style-form" method="GET" target="_blank">
        <input type="hidden" name="viewpayroll">
        <div class="col-lg-4 mt">
            <div class="content-panel">
                <div class="panel-heading">
                    <input type="submit" name="submit" class="btn btn-primary" value="Select" style="float:right;">
                    <h4><i class="fa fa-file-text-o"></i> SELECT PERIOD</h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-4 col-sm-4 control-label">Payroll Period</label>
                        <div class="col-sm-8">
                            <select name="id" class="form-control" required>
                                <option value=""></option>
                                <?php
                                $sqlPeriod=mysqli_query($con,"SELECT * FROM payroll ORDER BY id DESC");
                                if(mysqli_num_rows($sqlPeriod)>0){
                                    while($period=mysqli_fetch_array($sqlPeriod)){
                                        echo "<option value='$period[id]'>".date('F d, Y',strtotime($period['periodfrom']))." to ".date('F d, Y',strtotime($period['periodto']))."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col-lg-12-->
        </div>
    </form>
<?php
if(isset($_GET['submit'])){
    $id = $_GET['id'];
    $idno = $_SESSION['idno'];
    
    $sqlCheck = mysqli_query($con, "SELECT pd.*, ep.*, pd.id AS pd_id
        FROM payroll_details pd
        INNER JOIN employee_payroll ep ON ep.idno = pd.idno
        WHERE pd.payrollperiod = '$id' 
        AND pd.idno = '$idno' 
        AND pd.status = 'posted'");

    if(mysqli_num_rows($sqlCheck) > 0){
        $pay = mysqli_fetch_array($sqlCheck);
        $salary_type = $pay['salary_type'];
        $pay_id = $pay['pd_id']; // Use the alias for pd.id

        if($salary_type == 'Rated'){
            echo "<script>window.location='../accounting/payslipRated.php?id=$pay_id';</script>";
        } else {
            echo "<script>window.location='../accounting/payslip.php?id=$pay_id';</script>";
        }
    } else {
        echo "<script>window.location='paysliperror.php';</script>";
    }
}
?>