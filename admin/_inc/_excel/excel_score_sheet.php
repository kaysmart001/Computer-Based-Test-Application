<!DOCTYPE html>

<head>
	<?php		  
	require_once('../_inc/functions/CIFunctions.php');  

	$dir =  basename(__DIR__);
	$pgname = "Print Assessment Score Sheet";
	$ass_id = $_GET['ass'];
        function passm($score){
            global $passmark;
            if($score >= $passmark){
                return 'Passed';
            }else{
                return 'Failed';
            }
        }

	?>	
	<title> <?php echo $pgname; ?> </title>
      <!-- Page-Level Plugin CSS - Tables -->
<link href="../_res/css/bootstrap.min2.css" rel="stylesheet">
</head>

<body class="full-width" style="background-color:#333">
    <!-- Page container -->
    <div class="page-container container" style="background-color:white; min-height:800px; padding: 20px; width:990px; font-size: 12px">
    	
        <!-- Page content -->
        <div class="page-content">
			<div class="widget">
			<div class=" text-center">
				<?php printHeader();	?>		
		</div>
                            
			<div class="panel panel-default">
                                <?php // 	echo                                                               
                                $querytest = " 	SELECT ts.id, TName, assessments_id, assessments_name, assessments_code, session_name
                                                   FROM tests ts, assessments ass, session se
                                                   WHERE ass.id = ts.assessments_id
                                                   AND ass.session_id = se.id	
                                                   AND ass.id = $ass_id"; 
                                   $datatest = mysqli_query($dbc, $querytest);
                                   
                                   ;
                               while ($rowats = mysqli_fetch_array($datatest)){
                                           $tstnames[] = $rowats['TName'];
                                           $tstids[] = $rowats['id']; 

                                           $asscode = $rowats['assessments_code'];
                                           $assname = $rowats['assessments_name'];
                                           $session_name = $rowats['session_name'];                               
                               }	
                               $countts = mysqli_num_rows($datatest);                                   
                               ?>
                            
                            <?php if ($countts){  ?>
                            
                                     <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Test Added</th> 
                                                <th>Student Registered</th>                                     
                                                <th>No of Batch</th>                                     
                                                <th>No Assessed</th>                                     
                                                <th>No Passed</th>                                     
                                                <th>No Fail</th>                                     
                                                <th>Highest Score</th>                                     
                                                <th>Lowest Score</th>                                     
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php 
                                                $query = "  SELECT ass.id, ass.assessments_code, asst.assessment_type, se.session_name, ass.multiple_test
                                                            FROM assessments ass, 
                                                            session se, assessments_type asst
                                                            WHERE ass.session_id = se.id
                                                            AND ass.assessments_type = asst.id
                                                            AND ass.id = $ass_id
                                                            AND ass.session_id = '$csession_id'"; 
                                                $data = mysqli_query($dbc, $query);
                                                $count = mysqli_num_rows($data);

                                                $n = 1;
                                                while ($row = mysqli_fetch_array($data)){ 
                                                $assid = $row['id'];
                                                $assessment_code =  $row['assessments_code'];
                                                $assessed = no_of_users_assessed($assid);
                                                $passno = no_of_pass($assid);
                                                $failedno = $assessed - $passno;
                                                @$pass_percent = round(($passno/$assessed * 100), 2);
                                                @$failed_percent = round(($failedno/$assessed * 100), 2);
                                                        echo '<tr>
                                                                <td>'.countdata('assessments_id', $assid, 'tests').'</td>
                                                                <td>'.countdatasession('assessment_code', $assessment_code, 'assessments_users').'</td>
                                                                <td>'.countdatasessiondist('assessment_code', $assessment_code, 'batch', 'assessments_users').'</td>                                          
                                                                <td>'.$assessed.'</td>                                          
                                                                <td>'.$passno.' ('.$pass_percent.'%)</td>
                                                                <td>'.$failedno.' ('.$failed_percent.'%)</td> 
                                                                <td>'.total_scores($assid)["max"].'</td> 
                                                                <td>'.total_scores($assid)["min"].'</td> 
                                                                    

                                                            </tr>';         
                                                $n++;  
                                                $passno = $assessed = $failedno = 0; }
                                            ?> 
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                            
                         <div class="panel panel-default">
                             <div class="panel-heading">
                                <?php // 	echo                                                               
                               if ($countts){           
                                    echo 'Score Sheet for <b>'.$asscode.' - '.$assname.' </b> for <b>'.$session_name.'</b>';
                               }      
                               ?>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover sans" id="dataTables-example" >
                                        <thead>
                                            <tr style="font-size: 12px">
                                                <th>No</th>
                                                <th>Matric No</th>
                                                <th>Surname</th>
                                                <th>First Name</th>
                                                <th>Other Name</th>
                                                <?php foreach ($tstnames as $tstname) {  
                                                    echo '<th>'.$tstname.'</th>';
                                                }   ?>   
                                                 <th>Total</th>
                                                 <th>Remark</th>
                                                   
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                          $querytest = " SELECT DISTINCT us.id as usid, fname, mname, lname, userid
                                                            FROM user_test ut, users us, tests ts
                                                            WHERE us.id = ut.user_id
                                                            AND ut.test_id = ts.id
                                                            AND ts.assessments_id = $ass_id
                                                            ";
                                           $datatest = mysqli_query($dbc, $querytest);
                                              
                                            $n=1; $ns=0; while($rowats = mysqli_fetch_array($datatest)){
                                                        $uid = $rowats['usid'];
                                                        $LName = $rowats['lname'];
                                                        $FName = $rowats['fname'];
                                                        $MName = $rowats['mname'];
                                                        $userid = $rowats['userid'];
                                                                                                      
                                                echo '
                                                <tr class="odd gradeX">
                                                    <td>'.$n.'</td>
                                                    <td>'.$userid.'</td>
                                                    <td>'.$LName.'</td>
                                                    <td>'.$FName.'</td>
                                                    <td>'.$MName.'</td>';
                                             $tscore = 0;       
                                            foreach ($tstids as $tsid) {                                                                                                                                             
                                             $queryscore = " SELECT  score, id FROM user_test WHERE user_id = $uid AND test_id = $tsid"; 
                                            $datascore = mysqli_query($dbc, $queryscore);
                                            while($rowscore = mysqli_fetch_array($datascore)){
                                                        echo '<td>'.$rowscore['score'].'</td>';
                                                        $tscore = $tscore + $rowscore['score'];
                                                }
                                            $count_result = mysqli_num_rows($datascore);
                                                if (($count_result) < 1){
                                                           echo '<td>'.$rowscore['score'].'</td>';
                                                }
                                            }
                                           echo ' <td class="center">'.round($tscore, 0).'</td>
                                           <td class="center">'.  passm($tscore).'</td>
                                               
                                          </tr>';
                                        $n++;  }?>											
                                        </tbody>
                                    </table>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                           <?php  }  else {
                                    echo '<div class="text-center text-danger"> <h3> No Record Found </h3></div>';

                                            }  ?>
                        </div>
                        <!-- /.panel --><!-- /bordered datatable inside panel -->
			</div>
			<!-- With titles (frame) -->
		</div>
	</div>
	<!-- Footer -->
</body>
</html>