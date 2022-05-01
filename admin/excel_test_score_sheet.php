<?php       
    require_once('../_inc/functions/CIFunctions.php');  
//echo
    $tsid = $_GET['tsid'];
    $querytest = "  SELECT ts.id, TName, assessments_id, assessments_name, assessments_code, session_name
                    FROM tests ts, assessments ass, session se
                    WHERE ts.id = '$tsid'
                    AND ass.id = ts.assessments_id
                    AND ass.session_id = se.id  "; 
    $datatest = mysqli_query($dbc, $querytest);
    $rowats = mysqli_fetch_array($datatest);

            $TName = $rowats['TName'];
            $asscode = $rowats['assessments_code'];
            $assname = $rowats['assessments_name'];
            $session_name = $rowats['session_name'];                                                
            $session_id = $rowats['id'];                                                
    ?>
<?php $excelFileName = $asscode.'_'.$TName.'_'.$session_id.'.xls';       
      export_excel($excelFileName);  
?>

<!DOCTYPE html>

<head>
	<?php		  
	$dir =  basename(__DIR__);
	$pgname = "Test Score Sheet"; ?>	

	<title> <?php echo $pgname; ?> </title>
      <!-- Page-Level Plugin CSS - Tables -->
</head>

<body class="full-width">
    <!-- Page container -->
    <div class="page-container container">
    	
        <!-- Page content -->
        <div class="page-content">
			<div class="widget">
			<div class=" text-center">
				<?php //printHeader();	?>		
		</div>
				<!-- Bordered datatable inside panel -->
			<div class="panel panel-default">                           
                           <!-- /.panel-heading -->
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover sans" id="dataTables-example" >
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
                                                    <td>'.$userid.'</td>
                                                    <td  class="center2"><b>'.$score.'</b></td>
                                                </tr>';
                                            $n++;}?>											
                                        </tbody>
                                    </table>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel --><!-- /bordered datatable inside panel -->
			</div>
			<!-- With titles (frame) -->
		</div>
	</div>
	<!-- Footer -->
</body>
</html>

