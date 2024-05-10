<?php
include 'config.php';

if(isset($_POST['fname'])) // check if fnamme has input then we'll update the sql database
{
  $pid = $_POST['pid'];

  /// below code forked from management_edit need to pack this up into a generic function only col names changed
  $col = ["pid", "firstname", "lastname", "cid"];
  $i = 0; // used to get item from array
  foreach($_POST as $item)
  {
      $col[$i] = $col[$i]."='".$item."'";
      $i++;
  }
  $s = join(",", $col);
  mysqli_query($con, "UPDATE people SET $s WHERE pid='$pid'");

  //echo("success"); // used for feedback?
  echo("php - <br/>");
  //echo($_POST['photo']);
  echo($s);
}

?>
