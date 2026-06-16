<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$search = "";

$query = "SELECT * FROM students ORDER BY student_id DESC";

if(isset($_GET['search']) && !empty($_GET['search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $query = "SELECT * FROM students
              WHERE student_id LIKE '%$search%'
              OR first_name LIKE '%$search%'
              OR last_name LIKE '%$search%'
              OR email LIKE '%$search%'
              ORDER BY student_id DESC";
}

$result = mysqli_query($conn, $query);

$countQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students");
$countRow = mysqli_fetch_assoc($countQuery);
$totalStudents = $countRow['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Management | Admin Portal</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
    margin-bottom:20px;
}

.topbar h1{
    color:#800000;
    font-size:24px;
}

.student-summary{
    background:#fff;
    padding:22px;
    border-radius:14px;
    border:1px solid #eee;
    margin-bottom:20px;
}

.student-summary h2{
    font-size:32px;
    color:#800000;
    font-weight:700;
}

.student-summary p{
    font-size:13px;
    color:#64748b;
}

.panel{
    background:#fff;
    padding:22px;
    border-radius:14px;
    border:1px solid #eee;
}

.panel-header{
    margin-bottom:15px;
}

.panel-header h4{
    font-size:16px;
    font-weight:600;
}

.search-input{
    width:350px;
    padding:12px 14px;
    border:1px solid #e2e8f0;
    border-radius:10px;
    font-size:14px;
    outline:none;
    margin-bottom:20px;
    transition:.2s;
}

.search-input:focus{
    border-color:#800000;
    box-shadow:0 0 0 3px rgba(128,0,0,.08);
}


.table-container{
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#f8fafc;
}

thead th{
    padding:14px;
    text-align:left;
    font-size:13px;
    color:#64748b;
    text-transform:uppercase;
    border-bottom:2px solid #e2e8f0;
}

tbody td{
    padding:14px;
    font-size:14px;
    color:#1e293b;
    border-bottom:1px solid #f1f5f9;
}

tbody tr:hover{
    background:#f8fafc;
}

tbody td:nth-child(2){
    font-weight:600;
}

.no-data{
    text-align:center;
    padding:25px;
    color:#94a3b8;
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

    .search-input{
        width:100%;
    }

    table{
        min-width:700px;
    }

    .student-summary h2{
        font-size:28px;
    }
}

.student-summary{
    position:relative;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,.05);
    transition:.3s;
}

.student-summary::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:4px;
    background:#800000;
}

.student-summary:hover{
    transform:translateY(-2px);
}

.panel{
    box-shadow:0 10px 25px rgba(0,0,0,.04);
    transition:.3s;
}

.panel:hover{
    box-shadow:0 15px 35px rgba(0,0,0,.06);
}

.search-input{
    background:#fff;
    transition:all .3s ease;
}

.search-input:hover{
    border-color:#cbd5e1;
}

.search-input:focus{
    background:#fff;
}

.table-container{
    border-radius:12px;
    overflow:hidden;
}

thead th{
    letter-spacing:.5px;
    font-weight:700;
}

tbody tr{
    transition:all .25s ease;
}

tbody tr:hover{
    background:#fafafa;
    transform:scale(1.002);
}

tbody td{
    transition:.2s;
}

tbody td:first-child{
    font-weight:600;
    color:#800000;
}

tbody td:nth-child(4),
tbody td:nth-child(5){
    font-size:13px;
    font-weight:500;
}

.no-data{
    background:#fafafa;
    border-radius:10px;
    font-style:italic;
}

.student-summary h2{
    text-shadow:0 2px 10px rgba(128,0,0,.08);
}

.main{
    animation:fadeIn .4s ease;
}

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(10px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

::-webkit-scrollbar{
    width:8px;
    height:8px;
}

::-webkit-scrollbar-track{
    background:#f1f5f9;
}

::-webkit-scrollbar-thumb{
    background:#800000;
    border-radius:20px;
}

::-webkit-scrollbar-thumb:hover{
    background:#660000;
}

@media(max-width:768px){

    .student-summary,
    .panel{
        border-radius:12px;
    }

    tbody td{
        white-space:nowrap;
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
        <a href="admin_students.php" class="active">Students</a>
        <a href="admin_faculty.php">Faculty</a>
        <a href="admin_appointments.php">Appointments</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="main">

    <div class="topbar">
        <h1>Student Management</h1>
    </div>

    <div class="student-summary">
        <h2><?= $totalStudents ?></h2>
        <p>Total Registered Students</p>
    </div>

    <div class="panel">

        <div class="panel-header">
            <h4>Student Directory</h4>
        </div>

        <form method="GET">
            <input
                type="text"
                name="search"
                class="search-input"
                placeholder="Search by ID, Name or Email..."
                value="<?= htmlspecialchars($search) ?>">
        </form>

        <div class="table-container">

            <table>

                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th>Course</th>
                        <th>Year Level</th>
                    </tr>
                </thead>

                <tbody>

                <?php if(mysqli_num_rows($result) > 0): ?>

                    <?php while($row = mysqli_fetch_assoc($result)): ?>

                    <tr>
                        <td><?= htmlspecialchars($row['student_id']) ?></td>

                        <td>
                            <?= htmlspecialchars(
                                $row['first_name'].' '.$row['last_name']
                            ) ?>
                        </td>

                        <td><?= htmlspecialchars($row['email']) ?></td>

                        <td><?= htmlspecialchars($row['course'] ?? 'N/A') ?></td>

                        <td><?= htmlspecialchars($row['year_level'] ?? 'N/A') ?></td>
                    </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="5" class="no-data">
                            No students found.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>