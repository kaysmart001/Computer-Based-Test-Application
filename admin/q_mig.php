<?php 
require_once('../_inc/inc_head.php');

$query1 =  "SELECT * FROM session";

$sql_result = mysqli_query($dbc, $query1);
$row = mysqli_fetch_assoc($sql_result);

for(;$row = mysqli_fetch_assoc($sql_result);){
	echo $row['id'];
}
?>