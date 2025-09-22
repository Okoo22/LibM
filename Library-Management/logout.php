<?php
session_start();
session_destroy(); // this clears all session data
header("Location: login.php"); // send user back to login page
exit();
?>
