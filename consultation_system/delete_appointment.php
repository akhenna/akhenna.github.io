<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

if(isset($_GET['id'])){

    $id = intval($_GET['id']);

    $check = mysqli_query($conn,
        "SELECT status FROM appointments WHERE id = $id");

    if($row = mysqli_fetch_assoc($check)){

        if(strtolower($row['status']) == 'completed'){

            mysqli_query($conn,
                "DELETE FROM appointments WHERE id = $id");
        }
    }
}

header("Location: admin_appointments.php");
exit();
?>