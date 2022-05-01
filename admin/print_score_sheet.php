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
  <title> <?= $_GET['ass']; ?> <?php echo $pgname; ?> </title>
      <!-- Page-Level Plugin CSS - Tables -->
<link href="../_res/css/bootstrap.min2.css" rel="stylesheet">
</head>

<body class="full-width" style="background-color:#333">
    <!-- Page container -->
    <div class="page-container container" style="background-color:white; min-height:800px; padding: 20px; width:990px; font-size: 12px">
      
        <!-- Page content -->
        <div class="page-content">
      <div class="widget">
      <div class=" text-center">
        <?php printHeader();  ?>    
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
                            
                                     <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Test Added</th> 
                                                <th>Student Registered</th>                                     
                                                <th>No of Batch</th>                                     
                                                <th>No Assessed</th>                                     
                                                <th>No Passed</th>                                     
                                                <th>No Fail</th>                                     
                                                <th>Highest Score</th>                                     
                                                <th>Lowest Score</th>                                     
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php 
                                                 $query = "  SELECT ass.id, ass.assessments_code, asst.assessment_type, se.session_name, ass.multiple_test
                                                            FROM assessments ass, 
                                                            session se, assessments_type asst
                                                            WHERE ass.session_id = se.id
                                                            AND ass.assessments_type = asst.id
                                                            AND ass.id = '$ass_id'
                                                            AND ass.session_id = '$csession_id'"; 

                                                $data= mysqli_query($dbc, $query);

                                                $count = mysqli_num_rows($data);

                                                $n = 1;
                                                while ($row = mysqli_fetch_array($data)){ 
                                                $assid = $row['id'];
                                                $assessment_code =  $row['assessments_code'];
                                                $assessed = no_of_users_assessed2($assid);
                                                $passno = no_of_pass2($assid);
                                                $failedno = $assessed - $passno;
                                                @$pass_percent = round(($passno/$assessed * 100), 2);
                                                @$failed_percent = round(($failedno/$assessed * 100), 2);
                                                        echo '<tr>
                                                                <td>'.countdata('assessments_id', $assid, 'tests').'</td>
                                                                <td>'.countdatasession('assessment_code', $assessment_code, 'assessments_users').'</td>
                                                                <td>'.countdatasessiondist('assessment_code', $assessment_code, 'batch', 'assessments_users').'</td>                                          
                                                                <td>'.$assessed.'</td>                                          
                                                                <td>'.$passno.' ('.$pass_percent.'%)</td>
                                                                <td>'.$failedno.' ('.$failed_percent.'%)</td> 
                                                                <td>'.total_scores($assid)["max"].'</td> 
                                                                <td>'.total_scores($assid)["min"].'</td> 
                                                                    

                                                            </tr>';         
                                                $n++;  
                                                $passno = $assessed = $failedno = 0; }
                                            ?> 
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                            
                         <div class="panel panel-default">
                             <div class="panel-heading">
                                <?php //  echo                                                               
                               if ($countts){           
                                    echo 'Score Sheet for <b>'.$asscode.' - '.$assname.' </b> for <b>'.$session_name.'</b>';
                               }      
                               ?>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover sans" id="dataTables-example" >
                                        <thead>
                                            <tr style="font-size: 12px">
                                                <th>No</th>
                                                <th>Matric No</th>
                                                <th>Surname</th>
                                                <th>First Name</th>
                                                <th>Other Name</th>
                                                <th>C.A</th>  
                                                <th>EXAM</th>  
                                                 <th>Total</th>
                                                 <th>Remark</th>
                                                   
                                            </tr>
                                        </thead>
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
                                                    <td>'.$n.'</td>
                                                    <td>'.$userid.'</td>
                                                    <td>'.$LName.'</td>
                                                    <td>'.$FName.'</td>
                                                    <td>'.$MName.'</td>
                                                    <td class="center">'.$cascore.'</td>
                                                    <td class="center">'.$escore.'</td>
                                                    <td class="center">'.round($tscore, 0).'</td>
                                                    <td class="center">'.  passm($cascore, $tscore).'</td>                                              
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