<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

include "../includes/db.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST["book_id"];
    $member_id = $_POST["member_id"];
    $issue_date = $_POST["issue_date"];
    $due_date = $_POST["due_date"];

    $stmt = $conn->prepare("INSERT INTO issued_books (book_id, member_id, issue_date, due_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $book_id, $member_id, $issue_date, $due_date);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

// Get books and members for dropdown
$books = $conn->query("SELECT * FROM books");
$members = $conn->query("SELECT * FROM members");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Issue Book</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Issue a Book to Member</h2>
        <form method="post">
            <label>Choose Book:</label><br>
            <select name="book_id" required>
                <option value="">-- Select Book --</option>
                <?php while ($book = $books->fetch_assoc()): ?>
                    <option value="<?= $book['book_id'] ?>"><?= $book['name'] ?> (<?= $book['author'] ?>)</option>
                <?php endwhile; ?>
            </select><br><br>

            <label>Choose Member:</label><br>
            <select name="member_id" required>
                <option value="">-- Select Member --</option>
                <?php while ($member = $members->fetch_assoc()): ?>
                    <option value="<?= $member['id'] ?>"><?= $member['name'] ?> (<?= $member['email'] ?>)</option>
                <?php endwhile; ?>
            </select><br><br>

            <label>Issue Date:</label><br>
            <input type="date" name="issue_date" required><br><br>

            <label>Due Date:</label><br>
            <input type="date" name="due_date" required><br><br>

            <button type="submit">Issue Book</button>
        </form>
        <br>
        <a href="dashboard.php"><button>Back to Dashboard</button></a>
    </div>
</body>
</html>
