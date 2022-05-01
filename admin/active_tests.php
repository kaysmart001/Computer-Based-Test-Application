<!DOCTYPE html>
<html>

    <head>
        <?php $pgname = "Active Test & Users"; $dir =  basename(__DIR__);
        require_once('../_inc/inc_head.php');
        ?>	
    </head>
<?php


/**
 *  Process pagination
 */

$count = 15;
$page_no = $limit = 0;
if(isset($_GET['page_no']) && $_GET['page_no'] > 0) {
    $limit = ($_GET['page_no'] - 1) * $count;    
}

    if (isset($_GET['ass'])) {
        $ass_id = $_GET['ass'];
    }

    $post_url = $_SERVER['PHP_SELF'].'?ass='.$ass_id;
    //$msg = '';

    $ass_code = nameId('id', 'assessments_code', 'assessments', $ass_id);
    $ass_name = nameId('id', 'assessments_name', 'assessments', $ass_id);

$prev = $page_no > 1? "exam_stat.php?page_no=".($page_no - 1): "";

// TODO process after test query count
$next = $page_no ? "exam_stat.php?page_no=": "";


/*
 * Get test data
 */
$test_query = "SELECT `ts`.`id`, `TName` as `test_name`, `NOQ` as `no_of_ques`, `assessments_code` as `as_code`, `assessments_name` as `as_name`,
                `session_name`, COUNT(ut.user_id) as `user_total`, AVG(score) as `avg_score`, AVG(time_length) as `avg_time`, MAX(score) as highscore, MIN(score) as lowscore
                FROM `tests` `ts` 
                JOIN `assessments` `as` ON `ts`.`assessments_id` = `as`.`id` 
                JOIN `session` `s` ON `as`.`session_id` = `s`.`id` AND `s`.`session_active` = 1
                LEFT JOIN `user_test` as `ut` ON `ts`.`id` = `ut`.`test_id` 
                GROUP BY `ts`.`id`
                LIMIT {$limit}, {$count}"; 
$result = mysqli_query($dbc, $test_query);
$test_count = mysqli_num_rows($result);

?>

    <body>

        <div id="wrapper">

            <?php require_once('_inc/inc_topnav.php'); ?>
            <?php require_once('_inc/inc_sidebar.php'); ?>
            <!-- /.navbar-static-side -->

            <div id="page-wrapper">
                
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header" id='assid' data-code='<?php echo $ass_code; ?>' data-value='<?php echo $ass_id; ?>' > Active Test </h3>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row stats">
                    <div class="col-xs-6 col-md-3">
                        <div class="panel panel-primary text-center panel-eyecandy">
                            <div class="panel-body asbestos">
                                <h3><span id='countassusers'><?php echo countdatasession('assessment_code', $ass_code, 'assessments_users'); ?> </span><i class="fa fa-users"></i></h3>
                            </div>
                            <div class="panel-footer">
                                <span class="panel-eyecandy-title">
                                    Total Users
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-xs-6 col-md-3 -->
                    <div class="col-xs-6 col-md-3">
                        <div class="panel panel-primary text-center panel-eyecandy">
                            <div class="panel-body theme-color">
                                <h3><span id='countacitve'><?php echo countSQL("SELECT assessment_code FROM assessments_users, users us WHERE assessment_code = '$ass_code' AND session_id = $csession_id AND username = userid and login = 1 ", $dbc); ?> </span> <i class="fa fa-sign-in"></i></h3>
                            </div>
                            <div class="panel-footer">
                                <span class="panel-eyecandy-title">
                                    Active users
                                </span>
                            </div>
                        </div>
                    </div>
               
                    <!-- /.col-xs-6 col-md-3 -->
                    <div class="col-xs-6 col-md-3">
                        <div class="panel panel-primary text-center panel-eyecandy">
                            <div class="panel-body cool">
                                <h3><span id='countfinish'></span> <i class="fa fa-check-circle"></i></h3>
                            </div>
                            <div class="panel-footer">
                                <span class="panel-eyecandy-title">
                                    Finished
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-xs-6 col-md-3 -->     <!-- /.col-xs-6 col-md-3 -->
                    <div class="col-xs-6 col-md-3">
                        <div class="panel panel-primary text-center panel-eyecandy">
                            <div class="panel-body warm">
                                <h3><span id='countwaiting'></span> <i class="fa fa-spinner"></i></h3>
                            </div>
                            <div class="panel-footer">
                                <span class="panel-eyecandy-title">
                                    Waiting
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
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
                                            <th>Active</th>
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
                                        while ($row = mysqli_fetch_array($data)) {
                                            $testid = $row['id'];

                                            echo '<tr>
                                            <td>' . $n . '</td>
                                            <td>' . $row['TName'] . '</td>
                                            <td>' . countdata('test_id', $testid, 'questions') . '</td>
                                            <td>' . $row['NOQ'] . '</td>
                                            <td>' . $row['time'] . 'mins</td>
                                            <td>' . $row['test_mark'] . '</td>
                                            <td>' . yesno($row['be_default']) . '</td>
                                            <td>' . yesno($row['random']) . '</td>
                                            <td>' . yesno($row['show_answers']) . '</td>
                                            <td>' . yesno($row['show_mark']) . '</td>
                                            <td>' . yesno($row['show_rank']) . '</td>
                                            <td data-id="' . $row['id'] . '">                                   
                                                    <div class="btn-group">
                                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                                    Actions
                                                                    <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu pull-right" role="menu">
                                                                    <li><a href="add_test.php?ass=' . $ass_id . '&testid=' . $testid . '">Add Question to Test</a></li>
                                                                <!--    <li><a href="#">Edit Test Properties</a></li>
                                                                    <li><a href="#">Edit Questions</a></li>
                                                                    <li class="divider"></li>
                                                                    <li><a href="#">Add Question from Questions Bank</a>
                                                                    <li><a href="#">Delete Test</a>
                                                                    <li><a href="#">Print Test</a>
                                                                    <li><a href="#">Export</a> -->
                                                                    </li>
                                                            </ul>
                                                    </div>
                                            </td>
                                        </tr>';
                                            $n++;
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div> 

                <!-- /.row -->

                        <?php  require_once('_inc_active_users.php'); ?>	

            </div>
<?php //require_once('footer_bar.php'); ?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
<?php require_once('../_inc/inc_js.php'); ?>
 <script>
        function loadm() {
            $('#addQModal').modal('show');
        };
        
        var td;
        
    $(document).on("click", ".logout", function() {
                 
            td = $(this).parent().prev().prev('td');
            var userid = $(this).data('value');
            
            $.ajax({
                type: "POST",
                url: "../students/_inc/process.php",
                data: {logoutusid: userid},
                success: function (data) {
                    td.html(data)
                },
                error: function (exception) {
                    alert('error connecting to server');
                }
            });
        });
     
     function logoutall(){
         $.ajax({
                type: "POST",
                url: "../students/_inc/process.php",
                data: {logoutall: 'yes'},
                success: function (data) {
                   alert('all users logout sucessful');
                },
                error: function (exception) {
                    alert('error connecting to server');
                }
            });         
     }
    $(document).on("click", "#logoutall", function() {
       logoutall(); 
    });
     
        window.onload = function () {
        var ass_id = $('#assid').data('value');  
        var ass_code = $('#assid').data('code');  
        //alert(ass_id);
        var counttype;
            function countuser(counttype, ass_id){
                var counttype = counttype;
                $.ajax({
                    type: "POST",
                    url: "../students/_inc/process.php",
                    data: {counttype: counttype, assid: ass_id, ass_code: ass_code },
                    success: function (data) {
                        $('#'+counttype).html(data);
                        var countfinish = $('#countfinish').html();
                        var countassusers = $('#countassusers').html();
                        var  waiting = (countassusers - countfinish );
                        $('#countwaiting').html(waiting);
                    },
                    error: function (exception) {
                        alert('error connecting to server');
                    }
                });
            };


           setInterval(function () { countuser('countfinish', ass_id); }, 5000); 
           setInterval(function () { countuser('countacitve', ass_id); }, 5000); 
           setInterval(function () { countuser('countassusers', ass_id); }, 60000);            
                      
        };
    </script>

    </body>

</html>
