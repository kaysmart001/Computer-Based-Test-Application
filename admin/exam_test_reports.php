<!DOCTYPE html>
<html>

    <head>
        <?php $pgname = "Exam Statistics"; $dir =  basename(__DIR__);
        require_once('../_inc/inc_head.php');
        ?>		
    </head>
<?php

            
            if (isset($_GET['ass'])) {
                $ass_id = $_GET['ass'];
            }
            
            $ass_code = nameId('id', 'assessments_code', 'assessments', $ass_id);
            $ass_name = nameId('id', 'assessments_name', 'assessments', $ass_id);
           
/**
 *  Process pagination
 */

if(isset($_GET['tsid'])) {

    $test_id = $_GET['tsid'];
   $test_query = "UPDATE user_test SET finish = 0, score = NULL WHERE test_id = '$test_id'";
    $result = mysqli_query($dbc, $test_query) or die(mysqli_error($dbc));
    header("Location : ?ass=$ass_id");
}


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
                JOIN `assessments` `as` ON `ts`.`assessments_id` = `as`.`id` AND `ts`.`assessments_id` = '{$ass_id}' 
                JOIN `session` `s` ON `as`.`session_id` = `s`.`id` AND `s`.`session_active` = 1
                LEFT JOIN `user_test` as `ut` ON `ts`.`id` = `ut`.`test_id`                 
                GROUP BY `ts`.`id`
                LIMIT {$limit}, {$count}";
$result = mysqli_query($dbc, $test_query) or die(mysqli_error($dbc));
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
                        <h3 class="page-header"> Assessment Tests Reports</h3>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Tests Properties for <b>  <?php echo $ass_code; ?>  -  <?php echo $ass_name; ?> </b>
                        <span style="float:right;">
                            <a href="print_score_sheet.php?ass=<?php echo $ass_id; ?>" target="_blank"><button class="btn btn-grey btn-xs"><i class="fa  fa-print "></i> Print Assessment Score Sheet  </button> </a>

                            <button class="btn btn-info btn-xs" onclick="goBack()"><i class="fa  fa-backward "></i> Back  </button>
                        </span>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr style="font-size: 14px">
                                        <th>#</th>
                                        <th>Test Name</th>
                                        <th>Exam Questions </th>
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
                                    ?>
                                    <tr>
                                        <td><?php echo ++$key ?></td>
                                        <td><?php echo $tests['test_name'] ?> <a href="question_analysis.php"> 
                                        <td><?php echo $tests['no_of_ques'] ?> 
                                            <a href="question_analysis.php?test=<?php echo $tests['id']?>">
                                                <button title="View Questions Analysis" 
                                                        class="btn btn-outline btn-success btn-xs" type="button">
                                                    View
                                                </button>
                                            </a>
                                        </td>												
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
                                            <a href="exam_test_reports.php?ass=<?= $ass_id ?>&&tsid=<?= $tests['id'] ?>"><button class="btn btn-danger btn-xs"><i class="fa  fa-refresh "></i> Reset Score  </button> </a>

                                            <a href="print_test_score_sheet.php?tsid=<?php echo $tests['id'] ?>" target="_blank"><button class="btn btn-default btn-xs"><i class="fa  fa-print "></i> Print Sheet  </button> </a>                                                                                            
                                            <a href="excel_test_score_sheet.php?tsid=<?php echo $tests['id'] ?>"><button class="btn btn-primary btn-xs"><i class="fa fa-arrow-down"></i> Download Excel</button> </a>                                                                                            
                                        </td>
                                    </tr>
                                    <?php   
                                            endfor;
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
            </div>
<?php // require_once('footer_bar.php'); ?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
<?php require_once('../_inc/inc_js.php'); ?>

    </body>

</html>
