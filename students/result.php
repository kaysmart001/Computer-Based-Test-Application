<!DOCTYPE html>
<html>


    <head>
	<?php	
	$dir =  basename(__DIR__);
	$page = basename($_SERVER["PHP_SELF"]); 
    $pgname = "End Exam";
	require_once('../_inc/inc_head.php');	?>	
	
		<script>
			window.localStorage.clear();
		</script>
	</head>

    <body>
        <!-- Core Scripts - Include with every page -->
		<?php	require_once('_inc/inc_topnav_test.php');	?>
		
            <!-- /.navbar-static-top -->
        <div class="container">

            <div class="row">
                <div class="col-md-8 col-md-offset-2">

<?php if((isset($_GET['ps'])) && ($_GET['ps'] == 'tresult')){ //ps = page session, tresult = Total results ?>
                    
		<div class="login-panel panel panel-default" style="margin-top: 10%;">
                    <?php if (isset($msg)) { ?>
                            <div class="alert alert-danger fade in widget-inner">
                                <i class="fa fa-times"></i> <?php echo $msg; ?>
                            </div>
                        <?php }?>
                        <div class="panel-heading">
                            <h3 class="panel-title text-center"> Total results </h3>
                        </div>
                        <div class="panel-body">		
                             <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Test Title</th>
                                                <th>Questions</th>
                                                <th>Correct</th>
                                                <th>Incorrect</th>
                                                <th>Unanswered</th>
                                                <th>Test Mark</th>
                                                <th>Score</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(isset($_GET['ass'])){
                                                        $assid = $_GET['ass'];
                                                }else{  $assid = '0'; }
                                                        view_score($assid);  ?>
                                       </tbody>
                                    </table>
                                </div>   
                        </div>
                        <div class="modal-footer" style="margin-top: 0px;">
                            <a href="logout.php"><button type="button" class="btn btn-default">Log Out</button></a>
                            <a href="index.php"><button type="button" class="btn btn-primary">Back to Homepage</button>
                        </div>
                    </div>

<?php }else{
	$assid = $_GET['ass'];
	?>
                    <div class="login-panel panel panel-default"   style="margin-top: 10%;">
                    <?php if (isset($msg)) { ?>
                            <div class="alert alert-danger fade in widget-inner">
                                <i class="fa fa-times"></i> <?php echo $msg; ?>
                            </div>
                        <?php }?>
                        <div class="panel-body">
                            <h4>Exam Ended Successfully.</h4>
                            <p>The username <b> <?php echo $userid; ?> </b> is still "signed in".</p>
                                <a href="logout.php"><button type="button" class="btn btn-full-c btn-danger glow  "><i class="fa fa-power-off"></i> Log out User </button></a>
                                <a href="index.php"><button type="button"  class="btn btn-default btn-full-c"><i class="fa fa-home"></i> Back to Homepage </button></a>

                            <h4>Click on the following to check your result</h4>
                                <a href="result.php?ps=tresult&&ass=<?php echo $assid; ?>"><button type="button"  class="btn btn-info btn-full-c"> View Result</button></a>
                                <a href="view_test.php?ass=<?php echo $assid; ?>"><button type="button" class="btn btn-info btn-full-c"> View Answers</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php } ?>

        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js.php');	?>
        
        <script type="text/javascript">
            setTimeout(function () {
                 window.location.replace('logout.php');
                }, 3000000);
        </script>
    
    </body>
     <?php mysqli_close($dbc); ?>
</html>
