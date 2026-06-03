<?php
session_start();
include 'db.php';

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$faculty = $_POST['faculty'];
$schedule = $_POST['schedule'];
$date = $_POST['date'];
$concern = $_POST['concern'];

$query = "INSERT INTO appointments 
(student_id, faculty, schedule, appointment_date, concern)
VALUES 
('$student_id', '$faculty', '$schedule', '$date', '$concern')";

mysqli_query($conn, $query);

header("Location: my_appointment.php");
exit();
?>