<?php
session_start();
include 'db.php';

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM faculty WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $faculty = $result->fetch_assoc();

    if($faculty && password_verify($password, $faculty['password'])){

        $_SESSION['faculty_id'] = $faculty['faculty_id'];

        header("Location: faculty_dashboard.php");
        exit();
    }

    $error = "Invalid email or password!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Faculty Login</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #f5f6fa;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.box {
    width: 420px;
    background: white;
    padding: 35px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.logo {
    width: 60px;
    height: 60px;
    background: #800000;
    color: white;
    margin: auto;
    border-radius: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 22px;
    font-weight: bold;
}

h2 {
    margin: 15px 0 5px;
    color: #800000;
}

p {
    color: #666;
    font-size: 14px;
}

input {
    width: 100%;
    padding: 12px;
    margin-top: 12px;
    border-radius: 6px;
    border: 1px solid #ddd;
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

.links {
    margin-top: 15px;
    font-size: 14px;
}

.links a {
    color: #800000;
    text-decoration: none;
    display: block;
    margin-top: 5px;
}
</style>
</head>

<body>

<div class="box">

    <div class="logo">P</div>

    <h2>Faculty Login</h2>
    <p>Sign in to your faculty account</p>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="faculty@pup.edu.ph" required>
        <input type="password" name="password" placeholder="Enter your password" required>

        <button name="login">Sign In</button>
    </form>

    <div class="links">
        <a href="index.php">Back to Home</a>
        <a href="student_login.php">Student Portal</a>
    </div>

</div>

</body>
</html>