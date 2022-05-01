<!DOCTYPE html>
<html>

    <head>
	<?php error_reporting('E_ALL');
    	$dir =  basename(__DIR__);
	$pgname = "Settings";
	require_once('../_inc/inc_head.php');	

if(isset($_POST['activate_col'])){
     $active_cols = $_POST['active_col'];
     $sql = "UPDATE  `users` SET sch = '0' ";

     mysqli_query($dbc, $sql );

     foreach ($active_cols as $col) {

        if($col == 'all'){
            $sql = "UPDATE  `users` SET sch = '1'";
            mysqli_query($dbc, $sql );
            
        }else{

            $coll =  "2___".$col."_____%";
            $sql = "UPDATE  `users` SET sch = '1' WHERE id LIKE '$coll'";
            mysqli_query($dbc, $sql );
        }
        
        
       
    }

    $msg = 'OPERATION SUCCESFUL';
}
  

    ?>	
	</head>

    <body>
        <div id="wrapper">

<?php	require_once('_inc/inc_topnav.php');	?>
<?php	require_once('_inc/inc_sidebar.php');	?>
  <!-- /.navbar-static-side -->

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header">Settings</h3>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                               Enter Application Settings
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form role="form">
                                            <div class="form-group input-group">
                                                <span class="input-group-addon">Institution Name:</span>
                                                <input type="text" class="form-control">
                                            </div>											
                                            <div class="form-group input-group">
                                                <span class="input-group-addon">Institution Shortname:</span>
                                                <input type="text" class="form-control">
                                            </div>
                                            <div class="form-group input-group">
                                                <span class="input-group-addon">Address:</span>
                                                <input type="text" class="form-control">
                                            </div>											
                                            <div class="form-group input-group">
                                                <span class="input-group-addon">Email:</span>
                                                <input type="text"  class="form-control">
                                            </div>																						
                                            <div class="form-group input-group">
                                                <span class="input-group-addon">Phone:</span>
                                                <input type="text" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Homepage Message::</label>
                                                <textarea class="form-control" rows="3"></textarea>
						                      <p class="help-block">Enter instructions and homepage information. <i>(HTML is allowed)</i></p>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.col-lg-6 (nested) -->
                                    <div class="col-lg-6">
                                        <form role="form">                                                                                        
                                            <div class="form-group input-group">
                                                <span class="input-group-addon">Admin Username:</span>
                                                <input type="text" class="form-control">
                                            </div>                                                                                        
                                            <div class="form-group input-group">
                                                <span class="input-group-addon">Admin Password:</span>
                                                <input type="text" class="form-control">
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
                                                <label>Show a list of all active tests in the home page?</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ShowRank" id="ShowRank" value="1" checked="" /> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="ShowRank" id="ShowRank" value="0" /> No
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>Selects Current Session</label>
                                                <select class="form-control">
                                                    <option />2014/2015
                                                    <option />2014/2015
                                                </select>
                                            </div>
                                            <div class="logo3">
                                                <img id="passport" src="../_res/img/passport/<?php echo $userid; ?>.jpg" 
                                                     alt="" width="120" height="120"  onerror="this.src = '../_res/img/logo3.png';" />
                                            </div>                                            
                                            <div class="form-group">
                                                <label>Upload Institution Logo</label>
                                                <input type="file">
                                            </div>                                      
                                            <button type="submit" class="btn btn-primary mt10">Save and Continue</button>
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
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                               EXAM SETTINGS
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <div class="alert alert-success text-center"> <?php if(isset($msg)){ echo $msg;  } ?></div>
                                    </div>
                                   
                                     <div class="col-lg-6">
                                        <form role="form" method="POST">                                                                                        

                                            <div class="form-group">
                                                <label> ACTIVE COLLEGE UNDER-GRADUATE </label>
                                            </div> 

                                             <div class="form-group">
                                                <label class="radio-inline">
                                                    <input type="checkbox" name="active_col[]" value="01" /> COSPED
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="checkbox" name="active_col[]" value="02" /> COSIT 
                                                </label>
                                                 <label class="radio-inline">
                                                    <input type="checkbox" name="active_col[]" value="03" /> COHUM
                                                </label>
                                                 <label class="radio-inline">
                                                    <input type="checkbox" name="active_col[]" value="04" /> COSMAS
                                                </label>
                                                 <label class="radio-inline">
                                                    <input type="checkbox" name="active_col[]" value="05" /> COVTED
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="checkbox" name="active_col[]" value="all" /> ALL COLLEGES
                                                </label>
                                            </div>            

                                                                                
                                            <button type="submit" name="activate_col" class="btn btn-info mt10">ACTIVATE COLLEGE</button>
                                        </form>
                                    </div>
                                    <div class="col-lg-6">
                                        <form role="form" method="POST">                                                                                        

                                            <div class="form-group">
                                                <label> ACTIVE COLLEGE CEPPS</label>
                                            </div> 

                                             <div class="form-group">
                                                <label class="radio-inline">
                                                    <input type="checkbox" name="active_col[]" value="01" /> COSPED
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="checkbox" name="active_col[]" value="02" /> COSIT 
                                                </label>
                                                 <label class="radio-inline">
                                                    <input type="checkbox" name="active_col[]" value="03" /> COHUM
                                                </label>
                                                 <label class="radio-inline">
                                                    <input type="checkbox" name="active_col[]" value="04" /> COSMAS
                                                </label>
                                                 <label class="radio-inline">
                                                    <input type="checkbox" name="active_col[]" value="05" /> COVTED
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="checkbox" name="active_col[]" value="all" /> ALL COLLEGES
                                                </label>
                                            </div>            

                                                                                
                                            <button type="submit" name="activate_col"  class="btn btn-info mt10">ACTIVATE COLLEGE</button>
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
<!--                
                <div class="row">
                    <div class="col-lg-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Session 
                            </div>
                             /.panel-heading 
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Session Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>2014/2015</td>
                                                <td><a href="#"><i class="fa fa-times red"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>2014/2015</td>
                                                <td><a href="#"><i class="fa fa-times red"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>Add New</td>
                                                <td><input class="form-control"></td>
                                                <td><a href="#">
                                                    <button class="btn btn-primary" type="submit"><i class="fa fa-floppy-o wh"></i> Save </button>			
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                 /.table-responsive 
                            </div>
                             /.panel-body 
                        </div>
                         /.panel 
                    </div>
                    <div class="col-lg-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Basic Table
                            </div>
                             /.panel-heading 
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Username</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Mark</td>
                                                <td>Otto</td>
                                                <td>@mdo</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Jacob</td>
                                                <td>Thornton</td>
                                                <td>@fat</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Larry</td>
                                                <td>the Bird</td>
                                                <td>@twitter</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                 /.table-responsive 
                            </div>
                             /.panel-body 
                        </div>
                         /.panel 
                    </div>
                     /.col-lg-6 
                </div>-->
            </div>
           <?php	require_once('footer_bar.php');	?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js.php');	?>

    </body>

</html>
