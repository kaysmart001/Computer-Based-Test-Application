<?php ob_start();
	  require_once('functions/CIFunctions.php');  
	
	$page = basename($_SERVER["PHP_SELF"]);

	date_default_timezone_set("Africa/Lagos");

	if($page != 'login.php' AND $pgname != 'presentation' ) {
		if($dir == "admin"){  checkNotLoginAdmin(); }
		else{ checkNotLoginUser(); }
	 } 
?>
 
		<title> <?php echo $pgname; ?> - Examinar</title>        
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=no" />
        <meta name="description" content="Examinar - A complete E-exam application with Intuitive, cutting-edge, clean and easy to use. " />
        <meta name="author" content="Awonuga S.O" />

        <!-- Core CSS - Include with every page -->
        <link href="../_res/css/bootstrap.min.css" rel="stylesheet" />
        <link href="../_res/css/font-awesome.min.css" rel="stylesheet" />

        <!-- Page-Level Plugin CSS - Dashboard -->
        <link href="../_res/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <link href="../_res/css/plugins/timeline/timeline.css" rel="stylesheet" />

        <!-- Admin CSS - Include with every page -->
        <link href="../_res/css/admin2.css" rel="stylesheet" />

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />