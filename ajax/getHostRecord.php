<?php
include 'config.php';

// if used for error checking result set to null if Something goes wrong
if(isset($_POST['pid']))
{
  $fetchVisitor = mysqli_query($con, "select * from users where uid='".$_POST['pid']."'"); // get all data that matches
  $dataVisitor = mysqli_fetch_assoc($fetchVisitor); // gets row data stores as an array

  $dataCombo[] = array("photo" => $dataVisitor['photo'], "fname" => $dataVisitor['firstname'], "lname" => $dataVisitor['lastname'], "visitorid" => $dataVisitor['uid']);
  $result = json_encode($dataCombo);
}
else
{
  $result = "";
}

echo $result;

?>
