<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

include "../includes/db.php";

// Fetch issued books with JOINs to get book and member names
$sql = "SELECT issued_books.*, 
               books.name AS book_name, 
               books.author AS author, 
               members.name AS member_name, 
               members.email AS member_email 
        FROM issued_books 
        JOIN books ON issued_books.book_id = books.book_id 
        JOIN members ON issued_books.member_id = members.id 
        ORDER BY issue_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Issued Books</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="container">
    <h2>Issued Book History</h2>
    <a href="dashboard.php"><button>Back to Dashboard</button></a>
    <table border="1" cellpadding="10">
      <tr>
  <th>Issue ID</th>
  <th>Book Name</th>
  <th>Author</th>
  <th>Issued To</th>
  <th>Email</th>
  <th>Issue Date</th>
  <th>Due Date</th>
  <th>Status</th>
  <th>Fine</th>
  <th>Action</th>
</tr>

 <?php while ($row = $result->fetch_assoc()): ?>
  <?php 
    $fine = calculateFine($row["due_date"], $row["return_date"], $row["status"]);
  ?>
  <tr>
    <td><?= $row["issue_id"] ?></td>
    <td><?= $row["book_name"] ?></td>
    <td><?= $row["author"] ?></td>
    <td><?= $row["member_name"] ?></td>
    <td><?= $row["member_email"] ?></td>
    <td><?= $row["issue_date"] ?></td>
    <td><?= $row["due_date"] ?></td>
    <td><?= ucfirst($row["status"]) ?></td>
    <td>₹<?= $fine ?></td>
    <td>
      <?php if ($row["status"] === "issued"): ?>
        <a href="return-book.php?id=<?= $row['issue_id'] ?>" onclick="return confirm('Mark as returned?')">Mark Returned</a>
      <?php else: ?>
        Returned on <?= $row["return_date"] ?>
      <?php endif; ?>
    </td>
  </tr>
<?php endwhile; ?>
    </table>
  </div>
</body>
</html>
