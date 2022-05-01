<!DOCTYPE html>
<html>

<head>

  <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
  <script>
  MathJax = {
    tex: {inlineMath: [['$', '$'], ['\\(', '\\)']]}
  };
  </script>
  <script id="MathJax-script" async src="../es5/tex-chtml.js"></script>

	<?php	$dir =  basename(__DIR__);
	$pgname = "Question Bank";
	require_once('../_inc/inc_head.php');	
    $ass_id = isset($_GET['ass_id'])? $_GET['ass_id'] :'';
    $tsid = isset($_GET['tsid'])? $_GET['tsid'] :'';
    function query_result($query, $dbc=''){
            
            if(empty($dbc)){
                global $dbc;        
            }

            $array_result = [];
            $run_query = mysqli_query($dbc, $query) or die(mysqli_error($dbc));

            for(; $row_result = mysqli_fetch_assoc($run_query); ){
                      $array_result[] = $row_result;
                    }

            //Next Grab Try to grab the required info and return 
            return $array_result;   
    }

    if (isset($_GET['del_que'])){
        $queid = $_GET['del_que'];
        $qdata = query_result("DELETE FROM questions WHERE id = '$queid'");

         $msg = 'QUESTON DELETED SUCCESSFULLY';
    }

    if (isset($_GET['del_test_que'])){
        $tsid = $_GET['del_test_que'];
        $qdata = query_result("DELETE FROM questions WHERE test_id = '$tsid'");

         $msg = "ALL <b>$tsid</b> QUESTON DELETED SUCCESSFULLY";
    }

    if(isset($_POST['submitque'])){

        $que  =  $_POST['que2'];
        $opta =  $_POST['opta'];
        $optb =  $_POST['optb'];
        $optc =  $_POST['optc'];
        $optd =  $_POST['optd'];
        
        $queid =  $_POST['queid'];
        $qdata = query_result("SELECT * FROM questions WHERE id = '$queid'")[0];

        if(isset($_POST['ans'])){
            $qans =  $_POST['ans'];
        }else{
            $qans =  $qdata['answer'];
        }
        mysqli_query($dbc, "UPDATE questions SET question = '$que', choice1 = '$opta', choice2 = '$optb', choice3 = '$optc', choice4= '$optd', answer = '$qans' WHERE id = '$queid'" );
       $msg = 'QUESTON UPDATED SUCCESSFULLY';
    }
?>	
	<link href="../_res/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />

	</head>

    <body>

        <div id="wrapper">

<?php	require_once('_inc/inc_topnav.php');	?>
<?php	require_once('_inc/inc_sidebar.php');	?>
  <!-- /.navbar-static-side -->

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header"> Questions </h3>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <?php if(isset($msg)){?>    
                            <div class="alert alert-default alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                                <a href="#" class="alert-link "><?php echo $msg?></a>
                            </div>                      
                    <?php }?> 

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Question Bank
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="form-group input-group col-lg-3">
                            <input type="text" class="form-control">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default"><i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                
                                <select class="form-control" name="ass_id" id="assfilter">
                                 <option>Select Assessment</option>
                                 <?php $assdata = query_result("SELECT * FROM assessments WHERE session_id = '$csession_id'", $dbc); ?>
                                 <?php foreach ($assdata as $ass) {
                                        $selected = ($ass_id == $ass['id'])? 'selected': '';
                                        $ass_name = $ass['assessments_name']. ' - '. $ass['id'];
                                         echo "<option $selected value='$ass[id]' > $ass_name </option>";
                                    } ?>
                                </select>
                            </div>
                        </div>

                    <?php if(isset($_GET['ass_id'])){
                        $ass_id = $_GET['ass_id'];
                        $tsdata = query_result("SELECT * FROM tests WHERE assessments_id = '$ass_id'", $dbc); 
                    ?>
                        <div class="col-lg-3">
                            <div class="form-group">
                                
                                <select class="form-control" name="tsid" id="tsfilter">
                                 <option>Select Test</option>
                                 <?php foreach ($tsdata as $ts) {
                                        $selected = ($tsid == $ts['id'])? 'selected': '';
                                        $selected_test = ($tsid == $ts['id'])? $ts['TName']: '';
                                         echo "<option $selected  value='$ts[id]'> $ts[TName] </option>";
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <a href="?ass_id=<?= $ass_id ?>&del_test_que=<?= $tsid; ?>" class="btn btn-danger"> Delete <?= $selected_test ?> Questions </a>
                        </div>
                        <?php } ?>

                        <div class="clearfix"></div>
                            <div class="table">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th width="70%">Question</th>
                                                <th>Exam</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 	
                                            $queryque = "SELECT answer, ts.id, ass.id, assessments_code, q.id, test_id, question, choice1, choice2, choice3, choice4 
                                                        FROM questions q, tests ts, assessments ass 

                                                        WHERE test_id = ts.id 
                                                        AND ts.assessments_id = ass.id AND ts.id = '$tsid'
                                                        LIMIT 500 "; 
                                            $dataque = mysqli_query($dbc, $queryque);	

                                            $n = 1;  while($rowque = mysqli_fetch_array($dataque)){
                                            $countque = mysqli_num_rows($dataque);

                                            $queid = $rowque['id'];																				
                                            $question = $rowque['question'];
                                            $choice1 = $rowque['choice1'];
                                            $choice2 = $rowque['choice2'];
                                            $choice3 = $rowque['choice3'];
                                            $choice4 = $rowque['choice4'];
                                            $assessments_code = $rowque['assessments_code'];
                                            $ans = $rowque['answer'];
                                            ?>
                                            <tr>
                                                <td><h5><?php echo $n; ?></h5></td>                                            
                                                <td><h5 class="que"><?php echo $question; ?></h5>
                                                    <input type="hidden" class="queid" value="<?php echo $queid; ?>">

                                                <?php 
                                                $ch1 = $ch2 = $ch3 = $ch4 = "";
                                                        $icon = '<i class="fa fa-arrow-right green"></i>';
                                                        if($ans == 1 ){ $ch1 = $icon; }
                                                        elseif($ans == 2 ){ $ch2 = $icon; }
                                                        elseif($ans == 3 ){ $ch3 = $icon; }
                                                        elseif($ans == 4 ){ $ch4 = $icon; }	

                                                   echo '<ol  type="A" style=" font-size: 12px" class="">
                                                                <li class="a">  '.$ch1.' '.$choice1.' </li>
                                                                <li class="b">  '.$ch2.' '.$choice2.'</li>
                                                                <li class="c">  '.$ch3.' '.$choice3.' </li>
                                                                <li class="d">  '.$ch4.' '.$choice4.'</li>																								
                                                        </ol>';
                                                    ?> 

                                                </td> </form>
                                                <td><?php echo $assessments_code; ?>   </td>
                                                <td class="center">
                                                <button class="btn btn-outline btn-success btn-xs editq" type="button" data-toggle="modal" data-target="#myModal">Edit</button>
                                                <a href="?ass_id=<?= $ass_id ?>&tsid=<?= $tsid; ?>&del_que=<?= $queid; ?>" class="btn btn-outline btn-danger btn-xs">Delete</a>
                                                </td>                                            
                                            </tr>
                                            <?php $n++; } ?>
                                        </tbody>
                                    </table>
                            </div>								 
                            <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
                        <!-- //  Add Students --> 
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-question-circle"></i>  Edit Questions </h4>
                                    </div>
                                    <form role="form" method="post" action="" name="add_user">
                                        <div class="modal-body">
     
                                            <div class="form-group">
                                            <label>Question:</label>
                                            <textarea class="form-control" name="que2" rows="3"></textarea>
                                            <p class="help-block">Show <b>Editor</b> (html is allowed in all textarea boxes)</p>
                                        </div>
                                            <div class="form-group input-group">
                                                <input name="ans" id="ans" value="1" type="radio">
                                                <span class="input-group-addon">A:</span>
                                                <input type="text" name="opta"class="form-control" required="">
                                            </div> 
                                            <div class="form-group input-group">
                                                <input name="ans" id="ans" value="2" type="radio">
                                                <span class="input-group-addon">B:</span>
                                                <input type="text" name="optb" class="form-control" required="">
                                            </div> 
                                            <div class="form-group input-group">
                                                <input name="ans" id="ans" value="3" type="radio">
                                                <span class="input-group-addon">C:</span>
                                                <input type="text" name="optc" class="form-control" required="">
                                            </div> 
                                            <div class="form-group input-group">
                                                <input name="ans" id="ans" value="4" type="radio">
                                                <span class="input-group-addon">D:</span>
                                                <input type="text" name="optd" class="form-control" required="">
                                                <input type="hidden" name="queid" class="form-control" required="">
                                            </div>
                                        </div> 
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" name="submitque" class="btn btn-primary">Edit Questions</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> 
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
            <?php	require_once('footer_bar.php');	?>
            <!-- /#page-wrapper -->
        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js.php');	?>
        <script>
             $("#assfilter").change(function(){
                var ass_id = $("#assfilter").val();
                window.location.href = 'question_bank.php?ass_id='+ass_id;

            })

             $("#tsfilter").change(function(){
                var tsid = $("#tsfilter").val();
                var ass_id = '<?= $ass_id; ?>';
                window.location.href = 'question_bank.php?ass_id='+ass_id+'&tsid='+tsid;

            })

            $(".editq").click(function(){
                var row = $(this).closest('tr');
                var b = row.find(".b").text();
                var c = row.find(".c").text();
                var d = row.find(".d").text();
                var q = row.find(".que").text();
                var qid = row.find(".queid").val();
                //alert(q);
                
                $("input[name='opta']").val(row.find(".a").text());
                $("input[name='optb']").val(b);
                $("input[name='optc']").val(c);
                $("input[name='optd']").val(d);
                $("textarea[name='que2']").val(q);
                $("input[name='queid']").val(qid);
            })
        </script>
    </body>

</html>
