<?php
session_start();
include 'db.php';

if(isset($_GET['id']) && isset($_GET['action']) && isset($_SESSION['faculty_id'])) {
    
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $action = mysqli_real_escape_string($conn, $_GET['action']); 
    $faculty_id = $_SESSION['faculty_id'];

    $allowed_actions = ['approved', 'declined', 'completed'];
    
    if(in_array($action, $allowed_actions)) {
        $sql = "UPDATE appointments SET status = '$action' 
                WHERE id = '$id' AND faculty_id = '$faculty_id'";
        
        mysqli_query($conn, $sql);
    }
}
header("Location: faculty_appointments.php");
exit();
?>