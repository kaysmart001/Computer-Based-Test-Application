<?php 
$dir = basename(__DIR__);

 $prep_query = "SELECT DISTINCT batch FROM assessments_users where assessment_code = '$ass_code' "; 
$sessions = mysqli_query($dbc, $prep_query);
$row_sessions = mysqli_fetch_assoc($sessions);

$ses = array();
do{
    array_push($ses, $row_sessions); 
    
}while ($row_sessions = mysqli_fetch_assoc($sessions));


   $prep_query = " SELECT us.id, fname, mname, lname, userid, password,  assessments_code, batch, login
                 FROM users us, assessments_users aus, assessments ass  WHERE us.userid = aus.username 
                 AND ass.id = aus.assessment_code
                 AND ass.id = '$ass_id'
                 AND aus.session_id = $csession_id
                 ORDER BY aus.id DESC"; 
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
            //
            //console.log(sessions);
        </script>

    </head>
    <body>
        <!-- /.navbar-static-side -->
        <div id="" ng-controller="PageController">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">Assessment Students</h3>
                </div>
            </div>
                <div class="row">
                    <div class="col-lg-12">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Manage Students Table 
                                
                                <span style="float:right;">
                                    <a class="btn btn-grey2 btn-xs"  href="?reset_all_time=<?= $ass_id; ?>"> <i class="fa fa-reload" ></i> Reset All Users Time</a>
                                    <button class="btn btn-grey2 btn-xs " id="endexamall"><i class="fa fa-stop" ></i> End All Exam  </button>
                                    <button class="btn btn-danger btn-xs " id="logoutall"><i class="fa fa-power-off" ></i> Logout All Users  </button>
                                    <button class="btn btn-info btn-xs" onclick="goBack()"><i class="fa  fa-backward "></i> Back  </button>
                                </span>
                            </div>
                            <div class="panel-body">
                                <div class="form-group input-group col-lg-4">
                                    <input type="text" class="form-control" ng-model="criteria">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default"><i class="fa fa-search"></i>
                                            </button>
                                        </span>
                                </div>
                                <div class="form-group input-group col-lg-4">
                                    <div class="form-group input-group">
                                        <span class="input-group-addon">Batch:</span>
                                        <select class="form-control"  ng-model="sescrit">
                                            <option  value="">-- Choose Batch --</option>
                                            <option ng-repeat="ses in data.sessions" value="{{ses.batch}}">{{ses.batch}}</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group input-group col-lg-4">
                                    <div class="form-group input-group">
                                        <span class="input-group-addon">Active Status:</span>
                                        <select class="form-control"  ng-model="loginn">
                                            <option  value="">-- Choose Status --</option>
                                            <option  value="1"> Active </option>
                                            <option  value="0"> Inactive </option>
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
                                                <th>Batch</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                                <th>Logout</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd gradeX" ng-repeat="user in data.users | filter:{$: criteria, batch: sescrit,  login: loginn} | limitTo:20 ">
                                                <td>{{$index +1}}</td>
                                                <td class="center">{{user.userid}}</td>
                                                <td>{{user.lname}}</td>
                                                <td>{{user.fname}}</td>
                                                <td>{{user.mname}}</td>
                                                <td>{{user.password}}</td>
                                                <td>{{user.batch}}</td>                                                
                                                <td class="text-center"> 
                                                    <span class="text-primary " ng-if='user.login == 1'>Active</span>
                                                    <span class="text-default" ng-if='user.login != 1'>Inactive</span>
                                                </td>
                                                <td class="text-center" data-matricno="{{user.id}}">
                                                    <a href="test_history.php?stid={{user.id}}"><button class="btn btn-outline btn-success btn-xs" type="button">Exam History</button></a>  
                                                    <!-- <a href="test_history.php?stid={{user.id}}"><button class="btn btn-outline btn-danger btn-xs" type="button">Reset User</button></a>-->  
                                                    <a href="add_test.php?ass=<?php echo $ass_id; ?>&edittime={{user.id}}"> 
                                                    <button class="btn btn-outline btn-info btn-xs edittime"  type="button">Edit Time</button></a>  
                                                    
                                                </td>
                                                <td class="text-center">                                               
                                                    <button  class="btn btn-danger btn-outline btn-xs logout" ng-if='user.login == 1' data-value='{{user.userid}}'><i class="fa fa-power-off"></i> Logout</button>
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

    </body>
    
</html>
