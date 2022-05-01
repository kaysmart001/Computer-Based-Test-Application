<!DOCTYPE html>
<html>

    <head>
<?php	$dir =  basename(__DIR__);
$pgname = "Test Page";
require_once('../_inc/inc_head.php');	
//test info
if(isset($_GET['usid'])){
	$uid = $_GET['usid']; 
	$tsid = $_GET['tsid'];         
}
 ?>
</head>

    <body>

        <div id="wrapper">

            <nav class=" navbar navbar-default navbar-fixed-top" role="navigation">
                <!-- /.navbar-header -->
                        
                     <div class="navbar-header2 navbar-header">
                        <div class="titleprofilebar" style=""> 
                            <div class="user-info-profile-image2 passport">
                            <img id="passport" src="../_res/img/passport/<?php echo $userid; ?>.jpg" 
                                 alt="" width="140" height="140"  onerror="this.src = '../_res/img/passport/default.jpg';" />
                            </div>

                            <div class="user-info">
                                <div class="username"><strong> <?php echo $userid; ?> </strong>
                                <br> <?php echo $LName.' '.$FName.' '.$MName; ?>  </div>
                                <!--- <div class="username"> <strong>Computer Science</strong> 200L</div> --->
                                <div class="username"> <strong><?php //echo $asscode; ?></strong> - <?php //echo $assname; ?></div>
                            </div>   

                        </div>                
                    </div> 

                <!-- /.navbar-top-links -->
		<ul class="nav navbar-top-links navbar-right">
                    <li><a href="logout.php" title="Logout"><i class="fa fa-power-off fa-2x fa-fw"></i></a>
                </ul>
            </nav>

                    <div id="page-wrapper2" style="margin-top: 100px;">
                        <!-- /.row -->
                        <div class="row">
                            <div class="col-lg-10 col-md-offset-1 mt10">	

                                <?php 	 $qn = 1;
                                        //$utid = $rowts['id'];

                                        $queryts = "SELECT * FROM user_test WHERE test_id = '$tsid' AND user_id = '$uid'  "; 
                                        $datats = mysqli_query($dbc, $queryts);
                                        $rowut = mysqli_fetch_array($datats);
                                        $user_test_id = $rowut['id'];

                                        $show_answers = nameId('id', 'show_answers', 'tests', $tsid);

                                        $queryque = "SELECT user_choice.id as ucid, user_test_id, q_id, user_choice.answer, questions.id, test_id, question, choice1, choice2, choice3, choice4   
                                                    FROM user_choice INNER JOIN questions ON q_id = questions.id
                                                    AND user_test_id = '$user_test_id'";
                                        $dataque = mysqli_query($dbc, $queryque);																																			

                                       ?>

                                                <section id="testwrap">
                                                <div class="panel panel-default">

                                                        <!-- /.panel-heading -->
                                                        <div class="panel-body pbtm0">						
                                                            <div class="clear" style="clear:both">
                                                           <!-- /.table-responsive -->

                                                                <div role="tabpanel">

                                                                  <!-- Tab panes -->
                                                                  <div class="tab-content">


                                                                    <?php 	if($show_answers == 1){ ?>
                                                                        <div class="table">
                                                                            <table class="table table-striped">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>No</th>
                                                                                        <th>Question</th>
                                                                                        <th>Status</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                     <?php //$ucids[] = '';
                                                                                        $n = 1;  while($rowque = mysqli_fetch_array($dataque)){
                                                                                        $countque = mysqli_num_rows($dataque);

                                                                                        $queid = $rowque['id'];

                                                                                        $ucid = $rowque['ucid'];
                                                                                        $ucids[] = $rowque['ucid'];
                                                                                        $question = $rowque['question'];
                                                                                        $choice1 = $rowque['choice1'];
                                                                                        $choice2 = $rowque['choice2'];
                                                                                        $choice3 = $rowque['choice3'];
                                                                                        $choice4 = $rowque['choice4'];
                                                                                        $ans = $rowque['answer'];
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td><h4><?php echo $n; ?></h4></td>                                            
                                                                                            <td><h4><?php echo $question; ?></h4>                                            

                                                                                            <?php 
                                                                                            $ch1 = $ch2 = $ch3 = $ch4 = "";
                                                                                                    $icon = '<i class="fa fa-arrow-right green"></i>';
                                                                                                    if($ans == 1 ){ $ch1 = $icon; }
                                                                                                    elseif($ans == 2 ){ $ch2 = $icon; }
                                                                                                    elseif($ans == 3 ){ $ch3 = $icon; }
                                                                                                    elseif($ans == 4 ){ $ch4 = $icon; }	

                                                                                            echo '<ol  type="A" style=" font-size: 14px">
                                                                                                        <li>  '.$ch1.' '.$choice1.' </li>
                                                                                                        <li>  '.$ch2.' '.$choice2.'</li>
                                                                                                        <li>  '.$ch3.' '.$choice3.' </li>
                                                                                                        <li>  '.$ch4.' '.$choice4.'</li>																								
                                                                                                    </ol>';
                                                                                            ?> 

                                                                                            </td> </form>
                                                                                            <td class="center">
                                                                                            <?php if(!empty($ans)){
                                                                                             $check = check_correct_ans($queid, $ans);
                                                                                             if($check == 1){ echo ' <i class="fa  fa-check green fa-3x"></i>';}
                                                                                             else{  echo '<i class="fa  fa-times red fa-3x"></i>';}
                                                                                            }else{
                                                                                             echo '<i class="fa  fa-times fa-3x"></i>';	
                                                                                            }
                                                                                            ?>	
                                                                                             </td>                                            

                                                                                        </tr>
                                                                                <?php $n++; } ?>

                                                                                </tbody>
                                                                            </table>
                                                                        </div>								 

                                                                        <?php  }else{ ?>
                                                                        <div class="well">
                                                                                <h2 class="text-center text-danger"> <i class="fa fa-times fa-fw"> </i> No Permission to View Test Answers  <span id="duration"> </span></h2>
                                                                        </div>
                                                                        <?php  } ?>
                                                                  </div>

                                                                </div>
                                                            </div>
                                                        </div><!-- /.panel-body -->											                
                                                </div><!-- /.panel -->
                                           </section>
                                        </div>
                                <?php $qn++;  ?>
                        </div>

                        <div class="panel-footer text-center" style="margin-top: 0px; margin-left: 50px;">
                            <a href="result.php?ps=tresult&&ass=<?php echo $assid; ?>"><button type="button" class="btn btn-info" > View Result</button></a>
                            <a href="index.php"><button type="button" class="btn btn-primary">Back to Homepage</button></a>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

	<!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js.php');	?>
		
  </body>

</html>