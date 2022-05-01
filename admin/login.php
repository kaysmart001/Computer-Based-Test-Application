<?php ob_start();   
$dir =  basename(__DIR__);
 
session_start();  
    require_once('../_inc/functions/CIFunctions.php');
    $page = basename($_SERVER["PHP_SELF"]);

    function checkLoginAdmin(){ // Redirect user to index  if logged in to Admin
    global $url;  
            if(isset($_SESSION['aid'])){
            header("location:".$url."/admin/login.php");
            }
    } 
    
  // If the user isn't logged in, try to log them in
  if (isset($_POST['submit']))  {
      $error_msg = "";   
    
    $username=$_POST['username'];
    $password=$_POST['password'];  
    
    // Grab the user-entered log-in data
    $username = strtolower(stripslashes(strtolower($username)));
    $password = strtolower(stripslashes(strtolower($password)));   
    
    $username = $dbc->real_escape_string($_POST['username']);
    $password = $dbc->real_escape_string($_POST['password']);

      $sql = "SELECT * FROM settings WHERE admin_id = '$username' AND password  = '$password'";
        //echo $sql;
        $result = $dbc->query($sql) or die(mysqli_error());
        // submit the query and capture the result
        $row = $result->fetch_assoc();

    if ($result->num_rows > 0) 
        {
            //$row = mysqli_fetch_array($data);
             $_SESSION['aid'] = $row['admin_id'];
             $_SESSION['admin_name'] = $row['admin_id'];
              
            setcookie('aid', $row['admin_id'], time() + (60 * 60 * 24 * 1));    // expires in 1 days
           $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/confirm_session.php';
            //$home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/dashboard.php';
            //echo  $home_url;
            header('Location: '.$home_url);
            $msg = 'Login Successful';
        }
        else {

          // The username/password are incorrect so set an error message
          $msg = 'Incorrect Username or Password Combination.';
        }

    } ?>


<!DOCTYPE html>
<html>

    <head>

    <head>
	<?php	$page = basename($_SERVER["PHP_SELF"]); 
    $pgname = "Admin Login";
	require_once('../_inc/inc_head.php');	?>	
	</head>
	</head>

    <body>
            <nav class=" navbar navbar-default navbar-static-top" role="navigation">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php" style="float: none;"><img class="brand-logo" src="../_res/img/logo.png" alt="" /></a>
                </div>
                <!-- /.navbar-header -->
            </nav>
            <!-- /.navbar-static-top -->
        <div class="container">

            <div class="row">
                <div class="col-md-4 col-md-offset-4">

                    <div class="login-panel panel panel-default">
                    <?php if (isset($msg)) { ?>
                            <div class="alert alert-danger fade in widget-inner">
                                <i class="fa fa-times"></i> <?php echo $msg; ?>
                            </div>
                        <?php }?>
                        <div class="panel-heading">
                            <h3 class="panel-title text-center"> Admin Log In</h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post" action="" />
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control"  name="username" type="text" autofocus="" placeholder="Username" />
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control"  name="password" type="password" value="" placeholder="Password"/>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input name="remember" type="checkbox" value="Remember Me" />Remember Me
                                        </label>
                                    </div>
                                    <!-- Change this to a button or input when using this as a form -->
                                    <button name="submit" class="btn btn-lg btn-primary btn-block">Login</button>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Core Scripts - Include with every page -->
        <script src="../_res/js/jquery-1.10.2.js"></script>
        <script src="../_res/js/bootstrap.min.js"></script>
        <script src="../_res/js/plugins/metisMenu/jquery.metisMenu.js"></script>

        <!-- Mint Admin Scripts - Include with every page -->
        <script src="../_res/js/admin.js"></script>

    </body>

</html>
