<?php
include 'config.php';

//$a = shell_exec("..\shared\qrgen.py 1 2");
//echo "<pre>$a</pre>";

// if used for error checking result set to null if Something goes wrong

if(isset($_POST['pid']))
{
  $fetchVisitor = mysqli_query($con, "select * from people where pid='".$_POST['pid']."'"); // get all data that matches
  $dataVisitor = mysqli_fetch_assoc($fetchVisitor); // gets row data stores as an array

  $fetchCompany = mysqli_query($con, "select name from company where cid='".$dataVisitor['cid']."'");
  $dataCompany = mysqli_fetch_assoc($fetchCompany);

  //$fetchCompany = mysqli_query($con, "select name from visitation where cid='".$dataVisitor['cid']."'");
  //$dataCompany = mysqli_fetch_assoc($fetchCompany);


  if(isset($_POST['vid']))
  {
    // generate qr code for each visitor with the visitation id embedded USED IN EXPECTED PAGE
    $qr_id = $dataVisitor['pid'];
    $qr_vid = $_POST['vid'];
    $qr = shell_exec("..\shared\output\qrgen.exe $qr_id $qr_vid");
  }
  else
  {
    $qr = ""; // blank cause this code is used in prebook NOTE need to strat looking at optimising code getting too messy
  }

  //$fetchVisitor = mysqli_query($con, "select * from people where pid='".$_POST['pid']."'"); // get all data
  //$result = "<td><img src='".$dataVisitor['photo']."'alt='' width='150'></td>"."<td>".$dataVisitor['firstname']."</td><td>".$dataVisitor['lastname']."</td><td>".$dataCompany['name']."</td>";
  $dataCombo[] = array("photo" => $dataVisitor['photo'], "qr" => $qr, "fname" => $dataVisitor['firstname'], "lname" => $dataVisitor['lastname'], "cname" => $dataCompany['name'], "cid" => $dataVisitor['cid'], "visitorid" => $dataVisitor['pid']);
  $result = json_encode($dataCombo);
}
else
{
  $result = "";
}

//$fetchVisitor = mysqli_query($con, "select * from people where pid='uid1'"); // get all data that matches
//$dataVisitor = mysqli_fetch_assoc($fetchVisitor); // gets row data stores as an array

//echo $dataVisitor['photo'];
//echo "<img src='".$dataVisitor['photo']."'alt=''>";

//var_dump($dataCompany);
//echo("First Name --  ".$dataVisitor['firstname']);
//echo("<br />Last Name --  ".$dataVisitor['lastname']);
//echo("<br />Company Name --  ".$dataCompany['name']);
echo $result;

?>
