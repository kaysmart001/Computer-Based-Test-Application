<?php ob_start();   
$dir =  basename(__DIR__);
 
session_start();  
    require_once('../_inc/functions/CIFunctions.php');
    $page = basename($_SERVER["PHP_SELF"]);

    //$_SESSION['user_id']; 

  if (isset($_POST['changeSession']))  {
    //print_r($_POST);
      $session_id=$_POST['session_id'];
        
      fieldupdate('session', 'session_active', '0', 'id', $csession_id, $dbc); // set current session inactive
      fieldupdate('session', 'session_active', '1', 'id', $session_id, $dbc); // set new session active

     $csession = nameId('session_active', 'session_name', 'session', '1');

      $sucesss_msg = "Session Changed Successfully";
    } 

  if (isset($_POST['addSession']))  {
    //print_r($_POST);
      $SessionActive=$_POST['SessionActive'];
      $nsession_name=$_POST['session_name'];
      $npmark = $_POST['npmark'];

       $check_session = countdata('session_name', $nsession_name, 'session');

       if ($check_session == 0){
            if ($SessionActive == 1){
                fieldupdate('session', 'session_active', '0', 'id', $csession_id, $dbc); // set current session inactive
            }
       echo $sql = "INSERT into session ( `session_name`, `session_active`, passmark) VALUES ('$nsession_name', '$SessionActive', '$npmark');";
        $sql_result = mysqli_query($dbc,$sql) or die(mysqli_error($dbc));
        $sucesss_msg = '<span class="text-info"> <i class="fa fa-check"></i> New Session Added Successfully</span>';

         }else{
            $sucesss_msg = '<span class="text-danger"> <i class="fa fa-times"></i> Session Cannot be NOT Added: Session Already Exit </span>';
         }   

     $csession = nameId('session_active', 'session_name', 'session', '1');

    }

    ?>


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
                <a class="navbar-brand" href="index.php"><img class="brand-logo" src="../_res/img/logo.png" alt="" /></a>
            </div>
            <!-- /.navbar-header -->
        </nav>
            <!-- /.navbar-static-top -->
        <div class="container">

            <div class="row">
                <div class="col-md-8 col-md-offset-2">

                    <div class="login-panel panel panel-default" style="margin-top: 7%;">
                    <?php if (isset($msg)) { ?>
                            <div class="alert alert-danger fade in widget-inner">
                                <i class="fa fa-times"></i> <?php echo $msg; ?>
                            </div>
                        <?php }?>
                        

                        <div class="panel-heading">
                            <h3 class="panel-title text-center"> Confirm Active Session</h3>
                        </div>
                        <div class="panel-body">
                            <div class="well">
                                <h3 class="text-center text-primary"> <i class="fa fa-clock-o fa-fw"> </i> The Current Active session is <b><?php echo $csession; ?> </b><span id="duration"> </span></h3>
                             </div> 
                              <?php if (isset($sucesss_msg)) { echo $sucesss_msg; }?>  
                            <div class="well">
                                <form role="form" method="post" action="" />
                                    <fieldset>
                                        <div class="form-group">
                                            <label>Select to Change Active Session</label>
                                            <select class="form-control" name="session_id">
                                             <?php  echo select_option2_desc('session_name', 'id', 'session', $dbc); ?>
                                            </select>
                                        </div> 
                                        <button type="submit" name="changeSession" class="btn btn-info">Change Active Session</button>
                                        <button type="button" data-toggle="modal" data-target="#addSessionModal" class="btn btn-warning">Create New Session</button>                           
                                    </fieldset>
                                </form>
                            </div>
                        </div>

                        <div class="modal-footer" style="margin-top: 0px;">
                                <a href="index.php"><button type="submit" name="startexam" class="btn btn-primary">Continue with <?php echo $csession; ?></button></a>
                        </div>
                    </div>
                </div>
            </div>


                 <!-- //  Add  Session Modal --> 
                <div class="modal fade" id="addSessionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-clock-o"></i>  Add Session </h4>
                            </div>
                                <form role="form" method="post" action="" />
                                    <div class="modal-body">                                                                                 
                                        <fieldset>
                                            <div class="form-group">
                                                <label>Enter New Session name </label>
                                                <input class="form-control" name="session_name" placeholder="Enter Session Name">                                              
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon">Passmark:</span>
                                                <input type="text" name="npmark" class="form-control" required="">
                                            </div>
                                            <div class="form-group">
                                                <label>Make this Session Active Session? </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="SessionActive" value="1" checked="" /> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="SessionActive" value="0" /> No
                                                </label>
                                            </div> 
                                        </fieldset>                                                           
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" name="addSession" class="btn btn-primary">Add Session</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!-- //  Add  Session Modal --> 
            </div>

        <!-- Core Scripts - Include with every page -->
        <script src="../_res/js/jquery-1.10.2.js"></script>
        <script src="../_res/js/bootstrap.min.js"></script>
        <script src="../_res/js/plugins/metisMenu/jquery.metisMenu.js"></script>

        <!-- Mint Admin Scripts - Include with every page -->
        <script src="../_res/js/admin.js"></script>

    </body>

</html>
