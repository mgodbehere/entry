<?php
include 'config.php';

// Used to get all infomation on either hosts, visitors or companies then returns that for the management page

// if used for error checking result set to null if Something goes wrong

//if(isset($_POST['search_str']))
if(true)
{
  $search_str = $_POST['search_str'];
  $search_type = $_POST['search_type'];

  switch($search_type)
  {
    case "host":
      $table = "users";
      $search_term = "CONCAT(firstname, ' ', lastname) LIKE '%".$search_str."%' limit 5";
      break;
    case "company":
      $table = "company";
      $search_term = "name like '%".$search_str."%'";
      break;
    case "visitor":
      $table = "people";
      $search_term = "CONCAT(firstname, ' ', lastname) LIKE '%".$search_str."%' limit 5";
      break;
    case "date":
      $date = $search_str;
      break;
    default:
      $table = "people";
      $search_term = "CONCAT(firstname, ' ', lastname) LIKE '%".$search_str."%' limit 5";
      $date = date("Y-m-d");
  }

//  $fetchSearch = mysqli_query($con, "select * from ".$table."  where ".$search_term.""); // get all data that matches
  $today = date("Y-m-d");
  $today = $date;
  $fetchSearch = mysqli_query($con, "SELECT * FROM visitation WHERE date_start = '$date'"); // get all data that matches
  $fetchVisitData = mysqli_fetch_array($fetchSearch);

  $list_visitors = ""; // blank string for list of vistors
  foreach($fetchSearch as $data)
  {
    $list_visitors = $list_visitors . $data['list_visitors'] . ","; // add each string of visitors from the sql query
  }
  $list_visitors = explode(",", $list_visitors); // turn the string into an array
  $list_visitors = array_filter($list_visitors); // remove any blanks (last element will always be blank)

  $data = array();

  foreach($list_visitors as $pid)
  {
    $pid = str_replace(" ", "", $pid); // removes any white space from the current element

    $fetchVisitor = mysqli_query($con, "SELECT * FROM people WHERE pid = '$pid'");
    $fetchVisitorData = mysqli_fetch_assoc($fetchVisitor);

    $companyID = $fetchVisitorData['cid'];
    $fetchCompany = mysqli_query($con, "SELECT * FROM company WHERE cid = '$companyID'");
    $fetchCompanyData = mysqli_fetch_assoc($fetchCompany);

    // generate qr code for each visitor with the visitation id embedded
    $qr_id = $fetchVisitorData['pid'];
    $qr_vid = $fetchVisitData['vid'];
    $qr = shell_exec("..\shared\qrgen.py $qr_id $qr_vid");

    $data[] = array("id"=>$fetchVisitorData['pid'], "fname"=>$fetchVisitorData['firstname'], "lname"=>$fetchVisitorData['lastname'], "cname"=>$fetchCompanyData['name'], "type"=>$search_type, "photo"=>$fetchVisitorData['photo'], "qr"=>$qr, "vid"=>$fetchVisitData['vid'], "vname"=>$fetchVisitData['name'], "state"=>$fetchVisitorData['state']);
  }
  $result = json_encode($data);
}
else
{
  $result = "";
}
echo $result;
?>
