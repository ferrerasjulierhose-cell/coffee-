<?php
session_start();
require 'db.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1){
  header('Location: login.php'); exit;
}
$productsFile = 'products.json';
$products = json_decode(file_get_contents($productsFile), true);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $action = $_POST['action'] ?? '';
  if($action === 'add'){
    $name = trim($_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $maxId = 0; foreach($products as $p) $maxId = max($maxId, $p['id']);
    $products[] = ['id'=>$maxId+1,'name'=>$name,'price'=>$price,'description'=>$description];
    file_put_contents($productsFile, json_encode($products, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    header('Location: admin.php'); exit;
  }
  if($action === 'edit'){
    $id = (int)$_POST['id'];
    foreach($products as &$p){
      if($p['id'] === $id){
        $p['name'] = trim($_POST['name'] ?? $p['name']);
        $p['price'] = floatval($_POST['price'] ?? $p['price']);
        $p['description'] = trim($_POST['description'] ?? $p['description']);
      }
    }
    unset($p);
    file_put_contents($productsFile, json_encode($products, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    header('Location: admin.php'); exit;
  }
  if($action === 'delete'){
    $id = (int)$_POST['id'];
    $products = array_values(array_filter($products, fn($x)=> $x['id'] !== $id));
    file_put_contents($productsFile, json_encode($products, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    header('Location: admin.php'); exit;
  }
}

$products = json_decode(file_get_contents($productsFile), true);
$ordersRes = mysqli_query($conn, "SELECT o.*, u.fullname FROM orders o JOIN users u ON u.id = o.user_id ORDER BY o.created_at DESC");
$orders = []; while($row = mysqli_fetch_assoc($ordersRes)) $orders[] = $row;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Caffeine</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="nav">
  <div class="logo">Caffeine - Admin</div>
  <nav class="nav-links">
    <a href="index.php">Home</a>
    <a href="menu.php">Menu</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>

<main class="container">
  <h1>Product Manager</h1>

  <section class="card">
    <h3>Add New Product</h3>
    <form method="POST">
      <input type="hidden" name="action" value="add">
      <label>Name<br><input name="name" required></label><br>
      <label>Price<br><input name="price" type="number" step="0.01" required></label><br>
      <label>Description<br><input name="description"></label><br>
      <button class="btn" type="submit">Add Product</button>
    </form>
  </section>

  <section class="card">
    <h3>Existing Products</h3>
    <?php foreach($products as $p): ?>
      <div style="margin-bottom:12px;">
        <strong><?=htmlspecialchars($p['name'])?></strong> — ₱<?=number_format($p['price'],2)?>
        <div class="muted"><?=htmlspecialchars($p['description'] ?? '')?></div>

        <form method="POST" style="margin-top:6px;">
          <input type="hidden" name="action" value="edit">
          <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
          <input name="name" value="<?= htmlspecialchars($p['name']) ?>">
          <input name="price" type="number" step="0.01" value="<?= htmlspecialchars($p['price']) ?>">
          <input name="description" value="<?= htmlspecialchars($p['description'] ?? '') ?>">
          <button class="btn" type="submit">Save</button>
        </form>

        <form method="POST" style="display:inline-block;margin-top:6px;">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
          <button class="btn" style="background:#c0392b;border:none" type="submit">Delete</button>
        </form>
      </div>
      <hr>
    <?php endforeach; ?>
  </section>

  <section class="card">
    <h3>Recent Orders</h3>
    <?php if(empty($orders)): ?>
      <p>No orders yet.</p>
    <?php else: foreach($orders as $o): ?>
      <div style="margin-bottom:12px;">
        <strong>Order #<?= $o['id'] ?></strong> by <?=htmlspecialchars($o['fullname'])?> — ₱<?=number_format($o['total'],2)?> <span class="muted"><?= $o['created_at'] ?></span>
        <div class="muted">Items: <?= htmlspecialchars($o['items']) ?></div>
      </div>
      <hr>
    <?php endforeach; endif; ?>
  </section>

</main>
</body>
</html>
