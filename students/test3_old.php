<!DOCTYPE html>
<html>

    <head>
        <link rel="stylesheet" href="calc/css/style.css">
          <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
          <script>
          MathJax = {
            tex: {inlineMath: [['$', '$'], ['\\(', '\\)']]}
          };
          </script>
          <script id="MathJax-script" async src="../es5/tex-chtml.js"></script>


</head>

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
            $testid1 = $_POST['testid'];
            $sum_time = 0;
            $sum_time_spent = 0;


            //RESET TEST TIME 
            // user has record in user-assessment before a record in test?

           $queryts = "SELECT * FROM user_test WHERE test_id = '$testid1' AND user_id = '$uid' "; //echo $queryts; die(); 
            $datats = mysqli_query($dbc, $queryts);
            $countts = mysqli_num_rows($datats);

            //echo $countts; die();
            if($countts == 0){
                $sql = "UPDATE user_assessments SET time_length = '00:00'  WHERE ass_id = '$assid' AND ass_user_id = '$uid'";
                 $data = mysqli_query($dbc, $sql);
            }

            //GET POSTED TESTS  
           $querytest = " SELECT ts.id, TName, time, NOQ, random, minus_mark, be_default, assessments_id, assessments_name, assessments_code, session_id 
                   FROM tests ts, assessments ass
                   WHERE ts.assessments_id = ass.id 
                   AND session_id = '$csession_id' 
                   AND assessments_id = '$assid'
                   AND be_default = 1";   //echo $querytest; die(); 
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
                @$sum_time += $rowats['time'];
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
                    //header('Location: result.php?ass=' . $assid);
                }


                //MIGRATE USER CHOICE TO BACKUP
                // $migrate_sql =  "SELECT 
                //          INTO CustomersOrderBackup2017
                //          FROM Customers
                //          LEFT JOIN Orders ON Customers.CustomerID = Orders.CustomerID";
                // $datats = mysqli_query($dbc, $migrate_sql);

                // print_r($migrate_sql); die;




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
            // print_r($sql_insert_ass); die;


            $sql_result_ass = mysqli_query($dbc, $sql_insert_ass);
            $sum_time_rem = diffbtwTime($sum_time, $sum_time_spent);
            $sum_time_rem_sec = converttoSec($sum_time_rem);
        } else {

            if ((isset($_POST['endexam']))) {
                    
                    $ctimespent = $_POST['timespent'];
                    $csum_time_spent = diffbtwTime($sum_time, $ctimespent); //converted time format
                    update_time($assid, $csum_time_spent);
                    $assid = test_input($_POST["assid"]);

                    $calc_score = nameId('id', 'calc_score', 'settings', 1);
                    //echo $calc_score; die;
                    if($calc_score == '1'){
                       update_score($assid); 
                    }
                   // update_score($assid);
                    
                    header('Location: result.php?ass=' . $assid);
                }

            $sum_time_spent = threeFieldsId('ass_id', 'ass_user_id', 'session_id', 'time_length', 'user_assessments', $assid, $uid, $csession_id);

            //echo $countua; die();

            
            $sum_time_rem = diffbtwTime($sum_time, $sum_time_spent);
            $sum_time_rem_sec = converttoSec($sum_time_rem);
            //print_r($sum_time_rem); die();
        }

        if ($sum_time >= $sum_time_spent) {
            //header('Location: result.php'); 
            //$sum_time_rem = $sum_time
        }
        ?>

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

            $id = $uid.'-'.$testidats;

            //if not INSERT INTO usertest
            $sql_insert = "INSERT INTO user_test (id, user_id, test_id, date, time_length, user_ip, browser, time_start)
                          VALUES ('$id','$uid', '$testidats', NOW(),'$time:00','$ip', '$browser', CURTIME())";
            // print_r($sql_insert); die();
            $sql_result = mysqli_query($dbc, $sql_insert);
            //echo $user_test_id = mysqli_insert_id($dbc);                                                                  
        }

        $first_test = reset($rowactivets[0]);

        if ($testidats == $first_test) {
            $active = 'active';
        } else {
            $active = '';
        }
    }
    ?>

<?php
    //$qn = 1;
    foreach ($rowactivets as $rowats) {
        $countuc = 0;
        //TAB TEST
        $testidats = $rowats['id']; //active test
        $NOQ2 = $rowats['NOQ']; //no active test
        $testidatss[] = $testidats;
        //$utid = $rowts['id'];
       
        $queryts = "SELECT * FROM user_test WHERE test_id = '$testidats' AND user_id = '$uid' ";
        $datats = mysqli_query($dbc, $queryts);
        $countts = mysqli_num_rows($datats);
        $rowut = mysqli_fetch_array($datats);
        $user_test_id = $rowut['id'];
        $finish = $rowut['finish'];

        $show_mark = nameId('id', 'show_mark', 'tests', $testidats);
        $be_default = nameId('id', 'be_default', 'tests', $testidats);

        $q1 = "SELECT user_test_id FROM user_choice WHERE user_test_id = '$user_test_id'";
        $datauc = mysqli_query($dbc, $q1);
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
        $queryque = "SELECT  user_choice.id as ucid, user_test_id, user_choice.q_id, user_choice.answer, questions.id, test_id, question, choice1, choice2, choice3, choice4   
                        FROM user_choice 
                        INNER JOIN questions ON user_choice.q_id = questions.id
                        AND user_test_id = '$user_test_id' LIMIT $NOQ2";
            
         $dataque = mysqli_query($dbc, $queryque);

    }    
    ?>

<?php 
        $testid = $_POST['testid'];
        $query = "SELECT image FROM test_images WHERE testid = '$testid' AND userid = '$userid' "; 
        $data = mysqli_query($dbc, $query);
        $count = mysqli_num_rows($data);
        $row = mysqli_fetch_array($data);                                 

        if($count == 1){
            $img = $row['image'];
        }else{
            $img = '../_res/img/passport/default.jpg';
        } 
?>

    <body>

        <div id="wrapper">

            <nav class=" navbar navbar-default navbar-fixed-top" role="navigation">
                <!-- /.navbar-header -->

                <div class="navbar-header2 navbar-header">
                    <div class="titleprofilebar" style=""> 
                        <div class="user-info-profile-image2 passport">
                            <img id="passport" src="../_files/_passport/<?php echo $_SESSION['image']; ?>"
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
                    <li>
                    <?php if(1 == 1){?>
                    <a href="#" title="Logout" id='logout' style="color: red">
                     <img src="../_res/img/logout.png" width="60" height="60"   />
                    </a>
                    <?php }?>
                </ul>
            </nav>


            <!-- /.navbar-static-top -->
            <?php //require_once('bar_sidebar.php');     ?>
            <!-- /.navbar-static-side -->

            <div id="page-wrapper2" style="margin-top: 100px;">
                <div class="sidebar-nav-fixed affix" style="padding-top: 80px; float: left; z-index: 333;">
                    
                    <!--- TEST IMAGE -->
                    <div class="well">
                       
                        <?php 
                            echo '<img id="passport" src="'.$img.'" width="120" style="margin: -10px ; ">'; 
                        ?> 
                    </div>
                    <!-- END EXAM BUTTON -->
                    <div class="well">
                        <?php $showsave = nameId('id', 'save_test', 'settings', '1'); 
                                if($showsave == 1){
                             echo '<button type="button" class="btn btn-info saveexam mb20" id=""><i class="fa fa-save"></i> Save Exam</button> <br>';
                        } ?>

                             <a href="#"><button type="button" id="endexam1" class="btn btn-danger"><b><i class="fa fa-sign-out"></i> End Exam</b></button></a>
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
                                <?php foreach ($rowactivets as $rowats) { 

                                    echo'
                                    <li role="presentation" class="' . $active . '">
                                    <a href="#test' . $testidats . '" aria-controls="home" role="tab" data-toggle="tab">
                                    <b>'.$rowats['assessments_code'].' - '.(strtoupper($rowats['TName'])) . '</b></a>
                                    </li>';
                                } ?>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                             <?php
                                //$qn = 1;
                                foreach ($rowactivets as $rowats) { ?>
                                    

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
                                                                                       
                                                                                            <td><h4><?php echo $question; ?></h4><td>                                           
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

                                                                                             echo  'A. <input type="radio"  name="choice" data-value="ts' . $testidats . 'q' . $ucid . '" ' . $ch1 . ' value="1"> ' . $choice1 . '</input> <br/>
                                                                                                    B. <input type="radio"  name="choice" data-value="ts' . $testidats . 'q' . $ucid . '" ' . $ch2 . ' value="2"> ' . $choice2 . '</input> <br/>
                                                                                                    C. <input type="radio"  name="choice" data-value="ts' . $testidats . 'q' . $ucid . '" ' . $ch3 . ' value="3"> ' . $choice3 . '</input> <br/>
                                                                                                    D. <input type="radio"  name="choice" data-value="ts' . $testidats . 'q' . $ucid . '" ' . $ch4 . ' value="4"> ' . $choice4 . '</input> <br/>';
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

                                    <?php //$qn++;
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
                                            <table class="table table-striped table-bordered table-hover" >
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
                                            <a href="#"><button type="button" class="btn btn-warning " id="confrmEndExam"> <b><i class="fa fa-sign-out"></i>  CONFIRM END EXAM </b></button></a><br>                                        </form>
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
             

            <?php /// require_once('footer_bar.php');     ?>
            <!-- /#page-wrapper -->
                </div>

                </div>
            </div>
        </div><!-- /#wrapper -->
<?php mysqli_close($dbc); ?>

<!-- Core Scripts - Include with every page -->
<!-- <script src="calc/js/bliss.min.js"></script>
<script src="calc/js/index.js"></script> -->
<?php  require_once('../_inc/inc_js.php'); ?>


<form id="sumform">
    <input type="hidden" data-value="<?php echo $uid; ?>" name="userid" id='userid'>
    <input type="hidden" value="timeout" name="timeout">
    <input type="hidden" value="<?php echo $sum_time_str; ?>" id="sum_time" name="sum_time">
    <input type="hidden" value="<?php echo $assid; ?>" id="assid" name="assid">
    <input type="hidden" value="<?php echo $url . '/students/result.php?ass=' . $assid; ?>" id="result">
</form> 

<script src="test.js"></script>
                               
</body>

</html>