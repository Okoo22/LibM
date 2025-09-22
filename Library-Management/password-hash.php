<?php
$passwords = [
    "mansi123" => "mansi@library.com",
    "adesh123" => "adesh@library.com",
    "omkar123" => "omkar@library.com"
];

foreach ($passwords as $plain => $email) {
    echo "Email: $email<br>";
    echo "Password: $plain<br>";
    echo "Hash: " . password_hash($plain, PASSWORD_DEFAULT) . "<br><br>";
}
?>
