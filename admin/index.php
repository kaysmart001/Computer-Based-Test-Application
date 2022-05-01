<!DOCTYPE html>
<html>

    <head>
<?php	
$dir =  basename(__DIR__);
$pgname = "Dashboard";
require_once('../_inc/inc_head.php');	
?>	</head>

    <body>

        <div id="wrapper">

<?php	require_once('_inc/inc_topnav.php');	?>
<?php	require_once('_inc/inc_sidebar.php');	?>
  <!-- /.navbar-static-side -->

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header text-asbestos">Dashboard</h3>
                    </div>
                </div>
                <!-- /.col-lg-12 -->

                
                <div class="row">
                    <div class="col-xs-6 col-md-3">
                        <div class="panel panel-primary text-center panel-eyecandy">
                            <div class="panel-body asbestos">
                                <i class="fa fa-edit fa-3x"></i>
                                <h3><?php echo countall('id','assessments'); ?></i></h3>
                            </div>
                            <div class="panel-footer">
                                <span class="panel-eyecandy-title">
                                    Total Exams
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-xs-6 col-md-3 -->
                    <div class="col-xs-6 col-md-3">
                        <div class="panel panel-primary text-center panel-eyecandy">
                            <div class="panel-body theme-color">
                                <i class="fa fa-question-circle fa-3x"></i>
                                <h3><?php echo countall('id','questions'); ?></h3>
                            </div>
                            <div class="panel-footer">
                                <span class="panel-eyecandy-title">
                                    Total Questions
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-xs-6 col-md-3 -->
                    <div class="col-xs-6 col-md-3">
                        <div class="panel panel-primary text-center panel-eyecandy">
                            <div class="panel-body asbestos">
                                <i class="fa fa-users fa-3x"></i>
                                <h3><?php echo countall('id','users'); ?></i></h3>
                            </div>
                            <div class="panel-footer">
                                <span class="panel-eyecandy-title">
                                    Total Students
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-xs-6 col-md-3 -->
                    <div class="col-xs-6 col-md-3">
                        <div class="panel panel-primary text-center panel-eyecandy">
                            <div class="panel-body theme-color">
                                <i class="fa fa-book fa-3x"></i>
                                <h3><?php echo countall('id','	tests'); ?> </i></h3>
                            </div>
                            <div class="panel-footer">
                                <span class="panel-eyecandy-title">
                                    Total Test
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-xs-6 col-md-3 -->
                </div>
                
              <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <p>
                        <a href="view_exam.php"class="btn btn-social btn-info btn-lg"><i class="fa fa-edit"></i> Manage Assessment</a>
                        <a href="question_bank.php"><button type="button" class="btn btn-info btn-lg"><i class="fa fa-pencil"></i> Questions Bank </button></a>
                        <a href="students.php"><button type="button" class="btn btn-info btn-lg"><i class="fa  fa-users"></i> Manage Students </button></a>
                        <a href="exam_stats.php"><button type="button" class="btn btn-info btn-lg"><i class="fa fa-bar-chart-o"></i> Exam Statistics</button></a>
                        <a href="settings.php"><button type="button" class="btn btn-info btn-lg"><i class="fa fa-gear"></i> System Settings</button></a>
                        </p>
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">


                </div>
                <!-- /.row -->
            </div>	
                <?php	require_once('footer_bar.php');	?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js.php');	?>

    </body>

</html>
