
if(document.readyState == 'loading'){
   document.addEventListener('DOMContentLoaded',ready)
}else{
    ready()
    updateCartTotal()

}
function addItemToCart(title,price,img){
    var cartRow = document.createElement('div')
    cartRow.classList.add('cart-row')
    var cartItem = document.getElementsByClassName('cart-list')[0];

    var cartRowContent = `<div class="cart-box">
    <img  src="${img}" alt="" class="cart-img" >
    <div class="detail-box">
        <div class="cart-product-title" >${title}</div>
        <div class="cart-price"> ${price}</div>
        <div class="quantity">
            <input type="number" min="0" value="1" class="cart-quantity" onchange="updateCartTotal()">
          </div>
    </div>
    <i class='bx bx-trash remove-item' onclick="removeCartItemButtons(event,this);"></i>
    </div>`



    cartRow.innerHTML = cartRowContent
    cartItem.append(cartRow)
}

function ready(){
    var cartItemContainer = document.getElementsByClassName('cart-list')[0]
    var cartRow = cartItemContainer.getElementsByClassName('cart-box')

    for (let i = 0 ; i < cartRow.length; i++){
        cartRow[i].remove()
    }

    var quantityInputs = document.getElementsByClassName('cart-quantity')
    for (let i= 0; i < quantityInputs.length; i++){
        var input = quantityInputs[i]
        input.addEventListener('change', quantityChanged)
    }
    updateCartTotal()

}
function quantityChanged(event){
    var input = event.target
    //is number or not + non-neg

    if(isNaN(input.value) || input.value <= 0){
        input.value = 1;
    }
    updateCartTotal()
}
//trigger icon
function addToCartClicked(event,self){

    var count = self.getAttribute("counter")
    var button = event.target
    var shopItem = button.parentElement.parentElement
    var price = shopItem.getElementsByClassName('price')[count].innerText
    var title = shopItem.getElementsByClassName('product-title')[count].innerText
    var img =  shopItem.getElementsByClassName('product-img')[count].src
    console.log(title,price,img,count)
    addItemToCart(title,price,img)
    updateCartTotal()

}

function removeCartItemButtons(event,self){

    var count = self.getAttribute("counter")
    var button = event.target
        console.log('clicked')
    button.parentElement.remove()
    updateCartTotal()
}
function updateCartTotal(){
    var cartItemContainer = document.getElementsByClassName('cart')[0]
    var cartRow = cartItemContainer.getElementsByClassName('cart-box')
    var total = 0;

    for(var i = 0 ; i < cartRow.length; i++){
        var priceElement = cartRow[i].getElementsByClassName('cart-price')[0]
        var quantityElement = cartRow[i].getElementsByClassName('cart-quantity')[0]

        var price = parseFloat(priceElement.innerText.replace('$',''))
        var quantity = quantityElement.value
        total += price * quantity

    }
    document.getElementsByClassName('total-price')[0].innerText = '$' + total
}