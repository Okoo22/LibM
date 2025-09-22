<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

include "../includes/db.php";

if (isset($_GET["id"])) {
    $issue_id = $_GET["id"];
    $return_date = date("Y-m-d");
    $status = "returned";

    // Update the status and return_date
    $stmt = $conn->prepare("UPDATE issued_books SET return_date = ?, status = ? WHERE issue_id = ?");
    $stmt->bind_param("ssi", $return_date, $status, $issue_id);
    $stmt->execute();
}

header("Location: issued-books.php");
exit();
?>
