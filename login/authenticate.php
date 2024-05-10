<?php
session_start();
include("../ajax/config.php");
if($_SERVER["REQUEST_METHOD"] == "POST")
{
  var_dump($_POST);
  if ($_POST['button'] == "login")
  {
    // get username and password look them up check password
    // return yes or no
    $uName = $_POST['userName'];
    $pWord = $_POST['password'];

    // return password hash where name matches uName
    $user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE username = '$uName'"));
    if(password_verify($pWord, $user['password']) == 1)
    {

      // create a session to keep track of logged in user
      session_regenerate_id();
      $_SESSION['loggedin'] = True;
      $_SESSION['username'] = $uName;
      $_SESSION['fname'] = $user['firstname'];
      $_SESSION['lname'] = $user['lastname'];
      $_SESSION['photo'] = $user['photo']; // grab photo string and store for display in menu bar
      $_SESSION['uid'] = $user['uid']; // logged in user id for use in prebook page
      header("Location: ../dashboard.php");

    }
    else
    {
      // need to look at this if username or password wrong we stop here, we need to kick them back to index.php and tell them there
	  $_SESSION['error'] = 1;
	  header("Location: ../index.php");
      exit('username or password incorrect');
    }
  }
}
?>
