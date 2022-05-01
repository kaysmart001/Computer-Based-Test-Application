<!DOCTYPE html>
<html>

    <head>
<?php	$dir =  basename(__DIR__);
$pgname = "Dashboard";
require_once('../_inc/inc_head.php');	?>	</head>

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
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-bar-chart-o fa-fw"></i> Daily Visits
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                            Actions
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div id="morris-dashboard-chart"></div>
                            </div>
                            <!-- /.panel-body -->
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>

                <div class="row">
                    <div class="col-xs-6 col-md-3">
                        <div class="panel panel-primary text-center panel-eyecandy">
                            <div class="panel-body asbestos">
                                <i class="fa fa-edit fa-3x"></i>
                                <h3>5,741</i></h3>
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
                                <h3>32</h3>
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
                                <h3>437</i></h3>
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
                                <h3>47 </i></h3>
                            </div>
                            <div class="panel-footer">
                                <span class="panel-eyecandy-title">
                                    Total Admin
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-xs-6 col-md-3 -->
                </div>


                <!-- /.row -->
                <div class="row">


                </div>
                <!-- /.row -->
            </div>		<?php	require_once('footer_bar.php');	?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js.php');	?>

    </body>

</html>
