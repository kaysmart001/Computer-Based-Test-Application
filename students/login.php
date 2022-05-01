<?php ob_start();   
$dir =  basename(__DIR__);
 
session_start();  
    require_once('../_inc/functions/CIFunctions.php');
    $page = basename($_SERVER["PHP_SELF"]);

checkLoginUser();
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

      $sql = "SELECT * FROM users WHERE userid = '$username' AND password  = '$password' and sch = '1' ";
        //echo $sql;
        $result = $dbc->query($sql) or die(mysqli_error());
        // submit the query and capture the result
        $row = $result->fetch_assoc();

    if ($result->num_rows > 0) 
        {
            //$row = mysqli_fetch_array($data);
             $_SESSION['uid'] = $row['id'];
             $_SESSION['username'] = $row['userid'];
             $login = $row['login'];

             fieldupdate('users', 'login', '1', 'userid', $username, $dbc); // set login active

             if($login == 0){
              
            setcookie('uid', $row['userid'], time() + (60 * 60 * 24 * 1));    // expires in 1 days
            setcookie('username', $row['userid'], time() + (60 * 60 * 24 * 1));    // expires in 1 days
           $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
            header('Location: '.$home_url);

            }else{
                 $msg = '<span class="text-center">
                            User is already Signed In. Please Sign Out or Continue with the system you signed in on 
                        </span>';
                 $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
                header('Location: '.$home_url);
            }

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
    $pgname = "User Login";
    require_once('../_inc/inc_head.php');	?>

        <script>
                window.localStorage.clear();
        </script>
    </head>

    <body>
            <nav class=" navbar navbar-default navbar-static-top" role="navigation">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php" style="float: none;"><img class="brand-logo" src="../_res/img/logo.png" alt="" /></a>
                </div>
                <span class=" navbar-left">
                    <img class="logo2" src="../_res/img/logo2.png" alt="" />
                </span>
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
                            <h3 class="panel-title text-center"> User Log In</h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post" action="" />
                                <fieldset>     
                                    <div class="form-group">
                                        <input class="form-control"  name="username" type="text" autofocus="" placeholder="Username" required/>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control"  name="password" type="password" value="" placeholder="Password" required/>
                                    </div>
                                    <!-- Change this to a button or input when using this as a form -->
                                    <button name="submit" class="btn btn-lg btn-primary btn-block">Login</button>
                                </fieldset>
                            </form>
                        </di63v>
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
 <?php mysqli_close($dbc); ?>
</html>
