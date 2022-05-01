<!DOCTYPE html>
<html>

    <head>

    <head>
	<?php	$dir =  basename(__DIR__);
	$page = basename($_SERVER["PHP_SELF"]); 
    $pgname = "Index";
	require_once('../_inc/inc_head.php');	?>

    <script type="text/javascript" src="webcam.js"></script>
    <script language="JavaScript">
            document.write( webcam.get_html(440, 240) );
    </script>
	</head>

    <body>
	<?php require_once('_inc/inc_topnav_test.php'); 
	

	$uid = $_SESSION['uid'];
	
        if(isset($_POST["updaterec"])){
       
         $fatherName = test_input(strtoupper($_POST["fatherName"]));
                $FName = test_input(strtoupper($_POST["FName"]));
                $LName = test_input(strtoupper($_POST["LName"]));
                $tid = $_POST['tid'];

                $sql_insert = "Update USERS  SET fatherName = '$fatherName', FName = '$FName', LName ='$LName' WHERE id = '$uid'";
                $sql_result = mysqli_query($dbc, $sql_insert) or die(mysqli_error($dbc));
                if($sql_result){
                                $msg = "Submission Successful </b></a>";
                                //header('Location: index.php?ps=ti');
                        }else{
                                $msg = "Submission NOT Successful";
                        }
                }	

                $image_capture = nameId('id', 'image_capture', 'settings', 1);
                        ?>
            <!-- /.navbar-static-top -->
        <div class="container">

            <div class="row">
                <div class="col-md-8 col-md-offset-2">

		<?php  $pgname = "Completing form";
		if((isset($_GET['ps'])) && ($_GET['ps'] == 'cform')){ //ps = page session, cform = Completing form?>
		
		<?php  
			 $query = "  SELECT * FROM users WHERE id = '$uid'"; 
			$udata = mysqli_query($dbc, $query);
			$urow = mysqli_fetch_array($udata)	;	
			
			if((($urow['FName'])) && (( $urow['LName'])) && (($urow['fatherName']))){ 
				header('Location: index.php?ps=ti');
			}else{
				?>
		
		<?php $tid = $_POST['tid'] ?> 			   
	       <div class="login-panel panel panel-default" style="margin-top: 15%;">
                        <div class="panel-heading">
                            <h3 class="panel-title text-center"> Completing registration </h3>
                        </div>
                    <form role="form"action="index.php?ps=ti"  method="post" />
						
                        <div class="panel-body">
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Username:</span>
                                    <input type="text" class="form-control" disabled name="userid"  value="<?php echo $urow['userid'] ?>">
                                </div>                                
                                <div class="form-group input-group">
                                    <span class="input-group-addon">First Name:</span>
                                    <input type="text" class="form-control" name="FName" value="<?php echo $urow['FName'] ?>">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Middle Name:</span>
                                    <input type="text" class="form-control" name="LName" value="<?php echo $urow['LName'] ?>">
                                </div> 
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Last Name:</span>
                                    <input type="text" class="form-control" name="fatherName" value="<?php echo $urow['fatherName'] ?>">
                                </div>                                
                        </div>
                        <div class="modal-footer" style="margin-top: 0px;"> 
                            <input type="hidden"  name="tid" value="<?php echo $tid ?>">						
                            <a href="index.php"><button type="button" class="btn btn-default">Cancel</button>							
                            <button type="submit" name="updaterec" class="btn btn-primary">Update & Continue</button>
                        </div>
                    </form>
	       </div>

			<?php 		}
	
	}elseif((isset($_GET['ps'])) && ($_GET['ps'] == 'ti')) { 
 
				$pgname = "Test Instruction";	

                $tid = $_POST['tid'] ;               
                checkTestNotSet();

                

// ti = test instruction  
				 $query = " SELECT ts.id, TName, time, NOQ, random, minus_mark, be_default, assessments_id, assessments_name, assessments_code, session_id 
							FROM tests ts, assessments ass
							WHERE ts.assessments_id = ass.id AND be_default = 1 AND session_id = '$csession_id' AND ts.id = '$tid' ";
				$data = mysqli_query($dbc, $query);
				$count = mysqli_num_rows($data);

				$n = 1;
				$row = mysqli_fetch_array($data);
				$testid = $row['id']; 
				$assid = $row['assessments_id']; 


                 //SAVE IMAGE             
                if(isset($_POST['snapimg'])){
                    $snapimg = $_POST['snapimg'] ; 
                    // requires php5
                    define('UPLOAD_DIR', 'images/');
                    $img = $_POST['snapimg'];
                    $img = str_replace('data:image/jpeg;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = UPLOAD_DIR.$row['assessments_code'].$tid. $_SESSION['uid']. '.jpeg';
                    $success = file_put_contents($file, $data);
                    //print $success ? $file : 'Unable to save the file.';

                    $query = " INSERT INTO test_images(userid, testid, image, timestamp) Value('$userid', '$tid', '$file', now())"; 
                    $data = mysqli_query($dbc, $query);
                }?>

                    <div class="panel panel-default" style="margin-top: 10%;" >
                        <div class="panel-heading">
                            <h3 class="panel-title text-center"> INSTRUCTIONS </h3>
                        </div>
                        <div class="panel-body">
                            <b>PLEASE READ THE FOLLOWING INSTRUCTIONS FOR THE E-EXAMINATION</b><br>
                            <?php 	//print_r($_POST);
                              echo '- Assessment Title : &nbsp;<b>'.$row['assessments_code'].' - '.(strtoupper($row['assessments_name'])).'</b><br>'; 
                              echo '- Test Title : <b> '.(strtoupper($row['TName'])).'</b><br>';
                              echo '- Number of questions : <b>'.$row['NOQ'].'</b><br>';
                              echo '- Exam time length : <b>'.$row['time'].' min(s) </b><br>';
                              ?>
                            <b></b>
                            <b><b><u>System Instructions</u></b></b><br>
                            <?php echo "- Questions are random : <b>".yesno($row['random'])."</b><br>";
                              echo "- Exam does have negative mark : <b>".yesno($row['minus_mark'])."</b><br><br>";
                              echo '- <span>&nbsp;<b>After clicking on the "Start Exam" button, the Exam will be started and your username will be added as a participant of this Exam and you will not be able to repeat this Exam again.<br>- Do the Exam by reading each question and clicking on any of the four (4) options available, it will be added to the database directly. You can change your choice by clicking on another option.<br>
                                    - At the end of the allotted time or after you click on the "End All Test" button, your Exam ends.</b></span>'; ?>
                        </div>
                        <div class="modal-footer" style="margin-top: 0px;">
                            <form action="test3.php" method="post">
                                <input type="hidden" name="assid" value="<?php echo $assid; ?>" >                       
                                <input type="hidden" name="testid" value="<?php echo $tid; ?>" >                       
                                <a href="index.php"><button type="button" class="btn btn-default">Cancel</button></a>							
                                <button type="submit" name="startexam" class="btn btn-primary glow_green">Start Exam</button>
                            </form>
                        </div>
                    </div>
<?php }elseif((isset($_GET['ps'])) && ($_GET['ps'] == 'fc')) { 
 
                $pgname = "Face Capture ";               // fc = Face Capture               
                $tid = $_POST['tid'] ;               
                checkTestNotSet(); ?>

                    <div class="panel panel-default" style="margin-top: 5%;" >
                        <div class="panel-heading">
                            <h3 class="panel-title text-center"> Image Capture </h3>
                        </div>
                        <div class="panel-body">

            <body>

                <div class="col-md-6">
                    <div id="my_camera"></div>
                     <!-- A button for taking snaps -->
                    <form>
                        <input id="snapshot" type=button value="Take Snapshot" onClick="take_snapshot()" class="btn btn-info glow_blue" style="margin-top: 10px;" >
                    </form> 
                </div>  

                <div class="col-md-6">
                    <div id="results">Your captured image will appear here...</div>
                </div>  
                
                <div class="col-md-12">
                 <h4> Please be sure you capture the right image else you shall be logged out </h4>  
                </div>

                    
                    <!-- First, include the Webcam.js JavaScript Library -->
<!--                     <script type="text/javascript" src="webcam.js"></script>
 -->                    
                    <!-- Configure a few settings and attach camera -->
                    <script language="JavaScript">
                        Webcam.set({
                            width: 320,
                            height: 240,
                            crop_width: 240,
                            crop_height: 240,
                            image_format: 'jpeg',
                            jpeg_quality: 90
                        });
                        Webcam.attach( '#my_camera' );
                    </script>   
                   
                    <!-- Code to handle taking the snapshot and displaying it locally -->
                    <script language="JavaScript">
                        function take_snapshot() {
                            // take snapshot and get image data
                            Webcam.snap( function(data_uri) {
                                // display results in page
                                document.getElementById('results').innerHTML =  '<img src="'+data_uri+'"/>';
                                document.getElementById('snapimg').value =  data_uri                                      
                                document.getElementById('savesnapimg').innerHTML =  '<button type="submit" name="startexam" class="glow_blue btn btn-info">Submit Image</button>'            
                                document.getElementById('snapshot').className =  'btn btn-default';          

                            } );


                        }
                    </script>
                    
                </body>
                        </div>
                        <div class="modal-footer" style="margin-top: 0px;">
                            <form action="index.php?ps=ti" method="post">
                                <input id='snapimg' type="hidden" name="snapimg" value="" >                       
                                <input type="hidden" name="tid" value="<?php echo $tid; ?>" >                       
                                <a href="index.php"><button type="button" class="btn btn-default">Cancel</button></a>  
                                <span id='savesnapimg'></span>                         
                                
                            </form>
                        </div>
                    </div>

        <?php }else{?>

                    <div class="login-panel panel panel-default" style="margin-top: 15%;">
                        <?php if (isset($msg)) { ?>
                            <div class="alert alert-danger fade in widget-inner">
                                <i class="fa fa-times"></i> <?php echo $msg; ?>
                            </div>
                        <?php }?>
                        <div class="panel-heading">
                            <h3 class="panel-title text-center"> Active Exams </h3>
                        </div>
                        <div class="panel-body">
						<?php //echo list all active for a user
                              $query = " SELECT ts.id, TName, be_default, assessments_id, assessments_name, assessments_code, ass.session_id 
										FROM tests ts, assessments ass, assessments_users au
                                        WHERE ts.assessments_id = ass.id AND be_default = 1 AND ass.session_id = '$csession_id'
										AND au.username = '$username'
										AND ass.id = au.assessment_code
										AND au.session_id = '$csession_id'"; 
                            $data = mysqli_query($dbc, $query);
                            $count = mysqli_num_rows($data);
                            
                            $n = 1;
							
							if($count > 0){
							   echo '<p>You are allowed to participate in the following tests. Please <b>Click</b> on a tests to continue:</p>';
							}else{
								echo '<h4 class="text-center text-danger">You are not registered for any active test.</h4>';

							}
                            while ($row = mysqli_fetch_array($data)){ 
                            $testid = $row['id'];	?>						
							
                            <?php if($image_capture == 1){ ?>
                                    <form action="index.php?ps=fc" method="post">
                                <?php }else{ ?>
                                    <form action="index.php?ps=ti" method="post">
                            <?php } ?>

						
							<?php echo'<button type="submit" name="tid" value="'.$testid.'" class="btn btn-info btn-full">
							<i class="fa fa-hand-o-right"></i> '.$row['assessments_code'].' - '.(strtoupper($row['assessments_name'])).' - '.(strtoupper($row['TName'])).'</button>
							</form>';
							}
						?>
						
						</div>
                    </div>
                </div>
            </div>
        </div>

<?php mysqli_close($dbc); } ?>
        <!-- Core Scripts - Include with every page -->
        <script src="../_res/js/jquery-1.10.2.js"></script>
        <script src="../_res/js/bootstrap.min.js"></script>
        <script src="../_res/js/plugins/metisMenu/jquery.metisMenu.js"></script>

        <!--  Admin Scripts - Include with every page -->
        <script type="text/javascript">
            setTimeout(function () {
                 window.location.replace('logout.php');
                }, 6000000);
        </script>

    </body>

</html>
