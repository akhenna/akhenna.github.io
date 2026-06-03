<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>PUP AppointEd</title>

<link rel="stylesheet" href="style.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

<nav class="navbar">

    <div class="logo">
        <div class="logo-box">P</div>
        <h2>PUP AppointEd</h2>
    </div>

<div class="nav-links">
    <a href="index.php">Home</a>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>
    <a href="register.php" class="get-started">Get Started</a>
</div>

</nav>

<section class="hero">

    <div class="hero-content">

        <div class="left">

            <div class="badge">
                <span></span>
                Now Open for Consultation
            </div>

            <h1>
                Faculty Consultation <br>
                <span class="highlight">Made Simple</span>
            </h1>

            <p class="description">
                Book your faculty consultations online,
                track appointments in real-time,
                and manage your academic schedule with ease.
            </p>

            <div class="buttons">
                <a href="register.php" class="btn-primary">Register as Student</a>
               <a href="login.php" class="btn-secondary">Sign In</a>
            </div>

        </div>

        <div class="login-card">

           <div class="tabs">
    <button class="tab active" data-role="student">Student</button>
    <button class="tab" data-role="faculty">Faculty</button>
    <button class="tab" data-role="admin">Admin</button>
</div>

            <form>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email"
                    class="form-control"
                    placeholder="student@pup.edu.ph">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password"
                    class="form-control"
                    placeholder="********">
                </div>

                <button class="signin-btn">
                    Sign In
                </button>

            </form>

        </div>

    </div>

</section>

<section class="how-it-works">

    <div class="section-title">
        <h2>How It Works</h2>
        <p>Simple steps to book your faculty consultation</p>
    </div>

    <div class="steps-container">

        <div class="step-card">
            <div class="step-icon"></div>
            <span class="step-number">01</span>

            <h3>Choose Your Faculty</h3>

            <p>
                Browse available faculty members and
                view their schedules.
            </p>
        </div>

        <div class="step-card">
            <div class="step-icon"></div>
            <span class="step-number">02</span>

            <h3>Book a Schedule</h3>

            <p>
                Select a convenient time slot and
                submit your consultation request.
            </p>
        </div>

        <div class="step-card">
            <div class="step-icon"></div>
            <span class="step-number">03</span>

            <h3>Get Notified</h3>

            <p>
                Receive real-time updates on your
                appointment status.
            </p>
        </div>

    </div>

</section>

<section class="students-section">

    <div class="students-container">

        <div class="student-card">

            <div class="card-header">

                <div class="card-icon"></div>

                <div>
                    <h3>Student Dashboard</h3>
                    <p>Your consultation hub</p>
                </div>

            </div>

            <div class="card-stat">
                3 Upcoming Appointments
            </div>

            <div class="card-stat">
                2 Pending Requests
            </div>

            <div class="card-stat">
                12 Completed
            </div>

        </div>

        <div class="student-content">

            <h2>For Students</h2>

            <p class="student-description">
                Easily book consultations with your professors.
                Track your appointments, get notified of approvals,
                and manage your consultation history.
            </p>

            <ul class="student-features">

                <li>Book appointments online 24/7</li>
                <li>View faculty availability in real-time</li>
                <li>Submit consultation concerns in advance</li>
                <li>Track appointment status updates</li>
                <li>Access your consultation history</li>

            </ul>

          <a href="register.php" class="student-btn">
    Register Now
            </a>

        </div>

    </div>

</section>

<section class="faculty-section">

    <div class="faculty-container">

        <div class="faculty-content">

            <h2>For Faculty</h2>

            <p class="faculty-description">
                Manage your consultation schedule, approve or reject
                requests, and view student concerns before the meeting.
            </p>

            <ul class="faculty-features">

                <li>Set your available consultation hours</li>
                <li>Approve or reject appointment requests</li>
                <li>View student concerns in advance</li>
                <li>Track your consultation history</li>
                <li>Get notified of new bookings</li>

            </ul>

            <a href="#" class="faculty-btn">
                Faculty Portal
            </a>

        </div>

        <div class="faculty-card">

            <div class="faculty-header">

                <div class="faculty-icon"></div>

                <div>
                    <h3>Faculty Dashboard</h3>
                    <p>Manage your consultations</p>
                </div>

            </div>

            <div class="faculty-item">
                <span>Akhenna S. Lachica</span>
                <span class="status pending">Pending</span>
            </div>

            <div class="faculty-item">
                <span>Kimberly S. Tungcab</span>
                <span class="status approved">Approved</span>
            </div>

            <div class="faculty-item">
                <span>Andrew M. Guevarra</span>
                <span class="status rejected">Rejected</span>
            </div>

        </div>

    </div>

</section>

<section class="admin-section">

    <div class="admin-header">
        <h2>Admin Dashboard</h2>
        <p>Comprehensive management tools for system administrators</p>
    </div>

    <div class="admin-grid">

        <div class="admin-card">
            <div class="admin-icon"></div>
            <h3>Student Management</h3>
            <p>Add, edit, and manage student accounts</p>
        </div>

        <div class="admin-card">
            <div class="admin-icon"></div>
            <h3>Faculty Management</h3>
            <p>Manage faculty profiles and schedules</p>
        </div>

        <div class="admin-card">
            <div class="admin-icon"></div>
            <h3>Appointment Overview</h3>
            <p>View and manage all appointments</p>
        </div>

        <div class="admin-card">
            <div class="admin-icon"></div>
            <h3>Reports & Analytics</h3>
            <p>Generate insights and reports</p>
        </div>

        <div class="admin-card">
            <div class="admin-icon"></div>
            <h3>System Settings</h3>
            <p>Configure system preferences</p>
        </div>

        <div class="admin-card">
            <div class="admin-icon"></div>
            <h3>Activity Logs</h3>
            <p>Track system activities and changes</p>
        </div>

    </div>

    <div class="admin-button">
        <a href="#" class="admin-btn">
            Admin Portal
        </a>
    </div>

</section>

<section class="cta-section">

    <div class="cta-container">

        <h2>Ready to Get Started?</h2>

        <p>
            Join thousands of students and faculty who use our
            consultation booking system.
        </p>

        <div class="cta-buttons">

            <a href="register.php" class="cta-primary">
                Register as Student
            </a>

            <a href="login.php" class="cta-secondary">
                Sign In
            </a>

        </div>

    </div>

</section>

<footer class="footer">

    <div class="footer-container">

        <div class="footer-column">

            <div class="footer-logo">

                <div class="footer-logo-box">P</div>

                <h3>PUP AppointEd</h3>

            </div>

            <p>
                Faculty Consultation Booking System for
                Polytechnic University of the Philippines.
            </p>

        </div>

        <div class="footer-column">

          <a href="index.php">Home</a>
          <a href="login.php">Login</a>
          <a href="register.php">Register</a>

        </div>

        <div class="footer-column">

            <h4>Portals</h4>

            <a href="#">Student Portal</a>
            <a href="#">Faculty Portal</a>
            <a href="#">Admin Portal</a>

        </div>

        <div class="footer-column">

            <h4>Contact</h4>

            <p>Poblacion 2, Sto.tomas</p>
            <p>Batangas, Philippines</p>
            <p>pupstc@gmail.com</p>

        </div>

    </div>

    <div class="footer-bottom">
        © 2026 Polytechnic University of the Philippines. All rights reserved.
    </div>

<script>
const tabs = document.querySelectorAll('.tab');
const form = document.querySelector('form');

tabs.forEach(tab => {
    tab.addEventListener('click', () => {

        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        const role = tab.dataset.role;

        if(role === 'student'){
            form.innerHTML = `
                <input type="hidden" name="role" value="student">

                <div class="form-group">
                    <label>Student Email</label>
                    <input type="email" class="form-control"
                    placeholder="student@pup.edu.ph">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control"
                    placeholder="********">
                </div>

                <button class="signin-btn">Student Sign In</button>
            `;
        }

        if(role === 'faculty'){
            form.innerHTML = `
                <input type="hidden" name="role" value="faculty">

                <div class="form-group">
                    <label>Faculty Email</label>
                    <input type="email" class="form-control"
                    placeholder="faculty@pup.edu.ph">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control"
                    placeholder="********">
                </div>

                <button class="signin-btn">Faculty Sign In</button>
            `;
        }

        if(role === 'admin'){
            form.innerHTML = `
                <input type="hidden" name="role" value="admin">

                <div class="form-group">
                    <label>Admin Email</label>
                    <input type="email" class="form-control"
                    placeholder="admin@pup.edu.ph">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control"
                    placeholder="********">
                </div>

                <button class="signin-btn">Admin Sign In</button>
            `;
        }
    });
});
</script>

</body>
</html>