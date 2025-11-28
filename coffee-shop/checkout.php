<?php
session_start();
header('Content-Type: application/json');
require 'db.php';
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if(!isset($_SESSION['user'])){
  echo json_encode(['success'=>false,'login_required'=>true]); exit;
}
if(!$data || !isset($data['cart']) || !is_array($data['cart'])){
  echo json_encode(['success'=>false,'error'=>'Invalid cart']); exit;
}

$products = json_decode(file_get_contents('products.json'), true);
$items = []; $total = 0.0;
foreach($data['cart'] as $c){
  $id = (int)$c['id']; $qty = max(1,(int)$c['qty']);
  $p = array_values(array_filter($products, fn($x)=> $x['id']=== $id))[0] ?? null;
  if(!$p) continue;
  $items[] = ['id'=>$p['id'],'name'=>$p['name'],'price'=>$p['price'],'qty'=>$qty];
  $total += $p['price'] * $qty;
}
if(empty($items)){ echo json_encode(['success'=>false,'error'=>'No valid items']); exit; }

$user_id = (int)$_SESSION['user']['id'];
$items_json = mysqli_real_escape_string($conn, json_encode($items, JSON_UNESCAPED_UNICODE));
$total_val = number_format($total,2,'.','');

$sql = "INSERT INTO orders (user_id, items, total) VALUES ('$user_id', '$items_json', '$total_val')";
if(mysqli_query($conn, $sql)){
  echo json_encode(['success'=>true]);
} else {
  echo json_encode(['success'=>false,'error'=>mysqli_error($conn)]);
}
