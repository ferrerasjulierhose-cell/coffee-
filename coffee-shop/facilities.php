<?php session_start(); ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Facilities - Caffeine</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="nav">
  <div class="logo">Caffeine</div>
  <nav class="nav-links">
    <a href="index.php">Home</a>
    <a href="menu.php">Menu</a>
    <a href="about.php">About</a>
    <a href="facilities.php">Facilities</a>
    <a href="cart.php">Cart</a>
  </nav>
  <div class="nav-right">
    <?php if(isset($_SESSION['user'])): ?>
      <span class="muted">Hello, <?=htmlspecialchars($_SESSION['user']['fullname'])?></span>
      <?php if($_SESSION['user']['is_admin']==1): ?><a href="admin.php" class="btn-sm">Admin</a><?php endif; ?>
      <a href="logout.php" class="btn-sm">Logout</a>
    <?php else: ?>
      <a href="login.php" class="btn-sm">Sign In</a>
    <?php endif; ?>
  </div>
</header>

<main class="container">
  <h1>Facilities</h1>
  <div class="card">
    <ul class="muted">
      <li>Cozy seating with free Wi-Fi</li>
      <li>Private study corners</li>
      <li>Outdoor seating area</li>
      <li>Event space for small gatherings</li>
    </ul>
  </div>
</main>
</body>
</html>
