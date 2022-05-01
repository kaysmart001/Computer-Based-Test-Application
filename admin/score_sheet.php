<!DOCTYPE html>
<html>

    <head>
	<?php	$dir =  basename(__DIR__);
	$pgname = "Score Sheet";
	$tsid = $_GET['tsid'];
	require_once('../_inc/inc_head.php');	?>	
      <!-- Page-Level Plugin CSS - Tables -->
	<link href="../_res/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    </head>
    
    <body>
        <div id="wrapper">

<?php	require_once('_inc/inc_topnav.php');	?>
<?php	require_once('_inc/inc_sidebar.php');	?>
  <!-- /.navbar-static-side -->


            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header">Result</h3>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <?php 	//echo
                                $querytest = " 	SELECT ts.id, TName, assessments_id, assessments_name, assessments_code, session_name
                                                FROM tests ts, assessments ass, session se
                                                WHERE ts.id = '$tsid'
                                                AND ass.id = ts.assessments_id
                                                AND ass.session_id = se.id	"; 
                                $datatest = mysqli_query($dbc, $querytest);
                                $rowats = mysqli_fetch_array($datatest);

                                        $TName = $rowats['TName'];
                                        $asscode = $rowats['assessments_code'];
                                        $assname = $rowats['assessments_name'];
                                        $session_name = $rowats['session_name'];												

                                 echo 'View Score Sheet for <b>'.$asscode.' - '.$assname.' - '.$TName.' </b> for <b>'.$session_name.'</b>';
                                ?>
                            <span style="float:right;">
                            <a href="print_test_score_sheet.php?tsid=<?php echo $tsid; ?>" target="_blank"><button class="btn btn-grey btn-xs"><i class="fa  fa-print "></i> Print Score Sheet  </button> </a>

                                <button class="btn btn-info btn-xs" onclick="goBack()"><i class="fa  fa-backward "></i> Back  </button>
                            </span>
                            </div>
                            
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                            <!--     <div class="form-group input-group col-lg-6">
                                    <input type="text" class="form-control">
                                    <span class="input-group-btn">
                                            <button type="button" class="btn btn-default"><i class="fa fa-search"></i>
                                            </button>
                                    </span>
                                </div>
                                
                               <a href="print_test_score_sheet.php?tsid=<?php echo $tsid; ?>" target="_blank"><button class="btn btn-primary"><i class="fa  fa-print "></i> Print Score Sheet  </button> </a>
				
                                <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_previous"><a href="#">Previous</a></li><li class="paginate_button active" aria-controls="dataTables-example" tabindex="0"><a href="#">1</a></li><li class="paginate_button " aria-controls="dataTables-example" tabindex="0"><a href="#">2</a></li><li class="paginate_button " aria-controls="dataTables-example" tabindex="0"><a href="#">3</a></li><li class="paginate_button " aria-controls="dataTables-example" tabindex="0"><a href="#">4</a></li><li class="paginate_button next" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_next"><a href="#">Next</a></li></ul></div>
                                <div class="clear" style="clear:both"></div>
                                 -->
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover sans" id="dataTables-example" >
                                        <thead>
                                            <tr style="font-size: 12px">
                                                <th>No</th>
                                                <th>Matric No</th>
                                                <th>Surname</th>
                                                <th>First Name</th>
                                                <th>Other Name</th>
                                                <th>Questions</th>
                                                <th>Correct</th>
                                                <th>Incorrect</th>
                                                <th>Unanswered</th>
                                                <th>Score</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 	//echo  
                                            $querytest = " SELECT us.id as usid, fname, mname, lname, userid, NOQ, ut.id, session_name, ans_correct, ans_wrong, unanswered, score
                                                        FROM user_test ut, users us, tests ts, session se
                                                        WHERE ts.id = '$tsid'
                                                        AND us.id = ut.user_id
                                                        AND ut.test_id = ts.id
                                                        AND se.id = '$csession_id'"; 
                                            $datatest = mysqli_query($dbc, $querytest);
                                            
                                            $n=1; while($rowats = mysqli_fetch_array($datatest)){
                                                        $uid = $rowats['usid'];
                                                        $LName = $rowats['lname'];
                                                        $FName = $rowats['fname'];
                                                        $MName = $rowats['mname'];
                                                        $ans_correct = $rowats['ans_correct'];
                                                        $unanswered = $rowats['unanswered'];
                                                        $ans_wrong = $rowats['ans_wrong'];
                                                        $score = $rowats['score'];
                                                        $userid = $rowats['userid'];
                                                        $NOQ = $rowats['NOQ'];


                                                echo '
                                                <tr class="odd gradeX">
                                                    <td>'.$n.'</td>
                                                    <td class="center">'.$userid.'</td>
                                                    <td>'.$LName.'</td>
                                                    <td>'.$FName.'</td>
                                                    <td>'.$MName.'</td>
                                                    <td  class="center2">'.$NOQ.'</td>
                                                    <td  class="center2">'.$ans_correct.'</td>
                                                    <td  class="center2">'.$ans_wrong.'</td>
                                                    <td  class="center2">'.$unanswered.'</td>
                                                    <td  class="center2"><b>'.$score.'</b></td>
                                                    <td><a href="students_answers.php?tsid='.$tsid.'&usid='.$uid.'"> <button class="btn btn-success btn-xs"> Details</button></a></td>
                                                </tr>';
                                            $n++;}?>											
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /#page-wrapper -->

            		<?php	require_once('footer_bar.php');	?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js.php');	?>

    </body>

</html>
