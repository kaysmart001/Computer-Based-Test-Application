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
if(isset($_GET['test'])) {
    $test_id = $_GET['test'];  
}

/*
 * Get test data
 */

$test_query = "SELECT `ts`.`id`, `TName` AS `test_name`, `NOQ` as `no_of_ques`, `assessments_code` AS `as_code`, 
                `session_name`, count(ut.user_id) as `user_total`
                FROM `tests` `ts` 
                JOIN `assessments` `as` ON `ts`.`assessments_id` = `as`.`id` 
                JOIN `session` `s` ON `as`.`session_id` = `s`.`id` 
                LEFT JOIN `user_test` as `ut` ON `ts`.`id` = `ut`.`test_id`
                WHERE `ts`.`id` = '$test_id'
                GROUP BY `ts`.`id`"; 
$result = mysqli_query($dbc, $test_query);
$test_result = mysqli_fetch_assoc($result);
$test_count = mysqli_num_rows($result);

$q_count_query = "SELECT `q`.`id`, COUNT(uc.id) AS `ques_user_total`               
                FROM `questions` `q` 
                JOIN `user_test` `ut` ON `q`.`test_id` = `ut`.`test_id` AND `ut`.`test_id` = '$test_id' 
                JOIN `user_choice` `uc` ON `ut`.`id` = `uc`.`user_test_id` AND `q`.`id` = `uc`.`q_id`               
                GROUP BY `q`.`id` 
                ORDER BY `q`.`id`"; 
$q_count = mysqli_query($dbc, $q_count_query);

// Multiplier to calculate question performance percentage
$multiplier = [];
for(; $q_count_result = mysqli_fetch_assoc($q_count);) {
    $multiplier[$q_count_result['id']] = 
            $q_count_result['ques_user_total'] == 0? 0: 100 / $q_count_result['ques_user_total'];
}

$ques_query = "SELECT `ts`.`id`, `question`, `choice1`, `choice2`, `choice3`, `choice4`, `qt`.`answer` AS `correct_choice`, 
                COUNT(uc.id) AS `user_total`, q_id, uc.answer                 
                FROM `tests` `ts`                  
                JOIN `questions` `qt` ON `ts`.`id` = `qt`.`test_id` AND `ts`.`id` = '{$test_id}'                 
                JOIN `user_choice` `uc` ON `qt`.`id` = `uc`.`q_id`                  
                GROUP BY `uc`.`q_id`, `uc`.`answer`
                ORDER BY q_id, answer"; 
//print_r($ques_query); die;
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
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-exampleX">
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
                                                    $ques_result = $next = mysqli_fetch_assoc($q_result);
                                                    
                                                    while($ques_exist) {
                                                        $ques_result = $next; 
                                                        
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
                                                        
                                                        // Check if multiplier factor is present. 
                                                        // Should be if the question was answered at least once.
                                                        $q_multiplier = isset($multiplier[$q_id])? $multiplier[$q_id]: 0;
                                                        
                                                        // Add count and percent for available answers.
                                                        $perf[$ques_result['answer']]['count'] = $ques_result['user_total'];
                                                        $perf[$ques_result['answer']]['percent'] = 
                                                                            round($ques_result['user_total'] * $q_multiplier, 2); 
                                                        
                                                        // Check next row if it belongs to the same question.
                                                        for (; $ques_next = mysqli_fetch_assoc($q_result);) {
                                                            
                                                            // Exit loop if row contains another question.
                                                            if($ques_next['q_id'] != $q_id) {
                                                                $next = $ques_next;
                                                                break;
                                                            }
//                                                                              
                                                            // Add count and percent for available answers.
                                                            $perf[$ques_next['answer']]['count'] = $ques_next['user_total'];
                                                            $perf[$ques_next['answer']]['percent'] = 
                                                                            round($ques_next['user_total'] * $q_multiplier, 2);                                                               
                                                                                                                        
                                                        }                                                        
                                                        
                                                        // If no more questions are available, end main questions loop.
                                                        if(!$ques_next) {
                                                            $ques_exist = false;
                                                        }                                                                                                               
                                            ?>
                                            <tr class="odd gradeX f12">
                                                <td><?php echo ++$key?></td>
                                                <td><?php echo $ques_result['question']?>   
<pre>(Choices)
 A) <?php echo $ques_result['choice1']?> 
 B) <?php echo $ques_result['choice2']?> 
 C) <?php echo $ques_result['choice3']?> 
 D) <?php echo $ques_result['choice4']?> 
</pre>
                                                </td>
                                                <td class="center">
                                                    <?php                                                         
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
                                                    }  
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
