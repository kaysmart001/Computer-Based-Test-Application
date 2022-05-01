<?php
	require_once('../../_inc/functions/CIFunctions.php');
//ECHO "NNJCJNKCJ";
if (isset($_POST['endexam1'])) {	
	//print_r($_POST);
	$assid = test_input($_POST["assid"]);
        
	view_unanswered($assid);
}

if (isset($_POST['timeout'])) {	
	//print_r($_POST);
	$assid = test_input($_POST["assid"]);
	$sum_time = test_input($_POST["sum_time"]);	
	update_time($assid, $sum_time);	

    $calc_score = nameId('id', 'calc_score', 'settings', 1);
    if($calc_score == 'user'){
       update_score($assid); 
    }
	
    updateTimeoutTest($assid);
    if (isset($dbc_success)){
         echo $dbc_success;
    }
}


if (isset($_POST['savetime'])) { 
    //print_r($_POST); die();
    $assid = test_input($_POST["assid"]);
    $sum_time = test_input($_POST["sum_time"]); 
    update_time($assid, $sum_time); 
    echo 'Logout Successful';
}

if (isset($_POST['reload'])) {	
	//print_r($_POST);
	$assid = test_input($_POST["assid"]);
	$used_time = test_input($_POST["used_time"]);
	
	update_time($assid, $used_time);	
}

if (isset($_POST['answers'])) {	//save exam
	$answers = ($_POST['answers']);
	
	foreach( $answers as $ucid => $answer ){
		$sql= "UPDATE user_choice SET answer ='$answer' WHERE id = '$ucid'";
		$data  = mysqli_query($dbc, $sql);	
	}
}

if (isset($_POST['choice'])) {
		
    $assid = test_input($_POST["assid"]);
    $usedtime = test_input($_POST["usedtime"]);

    $user_test_id = test_input($_POST["user_test_id"]);
    $queid = test_input($_POST["queid"]);
    $answer = test_input($_POST["choice"]);

    ///echo 
    	$sql= "UPDATE user_choice SET answer ='$answer' WHERE user_test_id = '$user_test_id' AND q_id = '$queid' ";
    	$data  = mysqli_query($dbc, $sql);
    ///echo	
    	$sql2= "UPDATE user_assessments SET time_length = '$usedtime' WHERE ass_user_id = '$uid' AND ass_id = '$assid' AND session_id = '$csession_id' ";
    	$data2  = mysqli_query($dbc, $sql2);
	

	if($data){ $msg = '<div class="alert2 alert-success">Your Choice has been successfully submitted to the DataBase.</div>';  }else{
		$msg = '<div class="alert2 alert-danger">Choice can NOT be submitted. Continue with your work</div>';
	} 
	echo $msg;
																		 
}

if (isset($_POST['counttype'])){
   $assid = test_input($_POST["assid"]);
   $ass_code = test_input($_POST["ass_code"]);
   
    if ($_POST['counttype'] == 'countfinish'){
    $countfinish = countSQL("SELECT us.id, lname FROM user_test ut, assessments ass, tests ts, users us 
             WHERE ut.test_id = ts.id AND ts.assessments_id = ass.id AND ts.be_default = 1 
             AND ass.id = '$assid' AND finish > 0 AND us.id = ut.user_id", $dbc);
    echo $countfinish;
    }
    
    if ($_POST['counttype'] == 'countacitve'){
    $countactive = countSQL("SELECT assessment_code FROM assessments_users, users us WHERE assessment_code = '$ass_code' AND session_id = $csession_id AND username = userid and login = 1 ", $dbc);
    echo $countactive;
    }
    
    if ($_POST['counttype'] == 'countassusers'){
    $countassusers = countdatasession('assessment_code', $ass_code, 'assessments_users');
    echo $countassusers;
    }    
    
    if ($_POST['counttype'] == 'countwaiting'){
    $countassusers = countdatasession('assessment_code', $ass_code, 'assessments_users');
    
    echo $countassusers;
    }
    
}
        
if (isset($_POST['logoutusid'])) {	//admin user logout
	$logoutusid = test_input($_POST["logoutusid"]);
        fieldupdate('users', 'login', '0', 'userid', $logoutusid, $dbc); //set to logout
        echo '<span class="text-default">Inactive</span>';
}

if (isset($_POST['logoutall'])) {	//admin user logout
        updateall('users', 'login', '0', $dbc); //set to logout
        echo '<span class="text-default">Inactive</span>';
}

if (isset($_POST['endexamall'])) {	//admin user endexam
        end_all_exam($dbc, $_POST['ass_id']);
        echo 'all users exam  ended';
}


if (isset($_POST['perc_chart'])) {      
    ECHO 
    $no_percent10  = no_of_perc_test(1, 0, 10);
    $e[] = array('asscode' => '10%', 'perc' => "$no_percent10");  
    echo  $mark_obt = nameId('id', 'test_mark', 'tests', $tsid);

    $c = 10;
    while ($c <= 90) {
        $c0 = $c;
        $c1 = $c + 1;
        $c += 10;
        
        $c1 = get_perc_val($c1, $mark);
        $c = get_perc_val($c, $mark);

        '<br/>';
        $no_percent = no_of_perc_test($ts_id, $c1, $c);
        $e[] = array('asscode' => $c . '%', 'perc' => "$no_percent");
    }
}

//
if (isset($_POST['resetimg_usid'])) {  //admin user logout
    $resetimg_usid = test_input($_POST["resetimg_usid"]);
    $testid = test_input($_POST["testid"]);

        fieldupdate('users', 'login', '2', 'userid', $resetimg_usid, $dbc); //set to logout
       $sql = "DELETE FROM test_images WHERE userid = '$resetimg_usid' AND testid = $testid";
       $sql_result = mysqli_query($dbc, $sql); 
}

//
if (isset($_POST['checklogout'])) {  //admin user logout
    $user_id = test_input($_POST["user_id"]);
       
       $sql = "SELECT login FROM users WHERE userid = $user_id ";
       $sql_result = mysqli_query($dbc, $sql); 
       $row = mysqli_fetch_array($sql_result);
       echo $row['login'];
}

if (isset($_POST['perc_chart'])) {      
     

}

///
if (isset($_POST['reloadimages'])) {    
    
     $sql = "SELECT * FROM test_images ORDER BY id DESC LIMIT 24 ";
       $sql_result = mysqli_query($dbc, $sql); 

        while ($row = mysqli_fetch_array($sql_result)) { ?>
                 <div class="col-sm-2">
                    <div class="well" style="padding: 10px">
                        <img id="passport" src="../students/<?php echo $row['image']; ?>" style="margin: -5px -5px 0px -5px ; " width="123"> 
                        <h5> <?php echo $row['userid']; ?></h5>
                        <button class="btn btn-danger btn-xs resetimg" data-value="<?php echo $row['userid']; ?>" data-testid="<?php echo $row['testid']; ?>">
                            <i class="fa fa-refresh"></i>  Reset
                        </button>
                    </div>
                </div>
            <?php }    




   }?>

