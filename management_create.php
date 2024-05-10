<?php
include("login/active.php");
include("shared/functions.php")
 ?>
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet" />
  <?php
    // Setting up page variables
    $cname = NULL;
    $uid = NULL;

    // Generate form dependant on url defaults to visitor form if problem with url
    if(isset($_GET['type']))
    {
      switch($_GET['type'])
      {
        case "company":
          $form = '<label for="cname">Company Name</label><input type="text" name="cname" required/><input type="hidden" name="type" value="company"/>';
          break;
        case "host":
          $form = '<label for="uname">Username</label><input type="text" name="uname"/><label for="fname">First Name</label><input type="text" name="fname" required/><label for="lname">Last Name</label><input type="text" name="lname" required/><label for="pass">Password</label><input type="password" name="pass" required/><input type="hidden" name="type" value="host"/>';
          break;
        default:
          $form = '<label for="fname">First Name</label><input type="text" name="fname" required/><label for="lname">Last Name</label><input type="text" name="lname" required/><select name="cname" id="selCompany" style="width: 100%; z-index:9000;"></select><input type="hidden" name="type" value="visitor"/>';
          break;
      }
    }

    /// checks if a post request has been made from the form on the page then processes it
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
      include("shared/config.php");

      $cname = isset($_POST['cname'])? $_POST['cname'] : "";
      $fname = isset($_POST['fname'])? $_POST['fname'] : "";
      $lname = isset($_POST['lname'])? $_POST['lname'] : "";
      $uname = isset($_POST['uname'])? $_POST['uname'] : "";
      $pass = isset($_POST['pass'])? password_hash($_POST['pass'], PASSWORD_DEFAULT) : "";

      $type = $_POST['type']; // used to determine unique id prefix and query table & columns

      switch($_POST['type'])
      {
        case "company":
          $table = id_map($_POST['type'])['table'];
          $columns = "cid, name";
          $val = "'".id_gen("company")."', '".$cname."'";
          break;
        case "host":
          $photo = base64_encode(file_get_contents("./images/pro.png"));
          $table = id_map($_POST['type'])['table'];
          $columns = "uid, firstname, lastname, photo, password, username";
          $val = "'".id_gen("host")."', '".$fname."', '".$lname."', '".$photo."', '".$pass."', '".$uname."'";
          break;
        default:
		  $photo = base64_encode(file_get_contents("./images/pro.png"));
          $table = id_map($_POST['type'])['table'];
          $columns = "pid, firstname, lastname, photo, cid";
          $val = "'".id_gen("visitor")."', '".$fname."', '".$lname."', '".$photo."', '".$cname."'";
          break;

      }

      mysqli_query($con, "INSERT INTO $table ($columns) VALUES ($val)");
    }
  ?>
  <script>
  /// Start document ready
  $(document).ready(function(){
    /// Bootstrap configs
    $(function () {
        $('[data-toggle="tooltip"]').tooltip({placement: "right"})
    });

    $("#selCompany").select2({
     ajax: {
      url: "ajax/getCompanyRecord.php",
      type: "post",
      dataType: 'json',
      delay: 250,
      data: function (params) {
       return {
         searchTerm: params.term // search term
       };
      },
      processResults: function (response) {
        return {
           results: response
        };
      },
      cache: true
    },
     theme: "bootstrap",
     placeholder: "-- Search Comapny --",
     language: {
       noResults: function(){
         return $("<a href='http://google.com/'>Visitor Not Found Create New...</a>");
       }
     }
    });

   /// End document ready
  });
  </script>
</head>
<body>
<div class="box">

  <?php include("shared/menu.php") ?>

  <div class="content">

    <h1>Create</h1>

    <?php
    echo($cname."<br/>".$uid."<br/>")
    ?>
    <p>This page needs to take the type from url then display form for creating that type of record then validate field then create record in db after submition</p>
    <p>Start basic with a form for creating new company, no new fields just name and an unique id</p>
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']."?type=".$_GET['type']); ?>" method="post" class="needs-validation">
      <?php echo($form); ?>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>

</div>
<!-- Bootstrap java file  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>
</html>
