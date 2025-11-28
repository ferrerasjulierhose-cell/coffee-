<?php
session_start();
$products = json_decode(file_get_contents('products.json'), true);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Menu - Caffeine</title>
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
  <h1 class="center">Our Menu</h1>
  <div class="menu-grid">
    <?php foreach($products as $p): ?>
      <div class="item-card card">
        <h3><?=htmlspecialchars($p['name'])?></h3>
        <p class="muted"><?=htmlspecialchars($p['description'] ?? '')?></p>
        <p class="price">₱<?=number_format($p['price'],2)?></p>
        <button class="btn" onclick="addToCart(<?= (int)$p['id'] ?>)">Add to Cart</button>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<script src="script.js"></script>
</body>
</html>
