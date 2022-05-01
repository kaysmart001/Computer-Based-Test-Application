
<!DOCTYPE html>
<html>

    <head>
	<?php	$dir =  basename(__DIR__); 
	$pgname = "Images Captures ";
	require_once('../_inc/inc_head.php');?>	
	</head>
	<?php
    $sql = "SELECT * FROM test_images ORDER BY id DESC LIMIT 24 ";
    $sql_result = mysqli_query($dbc, $sql);
    //print_r($row);?>

    <body>

        <div id="wrapper">

        <?php	require_once('_inc/inc_topnav.php');	?>
        <?php	require_once('_inc/inc_sidebar.php');	?>
        <!-- /.navbar-static-side -->

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header"> Images Captures  </h3>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>


                <div class="panel panel-default">
                            <div class="panel-heading">
                                View Test Images
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body" id="testimages">
                                <?php while ($row = mysqli_fetch_array($sql_result)) { ?>
                                    <div class="col-sm-2">
                                        <div class="well" style="padding: 10px">
                                            <img id="passport" src="../students/<?php echo $row['image']; ?>" style="margin: -5px -5px 0px -5px ; " width="123"> 
                                            <h5> <?php echo $row['userid']; ?></h5>                                            <button class="btn btn-danger btn-xs resetimg" data-value="<?php echo $row['userid']; ?>" data-testid="<?php echo $row['testid']; ?>">
                                                <i class="fa fa-refresh"></i>  Reset
                                            </button>
                                        </div>
                                    </div>
                                <?php } ?>               
                            </div>
                            <!-- /.panel-body -->
                        </div>
            </div>
            		<?php	require_once('footer_bar.php');	?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js.php');	?>
<script type="text/javascript">

function reloadimages(){
                $.ajax({
                    type: "POST",
                    url: "../students/_inc/process.php",
                    data: {reloadimages: 'reloadimages' },
                    success: function(data) {
                        $('#testimages').html(data)
                    },
                    error: function(exception) {
                        //alert('error connecting to server');
                    }
                });
            };

setInterval(function () { reloadimages(); }, 10000); 

// function resetimg(){
//      $.ajax({
//             type: "POST", url: "../students/_inc/process.php", data: {resetimg: 'yes', userid: },
//             success: function (data) {
//                alert(data);
//             }
//         });         
//  }

$(document).on("click", ".resetimg", function() {
            var userid = $(this).data('value');
            var testid = $(this).data('testid');
            
            $.ajax({
                type: "POST",
                url: "../students/_inc/process.php",
                data: {resetimg_usid: userid, testid: testid},
                success: function (data) {
                   // td.html(data)
                   alert(' Image Reset Seccessful');
                    
                },
                error: function (exception) {
                    alert('error connecting to server');
                }
            });
        });
</script>
    </body>

</html>
