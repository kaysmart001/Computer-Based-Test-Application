<!DOCTYPE html>
<html>

    <head>
	<?php	$dir =  basename(__DIR__); 
	$pgname = "Statistics";
	require_once('../_inc/inc_head.php');?>	
    </head>
    <body>

        <div id="wrapper">

<?php	require_once('_inc/inc_topnav.php');	?>
<?php	require_once('_inc/inc_sidebar.php');	?>
  <!-- /.navbar-static-side -->

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header"> Statistics </h3>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>

                <div class="panel panel-default">
                            <div class="panel-heading">
                                Assessments Properties
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Test Added</th> 
                                                <th>Student Added</th>                                     
                                                <th>Batch No</th>                                     
                                                <th>No Assessed</th>                                     
                                                <th>No Passed</th>                                     
                                                <th>No Fail</th>                                     
                                                <th style="width: 90px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php 
                                            $query = "  SELECT ass.id, ass.assessments_code, asst.assessment_type, se.session_name, ass.multiple_test
                                                        FROM assessments ass, 
                                                        session se, assessments_type asst
                                                        WHERE ass.session_id = se.id
                                                        AND ass.assessments_type = asst.id
                                                        AND ass.session_id = '$csession_id'"; 
                                            $data = mysqli_query($dbc, $query);
                                            $count = mysqli_num_rows($data);

                                            $n = 1;
                                            while ($row = mysqli_fetch_array($data)){ 
                                            $assid = $row['id'];
                                            $assessment_code =  $row['assessments_code'];
                                            $assessed = no_of_users_assessed($assid);
                                             no_of_perc($assid, 0, 10);
                                            $passno = no_of_pass($assid);
                                            $failedno = $assessed - $passno;
                                            @$pass_percent = round(($passno/$assessed * 100), 2);
                                             @$pass_percent10 = round(($passno/$assessed * 10), 2);
                                            @$failed_percent = round(($failedno/$assessed * 100), 2);
                                            $tests = countdata('assessments_id', $assid, 'tests');                                       
                                            $assessments_users = countdatasession('assessment_code', $assessment_code, 'assessments_users');
                                            
                                            $d[] = array('asscode' => "$assessment_code" ,'users' => "$assessments_users",'accessed' => "$assessed", 'nopassed' => "$passno", 'nofailed' => "$failedno",  );

                                                    echo '<tr>
                                                            <td>'.$n.'</td>
                                                            <td>'.$row['assessments_code'].'</td>
                                                            <td>'.$tests.'</td>
                                                            <td>'.$assessments_users.'</td>
                                                            <td>'.countdatasessiondist('assessment_code', $assessment_code, 'batch', 'assessments_users').'</td>                                          
                                                            <td>'.$assessed.'</td>                                          
                                                            <td>'.$passno.' ('.$pass_percent.'%)</td>
                                                            <td>'.$failedno.' ('.$failed_percent.'%)</td>
                                                            <td width="250px">                                                                                                                      
                                                               <a href="exam_test_stats.php?ass='.$assid.'" class="btn btn-outline btn-primary btn-xs"> View Tests </a>
                                                            </td>
                                                        </tr>';         
                                            $n++;  
                                            $passno = $assessed = $failedno = 0; }
                                            
                                            $dj = json_encode($d);
                                        ?>

                                      
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                
<!--                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <i class="fa fa-bar-chart-o fa-fw"></i> Assessment Users and Questions Chart
                                            </div>
                                             /.panel-heading 
                                            <div class="panel-body">
                                                <div style=" z-index: 99999; position: absolute; right: 30px; padding: 10px;" class="well">
                                                    <span>
                                                        <span style="color: #0b62a4; float: right"> Users: <i class="fa fa-square"></i> </span><br/>
                                                        <span style="color: #7a92a3; float: right"> No Accessed: <i class="fa fa-square"></i></span><br/>
                                                        <span style="color: #4da74d; float: right"> No Passed: <i class="fa fa-square"></i></span><br/>
                                                        <span style="color: #afd8f8; float: right"> No Failed: <i class="fa fa-square"></i></span>
                                                    </span>
                                                </div>
                                                <div id="bar-example"></div>
                                            </div>
                                             /.panel-body 
                                        </div>
                                    </div>
                                     /.col-lg-12 
                                </div>-->
            </div>
            <?php	require_once('footer_bar.php');	?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js.php');	?>
        <script>
            Morris.Bar({
            element: 'bar-example',
            data: <?php echo $dj ?>,
            xkey: 'asscode',
            ykeys: [ 'users', 'accessed', 'nopassed', 'nofailed'],
            labels: [ 'Users','No Accessed', 'No Passed', 'No Failed']
            });
        </script>
    </body>

</html>
