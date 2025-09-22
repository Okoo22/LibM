<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="container">
    <div class="header">Welcome Admin, <?= $_SESSION["name"] ?></div>
    <div class="card">
      <p>You are now logged in as an Admin.</p>
      <a href="issued-books.php"><button>View Issued Books</button></a>
      <a href="../logout.php"><button>Logout</button></a>
    </div>
  </div>
</body>
</html>
