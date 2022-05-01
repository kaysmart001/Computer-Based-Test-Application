
   
<?php $excelFileName = 'ALL-STUDENTS-LIST.xls';       
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
        ?>

<body>

<div class="container2">
  <div class="content">
  
<div><div style="margin: 0% 15%; "> <?php      require_once('inc_Excel_Header.php'); ?></div></div>

 <?php   $sql_ts = "SELECT term_session_id, term_name, session_name, session_id, date_start, date_end FROM term_session WHERE ts_active = '1'";
   $result_ts = $dbc->query($sql_ts) or die(mysqli_error());
   $row_ts = $result_ts->fetch_array();
   $ts_term_name = $row_ts['term_name'];
   $ts_session_name = $row_ts['session_name'];
   $ts_id = $row_ts['term_session_id'];
    $ss_id = $row_ts['session_id'];
 ?>
     <div class="clear"></div>
     <div class="line"></div>
	   <div class="title2"> ALL STUDENTS' LIST </div>

  <div class="wrap2">  
    <!-- Main content wrapper -->
  <div class="wrap2">
	<table class="altrowstable" id="alternatecolor">
	<tr>
		<td>Session: <b><?php echo $ts_session_name ?></b></td> 
		<td> Term: <b><?php echo $ts_term_name; ?></b></td>

	</tr> 
	</table>
  </div>	

 <div class="wrap2">	
	<table class="altrowstable" id="alternatecolor" border="1" >
									
       <thead>
            <tr>
            <th width="30px">No.</th>
            <th  width="130px">Surname </th>
            <th  width="130px">First Name </th>
			<th  width="100px">Class </th>
            <th></th>
            </tr>
            </thead>
            <tbody>						
<?php	$sql ="SELECT st.student_id, st.lastname, st.surname, st.firstname, st.gender,  st.ts_id, scr.class_details_id, scr.term_id, scr.session_id, cd.class_level, scr.student_id, cd.class_details, cd.class_details_id  
		from  student st, student_class_reg scr,  class_details cd 
		where cd.class_details_id  = scr.class_details_id  
		AND scr.session_id = '$session_id'
		AND scr.student_id = st.student_id
		ORDER BY cd.class_level ";
		$result_1 = mysqli_query($dbc, $sql);  

					$r =1; while ($row = mysqli_fetch_array($result_1)) { 
					$class_level=$row['class_level'];
					$student_id=$row['student_id'];
					$stu_ts_id=$row['ts_id'];
					
					 ///student class payment bill	?>
					 <tr>
                                        <td ><?php echo $r; $r++; ?></td>
                                       	<td class="aa"><?php echo strtoupper($row['surname']); ?> </td>
                                       	<td class="aa"><?php echo strtoupper($row['firstname']); ?> </td>
										<td><?php echo $row['class_details']; ?></td>					
										<td class="red">  </td> 																	
							</tr>
                                 <?php  } ?>

                     </tbody>

					 </table>

	</div>	
		</div>					
			</div>
 <!-- end .container --></div> 
 
 <div class="clear"> </div>
  
</body>
</html>

