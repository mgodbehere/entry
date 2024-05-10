<?php
include 'config.php';

// Used to get all infomation on either hosts, visitors or companies then returns that for the management page

// if used for error checking result set to null if Something goes wrong

if(isset($_POST['search_str']))
//if(true)
{
  $search_str = $_POST['search_str'];
  $search_type = $_POST['search_type'];

  switch($search_type)
  {
    case "host":
      $table = "users";
      $search_term = "CONCAT(firstname, ' ', lastname) LIKE '%".$search_str."%'";
      break;
    case "company":
      $table = "company";
      $search_term = "name like '%".$search_str."%'";
      break;
    case "visitor":
      $table = "people";
      $search_term = "CONCAT(firstname, ' ', lastname) LIKE '%".$search_str."%'";
      break;
    case "visitation":
      $table = "visitation";
      $search_term = "name like '%".$search_str."%'";
      break;
    default:
      $table = "people";
      $search_term = "CONCAT(firstname, ' ', lastname) LIKE '%".$search_str."%'";
  }

  $fetchSearch = mysqli_query($con, "select * from ".$table."  where ".$search_term.""); // get all data that matches

  $data = array();

  while($row = mysqli_fetch_array($fetchSearch))
  {
    //if(isset($row['cid']))
    if($search_type == "host")
    {
      $data[] = array("id"=>$row['uid'], "fname"=>$row['firstname'], "lname"=>$row['lastname'], "type"=>$search_type, "photo"=>$row['photo'], "uname"=>$row['username']);
    }
    else if($search_type == "company")
    {
      $data[] = array("id"=>$row['cid'], "name"=>$row['name'], "type"=>$search_type);
    }
    else if($search_type == "visitation")
    {
      $data[] = array("id"=>$row['vid'], "name"=>$row['name'], "hosts"=>$row['list_hosts'], "visitors"=>$row['list_visitors'], "date_start"=>$row['date_start'], "valid"=>$row['valid'], "type"=>$search_type);
    }
    else
    {
      $fetchCompany = mysqli_query($con, "select name from company where cid like '%".$row['cid']."%'");
      $dataCompany = mysqli_fetch_assoc($fetchCompany);
      $data[] = array("id"=>$row['pid'], "fname"=>$row['firstname'], "lname"=>$row['lastname'], "cname"=>"(".$dataCompany['name'].")","type"=>$search_type, "photo"=>$row['photo'], "state"=>$row['state']);
    }
  }
  $result = json_encode($data);
}
else
{
  $result = "";
}
echo $result;
?>
