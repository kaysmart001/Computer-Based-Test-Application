<!DOCTYPE html>
<html>

    <head>
<?php	$dir =  basename(__DIR__);
$pgname = "Test Page";
require_once('../_inc/inc_head.php');	
 $assid = '0'; 

     	$user_ip = $ip = getRealIpAddr();	
	 	$browser = $_SERVER['HTTP_USER_AGENT'];
		$date = date("Y-m-d").'<br>';
		$time_start = date("H:i:s").'<br>';

	//test info
if(isset($_GET['ass'])){
	$assid = $_GET['ass']; 
	$sum_time = 0;
	$sum_time_spent = 0;

	//echo
	$querytest = " 	SELECT ts.id, TName, time, NOQ, random, show_answers, minus_mark, be_default, assessments_id, assessments_name, assessments_code, session_id 
					FROM tests ts, assessments ass
					WHERE ts.assessments_id = ass.id AND be_default = 1 AND session_id = '$csession_id' AND  assessments_id = $assid"; 
	$datatest = mysqli_query($dbc, $querytest);
	$countts1 = mysqli_num_rows($datatest);
	$n = 1;
	while($rowats = mysqli_fetch_array($datatest)){ 
		$testid = $rowats['id'];
		$asscode = $rowats['assessments_code'];
		$assname = $rowats['assessments_name'];
		$NOQ = $rowats['NOQ'];
		$time = $rowats['time'];
		
		$sum_time += $rowats['time'];		
		$rowactivets[] = $rowats; 
			
                    //echo check if user test exist and record if not
                    $queryts = "SELECT * FROM user_test WHERE test_id = '$testid' AND user_id = '$uid' "; 
                    $datats = mysqli_query($dbc, $queryts);
                    $countts = mysqli_num_rows($datats);
                    $rowts = mysqli_fetch_array($datats);			
                    $user_test_id = $rowts['id'];
                    $time_length = $rowts['time_length'];

	}
}
 ?>
</head>

    <body>

        <div id="wrapper">

            <nav class=" navbar navbar-default navbar-fixed-top" role="navigation">
                <!-- /.navbar-header -->
                        
                     <div class="navbar-header2 navbar-header">
                        <div class="titleprofilebar" style=""> 
                            <div class="user-info-profile-image2 passport">
                            <img id="passport" src="../_res/img/passport/<?php echo $userid; ?>.jpg" 
                                 alt="" width="140" height="140"  onerror="this.src = '../_res/img/passport/default.jpg';" />
                            </div>

                            <div class="user-info">
                                    <div class="username"><strong> <?php echo $userid; ?> </strong>
                                    <br> <?php echo $LName.' '.$FName.' '.$MName; ?>  </div>
                                    <!--- <div class="username"> <strong>Computer Science</strong> 200L</div> --->
                                    <div class="username"> <strong><?php echo $asscode; ?></strong> - <?php echo $assname; ?></div>
                            </div>   

                        </div>                
                    </div> 

                <!-- /.navbar-top-links -->
		<ul class="nav navbar-top-links navbar-right">
                    <li><a href="logout.php" title="Logout"><i class="fa fa-power-off fa-2x fa-fw"></i></a>
                </ul>
            </nav>
			
								
            <!-- /.navbar-static-top -->
<?php	//require_once('bar_sidebar.php');	?>
  <!-- /.navbar-static-side -->

            <div id="page-wrapper2" style="margin-top: 100px;">
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-10 col-md-offset-1 mt10">	
						
                        <div role="tabpanel" style="margin-left: 50px;">
                                <!-- Nav tabs -->
                                  <ul class="nav nav-tabs" role="tablist">
                                                <?php 

                                        foreach ($rowactivets as $rowats){ 
                                                $testidats = $rowats['id'];
                                                        //echo check if user test exist and record if not
                                                         $queryts = "SELECT * FROM user_test WHERE test_id = '$testidats' AND user_id = '$uid' "; 
                                                        $datats = mysqli_query($dbc, $queryts);
                                                        $countts = mysqli_num_rows($datats);
                                                        $rowut = mysqli_fetch_array($datats);
                                                        $user_test_id = $rowut['id'];

                                                        if($countts == 0){

                                                        }	

                                                 $first_test = reset($rowactivets[0]);

                                                if($testidats == $first_test){ $active = 'active';}else{ $active = ''; } 
                                                echo'
                                                <li role="presentation" class="'.$active.'"><a href="#test'.$testidats.'" aria-controls="home" role="tab" data-toggle="tab"><b>'.$rowats['assessments_code'].' - '.(strtoupper($rowats['TName'])).'</b></a></li>';
                                                }
                                                ?>
                                  </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                <?php 	 $qn = 1;
                                        foreach ($rowactivets as $rowats){ 								
                                                        $testidats = $rowats['id']; //active test
                                                        $NOQ2 = $rowats['NOQ']; //no active test
                                                        $testidatss[] = $testidats;
                                                        //$utid = $rowts['id'];

                                                        $queryts = "SELECT * FROM user_test WHERE test_id = '$testidats' AND user_id = '$uid'  "; 
                                                        $datats = mysqli_query($dbc, $queryts);
                                                        $rowut = mysqli_fetch_array($datats);
                                                        $user_test_id = $rowut['id'];

                                                        $show_answers = nameId('id', 'show_answers', 'tests', $testidats);


                                                        $queryque = "SELECT user_choice.id as ucid, user_test_id, q_id, user_choice.answer, questions.id, test_id, question, choice1, choice2, choice3, choice4   
                                                                                FROM user_choice INNER JOIN questions ON q_id = questions.id
                                                                                AND user_test_id = '$user_test_id' LIMIT $NOQ2";
                                                        $dataque = mysqli_query($dbc, $queryque);																																			

                                                        if($qn == 1){ $active = 'active';}else{ $active = ''; }									
                                                ?>

                                        <div role="tabpanel" class="tab-pane  <?php echo $active; ?>" id="test<?php echo $testidats;?>">

                                                <section id="testwrap">
                                                <div class="panel panel-default">

                                                        <!-- /.panel-heading -->
                                                        <div class="panel-body pbtm0">						
                                                                <div class="clear" style="clear:both">
                                                           <!-- /.table-responsive -->

                                                                <div role="tabpanel">

                                                                  <!-- Tab panes -->
                                                                  <div class="tab-content">


                                                                    <?php 	if($show_answers == 1){ ?>
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
                                                                                                        $icon = '<i class="fa fa-arrow-right green"></i>';
                                                                                                        if($ans == 1 ){ $ch1 = $icon; }
                                                                                                        elseif($ans == 2 ){ $ch2 = $icon; }
                                                                                                        elseif($ans == 3 ){ $ch3 = $icon; }
                                                                                                        elseif($ans == 4 ){ $ch4 = $icon; }	

                                                                                        echo '<ol  type="A" style=" font-size: 14px">
                                                                                                                <li>  '.$ch1.' '.$choice1.' </li>
                                                                                                                <li>  '.$ch2.' '.$choice2.'</li>
                                                                                                                <li>  '.$ch3.' '.$choice3.' </li>
                                                                                                                <li>  '.$ch4.' '.$choice4.'</li>																								
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

                                                                        <?php  }else{ ?>
                                                                        <div class="well">
                                                                                <h2 class="text-center text-danger"> <i class="fa fa-times fa-fw"> </i> No Permission to View Test Answers  <span id="duration"> </span></h2>
                                                                        </div>
                                                                        <?php  } ?>
                                                                  </div>

                                                                </div>
                                                        </div>


                                                        </div><!-- /.panel-body -->											                
                                                </div><!-- /.panel -->
                                           </section>
                                        </div>
                                <?php $qn++; 
                                }$testidatss[]="";  ?>
                          </div>

                        </div>
                    <div class="panel-footer text-center" style="margin-top: 0px; margin-left: 50px;">
                        <a href="result.php?ps=tresult&&ass=<?php echo $assid; ?>"><button type="button" class="btn btn-info" > View Result</button></a>
                        <a href="index.php"><button type="button" class="btn btn-primary">Back to Homepage</button></a>
                     </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /#page-wrapper -->
        </div>

	<!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js.php');	?>
		
  </body>

</html>