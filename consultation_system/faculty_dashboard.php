<?php
session_start();
include 'db.php';


if(!isset($_SESSION['faculty_id'])){
    header("Location: faculty_login.php");
    exit();
}

$faculty_id = mysqli_real_escape_string($conn, $_SESSION['faculty_id']); 

$total_query = mysqli_query($conn, "SELECT id FROM appointments WHERE faculty_id='$faculty_id'");
$total = mysqli_num_rows($total_query);

$pending_query = mysqli_query($conn, "SELECT id FROM appointments WHERE faculty_id='$faculty_id' AND status='pending'");
$pending = mysqli_num_rows($pending_query);

$approved_query = mysqli_query($conn, "SELECT id FROM appointments WHERE faculty_id='$faculty_id' AND status='approved'");
$approved = mysqli_num_rows($approved_query);

$completed_query = mysqli_query($conn, "SELECT id FROM appointments WHERE faculty_id='$faculty_id' AND status='completed'");
$completed = mysqli_num_rows($completed_query);


$pendingRequests = mysqli_query($conn, "
    SELECT a.*, s.first_name, s.last_name, s.course, s.year_level
    FROM appointments a
    LEFT JOIN students s ON a.student_id = s.student_id
    WHERE a.faculty_id='$faculty_id' AND a.status='pending'
    ORDER BY a.created_at DESC
    LIMIT 5
");

$notifications = mysqli_query($conn, "
    SELECT * FROM appointments
    WHERE faculty_id='$faculty_id'
    ORDER BY created_at DESC
    LIMIT 5
");
$notif_count = mysqli_num_rows($notifications);


$faculty_name = "Faculty Member";
$name_query = mysqli_query($conn, "SELECT faculty_name FROM appointments WHERE faculty_id='$faculty_id' LIMIT 1");
if($name_row = mysqli_fetch_assoc($name_query)){
    $faculty_name = $name_row['faculty_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard | PUP AppointEd</title>
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
    color:#1e293b;
}

.sidebar{
    width:270px;
    background:#800000;
    color:#fff;
    min-height:100vh;
    padding:25px 20px;
}

.sidebar h2{
    font-size:20px;
    font-weight:800;
    margin-bottom:5px;
}

.portal-label{
    font-size:11px;
    letter-spacing:1.5px;
    text-transform:uppercase;
    opacity:.7;
    margin-bottom:30px;
}

.menu a{
    display:block;
    color:rgba(255,255,255,.85);
    text-decoration:none;
    padding:12px 14px;
    border-radius:10px;
    margin-bottom:8px;
    font-size:14px;
    transition:.2s;
}

.menu a:hover{
    background:rgba(255,255,255,.15);
    transform:translateX(4px);
}

.menu a.active{
    background:rgba(255,255,255,.2);
    font-weight:600;
}

.main{
    flex:1;
    padding:30px;
}

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.topbar h1{
    color:#800000;
    font-size:24px;
}

.user{
    display:flex;
    align-items:center;
    gap:10px;
    background:#fff;
    padding:8px 14px;
    border-radius:30px;
    border:1px solid #eee;
    font-weight:600;
}

.badge{
    background:#ef4444;
    color:#fff;
    padding:2px 8px;
    border-radius:20px;
    font-size:11px;
}

.welcome p:first-child{
    font-size:18px;
    font-weight:600;
    color:#0f172a;
}

.small{
    font-size:13px;
    color:#64748b;
}

.stats{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:18px;
    margin:25px 0 30px;
}

.stats .card{
    background:#fff;
    padding:20px;
    border-radius:14px;
    border:1px solid #eee;
    border-top:4px solid #800000;
}

.stats .card h2{
    font-size:30px;
    font-weight:700;
    color:#1e293b;
}

.stats .card p{
    font-size:13px;
    color:#64748b;
}
.stats .card:nth-child(2){ border-top-color:#f59e0b; }
.stats .card:nth-child(3){ border-top-color:#38bdf8; }
.stats .card:nth-child(4){ border-top-color:#4ade80; }

.grid{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:20px;
}

.panel{
    background:#fff;
    padding:22px;
    border-radius:14px;
    border:1px solid #eee;
}

.panel h3{
    font-size:16px;
    font-weight:600;
    margin-bottom:15px;
}

.item{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    padding:14px;
    border-radius:12px;
    background:#f8fafc;
    border:1px solid #eee;
    margin-bottom:10px;
    transition:.2s;
}

.item:hover{
    background:#fff;
    transform:translateY(-2px);
}
.status{
    font-size:11px;
    padding:5px 10px;
    border-radius:20px;
    font-weight:600;
    text-transform:uppercase;
}

.status.pending{ background:#fef3c7; color:#b45309; }
.status.approved{ background:#dbeafe; color:#1d4ed8; }


.no-data{
    text-align:center;
    color:#94a3b8;
    font-size:13px;
    padding:20px;
}


@media (max-width: 1024px){
    .stats{
        grid-template-columns:repeat(2,1fr);
    }

    .grid{
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

    .topbar{
        flex-direction:column;
        align-items:flex-start;
        gap:10px;
    }

    .stats{
        grid-template-columns:1fr;
    }

    .item{
        flex-direction:column;
        gap:10px;
    }

    .status{
        align-self:flex-start;
    }
}
    </style>
</head>

<body>

    <div class="sidebar">
        <h2>PUP Consultation</h2>
        <div class="portal-label">Faculty Portal</div>
        <div class="menu">
            <a href="faculty_dashboard.php" class="active">Dashboard</a>
            <a href="faculty_appointments.php">Appointments</a>
            <a href="faculty_history.php">History</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="main">

        <div class="topbar">
            <h1>Faculty Dashboard</h1>
            <div class="user">
                <span><?= htmlspecialchars($faculty_name) ?></span>
                <span class="badge"><?= $notif_count ?></span>
            </div>
        </div>

        <div class="content" style="padding: 0;">
            <div class="welcome">
                <p style="font-size: 18px; color: #0f172a; font-weight: 600; margin-bottom: 4px;">
                    Welcome, <?= htmlspecialchars($faculty_name) ?>!
                </p>
                <p class="small">Here is your consultation overview today.</p>
            </div>

            <div class="stats">
                <div class="card">
                    <h2><?= $total ?></h2>
                    <p>Total Requests</p>
                </div>
                <div class="card" style="border-top-color: #f59e0b;">
                    <h2 style="color: #d97706;"><?= $pending ?></h2>
                    <p>Pending</p>
                </div>
                <div class="card" style="border-top-color: #38bdf8;">
                    <h2 style="color: #0284c7;"><?= $approved ?></h2>
                    <p>Approved</p>
                </div>
                <div class="card" style="border-top-color: #4ade80;">
                    <h2 style="color: #16a34a;"><?= $completed ?></h2>
                    <p>Completed</p>
                </div>
            </div>

            <div class="grid">

                <div class="panel">
                    <h3>Pending Requests</h3>

                    <?php if(mysqli_num_rows($pendingRequests) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($pendingRequests)): ?>
                            <div class="item">
                                <div>
                                    <b style="color: #1e293b; font-size: 15px; font-weight: 600;">
                                        <?php 
                                        if(!empty($row['first_name'])) {
                                            echo htmlspecialchars($row['first_name'].' '.$row['last_name']);
                                        } else {
                                            echo "Student ID: " . htmlspecialchars($row['student_id']);
                                        }
                                        ?>
                                    </b><br>
                                    <span style="color:#64748b; font-size: 13px; display: inline-block; margin-bottom: 6px;">
                                        <?= (!empty($row['course'])) ? htmlspecialchars($row['course'].' - '.$row['year_level']) : 'No student profile record found' ?>
                                    </span><br>
                                    <small class="small" style="display:block; margin-bottom: 2px;">
                                        <b style="color: #475569;">Schedule:</b> <?= htmlspecialchars($row['schedule']) ?>
                                    </small>
                                    <small class="small" style="display:block; margin-bottom: 4px;">
                                        <b style="color: #475569;">Date:</b> <?= htmlspecialchars($row['appointment_date']) ?>
                                    </small>
                                    <small style="color: #800000; font-weight:600; font-size: 13px; display:block; background: #fff5f5; padding: 4px 8px; border-radius: 6px; margin-top: 6px; border: 1px solid #ffe4e4;">
                                        <b>Concern:</b> <?= htmlspecialchars($row['concern']) ?>
                                    </small>
                                </div>
                                <div class="status pending"><?= htmlspecialchars($row['status']) ?></div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="no-data">No pending requests found.</p>
                    <?php endif; ?>
                </div>

                <div>
                    <div class="panel">
                        <h3>Notifications</h3>

                        <?php if($notif_count > 0): ?>
                            <?php while($n = mysqli_fetch_assoc($notifications)): ?>
                                <div class="item" style="border-left: 3px solid #ef4444; padding: 12px 15px;">
                                    <div>
                                        <span style="font-size: 13px; color: #334155; font-weight: 500;">New appointment request.</span><br>
                                        <small style="color: #94a3b8; font-size: 11px;"><?= htmlspecialchars($n['created_at']) ?></small>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="no-data">No recent notifications.</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div> 
        </div> 
    </div> 
</body>
</html>