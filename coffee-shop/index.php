<?php session_start(); ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Caffeine - Home</title>
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
      <a href="register.php" class="btn-sm">Register</a>
    <?php endif; ?>
  </div>
</header>

<main class="hero container">
  <div class="hero-left">
    <h1>Discover The<br>Art Of Perfect<br>Coffee</h1>
    <p class="subtitle">Experience the rich and bold flavors of our exquisite coffee blends, crafted to awaken your senses.</p>
    <div class="hero-buttons">
      <a class="btn" href="menu.php">Order Now →</a>
      <a class="btn outline" href="about.php">Explore More</a>
    </div>
    <div class="stats">
      <div><h3>50+</h3><p>Item Of Coffee</p></div>
      <div><h3>20+</h3><p>Order Running</p></div>
      <div><h3>2k+</h3><p>Happy Customer</p></div>
    </div>
  </div>
  <div class="hero-right">
    <img src="hero-coffee.png" alt="Coffee" class="coffee-cup">
  </div>
</main>

<script src="script.js"></script>
</body>
</html>
