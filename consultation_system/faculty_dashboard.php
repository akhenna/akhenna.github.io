<?php
session_start();
include 'db.php';

// 1. VERIFY LOGIN
if(!isset($_SESSION['faculty_id'])){
    header("Location: faculty_login.php");
    exit();
}

$faculty_id = mysqli_real_escape_string($conn, $_SESSION['faculty_id']); 

/* 2. REAL-TIME STATS */
$total_query = mysqli_query($conn, "SELECT id FROM appointments WHERE faculty_id='$faculty_id'");
$total = mysqli_num_rows($total_query);

$pending_query = mysqli_query($conn, "SELECT id FROM appointments WHERE faculty_id='$faculty_id' AND status='pending'");
$pending = mysqli_num_rows($pending_query);

$approved_query = mysqli_query($conn, "SELECT id FROM appointments WHERE faculty_id='$faculty_id' AND status='approved'");
$approved = mysqli_num_rows($approved_query);

$completed_query = mysqli_query($conn, "SELECT id FROM appointments WHERE faculty_id='$faculty_id' AND status='completed'");
$completed = mysqli_num_rows($completed_query);

/* 3. PENDING REQUESTS LIST */
$pendingRequests = mysqli_query($conn, "
    SELECT a.*, s.first_name, s.last_name, s.course, s.year_level
    FROM appointments a
    LEFT JOIN students s ON a.student_id = s.student_id
    WHERE a.faculty_id='$faculty_id' AND a.status='pending'
    ORDER BY a.created_at DESC
    LIMIT 5
");

/* 4. NOTIFICATIONS LIST */
$notifications = mysqli_query($conn, "
    SELECT * FROM appointments
    WHERE faculty_id='$faculty_id'
    ORDER BY created_at DESC
    LIMIT 5
");
$notif_count = mysqli_num_rows($notifications);

/* 5. GET FACULTY NAME */
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
            background: #f8fafc; /* Modern slate-white layout background */
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
            font-size: 28px;
            font-weight: 700;
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

        .small {
            font-size: 13px;
            color: #64748b;
        }

        /* ==========================================
           DASHBOARD STATS BLOCK CARDS
           ========================================== */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 25px;
            margin-bottom: 35px;
        }

        .stats .card {
            text-align: left;
            position: relative;
            overflow: hidden;
            border-top: 4px solid #800000;
        }

        .stats .card h2 {
            font-size: 36px;
            color: #1e293b;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 6px;
        }

        .stats .card p {
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
        }

        /* ==========================================
           CONTAINER GRID PANELS
           ========================================== */
        .grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
        }

        .card, .panel {
            background: white;
            padding: 28px;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
        }

        .panel h3 {
            margin-bottom: 20px;
            color: #0f172a;
            font-size: 18px;
            font-weight: 600;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 12px;
        }

        /* ==========================================
           LIST CARDS ITEMS COMPONENTS
           ========================================== */
        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
            background: #f8fafc;
            margin-bottom: 12px;
            transition: all 0.2s ease;
            border-left: 4px solid #cbd5e1;
        }

        .panel .item:has(.status.pending) {
            border-left-color: #f59e0b; /* Elegant orange indicator for pending entries */
        }

        .item:hover {
            transform: translateY(-2px);
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            border-color: #e2e8f0;
        }

        /* ==========================================
           BADGES STATUS COLORS
           ========================================== */
        .status {
            font-size: 12px;
            padding: 6px 14px;
            border-radius: 30px;
            font-weight: 600;
            text-transform: capitalize;
            letter-spacing: 0.3px;
        }

        .status.pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status.approved {
            background: #e0f2fe;
            color: #0369a1;
        }

        .status.completed {
            background: #dcfce7;
            color: #15803d;
        }

        .no-data {
            color: #94a3b8;
            font-style: italic;
            padding: 30px 0;
            text-align: center;
            font-size: 14px;
        }

        /* ==========================================
           SIDE COMPONENT MANAGE BOX
           ========================================== */
        .manage {
            margin-top: 25px;
            background: linear-gradient(135deg, #800000 0%, #4a0000 100%);
            color: white;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(128, 0, 0, 0.15);
        }

        .manage h3 {
            color: white !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
            font-size: 18px;
            margin-bottom: 10px;
            padding-bottom: 10px;
        }

        .manage p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.5;
        }

        .manage a {
            display: inline-block;
            margin-top: 18px;
            background: white;
            color: #800000;
            padding: 10px 22px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .manage a:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
            background: #fff5f5;
        }

        /* ==========================================
           RESPONSIVE INTERFACE LAYOUTS
           ========================================== */
        @media (max-width: 1024px) {
            .main { padding: 30px; }
            .stats { grid-template-columns: repeat(2, 1fr); gap: 15px; }
        }

        @media (max-width: 900px) {
            .grid { grid-template-columns: 1fr; }
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
            .item { flex-direction: column; align-items: flex-start; gap: 12px; }
            .status { width: 100%; text-align: center; }
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