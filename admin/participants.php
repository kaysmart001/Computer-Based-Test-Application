
<!DOCTYPE html>
<html>

    <head>
	<?php	$dir =  basename(__DIR__); 
	$pgname = "View Exam";
	require_once('../_inc/inc_head.php');?>	
	</head>

    <body>

        <div id="wrapper">

<?php	require_once('_inc/inc_topnav.php');	?>
<?php	require_once('_inc/inc_sidebar.php');	?>
  <!-- /.navbar-static-side -->

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header">Participant </h3>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        
                        <!-- Button trigger modal -->
                        <button class="btn btn-info" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-edit"></i>  Display Round 1 Scoreboard
                        </button>
                        <!-- Modal -->
                    </div>
                </div>
                    <div class="panel panel-default">
                            <div class="panel-heading">
                                Participants Records
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>School</th> 
                                                <th>Score 1</th>                                     
                                                <th>Score 2</th>                                     
                                                <th>Bonus</th>                                     
                                                <th>Status</th>                                     
                                                <th width="190px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <tr>
                                            <td>1</td>
                                            <td>Adewale Hassan</td>
                                            <td>TASUED</td>
                                            <td>98</td>
                                            <td>50</td>
                                            <td>0</td>                                          
                                            <td class="text-success">Active</td>
                                            <td>                                                                                                                      
                                               <button type="button" class="btn btn-info btn-xs"> Start Test </button> 
                                               <a href="add_test.php?ass=2" class="btn btn-primary btn-xs"> View Questions </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>1</td>
                                            <td>Adewale Hassan</td>
                                            <td>TASUED</td>
                                            <td>98</td>
                                            <td>50</td>
                                            <td>0</td>                                          
                                            <td class="text-info">Inactive</td>
                                            <td>                                                                                                                      
                                               <button type="button" class="btn btn-info btn-xs"> Start Test </button> 
                                               <a href="add_test.php?ass=2" class="btn btn-primary btn-xs"> View Questions </a>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
            </div>
            		<?php	require_once('footer_bar.php');	?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js.php');	?>

    </body>

</html>
