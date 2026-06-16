<?php
session_start();
include 'db.php'; 

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$status = isset($_GET['status']) ? $_GET['status'] : 'All';

$query = "SELECT a.*, s.first_name, s.last_name, f.full_name as faculty_name 
          FROM appointments a
          JOIN students s ON a.student_id = s.student_id
          JOIN faculty f ON a.faculty_id = f.faculty_id";

if($status !== 'All'){
    $query .= " WHERE LOWER(a.status) = '" . mysqli_real_escape_string($conn, strtolower($status)) . "'";
}
$query .= " ORDER BY a.id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments | Admin Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
    color:white;
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
    margin-bottom:20px;
}

.topbar h1{
    color:#800000;
    font-size:26px;
}


.filter-nav{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-bottom:20px;
}

.filter-btn{
    padding:10px 14px;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    color:#64748b;
    background:#fff;
    border:1px solid #e2e8f0;
    transition:.2s;
}

.filter-btn:hover{
    background:#f8fafc;
}

.filter-btn.active{
    background:#800000;
    color:white;
    border-color:#800000;
}


.panel{
    background:white;
    border-radius:12px;
    padding:20px;
    border:1px solid #eee;
}


table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#f8fafc;
}

thead th{
    text-align:left;
    padding:14px;
    font-size:12px;
    color:#64748b;
    text-transform:uppercase;
    border-bottom:2px solid #e2e8f0;
}

tbody td{
    padding:14px;
    font-size:14px;
    font-weight:500;
    border-bottom:1px solid #f1f5f9;
    color:#1e293b;
}

tbody tr:hover{
    background:#f8fafc;
}


.mini-status{
    display:inline-block;
    padding:5px 10px;
    border-radius:20px;
    font-size:11px;
    font-weight:700;
    text-transform:uppercase;
}

.pending{
    background:#fef3c7;
    color:#b45309;
}

.approved{
    background:#dbeafe;
    color:#1d4ed8;
}

.completed{
    background:#dcfce7;
    color:#15803d;
}

.rejected{
    background:#fee2e2;
    color:#b91c1c;
}


@media (max-width: 992px){
    .main{
        padding:20px;
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

    .topbar h1{
        font-size:22px;
    }

    thead th{
        font-size:11px;
        padding:10px;
    }

    tbody td{
        font-size:13px;
        padding:10px;
    }

    .filter-btn{
        font-size:12px;
        padding:8px 12px;
    }

    .panel{
        padding:15px;
    }
}
</style>
</head>


<body>

    <div class="sidebar">
        <h2>PUP Consultation</h2>
        <div class="portal-label">Admin Portal</div>
        <div class="menu">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_students.php">Students</a>
            <a href="admin_faculty.php">Faculty</a>
            <a href="admin_appointments.php" class="active">Appointments</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="main">
        <div class="topbar" style="margin-bottom: 20px;">
            <h1>Appointments</h1>
        </div>

        <div class="filter-nav">
            <a href="?status=All" class="filter-btn <?= $status == 'All' ? 'active' : '' ?>">All</a>
            <a href="?status=Pending" class="filter-btn <?= $status == 'Pending' ? 'active' : '' ?>">Pending</a>
            <a href="?status=Approved" class="filter-btn <?= $status == 'Approved' ? 'active' : '' ?>">Approved</a>
            <a href="?status=Completed" class="filter-btn <?= $status == 'Completed' ? 'active' : '' ?>">Completed</a>
        </div>

        <div class="panel">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Faculty</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td data-label="Student"><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                            <td data-label="Faculty"><?= htmlspecialchars($row['faculty_name']) ?></td>
                            <td data-label="Date"><?= htmlspecialchars($row['appointment_date']) ?></td>
                            <td data-label="Status"><span class="mini-status <?= strtolower($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 