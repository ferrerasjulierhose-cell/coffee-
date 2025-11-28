// script.js (cart helper)
function addToCart(id){
  let cart = JSON.parse(localStorage.getItem('cart') || '[]');
  let found = cart.find(i => i.id === id);
  if(found) found.qty++;
  else cart.push({ id: id, qty: 1 });
  localStorage.setItem('cart', JSON.stringify(cart));
  alert('Added to cart');
}
