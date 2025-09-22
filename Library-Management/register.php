<?php
include "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $department = $_POST["department"];
    $password = $_POST["password"]; // plain text password

    // Prepare insert query
    $stmt = $conn->prepare("INSERT INTO members (name, email, phone, department, password) VALUES (?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("sssss", $name, $email, $phone, $department, $password);

    if ($stmt->execute()) {
        $success = "Registration successful! You can now <a href='login.php'>login</a>.";
    } else {
        $error = "Registration failed: " . $stmt->error;
    }
}
?>

<!-- HTML Part -->
<!DOCTYPE html>
<html>
<head>
  <title>Register as Member</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
    <div class="header">Member Registration</div>
    <div class="card">
      <form method="post">
        <input type="text" name="name" required placeholder="Full Name">
        <input type="email" name="email" required placeholder="Email Address">
        <input type="text" name="phone" required placeholder="Phone Number">
        <input type="text" name="department" required placeholder="Department">
        <input type="password" name="password" required placeholder="Create Password">
        <button type="submit">Register</button>
      </form>
      <?php
        if (isset($error)) echo "<p style='color:red;'>$error</p>";
        if (isset($success)) echo "<p style='color:green;'>$success</p>";
      ?>
      <p>Already a member? <a href="login.php">Login here</a></p>
    </div>
  </d
