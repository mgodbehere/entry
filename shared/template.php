<?php include("login/active.php") ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Booking Dot Pants - PreBook Visitor</title>
  <!-- Bootstrap style sheet  -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <!-- Our style sheet  -->
  <link rel="stylesheet" href="css/main.css">
  <!-- Jquery java file  -->
  <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

  <!-- Select2 style sheet & java file  -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
  $(document).ready(function(){
    /// Bootstrap configs
    $(function () {
        $('[data-toggle="tooltip"]').tooltip({placement: "right"})
    });

   /// End document ready
  });
  </script>
</head>
<body>
<div class="box">

  <?php include("shared/menu.php") ?>

  <div class="content">

  </div>

</div>
<!-- Bootstrap java file  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>
</html>
