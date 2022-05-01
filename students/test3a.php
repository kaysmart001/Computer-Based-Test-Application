<!DOCTYPE html>
<html>

    <head>
        <link rel="stylesheet" href="calc/css/style.css">


        <?php
        $dir = basename(__DIR__);
        $pgname = "Test Page";
        require_once('../_inc/inc_head.php');
        checkSetStartExam();

        $user_ip = $ip = getRealIpAddr();
        $browser = $_SERVER['HTTP_USER_AGENT'];
        $date = date("Y-m-d") . '<br>';
        $time_start = date("H:i:s") . '<br>';


        //CHECK IF POST END OR START EXAM
        if((isset($_POST['startexam'])) || (isset($_POST['endexam']))) {
            $assid = $_POST['assid'];
            $sum_time = 0;
            $sum_time_spent = 0;

            //GET POSTED TESTS  
            $querytest = " SELECT ts.id, TName, time, NOQ, random, minus_mark, be_default, assessments_id, assessments_name, assessments_code, session_id 
    			   FROM tests ts, assessments ass
    			   WHERE ts.assessments_id = ass.id 
                   AND session_id = '$csession_id' 
                   AND  assessments_id = $assid";
            $datatest = mysqli_query($dbc, $querytest);
            $countts1 = mysqli_num_rows($datatest);
            
            //$rowats = mysqli_fetch_array($datatest);            
            //print_r($rowats);


            $n = 1;
            while ($rowats = mysqli_fetch_array($datatest)) {
                $testid = $rowats['id'];
                $asscode = $rowats['assessments_code'];
                $assname = $rowats['assessments_name'];
                $NOQ = $rowats['NOQ'];
                $time = $rowats['time'];
                $sum_time += $rowats['time'];
                $rowactivets[] = $rowats;

                //echo check if user test (GET USER TEST ID) exist and record if not
                $queryts = "SELECT * FROM user_test WHERE test_id = '$testid' AND user_id = '$uid' ";
                $datats = mysqli_query($dbc, $queryts);
                $countts = mysqli_num_rows($datats);
                $rowts = mysqli_fetch_array($datats);
                $user_test_id = $rowts['id'];
                $time_length = $rowts['time_length'];

                //IF END 
                if (isset($_POST['endexam'])) {
                    $user_test_ids = $rowts['id'];
                    $sql = "UPDATE user_test SET finish = 1 WHERE id = '$user_test_ids'";
                    $data = mysqli_query($dbc, $sql);
                }
            }

            $sum_time_str = $sum_time . ':00';
        }

        //CHECK IF USER_ASS EXIST  
      $queryua = "SELECT * FROM user_assessments WHERE ass_id = '$assid' AND ass_user_id = '$uid'";
      $dataua = mysqli_query($dbc, $queryua);
      $countua = mysqli_num_rows($dataua);

        if ($countua == 0) {
            $sum_time_spent = '00';

            //IF NOT INSERT RECORD TO USER_ASS
            $sql_insert_ass = "INSERT INTO user_assessments (ass_id, ass_user_id, time_length, date_time, session_id) 
					VALUES ('$assid', '$uid', '00.00', NOW(), $csession_id)";
            $sql_result_ass = mysqli_query($dbc, $sql_insert_ass);
            $sum_time_rem = diffbtwTime($sum_time, $sum_time_spent);
            $sum_time_rem_sec = converttoSec($sum_time_rem);
        } else {

            if ((isset($_POST['endexam']))) {

                    $ctimespent = $_POST['timespent'];
                    $csum_time_spent = diffbtwTime($sum_time, $ctimespent); //converted time format
                    update_time($assid, $csum_time_spent);
                    $assid = test_input($_POST["assid"]);

                    update_score($assid);
                    
                    header('Location: result.php?ass=' . $assid);
                }

            $sum_time_spent = threeFieldsId('ass_id', 'ass_user_id', 'session_id', 'time_length', 'user_assessments', $assid, $uid, $csession_id);
            $sum_time_rem = diffbtwTime($sum_time, $sum_time_spent);
            $sum_time_rem_sec = converttoSec($sum_time_rem);
        }

        if ($sum_time >= $sum_time_spent) {
            //header('Location: result.php'); 
            //$sum_time_rem = $sum_time
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
                                <br> <?php echo $LName . ' ' . $FName . ' ' . $MName; ?>  </div>
                                <!--- <div class="username"> <strong>Computer Science</strong> 200L</div> -->
                            <div class="username"> <strong><?php echo $asscode; ?></strong> - <?php echo $assname; ?></div>
                        </div>   
                    </div>                
                </div> 

                <!-- /.navbar-top-links -->

                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <a href="" title="Time1" style="color:red; width:200px">
                            <h1>				
                                <span id="time1" data-value=""></span>
                            </h1>
                        </a>
                    </li>
                    <li>
                        <a href="" title="Time" style="color:white; width:200px">
                            <h1>				
                                <span id="time" data-value="<?php echo $sum_time_rem_sec; ?>"><?php echo $sum_time; ?>:00</span>
                            </h1>
                        </a>
                    </li>
                    <li><a href="#" title="Log Out" id="logout" data-toggle="modal" data-target="#logoutModal"><i class="fa fa-power-off fa-2x fa-fw"></i></a>
                </ul>
            </nav>


            <!-- /.navbar-static-top -->
            <?php //require_once('bar_sidebar.php');	 ?>
            <!-- /.navbar-static-side -->

            <div id="page-wrapper2" style="margin-top: 100px;">
                <div class="sidebar-nav-fixed affix" style="padding-top: 80px; float: left; z-index: 333;">
					
					<!--- TEST IMAGE -->
                    <div class="well">
                        <?php 
                            $testid = $_POST['testid'];
                            $query = "SELECT image FROM test_images WHERE testid = $testid AND userid = '$userid' "; 
                            $data = mysqli_query($dbc, $query);
                            $count = mysqli_num_rows($data);
                            $row = mysqli_fetch_array($data);                                 

                            if($count == 1){
                                $img = $row['image'];
                            }else{
                                $img = '../_res/img/passport/default.jpg';
                            }
                            echo '<img id="passport" src="'.$img.'" width="120" style="margin: -10px ; ">'; 
                        ?> 
                    </div>
                    <!-- END EXAM BUTTON -->
                    <div class="well">
                        <?php $showsave = nameId('id', 'save_test', 'settings', '1'); 
                                if($showsave == 1){
                             echo '<button type="button" class="btn btn-info saveexam mb20" id=""><i class="fa fa-save"></i> Save Exam</button> <br>';
                        } ?>

                             <a href="#"><button type="button" id="endexam1" class="btn btn-danger glow"><b><i class="fa fa-sign-out"></i> End Exam</b></button></a>
                    </div>
                    <!-- <div class="well">
                        <a href="#"><button type="button" id="calculator_btn" class="btn btn-info"><b><i class="fa fa-edit "></i> Calculator </b></button></a>
                    </div> -->                

                </div>	
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-10 col-md-offset-1 mt10">	

                        <div role="tabpanel" style="margin-left: 50px;">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist" id="tabb">
                                <?php
                                foreach ($rowactivets as $rowats) {
                                    $testidats = $rowats['id'];
                                    //echo check if user test exist and record if not
                                    $queryts = "SELECT * FROM user_test WHERE test_id = '$testidats' AND user_id = '$uid' ";
                                    $datats = mysqli_query($dbc, $queryts);
                                    $countts = mysqli_num_rows($datats);
                                    $rowut = mysqli_fetch_array($datats);
                                    $user_test_id = $rowut['id'];
                                    $be_default = nameId('id', 'be_default', 'tests', $testidats);

                                    if (($countts == 0) && ($be_default == 1)) {

                                    	//if not INSERT INTO usertest
                                        $sql_insert = "INSERT INTO user_test (user_id, test_id, date, time_length, user_ip, browser, time_start)
						                              VALUES ('$uid', '$testidats', NOW(),'$time:00','$ip', '$browser', CURTIME())";
                                        ;
                                        $sql_result = mysqli_query($dbc, $sql_insert);
                                        //echo $user_test_id = mysqli_insert_id($dbc);																	
                                    }

                                    $first_test = reset($rowactivets[0]);

                                    if ($testidats == $first_test) {
                                        $active = 'active';
                                    } else {
                                        $active = '';
                                    }
                                    echo'
                                    <li role="presentation" class="' . $active . '">
                                    <a href="#test' . $testidats . '" aria-controls="home" role="tab" data-toggle="tab">
                                    <b>'.$rowats['assessments_code'].' - '.(strtoupper($rowats['TName'])) . '</b></a>
                                    </li>';
                                }
                                ?>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <?php
                                $qn = 1;
                                foreach ($rowactivets as $rowats) {
                                    $countuc = 0;
                                    //TAB TEST
                                    $testidats = $rowats['id']; //active test
                                    $NOQ2 = $rowats['NOQ']; //no active test
                                    $testidatss[] = $testidats;
                                    //$utid = $rowts['id'];
                                    //echo 
                                    $queryts = "SELECT * FROM user_test WHERE test_id = '$testidats' AND user_id = '$uid' ";
                                    $datats = mysqli_query($dbc, $queryts);
                                    $countts = mysqli_num_rows($datats);
                                    $rowut = mysqli_fetch_array($datats);
                                    $user_test_id = $rowut['id'];
                                    $finish = $rowut['finish'];

                                    $show_mark = nameId('id', 'show_mark', 'tests', $testidats);
                                    $be_default = nameId('id', 'be_default', 'tests', $testidats);

                                    $datauc = mysqli_query($dbc, "SELECT id FROM user_choice WHERE user_test_id = '$user_test_id'");
                                    $countuc = mysqli_num_rows($datauc);

                                    if (($countuc == 0) && ($be_default == 1)) {

                                        //GET QUESTIONS 
                                        $dataq = mysqli_query($dbc, "SELECT * FROM questions WHERE test_id = '$testidats' ORDER BY RAND() LIMIT $NOQ2 ");

                                        while ($rowq = mysqli_fetch_array($dataq)) {
                                            $queid = $rowq['id'];
                                            //STORE QUESTIONS 
                                            $sql_insert = "INSERT INTO user_choice (user_test_id, q_id, answer) 
                                                        VALUES ('$user_test_id', '$queid', NULL)";
                                            $sql_result = mysqli_query($dbc, $sql_insert);
                                        }
                                        
                                    }

                                     //GET QUESTIONS WITH USER CHIOCE AND OPTIONS				
                                     $queryque = "SELECT  user_choice.id as ucid, user_test_id, q_id, user_choice.answer, questions.id, test_id, question, choice1, choice2, choice3, choice4   
                                                    FROM user_choice 
                                                    INNER JOIN questions ON q_id = questions.id
                                                    AND user_test_id = '$user_test_id' LIMIT $NOQ2";
                                     $dataque = mysqli_query($dbc, $queryque);


                                     //SET ACTIVE TAB
                                    if ($qn == 1) { $active = 'active'; } else { $active = '';}
                                    ?>

                                    <div role="tabpanel" class="tab-pane  <?php echo $active; ?>" id="test<?php echo $testidats; ?>">

                                        <section id="testwrap">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">

                                                    Click on your choice of answer for the following questions
                                                </div>
                                                <!-- /.panel-heading -->
                                                <div class="panel-body pbtm0">						
                                                    <div class="clear" style="clear:both">
                                                        <!-- /.table-responsive -->

                                                        <div role="tabpanel">

                                                            <!-- Tab panes -->
                                                            <div class="tab-content">

                                                                <?php
                                                                if (($finish == 0) && ($be_default == 1)) {
                                                                    $n = 1;
                                                                    while ($rowque = mysqli_fetch_array($dataque)) {
                                                                        $countque = mysqli_num_rows($dataque);

                                                                        $queid = $rowque['id'];

                                                                        $ucid = $rowque['ucid'];
                                                                        $ucids[] = $rowque['ucid'];
                                                                        $question = $rowque['question'];
                                                                        $choice1 = $rowque['choice1'];
                                                                        $choice2 = $rowque['choice2'];
                                                                        $choice3 = $rowque['choice3'];
                                                                        $choice4 = $rowque['choice4'];
                                                                        $answers[] = $rowque['answer'];
                                                                        $ans = $rowque['answer'];

                                                                        if ($n == 1) {
                                                                            $a = "active";
                                                                        } else {
                                                                            $a = "";
                                                                        }
                                                                        
                                                                        $nextucid = $ucid+1;
                                                                        ?> 

                                                                        <div role="t1" class="tab-pane <?php echo $a; ?> fade in" id="<?php echo 'ts' . $testidats . 'q' . $ucid; ?>">  
                                                                            <div class="table">
                                                                                <table class="table table-striped">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Question <?php echo $n; ?> 
                                                                                                <a href="#<?php echo 'ts' . $testidats . 'q' . ($ucid + 1); ?>" data-toggle="tab"><button type="button" class="btn btn-primary next">Next</button></a>
                                                                                                <a href="#<?php echo 'ts' . $testidats . 'q' . ($ucid - 1); ?>" data-toggle="tab"><button type="button" class="btn btn-info next">Previous</button></a>																					

                                                                                            </th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>

                                                                                        <tr>
                                                                                            <td><h4><?php echo $question; ?></h4>                                            
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <form id="form<?php echo 'ts' . $testidats . 'q' . $ucid; ?>"  method="post" action="_inc/process.php"> 
                                                                                                    <?php
                                                                                                    $ch1 = $ch2 = $ch3 = $ch4 = "";

                                                                                                    if ($ans == 1) {
                                                                                                        $ch1 = "checked";
                                                                                                    } elseif ($ans == 2) {
                                                                                                        $ch2 = "checked";
                                                                                                    } elseif ($ans == 3) {
                                                                                                        $ch3 = "checked";
                                                                                                    } elseif ($ans == 4) {
                                                                                                        $ch4 = "checked";
                                                                                                    }

                                                                                             echo  '<input type="radio"  name="choice" data-value="ts' . $testidats . 'q' . $ucid . '" ' . $ch1 . '" value="1"> ' . $choice1 . '</input> <br/>
                                                                                                    <input type="radio"  name="choice" data-value="ts' . $testidats . 'q' . $ucid . '" ' . $ch2 . ' value="2"> ' . $choice2 . '</input> <br/>
                                                                                                    <input type="radio"  name="choice" data-value="ts' . $testidats . 'q' . $ucid . '" ' . $ch3 . ' value="3"> ' . $choice3 . '</input> <br/>
                                                                                                    <input type="radio"  name="choice" data-value="ts' . $testidats . 'q' . $ucid . '" ' . $ch4 . ' value="4"> ' . $choice4 . '</input> <br/>';
                                                                                                    ?> 

                                                                                                    <input type="hidden" value="<?php echo 'ts' . $testidats . 'q' . $nextucid; ?>" name="nextq">
                                                                                                    <input type="hidden" value="<?php echo $ucid; ?>" name="ucid">
                                                                                                    <input type="hidden" value="<?php echo $queid; ?>" name="queid">
                                                                                                    <input type="hidden" value="<?php echo $user_test_id; ?>" name="user_test_id">
                                                                                                    <?php
                                                                                                    if (!empty($ans)) {
                                                                                                        $respond = '<div class="alert2 alert-success">Your Choice has been successfully submitted to the DataBase.</div>';
                                                                                                    } else {
                                                                                                        $respond = "";
                                                                                                    }
                                                                                                    echo '<div id="respondts' . $testidats . 'q' . $ucid . '" style="min-height: 30px;">' . $respond . '</div>';
                                                                                                    ?>												
                                                                                                    </td> 
                                                                                                </form>	 																																								
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>								 
                                                                        </div>

                                                                        <?php
                                                                        $n++;
                                                                    }
                                                                } elseif ($finish > '0') {
                                                                    echo '<div class="well">
                                                                            <h2 class="text-center text-danger"> <i class="fa fa-exclamation-circle fa-fw"> </i> You have participated in this Test </span></h2>																  
                                                                          </div>';

                                                                    if ($show_mark == 1) {
                                                                        ?>

                                                                        <div class="table-responsive">
                                                                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Test Title</th>
                                                                                        <th>Questions</th>
                                                                                        <th>Correct</th>
                                                                                        <th>Incorrect</th>
                                                                                        <th>Unanswered</th>
                                                                                        <th>Mark Obtainable</th>
                                                                                        <th>Score</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php view_test_score($assid, $user_test_id); ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    echo '<div class="well">
                                                                                <h2 class="text-center text-danger"> <i class="fa fa-exclamation-circle fa-fw"> </i> No record found for this Test </span></h2>
                                                                          </div>';
                                                                }
                                                                ?>

                                                            </div>

                                                        </div>
                                                    </div>


                                                </div><!-- /.panel-body -->

                                                <div class="panel-body ptop0" >
                                                    <div class="well" id="pques">
                                                        <?php
                                                        //foreach ($rowactivets as $rowats){ 
                                                        //$NOQ = $rowats['NOQ'];


                                                        $nq = 1;
                                                        if (isset($ucid)) {
                                                            foreach ($ucids as $ucid) {
                                                                $answer = nameId('id', 'answer', 'user_choice', $ucid);
                                                                if (empty($answer) || empty($ucid)) {
                                                                    echo '<a href="#ts' . $testidats . 'q' . $ucid . '" data-toggle="tab"><button id="pts' . $testidats . 'q' . $ucid . '" type="button" class="btn btn-default btnpg">' . $nq . '</button></a>';
                                                                } else {
                                                                    echo '<a href="#ts' . $testidats . 'q' . $ucid . '" data-toggle="tab"><button id="pts' . $testidats . 'q' . $ucid . '" type="button" class="btn btn-primary btnpg">' . $nq . '</button></a>';
                                                                }
                                                                $nq++;
                                                            }
                                                        }
                                                        unset($ucids);
                                                        $ucids = array();
                                                        ?>													
                                                    </div>
                                                </div>                   
                                            </div><!-- /.panel -->
                                        </section>
                                    </div>
                                    <?php $qn++;
                                } $testidatss[] = "";
                                ?>
                            </div>

                        </div>

                         <!-- //  Admin Logout exam modal --> 
                        <div class="modal fade" id="adminlogoutModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content" style="margin-top:160px  ">
                                    <div class="modal-body text-center">
                                        <h3> You have been logged Out from the Admin</h3>
                                    </div>
                                </div>
                            </div>
                        </div><!-- // Admin Logout modal end-->

                        <!-- //  Logout exam modal --> 
                        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body text-center">
                                        <h3> Are you sure you want to LOGOUT ? </h3>
                                        <!--  <p><b>NOTE:</b> Please make sure you have <b>saved your test</b> before logging out</p>         -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal" ><i class="fa fa-times"></i> Cancel </button>
                                        <a href="logout.php"><button type="submit" name="endexam" class="btn btn-danger" ><i class="fa fa-power-off"></i> LOGOUT</button> </a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- //  Logout modal end-->

                        <!-- //  Endexam1 modal --> 
                        <div class="modal fade" id="showunanswerdModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-sign-out"></i>  You are about to End Exam? </h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                                <thead>
                                                    <tr>
                                                        <th>Test Title</th>
                                                        <th>Questions</th>
                                                        <th>Unanswered</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="showunanswerd">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="" method="post">																				
                                            <button type="button" class="btn btn-default" data-dismiss="modal" ><i class="fa fa-times"></i> Cancel </button>
                                            <button type="submit" name="endexam" class="btn btn-danger"><b><i class="fa fa-sign-out"></i> END EXAM</b></button>
                                    </div>
                                </div>
                            </div>
                        </div><!-- //  modal end1--> 

                        <!-- //  End exam modal --> 
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog ">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-sign-out"></i>  Are you sure you want to End Exam? </h4>
                                    </div>
                                    <div class="modal-body">
                                        <p> Click on End Exam If you are sure you are through. </p>
                                        <p><b>NOTE:</b> This Action is not reversible.</p>         
                                    </div>
                                    <div class="modal-footer">
                                        <form action="" method="post">																				
                                            <input type="hidden" value="<?php echo $assid; ?>" name="assid">
                                            <input type="hidden" id="timespent" value="<?php echo $sum_time_rem; ?>" name="timespent">
                                            <button type="button" class="btn btn-default" data-dismiss="modal" ><i class="fa fa-times"></i> Cancel </button>
                                            <a href="#"><button type="button" class="btn btn-warning " id="confrmEndExam"> <b><i class="fa fa-sign-out"></i>  CONFIRM END EXAM </b></button></a><br>										</form>
                                        </form>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                        </div><!-- //  modal end-->  

                        <!-- //  time out modal --> 
                        <div class="modal pt100 fade" id="timeoutModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <h2 class="text-center text-danger"> <span id="endmsg"></span> <span id="duration"> </span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- //  modal end--> 						

                    </div>
                </div>
                <!-- /.row -->
            </div>
             

<?php /// require_once('footer_bar.php');	  ?>
            <!-- /#page-wrapper -->
        </div>

    </div>
</div>
</div><!-- /#wrapper -->


<!-- Core Scripts - Include with every page -->
<!-- <script src="calc/js/bliss.min.js"></script>
<script src="calc/js/index.js"></script> -->
<?php  require_once('../_inc/inc_js.php'); ?>


<form id="sumform">
    <input type="hidden" data-value="<?php echo $uid; ?>" name="userid">
    <input type="hidden" value="timeout" name="timeout">
    <input type="hidden" value="<?php echo $sum_time_str; ?>" id="sum_time" name="sum_time">
    <input type="hidden" value="<?php echo $assid; ?>" id="assid" name="assid">
    <input type="hidden" value="<?php echo $url . '/students/result.php?ass=' . $assid; ?>" id="result">
</form>	


<script>
    var ans_arr = {};
        
    if (localStorage.getItem('ans_arr')) {
        ans_arr = JSON.parse(localStorage.getItem('ans_arr'));
    } else {
        ans_arr = {};
    }

    var err_ans_arr = {};
    var err_ans_arr_p = {};

    if (localStorage.getItem('err_ans_arr')) {
        err_ans_arr = JSON.parse(localStorage.getItem('err_ans_arr'));
        err_ans_arr_p = JSON.parse(localStorage.getItem('err_ans_arr_p'));
    } else {
        err_ans_arr = {};
        err_ans_arr_p = {};
    }

    function sectoTime(secc) {
        var timer = secc, minutes, seconds;

            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            var timm = minutes + ":" + seconds;
            return timm;         
    }

    function timetoSec(tim){
        var a = tim.split(':'); // split it at the colons

        // minutes are worth 60 seconds. Hours are worth 60 minutes.
        var seconds = ((+a[0]) * 60 + (+a[1])); 
        return seconds;
    }
     
    function usedtime(){
     //       var qctime = $('#time span').text();
        var qctime = document.getElementById("timespent").value;
        var qctimeSec = timetoSec(qctime);
         //alert(qctimeSec);
         
        var ttime = document.getElementById("sum_time").value;
        var ttimeSec = timetoSec(ttime);
        //alert(ttimeSec);
        var usedtime = ttimeSec - qctimeSec;
        //alert(usedtime);

        var usedtimeformat = sectoTime(usedtime);
         //alert(usedtimeformat);
        return usedtimeformat;
    }
    
    var usedtimeformat  = usedtime();
    
    //    setInterval(function () {
    //    var usedtimeformat2  = usedtime();    
    //   // var loc_time = localStorage.setItem("loctime", usedtimeformat2);
    //        }, 1000);

    $("input[name='choice']").click(function () {

        var queNo = $(this).data('value');
        var nextqueNo = $(this).data('next');
        var nextq = $(this).siblings("input[name='nextq']").val();

        // var TimeInterval for Time Up to Check Server
        var TimeInterval;

        //alert('#'+nextucid);
        var formID = $('#form' + queNo);
        //alert(formID);

        var usedtimeformat  = usedtime();
        //alert(usedtimeformat);

        var assid = document.getElementById("assid").value;

        $(formID).append('<input type="hidden" name="usedtime" value="' + usedtimeformat + '" />');
        $(formID).append('<input type="hidden" name="assid" value="' + assid + '" />');

        var ucid = $(this).siblings("input[name='ucid']").val();
        ans_arr[ucid] = $(this).val();
        var val = $(this).val();

        // Put the object into storage
        localStorage.setItem('ans_arr', JSON.stringify(ans_arr));

        $.ajax({
            type: "POST",
            url: "_inc/process.php",
            data: $(formID).serialize(),
            //data: { id: $(this).data('value') },
            success: function (msg) {
                if (msg.indexOf("successfully") > -1) {
                    $('#p' + queNo).removeClass("btn-danger");
                    $('#p' + queNo).addClass("btn-primary");
                    $('#respond' + queNo).html(msg);
                    setTimeout(function () { $('#pques a[href="#' + nextq + '"]').tab('show');   }, 1000);
                    //$(this).next().tab('show');
                        savererror();
                } else {
                    err_ans_arr[ucid] = val;
                    localStorage.setItem('err_ans_arr', JSON.stringify(err_ans_arr));

                    err_ans_arr_p[queNo] = val; //KEEP PAGENATION ID
                    localStorage.setItem('err_ans_arr_p', JSON.stringify(err_ans_arr_p));

                    $('#p' + queNo).removeClass("btn-primary");
                    $('#p' + queNo).addClass("btn-danger");
                    $('#respond' + queNo).html('<div class="alert2 alert-danger">Choice can NOT be submitted. Continue with your work</div>');       
                    setTimeout(function () { $('#pques a[href="#' + nextq + '"]').tab('show');   }, 1000);
                }
            },
            error: function (exception) {              
                            
                err_ans_arr[ucid] = val;
                localStorage.setItem('err_ans_arr', JSON.stringify(err_ans_arr));

                err_ans_arr_p[queNo] = val; //KEEP PAGENATION ID
                localStorage.setItem('err_ans_arr_p', JSON.stringify(err_ans_arr_p));

                $('#p' + queNo).removeClass("btn-primary");
                $('#p' + queNo).addClass("btn-danger");
                $('#respond' + queNo).html('<div class="alert2 alert-danger">Choice can NOT be submited now. Continue with your work</div>');
          
                setTimeout(function () {
                            $('#pques a[href="#' + nextq + '"]').tab('show');
                            }, 1000);  
            }
        });
    });
    
    function savererror(){
           if (localStorage.getItem('err_ans_arr')){ //store error
               err_ans_arr = JSON.parse(localStorage.getItem('err_ans_arr'));
               $.ajax({
                   type: "POST",
                   url: "_inc/process.php",
                   data: {answers: err_ans_arr},
                   //dataType: "json", 
                   success: function (data) {
                       //$('#respond'+queNo).html(msg);
                       localStorage.removeItem('err_ans_arr', JSON.stringify(err_ans_arr));

                       for (var index in err_ans_arr_p) {
                           ///alert(index);
                           $('#p' + index).removeClass("btn-danger");
                           $('#p' + index).addClass("btn-primary");
                           $('#respond' + index).html('<div class="alert2 alert-success">Your Choice has been successfully submitted to the DataBase.</div>');
                       }
                       localStorage.removeItem('err_ans_arr_p', JSON.stringify(err_ans_arr));

                       saveloading();
                   },
                   error: function (exception) {
                       alert('error');
                   }
               });
           } else {
               err_ans_arr = {};
           }
       }
       
    function saveexam(){
        $.ajax({
            type: "POST",
            url: "_inc/process.php",
            data: {answers: ans_arr},
            //dataType: "json", 
            success: function (data) {
                for (var index in err_ans_arr_p) {
                    //alert(index);
                    $('#p' + index).removeClass("btn-danger");
                    $('#p' + index).addClass("btn-primary");
                    $('#respond' + index).html('<div class="alert2 alert-success">Your Choice has been successfully submitted to the DataBase.</div>');
                }
                localStorage.removeItem('err_ans_arr_p', JSON.stringify(err_ans_arr));
                saveloading();
            },
            error: function (exception) {
                //alert('error connecting to server');
            }
        });
    };
     
    $('.saveexam').on('click', function () {
        saveexam();
        });

    $('#logout').on('click', function () {
        saveexam();
      });

    $('#calculator_btn').on('click', function () {
                $('#calculator_modal').modal('show');
      });
      
    var result = document.getElementById("result").value;
    function startTimer(duration, display, display2) {
            var timer = duration, minutes, seconds;
            var time = $('#sum_time').val();
            var time = timetoSec(time);
            var waiting = 1000;
            
            var alerttime = (((time * 25) / 100));
            var redalerttime = (((time * 10) / 100));
           // alert(time);
            //alert(redalerttime);
            var loc_time;
            
             TimeInterval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
       
                loc_time = localStorage.setItem("loctime", minutes + ":" + seconds);

                display.textContent = minutes + ":" + seconds;
                display2.value = minutes + ":" + seconds;
                //
                if (timer > 0) {
                    if (timer < alerttime) {
                        $('#time').addClass("blink_me");
                        if (timer < redalerttime) {
                            $('#time').addClass("red");
                        }
                    }
                    --timer;
                } else {
                    var endmsg = '<i class="fa fa-clock-o fa-fw"> </i> Your Time is Up';
                    checkServerEndExam(endmsg);
                    
                }
            }, waiting);
        }

        window.onload = function () {
            if (localStorage.getItem('loctime')) {
                var usedtimeformat = localStorage.getItem('loctime');
                var time = timetoSec(usedtimeformat);
            }else{
            var time = $('#time').data('value');
            }//alert(time);
            
            var timeLength = time,
                    //var fiveMinutes = 60 * 10,
                    display = document.querySelector('#time');
            display2 = document.querySelector('#timespent');
            startTimer(timeLength, display, display2);
        };

        $(document).ready(function ()
        {
            $("#passport").error(function () {
                $(this).attr('src', '../_res/img/passport/default.jpg');
            });
        });

    function checkServerEndExam(endmsg){
         $('#endmsg').html(endmsg);
     
         $('#timeoutModal').modal({backdrop: 'static', keyboard: false});                

         $.ajax({
            type: "POST",
            url: "_inc/process.php",
            data: $('#sumform').serialize(),
            success: function (msg) {
                if (msg.indexOf("Unable") > -1) { //unable to connect                             
                        $('#duration').html(msg);

                }else{//connected to database
                  clearInterval(TimeInterval); //stop checking server
                  savererror();
                  setTimeout(function (){ $('#serverstatus').html(msg); }, 200);                          
                  setTimeout(function (){ $('#serverstatus').html('<div class="alert2 alert-info">Saving exam...</div>'); }, 800);
                  setTimeout(function (){ window.location.replace(result); }, 1200); 
              }
            },

            error: function (exception) {
                $('#duration').html('<div id="serverstatus"><div class="alert2 alert-danger blink_me">Unable to connect to Server.</div></div>');
            }
        });
    };
    
    function servererror(){
        setTimeout(function () {
            var notify = $.notify('<strong>Saving</strong> Do not close this page...', {
            type: 'danger'
        });

            notify.update('message', '<strong>Please</strong> Question Data.');
        }, 100);
      }
    function saveloading(){
        var notify = $.notify('<strong>Saving</strong> Do not close this page...', {
            type: 'info',
            allow_dismiss: false,
            showProgressbar: false,
            timer: 500
        });

        setTimeout(function () {
            notify.update('message', '<strong>Please</strong> Question Data.');
        }, 500);

        setTimeout(function () {
            notify.update('message', '<strong>Saving</strong> Answer Data.');
        }, 1000);

        setTimeout(function () {
            $.notifyClose('top-right');
        }, 1000);
    };

    $('#endexam1').on('click', function () {
        //servererror();
        $('#myModal').modal('show');
    });
    $('#confrmEndExam').on('click', function () {
        $('#myModal').modal('hide');
        var assid = document.getElementById("assid").value;
        $.ajax({
            type: "POST",
            url: "_inc/process.php",
            data: {assid: assid, endexam1: 1},
            success: function (msg) {
                  if (msg.indexOf("Unable") > -1) { //unable to connect                             
                        var endmsg = 'Waiting for server connection';
                        setInterval(function (){ checkServerEndExam(endmsg); }, 1000);                                                 
                    }else{//connected to database
                $('#showunanswerd').html(msg);
                $('#showunanswerdModal').modal({backdrop: 'static', keyboard: false});                
                }
            },
             error: function (exception) {
                        var endmsg = 'Waiting for server connection';
                        setInterval(function (){ checkServerEndExam(endmsg); }, 1000);                                                 
                    }
        });               
    });

    window.onbeforeunload = function(e){
        //    //Do some thing here
        //           // var usedtime = usedtime();
        //
        //       display = document.querySelector('#time1').textContent = '2222';
        //        $.ajax({
        //            type: "POST",
        //            url: "_inc/process.php",
        //           // data: {assid: assid, used_time: usedtime, reload: 1},
        //            success: function (msg) {
        //            }
        //        });
    };
        
    function timeout(){           
        $('#timeoutModal').modal({backdrop: 'static', keyboard: false});
        saveexam();

        $.ajax({
            type: "POST",
            url: "_inc/process.php",
            data: $('#sumform').serialize(),
            //data: { id: $(this).data('value') },
            success: function (msg) {
                $('#duration').html(msg);
                setTimeout(function () {
                    window.location.replace(result);
                }, 2000);
            }
        });
    };           
                 //   $('#tabb li:eq(1) a').tab('show');


function checklogout(){
    var userid = $("input[name='userid']").data('value');
                $.ajax({
                    type: "POST",
                    url: "../students/_inc/process.php",
                    data: {checklogout: 'checklogout', user_id: userid  },
                    success: function(data) {
                           if(data != 1){                   
                                setTimeout(function () {
                                    $('#adminlogoutModal').modal({backdrop: 'static', keyboard: false});
                                    window.location.replace('logout.php');
                                }, 2000);
                           }
                        }
                    });
                };

setInterval(function() { checklogout(); }, 5000);   
</script> 
                               
</body>

</html>