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
//$no_percent10 = no_of_perc($ass_id, 0, 10);
//$no_percent20 = no_of_perc($ass_id, 11, 20);
//$no_percent30 = no_of_perc($ass_id, 21, 30);
//$no_percent40 = no_of_perc($ass_id, 31, 40);
//$no_percent50 = no_of_perc($ass_id, 41, 50);
//$no_percent60 = no_of_perc($ass_id, 51, 60);
//$no_percent70 = no_of_perc($ass_id, 61, 70);
//$no_percent80 = no_of_perc($ass_id, 71, 80);
//$no_percent90 = no_of_perc($ass_id, 81, 90);
//$no_percent100 = no_of_perc($ass_id, 91, 100);
//
//
//$d[] = array('asscode' => "PERCENTAGE " ,'10' => "$no_percent10",'20' => "$no_percent20",'30' => "$no_percent30",
//    '40' => "$no_percent40",'50' => "$no_percent50",'60' => "$no_percent60",'70' => "$no_percent70",
//    '80' => "$no_percent80",'90' => "$no_percent90",'100' => "$no_percent100",  );

//
////no_of_perc_test(1, 0, 10);
//
//$no_percent10 = no_of_perc($ass_id, 0, 10);
//$e[] = array('asscode' => '10%', 'perc' => "$no_percent10");  
//
//$c = 10;
//while ($c <= 90) {
//    $c0 = $c;
//    $c1 = $c + 1;
//    $c += 10;
//    '<br/>';
//    $no_percent = no_of_perc($ass_id, $c1, $c);
//    $e[] = array('asscode' => $c . '%', 'perc' => "$no_percent");
//} 
     $no_percent10  = no_of_perc_test(1, 0, 10);
    $e[] = array('asscode' => '10%', 'perc' => "$no_percent10");  
      $mark = nameId('id', 'test_mark', 'tests', 1);

    $c = 10;
    while ($c <= 90) {
        $c0 = $c;
        $c1 = $c + 1;
        $c += 10;
        
        $c1b = get_perc_val($c1, $mark);
        $cb = get_perc_val($c, $mark);

        //echo '<br/>';
        $no_percent = no_of_perc_test(1, $c1b, $cb);
        $e[] = array('asscode' => $c . '%', 'perc' => "$no_percent");
    }
 $test_query = "SELECT `ts`.`id`, `TName` as `test_name`, `NOQ` as `no_of_ques`, `assessments_code` as `as_code`, `assessments_name` as `as_name`,
                `session_name`, COUNT(ut.user_id) as `user_total`, AVG(score) as `avg_score`, AVG(time_length) as `avg_time`, MAX(score) as highscore, MIN(score) as lowscore
                FROM `tests` `ts` 
                JOIN `assessments` `as` ON `ts`.`assessments_id` = `as`.`id` AND `ts`.`assessments_id` = $ass_id
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
                        <h3 class="page-header"> Assessment Tests Reports</h3>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Tests Properties for <b>  <?php echo $ass_code; ?>  -  <?php echo $ass_name; ?> </b>
                        <span style="float:right;">
<!--                            <a href="print_score_sheet.php?ass=<?php echo $ass_id; ?>" target="_blank"><button class="btn btn-grey btn-xs"><i class="fa  fa-print "></i> Print Assessment Score Sheet  </button> </a>-->

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
                                        <th>Action</th>
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
                                            <a href="#" data-toggle="modal" data-target="#myModal" ><button class="btn btn-default btn-xs"><i class="fa fa-bar-chart-o"></i> View Chart  </button> </a>                                                                                            
                                        </td>
<!--                                        <td>  
                                            <a href="print_test_score_sheet.php?tsid=<?php echo $tests['id'] ?>" target="_blank"><button class="btn btn-default btn-xs"><i class="fa  fa-print "></i> Print Test Sheet  </button> </a>                                                                                            
                                        </td>-->
                                    </tr>
                                    <?php   
                                            endfor; 
                                              //$dj = json_encode($d);
                                              $ej = json_encode($e);
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
                                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-bar-chart-o fa-fw"></i> Assessment Users and Questions Chart
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
<!--                                <div style=" z-index: 99999; position: absolute; right: 30px; padding: 10px;" class="well">
                                    <span>
                                        <span style="color: #0b62a4; float: right"> Users: <i class="fa fa-square"></i> </span><br/>
                                        <span style="color: #7a92a3; float: right"> Questions: <i class="fa fa-square"></i></span>
                                    </span>
                                </div>-->
                                <div id="bar-example"></div>
                            </div>
                            <!-- /.panel-body -->
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
 <!-- Add a New Test Modal Start -->               
                    <div class="modal fade modal-primary" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title text-primary" id="myModalLabel"><i class="fa fa-pencil"></i> Add a New Test to <b> <?php echo $ass_code; ?> </b> -  <?php echo $ass_name; ?>                    
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            
                                        </div>
                                    </div>                           
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    
            </div>
<?php require_once('footer_bar.php'); ?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
<?php require_once('../_inc/inc_js2.php'); ?>
        <script>
            Morris.Bar({
            element: 'bar-example',
            data: <?php echo $ej ?>,
            xkey: 'asscode',
            ykeys: ['perc'],
            labels: ['No of Users']
        });
    </script>
    </body>

</html>
