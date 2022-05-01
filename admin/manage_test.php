<!DOCTYPE html>
<html>
    <head>
        <?php
        $dir = basename(__DIR__);
        
        if (!function_exists("GetSQLValueString")) {
            function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
            { 
                if (PHP_VERSION < 6) {
                    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
                }

                $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

                switch ($theType) {
                  case "text":
                    $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                    break;    
                  case "long":
                  case "int":
                    $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                    break;
                  case "double":
                    $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                    break;
                  case "date":
                    $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                    break;
                  case "defined":
                    $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                    break;
                }
                return $theValue;
            }
        }
        
        $pgname = "Add Test";
        require_once('../_inc/inc_head.php');
        require_once ('../_inc/phpexcel/PHPExcel/IOFactory.php');
        ?>	</head>

    <body>

        <div id="wrapper">

            <?php require_once('_inc/inc_topnav.php'); ?>
            <?php require_once('_inc/inc_sidebar.php'); ?>
            <?php
            if (isset($_GET['ass'])) {
                $ass_id = $_GET['ass'];
            }

            $post_url = $_SERVER['PHP_SELF'].'?ass='.$ass_id;
            //$msg = '';
            
            $ass_code = nameId('id', 'assessments_code', 'assessments', $ass_id);
            $ass_name = nameId('id', 'assessments_name', 'assessments', $ass_id);
            
            if (isset($_POST["import_test"])) {
                
                $params = $_POST;
                $test_id = $params['test_id'];
                $ass_id = $params['assess_id'];
                
                if(isset($test_id) && $test_id > 0) {                    
                    $type = ['text/comma-separated-values', 
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                            'application/vnd.ms-excel'
                    ];

                    if(is_uploaded_file($_FILES['filename']['tmp_name']) && in_array($_FILES['filename']['type'], $type)) {
                        //Import uploaded file to Database	
                        $objPHPExcel = PHPExcel_IOFactory::load($_FILES['filename']['tmp_name']);
                        $objWorkSheet = $objPHPExcel->getActiveSheet();
                        $objIterator = $objWorkSheet->getRowIterator();

                        unset($objPHPExcel);
                        unset($objWorkSheet);
                        
                        $insert_row = 0;
                        $rows = [];
                        
                        foreach($objIterator as $key => $row) {                            
                            
                            $question = (string)$row->getColumnValue(0)->getValue();            
                            $ans1 = (string)$row->getColumnValue(1)->getValue();
                            $ans2 = (string)$row->getColumnValue(2)->getValue();           
                            $ans3 = (string)$row->getColumnValue(3)->getValue();
                            $ans4 = (string)$row->getColumnValue(4)->getValue();
                            $right = (int)$row->getColumnValue(5)->getValue();
                            
                            
                            
                            $sql_insert = sprintf("INSERT INTO questions (test_id, question, choice1, choice2, choice3, choice4, answer) 
                                                VALUES (%s, %s, %s, %s, %s, %s, %s)", 
                                                GetSQLValueString($test_id, "text"), 
                                                GetSQLValueString($question, "text"), 
                                                GetSQLValueString($ans1, "text"), 
                                                GetSQLValueString($ans2, "text"), 
                                                GetSQLValueString($ans3, "text"), 
                                                GetSQLValueString($ans4, "text"), 
                                                GetSQLValueString($right, "int"));
                            $sql_result = mysqli_query($dbc, $sql_insert);
                            
                            If($sql_result)  {
                                $insert_row++;
                            }else {
                                $rows[] = $key;
                            }

                        }

                        if(!empty($rows)) {
                            $length = count($rows);
                           // echo $msg = "{$insert_row} questions were uploaded successfully and {$length} questions "
                           // . "failed to upload!<br/>The following rows"
                           // . " failed to upload: [".implode(', ', $rows)."]";
                        }else {
                             $msg = "{$insert_row} questions uploaded successfully";
                        }
                        
                        unset($objIterator);
                    } // end is_uploaded_file
                }// end test_id check
            } // end import_test 

           
            if (isset($_POST["import_users"])) {
                
                $params = $_POST;
                 $ass_id = $params['assess_id'];
                
                    $type = ['text/comma-separated-values', 
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                            'application/vnd.ms-excel'];

                    if(is_uploaded_file($_FILES['filename']['tmp_name']) && in_array($_FILES['filename']['type'], $type)) {
                        //Import uploaded file to Database	
                        $objPHPExcel = PHPExcel_IOFactory::load($_FILES['filename']['tmp_name']);
                        $objWorkSheet = $objPHPExcel->getActiveSheet();
                        $objIterator = $objWorkSheet->getRowIterator();

                        unset($objPHPExcel);
                        unset($objWorkSheet);
                        
                        $insert_row = 0;
                        $rows = [];
                        $count_err = 0;
                        
                        foreach($objIterator as $key => $row) {  
                            
                            $batch = (string)$row->getColumnValue(0)->getValue();            
                            $username = (string)$row->getColumnValue(1)->getValue();
                            
                             $sql_select = "SELECT * FROM assessments_users where username = '$username' AND assessment_code = '$ass_code' AND session_id = $csession_id";
                           $countusas =  countSQL($sql_select, $dbc);
                           
                            
                            if ($countusas == 0){
                                $sql_insert = sprintf("INSERT INTO assessments_users (assessment_code, username, session_id, batch) 
                                                    VALUES (%s, %s, %s, %s)", 
                                                    GetSQLValueString($ass_code, "text"), 
                                                    GetSQLValueString($username, "text"),  
                                                    GetSQLValueString($csession_id, "text"),  
                                                    GetSQLValueString($batch, "int"));

                                $sql_result = mysqli_query($dbc, $sql_insert);

                                If($sql_result)  {
                                    $insert_row++;
                                }else {
                                    $rows[] = $key;
                                }
                            }  else {
                                 $count_err = $count_err +1;
                            }
                        }

                        if(!empty($rows)) {
                            $length = count($rows);
                            $msg = "{$insert_row} users were uploaded successfully and {$length} questions "
                           . "failed to upload!<br/>The following rows"
                           . " failed to upload: [".implode(', ', $rows)."]";
                           
                        }else {
                              $msg = "{$insert_row} users uploaded successfully";
                        }
                        $alert = ['msg' => $msg,
                                'type' => 'success'];
                        unset($objIterator);
                    } // end is_uploaded_file
            } // end import_test 
            
            if (isset($_POST["add_test"])) {
                //print_r($_POST);
                $TName = test_input(strtoupper($_POST["TName"]));
                $NOQ = test_input($_POST["NOQ"]);
                $start_message = test_input($_POST["start_message"]);
                $end_message = test_input($_POST["end_message"]);
                $time = test_input($_POST["time"]);
                $test_mark = test_input($_POST["test_mark"]);
                $RandomQ = test_input($_POST["RandomQ"]);
                $test_type = test_input($_POST["test_type"]);
                $ShowAns = test_input($_POST["ShowAns"]);
                $ShowMark = test_input($_POST["ShowMark"]);
                $ShowRank = test_input($_POST["ShowRank"]);
                $prof_or_user = test_input($_POST["prof_or_user"]);
                $active = test_input($_POST["active"]);
                $be_default = test_input($_POST["be_default"]);
                $assessment_id = $ass_id;

                $test_id = test_input($_POST["test_id"]);

                if($test_id > 0){
                    
                    $sql_update = "UPDATE tests SET TName = '$TName', NOQ = '$NOQ', be_default = $be_default, prof_or_user = $prof_or_user, random = $RandomQ, time = '$time:00', test_type = '$test_type', show_answers = '$ShowAns', show_mark ='$ShowMark', active ='$active', start_message='$start_message', end_message = '$end_message', show_rank='$ShowRank', test_mark = '$test_mark'
                        WHERE id = $test_id
                    ";
                   // echo $sql_update;
                    $sql_result = mysqli_query($dbc, $sql_update);
                    if ($sql_result) {
                            $msg = "Test Updated Successfully";
                        } else {
                            $msg = "Test Updated Error";
                        }
                }else{
                       $sql_insert = "INSERT INTO tests (TName, NOQ, be_default, prof_or_user, random, time, test_type, show_answers, show_mark, active, start_message, end_message, show_rank, test_mark, assessments_id ) 
                                   VALUES( '$TName', '$NOQ','$be_default', '$prof_or_user', '$RandomQ', '$time:00', '$test_type', '$ShowAns',  '$ShowMark', '$active', '$start_message', '$end_message',  '$ShowRank', '$test_mark', '$assessment_id')";
                    $sql_result = mysqli_query($dbc, $sql_insert);
                    if ($sql_result) {
                        $msg = "Test Added Successfully";
                    } else {
                        $msg_err = mysqli_error($dbc);
                    } 
                }

                


                
            }
            
            if(isset($_POST['upload_multy_student'])){
    
                $userid = explode("\n", $_POST['userid']);
                
                    $uploaded = TRUE;
                    $insert_row = 0;
                    $count_err = 0;
                    
                    for($idx = 0; $idx < count($userid); $idx++){
                        $useridd = test_input($userid[$idx]);
                             $sql_select = "SELECT * FROM assessments_users where username = '$useridd' AND assessment_code = '$ass_code' AND session_id = $csession_id";
                            $countusas =  countSQL($sql_select, $dbc);
                           
                        if ($countusas == 0){   
                            $prep_query = sprintf("INSERT INTO assessments_users (username,  batch, session_id, assessment_code) "
                                                . "VALUES ('%s','%s', '%s', '%s')", 
                                                test_input($userid[$idx]),
                                                test_input($_POST['batch']),
                                                test_input($csession_id),
                                                test_input($ass_code));
                            $users = mysqli_query($dbc, $prep_query) or die(mysqli_error($dbc));

                            if($users){
                                $insert_row++;
                            }else{
                                $insert_error[] =  $userid[$idx];
                                $uploaded = false;
                            }
                        }  else {
                                 $count_err = $count_err +1;
                            }
                    }// end of for loop

                    //if uploaded successfully 
                    if( $uploaded ){
                        $uploadstat = "Upload Successful! ".$insert_row."  students uploaded.";
                    }else{
                        $uploadstat = "<p style='color :red'>Upload Unsuccessful! The following tudents could not be uploaded:<br/>";

                        foreach( $insert_error as $error){
                            $uploadstat .= $error."<br/>";
                        }
                    }
                    $msg = $uploadstat;
            }

            if (isset($_POST["test_status"])) {
                //print_r($_POST);
                $test_status = test_input($_POST["test_status"]);
                $test_id = test_input($_POST["test_id"]);
           
                $sql_update = "UPDATE tests SET be_default = $test_status, active = $test_status WHERE id = $test_id";
                $sql_result = mysqli_query($dbc, $sql_update);
                if ($sql_result) {
                    if($test_status == 1){
                        $msg = "Test Activated Successfully";
                    }else{
                        $msg = "Test Deactivated Successfully";
                    }
                    
                } else {
                    $msg_err = mysqli_error($dbc);
                }
            }

             if (isset($_POST["reset_user"])) {
                //get test id
                //get user_test id 
                //get list of user_choice 

                // delete user_chioces  with user_test id 
                
                // delete user_id id 
            }

            ?>
            <!-- /.navbar-static-side -->

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="page-header" id='assid' data-code='<?php echo $ass_code; ?>' data-value='<?php echo $ass_id; ?>'>View Assessment Test <b> <?php echo $ass_code; ?>  -  <?php echo $ass_name; ?> </b></h4>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">

                        <!-- Button trigger modal -->
                        <button class="btn btn-info AddTest" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-pencil"></i> Add Test to Assessments
                        </button>
                        <!-- Modal -->
                        <button class="btn btn-info" data-toggle="modal" data-target="#myModal2"><i class="fa fa-users"></i>  Add Multiple Students </button>
                        <button class="btn btn-info" data-toggle="modal" data-target="#myModal3"><i class="fa fa-arrow-up"></i>  Import Students  </button>

                    </div>
                </div>
<?php if (isset($_GET['ass'])) { ?>

                    <!-- Add a New Test Modal Start -->               
                    <div class="modal fade modal-primary" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title text-primary" id="myModalLabel"><i class="fa fa-pencil"></i> <span class='titlea'>Add a New Test to </span><b> <?php echo $ass_code; ?> </b> -  <?php echo $ass_name; ?>                    
                                </div>
                                <form role="form"  action="<?php echo $post_url; ?>" method="post" />

                                    <div class="modal-body">

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <form role="form" action="" method="post" />
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon">Test Title:</span>
                                                    <input type="text" name="TName" class="form-control" required>
                                                </div>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon">Number of Questions:</span>
                                                    <input type="text" name="NOQ" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Message at the beginning of this Test:</label>
                                                    <textarea class="form-control" rows="3" name="start_message"></textarea>
                                                    <p class="help-block">Example: Do not <b>cheat</b>. <i>(HTML is allowed)</i></p>
                                                </div>
                                                <div class="form-group">
                                                    <label>Message at the bottom of the Test:</label>
                                                    <textarea class="form-control" rows="3" name="end_message"></textarea>
                                                    <p class="help-block">Example: Good <b>LUCK</b>. <i>(HTML is allowed)</i></p>
                                                </div>
                                            </div>
                                            <!-- /.col-lg-6 (nested) -->
                                            <div class="col-lg-6">

                                                <div class="form-group input-group">
                                                    <span class="input-group-addon">Test Time Length:</span>
                                                    <input type="text" placeholder="Time in Minutes" name="time" class="form-control" required>
                                                </div>                                        
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon">Test Mark:</span>
                                                    <input type="text" placeholder="Mark Obtainable" name="test_mark" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Test Type</label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="test_type" value="1"  required/> Test
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="test_type"  value="2" required/> Exam
                                                    </label>
                                                </div><div class="form-group">
                                                    <label>Activate Test as default?</label>
                                                    <label class="radio-inline">
                                                        <input type="radio"  name="be_default" value="1" checked="" /> Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="be_default" value="0" /> No
                                                    </label>
                                                </div> 
                                                <div class="form-group">
                                                    <label>Allow User to Write Test?</label>
                                                    <label class="radio-inline">
                                                        <input type="radio"  name="active" value="1" checked="" /> Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="active" value="0" /> No
                                                    </label>
                                                </div>                                            
                                                <div class="form-group">
                                                    <label>Questions are Random?</label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="RandomQ"  value="1" checked="" /> Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="RandomQ" value="0" /> No
                                                    </label>
                                                </div> 
                                                <div class="form-group">
                                                    <label>Allow User Registration?</label>
                                                    <label class="radio-inline">
                                                        <input type="radio"  name="prof_or_user" value="1" /> Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="prof_or_user" value="0" checked="" /> No
                                                    </label>
                                                </div>                                            
                                                											
                                                <div class="form-group">
                                                    <label>Show correct answers after finishing the Test?</label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ShowAns" value="1" /> Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ShowAns" value="0" checked=""/> No
                                                    </label>
                                                </div>											
                                                <div class="form-group">
                                                    <label>Show grade after finishing the Test?</label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ShowMark" value="1"  /> Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ShowMark" value="0" checked=""/> No
                                                    </label>
                                                </div>											
                                                <div class="form-group">
                                                    <label>Show entrants their ranks?</label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ShowRank" id="ShowRank" value="1"  /> Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="ShowRank" id="ShowRank" value="0" checked=""/> No
                                                    </label>
                                                </div>                                                                             
                                            </div>                            
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="test_id" value=""/>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        <button type="submit" name="add_test" class="btn btn-primary">Save and Continue</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    
                    <!-- Import A Question Modal Start -->               
                    <div class="modal fade modal-primary" id="addQModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title text-primary" id="myModalLabel"><i class="fa fa-pencil"></i> Add Questions to <b> <?php echo $ass_code; ?> </b> - <span class="tnm"></span> 

                                </div>

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="well">
                                            <span style=" color: brown"><h5><strong>Hint:</strong> Structure of what the CSV file should look like</h5> </span>
                                            <table class="table table-bordered table-condensed table-responsive">
                                                <thead >
                                                    <tr>
                                                        <th>Question</th>
                                                        <th>OptionA</th>
                                                        <th>OptionB</th>
                                                        <th>OptionC</th>
                                                        <th>OptionD</th>
                                                        <th>Corect</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Keyboard is a _____ device</td>
                                                        <td>output</td>
                                                        <td>processing</td>
                                                        <td>input</td>
                                                        <td>storage</td>
                                                        <td>3</td>
                                                    </tr>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-lg-8">
                                            
                                            <form role="form" action="<?php echo $post_url; ?>" method="post" 
                                                  enctype="multipart/form-data" />

                                            <div class="form-group">
                                                <label>Import A Question File (excel or csv file) - Optional</label>
                                                <input type="file" name="filename"/>
                                            </div>                                     
                                            <button type="submit" name="import_test" class="btn btn-primary">
                                                Save and Continue
                                            </button>
                                            <input type="hidden" name="test_id" value="<?php echo $testid?>"/>
                                            <input type="hidden" name="assess_id" value="<?php echo $ass_id?>"/>
                                            </form>

                                        </div>
                                        <!-- /.col-lg-6 (nested) -->
                                        <div class="col-lg-4">
                                            <a href="add_question.php?ass=<?php echo $ass_id; ?>&testid=<?php echo $testid; ?>" class="btn btn-info">Create Questions</a>

                                        </div>
                                        <!-- /.col-lg-6 (nested) -->

                                    </div>									
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    
                    
                     <!-- //  Add  Multiple Students --> 
                    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-users"></i>  Add Multiple Students </h4>
                                </div>
                                <form role="form" method="post" action="<?php echo $post_url; ?>" name="upload_multy_student">
                                    <div class="modal-body">
                                        <div class="col-sm-12">                                       
                                            <div class="form-group">
                                                <label>Usernames:</label>
                                                <textarea class="form-control"  name="userid" rows="10" required=""></textarea>
                                                <p class="help-block">Add one <b>username</b> per row. Do not leave <b>spaces</b>.</p>
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon">Batch Number:</span>
                                                <input type="text" name="batch" class="form-control" required>
                                            </div>
                                        </div>
                                        <div style="clear:both"> </div>    
                                    </div>
                                    <input type="hidden" name="upload_multy_student">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add Students to Assessment</button>
                                    </div>
                                </form>
                            </div>  
                        </div>
                    </div>
                   
            <?php if (isset($msg)) { ?>    
                <div class="alert alert-default alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                    <a href="#" class="alert-link "><?php echo $msg; ?></a>
                </div>                            
            <?php } ?> 
                  
            <?php if (isset($msg_err)) { ?>    
                <div class="alert alert-default alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                    <span class="red2"><b><?php echo $msg_err; ?></b></span>
                </div>                            
            <?php } ?>          
                     
             <?php if (isset($count_err) && ($count_err > 0)) { ?>    
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                    <a href="#" class="alert-link alert-danger"><?php echo $count_err; ?> users failed to upload for duplicate records</a>
                </div>                            
            <?php } ?> 

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Test Properties
                            <span style="float:right;">
                                <button class="btn btn-primary btn-xs" type="button" id="busers"><i class="fa  fa-retweet "></i> Total User: <span id='countassusers'><?php echo countdatasession('assessment_code', $ass_code, 'assessments_users'); ?> </span> </button>
                                <button class="btn btn-primary btn-xs" type="button" id="bacitve"><i class="fa  fa-retweet "></i> Active User: <span id='countacitve'><?php echo countSQL("SELECT assessment_code FROM assessments_users, users us WHERE assessment_code = '$ass_code' AND session_id = $csession_id AND username = userid and login = 1 ", $dbc); ?> </span> </button>
                             <button class="btn btn-info btn-xs" type="button" id="bfinish"><i class="fa  fa-retweet "></i> Finished User: <span id='countfinish'></span></button>
                             <button class="btn btn-warning btn-xs" type="button" id="bwaiting"><i class="fa  fa-retweet "></i> Waiting User: <span id='countwaiting'></span></button>
                             </span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Test Title</th>
                                            <th>Questions Added</th>
                                            <th>Test Questions </th>
                                            <th>Time</th>
                                            <th>Mark</th>
                                            <th>Active</th>
                                            <th>Random</th>
                                            <th>Answers</th>
                                            <th>Grade</th>
                                            <th>Rank</th>
                                            <th>Actions</th>
                                            <th>Activate</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $query = "  SELECT * FROM tests ts WHERE ts.assessments_id = '$ass_id'";
                                        $data = mysqli_query($dbc, $query);
                                        $count = mysqli_num_rows($data);

                                        $n = 1;
                                        while ($row = mysqli_fetch_array($data)) {
                                            $testid = $row['id']; ?>

                                        <tr>
                                            <td> <?php echo $n; ?> </td>
                                            <td class="tsname"> <?php echo $row['TName']; ?> </td>
                                            <td> <?php echo countdata('test_id', $testid, 'questions'); ?> </td>
                                            <td class="NOQ"> <?php echo $row['NOQ']; ?> </td>
                                            <td> <span class="time"> <?php echo $row['time']; ?> </span> mins</td>
                                            <td class="test_mark"> <?php echo $row['test_mark']; ?> </td>
                                            <td> <b> <?php echo yesno($row['be_default']); ?> </b> </td>
                                            <td> <?php echo yesno($row['random']); ?> </td>
                                            <td> <?php echo yesno($row['show_answers']); ?> </td>
                                            <td> <?php echo yesno($row['show_mark']); ?> </td>
                                            <td> <?php echo yesno($row['show_rank']); ?> </td>
                                            <td data-id=" <?php echo $row['id']; ?>">

                                                    <div class="btn-group">
                                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                    Actions
                                                                    <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu pull-right" role="menu">
                                                                    <li class="addQ" ><a href="#">Add Question to Test</a></li>
                                                                    <li class="edittest"><a data-toggle="modal" data-target="#myModal" href="#">Edit Test Properties</a></li>
                                                                    <li><a href="#">Delete Test</a>
                                                                <!-- 
                                                                    <li><a href="#">Edit Questions</a></li>
                                                                    <li class="divider"></li>
                                                                    <li><a href="#">Add Question from Questions Bank</a>
                                                                    
                                                                    <li><a href="#">Print Test</a>
                                                                    <li><a href="#">Export</a>  -->
                                                                    </li>
                                                            </ul>
                                                    </div>
                                            </td>
                                            <td>
                                                <form method="post" action="<?php echo $post_url; ?>" >
                                                    <input name="test_id" value="<?php echo $testid; ?>" type="hidden"></input>                                                
                                                 <?php if($row['be_default'] == 1){ ?>
                                                    <button class="btn btn-danger btn-xs" type="submit" value="0" name="test_status"> Deactivate </button>
                                                <?php }else{ ?>
                                                    <button class="btn btn-primary btn-xs" type="submit" value="1"  name="test_status"> Activate </button>
                                                <?php } ?>
                                             </form>
                                            </td>
                                        </tr>

                                        <?php 
                                            $n++;
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div> 

    <?php require_once('_inc_add_test_users.php'); ?>


<?php } ?>
                    <!-- Modal Import Users -->
                    <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-arrow-up"></i>  Import Students </h4>
                                </div>
                                <form method="post" action="<?php echo $post_url; ?>" name="upload_user" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="well">
                                            <span style=" color: brown"><h5><strong>Hint:</strong> Structure of what the CSV file should look like</h5> </span>
                                            <table class="table table-bordered table-condensed table-responsive">
                                                <thead >
                                                    <tr>
                                                        <th>Batch No</th>
                                                        <th>Matric No</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>20090204001</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>20090204002</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputFile">Upload Users</label>
                                            <input type="file" name="filename" class="filestyle" data-buttonText="Find file">
                                            <p class="help-block">Import a CSV file.</p>
                                            <input type="hidden" name="assess_id" value="<?php echo $ass_id?>" required=""/>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="import_users">Upload File</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    <!-- Modal Import Users -->
                    <div class="modal fade" id="EditTime" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-clock-o"></i>  Edit User Time </h4>
                                </div>
                                <form method="post" action="<?php echo $post_url; ?>" name="edit_time" enctype="multipart/form-data">
                                    <div class="modal-body">
                                       <table class="table table-bordered table-condensed table-responsive">
                                                <thead >
                                                    <tr>
                                                        <th>USERNAME </th>
                                                        <th>TIME USED </th>
                                                        <th>ASSESSMENT </th>
                                                        <th>ACTIVE TEST </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td id="shwo_matric">20090204001</td>
                                                        <td id="shwo_matric"><input type="text" name="TName" class="form-control" required width="100px"></td>
                                                        <td>EDU122</td>
                                                        <td>TEST 2</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="import_users">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
            </div>
        <?php require_once('footer_bar.php'); ?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
<?php require_once('../_inc/inc_js.php'); ?>
 <script>
        function loadm() {
            
        };
        $(document).on("click", ".addQ", function() {
            var row = $(this).closest('tr');
            var tsanme = row.find(".tsname").text();
            $(".tnm").text(tsanme);
            
            var tsid = $(this).closest('td').data('id');
             $("input[name='test_id']").val(tsid);
             
            $('#addQModal').modal('show');
        });   
        var td;      
    $(document).on("click", ".logout", function() {
                 
            td = $(this).parent().prev().prev('td');
            var userid = $(this).data('value');
            
            $.ajax({
                type: "POST",
                url: "../students/_inc/process.php",
                data: {logoutusid: userid},
                success: function (data) {
                    td.html(data)
                },
                error: function (exception) {
                    alert('error connecting to server');
                }
            });
        });
     
     var ass_code = $('#assid').data('code'); 
     function logoutall(){
         $.ajax({
                type: "POST",
                url: "../students/_inc/process.php",
                data: {logoutall: 'yes', ass_code: ass_code},
                success: function (data) {
                   alert('all users logout sucessful');
                },
                error: function (exception) {
                    alert('error connecting to server');
                }
            });         
     }
    $(document).on("click", "#logoutall", function() {
       logoutall(); 
    });
    
     
        window.onload = function () {
        var ass_id = $('#assid').data('value');  
        var ass_code = $('#assid').data('code');  
        //alert(ass_id);
        var counttype;
            function countuser(counttype, ass_id){
                var counttype = counttype;
                $.ajax({
                    type: "POST",
                    url: "../students/_inc/process.php",
                    data: {counttype: counttype, assid: ass_id, ass_code: ass_code },
                    success: function (data) {
                        $('#'+counttype).html(data);
                        var countfinish = $('#countfinish').html();
                        var countassusers = $('#countassusers').html();
                        var  waiting = (countassusers - countfinish );
                        $('#countwaiting').html(waiting)
                    },
                    error: function (exception) {
                        //alert('error connecting to server');
                    }
                });
            };


           setInterval(function () { countuser('countfinish', ass_id); }, 5000); 
           setInterval(function () { countuser('countacitve', ass_id); }, 5000); 
          setInterval(function () { countuser('countassusers', ass_id); }, 60000);            
        };

        

        var ass_id = $('#assid').data('value');
        function endexamall(ass_id){
         $.ajax({
                type: "POST",
                url: "../students/_inc/process.php",
                data: {endexamall: 'yes', ass_id: ass_id },
                success: function (data) {
                   alert(data);
                }
            });         
     }
    $(document).on("click", "#endexamall", function() {
       endexamall(ass_id); 
    });
    </script>

    <script>
            $(".edittest").click(function(){

                var row = $(this).closest('tr');
                
                $("input[name='TName']").val(row.find(".tsname").text());
                $("input[name='NOQ']").val(row.find(".NOQ").text());
               
                var time = row.find(".time").text();
                    timer = time.slice(0, -4)
                $("input[name='time']").val(timer);

                $("input[name='test_mark']").val(row.find(".test_mark").text());
                $(".titlea").text('Edit Test ');

                var tsid = $(this).closest('td').data('id');
                $("input[name='test_id']").val(tsid);
            })

            $(".AddTest").click(function(){
                
                $("input[name='TName']").val('');
                $("input[name='NOQ']").val('');
                $("input[name='time']").val('');
                $("input[name='test_mark']").val('');
                $("input[name='test_id']").val('0');
            })

$(document).on('click', '.edittime', function() {
alert('djdj');
})

window.onload = function () {
$('#addQModal').modal('show');
}
        </script>

        <?php ISSET($_GET['edittime']){ ?>
        <script>
            $('#EditTime').modal('show');
        </script>
        <?php } ?>
        </body>
</html>
