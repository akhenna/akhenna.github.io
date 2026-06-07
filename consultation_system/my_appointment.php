<?php
session_start();

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","consultation_system");

if($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
}

$student_id = $_SESSION['student_id'];

$result = $conn->query("
SELECT *
FROM appointments
WHERE student_id = '$student_id'
ORDER BY created_at DESC
");

$facultyNames = [
    1 => 'Sir Aris Dela Rea',
    2 => 'Sir Christopher Jay De Claro',
    3 => 'Maam Melanie Castillo',
    4 => 'Maam Marie Nel Velasco'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Appointments | PUP AppointEd</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700;800&display=swap" rel="stylesheet">

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

.card{
background:white;
padding:20px;
border-radius:12px;
border:1px solid #eee;
margin-bottom:15px;
transition:.3s;
}

.card:hover{
box-shadow:0 5px 15px rgba(0,0,0,.08);
}

.card h3{
color:#800000;
margin-bottom:10px;
}

.info{
margin-top:8px;
color:#555;
font-size:14px;
}

.status{
display:inline-block;
padding:5px 12px;
border-radius:20px;
font-size:12px;
font-weight:600;
margin-top:10px;
}

.pending{
background:#fff3cd;
color:#856404;
}

.approved{
background:#d4edda;
color:#155724;
}

.completed{
background:#d1ecf1;
color:#0c5460;
}

.empty{
background:white;
padding:30px;
border-radius:12px;
border:1px solid #eee;
text-align:center;
color:#777;
}

@media (max-width:768px){

body{
flex-direction:column;
}

.sidebar{
width:100%;
min-height:auto;
}

.menu{
display:flex;
flex-wrap:wrap;
gap:8px;
}

.menu a{
flex:1 1 40%;
text-align:center;
}

.main{
padding:15px;
}

}
</style>

</head>
<body>

<div class="sidebar">

<h2>PUP AppointEd</h2>

<div class="menu">
<a href="dashboard.php">Dashboard</a>
<a href="book_appointment.php">Book Appointment</a>
<a class="active" href="my_appointment.php">My Appointments</a>
<a href="history.php">History</a>
<a href="profile.php">Profile</a>
<a href="logout.php">Logout</a>
</div>

</div>

<div class="main">

<div class="topbar">
<h1>My Appointments</h1>
<p class="small">View your submitted consultation requests.</p>
</div>

<?php if($result && $result->num_rows > 0): ?>

<?php while($row = $result->fetch_assoc()): ?>

<?php
$facultyName = $facultyNames[$row['faculty_id']] ?? 'Unknown Faculty';
$statusClass = strtolower($row['status']);
?>

<div class="card">

<h3><?= htmlspecialchars($row['concern']) ?></h3>

<div class="info">
<strong>Faculty:</strong>
<?= htmlspecialchars($facultyName) ?>
</div>

<div class="info">
<strong>Schedule:</strong>
<?= htmlspecialchars($row['schedule']) ?>
</div>

<div class="info">
<strong>Date:</strong>
<?= date("F d, Y", strtotime($row['appointment_date'])) ?>
</div>

<div class="info">
<strong>Submitted:</strong>
<?= date("F d, Y h:i A", strtotime($row['created_at'])) ?>
</div>

<div class="info">
<strong>Status:</strong>
<span class="status <?= $statusClass ?>">
<?= ucfirst($row['status']) ?>
</span>
</div>

</div>

<?php endwhile; ?>

<?php else: ?>

<div class="empty">
No appointments found.
</div>

<?php endif; ?>

</div>

</body>
</html>