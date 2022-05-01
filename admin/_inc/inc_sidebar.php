            <nav class="nav2 navbar-default navbar-static-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav" id="side-menu">
                        <!--<li>
                            <div class="user-info-wrapper">	
                                <div class="user-info-profile-image">
                                    <img src="../img/profile-1.jpg" alt="" width="65" height="65" />
                                </div>
                                <div class="user-info">
                                    <div class="user-welcome">Welcome</div>
                                    <div class="username">Awonuga <strong>Sheriff</strong></div>
                                </div>
                            </div>
                        </li>-->
  
                        <li class=""><a href="index.php" <?php if ($page == "index.php")  echo 'class="active"' ?> ><i class="fa fa-dashboard fa-fw fa-3x"></i> Dashboard</a></li>                       
                        <li><a href="view_exam.php" <?php if ($page == "add_exam.php" || $page == "view_exam.php" || $page == "add_test.php")  echo 'class="active"' ?>><i class="fa fa-edit fa-fw fa-3x"></i> Assessments</a></li>						
<!--                        <li><a href="active_tests.php" <?php if ( $page == "active_tests.php")  echo 'class="active"' ?>><i class="fa fa-bullseye fa-fw fa-3x"></i> Active Tests </a></li>-->
                        <li><a href="students.php" <?php if ($page == "students.php")  echo 'class="active"' ?>><i class="fa fa-users fa-fw fa-3x"></i> Students</a></li>
			<li><a href="question_bank.php" <?php if ($page == "question_bank.php")  echo 'class="active"' ?>><i class="fa fa-table fa-fw fa-3x"></i> Question Bank  </a></li>
                        <li><a href="exam_stats.php" <?php if ( $page == "exam_test_stats.php" || $page == "test_history.php" || $page == "exam_stats.php" || $page == "question_analysis.php" || $page == "score_sheet.php" || $page == "students_answers.php" || $page == "exam_test_chart.php" )  echo 'class="active"' ?>><i class="fa fa-bar-chart-o fa-fw fa-3x"></i> Statistics</span></a></li>
                        <li><a href="exam_reports.php" <?php if ( $page == "exam_reports.php" || $page == "exam_test_reports.php")  echo 'class="active"' ?>><i class="fa fa-copy fa-fw fa-3x"></i> Reports </a></li>
                        <li><a href="view_test_images.php" <?php if ( $page == "view_test_images.php" || $page == "view_test_images.php")  echo 'class="active"' ?>><i class="fa fa-camera fa-fw fa-3x"></i> Image Capture </a></li>
                    </ul>
                    <!-- /#side-menu -->
                </div>
                <!-- /.sidebar-collapse -->
            </nav>
          