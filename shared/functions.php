<?php
/* FUNCTIONS USED ON MULTIPLE PAGES HERE */

// takes the type of record then returns the table and unique id name
function id_map($type)
{
  $id_map = ["host" => ["table" => "users", "id" => "uid"], "visitor" => ["table" => "people", "id" => "pid"], "company" => ["table" => "company", "id" => "cid"], "visitation" => ["table" => "visitation", "id" => "vid"]];
  $map = isset($id_map[$type])? $id_map[$type] : FALSE;

  return $map;
}

// generates unique id number for db records
// type determines the table and prefix that is used
function id_gen($type)
{
  include("config.php"); // gets sql connection details

/// ***** NO error handling if type not in map throws up errors
  $id_map = id_map($type);
  $prefix = $id_map['id'];
  $table = $id_map['table'];

  // generate a unique id and then insert it and the name to the relevent table
  $uid = "";
  while(True)
    {
      foreach(range(1,5) as $num)
      {
        $uid = $uid.mt_rand(0,9);
      }

      if(strlen($uid) >= 29)
      {
        // check generated uid doesn't exisit in table
        $checkID = mysqli_fetch_assoc(mysqli_query($con, "SELECT $prefix FROM $table WHERE $prefix = '".$prefix."-".$uid."'"));

        if(!isset($checkID))
        {
            //var_dump($checkID);
            //echo("id doesn't exisit");
            break;
        }
        // if record exists then we clear all the random numbers and start again
        elseif(isset($checkID))
        {
          $uid = "";
        }
      }
      $uid = $uid."-";
    }
  return $prefix."-".$uid;
}

/// used to lookup single sql records
function get_record($table, $id, $uid)
{
  include("config.php");
  $fetchRecord = mysqli_query($con, "SELECT * from $table WHERE $uid = '$id'");
  $fetchData = mysqli_fetch_assoc($fetchRecord);

  //var_dump($fetchData);
  return($fetchData);
}

/// used to return multiple sql records
function get_record_object($table, $id, $uid)
{
  include("config.php");
  $fetchRecord = mysqli_query($con, "SELECT * from $table WHERE $uid = '$id'");

  return($fetchRecord);
}

/// check in user function - requires visitors id number and the visitation id number
function checkin_visitor($vid, $pid)
{
  include("config.php");
  // Change Visitor State to 1
  mysqli_query($con, "UPDATE people SET state=1 WHERE pid='$pid'");

  // Record checked in visitor into visitation log table
  date_default_timezone_set("Europe/London"); // set time zone to account for BST
  $date = date("Y-m-d");
  $time = date("H:i");
  $columns = "vid, pid, date, time, state";
  $vals = "'" . $vid . "', '" . $pid . "', '" . $date . "', '" . $time . "', 1";
  mysqli_query($con, "INSERT INTO visitation_log ($columns) VALUES ($vals)");

  return "done";
}

/// check out user function - requires visitors id number and the visitation id number
function checkout_visitor($vid, $pid)
{
  include("config.php");
  // Change Visitor State to 1
  mysqli_query($con, "UPDATE people SET state=0 WHERE pid='$pid'");

  // Record checked in visitor into visitation log table
  date_default_timezone_set("Europe/London"); // set time zone to account for BST
  $date = date("Y-m-d");
  $time = date("H:i");
  $columns = "vid, pid, date, time, state";
  $vals = "'" . $vid . "', '" . $pid . "', '" . $date . "', '" . $time . "', 0";
  mysqli_query($con, "INSERT INTO visitation_log ($columns) VALUES ($vals)");

  return "done";
}

/// updates photo after camera is used to take new picture ** Update to cover more visitor sql updates
function visitor_update($pid, $photo_data)
{
  include("config.php");
echo("shared function");
  $photo_data = str_replace("data:image/png;base64,", "", $photo_data); // using for now camera adds what we don't want store for now

  mysqli_query($con, "UPDATE people SET photo='$photo_data' WHERE pid='$pid'");
}
?>
