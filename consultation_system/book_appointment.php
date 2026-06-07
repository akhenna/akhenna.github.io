<?php
session_start();

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Appointment | PUP AppointEd</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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


.container{
display:grid;
grid-template-columns:1fr 1fr;
gap:20px;
}


.card{
background:white;
padding:20px;
border-radius:12px;
border:1px solid #eee;
}


.faculty{
display:flex;
justify-content:space-between;
align-items:center;
padding:15px;
border-radius:10px;
border:1px solid #eee;
margin-bottom:10px;
cursor:pointer;
transition:.3s;
flex-wrap:wrap;
gap:10px;
}

.faculty:hover{
background:#f9f9f9;
}

.faculty.active{
border:2px solid #800000;
background:#fff5f5;
}

.badge{
font-size:12px;
padding:5px 10px;
border-radius:20px;
background:#e9ffe9;
color:green;
}


label{
display:block;
margin-top:12px;
font-weight:500;
}

input,
textarea{
width:100%;
padding:12px;
margin-top:8px;
border-radius:8px;
border:1px solid #ddd;
outline:none;
}

input:focus,
textarea:focus{
border-color:#800000;
}

button{
margin-top:15px;
width:100%;
padding:14px;
border:none;
background:#800000;
color:white;
font-weight:600;
border-radius:10px;
cursor:pointer;
}

button:hover{
background:#9b0000;
}


@media (max-width: 992px){
.container{
grid-template-columns:1fr;
}
}


@media (max-width: 768px){

body{
flex-direction:column;
}

.sidebar{
width:100%;
min-height:auto;
text-align:center;
}

.menu{
display:flex;
flex-wrap:wrap;
justify-content:center;
gap:8px;
}

.menu a{
flex:1 1 40%;
text-align:center;
}

.main{
padding:15px;
}

.faculty{
flex-direction:column;
align-items:flex-start;
}

}

</style>
</head>

<body>

<div class="sidebar">
<h2>PUP AppointEd</h2>

<div class="menu">
<a href="dashboard.php">Dashboard</a>
<a class="active" href="book_appointment.php">Book Appointment</a>
<a href="my_appointment.php">My Appointments</a>
<a href="history.php">History</a>
<a href="profile.php">Profile</a>
<a href="logout.php">Logout</a>
</div>
</div>

<div class="main">

<div class="topbar">
<h1>Book Consultation</h1>
<p class="small">Select a faculty and schedule your appointment.</p>
</div>

<div class="container">

<div class="card">

<h3>Select Faculty</h3>
<br>

<div class="faculty"
onclick="selectFaculty(1,'Sir Aris Dela Rea','Tuesday | 9:00 AM - 12:00 PM',this)">
<div>
<b>Sir Aris Dela Rea</b><br>
<span class="small">System Administrator · Professor</span>
</div>
<span class="badge">Available</span>
</div>

<div class="faculty"
onclick="selectFaculty(2,'Sir Christopher Jay De Claro','Monday | 1:00 PM - 3:00 PM',this)">
<div>
<b>Sir Christopher Jay De Claro</b><br>
<span class="small">Web Development · Professor</span>
</div>
<span class="badge">Available</span>
</div>

<div class="faculty"
onclick="selectFaculty(3,'Maam Melanie Castillo','Wednesday | 2:00 PM - 5:00 PM',this)">
<div>
<b>Ma'am Melanie Castillo</b><br>
<span class="small">Information Management · Professor</span>
</div>
<span class="badge">Available</span>
</div>

<div class="faculty"
onclick="selectFaculty(4,'Maam Marie Nel Velasco','Thursday | 10:00 AM - 12:00 PM',this)">
<div>
<b>Ma'am Marie Nel Velasco</b><br>
<span class="small">Object-Oriented Programming · Professor</span>
</div>
<span class="badge">Available</span>
</div>

</div>

<div class="card">

<h3>Appointment Form</h3>

<form method="POST" action="save_appointment.php">

<label>Selected Faculty</label>
<input type="text" id="faculty_name" name="faculty_name" readonly required>
<input type="hidden" id="faculty_id" name="faculty_id">

<label>Available Schedule</label>
<input
type="text"
id="schedule"
name="schedule"
readonly
required>

<label>Preferred Date</label>
<input type="date"
name="appointment_date"
required>

<label>Concern</label>
<textarea
name="concern"
rows="4"
required></textarea>

<button type="submit">
Submit Request
</button>

</form>

</div>

</div>
</div>

<script>
function selectFaculty(id, name, schedule, element){

    let cards = document.querySelectorAll('.faculty');

    cards.forEach(function(card){
        card.classList.remove('active');
    });

    element.classList.add('active');

    document.getElementById('faculty_id').value = id;
    document.getElementById('faculty_name').value = name;
    document.getElementById('schedule').value = schedule;

    console.log("Faculty ID:", id);
    console.log("Faculty Name:", name);
}
</script>

</body>
</html>