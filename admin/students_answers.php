<!DOCTYPE html>
<html>

    <head>
    <head>
	<?php	$dir =  basename(__DIR__);
	$pgname = "Student's Answers";
    $urll = $_SERVER['REQUEST_URI'];

    //print_r($_SERVER); die();

	require_once('../_inc/inc_head.php');

       if(isset($_GET['deleteq'])){
            $uid = $_GET['usid']; 
            $tsid = $_GET['tsid']; 
            $user_test_id = $uid.'-'.$tsid;

            $queryts = "DELETE FROM user_choice  WHERE user_test_id = '$user_test_id'"; 
                        $datats = mysqli_query($dbc, $queryts); 

            $queryts = "DELETE FROM  user_test WHERE user_id = '$uid' AND test_id = '$tsid'"; 
                        $datats = mysqli_query($dbc, $queryts); 


            //print_r($queryts); die(); 
            header("Location: /admin/students_answers.php?tsid=$tsid&usid=$uid");
             
        }   

        if(isset($_GET['usid'])){
            $uid = $_GET['usid']; 
            $tsid = $_GET['tsid']; 
            $userdata = userquery($uid);
            $count = count($userdata);
            for($i = 0; $i < $count; $i++){
                $lname = $userdata[$i]['lname'];
                $fname = $userdata[$i]['fname'];
                $mname = $userdata[$i]['mname'];
                $username = $userdata[$i]['userid'];
            }
        } 

      
        ?>	
	</head>	</head>

    <body>

        <div id="wrapper">

<?php	require_once('_inc/inc_topnav.php');	?>
<?php	require_once('_inc/inc_sidebar.php');	?>
  <!-- /.navbar-static-side -->

                    <div id="page-wrapper">
                        <!-- /.row -->
                        <div class="row">
                            <div class="col-lg-12  mt10">	
                                <h3 class="page-header"> User Questions & Answers </h3>
                                <?php 	 $qn = 1;
                                        //$utid = $rowts['id'];

                                        $queryts = "SELECT * FROM user_test WHERE test_id = '$tsid' AND user_id = '$uid'  "; 
                                        $datats = mysqli_query($dbc, $queryts);
                                        $rowut = mysqli_fetch_array($datats);
                                        $user_test_id = $rowut['id'];

                                        $show_answers = nameId('id', 'show_answers', 'tests', $tsid);

                                        $queryque = "SELECT user_choice.id as ucid, user_test_id, q_id, user_choice.answer, questions.id, test_id, question, choice1, choice2, choice3, choice4   
                                                    FROM user_choice INNER JOIN questions ON q_id = questions.id
                                                    AND user_test_id = '$user_test_id'";
                                        $dataque = mysqli_query($dbc, $queryque);																																			

                                       ?>

                                            <section id="testwrap">
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
                                                   ?>
                                                    <?php  echo "<b>{$username} - {$lname} {$fname} {$mname}</b> || {$asscode} {$assname} {$TName} for {$session_name}"; ?>
                                                        <span style="float:right;">
                                                            <a href="<?= $urll ?>&deleteq=1" class="btn btn-danger btn-xs"><i class="fa  fa-cross "></i> Delete All Questions  </a>
                                                            <button class="btn btn-info btn-xs" onclick="goBack()"><i class="fa  fa-backward "></i> Back  </button>

                                                        </span>
                                                    </div>
                                                        <!-- /.panel-heading -->
                                                        <div class="panel-body pbtm0">						
                                                            <div class="clear" style="clear:both">
                                                           <!-- /.table-responsive -->

                                                                <div role="tabpanel">

                                                                  <!-- Tab panes -->
                                                                  <div class="tab-content">


                                                                        <div class="table">
                                                                            <table class="table table-striped">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>No</th>
                                                                                        <th>Question</th>
                                                                                        <th>Status</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                     <?php //$ucids[] = '';
                                                                                        $n = 1;  while($rowque = mysqli_fetch_array($dataque)){
                                                                                        $countque = mysqli_num_rows($dataque);

                                                                                        $queid = $rowque['id'];

                                                                                        $ucid = $rowque['ucid'];
                                                                                        $ucids[] = $rowque['ucid'];
                                                                                        $question = $rowque['question'];
                                                                                        $choice1 = $rowque['choice1'];
                                                                                        $choice2 = $rowque['choice2'];
                                                                                        $choice3 = $rowque['choice3'];
                                                                                        $choice4 = $rowque['choice4'];
                                                                                        $ans = $rowque['answer'];
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td><h4><?php echo $n; ?></h4></td>                                            
                                                                                            <td><h4><?php echo $question; ?></h4>                                            

                                                                                            <?php 
                                                                                            $ch1 = $ch2 = $ch3 = $ch4 = "";
                                                                                            $chans1 = $chans2 = $chans3 = $chans4 = "";
                                                                                                    $icon = '<i class="fa fa-arrow-right green"></i>';
                                                                                                    if($ans == 1 ){ $ch1 = $icon;}
                                                                                                    elseif($ans == 2 ){ $ch2 = $icon;}
                                                                                                    elseif($ans == 3 ){ $ch3 = $icon;}
                                                                                                    elseif($ans == 4 ){ $ch4 = $icon;}
                                                                                                    
                                                                                           $right_ans = pick_correct_ans($queid);
                                                                                            if($right_ans == 1 ){  $chans1 = "class='green'"; }
                                                                                            elseif($right_ans == 2 ){ $chans2 = "class='green'";}
                                                                                            elseif($right_ans == 3 ){  $chans3 = "class='green'";}
                                                                                            elseif($right_ans == 4 ){ $chans4 = "class='green'";}

                                                                                            echo '<ol  type="A" style=" font-size: 14px">
                                                                                                        <li '.$chans1.'>  '.$ch1.' '.$choice1.' </li>
                                                                                                        <li '.$chans2.'>  '.$ch2.' '.$choice2.'</li>
                                                                                                        <li '.$chans3.'>  '.$ch3.' '.$choice3.' </li>
                                                                                                        <li '.$chans4.'>  '.$ch4.' '.$choice4.'</li>																								
                                                                                                    </ol>';
                                                                                            ?> 

                                                                                            </td> </form>
                                                                                            <td class="center">
                                                                                            <?php if(!empty($ans)){
                                                                                             $check = check_correct_ans($queid, $ans);
                                                                                             if($check == 1){ echo ' <i class="fa  fa-check green fa-3x"></i>';}
                                                                                             else{  echo '<i class="fa  fa-times red fa-3x"></i>';}
                                                                                            }else{
                                                                                             echo '<i class="fa  fa-times fa-3x"></i>';	
                                                                                            }
                                                                                            ?>	
                                                                                             </td>                                            

                                                                                        </tr>
                                                                                <?php $n++; } ?>

                                                                                </tbody>
                                                                            </table>
                                                                        </div>								 
                                                                  </div>

                                                                </div>
                                                            </div>
                                                        </div><!-- /.panel-body -->											                
                                                </div><!-- /.panel -->
                                           </section>
                                        </div>
                                <?php $qn++;  ?>
                            
                        </div>
                          <div class="row">
                    <!-- /.col-lg-12 -->
                            <div class="col-lg-6">
                                <div class="panel panel-default">                                   
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">
                                        <div class="flot-chart">
                                            <div class="flot-chart-content" id="flot-pie-chart"></div>
                                        </div>
                                    </div>
                                    <!-- /.panel-body -->
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="panel panel-default">                                   
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">
                                         <div class="table-responsive">
                                        <?php 	  
                                            $querytest = " SELECT us.id as usid, fname, mname, lname, userid, date, test_mark, time_start, time_end, NOQ, ut.id, session_name, ans_correct, ans_wrong, unanswered, score
                                                        FROM user_test ut, users us, tests ts, session se
                                                        WHERE ts.id = '$tsid'
                                                        AND ut.user_id = us.id
                                                       AND us.id = '$uid'
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
                                                        $test_mark = $rowats['test_mark'];
                                            
                     echo    " <table class='table table-striped table-bordered table-hover'>
                                       <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>{$username}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Course code</th>
                                                <td>{$asscode}</td>
                                            </tr>
                                            <tr>
                                                <th>No  Correct </th>
                                                <td>{$ans_correct}</td>
                                            </tr>
                                            <tr>
                                                <th>No Incorrect </th>
                                                <td>{$ans_wrong}</td>
                                            </tr>
                                            <tr>
                                                <th>No Unanswered </th>
                                                <td>{$unanswered}</td>
                                            </tr>                                            
                                            <tr>
                                                <th>Score </th>
                                                <td>{$score}</td>
                                            </tr>                                            
                                            <tr>
                                                <th>Mark Obtainable</th>
                                                <td>{$test_mark}</td>
                                            </tr>                                            
                                            <tr>
                                                <th>Time Started </th>
                                                <td>{$rowats['time_start']}</td>
                                            </tr>                                            
                                            <tr>
                                                <th>Time Ended</th>
                                                <td>{$rowats['time_end']}</td>
                                            </tr>                                           
                                            <tr>
                                                <th>Test Date</th>
                                                <td>{$rowats['date']}</td>
                                            </tr>
                                        </tbody>
                                    </table>"; }?>
                                        </div>
                                    </div>
                                    <!-- /.panel-body -->
                                </div>
                            </div>

                        </div>

                    </div>
 
        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js2.php');	?>
 <script>
      $(function() {

    var data = [{
        label: "Correct <?php echo $ans_correct; ?>",
        data: <?php echo $ans_correct; ?>
    }, {
        label: "Incorect <?php echo $ans_wrong; ?>",
        data: <?php echo $ans_wrong; ?>
    }, {
        label: "Unanswered <?php echo $unanswered; ?>",
        data: <?php echo $unanswered; ?>
    }];

    var plotObj = $.plot($("#flot-pie-chart"), data, {
        series: {
            pie: {
                show: true
            }
        },
        grid: {
            hoverable: true
        },
        tooltip: true,
        tooltipOpts: {
            content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
            shifts: {
                x: 20,
                y: 0
            },
            defaultTheme: true
        },
           legend: {
        show: false
    }
    });

});
</script>
    </body>

</html>
