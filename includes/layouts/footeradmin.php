<?
 mysql_close ();

 ?>

        </div><!-- end inner wrap, must go after ALL content -->
    </div><!-- end offcanvas -->


    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/foundation-datepicker.js"></script>
    <script src="js/angular.min.js"></script>
    <script src="js/app.js"></script>
      <script>
            $(function () {
                $('#dp1').fdatepicker({
                    format: 'mm/dd/yyyy',
                    disableDblClickSelection: true
                });

            });
        </script>
     <script>
      $(document).foundation();
    </script>
</body>
</html>
