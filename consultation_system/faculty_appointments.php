<?php
session_start();
include 'db.php';

if (!isset($_SESSION['faculty_id'])) {
    header("Location: faculty_login.php");
    exit();
}

$faculty_id = mysqli_real_escape_string($conn, $_SESSION['faculty_id']);

$faculty_name = "Faculty Member";
$name_query = mysqli_query($conn, "SELECT full_name FROM faculty WHERE faculty_id='$faculty_id'");
if ($row = mysqli_fetch_assoc($name_query)) {
    $faculty_name = $row['full_name'];
}

$notif_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE faculty_id='$faculty_id' AND status='pending'"));

$sql = "SELECT a.*, s.first_name, s.last_name 
        FROM appointments a 
        LEFT JOIN students s ON a.student_id = s.student_id 
        WHERE a.faculty_id='$faculty_id' AND a.status IN ('pending', 'approved')
        ORDER BY a.appointment_date ASC";
$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments | Faculty Portal</title>
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
    background:#f8fafc;
    min-height:100vh;
    color:#1e293b;
}

.sidebar{
    width:270px;
    background:#800000;
    color:white;
    min-height:100vh;
    padding:30px 20px;
    position:fixed;
    top:0;
    left:0;
    box-shadow:4px 0 25px rgba(0,0,0,.05);
    z-index:100;
}

.sidebar h2{
    font-size:20px;
    font-weight:700;
    margin-bottom:4px;
    padding-left:10px;
}

.portal-label{
    font-size:11px;
    text-transform:uppercase;
    letter-spacing:1.5px;
    color:rgba(255,255,255,.4);
    margin-bottom:35px;
    padding-left:10px;
    display:block;
    font-weight:600;
}

.menu a{
    display:flex;
    align-items:center;
    color:rgba(255,255,255,.75);
    text-decoration:none;
    padding:14px 16px;
    border-radius:12px;
    margin-bottom:8px;
    font-size:14px;
    font-weight:500;
    transition:.3s;
}

.menu a:hover{
    color:white;
    background:rgba(255,255,255,.08);
    transform:translateX(4px);
}

.menu a.active{
    color:white;
    background:rgba(255,255,255,.15);
    font-weight:600;
    box-shadow:inset 4px 0 0 white;
}

.main{
    flex:1;
    margin-left:270px;
    padding:40px;
}

.topbar{
    margin-bottom:25px;
    padding-bottom:20px;
    border-bottom:1px solid #e2e8f0;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.topbar h1{
    color:#800000;
    font-size:24px;
    font-weight:700;
}

.user{
    display:flex;
    align-items:center;
    gap:10px;
    background:#fff;
    padding:8px 16px;
    border-radius:30px;
    border:1px solid #f1f5f9;
    box-shadow:0 4px 10px rgba(0,0,0,.03);
}

.badge{
    background:#ef4444;
    color:white;
    border-radius:50%;
    padding:2px 8px;
    font-size:11px;
    font-weight:700;
}

.panel{
    background:white;
    padding:24px;
    border-radius:14px;
    border:1px solid #f1f5f9;
    box-shadow:0 4px 15px rgba(0,0,0,.02);
}

.item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:16px;
    border:1px solid #f1f5f9;
    border-radius:12px;
    margin-bottom:12px;
    background:#f8fafc;
    transition:.2s;
}

.item:hover{
    background:#fff;
    box-shadow:0 4px 12px rgba(0,0,0,.04);
}

.details b{
    display:block;
    font-size:15px;
    color:#1e293b;
    margin-bottom:4px;
}

.details p{
    font-size:13px;
    color:#64748b;
}

.btn{
    padding:8px 14px;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    margin-left:8px;
}

.approve{
    background:#dcfce7;
    color:#166534;
}

.decline{
    background:#fee2e2;
    color:#991b1b;
}

.approved-text{
    color:#16a34a;
    font-weight:600;
    font-size:13px;
}

@media (max-width:768px){

    body{
        flex-direction:column;
    }

    .sidebar{
        width:100%;
        position:relative;
        min-height:auto;
        text-align:center;
        padding:20px;
    }

    .menu{
        display:flex;
        flex-wrap:wrap;
        justify-content:center;
        gap:8px;
    }

    .menu a{
        flex:1 1 45%;
        justify-content:center;
        margin-bottom:0;
    }

    .main{
        margin-left:0;
        padding:15px;
    }

    .topbar{
        flex-direction:column;
        align-items:flex-start;
        gap:10px;
    }

    .item{
        flex-direction:column;
        align-items:flex-start;
        gap:10px;
    }

    .btn{
        margin-left:0;
        margin-right:8px;
    }
} 
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>PUP Consultation</h2>
        <div class="portal-label">Faculty Portal</div>
        <div class="menu">
            <a href="faculty_dashboard.php">Dashboard</a>
            <a href="faculty_appointments.php" class="active">Appointments</a>
            <a href="faculty_history.php">History</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="main">
        <div class="topbar">
            <h1>Manage Appointments</h1>
            <div class="user">
                <span><?= htmlspecialchars($faculty_name) ?></span>
                <span class="badge"><?= $notif_count ?></span>
            </div>
        </div>

        <div class="panel">
            <?php if (mysqli_num_rows($query) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <div class="item">
                        <div class="details">
                            <b><?= htmlspecialchars($row['first_name'].' '.$row['last_name']) ?></b>
                            <p>Concern: <?= htmlspecialchars($row['concern']) ?> • Date: <?= htmlspecialchars($row['appointment_date']) ?></p>
                        </div>
                        <div>
                           <?php if ($row['status'] == 'pending'): ?>

    <a href="update_status.php?id=<?= $row['id'] ?>&action=approved" class="btn approve">
        Approve
    </a>

    <a href="update_status.php?id=<?= $row['id'] ?>&action=declined" class="btn decline">
        Decline
    </a>

<?php elseif ($row['status'] == 'approved'): ?>

    <span class="approved-text">Approved</span>

    <a href="update_status.php?id=<?= $row['id'] ?>&action=completed" class="btn complete">
        Complete
    </a>

<?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color:#94a3b8; text-align:center; padding: 20px;">No pending or approved appointments found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>