<a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
  <div id="preloader"></div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title cart-item-icon" id="exampleModalLabel"><i class="fas fa-shopping-cart"></i></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php  ?>
      </div>
      <div class="modal-footer">
        <a href="cart" class="btn btn-success">
            <span class="icon text-white-50">
            <i class="fas fa-arrow-right"></i>
            </span>
            <span class="text">Go to Cart</span>
        </a>        
        <button type="button" class="btn btn-danger" data-dismiss="modal">
            <span class="icon text-white-50">
            <i class="fas fa-times"></i>
            </span>
            <span class="text">Close</span>
        </button>
    </div>
    </div>
  </div>
</div>
<!-- Profile Modal -->
<div class="modal fade" id="exampleProfile" tabindex="-1" role="dialog" aria-labelledby="modalProfile" aria-hidden="true">
  <div class="modal-dialog modal-dialog-orders modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalProfile">Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-responsive" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th</th>
                <th></th>
            </tr>
          </thead>
          <tbody>
            <?php ?>         
          </tbody>
        </table>            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- View Orders Modal -->
<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="modalOrders" aria-hidden="true">
  <div class="modal-dialog modal-dialog-orders modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalOrders">My Orders</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-responsive" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
                <th>Order #</th>
                <th>Item Title</th>
                <th>Amount</th>
                <th>Order Placed</th>
                <th>Payment Mode</th>
                <th>Order Status</th>
            </tr>
          </thead>
          <tbody>
            <?php  ?>         
          </tbody>
        </table>            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Order History Modal -->
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
                <th>Order #</th>
                <th>Item Title</th>
                <th>Amount</th>
                <th>Order Placed</th>
                <th>Payment Mode</th>
                <th>Order Delivered</th>
            </tr>
          </thead>
          <tbody>
            <?php  ?>         
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