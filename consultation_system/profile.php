<?php
session_start();
include 'db.php';

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

$id = $_SESSION['student_id'];

$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if(!$user){
    die("User not found");
}

if(isset($_POST['update'])){

    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];
    $year = $_POST['year_level'];

    $stmt = $conn->prepare("
        UPDATE students 
        SET first_name=?, last_name=?, phone=?, course=?, year_level=? 
        WHERE student_id=?
    ");

    $stmt->bind_param("sssssi", $fname, $lname, $phone, $course, $year, $id);
    $stmt->execute();

    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profile | PUP AppointEd</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
display:flex;
background:#f6f7fb;
min-height:100vh;
}

.sidebar{
width:260px;
background:#800000;
color:white;
padding:20px;
}

.sidebar h2{
font-size:18px;
margin-bottom:30px;
}

.menu a{
display:block;
color:white;
text-decoration:none;
padding:12px;
border-radius:8px;
margin-bottom:8px;
}

.menu a:hover,
.menu a.active{
background:rgba(255,255,255,.15);
}

.main{
flex:1;
padding:30px;
}

.header-card{
background:white;
padding:20px;
border-radius:12px;
margin-bottom:20px;
box-shadow:0 3px 10px rgba(0,0,0,.08);
}

.header-card h2{
color:#800000;
}

.header-card p{
color:#666;
font-size:14px;
margin-top:5px;
}

.info-box{
background:#800000;
color:white;
padding:20px;
border-radius:12px;
margin-bottom:20px;
}

.info-box h3{
font-size:18px;
}

.info-box p{
opacity:.9;
font-size:14px;
margin-top:5px;
}

.card{
background:white;
padding:25px;
border-radius:12px;
box-shadow:0 3px 10px rgba(0,0,0,.08);
}

.form-title{
font-size:16px;
margin-bottom:15px;
color:#800000;
font-weight:600;
}

.row{
display:flex;
gap:15px;
}

.form-group{
width:100%;
margin-bottom:15px;
}

label{
font-size:13px;
color:#555;
}

input, select{
width:100%;
padding:12px;
margin-top:6px;
border-radius:8px;
border:1px solid #ddd;
outline:none;
}

input:focus, select:focus{
border-color:#800000;
}

button{
width:100%;
padding:12px;
background:#800000;
color:white;
border:none;
border-radius:10px;
font-weight:600;
cursor:pointer;
}

button:hover{
background:#9b0000;
}

</style>
</head>

<body>

<div class="sidebar">
<h2>PUP AppointEd</h2>

<div class="menu">
<a href="dashboard.php">Dashboard</a>
<a href="book_appointment.php">Book Appointment</a>
<a href="my_appointments.php">My Appointments</a>
<a href="history.php">History</a>
<a class="active" href="profile.php">Profile</a>
<a href="logout.php">Logout</a>
</div>
</div>

<div class="main">

<div class="header-card">
<h2>Profile Settings</h2>
<p>Manage your account information</p>
</div>

<div class="info-box">
<h3><?= $user['first_name'].' '.$user['last_name']; ?></h3>
<p><?= $user['email']; ?></p>
<p>Student ID: <?= $user['student_id']; ?></p>
</div>

<div class="card">

<div class="form-title">Update Information</div>

<form method="POST">

<div class="row">
<div class="form-group">
<label>First Name</label>
<input type="text" name="first_name" value="<?= $user['first_name'] ?>" required>
</div>

<div class="form-group">
<label>Last Name</label>
<input type="text" name="last_name" value="<?= $user['last_name'] ?>" required>
</div>
</div>

<div class="form-group">
<label>Phone</label>
<input type="text" name="phone" value="<?= $user['phone'] ?>">
</div>

<div class="form-group">
<label>Course</label>
<input type="text" name="course" value="<?= $user['course'] ?>">
</div>

<div class="form-group">
<label>Year Level</label>
<select name="year_level">
<option <?= $user['year_level']=="1st Year"?'selected':'' ?>>1st Year</option>
<option <?= $user['year_level']=="2nd Year"?'selected':'' ?>>2nd Year</option>
<option <?= $user['year_level']=="3rd Year"?'selected':'' ?>>3rd Year</option>
<option <?= $user['year_level']=="4th Year"?'selected':'' ?>>4th Year</option>
</select>
</div>

<button type="submit" name="update">Save Changes</button>

</form>

</div>

</div>

</body>
</html>