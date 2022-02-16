<div class="well card-footer">
  <p>
      <span class="float-right"></span>
  </p>
</div>




  </body>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/jquery.dataTables.min.js"></script>
  <script src="assets/js/dataTables.bootstrap4.min.js"></script>
  <script>
      $(document).ready(function () {
          $("#flash-msg").delay(7000).fadeOut("slow");
      });
      $(document).ready(function() {
          $('#example').DataTable( {
            responsive: true
          }

          );
      } );
  </script>
</html>
