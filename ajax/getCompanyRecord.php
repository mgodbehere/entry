<?php
include 'config.php';
shell_exec("dir");
if(!isset($_POST['searchTerm'])){
  $fetchData = mysqli_query($con,"select * from company order by name limit 5");
}else{
  $search = $_POST['searchTerm'];
  $fetchData = mysqli_query($con,"select * from company where name like '%".$search."%' limit 5");
}

$data = array();
while ($row = mysqli_fetch_array($fetchData)) {
  //$fetchCompany = mysqli_query($con, "select name from company where cid like '%".$row['cid']."%'");
  //$dataCompany = mysqli_fetch_assoc($fetchCompany);
  //$data[] = array("id"=>$row['pid'], "text"=>$row['firstname']." ".$row['lastname']." (".$dataCompany['name'].")");
  $data[] = array("id"=>$row['cid'], "text"=>$row['name']);
}

echo json_encode($data);
?>
