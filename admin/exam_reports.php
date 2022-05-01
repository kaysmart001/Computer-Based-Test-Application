<!DOCTYPE html>
<html>

    <head>
	<?php	$dir =  basename(__DIR__); 
	$pgname = "View Exam";
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
                        <h3 class="page-header"> Reports </h3>
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
                                                <th width="190px">Actions</th>
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
                            $assessment_code =  $row['id'];
                            $assessed = no_of_users_assessed($assid);
                            $passno = no_of_pass($assid);
                            $failedno = $assessed - $passno;
                            @$pass_percent = round(($passno/$assessed * 100), 2);
                            @$failed_percent = round(($failedno/$assessed * 100), 2);
                                    echo '<tr>
                                            <td>'.$n.'</td>
                                            <td>'.$row['assessments_code'].'</td>
                                            <td>'.countdata('assessments_id', $assid, 'tests').'</td>
                                            <td>'.countdatasession('assessment_code', $assessment_code, 'assessments_users').'</td>
                                            <td>'.countdatasessiondist('assessment_code', $assessment_code, 'batch', 'assessments_users').'</td>                                          
                                            <td>'.$assessed.'</td>                                          
                                            <td>'.$passno.' ('.$pass_percent.'%)</td>
                                            <td>'.$failedno.' ('.$failed_percent.'%)</td>
                                            <td width="250px">                                                                                                                      
                                               <a href="exam_test_reports.php?ass='.$assid.'" class="btn btn-outline btn-primary btn-xs"> View Tests </a>
                                               <a href="print_score_sheet.php?ass='.$assid.'" target="_blank"><button class="btn btn-grey btn-xs"><i class="fa  fa-print "></i> Print Score Sheet  </button></a>
                                               <a href="excel_score_sheet.php?ass='.$assid.'" target="_blank"><button class="btn btn-success  btn-xs"><i class="fa  fa-arrow-down "></i> EXPORT '.$assid.'  </button></a>
                                            </td>
                                        </tr>';         
                            $n++;  
                            $passno = $assessed = $failedno = 0; }
                        ?>
                            <!--
                                            <tr>
                                                <td>1</td>
                                                <td>ENT232</td>
                                                <td>5</td>
                                                <td>70</td>                                                
                                                <td>703</td>                                                
                                                <td>1</td>                                                
                                                <td>2014/2015</td>                                                
                                                <td>No</td>                                                
                                                <td>                                   
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                            Actions
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu pull-right" role="menu">
                                                            <li><a href="#">Edit Assessments Properties</a></li>
                                                            <li><a href="#">Add Test to Assessments</a></li>
                                                            <li><a href="#">Add Students to Assessments</a></li>
                                                        </ul>
                                                    </div>
                                                    </td>
                                            </tr>
                                -->
                                      
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
            </div>
            		<?php	//require_once('footer_bar.php');	?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js.php');	?>

    </body>

</html>
