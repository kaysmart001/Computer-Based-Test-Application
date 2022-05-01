<?php
//server cofiguration setting
$host = 'localhost';

$user = 'root';
$password = '!Opensecsemy2';
$database = 'cbt_old';


//establish connection to server and database
$dbc = @mysqli_connect($host, $user, $password, $database) or die($dbc_error = '<div id="serverstatus"><div class="alert2 alert-danger">Unable to connect to database.</div></div>');

if ($dbc) {
  $dbc_success =  '<div class="alert2 alert-success">Connnetion to database Successful.</div>';
}
