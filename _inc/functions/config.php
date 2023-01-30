<?php
//server cofiguration setting
$host = 'sql311.epizy.com';

$user = 'epiz_33459705';
$password = 'hvZgW4p2guu7';
$database = 'epiz_33459705_onlinetest_db';


//establish connection to server and database
$dbc = @mysqli_connect($host, $user, $password, $database) or die($dbc_error = '<div id="serverstatus"><div class="alert2 alert-danger">Unable to connect to database.</div></div>');

if ($dbc) {
  $dbc_success =  '<div class="alert2 alert-success">Connnetion to database Successful.</div>';
}
