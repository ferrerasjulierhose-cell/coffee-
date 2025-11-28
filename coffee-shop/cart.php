<?php
session_start();
$products = json_decode(file_get_contents('products.json'), true);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Cart - Caffeine</title>
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
  <h1 class="center">Your Cart</h1>
  <div id="cart" class="card center"></div>
</main>

<script>
const PRODUCTS = <?= json_encode($products, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>;

function renderCart(){
  let cart = JSON.parse(localStorage.getItem('cart') || '[]');
  const el = document.getElementById('cart');
  if(cart.length === 0){
    el.innerHTML = "<p>Your cart is empty.</p><p><a href='menu.php' class='btn'>Go to Menu</a></p>";
    return;
  }
  let html = '';
  let total = 0;
  cart.forEach((item, idx)=>{
    const p = PRODUCTS.find(pr => pr.id === item.id);
    if(!p) return;
    const subtotal = p.price * item.qty;
    total += subtotal;
    html += `<div style="margin-bottom:12px">
      <strong>${p.name}</strong> <span class="muted">₱${p.price.toFixed(2)}</span><br>
      Qty: <button onclick="decrease(${item.id})">-</button> ${item.qty} <button onclick="increase(${item.id})">+</button> &nbsp; Sub: ₱${subtotal.toFixed(2)}
    </div>`;
  });
  html += `<hr><h3>Total: ₱${total.toFixed(2)}</h3>`;
  html += `<button class="btn" onclick="checkout()">Checkout</button>`;
  el.innerHTML = html;
}

function increase(id){
  let cart = JSON.parse(localStorage.getItem('cart')||'[]');
  let it = cart.find(x=>x.id===id); if(it) it.qty++;
  localStorage.setItem('cart', JSON.stringify(cart)); renderCart();
}
function decrease(id){
  let cart = JSON.parse(localStorage.getItem('cart')||'[]');
  let it = cart.find(x=>x.id===id); if(it){ it.qty--; if(it.qty<=0) cart = cart.filter(x=>x.id!==id); }
  localStorage.setItem('cart', JSON.stringify(cart)); renderCart();
}

function checkout(){
  let cart = JSON.parse(localStorage.getItem('cart')||'[]');
  if(cart.length===0) return alert('Cart is empty');
  fetch('checkout.php',{
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body: JSON.stringify({ cart })
  }).then(r=>r.json()).then(res=>{
    if(res.success){
      localStorage.removeItem('cart');
      alert('Order placed!');
      window.location='index.php';
    } else {
      if(res.login_required){ alert('Please login to checkout.'); window.location='login.php'; }
      else alert('Error: ' + (res.error || 'Unknown'));
    }
  }).catch(e=>alert('Network error'));
}

renderCart();
</script>
</body>
</html>
