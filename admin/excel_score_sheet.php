<!DOCTYPE html>

<head>
  <?php     
  require_once('../_inc/functions/CIFunctions.php');  

  $dir =  basename(__DIR__);
  $pgname = "Score Sheet";
  $ass_id = $_GET['ass'];
        function passm($cascore, $score){
            global $passmark;

            if(empty($cascore)){
              return 'TF';
            }
            if($score >= $passmark){
                return 'Passed';
            }else{
                return 'Failed';
            }
        }

        // function update_test_score($userid, $){

        // }

        function update_scores($userid, $test_type){
          global $ass_id, $csession_id, $dbc;
          $sql = "SELECT DISTINCT ass_user_id, ass_id, (score) as score FROM `user_assessments` ua 
                  JOIN user_test ut on (ut.user_id = ass_user_id) 
                  JOIN users us on (us.id = ass_user_id) 
                  JOIN tests ts on (ut.test_id = ts.id and ts.test_type =  $test_type and ts.assessments_id =  '$ass_id') 
                  WHERE ass_id =  '$ass_id' and ua.session_id =  $csession_id and ua.ass_user_id = '$userid'
                  GROUP BY ut.test_id";
         // print_r($sql); die();
          $data = mysqli_query($dbc, $sql);
          $row = mysqli_fetch_array($data);
          
          if (empty($row['score'])){
            return  '-';
          }else{
            $score = $row['score'];
            if($test_type == 1){
                $sql = "UPDATE user_assessments SET ca_score = '$score' 
                    WHERE ass_id = '$ass_id' AND session_id = $csession_id AND ass_user_id = '$userid'";
            }else{
                $sql = "UPDATE user_assessments SET exam_score = '$score' 
                    WHERE ass_id = '$ass_id' AND session_id = $csession_id AND ass_user_id = '$userid'";
            }
            mysqli_query($dbc, $sql);
           // print_r($sql); die();


            $data = mysqli_query($dbc, $sql);
            return  $row['score'];
          }
        }

        function check_score($score, $userid, $test_type){
            global $passmark;
            if(empty($score)){

             return  update_scores($userid, $test_type);
                'NULL';
            }else{
                return $score;
            }
        }

  ?>  

<?php $excelFileName = $ass_id.'.xls';       
      export_excel($excelFileName);  
?>
    <title> <?= $_GET['ass']; ?> <?php echo $pgname; ?> </title>
      <!-- Page-Level Plugin CSS - Tables -->

</head>

<body>
    <!-- Page container -->
    <div class="page-container container">
      
        <!-- Page content -->
        <div class="page-content">
      <div class="widget">
      <div class=" text-center">
    </div>
                            
      <div class="panel panel-default">
                                <?php //  echo                                                               
                                 $querytest = " SELECT * FROM `assessments` ass
                                                JOIN session on (session_id = session.id)
                                                WHERE ass.id = '$ass_id'"; 
                                   $datatest = mysqli_query($dbc, $querytest);
                                   $rowats = mysqli_fetch_array($datatest);
                                    $asscode = $rowats['assessments_code'];
                                    $assname = $rowats['assessments_name'];
                                    $session_name = $rowats['session_name'];                               
                              // }  
                               $countts = mysqli_num_rows($datatest);                                   
                               ?>
                            
                            <?php if ($countts){  ?>
                            
                                     
                        </div>
                            
                         <div class="panel panel-default">
                             
                            
                            <div class="table-responsive">
                                <table class="" id="dataTables-example" >
                                        
                                        <tbody>
                                        <?php 
                                        $querytest = " SELECT DISTINCT us.id as usid, fname, mname, lname, userid, exam_score, ca_score
                                                       FROM user_assessments ua
                                                       JOIN users us on (ua.ass_user_id = us.id)
                                                       WHERE ua.ass_id = '$ass_id'
                                                          ";
                                           // print_r($querytest); die();
                                           $datatest = mysqli_query($dbc, $querytest);
                                              
                                            $n=1; $ns=0; while($rowats = mysqli_fetch_array($datatest)){
                                                        $uid = $rowats['usid'];
                                                        $LName = $rowats['lname'];
                                                        $FName = $rowats['fname'];
                                                        $MName = $rowats['mname'];
                                                        $userid = $rowats['userid'];
                                                        $cascore = check_score($rowats['ca_score'], $userid, 1);
                                                        $escore = check_score($rowats['exam_score'], $userid, 2);
                                                        $tscore = $cascore + $escore;
                                                                                                      
                                                echo '
                                                <tr class="odd gradeX">
                                                    <td>'.$userid.'</td>
                                                    <td class="center">'.$cascore.'</td>
                                                    <td class="center">'.$escore.'</td>
                                          </tr>';
                                        $n++;  }?>                      
                                        </tbody>
                                    </table>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                           <?php  }  else {
                                    echo '<div class="text-center text-danger"> <h3> No Record Found </h3></div>';

                                            }  ?>
                        </div>
                        <!-- /.panel --><!-- /bordered datatable inside panel -->
      </div>
      <!-- With titles (frame) -->
    </div>
  </div>
  <!-- Footer -->
</body>
</html>