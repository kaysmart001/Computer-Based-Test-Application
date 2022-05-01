
            <nav class=" navbar navbar-default" role="navigation">
                <!-- /.navbar-header -->

                <div class="navbar-header2 navbar-header">
                    <div class="titleprofilebar" style=""> 
                        <div class="user-info-profile-image2 passport">
                            <img id="passport" src="../_files/_passport/<?php echo $userid; ?>.jpg" 
                                 alt="" width="140" height="140"  onerror="this.src = '../_files/_passport/default.jpg';" />
                        </div>

                        <div class="user-info">
                            <div class="username"><strong> <?php echo $userid; ?> </strong>
                                <br> <?php echo $LName . ' ' . $FName . ' ' . $MName; ?>  </div>
                                <!--- <div class="username"> <strong>Computer Science</strong> 200L</div> -->
                            <div class="username"> <strong><?php echo $csession; ?></strong></div>
                        </div>   
                    </div>                
                </div> 

                <!-- /.navbar-top-links -->

                <ul class="nav navbar-top-links navbar-right">
                   <!-- <li>
                        <a href="" title="Time" style="color:white; width:200px">
                            <h1>                
                                <span id="time" data-value="393">05:58</span>
                            </h1>
                        </a>
                    </li> -->
                    <li>
					
					<a href="logout.php" title="Logout" style="color: red">
                     <img src="../_res/img/logout.png" width="60" height="60"   />
                    </a>
				
                </ul>
            </nav>

