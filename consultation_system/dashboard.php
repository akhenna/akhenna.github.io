<?php
session_start();
include 'db.php';

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$userQuery = mysqli_query($conn, "SELECT * FROM students WHERE student_id='$student_id'");
$user = mysqli_fetch_assoc($userQuery);

$name = $user['first_name'].' '.$user['last_name'];


$total = mysqli_num_rows(mysqli_query($conn, 
    "SELECT id FROM appointments WHERE student_id='$student_id' AND student_id != '' AND student_id != '0' AND student_id IS NOT NULL"));

$pending = mysqli_num_rows(mysqli_query($conn, 
    "SELECT id FROM appointments WHERE student_id='$student_id' AND status='pending' AND student_id != '' AND student_id != '0' AND student_id IS NOT NULL"));

$approved = mysqli_num_rows(mysqli_query($conn, 
    "SELECT id FROM appointments WHERE student_id='$student_id' AND status='approved' AND student_id != '' AND student_id != '0' AND student_id IS NOT NULL"));

$completed = mysqli_num_rows(mysqli_query($conn, 
    "SELECT id FROM appointments WHERE student_id='$student_id' AND status='completed' AND student_id != '' AND student_id != '0' AND student_id IS NOT NULL"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

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
    font-weight:700;
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

.topbar h1{
    color:#800000;
}

.user{
    display:flex;
    align-items:center;
    gap:10px;
    margin-top:10px;
}

.avatar{
    width:40px;
    height:40px;
    border-radius:50%;
    background:#800000;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
}

.cards{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:15px;
    margin-top:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:12px;
    border:1px solid #eee;
}

.card h2{
    color:#800000;
    font-size:28px;
}

.content{
    margin-top:20px;
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:15px;
}

.box{
    background:white;
    padding:20px;
    border-radius:12px;
    border:1px solid #eee;
}

.box h3{
    color:#800000;
    margin-bottom:15px;
}

.appointment{
    display:flex;
    justify-content:space-between;
    padding:10px 0;
    border-bottom:1px solid #eee;
}

.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}

.pending{background:#fff4d6;color:#d97706;}
.approved{background:#dcfce7;color:#15803d;}
.completed{background:#dbeafe;color:#1d4ed8;}

.cta{
    margin-top:20px;
    background:#800000;
    color:white;
    padding:20px;
    border-radius:12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:10px;
}

.btn{
    background:white;
    color:#800000;
    padding:10px 15px;
    border-radius:8px;
    text-decoration:none;
    font-weight:600;
}

@media (max-width: 992px){
    .cards{
        grid-template-columns:repeat(2,1fr);
    }
    .content{
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
    .cards{
        grid-template-columns:1fr;
    }
    .cta{
        flex-direction:column;
        text-align:center;
    }
}
</style>
</head>

<body>

<div class="sidebar">
    <h2>PUP AppointEd</h2>
    <div class="menu">
        <a class="active" href="dashboard.php">Dashboard</a>
        <a href="book_appointment.php">Book Appointment</a>
        <a href="my_appointment.php">My Appointments</a>
        <a href="history.php">History</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="main">
    <div class="topbar">
        <h1>Student Dashboard</h1>
        <div class="user">
            <div class="avatar">
                <?= strtoupper(substr(trim($name),0,1)) ?>
            </div>
            <div>
                <strong><?= htmlspecialchars($name) ?></strong>
            </div>
        </div>
    </div>

    <div class="cards">
        <div class="card">
            <h2><?= $total ?></h2>
            <p>Total Appointments</p>
        </div>
        <div class="card">
            <h2><?= $pending ?></h2>
            <p>Pending</p>
        </div>
        <div class="card">
            <h2><?= $approved ?></h2>
            <p>Approved</p>
        </div>
        <div class="card">
            <h2><?= $completed ?></h2>
            <p>Completed</p>
        </div>
    </div>

    <div class="content">
        <div class="box">
            <h3>Recent Appointments</h3>

            <?php
            $appointments = mysqli_query($conn,
            "SELECT * FROM appointments
             WHERE student_id='$student_id'
               AND student_id != ''
               AND student_id != '0'
               AND student_id IS NOT NULL
             ORDER BY created_at DESC
             LIMIT 5");

            if(mysqli_num_rows($appointments) > 0) {
                while($row = mysqli_fetch_assoc($appointments)){
                ?>
                <div class="appointment">
                    <span><?= htmlspecialchars($row['concern']) ?></span>
                    <span class="badge <?= strtolower($row['status']) ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </div>
                <?php 
                }
            } else {
                echo "<p style='color: #888; font-size: 14px;'>No recent appointments found.</p>";
            }
            ?>
        </div>

        <div class="box">
            <h3>Notifications</h3>
            <p>✔ Welcome to PUP AppointEd</p>
            <p>✔ System Active</p>
        </div>
    </div>

    <div class="cta">
        <div>
            <h2>Need a Consultation?</h2>
            <p>Book an appointment with your faculty.</p>
        </div>
        <a href="book_appointment.php" class="btn">Book Now</a>
    </div>
</div>

</body>
</html>