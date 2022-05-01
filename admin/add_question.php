<!DOCTYPE html>
<html>

    <head>
        <?php
        $dir = basename(__DIR__);
        $pgname = "Add Questions ";
        require_once('../_inc/inc_head.php');
        ?>	</head>

    <body>

        <div id="wrapper">

<?php require_once('_inc/inc_topnav.php'); ?>
<?php require_once('_inc/inc_sidebar.php'); ?>
            <!-- /.navbar-static-side -->

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header">Questions</h3>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Add a Question
                            </div>

                            <div class="panel-body">
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    Question 1 added to database.
                                </div>					
                                <div class="row show-grid">
                                    <div class="col-md-10 blue"><b>Question : 2</b></div>
                                    <div class="col-md-2" style="padding:5px;"><button type="submit" class="btn btn-info  btn-sm" >Cancel</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form role="form" />
                                        <div class="form-group">
                                            <label>Question:</label>
                                            <textarea class="form-control" rows="3"></textarea>
                                            <p class="help-block">Show <b>Editor</b> (html is allowed in all textarea boxes)</p>
                                        </div>

                                        <hr>

                                        <div class="form-group">
                                            <label>Full Answer:</label>
                                            <textarea class="form-control" rows="3"></textarea>
                                            <p class="help-block"> <i>(Optional)</i> If you allow the entrant to view the Exam result, then this will be shown too.</p>
                                        </div>

                                        <hr>

                                        <div class="form-group">
                                            <label>Option A:</label>
                                            <textarea class="form-control" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Option B:</label>
                                            <textarea class="form-control" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Option C:</label>
                                            <textarea class="form-control" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Option D:</label>
                                            <textarea class="form-control" rows="3"></textarea>
                                        </div>

                                        </form>
                                    </div>

                                    <div class="col-lg-12">
                                        <form role="form" />
                                        <div class="form-group">
                                            <label>Choices should be random?</label>
                                            <label class="radio-inline">
                                                <input type="radio" name="RandChoice" id="RandChoice" value="1" /> Yes
                                                <input type="radio" name="RandChoice" id="RandChoice" value="0" checked=""/> No
                                            </label>
                                        </div>											                                     
                                        <button type="submit" class="btn btn-primary">Next Question</button>
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
<?php require_once('footer_bar.php'); ?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
<?php require_once('../_inc/inc_js.php'); ?>

    </body>

</html>
