<?php 
  include("includes/models/dylan.php")
?>
<a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
  <div id="preloader"></div>


<!-- View Orders Modal -->
<div class="modal fade" id="exampleModalOrderHistory" tabindex="-1" role="dialog" aria-labelledby="modalOrders" aria-hidden="true">
  <div class="modal-dialog modal-dialog-orders modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalOrders">Order History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-responsive" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
                <th>Total</th>
                <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php  
                if(isset($_SESSION['USER']->user_id)){
                  $result = Order::getCompleteOrdersByUserID($_SESSION['USER']->user_id);
                  if($result != false){
                    foreach($result as $order){
                      ?>
                        <tr>
                          <td>
                            <?= $order->order_price?>
                          </td>
                          <td>
                            <?= str_replace("00:00:00", "",$order->order_datetime)  ?>
                          </td>
                        </tr>
                      <?php
                    }
                  }
                  
                }
            ?>         
          </tbody>
        </table>            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- JavaScript Libraries -->
<script src="lib/jquery/jquery.min.js"></script>
<script src="lib/jquery/jquery-migrate.min.js"></script>
<script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
</script>
<script type="text/javascript">
$('#cart-tooltip').popover('show')

setTimeout("$('#cart-tooltip').popover('hide')", 5000);
</script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/superfish/hoverIntent.js"></script>
<script src="lib/superfish/superfish.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/counterup/counterup.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="lib/isotope/isotope.pkgd.min.js"></script>
<script src="lib/lightbox/js/lightbox.js"></script>
<script src="lib/touchSwipe/jquery.touchSwipe.min.js"></script>

<!-- Template Main Javascript File -->
<script src="js/main.js"></script>
<script src="lib/login/js/main.js"></script>
<?php
$filename = substr($_SERVER['PHP_SELF'], strlen($_SERVER['PHP_SELF']) - 8, strlen($_SERVER['PHP_SELF'])); 
if($filename === "item.php") {
    if(isset($_SESSION['CART']) && sizeof($_SESSION['CART']) >= 1) {
        echo "<script type='text/javascript'>$('#exampleModal').modal('show'); </script>";
    }        
}
?>
</body>
</html>