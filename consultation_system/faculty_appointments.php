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
    // Fallback names kung walang appointment history pa ang account
    if($faculty_id == 1) $faculty_name = "Sir Aris Dela Rea";
    else if($faculty_id == 2) $faculty_name = "Sir Christopher Jay De Claro";
    else if($faculty_id == 3) $faculty_name = "Maam Melanie Castillo";
    else if($faculty_id == 4) $faculty_name = "Maam Marie Nel Velasco";
}

// Bilang ng Notifications badge
$notif_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE faculty_id='$faculty_id'"));

// 2. FILTER MANAGEMENT (All, Pending, Approved, Completed)
$current_filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';

// SQL Query na naka-JOIN sa students table para makuha ang kumpletong detalye ng estudyante
$sql = "SELECT a.*, s.first_name, s.last_name, s.course, s.year_level 
        FROM appointments a 
        LEFT JOIN students s ON a.student_id = s.student_id 
        WHERE a.faculty_id='$faculty_id'";

if (in_array(strtolower($current_filter), ['pending', 'approved', 'completed'])) {
    $filter_lower = strtolower($current_filter);
    $sql .= " AND a.status='$filter_lower'";
}

$sql .= " ORDER BY a.id DESC";
$appointments_query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments | Faculty Portal</title>
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

        .welcome {
            margin-bottom: 25px;
        }

        .small {
            font-size: 13px;
            color: #64748b;
        }

        /* ==========================================
           FILTER TABS SYSTEM
           ========================================== */
        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            margin-top: 15px;
        }

        .tab-btn {
            padding: 8px 20px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            background: #e2e8f0;
            color: #475569;
            transition: all 0.2s ease;
        }

        .tab-btn:hover {
            background: #cbd5e1;
            color: #1e293b;
        }

        .tab-btn.active {
            background: #800000;
            color: white;
            box-shadow: 0 4px 12px rgba(128, 0, 0, 0.15);
        }

        /* ==========================================
           PANEL AND CONTENT CARD BLOCK
           ========================================== */
        .panel {
            background: white;
            padding: 28px;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            width: 100%;
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
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
            background: #f8fafc;
            margin-bottom: 16px;
            transition: all 0.2s ease;
            border-left: 4px solid #cbd5e1;
        }

        /* Dynamic left-border lines based on status */
        .item:has(.status.pending) { border-left-color: #f59e0b; }
        .item:has(.status.approved) { border-left-color: #38bdf8; }
        .item:has(.status.completed) { border-left-color: #4ade80; }

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
            min-width: 110px;
            text-align: center;
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

        @media (max-width: 900px) {
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
            .filter-tabs { flex-wrap: wrap; }
            .item { flex-direction: column; align-items: flex-start; gap: 14px; }
            .status { width: 100%; }
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
            <h1>Faculty Dashboard</h1>
            <div class="user">
                <span><?= htmlspecialchars($faculty_name) ?></span>
                <span class="badge"><?= $notif_count ?></span>
            </div>
        </div>
            
        <div class="welcome">
            <p style="font-size: 18px; color: #0f172a; font-weight: 600; margin-bottom: 4px;">Appointments</p>
            <p class="small">Manage and filter student consultation requests systematically.</p>
        </div>

        <div class="filter-tabs">
            <a href="faculty_appointments.php?filter=All" class="tab-btn <?= strtolower($current_filter) == 'all' ? 'active' : '' ?>">All</a>
            <a href="faculty_appointments.php?filter=Pending" class="tab-btn <?= strtolower($current_filter) == 'pending' ? 'active' : '' ?>">Pending</a>
            <a href="faculty_appointments.php?filter=Approved" class="tab-btn <?= strtolower($current_filter) == 'approved' ? 'active' : '' ?>">Approved</a>
            <a href="faculty_appointments.php?filter=Completed" class="tab-btn <?= strtolower($current_filter) == 'completed' ? 'active' : '' ?>">Completed</a>
        </div>

        <div class="panel">
            <h3>Consultation Requests List (<?= htmlspecialchars($current_filter) ?>)</h3>

            <?php if(mysqli_num_rows($appointments_query) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($appointments_query)): ?>

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
                            <small style="color: #800000; font-weight:600; font-size: 13px; display:block; background: #fff5f5; padding: 6px 10px; border-radius: 6px; margin-top: 8px; border: 1px solid #ffe4e4; width: 100%;">
                                <b>Concern:</b> <?= htmlspecialchars($row['concern']) ?>
                            </small>
                        </div>

                        <?php $status_class = strtolower($row['status']); ?>
                        <div class="status <?= $status_class ?>"><?= htmlspecialchars($row['status']) ?></div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-data">No <?= htmlspecialchars(strtolower($current_filter)) ?> requests found.</p>
            <?php endif; ?>
        </div>

    </div> 
</body>
</html>