<?php 
    $dir = basename(__DIR__);


    require_once('../_inc/functions/config.php');
    require_once('../_inc/functions/CIFunctions.php');
    

    $prep_query = "SELECT * FROM session "; 
    $sessions = mysqli_query($dbc, $prep_query);
    $row_sessions = mysqli_fetch_assoc($sessions);

    $ses = array();
    do{
        array_push($ses, $row_sessions); 
        
    }while ($row_sessions = mysqli_fetch_assoc($sessions));


    $prep_query = "SELECT u.*, s.session_name  FROM users u LEFT JOIN session s ON u.sesid = s.id  ORDER BY u.id DESC "; 
    $users = mysqli_query($dbc, $prep_query);
    $row_users = mysqli_fetch_assoc($users);

    $student = array();

    do{
        array_push($student, $row_users); 
        
    }while ($row_users = mysqli_fetch_assoc($users));
    //die(var_dump($student));


    //die(var_dump($ses));
    $msg = array();
    $msg['status'] = FALSE;
    $msg['rs'] = "no message yet";

    if(isset($_POST['add_user'])){
        
        $prep_query = sprintf("INSERT INTO users (id, fname, lname, mname, sesid, userid, password) "
                            . "VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')", 
                            test_input($_POST['userid']) ,
                            test_input($_POST['fname']),
                            test_input($_POST['lname']),
                            test_input($_POST['mname']),
                            test_input($_POST['session']),
                            test_input($_POST['userid']) ,
                            test_input($_POST['password'])
                            );
                            
        $users = mysqli_query($dbc, $prep_query);
        
        if($users){
            $msg = [
                'status'=> TRUE,
                'rs' => "user Added successfully",
                'type' =>'success'        
            ];
        }else{
            $msg = [
                'status'=> TRUE,
                'rs' => "user can not be added ",
                'type' =>'error'        
            ];
        }
        
    }

    if(isset($_POST['update_student'])){
        
         $prep_query = sprintf("UPDATE  users "
                            . "SET fname = '%s', lname = '%s', mname = '%s', userid = '%s', password = '%s', sesid = '%s'"
                            . "WHERE id = '%s' ", 
                                test_input($_POST['fname']),
                                test_input($_POST['lname']),
                                test_input($_POST['mname']),
                                test_input($_POST['userid']) ,
                                test_input($_POST['password']),
                                test_input($_POST['session']),                           
                                test_input($_POST['edit_id'])
                            );
                            
            $users = mysqli_query($dbc, $prep_query) or die(mysqli_error($dbc));
            
            if($users){
                $msg = [
                    'status'=> TRUE,
                    'rs' => "user Updated successfully",
                    'type' =>'success'        
                ];
            }else{
                $msg = [
                    'status'=> TRUE,
                    'rs' => "user can not be Updated ",
                    'type' =>'error'        
                ];
            }
        
    }

    $ver_type = 1;
    $ver = 'FALSE';
    $insert_row = 0;
    $insert_error = array();
    $uploadstat = "";


    if( isset($_POST['upload_user'])){ //database query to upload Admitted Student		

    	if(is_uploaded_file($_FILES['filename']['tmp_name'])){
    		  
    		//Import uploaded file to Database	
    		$handle = fopen($_FILES['filename']['tmp_name'], "r");
    		
    		
    		$uploaded = true;
                    //mysqli_begin_transaction($dbc);
                    
    		while (($data = fgetcsv($handle, 1500, ",")) !== FALSE) {
                        //insert verification code for each uploade prospective student 
                        $prep_query = sprintf("INSERT INTO users (id, userid, fname, mname, lname, sesid, password) "
                                            . "VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')", 
                                            test_input($data[0]),
                                            test_input($data[0]),
                                            test_input($data[1]),
                                            test_input($data[2]),
                                            test_input($data[3]) ,
                                            test_input($_POST['session']),
                                    strtolower(test_input($data[3]))
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


    if(isset($_POST['upload_multy_student'])){
        
        $userid = explode("\n", $_POST['userid']);
        $password = explode("\n", $_POST['password']);
        
        
        $equal = (count($userid) == count($password));
        
        if($equal){
            $uploaded = TRUE;
            //begin transaction
            //mysqli_begin_transaction($dbc);
            
            for($idx = 0; $idx < count($userid); $idx++){
                //insert verification code for each uploade prospective student 
                $prep_query = sprintf("INSERT INTO users (id, userid, sesid, password) "
                                    . "VALUES ('%s', '%s','%s', '%s')", 
                                    test_input($userid[$idx]),
                                    test_input($userid[$idx]),
                                    test_input($_POST['session']),
                                    test_input($password[$idx]));
                $users = mysqli_query($dbc, $prep_query) or die(mysqli_error($dbc));
                
                if($users){
                    $insert_row++;
                }else{
                    $insert_error[] =  $userid[$idx];
                    $uploaded = false;
                }
                
            }// end of for loop
            
            //if uploaded successfully 
            if( $uploaded ){
                mysqli_commit($dbc);
                $uploadstat = "<p style='color :green'>Upload Successful! ".$insert_row."  students uploaded.<p>";

            }else{
                mysqli_rollback($dbc);
                $uploadstat = "<p style='color :red'>Upload Unsuccessful! The following tudents could not be uploaded:<br/>";

                foreach( $insert_error as $error){
                    $uploadstat .= $error."<br/>";
                }
            }
            
        }else{
           $uploadstat = "<p style='color :red'>The lenght of username is not equal the lenght of password </p>"; 
            
        }
        
        $msg = [
                'status'=> TRUE,
                'rs' => $uploadstat,
                'type' =>'success'        
            ];
    }


    $prep_query = "SELECT u.*, s.session_name  FROM users u LEFT JOIN session s ON u.sesid = s.id  ORDER BY u.id DESC"; 
    $users = mysqli_query($dbc, $prep_query);
    $row_users = mysqli_fetch_assoc($users);

    $student = array();

    do{
        array_push($student, $row_users); 
        
    }while ($row_users = mysqli_fetch_assoc($users));
    //die(var_dump($student));
?>


<!DOCTYPE html>
<html ng-app="exam-app">
    <head>
	<?php	$pgname = "Students";
	    require_once('../_inc/inc_head.php');	?>	
        <!-- Page-Level Plugin CSS - Tables -->
        <style type="text/css">
            .icon-refresh-animate {
                    animation-name: rotateThis;
                    animation-duration: 1.2s;
                    animation-iteration-count: infinite;
                    animation-timing-function: linear;
                    }

            @keyframes rotateThis {
                from { transform: scale( 1 ) rotate( 0deg );   }
                to   { transform: scale( 1 ) rotate( 360deg ); }
            }
        </style>
        
        
        <script>
            var users = <?php echo (is_array($student))? json_encode($student): '[]'?>;
            var sessions = <?php echo (is_array($ses))? json_encode($ses): '[]'?>;
            //console.log(sessions);
        </script>

    </head>
    <body>
        <div id="wrapper">
        <?php	require_once('_inc/inc_topnav.php');	?>
        <?php	require_once('_inc/inc_sidebar.php');	?>
        <!-- /.navbar-static-side -->
        <div id="page-wrapper" ng-controller="PageController">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">Students</h3>
                </div>
            </div>
                <div class="row">
                    <div class="col-lg-12">
                        
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-user"></i>  Add Student </button>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#myModal2"><i class="fa fa-users"></i>  Add Multiple Students </button>
                                <button class="btn btn-info" data-toggle="modal" data-target="#myModal3"><i class="fa fa-arrow-up"></i>  Import Students  </button>
                                <button class="btn btn-info" data-toggle="modal" data-target="#myModal4"><i class="fa fa-refresh"></i> Synchronize Students from TAMS </button>
                            </p>
                        </div>
                    </div>
                    <?php if($msg['status']){?>    
                            <div class="alert alert-default alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                                <a href="#" class="alert-link "><?php echo $msg['rs']?></a>
                            </div>                      
                    <?php }?>    
                    <!-- //  Add Students --> 
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-user"></i>  Add Students  </h4>
                                </div>
                                <form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']?>" name="add_user">
                                    <div class="modal-body">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Session:</span>
                                            <select class="form-control" required="" name="session">
                                                <option  value="">-- Choose Session --</option>
                                                <option ng-repeat="ses in data.sessions" value="{{ses.id}}">{{ses.session_name}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">First Name:</span>
                                            <input type="text" name="fname" class="form-control" required="">
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Middle Name:</span>
                                            <input type="text" name="mname"class="form-control" required="">
                                        </div> 
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Last Name:</span>
                                            <input type="text" name="lname" class="form-control" required="">
                                        </div> 
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Username:</span>
                                            <input type="text" name="userid" class="form-control" required="">
                                        </div> 
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Password:</span>
                                            <input type="text" name="password" class="form-control" required="">
                                        </div> 
                                        <input type="hidden" name="add_user">                                       
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add Student</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>                 
                    <!-- //  Add  Multiple Students --> 
                    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-users"></i>  Add Multiple Students </h4>
                                </div>
                                <form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']?>" name="upload_multy_student">
                                    <div class="modal-body">
                                        <div class="col-sm-12">
                                            <div class="form-group input-group">
                                                <span class="input-group-addon">Session:</span>
                                                <select class="form-control" required="" name="session">
                                                    <option  value="">-- Choose Session --</option>
                                                    <option ng-repeat="ses in data.sessions" value="{{ses.id}}" ng-selected="current.sesid == ses.id">{{ses.session_name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Usernames:</label>
                                                <textarea class="form-control"  name="userid" rows="15" required=""></textarea>
                                                <p class="help-block">Do not leave <b>spaces</b>.</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Passwords:</label>
                                                <textarea class="form-control" name="password" rows="15" required=""></textarea>
                                                <p class="help-block">Do not leave <b>spaces</b>.</p>
                                            </div>
                                        </div>
                                        <div style="clear:both"> </div>    
                                    </div>
                                    <input type="hidden" name="upload_multy_student">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add Students</button>
                                    </div>
                                </form>
                            </div>  
                        </div>
                    </div>

                    <!-- Modal Import Users -->
                    <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-arrow-up"></i>  Import Students </h4>
                                </div>
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']?>" name="upload_user" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="well">
                                            <span style=" color: brown"><h5><strong>Hint:</strong> Structure of what the CSV file should look like</h5> </span>
                                            <table class="table table-bordered table-condensed table-responsive">
                                                <thead >
                                                    <tr>
                                                        <th>Matric No</th>
                                                        <th>First Name</th>
                                                        <th>Middle Name</th>
                                                        <th>Last Name</th>
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
                                            <div class="form-group input-group">
                                                <span class="input-group-addon">Session:</span>
                                                <select class="form-control" required="" name="session">
                                                    <option  value="">-- Choose Session --</option>
                                                    <option ng-repeat="ses in data.sessions" value="{{ses.id}}" ng-selected="current.sesid == ses.id">{{ses.session_name}}</option>
                                                </select>
                                            </div>
                                        <div class="form-group">
                                            <label for="exampleInputFile">Upload Users</label>
                                            <input type="file" name="filename" class="filestyle" data-buttonText="Find file">
                                            <p class="help-block">Import a CSV file.</p>
                                        </div>
                                        <input type="hidden" name="upload_user">
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

                    <!-- Modal Snyc Users -->
                    <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-refresh icon-refresh-animate"></i> Synchronize Students from TAMS </h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="exampleInputFile">Upload Picture</label>
                                        <input type="file" class="filestyle" data-buttonText="Find file">
                                        <p class="help-block">Select post featured picture.</p>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Start Sync</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    
                    <!-- //  Edit Students --> 
                    <div class="modal fade" id="edit_user_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-user"></i>  Edit Student</h4>
                                </div>
                                <form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']?>" name="update_student">
                                    <div class="modal-body">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Session:</span>
                                            <select class="form-control" required="" name="session">
                                                <option  value="">-- Choose Session --</option>
                                                <option ng-repeat="ses in data.sessions" value="{{ses.id}}" ng-selected="current.sesid == ses.id">{{ses.session_name}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">First Name:</span>
                                            <input type="text" class="form-control" name="fname" value="{{current.fname}}">
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Middle Name:</span>
                                            <input type="text" class="form-control" name="mname" value="{{current.mname}}">
                                        </div> 
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Last Name:</span>
                                            <input type="text" class="form-control" name="lname"value="{{current.lname}}">
                                        </div> 
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Username:</span>
                                            <input type="text" class="form-control" name="userid" value="{{current.userid}}">
                                        </div> 
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Password:</span>
                                            <input type="text" class="form-control" name="password" value="{{current.password}}">
                                        </div>
                                        <input type="hidden" name="edit_id" value="{{current.id}}">
                                        <input type="hidden" name="update_student">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save Edit</button>
                                    </div>
                                </form>   
                            </div>
                        </div>
                    </div><!-- //  Edit Students -->                  


                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Manage Students Table
                            </div>
                            <div class="panel-body">
                                <div class="form-group input-group col-lg-6">
                                    <input type="text" class="form-control" ng-model="criteria">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default"><i class="fa fa-search"></i>
                                            </button>
                                        </span>
                                </div>
                                <div class="form-group input-group col-lg-6">
                                    <div class="form-group input-group">
                                        <span class="input-group-addon">Session:</span>
                                        <select class="form-control"  ng-model="sescrit">
                                            <option  value="">-- Choose Session --</option>
                                            <option ng-repeat="ses in data.sessions" value="{{ses.id}}" ng-selected="current.sesid == ses.id">{{ses.session_name}}</option>
                                        </select>
                                    </div>
                                </div>
                                
<!--                                <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                                    <ul class="pagination">
                                        <li class="paginate_button previous disabled" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_previous"><a href="#">Previous</a></li>
                                        <li class="paginate_button active" aria-controls="dataTables-example" tabindex="0"><a href="#">1</a></li>
                                        <li class="paginate_button " aria-controls="dataTables-example" tabindex="0"><a href="#">2</a></li>
                                        <li class="paginate_button " aria-controls="dataTables-example" tabindex="0"><a href="#">3</a></li>
                                        <li class="paginate_button " aria-controls="dataTables-example" tabindex="0"><a href="#">4</a></li>
                                        <li class="paginate_button " aria-controls="dataTables-example" tabindex="0"><a href="#">5</a></li>
                                        <li class="paginate_button " aria-controls="dataTables-example" tabindex="0"><a href="#">6</a></li>
                                        <li class="paginate_button next" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_next"><a href="#">Next</a></li>
                                    </ul>
                                </div>-->
                                <div class="clear" style="clear:both"></div>
                                <div class="">
                                    <table class="table table-responsive table-bordered table-condensed ">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Matric No</th>
                                                <th>Surname</th>
                                                <th>First Name</th>
                                                <th>Other Name</th>
                                                <th>Password</th>
                                                <th>Session</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd gradeX" ng-repeat="user in data.users | filter:{$: criteria, sesid: sescrit} | limitTo:20 ">
                                                <td>{{$index +1}}</td>
                                                <td class="center">{{user.userid}}</td>
                                                <td>{{user.lname}}</td>
                                                <td>{{user.fname}}</td>
                                                <td>{{user.mname}}</td>
                                                <td>{{user.password}}</td>
                                                <td>{{user.session_name}}</td>                                                
                                                <td class="center" style="width: 200px;">
                                                    <button class="btn btn-outline btn-success btn-xs" ng-click="getData(user.id)"  data-toggle="modal" data-target="#edit_user_modal" type="button">Edit</button>
                                                    <button class="btn btn-outline btn-danger btn-xs" type="button">Delete</button>
                                                    <a href="test_history.php?stid={{user.id}}"><button class="btn btn-outline btn-info btn-xs" type="button">Exam History</button></a>                                                    
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
