<!DOCTYPE html>
<html>
    <head>      
            <?php
            $dir =  basename(__DIR__);
            $pgname = "Question Analysis";
            require_once('../_inc/inc_head.php');?>

        <!-- Page-Level Plugin CSS - Tables -->
    <link href="../_res/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    </head>
<?php
$test_id = NULL;
if(isset($_GET['test']) && $_GET['test'] > 0) {
    $test_id = $_GET['test'];  
}


/*
 * Get test data
 */

$test_query = "SELECT `ts`.`id`, `TName` as `test_name`, `NOQ` as `no_of_ques`, `assessments_code` as `as_code`, 
                `session_name`, count(ut.user_id) as `user_total`
                FROM `tests` `ts` 
                JOIN `assessments` `as` ON `ts`.`assessments_id` = `as`.`id` 
                JOIN `session` `s` ON `as`.`session_id` = `s`.`id` 
                LEFT JOIN `user_test` as `ut` ON `ts`.`id` = `ut`.`test_id`
                WHERE `ts`.`id` = {$test_id}
                GROUP BY `ts`.`id`"; 
$result = mysqli_query($dbc, $test_query);
$test_result = mysqli_fetch_assoc($result);
$test_count = mysqli_num_rows($result);

// To calculate performance percentage
$multiplier = $test_result['user_total'] == 0? 0: 100 / $test_result['user_total'];

$ques_query = "SELECT `ts`.`id`, `question`, `choice1`, `choice2`, `choice3`, `choice4`, `qt`.`answer` as `correct_choice`, 
                COUNT(uc.id) as `user_total`, q_id, uc.answer                 
                FROM `tests` `ts`                  
                JOIN `questions` `qt` ON `ts`.`id` = `qt`.`test_id` AND `ts`.`id` = {$test_id}                 
                JOIN `user_choice` `uc` ON `qt`.`id` = `uc`.`q_id`                  
                GROUP BY `uc`.`q_id`, `uc`.`answer`
                ORDER BY q_id, answer"; 
$q_result = mysqli_query($dbc, $ques_query);
$ques_count = mysqli_num_rows($q_result);
?>
    <body>

        <div id="wrapper">

            <?php require_once('_inc/inc_topnav.php'); ?>
<?php require_once('_inc/inc_sidebar.php'); ?>
            <!-- /.navbar-static-side -->


            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header">Statistics & Analysis</h3>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Exam Question Analysis for 
                                <b> <?php echo "{$test_result['as_code']} - {$test_result['test_name']}"?> </b> for 
                                <b><?php echo "{$test_result['session_name']}"?></b>
                                <span style="float:right;">
                                    <button class="btn btn-info btn-xs" onclick="goBack()"><i class="fa  fa-backward "></i> Back  </button>
                                </span>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="form-group input-group col-lg-6">
                                    <input type="text" class="form-control">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                                <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                                    <ul class="pagination">
                                        <li class="paginate_button previous disabled" aria-controls="dataTables-example" 
                                            tabindex="0" id="dataTables-example_previous">
                                            <a href="#">Previous</a>
                                        </li>
                                        <li class="paginate_button active" aria-controls="dataTables-example" tabindex="0">
                                            <a href="#">1</a></li>
                                        <li class="paginate_button " aria-controls="dataTables-example" tabindex="0">
                                            <a href="#">2</a>
                                        </li>
                                        <li class="paginate_button " aria-controls="dataTables-example" tabindex="0">
                                            <a href="#">3</a>
                                        </li>
                                        <li class="paginate_button " aria-controls="dataTables-example" tabindex="0">
                                            <a href="#">4</a>
                                        </li>
                                        <li class="paginate_button " aria-controls="dataTables-example" tabindex="0">
                                            <a href="#">5</a>
                                        </li>
                                        <li class="paginate_button " aria-controls="dataTables-example" tabindex="0">
                                            <a href="#">6</a>
                                        </li>
                                        <li class="paginate_button next" aria-controls="dataTables-example" tabindex="0" 
                                            id="dataTables-example_next">
                                            <a href="#">Next</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="clear" style="clear:both"></div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Question</th>
                                                <th>A</th>
                                                <th>B</th>
                                                <th>C</th>
                                                <th>D</th>
                                                <th>Unanswered</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                if($ques_count > 0) :
                                                    $key = 0;
                                                    $ques_exist = true;
                                                    $ques_result = $last = mysqli_fetch_assoc($q_result);
                                                    
                                                    do {   
                                                        // Define performance holding array.
                                                        $perf = [
                                                            '1' => [
                                                                'count' => 0,
                                                                'percent' => 0
                                                            ],
                                                            '2' => [
                                                                'count' => 0,
                                                                'percent' => 0
                                                            ],
                                                            '3' => [
                                                                'count' => 0,
                                                                'percent' => 0
                                                            ],
                                                            '4' => [
                                                                'count' => 0,
                                                                'percent' => 0
                                                            ],
                                                            '' => [
                                                                'count' => 0,
                                                                'percent' => 0
                                                            ]
                                                        ];
                                                        
                                                        // Set the correct answer
                                                        $perf[$ques_result['correct_choice']]['right'] = true;
                                                                                                             
                                                        // Add count and percent for available answers.
                                                        $q_id = $ques_result['q_id'];                                    
                                                        
                                                        $perf[$ques_result['answer']]['count'] = $ques_result['user_total'];
                                                        $perf[$ques_result['answer']]['percent'] = round($ques_result['user_total'] * $multiplier, 2); 
                                                        
                                                        
                                                        for (; $ques_result = mysqli_fetch_assoc($q_result);) {
                                                            if($ques_result['q_id'] != $q_id) {
                                                                break;
                                                            }
                                                            
                                                            // Keep a hold of the last row for the last question in the UI
                                                            $last = $ques_result;
                                                            
                                                            $perf[$ques_result['answer']]['count'] = $ques_result['user_total'];
                                                            $perf[$ques_result['answer']]['percent'] = round($ques_result['user_total'] * $multiplier, 2);                                                                                                                      
                                                            
                                                        }
                                                        
                                                        if(!$ques_result) {
                                                            $ques_result = $last;
                                                            $ques_exist = false;
                                                        }                                                                                                               
                                            ?>
                                            <tr class="odd gradeX f12">
                                                <td><?php echo ++$key ?></td>
                                                <td><?php echo $ques_result['question']?>   
<pre>(Choices)
 A) <?php echo $ques_result['choice1']?> 
 B) <?php echo $ques_result['choice2']?> 
 C) <?php echo $ques_result['choice3']?> 
 D) <?php echo $ques_result['choice4']?> 
</pre>
                                                </td>
                                                <td class="center">
                                                    <?php                                                   // print_r($perf);                                                
                                                        if(isset($perf['1']['right'])){
                                                            echo "
                                                        <button class='btn btn btn-primary btn-xs' type='button'>
                                                            Correct {$perf['1']['count']} ({$perf['1']['percent']}%)
                                                        </button>";
                                                         }else{ echo "{$perf['1']['count']} ({$perf['1']['percent']}%)";  } 
                                                     ?>

                                                </td>
                                                <td class="center">
                                                    <?php                                                         
                                                    if(isset($perf['2']['right'])){
                                                        echo "
                                                    <button class='btn btn btn-primary btn-xs' type='button'>
                                                        Correct {$perf['2']['count']} ({$perf['2']['percent']}%)
                                                    </button>";
                                                     }else{ echo "{$perf['2']['count']} ({$perf['2']['percent']}%)";  } 
                                                     ?>
                                                    
                                                </td> 
                                                <td class="center">
                                                    <?php                                                         
                                                    if(isset($perf['3']['right'])){
                                                        echo "
                                                    <button class='btn btn btn-primary btn-xs' type='button'>
                                                        Correct {$perf['3']['count']} ({$perf['3']['percent']}%)
                                                    </button>";
                                                     }else{ echo "{$perf['3']['count']} ({$perf['3']['percent']}%)";  } 
                                                     ?>
                                                </td>
                                                <td class="center">
                                                    <?php                                                         
                                                    if(isset($perf['4']['right'])){
                                                        echo "
                                                    <button class='btn btn btn-primary btn-xs' type='button'>
                                                        Correct {$perf['4']['count']} ({$perf['4']['percent']}%)
                                                    </button>";
                                                     }else{ echo "{$perf['4']['count']} ({$perf['4']['percent']}%)";  } 
                                                     ?>
                                                </td>
                                                <td class="center">
                                                    <?php echo "{$perf['']['count']} ({$perf['']['percent']}%)"?>
                                                </td>
                                            </tr>
                                            
                                            <?php       
                                                    }while($ques_exist);   
                                                else :                                    
                                            ?>

                                            <tr>
                                                <td colspan="6">There are no questions in this test!</td>                                        
                                            </tr>

                                            <?php endif;?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /#page-wrapper -->

<?php require_once('footer_bar.php'); ?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
<?php require_once('../_inc/inc_js.php'); ?>

    </body>

</html>
