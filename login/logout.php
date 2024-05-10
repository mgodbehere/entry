<?php
// Used to logout out user and destroy session data
session_start();
session_destroy();
header("Location: ../index.php");
?>
