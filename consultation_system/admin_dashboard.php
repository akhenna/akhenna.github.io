<?php
session_start();
include 'db.php'; 

$students_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM students");
$total_students = ($students_res) ? mysqli_fetch_assoc($students_res)['total'] : 0;

$faculty_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM faculty");
$total_faculty = ($faculty_res) ? mysqli_fetch_assoc($faculty_res)['total'] : 4;
if($total_faculty == 0) {
    $total_faculty = 4; 
}

$appointments_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments");
$total_appointments = ($appointments_res) ? mysqli_fetch_assoc($appointments_res)['total'] : 0;

$pending_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE LOWER(status)='pending'");
$pending_count = ($pending_res) ? mysqli_fetch_assoc($pending_res)['total'] : 0;

$approved_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE LOWER(status)='approved'");
$approved_count = ($approved_res) ? mysqli_fetch_assoc($approved_res)['total'] : 0;

$completed_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE LOWER(status)='completed'");
$completed_count = ($completed_res) ? mysqli_fetch_assoc($completed_res)['total'] : 0;

$recent_sql = "SELECT a.id, a.concern, a.appointment_date, a.status, a.student_id,
                      MIN(s.first_name) as first_name, MIN(s.last_name) as last_name 
               FROM appointments a 
               INNER JOIN students s ON a.student_id = s.student_id 
               GROUP BY a.id, a.concern, a.appointment_date, a.status, a.student_id
               ORDER BY a.id DESC LIMIT 6";
$recent_query = mysqli_query($conn, $recent_sql);
$has_real_data = ($recent_query && mysqli_num_rows($recent_query) > 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Management Portal</title>
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
    padding:8px 16px;
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


.welcome{
    margin-bottom:20px;
}

.welcome h2{
    font-size:22px;
    font-weight:700;
}

.small{
    font-size:13px;
    color:#64748b;
}


.stats-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-bottom:30px;
}

.stat-card{
    background:#fff;
    padding:22px;
    border-radius:14px;
    border:1px solid #eee;
}

.card-icon{
    width:42px;
    height:42px;
    border-radius:10px;
    margin-bottom:12px;
}

.icon-blue{ background:#dbeafe; }
.icon-purple{ background:#ede9fe; }
.icon-pink{ background:#fce7f3; }
.icon-yellow{ background:#fef3c7; }
.icon-sky{ background:#e0f2fe; }
.icon-green{ background:#dcfce7; }

.stat-card h3{
    font-size:28px;
    font-weight:700;
}

.stat-card p{
    font-size:13px;
    color:#64748b;
}


.bottom-grid{
    display:grid;
    grid-template-columns:1.5fr 1fr;
    gap:20px;
}

.panel{
    background:#fff;
    padding:22px;
    border-radius:14px;
    border:1px solid #eee;
}

.panel-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px;
}

.panel-header h4{
    font-size:16px;
    font-weight:600;
}

.view-all-btn{
    font-size:13px;
    color:#800000;
    font-weight:600;
    text-decoration:none;
}


.recent-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:14px;
    background:#f8fafc;
    border:1px solid #eee;
    border-radius:10px;
    margin-bottom:10px;
}

.recent-item-left{
    display:flex;
    align-items:center;
    gap:12px;
}

.avatar-placeholder{
    width:40px;
    height:40px;
    background:#e5e7eb;
    border-radius:8px;
}

.recent-details b{
    font-size:13px;
}

.recent-details span{
    font-size:11px;
    color:#94a3b8;
}


.mini-status{
    font-size:11px;
    padding:4px 10px;
    border-radius:20px;
    font-weight:600;
    text-transform:uppercase;
}

.pending{ background:#fef3c7; color:#b45309; }
.approved{ background:#dbeafe; color:#1d4ed8; }
.completed{ background:#dcfce7; color:#15803d; }
.rejected{ background:#fee2e2; color:#b91c1c; }


.actions-2x2-grid{
    display:grid;
    grid-template-columns:1fr;
    gap:12px;
}

.action-card{
    display:flex;
    align-items:center;
    gap:12px;
    background:#f8fafc;
    padding:16px;
    border-radius:12px;
    text-decoration:none;
    border:1px solid #eee;
    transition:.2s;
}

.action-card:hover{
    background:#fff;
    transform:translateY(-2px);
}

.action-card b{
    display:block;
    font-size:14px;
    color:#1e293b;
}

.action-card span{
    font-size:12px;
    color:#64748b;
}


@media (max-width:1150px){
    .stats-grid{
        grid-template-columns:repeat(2,1fr);
    }

    .bottom-grid{
        grid-template-columns:1fr;
    }
}

@media (max-width:768px){

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

    .stats-grid{
        grid-template-columns:1fr;
    }

    .bottom-grid{
        grid-template-columns:1fr;
    }
}
    </style>
</head>

<body>

    <div class="sidebar">
        <h2>PUP Consultation</h2>
        <div class="portal-label">Admin Portal</div>
        <div class="menu">
            <a href="admin_dashboard.php" class="active">Dashboard</a>
            <a href="admin_students.php">Students</a>
            <a href="admin_faculty.php">Faculty</a>
            <a href="admin_appointments.php">Appointments</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="main">

        <div class="topbar">
            <h1>Admin Dashboard</h1>
            <div class="user">
                <span>Admin</span>
                <span class="badge"><?= $pending_count ?></span>
            </div>
        </div>
            
        <div class="welcome">
            <p class="small">System overview and statistics.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="card-icon icon-blue"></div>
                <h3><?= $total_students ?></h3>
                <p>Total Students</p>
            </div>
            <div class="stat-card">
                <div class="card-icon icon-purple"></div>
                <h3><?= $total_faculty ?></h3>
                <p>Total Faculty</p>
            </div>
            <div class="stat-card">
                <div class="card-icon icon-pink"></div>
                <h3><?= $total_appointments ?></h3>
                <p>Total Appointments</p>
            </div>
            <div class="stat-card">
                <div class="card-icon icon-yellow"></div>
                <h3><?= $pending_count ?></h3>
                <p>Pending</p>
            </div>
            <div class="stat-card">
                <div class="card-icon icon-sky"></div>
                <h3><?= $approved_count ?></h3>
                <p>Approved</p>
            </div>
            <div class="stat-card">
                <div class="card-icon icon-green"></div>
                <h3><?= $completed_count ?></h3>
                <p>Completed</p>
            </div>
        </div>

        <div class="bottom-grid">
            
            <div class="panel">
                <div class="panel-header">
                    <h4>Recent Appointments</h4>
                    <a href="admin_appointments.php" class="view-all-btn">View all</a>
                </div>

                <?php if($has_real_data): ?>
                    <?php while($row = mysqli_fetch_assoc($recent_query)): ?>
                        <?php 
                            $student_display_name = (!empty($row['first_name']) && !empty($row['last_name'])) 
                                ? htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) 
                                : 'Student ID: ' . htmlspecialchars($row['student_id']);
                        ?>
                        <div class="recent-item">
                            <div class="recent-item-left">
                                <div class="avatar-placeholder"></div>
                                <div class="recent-details">
                                    <b><?= htmlspecialchars($row['concern']) ?></b>
                                    <span>
                                        By: <?= $student_display_name ?> | 
                                        <?= date('Y-m-d', strtotime($row['appointment_date'])) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="mini-status <?= strtolower($row['status']) ?>">
                                <?= htmlspecialchars($row['status']) ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 20px; color: #94a3b8; font-size: 14px;">
                        No recent appointments found in the database.
                    </div>
                <?php endif; ?>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h4>Quick Actions</h4>
                </div>
                
                <div class="actions-2x2-grid">
                    <a href="admin_students.php" class="action-card">
                        <div class="card-icon icon-blue"></div>
                        <div>
                            <b>Students</b>
                            <span>Manage accounts</span>
                        </div>
                    </a>

                    <a href="admin_faculty.php" class="action-card">
                        <div class="card-icon icon-purple"></div>
                        <div>
                            <b>Faculty</b>
                            <span>Manage profiles</span>
                        </div>
                    </a>

                    <a href="admin_appointments.php" class="action-card">
                        <div class="card-icon icon-pink"></div>
                        <div>
                            <b>Appointments</b>
                            <span>View all</span>
                        </div>
                    </a>
                        </div>
                    </a>
                </div>
            </div>

        </div>

    </div> 
</body>
</html>