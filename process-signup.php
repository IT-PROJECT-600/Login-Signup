<?php

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Name validation
    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
    if (empty($name)) {
        $errors[] = "Name is required";
    }

    // Email validation
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }

    // Password validation
    $password = $_POST["password"];
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }
    if (!preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must contain at least one uppercase letter, one lowercase letter, and one number";
    }

    // Password confirmation validation
    if ($_POST["password"] !== $_POST["password_confirmation"]) {
        $errors[] = "Passwords must match";
    }

    // If no errors, proceed with database insertion
    if (empty($errors)) {
        $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $mysqli = require __DIR__ . "/database.php";

        $sql = "INSERT INTO user (name, email, password_hash) VALUES (?, ?, ?)";

        $stmt = $mysqli->stmt_init();

        if (!$stmt->prepare($sql)) {
            die("SQL error: " . $mysqli->error);
        }

        $stmt->bind_param("sss", $name, $email, $password_hash);

        if ($stmt->execute()) {
            header("Location: signup-success.html");
            exit;
        } else {
            if ($mysqli->errno === 1062) {
                $errors[] = "Email already taken";
            } else {
                $errors[] = $mysqli->error . " " . $mysqli->errno;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Error</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Signup Error</h1>
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <p><a href="signup.html">Try again</a></p>
    </div>
</body>
</html>