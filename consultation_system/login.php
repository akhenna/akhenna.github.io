<?php
session_start();
include 'db.php';

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if($role == "student"){

        $stmt = $conn->prepare("SELECT * FROM students WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if($user && password_verify($password,$user['password'])){
            $_SESSION['student_id'] = $user['student_id'];
            header("Location: dashboard.php");
            exit();
        }
    }

    if($role == "faculty"){

        $stmt = $conn->prepare("SELECT * FROM faculty WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();
        $faculty = $result->fetch_assoc();

        if($faculty && password_verify($password,$faculty['password'])){
            $_SESSION['faculty_id'] = $faculty['faculty_id'];
            header("Location: faculty_dashboard.php");
            exit();
        }
    }


    if($role == "admin"){

        $stmt = $conn->prepare("SELECT * FROM admin WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        if($admin && password_verify($password,$admin['password'])){
            $_SESSION['admin_id'] = $admin['admin_id'];
            header("Location: admin_dashboard.php");
            exit();
        }
    }

    $error = "Invalid email or password!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

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
    width: 380px;
    background: #ffffff;
    padding: 30px;
    border-radius: 14px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    text-align: center;
}

.logo {
    width: 50px;
    height: 50px;
    background: #800000;
    color: #fff;
    margin: auto;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: bold;
}

.tabs {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin: 15px 0;
}

.tab {
    flex: 1;
    padding: 10px;
    border-radius: 20px;
    border: 1px solid #ccc;
    background: #f3f4f6;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
}

.tab.active {
    background: #800000;
    color: white;
    border: 1px solid #800000;
}

.tab:hover {
    border-color: #800000;
}

h2 {
    margin: 15px 0 5px;
}

.subtitle {
    font-size: 13px;
    color: #666;
    margin-bottom: 18px;
}

input {
    width: 100%;
    padding: 11px;
    margin-top: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    box-sizing: border-box;
}

button.signin-btn {
    width: 60%;              
    margin-top: 15px;
    padding: 9px;
    background: #800000;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    font-size: 14px;
}

button.signin-btn:hover {
    background: #a00000;
}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.error {
    color: red;
    font-size: 13px;
    margin-top: 10px;
}

.footer {
    margin-top: 15px;
    font-size: 13px;
}

.footer a {
    color: #800000;
    text-decoration: none;
    font-weight: bold;
    margin: 0 4px;
}
</style>
</head>

<body>

<div class="login-container">

    <div class="logo">P</div>
    <h2>Login Portal</h2>
    <p class="subtitle">Sign in to your account</p>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <div class="tabs">
        <button type="button" class="tab active" onclick="setRole('student', this)">Student</button>
        <button type="button" class="tab" onclick="setRole('faculty', this)">Faculty</button>
        <button type="button" class="tab" onclick="setRole('admin', this)">Admin</button>
    </div>

    <form method="POST">

        <input type="hidden" name="role" id="role" value="student">

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="login" class="signin-btn">
            Sign In
        </button>

    </form>

    <div class="footer">
        <a href="index.php">← Back to Home</a>
        <a href="register.php">Register</a>
    </div>

</div>

<script>

function setRole(role, element){

    document.getElementById("role").value = role;

    let tabs = document.querySelectorAll(".tab");
    tabs.forEach(t => t.classList.remove("active"));

    element.classList.add("active");
}
</script>

</body>
</html>