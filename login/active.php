<?php
// Used on all pages except index to check if user is logged in, if not then redirects
session_start();
if(!isset($_SESSION['loggedin']))
{
  header("Location: ./index.php");
  exit;
}
?>
