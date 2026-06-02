<?php
include 'db.php';

$message = "";

if(isset($_POST['register'])){

    $student_id = mysqli_real_escape_string($conn,$_POST['student_id']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $first_name = mysqli_real_escape_string($conn,$_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn,$_POST['last_name']);
    $course = mysqli_real_escape_string($conn,$_POST['course']);
    $year_level = mysqli_real_escape_string($conn,$_POST['year_level']);
    $phone = mysqli_real_escape_string($conn,$_POST['phone']);

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO students
    (student_id,email,first_name,last_name,course,year_level,phone,password)
    VALUES
    ('$student_id','$email','$first_name','$last_name',
    '$course','$year_level','$phone','$password')";

    if(mysqli_query($conn,$sql)){
        $message = "Registration Successful!";
    }else{
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PUP AppointEd Registration</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#f5f6fa;
min-height:100vh;
display:flex;
justify-content:center;
align-items:center;
padding:30px;
}

.container{
width:100%;
max-width:900px;
}

.logo{
width:60px;
height:60px;
background:#800000;
color:#fff;
margin:auto;
border-radius:12px;
display:flex;
align-items:center;
justify-content:center;
font-size:28px;
font-weight:700;
}

.title{
text-align:center;
margin-top:20px;
}

.title h1{
color:#800000;
font-size:40px;
}

.title p{
color:#64748b;
margin-top:5px;
}

.card{
background:#fff;
margin-top:30px;
padding:35px;
border-radius:15px;
box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.row{
display:grid;
grid-template-columns:1fr 1fr;
gap:20px;
margin-bottom:20px;
}

.form-group label{
display:block;
margin-bottom:8px;
font-weight:600;
}

.form-group input,
.form-group select{
width:100%;
padding:14px;
border:1px solid #dcdcdc;
border-radius:8px;
outline:none;
}

.form-group input:focus,
.form-group select:focus{
border-color:#800000;
}

.register-btn{
width:100%;
padding:15px;
border:none;
border-radius:8px;
background:#800000;
color:white;
font-size:16px;
font-weight:600;
cursor:pointer;
}

.register-btn:hover{
background:#990000;
}

.message{
background:#d4edda;
color:#155724;
padding:12px;
border-radius:8px;
margin-bottom:20px;
text-align:center;
}

.back{
display:block;
text-align:center;
margin-top:20px;
text-decoration:none;
color:#800000;
font-weight:600;
}

@media(max-width:768px){

.row{
grid-template-columns:1fr;
}

.title h1{
font-size:30px;
}

.card{
padding:25px;
}

}

</style>
</head>
<body>

<div class="container">

<div class="logo">P</div>

<div class="title">
<h1>Student Registration</h1>
<p>Create your student account</p>
</div>

<div class="card">

<?php if($message != ""){ ?>
<div class="message">
<?php echo $message; ?>
</div>
<?php } ?>

<form method="POST">

<div class="row">

<div class="form-group">
<label>Student ID</label>
<input type="text" name="student_id" required>
</div>

<div class="form-group">
<label>Email</label>
<input type="email" name="email" required>
</div>

</div>

<div class="row">

<div class="form-group">
<label>First Name</label>
<input type="text" name="first_name" required>
</div>

<div class="form-group">
<label>Last Name</label>
<input type="text" name="last_name" required>
</div>

</div>

<div class="row">

<div class="form-group">
<label>Course</label>
<input type="text" name="course" required>
</div>

<div class="form-group">
<label>Year Level</label>
<select name="year_level" required>
<option value="">Select Year</option>
<option>1st Year</option>
<option>2nd Year</option>
<option>3rd Year</option>
<option>4th Year</option>
</select>
</div>

</div>

<div class="row">

<div class="form-group">
<label>Phone</label>
<input type="text" name="phone">
</div>

<div class="form-group">
<label>Password</label>
<input type="password" name="password" required>
</div>

</div>

<button type="submit" name="register" class="register-btn">
Create Account
</button>

</form>

</div>

<a href="index.php" class="back">← Back to Home</a>

</div>

</body>
</html>