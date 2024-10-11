<?php
session_start();
include("connection.php"); // Use the connection.php file to connect to the database

// If the user is already logged in, redirect to the main page
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$error = ""; // Initialize an empty error message

// Check if the login form has been submitted
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to fetch user details from the database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Check if the entered password matches the hashed password in the database
        if (password_verify($password, $user['password'])) {
            // Password is correct, start the session
            $_SESSION['username'] = $username;
            header("Location: index.php"); // Redirect to the main page
            exit();
        } else {
            // Invalid password, redirect to login page with error
            header("Location: login.php?error=true");
            exit();
        }
    } else {
        // Username not found, redirect to login page with error
        header("Location: login.php?error=true");
        exit();
    }
}

// Check if error=true is passed in the URL to show an error message
if (isset($_GET['error']) && $_GET['error'] == 'true') {
    $error = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2 class="login-header">Login</h2>

    <!-- Display error message if login fails -->
    <?php if ($error): ?>
        <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <!-- Login form -->
    <form method="post" action="login.php">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        
        <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
    </form>
</div>

<!--
