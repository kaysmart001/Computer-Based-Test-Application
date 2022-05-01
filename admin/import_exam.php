<!DOCTYPE html>
<html>

    <head>
<?php	$dir =  basename(__DIR__);
require_once('../_inc/inc_head.php');	?>
	</head>

    <body>

        <div id="wrapper">
            <?php	require_once('_inc/inc_topnav.php');	?>
            <?php	require_once('_inc/inc_sidebar.php');	?>
              <!-- /.navbar-static-side -->

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header">Exams</h3>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Add a New Exam
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form role="form" />
											<div class="form-group">
                                                <label>Import A Question File</label>
                                                <input type="file">
                                            </div>

                                    </div>
                                    <!-- /.col-lg-6 (nested) -->
                                    <div class="col-lg-6">
                                        <form role="form" />
                                            <div class="form-group">
                                                <label>Allow student to Register?</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="StuReg" id="StuReg" value="1" checked="" /> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="StuReg" id="StuReg" value="0" /> No
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>Questions are Random?</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="RandomQ" id="RandomQ" value="1" checked="" /> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="RandomQ" id="RandomQ" value="0" /> No
                                                </label>
                                            </div>                                            
											<div class="form-group">
                                                <label>Does this Exam have negative mark?</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="NegativeQ" id="NegativeQ" value="1" checked="" /> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="NegativeQ" id="NegativeQ" value="0" /> No
                                                </label>
                                            </div>											
											<div class="form-group">
                                                <label>Show correct answers after finishing the Exam?</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ShowAns" id="ShowAns" value="1" checked="" /> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ShowAns" id="ShowAns" value="0" /> No
                                                </label>
                                            </div>											
											<div class="form-group">
                                                <label>Show grade after finishing the Exam?</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ShowGrade" id="ShowGrade" value="1" checked="" /> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ShowGrade" id="ShowGrade" value="0" /> No
                                                </label>
                                            </div>											
											<div class="form-group">
                                                <label>Show entrants their ranks?</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ShowRank" id="ShowRank" value="1" checked="" /> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ShowRank" id="ShowRank" value="0" /> No
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>Selects Session</label>
                                                <select class="form-control">
                                                    <option />1
                                                    <option />2
                                                </select>
                                            </div>                                       
                                            <button type="submit" class="btn btn-primary">Save and Continue</button>
											</form>
                                        
                                    </div>
                                    <!-- /.col-lg-6 (nested) -->
                                </div>
                                <!-- /.row (nested) -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
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
