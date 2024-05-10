<?php
include 'config.php';

if(!isset($_POST['searchTerm'])){
  $fetchData = mysqli_query($con,"select * from people order by lastname limit 5");
}else{
  $search = $_POST['searchTerm'];
  //$fetchData = mysqli_query($con,"select * from people where lastname like '%".$search."%' OR firstname LIKE '%".$search."%' limit 5");
  $fetchData = mysqli_query($con,"SELECT * FROM people WHERE CONCAT(firstname, ' ', lastname) LIKE '%".$search."%' limit 5"); // try or like last or first maybe?
}

$data = array();
while ($row = mysqli_fetch_array($fetchData)) {
  $fetchCompany = mysqli_query($con, "select name from company where cid like '%".$row['cid']."%'");
  $dataCompany = mysqli_fetch_assoc($fetchCompany);
  $data[] = array("id"=>$row['pid'], "text"=>$row['firstname']." ".$row['lastname']." (".$dataCompany['name'].")");
}
$test = json_encode($data);
//var_dump($test);
//echo "<br />";
echo json_encode($data);
?>
