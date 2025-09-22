<?php
session_start();

// Only allow logged-in members
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "member") {
    header("Location: ../login.php");
    exit();
}

include "../includes/db.php";
include "../includes/functions.php"; // contains calculateFine()

$member_id = $_SESSION["user_id"];

// Fetch issued books for this member
$sql = "SELECT ib.issue_id, ib.issue_date, ib.due_date, ib.return_date, ib.status,
               b.name AS book_name, b.author
        FROM issued_books ib
        JOIN books b ON ib.book_id = b.book_id
        WHERE ib.member_id = ?
        ORDER BY ib.issue_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Member Dashboard</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="container">
    <h2>My Issued Books</h2>
    <a href="logout.php"><button>Logout</button></a>

    <table border="1" cellpadding="10">
      <tr>
        <th>Issue ID</th>
        <th>Book Name</th>
        <th>Author</th>
        <th>Issue Date</th>
        <th>Due Date</th>
        <th>Return Date</th>
        <th>Status</th>
        <th>Fine</th>
      </tr>

      <?php while($row = $result->fetch_assoc()): 
            $fine = calculateFine($row['due_date'], $row['return_date'], $row['status']);
      ?>
      <tr>
        <td><?= $row['issue_id'] ?></td>
        <td><?= $row['book_name'] ?></td>
        <td><?= $row['author'] ?></td>
        <td><?= $row['issue_date'] ?></td>
        <td><?= $row['due_date'] ?></td>
        <td><?= $row['return_date'] ?? '-' ?></td>
        <td><?= ucfirst($row['status']) ?></td>
        <td>₹<?= $fine ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
</body>
</html>
