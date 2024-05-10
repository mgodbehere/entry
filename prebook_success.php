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

  <?php
  include("ajax/config.php");

  function id_gen($prefix, $db_con)
  {
    // generate a unique id and then insert it and the name to the relevent table
    $uid = "";
    while(True)
      {
        foreach(range(1,5) as $num)
        {
          $uid = $uid.mt_rand(0,9);
        }

        if(strlen($uid) >= 29)
        {
          // check generated uid doesn't exisit in table
          $checkID = mysqli_fetch_assoc(mysqli_query($db_con, "SELECT vid FROM visitation WHERE vid = '".$prefix."-".$uid."'"));
          //echo("if ".$uid);
          if(!isset($checkID))
          {
              //var_dump($checkID);
              //echo("id doesn't exisit");
              break;
          }
          // if record exists then we clear all the random numbers and start again
          elseif(isset($checkID))
          {
            $uid = "";
          }
        }
        $uid = $uid."-";
      }
    return $prefix."-".$uid;
  }



  $visitors = json_decode($_POST['visitors'], true); // get array string from form and turn it back into an array
  $hosts = json_decode($_POST['hosts'], true); // get array string from form and turn it back into an array

  $list_visitors = join(", ", array_keys($visitors)); // returns a string of all visitor ids to insert into the db
  $list_hosts = join(", ", array_keys($hosts)); // returns a string of all host ids to insert into the db

  $vid = id_gen("vid", $con); // unique id for the visitation

  $date_start = $_POST['sdate'];
  $date_end = $_POST['edate'];
  $time_start = $_POST['stime'];
  $time_end = $_POST['etime'];

  $visitname = !empty($_POST['visitname']) ? $_POST['visitname'] : $_SESSION['fname']." ".$_SESSION['lname']." Visitation Created ".date("d/m/Y");
  $location = $_POST['location'];

  $columns = "vid, name, list_visitors, list_hosts, date_start, date_end, time_start, time_end, location, valid";
  $vals = "'".$vid."', '".$visitname."', '".$list_visitors."', '".$list_hosts."', '".$date_start."', '".$date_end."', '".$time_start."', '".$time_end."', '".$location."', '1'";

  mysqli_query($con, "INSERT INTO visitation ($columns) VALUES ($vals)");
  ?>


</head>
<body>
<div class="box">

  <?php include("shared/menu.php") ?>

  <div class="content">
      The following people are prebooked for a visit -
      <table>
        <tr><th>PID</th><th>First Name</th><th>Last Name</th><th>Company Name</th></tr>
      <?php
      foreach($visitors as $key => $record) // loop through the top level array holds all pids
      {
        echo("<tr>");
        echo("<td>".$key."</td>");
        foreach($record as $value) // gets the first name, last name and company and prints them
        {
          echo("<td>".$value."</td>");
        }
        echo("</tr>");
      }

      ?>
    </table>
    </p>
    <p>The visit Hosts are -
      <table>
        <tr><th>PID</th><th>First Name</th><th>Last Name</th></tr>
      <?php
      foreach($hosts as $key => $record) // loop through the top level array holds all pids
      {
        echo("<tr>");
        echo("<td>".$key."</td>");
        foreach($record as $value) // gets the first name, last name and company and prints them
        {
          echo("<td>".$value."</td>");
        }
        echo("</tr>");
      }

      ?>
    </table>
    </p>
  </div>

</div>
<!-- Bootstrap java file  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>
</html>
