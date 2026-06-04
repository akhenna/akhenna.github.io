<?php
session_start();
include 'db.php';

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = mysqli_query(
        $conn,
        "SELECT * FROM students WHERE email='$email'"
    );

    if(mysqli_num_rows($result) > 0){

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])){

            $_SESSION['student_id'] = $user['student_id'];

            $_SESSION['student_name'] =
            $user['first_name'].' '.$user['last_name'];

            header("Location: dashboard.php");
            exit();
        }
    }

    echo "<script>
        alert('Invalid Email or Password');
        window.location='index.php';
    </script>";
}
?>