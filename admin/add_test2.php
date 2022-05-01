<!DOCTYPE html>
<html>

    <head>
<?php	$dir =  basename(__DIR__);
$pgname = "Add Test";
require_once('../_inc/inc_head.php');	?>	</head>

    <body <?php	 if(isset($_GET['testid'])){ $testid = $_GET['testid'];	$TName  = nameId('id', 'TName', 'tests', $testid); ?> onload="loadm() <?php } ?>">

        <div id="wrapper">

<?php	require_once('_inc/inc_topnav.php');	?>
<?php	require_once('_inc/inc_sidebar.php');	?>
<?php	 if(isset($_GET['ass'])){ $ass_id = $_GET['ass']; }

					if(isset($_POST["add_test"])){
					//print_r($_POST);
								$TName = test_input(strtoupper($_POST["TName"]));
								$NOQ = test_input($_POST["NOQ"]);
								$start_message = test_input($_POST["start_message"]);
								$end_message = test_input($_POST["end_message"]);
								$time = test_input($_POST["time"]);
								$test_mark = test_input($_POST["test_mark"]);
								$RandomQ = test_input($_POST["RandomQ"]);
								$NegativeQ = test_input($_POST["NegativeQ"]);
								$ShowAns = test_input($_POST["ShowAns"]);
								$ShowMark  = test_input($_POST["ShowMark"]);
								$ShowRank = test_input($_POST["ShowRank"]); 
								$prof_or_user = test_input($_POST["prof_or_user"]); 
								$active = test_input($_POST["active"]); 
								$be_default = test_input($_POST["be_default"]); 
								$assessment_id = $ass_id; 


							 $sql_insert = "INSERT INTO tests (TName, NOQ, be_default, prof_or_user, random, time, minus_mark, show_answers, show_mark, active, start_message, end_message, show_rank, test_mark, assessments_id ) 
											VALUES     ( '$TName', '$NOQ','$be_default', '$prof_or_user', '$RandomQ', '$time:00', '$NegativeQ', '$ShowAns',  '$ShowMark', '$active', '$start_message', '$end_message',  '$ShowRank', '$test_mark', '$assessment_id')";
								$sql_result = mysqli_query($dbc, $sql_insert) or die(mysqli_error($dbc));
								if($sql_result){
								$msg = "Registration Successful Please <a href='login.php'><b> Sign in to Continue </b></a>";									
									}else{
										$msg = "Registration NOT Successful";
									}
								}	

if( isset($_POST['form']) && $_POST['form'] == "upload_user"){ //database query to upload Admitted Student      

      if(is_uploaded_file($_FILES['filename']['tmp_name'])){
          
        //Import uploaded file to Database  
        $handle = fopen($_FILES['filename']['tmp_name'], "r");
        
        
        $uploaded = true;
        // mysqli_begin_transaction($dbc); 
                
        while (($data = fgetcsv($handle, 1500, ",")) !== FALSE) {
                    //insert verification code for each uploade prospective student 
                    $prep_query = sprintf("INSERT INTO users (userid, fname, lname, mname, password) "
                                        . "VALUES ('%s', '%s', '%s', '%s', '%s')", 
                                        test_input($data[0]),
                                        test_input($data[1]),
                                        test_input($data[2]),
                                        test_input($data[3]) ,
                                        test_input($data[1])
                                        );
                    $users = mysqli_query($dbc, $prep_query);

                    if( $users ){// && $update1){
                            $insert_row++;
                    }
                    else{       
                            $insert_error[] = $data[0];
                            $uploaded = false;
                    }

            }
            
            if( $uploaded ){
                mysqli_commit($dbc);              
                
                $uploadstat = "<p style='color :green'>Upload Successful! ".$insert_row."  students uploaded.<p>";            
            }else{
                        
                        mysqli_rollback($dbc);
                $uploadstat = "<p style='color :red'>Upload Unsuccessful! The following tudents could not be uploaded:<br/>";
                foreach( $insert_error as $error){
                    $uploadstat .= $error."<br/>";
                }
                
                $uploadstat .= "Please check your CSV file and try again, or contact system admin.</p>";
            }
            fclose($handle);
                    
                    $msg = [
                        'status'=> TRUE,
                        'rs' => $uploadstat,
                        'type' =>'success'        
                    ];
        }
    
}							
							
		$ass_code = nameId('id', 'assessments_code', 'assessments', $ass_id);
		$ass_name = nameId('id', 'assessments_name', 'assessments', $ass_id);									
?>
  <!-- /.navbar-static-side -->

            <div id="page-wrapper">
	
				<div class="row">
                    <div class="col-lg-12">
                        <h4 class="page-header">View Assessment Test <span> <?php echo $ass_code; ?> </b> -  <?php echo $ass_name; ?> </span> </h4>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
				
				<div class="panel panel-default">
                    <div class="panel-body">
                        
                        <!-- Button trigger modal -->
                        <button class="btn btn-info" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-pencil"></i> Add Test to Assessments
                        </button>
                        <!-- Modal -->
                       <button class="btn btn-primary" data-toggle="modal" data-target="#myModal3">
                       <i class="fa fa-users"></i> Add Users to Assessments</button>
                        
                    </div>
                </div>
<?php	 if(isset($_GET['ass'])){ ?>
                            <!-- Modal Import Users -->
                    <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-arrow-up"></i>  Import Students </h4>
                                </div>
                                <form method="post" action="" name="upload_user" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="well">
                                            <span style=" color: brown"><h5><strong>Hint:</strong> Structure of what the CSV file should look like</h5> </span>
                                            <table class="table table-bordered table-condensed table-responsive">
                                                <thead >
                                                    <tr>
                                                        <th>matric No</th>
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
                                                        <th>middle Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>20090204001</td>
                                                        <td>Ogbeni</td>
                                                        <td>Adamu</td>
                                                        <td>musa</td>
                                                    </tr>
                                                    <tr>
                                                        <td>20090204002</td>
                                                        <td>Sherif</td>
                                                        <td>Dayo</td>
                                                        <td>Tunmishe</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputFile">Upload Users</label>
                                            <input type="file" name="filename" class="filestyle" data-buttonText="Find file">
                                            <p class="help-block">Import a CSV file.</p>
                                        </div>
                                        <input type="hidden" name="form" value="upload_user">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Upload File</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->				
				<!-- Modal Start -->               
                    <div class="modal fade modal-primary" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title text-primary" id="myModalLabel"><i class="fa fa-pencil"></i> Add a New Test to <b> <?php echo $ass_code; ?> </b> -  <?php echo $ass_name; ?>                    

                                </div>
							<form role="form"  action="" method="post" />

                                <div class="modal-body">

                                    <div class="row">
                                    <div class="col-lg-6">
                                        <form role="form" action="" method="post" />
											<div class="form-group input-group">
                                                <span class="input-group-addon">Test Title:</span>
                                                <input type="text" name="TName" class="form-control" required>
                                            </div>
											<div class="form-group input-group">
                                                <span class="input-group-addon">Number of Questions:</span>
                                                <input type="text" name="NOQ" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Message at the beginning of this Test:</label>
                                                <textarea class="form-control" rows="3" name="start_message"></textarea>
												<p class="help-block">Example: Do not <b>cheat</b>. <i>(HTML is allowed)</i></p>
                                            </div>
                                            <div class="form-group">
                                                <label>Message at the bottom of the Test:</label>
                                                <textarea class="form-control" rows="3" name="end_message"></textarea>
												<p class="help-block">Example: Good <b>LUCK</b>. <i>(HTML is allowed)</i></p>
                                            </div>
                                    </div>
                                    <!-- /.col-lg-6 (nested) -->
                                    <div class="col-lg-6">
                                                                                      
                                            <div class="form-group input-group">
                                                <span class="input-group-addon">Test Time Length:</span>
                                                <input type="text" placeholder="Time in Minutes" name="time" class="form-control" required>
                                            </div>                                        
                                            <div class="form-group input-group">
                                                <span class="input-group-addon">Test Mark:</span>
                                                <input type="text" placeholder="Mark Obtainable" name="test_mark" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Activate Test as default?</label>
                                                <label class="radio-inline">
                                                    <input type="radio"  name="be_default" value="1" checked="" /> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="be_default" value="0" /> No
                                                </label>
                                            </div> 
                                            <div class="form-group">
                                                <label>Allow User to Write Test?</label>
                                                <label class="radio-inline">
                                                    <input type="radio"  name="active" value="1" checked="" /> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="active" value="0" /> No
                                                </label>
                                            </div> 
                                            <div class="form-group">
                                                <label>Allow User Registration?</label>
                                                <label class="radio-inline">
                                                    <input type="radio"  name="prof_or_user" value="1" checked="" /> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="prof_or_user" value="0" /> No
                                                </label>
                                            </div>                                            
                                            <div class="form-group">
                                                <label>Questions are Random?</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="RandomQ"  value="1" checked="" /> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="RandomQ" value="0" /> No
                                                </label>
                                            </div>                                            
											<div class="form-group">
                                                <label>Does this Test have negative mark?</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="NegativeQ" value="1" checked="" /> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="NegativeQ"  value="0" />No
                                                </label>
                                            </div>											
											<div class="form-group">
                                                <label>Show correct answers after finishing the Test?</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ShowAns" value="1" checked="" /> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ShowAns" value="0" /> No
                                                </label>
                                            </div>											
											<div class="form-group">
                                                <label>Show grade after finishing the Test?</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ShowMark" value="1" checked="" /> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ShowMark" value="0" /> No
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
                                    </div>                            
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" name="add_test" class="btn btn-primary">Save and Continue</button>
                                </div>
                            </div>
					   </form>
                        </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
				<!-- Modal Start -->               
                    <div class="modal fade modal-primary" id="addQModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title text-primary" id="myModalLabel"><i class="fa fa-pencil"></i> Add Questions to <b> <?php echo $ass_code; ?> </b> - <?php echo $TName; ?> 

                                </div>
							<form role="form"  action="" method="post" />

                                <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <form role="form" action="" method="post" />
											
											<div class="form-group">
                                                <label>Import A Question File (excel or csv file) - Optional</label>
                                                <input type="file">
                                            </div>                                      
                                            <button type="submit" name="import_test" class="btn btn-primary">Save and Continue</button>
											</form>
                                        
                                    </div>
                                    <!-- /.col-lg-6 (nested) -->
									<div class="col-lg-4">
                                        <a href="add_question.php?ass=<?php echo $ass_id; ?>&testid=<?php echo $testid; ?>" class="btn btn-info">Create Questions</a>

                                    </div>
                                    <!-- /.col-lg-6 (nested) -->

                                </div>									
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
                                Test Properties
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Test Title</th>
                                                <th>Questions Added</th>
                                                <th>Test Questions </th>
                                                <th>Time</th>
                                                <th>Mark</th>
                                                <th>Random</th>
                                                <th>Answers</th>
                                                <th>Grade</th>
                                                <th>Rank</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										
                        <?php 
                             $query = "  SELECT * FROM tests ts
                                        WHERE ts.assessments_id = '$ass_id'"; 
                            $data = mysqli_query($dbc, $query);
                            $count = mysqli_num_rows($data);
                            
                            $n = 1;
                            while ($row = mysqli_fetch_array($data)){ 
                            $testid = $row['id'];

                                    echo '<tr>
                                            <td>'.$n.'</td>
                                            <td>'.$row['TName'].'</td>
                                            <td>'.$row['NOQ'].'</td>
                                            <td>'.countdata('id', $testid, 'questions').'</td>
                                            <td>'.$row['time'].'mins</td>
                                            <td>'.$row['test_mark'].'</td>
                                            <td>'.yesno($row['random']).'</td>
                                            <td>'.yesno($row['show_answers']).'</td>
                                            <td>'.yesno($row['show_mark']).'</td>
                                            <td>'.yesno($row['show_rank']).'</td>
											<td data-id="'.$row['id'].'">                                   
												<div class="btn-group">
													<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
														Actions
														<span class="caret"></span>
													</button>
													<ul class="dropdown-menu pull-right" role="menu">
														<li><a href="add_test.php?ass='.$ass_id.'&testid='.$testid.'">Add Question to Exam</a></li>
														<li><a href="#">Edit Exam Properties</a></li>
														<li><a href="#">Edit Questions</a></li>
														<li class="divider"></li>
														<li><a href="#">Add Question from Questions Bank</a>
														<li><a href="#">Delete Exam</a>
														<li><a href="#">Print Exam</a>
														<li><a href="#">Export</a>
														</li>
													</ul>
												</div>
											</td>
                                        </tr>';         
                            $n++;   }
                        ?>

                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                </div> 

<?php } ?>
			
            </div>
            		<?php	require_once('footer_bar.php');	?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js.php');	?>

    </body>
<script>
$(function () {

    $(".table").find('td[data-id]').on('click', function () {
        //debugger;
           $.ajax({
                type: "POST",
                url: "_inc/process.php",
                data: { id: $(this).data('id') },
                success: function(msg){
        //do all your operation populate the modal and open the modal now. DOnt need to use show event of modal again
        $('#orderModal').modal('show');      
 	          		$('#editContent').html(msg)
					$("#orderModal").modal('hide').on('hidden.bs.modal', functionThatEndsUpDestroyingTheDOM); // force to dismis backdrop
	
		}


		});
    });
});

</script>
<script>
function loadm() {
        $('#addQModal').modal('show');      
}

</script>


</html>
