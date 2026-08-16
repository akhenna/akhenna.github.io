<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PUP AppointEd - Analytics Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:#f8f9fc;
}


.navbar{
    background:#800000;
    padding:15px 8%;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 10px rgba(0,0,0,.1);
}

.logo{
    display:flex;
    align-items:center;
    gap:10px;
}

.logo-box{
    width:40px;
    height:40px;
    background:#fff;
    color:#800000;
    border-radius:8px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    font-size:20px;
}

.logo h2{
    color:#fff;
    font-size:22px;
}

.back-btn{
    text-decoration:none;
    color:#800000;
    background:#fff;
    padding:10px 18px;
    border-radius:8px;
    font-weight:600;
}


.hero{
    background:linear-gradient(135deg,#800000,#a00000);
    color:white;
    text-align:center;
    padding:60px 20px;
}

.hero h1{
    font-size:40px;
    margin-bottom:10px;
}

.hero p{
    font-size:16px;
    opacity:.9;
}


.container{
    width:90%;
    max-width:1400px;
    margin:auto;
    margin-top:-40px;
    margin-bottom:50px;
}

.dashboard-card{
    background:white;
    border-radius:20px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.section-title{
    text-align:center;
    margin-bottom:25px;
}

.section-title h2{
    color:#800000;
    font-size:30px;
}

.section-title p{
    color:#666;
    margin-top:8px;
}


.powerbi-wrapper{
    width:100%;
    overflow:hidden;
    border-radius:15px;
    border:1px solid #eee;
}

.powerbi-wrapper iframe{
    width:100%;
    height:80vh;
    min-height:700px;
    border:none;
}


.footer{
    background:#800000;
    color:white;
    text-align:center;
    padding:20px;
}

.footer p{
    opacity:.9;
}

/* MOBILE */

@media(max-width:768px){

    .navbar{
        padding:15px 20px;
    }

    .logo h2{
        font-size:18px;
    }

    .hero h1{
        font-size:28px;
    }

    .powerbi-wrapper iframe{
        height:70vh;
        min-height:500px;
    }

}

</style>
</head>
<body>


<nav class="navbar">

    <div class="logo">
        <div class="logo-box">P</div>
        <h2>PUP AppointEd</h2>
    </div>

    <a href="index.php" class="back-btn">
        ← Home
    </a>

</nav>


<section class="hero">

    <h1>Appointment Analytics</h1>

    <p>
        Monitor consultation bookings, appointment trends,
        and faculty-student engagement through interactive reports.
    </p>

</section>


<div class="container">

    <div class="dashboard-card">

        <div class="section-title">
            <h2>Booking Visualization Dashboard</h2>
            <p>Real-time analytics powered by Microsoft Power BI</p>
        </div>

        <div class="powerbi-wrapper">

            <iframe
                title="BOOKING (1)"
                src="https://app.powerbi.com/view?r=eyJrIjoiOWYyN2I5OTAtMWRjZC00OWVhLWFlMDQtODhiNjU2ZTc4NWVmIiwidCI6IjRkYTk4NTcxLWRjZWEtNDgzOS04ZmIxLTBiZGQ1ZGM5NjlmOSIsImMiOjEwfQ%3D%3D"
                allowfullscreen="true">
            </iframe>

        </div>

    </div>

</div>


<footer class="footer">
    <p>
        © 2026 Polytechnic University of the Philippines - COnsultation Schedule System
    </p>
</footer>

</body>
</html>