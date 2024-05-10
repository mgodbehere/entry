<?php
include 'config.php';

if(!isset($_POST['searchTerm'])){
  $fetchData = mysqli_query($con,"select * from users order by lastname limit 5");
}else{
  $search = $_POST['searchTerm'];
  $fetchData = mysqli_query($con,"SELECT * FROM users WHERE CONCAT(firstname, ' ', lastname) LIKE '%".$search."%' limit 5");
}

$data = array();
while ($row = mysqli_fetch_array($fetchData)) {
  $data[] = array("id"=>$row['uid'], "text"=>$row['firstname']." ".$row['lastname']);
}
$test = json_encode($data);
//var_dump($test);
//echo "<br />";
echo json_encode($data);
?>
