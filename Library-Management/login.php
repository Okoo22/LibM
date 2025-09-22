<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // ----------- Check admin login -----------
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    if (!$stmt) {
        die("SQL Error (admin): " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $adminResult = $stmt->get_result();

    if ($adminResult->num_rows === 1) {
        $admin = $adminResult->fetch_assoc();

        if (password_verify($password, $admin["password"])) {
            $_SESSION["user_id"] = $admin["id"];
            $_SESSION["role"] = "admin";
            $_SESSION["name"] = $admin["name"];

            header("Location: admin/dashboard.php");
            exit();
        } else {
            $error = "Invalid password for admin.";
        }
    } else {
        // ----------- Check member login -----------
        $stmt = $conn->prepare("SELECT * FROM members WHERE email = ?");
        if (!$stmt) {
            die("SQL Error (member): " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $memberResult = $stmt->get_result();

        if ($memberResult->num_rows === 1) {
            $user = $memberResult->fetch_assoc();

            // Plain-text password comparison for members
            if ($password === $user["password"]) {
                $_SESSION["user_id"] = $user["member_id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["role"] = "member";

                header("Location: member/dashboard.php");
                exit();
            } else {
                $error = "Invalid password for member.";
            }
        } else {
            $error = "Email not found.";
        }
    }
}
?>

<!-- HTML Part -->
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
    <div class="header">Login</div>
    <div class="card">
      <form method="post">
        <input type="email" name="email" required placeholder="Enter your email">
        <input type="password" name="password" required placeholder="Enter your password">
        <button type="submit">Login</button>
      </form>
      <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </div>
  </div>
</body>
</html>
