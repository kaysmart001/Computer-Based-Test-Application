<!DOCTYPE html>
<html>

    <head>
        <?php $pgname = "Exam Statistics"; $dir =  basename(__DIR__);
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
                        <h3 class="page-header">Statistics </h3>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Exam Properties
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr style="font-size: 14px">
                                        <th>#</th>
                                        <th>Exam Code</th>
                                        <th>Test Name</th>
                                        <th>Exam Questions </th>
                                        <th>Session </th>
                                        <th>Participants</th>
                                        <th>Highest Grade</th>
                                        <th>Avg. Grade</th>
                                        <th>Lowest Grade</th>
                                        <th>Avg. Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        if($test_count > 0) :
                                            $key = 0;
                                            for (; $tests = mysqli_fetch_assoc($result);) :
                                                $name = $tests['as_code'].' '.$tests['test_name']; $users = $tests['user_total']; $noq = $tests['no_of_ques'];
                                                $d[] = array('asscode' => "$name" ,'users' => "$users",'noq' => "$noq",  );
                                    ?>
                                    <tr>
                                        <td><?php echo ++$key ?></td>
                                        <td><?php echo $tests['as_code'] ?> 
                                            <button title="<?php echo $tests['as_name'] ?>" 
                                                    class="btn btn-outline btn-success btn-xs" type="button">
                                                ?
                                            </button>
                                        </td>
                                        <td><?php echo $tests['test_name'] ?> <a href="question_analysis.php"> 
                                        <td><?php echo $tests['no_of_ques'] ?> 
                                            <a href="question_analysis.php?test=<?php echo $tests['id']?>">
                                                <button title="View Questions Analysis" 
                                                        class="btn btn-outline btn-success btn-xs" type="button">
                                                    View
                                                </button>
                                            </a>
                                        </td>												
                                        <td><?php echo $tests['session_name'] ?>  </td>
                                        <td><?php echo $tests['user_total'] ?>  
                                            <a href="score_sheet.php?tsid=<?php echo $tests['id']?>"> 
                                                <button title="View Result Sheet" 
                                                        class="btn btn-outline btn-success btn-xs" 
                                                        type="button">
                                                    View
                                                </button>
                                            </a>
                                        </td>
                                        <td><?php echo round($tests['highscore'],2); ?>% </td>                                                
                                        <td><?php echo round($tests['avg_score'],2); ?>% </td>                                                
                                        <td><?php echo round($tests['lowscore'],2); ?>% </td>                                                
                                        <td><?php echo round($tests['avg_time'],2); ?></td>
                                        <td>                                   
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" 
                                                        data-toggle="dropdown">
                                                    Actions
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu pull-right" role="menu">
                                                    <li><a href="question_analysis.php?test=<?php echo $tests['id']?>">View Questions Analysis</a></li>
                                                    <li><a href="score_sheet.php?tsid=<?php echo $tests['id']?>">View Entrants Performance</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="print_score_sheet.php?tsid=<?php echo $tests['id']; ?>" target="_blank">Print Result</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php   
                                            endfor;
                                              $dj = json_encode($d);
                                        else :                                    
                                    ?>
                                    
                                    <tr>
                                        <td colspan="9">There are no tests defined yet!</td>                                        
                                    </tr>
                                    
                                    <?php endif;?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.row -->
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-bar-chart-o fa-fw"></i> Assessment Users and Questions Chart
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div style=" z-index: 99999; position: absolute; right: 30px; padding: 10px;" class="well">
                                    <span>
                                        <span style="color: #0b62a4; float: right"> Users: <i class="fa fa-square"></i> </span><br/>
                                        <span style="color: #7a92a3; float: right"> Questions: <i class="fa fa-square"></i></span>
                                    </span>
                                </div>
                                <div id="bar-example"></div>
                            </div>
                            <!-- /.panel-body -->
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>

                
            </div>
<?php require_once('footer_bar.php'); ?>

            <!-- /#page-wrapper -->
        
    </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
<?php require_once('../_inc/inc_js.php'); ?>

        <script>
            Morris.Bar({
            element: 'bar-example',
            data: <?php echo $dj ?>,
            xkey: 'asscode',
            ykeys: ['noq', 'users'],
            labels: ['Questions', 'Users']
        });
    </script>

    </body>

</html>
