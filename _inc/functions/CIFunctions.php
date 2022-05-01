

<?php // C.I - FUNCTIONS LIBRARY III
//GENRAL FUNCTIONS
include('config.php');
        
    $query_session ="select * from session where session_active = 1";
    $result_session = mysqli_query($dbc, $query_session);
    $row_session = mysqli_fetch_array($result_session);
    $csession_id = $row_session['id']; 
    $csession = $row_session['session_name'];

    $page = basename($_SERVER["PHP_SELF"]);	

if(!isset($_SESSION)){
	session_start(); 
	}

 function url(){
  $appfolder ="/cbt/"; ///app folder
	return $url = 	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . $appfolder;
  }
$url =   url();  

function asset_url()
{
	global $url;

	echo $url.'_res';
}

function printHeader(){
	 echo '	<div class="logo">
                        <img src="../_res/img/logo3.png" alt="" width="115" height="115">
                    </div>
                    <div class="headings" style="padding:10px 0 0 30px">
                    <h2>TAI SOLARIN UNIVERSITY OF EDUCATION </h2>
                    <h4>P.M.B. 2118, Ijebu Ode, Ogun State, Nigeria</h4>
                 </div>';
}

function select_option2_id($name, $id, $id1, $value1, $table, $dbc){ //Select name and id where 
  
  $sql = "SELECT * FROM $table where $id1 = '$value1'";
  $result = $dbc->query($sql) or die(mysqli_error());
  $options = "";

  while($row= $result->fetch_assoc()){
    $namea = $row ["$name"];
    $ida = $row ["$id"];
    $options .= "<option value='$ida' >".$namea.'</option>';
  }
   return $options;
}

function select_option2_2ids($name, $id, $id1, $value1, $id2, $value2, $table, $dbc){ //Select name and id where 
  
  $sql = "SELECT * FROM $table where $id1 = '$value1' and $id2 = $value2";
  $result = $dbc->query($sql) or die(mysqli_error());
  $options = "";

  while($row= $result->fetch_assoc()){
    $namea = $row ["$name"];
    $ida = $row ["$id"];
    $options .= "<option value='$ida' >".$namea.'</option>';
  }
   return $options;
}

function select_option2_desc($name, $id, $table, $dbc){ //Select name and id
  $sql = "SELECT * FROM $table ORDER BY $id DESC";
  $result = $dbc->query($sql) or die(mysqli_error());
  $options = "";

  while($row= $result->fetch_assoc()){
    $name2 = $row ["$name"];
    $id2 = $row ["$id"];
    $options .= "<option value='$id2' >".$name2.'</option>';
  }
   return $options;
}

function select_option2names_desc($name, $name2, $id, $table, $dbc){ //Select name and id
  $sql = "SELECT * FROM $table ORDER BY $id DESC";
  $result = $dbc->query($sql) or die(mysqli_error());
  $options = "";

  while($row= $result->fetch_assoc()){
    $nam = $row["$name"];
    $nam2 = $row["$name2"];
    $id2 = $row ["$id"];
    $options .= "<option value='$id2' >".$nam. ' - '.$nam2.'</option>';
  }
   return $options;
}

function select_option2($name, $id, $table, $dbc){ //Select name and id
  $sql = "SELECT * FROM $table";
  $result = $dbc->query($sql) or die(mysqli_error());
  $options = "";

  while($row= $result->fetch_assoc()){
    $name2 = $row ["$name"];
    $id2 = $row ["$id"];
    $options .= "<option value='$id2' >".$name2.'</option>';
  }
   return $options;
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

# drop down from database
function select($table,$name, $dbc)
{
$query = "SELECT $name FROM $table ORDER BY $name";
$sql = mysqli_query($dbc, $query) or die(mysqli_error());
        while($row = mysqli_fetch_array($sql))
        {
                $name1 = $row["$name"];
                $display = '<option value = "'.$name1.'">'.$name1.'</option>';
                echo $display;
        }

}


					
# update a field
function fieldupdate($table, $field, $value, $id, $value2, $dbc){
                global $dbc;
                //echo
                $sql = "UPDATE $table SET $field = '$value' WHERE $id = '$value2'";
                $sql_result = mysqli_query($dbc,$sql) or die(mysqli_error($dbc));
                }
                
# update all rows field
function updateall($table, $field, $value, $dbc){
                global $dbc;
                //echo
                $sql = "UPDATE $table SET $field = '$value'";
                $sql_result = mysqli_query($dbc,$sql) or die(mysqli_error($dbc));
                }
					
function logout($session_id){
  if (isset($_SESSION['user_id'])) {

    // Delete the session vars by clearing the $_SESSION array
    $_SESSION = array();

    // Delete the session cookie by setting its expiration to an hour ago (3600)
    if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time() - 36000);
    }

    // Destroy the session
    session_destroy();
  }

  // Redirect to the home page
  $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
 // $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/login.php';
  header('Location: ' . $home_url);
  }
  
 
function checkNotLoginAdmin(){ // Redirect user to login  if not logged in to Admin
global $url;  
        if(!isset($_SESSION['aid'])){
        header("location:".$url."/admin/login.php");
        }
} 
 
function checkNotLoginUser(){ // Redirect user to login if not logged in 
global $url; 
        if(!isset($_SESSION['uid'])){
        header("location:".$url."/students/login.php");
        }
}

function checkLoginUser(){ // Redirect user to index if logged in 
global $url; 
        if(isset($_SESSION['uid'])){
        header("location:".$url."/students/index.php");
        }
}


function twoFieldsId($field1, $field2, $name, $table, $id1, $id2){
 global $dbc;
 $sql = "SELECT $name FROM $table WHERE $field1 = '$id1' AND $field2 = '$id2' ";
 $result = $dbc->query($sql) or die(mysqli_error());
 $row = $result->fetch_assoc();
 $name = $row["$name"];

 return $name;
}

function threeFieldsId($field1, $field2, $field3, $name, $table, $id1, $id2, $id3){
 global $dbc;
 
 $sql = "SELECT $name FROM $table WHERE $field1 = '$id1' AND $field2 = '$id2' AND $field3 = '$id3' ";
 $result = $dbc->query($sql) or die(mysqli_error());
 $row = $result->fetch_assoc();
 $name = $row["$name"];

 return $name;
}


function nameId2($id, $name, $name2, $table, $id2){
 global $dbc;
 $sql = "SELECT $id, $name, $name2 FROM $table WHERE $id = '$id2' ";
$result = $dbc->query($sql) or die(mysqli_error());
$row = $result->fetch_assoc();
 $name1a = $row["$name"];
 $name2a = $row["$name2"];

 return $name1a.' '.$name2a;
 }

function nameId($id, $name, $table, $id2){
 global $dbc;
 //echo
   $sql = "SELECT $id, $name FROM $table WHERE $id = '$id2' ";
$result = $dbc->query($sql) or die(mysqli_error($dbc));
$row = $result->fetch_assoc();
$name = $row["$name"];

 return $name;
 }
 
function checkId2($id, $name, $table, $id2){
 global $dbc;
 //echo
 $sql = "SELECT * FROM $table WHERE $id = '$id2' ";
$data = mysqli_query($dbc, $sql);
$row = mysqli_fetch_array($data);
$name = $row["$name"];

 return $name;
 }
 
function countall($field,$table){
global $dbc; global $csession_id;
	 $query = "  SELECT $field
                FROM $table"; 
    $data = mysqli_query($dbc, $query);
    $count = mysqli_num_rows($data);
    return $count;
}

function countdata($field, $id,$table){
global $dbc; global $csession_id;
	 $query = "  SELECT $field
                FROM $table 
                WHERE $field = '$id'"; 
    $data = mysqli_query($dbc, $query);
    $count = mysqli_num_rows($data);
    return $count;
}

function countdatasession($field, $id,$table){
global $dbc; global $csession_id;
	 $query = " SELECT $field
                FROM $table 
                WHERE $field = '$id'
                AND session_id = $csession_id"; 
    $data = mysqli_query($dbc, $query);
    $count = mysqli_num_rows($data);
    return $count; 
}

function countSQL($query, $dbc){
    //echo $query;
    $data = mysqli_query($dbc, $query);
    $count = mysqli_num_rows($data);
    return $count;
}



function countdatasessiondist($field, $id, $field2, $table){ //COUNT DIS BATCH
global $dbc; global $csession_id;
	 $query = "  SELECT  DISTINCT $field, $field2
                FROM $table 
                WHERE $field = '$id'
                AND session_id = $csession_id"; 
    $data = mysqli_query($dbc, $query);
    $count = mysqli_num_rows($data);
    return $count;
}

function getRealIpAddr()
	{
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
		//check ip from share internet
		{
		$ip=$_SERVER['HTTP_CLIENT_IP'];
		}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		//to check ip is pass from proxy
		{
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
	else
		{
		$ip=$_SERVER['REMOTE_ADDR'];
		}
	return $ip;
			}
			
function yesno($value){
	if($value == 1){ $value2 = "Yes"; }else{  $value2 = "No"; }
 return $value2;
 }
/***APP FUNCTIONS ****/

if(isset($_SESSION['uid'])){
	//user info
	$uid = $_SESSION['uid'];
	$query = "  SELECT * FROM users WHERE id = '$uid'"; 
	$udata = mysqli_query($dbc, $query);
	$urow = mysqli_fetch_array($udata);
	$userid = $urow['userid'];
	$FName = $urow['fname'];
	$MName = $urow['mname'];
	$LName = $urow['lname'];
	$username = $urow['userid'];	
}	

function userquery($usid){ //echo
$userquery = "  SELECT * FROM users WHERE id = '$usid' "; 
$userdata = GetData($userquery);
return $userdata;
//$usercount = count($userdata);
}

 function GetData($sqlquery){
   global $dbc;
   
    $result = mysqli_query($dbc,$sqlquery);
    $data = array();

    while($row = mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }
    return $data;
}
	
	
		/*questions info
	if (isset($_POST['tid'])){
	$tid  = $_POST['tid'];
	
	//echo 
	$queryque = "SELECT * FROM questions WHERE test_id = '$tid' ORDER BY RAND() LIMIT $NOQ "; 
	$dataque = mysqli_query($dbc, $queryque);
	$n = 1;
	//$rowque = mysqli_fetch_array($dataque);

	
	} */
	
	function checkTestNotSet(){ // Redirect user to index  if test not set
	global $url;  
		if((!isset($_SESSION['uid'])) || (!isset($_POST['tid']))){
			header("location:".$url."/students/index.php");
		}
	}
	
	function checkSetStartExam(){ // Redirect user to index  if test not set
	global $url;  
		if((isset($_POST['startexam'])) || (isset($_POST['endexam']))){
			
		}else{
		header("location:".$url."/students/index.php");
		}
	} 
	
	function noQuestions(){ // Redirect user to index  if test not set
if((isset($_POST['startexam'])) || (isset($_POST['endexam']))){
	$assid = $_POST['assid']; 
	$sum_time = 0;
	$sum_time_spent = 0;

	//echo
	$querytest = " 	SELECT ts.id, TName, time, NOQ, random, minus_mark, be_default, assessments_id, assessments_name, assessments_code, session_id 
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
			
		if (isset($_POST['endexam'])){		
			$user_test_ids  = $rowts['id'];
			echo $sql= "UPDATE user_test SET finish = 1 WHERE id = '$user_test_ids'";
			$data  = mysqli_query($dbc, $sql);		
		}		
		
	}
}
	}


/// try get keys and value of array	
function atss($assid, $csession_id, $dbc){
	//echo
	$querytest = " 	SELECT ts.id, TName, time, NOQ, random, minus_mark, be_default, assessments_id, assessments_name, assessments_code, session_id 
					FROM tests ts, assessments ass
					WHERE ts.assessments_id = ass.id AND be_default = 1 AND session_id = '$csession_id' AND  assessments_id = $assid"; 
	$datatest = mysqli_query($dbc, $querytest);
	$countts1 = mysqli_num_rows($datatest);
	$n = 1;
	return $rowats[] = mysqli_fetch_array($datatest);
}	

function ques_of_ats(){
	//echo
	$queryts = "SELECT q_id, uc.answer, user_test_id, q.answer
				FROM user_choice uc, questions q
				WHERE user_test_id
				IN (	
					SELECT ut.id FROM user_test ut, tests ts, assessments ass 
					WHERE ut.test_id = ts.id AND ts.be_default = 1 
					AND ass.id = ts.assessments_id
					AND ass.id = '$assid' 
					AND ut.user_id = '$uid'
					AND ass.session_id = '$csession_id'
					)
				AND uc.answer = q.answer
				AND uc.q_id = q.id"; 
				$datats = mysqli_query($dbc, $queryts);
}

function countans($utid, $conditon){
	global $dbc;
		$sql = "SELECT q_id, uc.answer, user_test_id, q.answer
			FROM user_choice uc, questions q
			WHERE user_test_id = '$utid'
			AND uc.q_id = q.id
			AND uc.answer $conditon";
		$data  = mysqli_query($dbc, $sql);
		$count  = mysqli_num_rows($data);
		return $count;
}

function update_score($assid){
	global $dbc, $uid, $csession_id ;
	//echo
		$queryats = "SELECT ut.id, ts.id as tsid FROM user_test ut, tests ts, assessments ass 
					WHERE ut.test_id = ts.id AND ts.be_default = 1 
					AND ass.id = ts.assessments_id
					AND ass.id = '$assid' 
					AND ut.user_id = '$uid'
					AND ass.session_id = '$csession_id'";
		$datats = mysqli_query($dbc, $queryats);

		while($rowats = mysqli_fetch_array($datats)){ 
			$utid = $rowats['id'];
			$tsid = $rowats['tsid'];
			$test_mark_percent = nameId('id', 'test_mark', 'tests', $tsid);
			$NOQ = nameId('id', 'NOQ', 'tests', $tsid);
                        
                        $total_correct_ans = countans($utid, '= q.answer');
			$total_incorrect_ans = countans($utid, '!= q.answer');
			$total_unanswered = countans($utid, 'IS NULL');
			
                        $percent_score = round((($total_correct_ans / $NOQ) * $test_mark_percent), 1);
             //print_r($percent_score); die();
                        
			 $sql= "UPDATE user_test SET ans_correct = '$total_correct_ans', ans_wrong = '$total_incorrect_ans', 
				unanswered = '$total_unanswered', score = '$percent_score'
				WHERE id = '$utid'";
			$data  = mysqli_query($dbc, $sql);
		}
}

function view_score($assid){
	global $dbc, $uid, $csession_id ;
		$queryats = "SELECT ut.id, NOQ, TName, assessments_code, test_mark, score FROM user_test ut, tests ts, assessments ass 
					WHERE ut.test_id = ts.id AND ts.be_default = 1 
					AND ass.id = ts.assessments_id
					AND ass.id = '$assid' 
					AND ut.user_id = '$uid'
					AND ass.session_id = '$csession_id'
					AND show_mark = 1";
		$datats = mysqli_query($dbc, $queryats);
		$count  = mysqli_num_rows($datats);

		while($rowats = mysqli_fetch_array($datats)){ 
			$utid = $rowats['id'];
			$NOQ = $rowats['NOQ'];
			$TName = $rowats['TName'];
			$test_mark = $rowats['test_mark'];
			$assessments_code = $rowats['assessments_code'];
			
			$total_correct_ans = countans($utid, '= q.answer');
			$total_incorrect_ans = countans($utid, '!= q.answer');
			$total_unanswered = countans($utid, 'IS NULL');
                        
                        $score = $rowats['score'];

		echo 	'<tr class="odd gradeX">
					<td>'.$assessments_code.' - '.$TName.'</td>
					<td>'.$NOQ.'</td>
					<td>'.$total_correct_ans.'</td>
					<td>'.$total_incorrect_ans.'</td>
					<td class="center">'.$total_unanswered.'</td>
					<td class="center">'.$test_mark.'</td>
					<td class="center">'.$score.'</td>
				</tr>';
		}
		IF ($count == 0){
			
		echo 	'<tr class="odd gradeX">
					<td class="text-center text-danger" colspan="7"> <h4>No Permission to View Result </h4></td>
				</tr>';
		}
}

function view_score_history($user_id){
     
	global $dbc, $uid, $csession_id ; 
		$queryats = "SELECT ut.id as utid, NOQ,ts.id as tsid, TName, assessments_code, test_mark, session_name
                            FROM user_test ut, tests ts, assessments ass, session se
                            WHERE ut.test_id = ts.id 
                            AND ass.id = ts.assessments_id
                            AND ass.session_id = se.id
                            AND ut.user_id = '$uid' ";
                
		$datats = mysqli_query($dbc, $queryats);
		$count  = mysqli_num_rows($datats);

		while($rowats = mysqli_fetch_array($datats)){ 
			$tsid = $rowats['tsid'];
			$utid = $rowats['utid'];
			$NOQ = $rowats['NOQ'];
			$session_name = $rowats['session_name'];
			$TName = $rowats['TName'];
			$test_mark = $rowats['test_mark'];
			$assessments_code = $rowats['assessments_code'];
			
			$total_correct_ans = countans($utid, '= q.answer');
			$total_incorrect_ans = countans($utid, '!= q.answer');
			$total_unanswered = countans($utid, 'IS NULL');
			
		echo 	'<tr class="odd gradeX">
                            <td>'.$assessments_code.' - '.$TName.'</td>
                            <td>'.$session_name.'</td>
                            <td>'.$NOQ.'</td>
                            <td>'.$total_correct_ans.'</td>
                            <td>'.$total_incorrect_ans.'</td>
                            <td class="center">'.$total_unanswered.'</td>
                            <td class="center">'.$test_mark.'</td>
                            <td class="center">'.$total_correct_ans.'</td>
                            <td><a href="students_answers.php?tsid='.$tsid.'&usid='.$uid.'"> <button class="btn btn-success btn-xs" type="button"> View Details</button></a></td>
                               
                        </tr>';
		}

}


function view_test_score($assid, $utid){
	global $dbc, $uid, $csession_id;
		$queryats = "SELECT ut.id, NOQ, TName, assessments_code, test_mark, score, ans_correct, ans_wrong, unanswered FROM user_test ut, tests ts, assessments ass 
					WHERE ut.test_id = ts.id AND ts.be_default = 1 
					AND ass.id = ts.assessments_id
					AND ass.id = '$assid' 
					AND ut.user_id = '$uid'
					AND ass.session_id = '$csession_id'
					AND ut.id = '$utid'
					AND show_mark = 1";
		$datats = mysqli_query($dbc, $queryats);
		$count  = mysqli_num_rows($datats);

		while($rowats = mysqli_fetch_array($datats)){ 
			$utid = $rowats['id'];
			$NOQ = $rowats['NOQ'];
			$TName = $rowats['TName'];
			$test_mark = $rowats['test_mark'];
			$assessments_code = $rowats['assessments_code'];
			$score = $rowats['score'];
			$unanswered = $rowats['unanswered'];
			$ans_wrong = $rowats['ans_wrong'];
			$ans_correct = $rowats['ans_correct'];
			
//			$total_correct_ans = countans($utid, '= q.answer');
//			$total_incorrect_ans = countans($utid, '!= q.answer');
//			$total_unanswered = countans($utid, 'IS NULL');
//			
		echo 	'<tr class="odd gradeX">
					<td>'.$assessments_code.' - '.$TName.'</td>
					<td>'.$NOQ.'</td>
					<td>'.$ans_correct.'</td>
					<td>'.$ans_wrong.'</td>
					<td class="center">'.$unanswered.'</td>
					<td class="center">'.$test_mark.'</td>
					<td class="center">'.$score.'</td>
				</tr>';
		}
		IF ($count == 0){
			
		echo 	'<tr class="odd gradeX">
					<td class="text-center text-danger" colspan="7"> <h4>No Permission to View Result </h4></td>
				</tr>';
		}
}

function view_unanswered($assid){
	global $dbc, $uid, $csession_id ;
		$queryats = "SELECT ut.id, NOQ, TName, assessments_code, test_mark FROM user_test ut, tests ts, assessments ass 
					WHERE ut.test_id = ts.id AND ts.be_default = 1 
					AND ass.id = ts.assessments_id
					AND ass.id = '$assid' 
					AND ut.user_id = '$uid'
					AND ass.session_id = '$csession_id'";
		$datats = mysqli_query($dbc, $queryats);
		$count  = mysqli_num_rows($datats);

		while($rowats = mysqli_fetch_array($datats)){ 
			$utid = $rowats['id'];
			$NOQ = $rowats['NOQ'];
			$assessments_code = $rowats['assessments_code'];
			$TName = $rowats['TName'];
			$test_mark = $rowats['test_mark'];
			
			$total_unanswered = countans($utid, 'IS NULL');
			
		echo 	'<tr class="odd gradeX">
					<td>'.$assessments_code.' - '.$TName.'</td>
					<td>'.$NOQ.'</td>
					<td class="center text-danger"><b>'.$total_unanswered.'</b></td>
				</tr>';
		}
		
}

function update_time($assid, $stime){
	global $dbc, $uid, $csession_id ;
	//echo
	$sql= "UPDATE user_assessments SET time_length = '$stime' WHERE ass_user_id = '$uid' AND ass_id = '$assid' AND session_id = '$csession_id' ";
		mysqli_query($dbc, $sql);
}

function check_correct_ans($q_id, $answer){
	global $dbc, $uid, $csession_id ;
	//echo
	$sql = "SELECT answer FROM questions 
			WHERE id = '$q_id'
			AND answer = '$answer'";
	$data  = mysqli_query($dbc, $sql);
	$count  = mysqli_num_rows($data);
	
	return $count;
}

function pick_correct_ans($q_id){
	global $dbc, $uid, $csession_id ;
	//echo
	$sql = "SELECT answer FROM questions 
			WHERE id = '$q_id'";
	$data  = mysqli_query($dbc, $sql);
	$count  = mysqli_num_rows($data);
        $row = mysqli_fetch_assoc($data);
	
	return $row['answer'];
}

function converttoSec($str_time){
	sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	$time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
	return  $time_seconds;
}

function diffbtwTime($str_time, $str_time2){

	  $time1 = converttosec($str_time);
 	   $time2 =  converttosec($str_time2);

	 $remtimesec = $time1  - $time2;
	 if($time1 > $time2){
	 $remtime =  gmdate("i:s", $remtimesec);
	}else{
		$remtime = 0;
	}
	return $remtime;
}
//echo diffbtwTime($str_time, $str_time2);



$array = array(
    'r1' => array(2, 4),
    'r2' => array(5, 96),
    'tekma_id' => array(7, 8));

$keys = array_keys($array);
$iterations = count($array[$keys[0]]);

for($i = 0; $i < $iterations; $i++) {
     $data = array();
    foreach($array as $key => $value) {
         $data[$key] = $value[$i];
        
    }
    //print_r($data);
}

function updateTimeoutTest($assid){ 
    global $csession_id, $dbc, $uid;
    
    $querytest = "  SELECT ts.id FROM tests ts, assessments ass
                            WHERE ts.assessments_id = ass.id 
                            AND session_id = '$csession_id' AND  assessments_id = $assid AND be_default = 1";

            $datatest = mysqli_query($dbc, $querytest);
           
            while ($rowats = mysqli_fetch_array($datatest)) {
                $testid = $rowats['id'];

                //echo check if user test exist and record if not
                 $queryts = "SELECT * FROM user_test WHERE test_id = '$testid' AND user_id = '$uid' ";
                $datats = mysqli_query($dbc, $queryts);
                $rowts = mysqli_fetch_array($datats);
                $user_test_id = $rowts['id'];

                    $user_test_ids = $rowts['id'];
                    $sql = "UPDATE user_test SET finish = 2, time_end = CURTIME() WHERE id = '$user_test_ids'";
                    $data = mysqli_query($dbc, $sql);
            }
}

 $passmark = nameId('id', 'passmark', 'session', $csession_id);

function no_of_pass($assid){
    global  $dbc, $passmark;
//   echo 
        $sql2 = "SELECT sum(score)as total FROM user_test ut, tests ts WHERE  ut.test_id = ts.id AND ts.assessments_id = '$assid' GROUP BY user_id having total > $passmark";
        $data2 = mysqli_query($dbc, $sql2);
        $count_users_pass = mysqli_num_rows($data2);
        return $count_users_pass;
}

function no_of_pass2($assid){
    global  $dbc, $passmark;
//   echo 
        $sql2 = "SELECT (ca_score+exam_score) as total FROM user_assessments WHERE  ass_id = '$assid' GROUP BY ass_user_id having total > $passmark";
        $data2 = mysqli_query($dbc, $sql2);
        $count_users_pass = mysqli_num_rows($data2);
        return $count_users_pass;
}

function no_of_perc($assid, $perc1, $perc2){
    global  $dbc, $passmark; 
        $sql2 = "SELECT sum(score)as total FROM user_test ut, tests ts WHERE ts.assessments_id = $assid and test_id = ts.id GROUP BY user_id having total >= $perc1 and total <= $perc2";
        $data2 = mysqli_query($dbc, $sql2);
        $count_users_pass = mysqli_num_rows($data2);
        return $count_users_pass;
}

function no_of_perc_test($tsid, $perc1, $perc2){
    global  $dbc; 
       
       $sql2 = "SELECT score FROM user_test ut WHERE  test_id = '$tsid'  AND score >= $perc1 and score <= $perc2";
        $data2 = mysqli_query($dbc, $sql2);
        $count_users_pass = mysqli_num_rows($data2);
        return $count_users_pass;
}

function total_scores($assid){
    global  $dbc, $passmark;
//    echo
       $sql = "SELECT sum(score) as total FROM user_test ut, tests ts WHERE ts.assessments_id = '$assid' and ut.test_id = ts.id GROUP BY ut.user_id, ut.test_id"; 
        $data = mysqli_query($dbc, $sql);
        while ($row = mysqli_fetch_array($data)){
            $totals[] = $row['total'];
        }
        $min = min($totals);
        $max = max($totals);
        if ($min == ''){ $min = 0; }  
        if ($max == ''){ $max = 0; } 
        
       return array('min' => $min, 'max' => $max);               
}

//print_r (total_scores(1)['min']);

function no_of_users_assessed($assid){
    global  $dbc;
        $sql = "SELECT  distinct user_id 
        		FROM user_test ut, tests ts 
        		WHERE ut.test_id = ts.id AND ts.assessments_id = '$assid' "; 
        $data = mysqli_query($dbc, $sql);
        $count_users = mysqli_num_rows($data);
        return  $count_users;
}


function no_of_users_assessed2($assid){
    global  $dbc;
        $sql = "SELECT  distinct ass_user_id 
        		FROM user_assessments WHERE ass_id = '$assid' "; 
        $data = mysqli_query($dbc, $sql);
        $count_users = mysqli_num_rows($data);
        return  $count_users;
}

function end_all_exam($dbc, $ass_id){
	//AND finish = 0
    	 $sql = "SELECT ut.id as utid, ts.id as tsid, test_id, finish, score FROM user_test ut, tests ts 
				WHERE ut.test_id = ts.id AND ut.user_id IS NOT NULL AND finish =0 
				AND ts.assessments_id = '$ass_id' AND ( ut.score = 0 OR ut.score IS NULL)";
    		// echo($sql); die();
	$data = mysqli_query($dbc, $sql);
	while($row = mysqli_fetch_array($data)){ 
            $utid = $row['utid'];
            $tsid = $row['tsid'];
            ///print_r($row); die();
			update_missing_score($utid, $tsid);                
        }
}

function log_out_user($dbc, $ass_code){
    	$sql = "UPDATE users SET login = 3
    			JOIN assessments_users au ON users.id = au.username 
    			WHERE au.assessment_code = '$ass_id'";
	$data = mysqli_query($dbc, $sql);
}

function update_missing_score($utid, $tsid){
	global $dbc;
    $test_mark_percent = nameId('id', 'test_mark', 'tests', $tsid);
    $NOQ = nameId('id', 'NOQ', 'tests', $tsid);

    $total_correct_ans = countans($utid, '= q.answer');
    $total_incorrect_ans = countans($utid, '!= q.answer');
    $total_unanswered = countans($utid, 'IS NULL');

    $percent_score = round((($total_correct_ans / $NOQ) * $test_mark_percent), 1);
	// echo('-utid='.$utid.'-score'.$percent_score.'-ANS'.$total_correct_ans.'-NOQ'.$NOQ.'-MARK'.$test_mark_percent );
	// die(); 

      $sql= "UPDATE user_test SET ans_correct = '$total_correct_ans', ans_wrong = '$total_incorrect_ans', 
            unanswered = '$total_unanswered', score = '$percent_score', finish = '3'
            WHERE id = '$utid' AND (score IS NULL OR score = 0)";
        
    $data  = mysqli_query($dbc, $sql) or die(mysqli_error());
}

function export_excel($excelFileName){
	header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: pre-check=0, post-check=0, max-age=0');
	header("Pragma: no-cache");
	header("Expires: 0");
	//header('Content-Transfer-Encoding: none');
	header('Content-Disposition: attachment; filename="' . basename($excelFileName) . '"');
	header('Content-Description: File Transfer');
	header('Content-Transfer-Encoding: binary');          
	//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
	header('Content-type: application/ms-word'); // This should work for the rest 
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header("Content-Type: application/csv"); 
	header('Content-Type: application/vnd.ms-excel;charset=UTF-8;');
	header('Pragma: no-cache');
	header('Expires: 0'); 	
}		

function get_perc_val($perc, $mark){
    return $mark/100*$perc;
}   

  
  
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

function  move_User_chioce($dbc, $user_id, $test_id){
    $sql = "";
	$data = mysqli_query($dbc, $sql);
}

?>