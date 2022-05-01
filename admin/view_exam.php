

<!DOCTYPE html>
<html>

    <head>
	<?php	$dir =  basename(__DIR__); 
	$pgname = "View Exam";
	require_once('../_inc/inc_head.php');?>	
	</head>

 
  
	<?php
            if(isset($_POST["create_ass"])){
            //print_r($_POST);
                $ass_code = test_input($_POST["ass_code"]);
                $ass_type = test_input(strtolower($_POST["ass_type"]));
                $ass_title = test_input($_POST["ass_title"]);
                $ca_score = test_input($_POST["ca_score"]);
                $exam_score = test_input($_POST["exam_score"]);
                $csession_id = $csession_id;
                $id = test_input($_POST["ass_code"])."-".$csession_id;

                $sql_insert = "INSERT INTO assessments (id, assessments_code, assessments_name, assessments_type,  timestamp, session_id, ca_score, exam_score) 
                                        VALUES( '$id','$ass_code', '$ass_title', '$ass_type', CURRENT_TIMESTAMP, '$csession_id', $ca_score, $exam_score )";
                $sql_result = mysqli_query($dbc, $sql_insert) or die(mysqli_error($dbc));
                if($sql_result){
                $msg = "Registration Successful Please <a href='login.php'><b> Sign in to Continue </b></a>";									
                        }else{
                                $msg = "Registration NOT Successful";
                        }
                }
            //}

            if (isset($_POST["all_test_status"])) {
                //print_r($_POST);
                $test_status = test_input($_POST["all_test_status"]);

                $sql_update = "UPDATE tests SET be_default = $test_status, active = $test_status ";
                $sql_result = mysqli_query($dbc, $sql_update);
                if ($sql_result) {
                    if($test_status == 1){
                        $msg = "All Test Activated Successfully";
                    }else{
                        $msg = "All Test Deactivated Successfully";
                    }
                    
                } else {
                    $msg_err = mysqli_error($dbc);
                }
            }

            if (isset($_POST["camera_status"])) {
                //print_r($_POST);
                $test_status = test_input($_POST["camera_status"]);

             $sql_update = "UPDATE settings SET image_capture = $test_status";
                $sql_result = mysqli_query($dbc, $sql_update);
                if ($sql_result) {
                    if($test_status == 1){
                        $msg = "Image Capture Activated Successfully";
                    }else{
                        $msg = "Image Captured Deactivated Successfully";
                    }
                    
                } else {
                    $msg_err = mysqli_error($dbc);
                }
            }

            $image_capture = nameId('id', 'image_capture', 'settings', 1);



            ?>
    <body>

        <div id="wrapper">

<?php	require_once('_inc/inc_topnav.php');	?>
<?php	require_once('_inc/inc_sidebar.php');	?>
  <!-- /.navbar-static-side -->

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header">Assessments </h3>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>

            <?php if (isset($msg)) { ?>    
                <div class="alert alert-default alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                    <a href="#" class="alert-link "><?php echo $msg; ?></a>
                </div>                            
            <?php } ?> 
                  
            <?php if (isset($msg_err)) { ?>    
                <div class="alert alert-default alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                    <span class="red2"><b><?php echo $msg_err; ?></b></span>
                </div>                            
            <?php } ?>

                <!-- /.row -->
                <div class="panel panel-default">
                    <div class="panel-body">

                        <form method="post" action="" >
                            <!-- Button trigger modal -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-edit"></i>  Add New Assessments
                            </button>
                            <?php if($image_capture == 1){ ?>
                                 <!--<button class="btn btn-danger" type="submit" value="0"  name="camera_status">  <i class="fa fa-times"></i>  Deactivate Image Capture </button> -->
                            <?php }else{ ?>
                                <button class="btn btn-info" type="submit" value="1"  name="camera_status">  <i class="fa fa-check"></i>  Activate Image Capture </button>
                            <?php } ?>
 
                            <a href="view_test_images.php" class="btn btn-primary">  
                            <i class="fa fa-eye"></i> View Test Images</a>
                            <button class="btn btn-danger" type="submit" value="0" name="all_test_status">  <i class="fa fa-times"></i> Deactivate All Test</button>
                            <button class="btn btn-info" type="submit" value="1"  name="all_test_status">  <i class="fa fa-check"></i>  Activate All Test </button>
                            <button class="btn btn-info" type="submit" value="1"  name="dwd_db">  <i class="fa fa-check"></i>  Download Center DB </button>

                         </form>
                    </div>
                </div>

                    
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i>  Add New Assessments</h4>
                                </div>
                                <form role="form"  action="" method="post">

                                    <div class="modal-body">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Assessments Code:</span>
                                            <input type="text" class="form-control" name="ass_code" placeholder="Course Code" required>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Assessments Title:</span>
                                            <input type="text" class="form-control" name="ass_title" placeholder="Course Title" required>
                                        </div>                                          
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Assessments Type:</span>
                                            <select name="ass_type" class="form-control">
                                            <?php  echo select_option2('assessment_type', 'id', 'assessments_type', $dbc); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6" style="padding-left: 0px">
                                            <div class="form-group input-group">
                                                <span class="input-group-addon">CA:</span>
                                                <input type="text" class="form-control" name="ca_score" placeholder="CA Score" required>
                                            </div> 
                                        </div> 
                                        <div class="col-md-6" style="padding-right: 0px"> 
                                            <div class="form-group input-group">
                                                <span class="input-group-addon">EXAM:</span>
                                                <input type="text" class="form-control" name="exam_score" placeholder="Exam Score" required>
                                            </div> 
                                        </div> 
                                        <div class="clearfix"></div>
                                                                                                              
                                     </div>
                                     <div class="modal-footer">
                                             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                             <button type="submit" name="create_ass" class="btn btn-primary">Create Assessments</button>
                                         </div>
                                </form>
                        </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                <div class="panel panel-default">
                            <div class="panel-heading">
                                NOTE:  Passmark is  <b><?php echo $passmark; ?></b> for the current Session.. 
                            
                                <?php if($image_capture == 1){ ?>
                                    Image Capture Activated 
                                <?php }else{ ?>
                                    Image Capture Deactivated 
                                <?php } ?>

                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Test Added</th> 
                                                <th>Type</th>                                     
                                                <th>Student Added</th>                                     
                                                <th>Batch No</th>                                     
                                                <th>Session</th>                                     
                                                <th width="190px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                        <?php 
                            $query = "  SELECT ass.id, ass.assessments_code, asst.assessment_type, se.session_name, ass.multiple_test
                                        FROM assessments ass, 
                                        session se, assessments_type asst
                                        WHERE ass.session_id = se.id
                                        AND ass.assessments_type = asst.id
										AND ass.session_id = '$csession_id'
                                        ORDER BY ass.id DESC"; 
                            $data = mysqli_query($dbc, $query);
                            $count = mysqli_num_rows($data);
                            
                            $n = 1;
                            while ($row = mysqli_fetch_array($data)){ 
                            $assid = $row['id'];
                            $assessment_code =  $row['id'];

                                    echo '<tr>
                                            <td>'.$n.'</td>
                                            <td>'.$row['assessments_code'].'</td>
                                            <td>'.countdata('assessments_id', $assid, 'tests').'</td>
                                            <td>'.$row['assessment_type'].'</td>
                                            <td>'.countdatasession('assessment_code', $assessment_code, 'assessments_users').' </td>
                                            <td>'.countdatasessiondist('assessment_code', $assessment_code, 'batch', 'assessments_users').'</td>                                          
                                            <td>'.$row['session_name'].'</td>
                                            <td>                                                                                                                      
                                               <!-- <button type="button" class="btn btn-outline btn-info btn-xs"> Edit </button> -->
                                               <a href="add_test.php?ass='.$assid.'" class="btn btn-outline btn-primary btn-xs"> Manage Assessments </a>
                                            </td>
                                        </tr>';         
                            $n++;   }
                        ?>
                            <!--
                                            <tr>
                                                <td>1</td>
                                                <td>ENT232</td>
                                                <td>5</td>
                                                <td>70</td>                                                
                                                <td>703</td>                                                
                                                <td>1</td>                                                
                                                <td>2014/2015</td>                                                
                                                <td>No</td>                                                
                                                <td>                                   
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                            Actions
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu pull-right" role="menu">
                                                            <li><a href="#">Edit Assessments Properties</a></li>
                                                            <li><a href="#">Add Test to Assessments</a></li>
                                                            <li><a href="#">Add Students to Assessments</a></li>
                                                        </ul>
                                                    </div>
                                                    </td>
                                            </tr>
                                -->
                                      
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
