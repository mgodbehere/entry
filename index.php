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
  <?php
  session_start();
	if(isset($_SESSION['error']))
	{
		$msg = '<div class="alert alert-danger m-5" id="alert" role="alert">
					Username or Password incorrect
				</div>';
		session_destroy();
	}
	else
	{
		$msg = "";
	}
  ?>
</head>
<body>
<div class="box">

  <?php include("shared/menu.php") ?>

  <div class="content">
	<?php echo($msg); ?>
	<h1 class="display-3">Login</h1>
	<div>
		<form action="login/authenticate.php" method="post" style="width:60%;margin:auto;text-align:left" class="needs-validation">
			<div class="mb-3 position-relative">
				<label for="userName" class="form-label">User Name</label>
				<input type="text" name="userName" class="form-control" id="validationTooltip01" aria-describedby="usernamehelp" required/> <!-- We generate ourselves? Using jquery or bootstrap take first and last name and . them together? -->
				<div id="usernamehelp" class="form-text">Enter your username</div>
			</div>
			<div class="mb-3 position-relative">
				<label for="password" class="form-label">Password</label>
				<input type="password" name="password" class="form-control" aria-describedby="passwordhelp" required/>
				<div id="passwordhelp" class="form-text">Enter your password</div>
				<div class="valid-tooltip">
					Looks good!
				</div>
			</div>
			<button type="submit" class="btn btn-dark" name="button" value="login">Submit</button>
		</form>
	</div>

</div>
<!-- Bootstrap java file  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>
</html>
