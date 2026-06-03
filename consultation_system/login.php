<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM students WHERE email='$email'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['student_id'] = $user['student_id'];
        $_SESSION['student_name'] = $user['first_name'].' '.$user['last_name'];

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Login</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f3f4f6;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-container {
    width: 400px;
    background: #ffffff;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    text-align: center;
}

.logo {
    width: 55px;
    height: 55px;
    background: #800000;
    color: #fff;
    margin: auto;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: bold;
}

h2 {
    margin: 15px 0 5px;
}

.subtitle {
    font-size: 14px;
    color: #666;
    margin-bottom: 20px;
}

input {
    width: 100%;
    padding: 12px;
    margin-top: 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}

button {
    width: 100%;
    margin-top: 18px;
    padding: 12px;
    background: #800000;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background: #a00000;
}

.error {
    color: red;
    font-size: 13px;
    margin-top: 10px;
}

.footer {
    margin-top: 15px;
    font-size: 14px;
}

.footer a {
    color: #800000;
    text-decoration: none;
    font-weight: bold;
}
</style>

</head>

<body>

<div class="login-container">

    <div class="logo">P</div>

    <h2>Student Login</h2>
    <p class="subtitle">Sign in to your student account</p>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="student@pup.edu.ph" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button name="login">Sign In</button>
    </form>

    <div class="footer">
        Don't have an account? <a href="register.php">Register</a>
        <a href="index.php" class="back">← Back to Home</a>
    </div>

</div>

</body>
</html>