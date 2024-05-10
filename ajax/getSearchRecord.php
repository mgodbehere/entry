<?php
include 'config.php';

// if used for error checking result set to null if Something goes wrong
if(isset($_POST['fname']))
//if(true)
{
  $fetchVisitor = mysqli_query($con, "select * from people where firstname like '%".$_POST['fname']."%'"); // get all data that matches
  //$fetchVisitor = mysqli_query($con, "select * from people where firstname like '%james%'"); // get all data that matches
  //$dataVisitor = mysqli_fetch_assoc($fetchVisitor); // gets row data stores as an array
  //var_dump($dataVisitor);

  $data = array();

  while($row = mysqli_fetch_array($fetchVisitor))
  {
    $fetchCompany = mysqli_query($con, "select name from company where cid like '%".$row['cid']."%'");
    $dataCompany = mysqli_fetch_assoc($fetchCompany);
    $data[] = array("id"=>$row['pid'], "fname"=>$row['firstname'], "lname"=>$row['lastname'], "photo"=>$row['photo'], "cname"=>"(".$dataCompany['name'].")");
  }

  //$fetchCompany = mysqli_query($con, "select name from company where cid='".$dataVisitor['cid']."'");
  //dataCompany = mysqli_fetch_assoc($fetchCompany);

  //$fetchVisitor = mysqli_query($con, "select * from people where pid='".$_POST['pid']."'"); // get all data
  //$result = "<td><img src='".$dataVisitor['photo']."'alt='' width='150'></td>"."<td>".$dataVisitor['firstname']."</td><td>".$dataVisitor['lastname']."</td><td>".$dataCompany['name']."</td>";
  //$dataCombo[] = array("photo" => $dataVisitor['photo'], "fname" => $dataVisitor['firstname'], "lname" => $dataVisitor['lastname'], "cname" => $dataCompany['name'], "visitorid" => $dataVisitor['pid']);
  $result = json_encode($data);
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
