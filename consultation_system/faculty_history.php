<?php
session_start();
include 'db.php';

// 1. VERIFY LOGIN
if(!isset($_SESSION['faculty_id'])){
    header("Location: faculty_login.php");
    exit();
}

$faculty_id = mysqli_real_escape_string($conn, $_SESSION['faculty_id']);

// Kuhanin ang pangalan ng Faculty para sa Topbar base sa session identity
$faculty_name = "Faculty Member";
$name_query = mysqli_query($conn, "SELECT faculty_name FROM appointments WHERE faculty_id='$faculty_id' LIMIT 1");
if($name_row = mysqli_fetch_assoc($name_query)){
    $faculty_name = $name_row['faculty_name'];
} else {
    // Fallback names batay sa ID
    if($faculty_id == 1) $faculty_name = "Sir Aris Dela Rea";
    else if($faculty_id == 2) $faculty_name = "Sir Christopher Jay De Claro";
    else if($faculty_id == 3) $faculty_name = "Maam Melanie Castillo";
    else if($faculty_id == 4) $faculty_name = "Maam Marie Nel Velasco";
}

// Bilang ng Notifications badge
$notif_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE faculty_id='$faculty_id'"));

// 2. QUERY FOR HISTORY (Kukuha ng 'completed' at 'rejected/cancelled' na status kung mayroon man)
$sql = "SELECT a.*, s.first_name, s.last_name 
        FROM appointments a 
        LEFT JOIN students s ON a.student_id = s.student_id 
        WHERE a.faculty_id='$faculty_id' AND a.status='completed'
        ORDER BY a.appointment_date DESC, a.id DESC";

$history_query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation History | Faculty Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ==========================================
           GLOBAL & BASE STYLES
           ========================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            background: #f8fafc; /* Modern slate-white hue layout background */
            min-height: 100vh;
            color: #1e293b;
        }

        /* ==========================================
           PREMIUM SIDEBAR SYSTEM
           ========================================== */
        .sidebar {
            width: 270px;
            background: #800000;
            color: white;
            min-height: 100vh;
            padding: 30px 20px;
            position: fixed;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.05);
            z-index: 100;
        }

        .sidebar h2 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            padding-left: 10px;
        }

        .portal-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 35px;
            padding-left: 10px;
            display: block;
            font-weight: 600;
        }

        .menu a {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .menu a:hover {
            color: white;
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }

        .menu a.active {
            color: white;
            background: rgba(255, 255, 255, 0.15);
            font-weight: 600;
            box-shadow: inset 4px 0 0 white;
            padding-left: 20px;
        }

        /* ==========================================
           MAIN CONTAINER CONTENT AREA
           ========================================== */
        .main {
            flex: 1;
            padding: 40px;
            margin-left: 270px;
            transition: all 0.3s ease;
        }

        .topbar {
            margin-bottom: 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .topbar h1 {
            color: #800000;
            font-size: 14px;
            font-weight: 600;
        }

        .user {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            color: #334155;
            background: white;
            padding: 8px 18px;
            border-radius: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            border: 1px solid #f1f5f9;
        }

        .badge {
            background: #ef4444;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 700;
        }

        .welcome {
            margin-bottom: 30px;
        }

        .welcome h2 {
            font-size: 24px;
            color: #0f172a;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .small {
            font-size: 13px;
            color: #64748b;
        }

        /* ==========================================
           HISTORY PANEL CONTAINER
           ========================================== */
        .panel {
            background: white;
            padding: 35px 28px;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            width: 100%;
        }

        /* ==========================================
           EXACT MATCH READDY.AI ITEM ROW STYLE
           ========================================== */
        .history-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .history-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .history-item:first-child {
            padding-top: 0;
        }

        .left-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* User Icon/Avatar Placeholder Box */
        .avatar-box {
            width: 48px;
            height: 48px;
            background: #f0fdf4; /* Light green block base color */
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #16a34a;
            font-weight: 600;
            font-size: 16px;
        }

        .details b {
            color: #0f172a;
            font-size: 15px;
            font-weight: 600;
            display: block;
            margin-bottom: 3px;
        }

        .details .concern-text {
            color: #475569;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .details .date-text {
            color: #94a3b8;
            font-size: 12px;
        }

        /* ==========================================
           STATUS BADGES SYSTEM
           ========================================== */
        .status {
            font-size: 12px;
            padding: 5px 14px;
            border-radius: 20px;
            font-weight: 500;
            text-transform: lowercase;
            letter-spacing: 0.2px;
            text-align: center;
        }

        .status.completed {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .no-data {
            color: #94a3b8;
            font-style: italic;
            padding: 40px 0;
            text-align: center;
            font-size: 14px;
        }

        /* ==========================================
           RESPONSIVE INTERFACE LAYOUTS
           ========================================== */
        @media (max-width: 1024px) {
            .main { padding: 30px; }
        }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar {
                width: 100%;
                min-height: auto;
                text-align: center;
                position: relative;
                padding: 20px;
            }
            .portal-label { margin-bottom: 20px; }
            .menu {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 8px;
            }
            .menu a {
                flex: 1 1 43%;
                text-align: center;
                justify-content: center;
                margin-bottom: 0;
                padding: 10px;
            }
            .main { padding: 20px; margin-left: 0; }
            .topbar { flex-direction: column; gap: 15px; align-items: flex-start; }
            .user { width: 100%; justify-content: space-between; }
            .history-item { flex-direction: column; align-items: flex-start; gap: 16px; }
            .status { align-self: flex-start; width: auto; }
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h2>PUP Consultation</h2>
        <div class="portal-label">Faculty Portal</div>
        <div class="menu">
            <a href="faculty_dashboard.php">Dashboard</a>
            <a href="faculty_appointments.php">Appointments</a>
            <a href="faculty_history.php" class="active">History</a>
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
            
        <div class="welcome">
            <h2>Consultation History</h2>
            <p class="small">View your past consultations and their outcomes.</p>
        </div>

        <div class="panel">
            <?php if(mysqli_num_rows($history_query) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($history_query)): ?>
                    
                    <?php 
                        // Gumawa ng initials para sa Profile Icon Box base sa pangalan ng estudyante
                        $initials = "S";
                        if(!empty($row['first_name'])) {
                            $initials = strtoupper(substr($row['first_name'], 0, 1));
                        }
                    ?>

                    <div class="history-item">
                        <div class="left-content">
                            <div class="avatar-box">
                                <?= $initials ?>
                            </div>
                            
                            <div class="details">
                                <b>
                                    <?php 
                                    if(!empty($row['first_name'])) {
                                        echo htmlspecialchars($row['first_name'].' '.$row['last_name']);
                                    } else {
                                        echo "Student ID: " . htmlspecialchars($row['student_id']);
                                    }
                                    ?>
                                </b>
                                <p class="concern-text"><?= htmlspecialchars($row['concern']) ?></p>
                                <p class="date-text">
                                    <?= htmlspecialchars($row['appointment_date']) ?> at 
                                    <?= date('h:i A', strtotime($row['created_at'])) ?>
                                </p>
                            </div>
                        </div>

                        <div class="status completed">
                            <?= htmlspecialchars($row['status']) ?>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-data">No consultation history records found.</p>
            <?php endif; ?>
        </div>

    </div> 
</body>
</html>