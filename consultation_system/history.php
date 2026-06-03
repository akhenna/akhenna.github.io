<?php
session_start();
include 'db.php';

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$history = mysqli_query(
    $conn,
    "SELECT * FROM appointments
     WHERE student_id='$student_id'
     AND status='completed'
     ORDER BY appointment_date DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>History | PUP AppointEd</title>

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
}

.sidebar{
width:260px;
background:#800000;
color:white;
min-height:100vh;
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
padding:25px;
}

.topbar{
margin-bottom:20px;
}

.topbar h1{
color:#800000;
}

.small{
font-size:13px;
color:#666;
}

.history-container{
display:flex;
flex-direction:column;
gap:15px;
}

.history-card{
background:white;
padding:20px;
border-radius:12px;
border:1px solid #eee;
transition:.3s;
}

.history-card:hover{
box-shadow:0 5px 15px rgba(0,0,0,.08);
}

.history-card h3{
color:#800000;
margin-bottom:10px;
}

.history-card p{
color:#555;
font-size:14px;
margin-bottom:5px;
}

.completed-badge{
display:inline-block;
margin-top:10px;
padding:6px 12px;
border-radius:20px;
background:#d4f5dd;
color:#15803d;
font-size:12px;
font-weight:600;
}

.empty{
background:white;
padding:30px;
border-radius:12px;
text-align:center;
color:#777;
border:1px solid #eee;
}

</style>
</head>

<body>

<div class="sidebar">

<h2>PUP AppointEd</h2>

<div class="menu">
<a href="dashboard.php">Dashboard</a>
<a href="book_appointment.php">Book Appointment</a>
<a href="my_appointment.php">My Appointments</a>
<a class="active" href="history.php">History</a>
<a href="profile.php">Profile</a>
<a href="logout.php">Logout</a>
</div>

</div>

<div class="main">

<div class="topbar">
<h1>Consultation History</h1>
<p class="small">Completed appointments only</p>
</div>

<div class="history-container">

<?php if(mysqli_num_rows($history) > 0): ?>

    <?php while($row = mysqli_fetch_assoc($history)): ?>

    <div class="history-card">

        <h3><?= htmlspecialchars($row['concern']); ?></h3>

        <p><b>Faculty:</b> <?= htmlspecialchars($row['faculty']); ?></p>
        <p><b>Schedule:</b> <?= htmlspecialchars($row['schedule']); ?></p>
        <p><b>Date:</b> <?= date('F d, Y', strtotime($row['appointment_date'])); ?></p>

        <span class="completed-badge">Completed</span>

    </div>

    <?php endwhile; ?>

<?php else: ?>

    <div class="empty">
        No consultation history yet.
    </div>

<?php endif; ?>

</div>

</div>

</body>
</html>