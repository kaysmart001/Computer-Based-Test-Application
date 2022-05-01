<!DOCTYPE html>
<html>

    <head>
<?php	$dir =  basename(__DIR__);
$pgname = "Add Exam";
require_once('../_inc/inc_head.php');	?>	</head>
<style type="text/css">
 .progress-bar.animate {
   width: 98%;
}   
</style>
<style type="text/css">
    .icon-refresh-animate {
    animation-name: rotateThis;
    animation-duration: .9s;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
}

@keyframes rotateThis {
    from { transform: scale( 1 ) rotate( 0deg );   }
    to   { transform: scale( 1 ) rotate( 360deg ); }
}
</style>
    <body>

        <div id="wrapper">

  <!-- /.navbar-static-side -->

            <button id="load">Load It!</button>
                <div class="modal js-loading-bar">
                 <div class="modal-dialog">
                   <div class="modal-content">
                     <div class="modal-body">
                       <div class="progress progress-popup">
                        <div class="progress-bar"></div>
                       </div>
                     </div>
                   </div>
                 </div>
                </div>


                <a id="update" href="#"><i class="fa fa-refresh icon-refresh-animate fa-3x"></i></a>
            <?php	require_once('footer_bar.php');	?>

            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
		<?php	require_once('../_inc/inc_js.php');	?>
<script type="text/javascript">
    // Setup
this.$('.js-loading-bar').modal({
  backdrop: 'static',
  show: false
});

$('#load').click(function() {
  var $modal = $('.js-loading-bar'),
      $bar = $modal.find('.progress-bar');
  
  $modal.modal('show');
  $bar.addClass('animate');

  setTimeout(function() {
    $bar.removeClass('animate');
    $modal.modal('hide');
  }, 1000);
});
</script>
<script type="text/javascript">
    $( document ).ready( function() {
    $( "#update" ).on( "click", function( e ) {
        var $icon = $( this ).find( ".icon-refresh" ),
            animateClass = "icon-refresh-animate";

        $icon.addClass( animateClass );
        // setTimeout is to indicate some async operation
        window.setTimeout( function() {
            $icon.removeClass( animateClass );
        }, 2000 );
    });    
});
</script>
    </body>

</html>
