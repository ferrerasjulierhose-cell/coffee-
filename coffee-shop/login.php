<?php
session_start();
require 'db.php';
$err = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  if($email === '' || $pass === '') $err = 'Email & password required';
  else {
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($conn,$email)."' LIMIT 1");
    if($row = mysqli_fetch_assoc($sql)){
      if(password_verify($pass, $row['password'])){
        $_SESSION['user'] = [
          'id' => $row['id'],
          'fullname' => $row['fullname'],
          'email' => $row['email'],
          'is_admin' => (int)$row['is_admin']
        ];
        header('Location: index.php'); exit;
      } else $err = 'Invalid credentials';
    } else $err = 'Invalid credentials';
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - Caffeine</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="nav"><div class="logo">Caffeine</div></header>
<main class="container">
  <h1>Login</h1>
  <?php if($err) echo "<div class='card' style='background:#3b2720;color:#fff;margin-bottom:10px;'>".htmlspecialchars($err)."</div>"; ?>
  <form method="POST" class="card">
    <label>Email<br><input name="email" type="email" required></label><br>
    <label>Password<br><input name="password" type="password" required></label><br>
    <button class="btn" type="submit">Login</button>
  </form>
</main>
</body>
</html>
