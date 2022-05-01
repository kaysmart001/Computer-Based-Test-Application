
<?php

require_once('../_inc/functions/config.php');
  // If the user is logged in, delete the session vars to log them out
  session_start();
  if (isset($_SESSION['aid'])) {

    // Delete the session vars by clearing the $_SESSION array
    $_SESSION = array();

    // Delete the session cookie by setting its expiration to an hour ago (3600)
    if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time() - 36000);
    }

    // Destroy the session
    session_destroy();
  }
  // Delete the user ID and username cookies by setting their expirations to an hour ago (3600)
  setcookie('aid', '', time() - 36000);
  
  // Redirect to the home page
  $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
 // $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/login.php';
  header('Location: ' . $home_url);
?>