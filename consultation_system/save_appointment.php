<?php
session_start();
include 'db.php';

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$faculty_id = isset($_POST['faculty_id']) ? $_POST['faculty_id'] : 0;
$faculty_name = isset($_POST['faculty_name']) ? $_POST['faculty_name'] : '';
$schedule = isset($_POST['schedule']) ? $_POST['schedule'] : '';
$appointment_date = isset($_POST['appointment_date']) ? $_POST['appointment_date'] : '';
$concern = isset($_POST['concern']) ? $_POST['concern'] : '';

if($faculty_id == 0){
    die("Please select a faculty first.");
}

$sql = "INSERT INTO appointments
(
    faculty_id,
    faculty_name,
    student_id,
    schedule,
    appointment_date,
    concern,
    status
)
VALUES
(
    '$faculty_id',
    '$faculty_name',
    '$student_id',
    '$schedule',
    '$appointment_date',
    '$concern',
    'pending'
)";

if(mysqli_query($conn, $sql)){
    header("Location: my_appointment.php");
    exit();
}else{
    die("Database Error: " . mysqli_error($conn));
}
?>