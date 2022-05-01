<!DOCTYPE html>
<html>

    <head>
	<?php	$dir =  basename(__DIR__);
	$pgname = "Test History";
	
        require_once('../_inc/inc_head.php');	

        $lname = $fname = $mname = $username = '';
        if(isset($_GET['stid'])){
            $uid = $_GET['stid']; 
            $userdata = userquery($uid);
            $count = count($userdata);
            for($i = 0; $i < $count; $i++){
                $lname = $userdata[$i]['lname'];
                $fname = $userdata[$i]['fname'];
                $mname = $userdata[$i]['mname'];
                $username = $userdata[$i]['userid'];
            }
        }
        ?>	
      <!-- Page-Level Plugin CSS - Tables -->
	<link href="../_res/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
	</head>

    <body>

        <div id="wrapper">

<?php	require_once('_inc/inc_topnav.php');	?>
<?php	require_once('_inc/inc_sidebar.php');	?>
  <!-- /.navbar-static-side -->


            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header">Test History</h3>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <?php  echo "<b>{$username} - {$lname} {$fname} {$mname}</b>"; ?>
                                <span style="float:right;">
                                    <button class="btn btn-info btn-xs" onclick="goBack()"><i class="fa  fa-backward "></i> Back  </button>
                                </span>
                            </div>
                            <!-- /.panel-heading -->
                                                   <div class="panel-body">		
                            <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Test Title</th>
                                                <th>Session</th>
                                                <th>Questions</th>
                                                <th>Correct</th>
                                                <th>Incorrect</th>
                                                <th>Unanswered</th>
                                                <th>Test Mark</th>
                                                <th>Score</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                           view_score_history($uid); 
                                           ?>
                                       </tbody>
                                    </table>
                            </div>   
                        </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /#page-wrapper -->

            		<?php	require_once('footer_bar.php');	?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js.php');	?>

    </body>

</html>
