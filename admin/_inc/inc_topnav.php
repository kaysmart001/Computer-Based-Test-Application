            <nav class=" navbar navbar-default navbar-static-top" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    
                    <a class="navbar-brand" href="index.php">
                    <img class="brand-logo" src="../_res/img/logo.png" alt="" />
                    </a>
                </div>
                <span class=" navbar-left">
                    <!-- <img class="logo2" src="../_res/img/logo2.png" alt="" /> -->
                </span>
                <!-- /.navbar-header -->

                <ul class="nav navbar-top-links navbar-right" style="font-size: 29px; text-align: center">
                    <li>
                        <a href="confirm_session.php" title="Change Session" style=" width:180px;">
                            <span>				
                                <span><?php echo $csession; ?></span>
                            </span>
                        </a>
                    </li>
                    <!-- /.dropdown -->

<!--                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user  fa-2x fa-fw"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#"><i class="fa fa-user fa-fw"></i> Admin Profile</a>
                            </li>

                            <li class="divider"></li>
                            <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                            </li>
                        </ul>
                         /.dropdown-user 
                    </li>
-->
                    <!-- /.dropdown -->
                            <li><a href="settings.php"><i class="fa fa-gear fa-fw"></i></a>
                            
                            <li><a href="logout.php" title="Logout"><i class="fa fa-power-off fa-fw"></i></a>

                </ul>
                <!-- /.navbar-top-links -->

            </nav>
            <!-- /.navbar-static-top -->
