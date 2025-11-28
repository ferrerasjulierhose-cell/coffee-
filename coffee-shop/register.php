<?php
session_start();
require 'db.php';
$err = $msg = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $name = trim($_POST['fullname'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  if($name===''||$email===''||$pass==='') $err = 'All fields required';
  else {
    $exists = mysqli_query($conn,"SELECT id FROM users WHERE email='".mysqli_real_escape_string($conn,$email)."' LIMIT 1");
    if(mysqli_num_rows($exists)>0) $err = 'Email already registered';
    else {
      $hash = password_hash($pass, PASSWORD_DEFAULT);
      $sql = "INSERT INTO users (fullname,email,password) VALUES ('".mysqli_real_escape_string($conn,$name)."','".mysqli_real_escape_string($conn,$email)."','$hash')";
      if(mysqli_query($conn,$sql)) $msg = 'Registration successful. Please login.';
      else $err = 'DB error: '.mysqli_error($conn);
    }
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register - Caffeine</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="nav"><div class="logo">Caffeine</div></header>
<main class="container">
  <h1>Register</h1>
  <?php if($err) echo "<div class='card' style='background:#3b2720;color:#fff;margin-bottom:10px;'>".htmlspecialchars($err)."</div>"; ?>
  <?php if($msg) echo "<div class='card' style='background:#233a2a;color:#fff;margin-bottom:10px;'>".htmlspecialchars($msg)."</div>"; ?>
  <form method="POST" class="card">
    <label>Full name<br><input name="fullname" required></label><br>
    <label>Email<br><input name="email" type="email" required></label><br>
    <label>Password<br><input name="password" type="password" required></label><br>
    <button class="btn" type="submit">Register</button>
  </form>
</main>
</body>
</html>
