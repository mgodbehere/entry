<?php
include('config.php');
include('../shared/functions.php');

/// This is used in expected.php to check in/out visitors by executing php script

if(isset($_POST['vid'])) // check if fnamme has input then we'll update the sql database
{
  var_dump($_POST);
  if($_POST['type'] == "1")
  {
    $vid = $_POST['vid'];
    $pid = $_POST['pid'];

    checkin_visitor($vid, $pid);
  }
  elseif($_POST['type'] == "0")
  {
    $vid = $_POST['vid'];
    $pid = $_POST['pid'];

    checkout_visitor($vid, $pid);
  }
}
if($_POST['type'] == "photo")
{
  echo("photo update");
  $pid = $_POST['pid'];
  $data = $_POST['photo_data'];

  visitor_update($pid, $data);
}

?>
