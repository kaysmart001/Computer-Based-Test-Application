<?php 
   date_default_timezone_set("Africa/Lagos");

	require_once('../startsession.php');
    require_once('../config.php');
	require_once('../functions/select.php');
    require_once('../inc/functions/form_functions.inc.php'); 
	require_once('../functions/database.php');  
	require_once('../functions/CIFunctions.php');  
	
	$dbc = db_connect();      
	
	// Get current page file name
    $page = basename($_SERVER["PHP_SELF"]);
	
   $sql2 = "SELECT * FROM school_setup2";
   $result2 = $dbc->query($sql2) or die(mysqli_error());
   $row2 = $result2->fetch_array();

   $sch_full_name = $row2['sch_full_name'];
      $sch_short_name = $row2['sch_short_name'];
      $sch_motto = $row2['sch_motto'];
      $year_est = $row2['year_est'];
      $sch_email_add = $row2['sch_email_add'];
      $sch_phone1 = $row2['sch_phone1'];
      $sch_phone2 = $row2['Sch_phone2'];
      $sch_web = $row2['sch_web'];
      $sch_addr = $row2['sch_addr'];
      $principals_signature = $row2['principals_signature'];
?>	 
 
<?php 	  
$sql_test1 ="select * from exam_setup where Exam_names = 'Test1'";
 $data_test1 = $dbc->query($sql_test1) or die(mysqli_error());
 $row_test1 = $data_test1->fetch_array();
 $test1 = $row_test1['Exam_Max_Score'];
 $test1n = $row_test1['Exam_display_name']; 
 $test1pass = $row_test1['Exam_pass'];

 $sql_test2 ="select * from exam_setup where Exam_names = 'Test2'";
 $data_test2 = $dbc->query($sql_test2) or die(mysqli_error());
 $row_test2 = $data_test2->fetch_array();
 $test2 = $row_test2['Exam_Max_Score'];
 $test2n = $row_test2['Exam_display_name']; 
 $test2pass = $row_test2['Exam_pass'];

 $sql_test3 ="select * from exam_setup where Exam_names = 'Test3'";
 $data_test3 = $dbc->query($sql_test3) or die(mysqli_error());
 $row_test3 = $data_test3->fetch_array();
 $test3 = $row_test3['Exam_Max_Score']; 
 $test3n = $row_test3['Exam_display_name']; 
 $test3pass = $row_test3['Exam_pass'];

 $sql_test4 ="select * from exam_setup where Exam_names = 'Test4'";
 $data_test4 = $dbc->query($sql_test4) or die(mysqli_error());
 $row_test4 = $data_test4->fetch_array();
 $test4 = $row_test4['Exam_Max_Score'];
 $test4n = $row_test4['Exam_display_name']; 
 $test4pass = $row_test4['Exam_pass'];

  $sql_exam ="select * from exam_setup where Exam_names = 'Exam'";
 $data_exam = $dbc->query($sql_exam) or die(mysqli_error());
 $row_exam = $data_exam->fetch_array();
 $exam = $row_exam['Exam_Max_Score'];
 $exampass = $row_exam['Exam_pass'];

 $sql_total ="select * from exam_setup where Exam_names = 'Total Score Obtainable'";
 $data_total = $dbc->query($sql_total) or die(mysqli_error());
 $row_total = $data_total->fetch_array();
 $total = $row_total['Exam_Max_Score'];	 
 $totalpass = $row_total['Exam_pass'];
 
	 //connect to Database function          
	$dbc = db_connect();   
	$query_term ="select * from term where term_active = 1"; 
    $result_term = $dbc->query($query_term) or die(mysqli_error()); 
    $row_term = $result_term->fetch_assoc();
    $term_id = $row_term['term_id'];	
	
	$query_acc ="select * from acc_session where acc_session_id = 1"; 
    $result_acc= $dbc->query($query_acc) or die(mysqli_error()); 
    $row_acc = $result_acc->fetch_assoc();
    $acc_session_id = $row_acc['acc_session_id'];
    
    $query_session ="select * from session where session_active = 1";
    $result_session = $dbc->query($query_session) or die(mysqli_error()); 
    $row_session = $result_session->fetch_assoc();
    $session_id = $row_session['session_id'];
	
   $sql_ts = "SELECT term_session_id, term_name, session_name, 	date_start, date_end FROM term_session WHERE ts_active = '1'";
   $result_ts = $dbc->query($sql_ts) or die(mysqli_error());
   $row_ts = $result_ts->fetch_array();
   $ts_term_name = $row_ts['term_name'];
   $ts_session_name = $row_ts['session_name'];
   $ts_id = $row_ts['term_session_id'];

 
?>