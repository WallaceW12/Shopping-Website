

updateCartLocal('load',-1,null)


function addItemToCart(title,price,img, pid){

   let cartRow = document.createElement('div')
    cartRow.classList.add('cart-row')


    let cartRowContent = `
<div class="cart-box" id="p${pid}">
    <img  src="${img}" alt="" class="cart-img" >
    <div class="detail-box" >
        <div class="cart-product-title" >${title}</div>
        <div class="cart-price">$${price}</div>
        <div class="quantity">
            <input type="number" min="0" value="1"  pattern="^[1-9]+[0-9]*$" class="cart-quantity" onchange="quantityChanged(event,${pid})">
          </div>
    </div>
    <i class='bx bx-trash remove-item' onclick="removeCartItemButtons(event,this,${pid});"></i>
 </div>`



    cartRow.innerHTML = cartRowContent



   addLocalStorage(pid,cartRow)


}
function addLocalStorage(pid, cartRow){
    let currentQuantity = Number(localStorage.getItem(pid));


    if (currentQuantity == null || currentQuantity == 0 || currentQuantity == "")
        localStorage.setItem(pid, Number(1));
    else
        localStorage.setItem(pid, Number(currentQuantity + 1));

    // update the shopping list in the HTML

    updateCartLocal("update",pid, cartRow)

}

const valid_pattern = /^[1-9]+[0-9]*$/;
function quantityChanged(event,pid){
    var input = event.target


    //is number or not + non-neg
    if(isNaN(input.value) || input.value < 0 || !input.value.match(valid_pattern)){

        localStorage.removeItem(pid);
        input.parentElement.parentElement.parentElement.remove();


    }else{
        var updatedQuantity = String(Math.trunc(input.value));
        localStorage.setItem(pid,updatedQuantity)


    }
    document.getElementsByClassName('total-price')[0].innerText = '$' + TotalPrice().toFixed(2)



}
//trigger icon
function addToCartClicked(event,self){

    var count = self.getAttribute("counter")
    var pid = self.getAttribute("pid")
   // console.log("PID: sdsd" + pid)
    var button = event.target
    var shopItem = button.parentElement.parentElement


    var price=1 ;
    var title='';
    var img='' ;


    self.disabled = true;
    self.classList.add("adding");

    let request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        //  let cartRowContent = document.querySelector(`#cart-box-template`);

        if(this.status == 200 && this.readyState==4) {

            title = JSON.parse(this.responseText).NAME;

            price = Number(JSON.parse(this.responseText).PRICE);
            img = JSON.parse(this.responseText).THUMBNAIL;



            addItemToCart(title,price,img,pid)
         //    console.log(title,price,img,count)

        }

    } ;
    request.open("GET", "cart-update.php?pid=" + pid, true);
    request.send();




    self.disabled = false;
    self.classList.remove("adding");




   // updateCartTotal()

}

function removeCartItemButtons(event,self,pid){


    var button = event.target
    console.log('clic3ked')

   // self.disabled = true;
   // self.classList.add("adding");

   localStorage.removeItem(pid);

    button.parentElement.remove();

//
    //self.disabled = false;
  //  self.classList.remove("adding");

    document.getElementsByClassName('total-price')[0].innerText = '$' + TotalPrice().toFixed(2)


}
function updateCartTotal(){
    var cartItemContainer = document.getElementsByClassName('cart-list')[0]
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

function updateCartLocal(mode,pid, additem){






    if(mode == "load") {





        // then fetch the item one-by-one based on the records in local storage
        for (let i = 0; i < localStorage.length; i++) {

          //  let product = document.querySelector(``);

            let pid;
           if( (localStorage.key(i) != "__paypal_storage__" )){
               pid = localStorage.key(i)
            let image = '';
            let title='';
            let price=0;
            let item_quantity=0;



           // console.log("PID:" + pid + "item:  " + localStorage.getItem(pid))
            // what to do after sending the request

            let request = new XMLHttpRequest();
            request.onreadystatechange = function () {

              //  let cartRowContent = document.querySelector(`#cart-box-template`);
            if(this.readyState==4){
                if(this.status == 200 ){



                    const cartRow = document.createElement('div')
                    cartRow.classList.add('cart-row')
                    var cartItem = document.getElementsByClassName("cart-list")[0];

                    title = JSON.parse(this.responseText).NAME;
                    price = Number(JSON.parse(this.responseText).PRICE);
                    image = JSON.parse(this.responseText).THUMBNAIL;
                    item_quantity= Number(localStorage.getItem(pid));



                    const cartRowContent = `<div class="cart-box" id="p${pid}">
                        <img  src="${image}" alt="" class="cart-img" >
                        <div class="detail-box" >
                            <div class="cart-product-title" >${title}</div>
                            <div class="cart-price">$${price}</div>
                            <div class="quantity">
                                <input type="number" min="0" value="${item_quantity}" pattern="^[1-9]+[0-9]*$" class="cart-quantity" onchange="quantityChanged(event,${pid});"  required>
                              </div>
                        </div>
                        <i class='bx bx-trash remove-item' onclick="removeCartItemButtons(event,this,${pid});"></i>
                     </div>`



                    // append to current HTML
                    cartRow.innerHTML = cartRowContent
                    cartItem.append(cartRow);


                    document.querySelector(`#p${pid} input`).value = item_quantity;

                    // then calculate price
                    document.getElementsByClassName('total-price')[0].innerText = '$' + TotalPrice().toFixed(2)


                }


            }
            }
               request.open("GET", "cart-update.php?pid=" + pid, true);
               request.send();
           }

            };


        document.getElementsByClassName('total-price')[0].innerText = '$' + TotalPrice().toFixed(2)
    }


      if(mode == "update") {
          let   totalPrice = 0;

          // get the item that should be updated
          let cartItem = document.querySelector(`#p${pid}`);

        //  console.log(cartItem)
          // add new item
          if (cartItem == null) {



              let request = new XMLHttpRequest();

              // what to do after sending the request
              request.onreadystatechange =   function () {

                  if (this.readyState == 4 && this.status == 200) {

                      //console.log("adde");
                      let cartItem = document.getElementsByClassName('cart-list')[0];
                      cartItem.append(additem);

                       // console.log(cartItem);
                      document.getElementsByClassName('total-price')[0].innerText = '$' + TotalPrice().toFixed(2)
                  }
              };
              request.open("GET", "cart-update.php?pid=" + pid, true);
              request.send();


          }
          else { // if cart have item

              // first update the quantity
              document.querySelector(`#p${pid} input`).value = Number(localStorage.getItem(pid));

              // then calculate the new total quantity and price
              let allItem = document.querySelectorAll(`.detail-box`);
              allItem.forEach(item => {
                  let quantity = Number(item.children[2].innerHTML);
                  let price = Number(item.children[1].innerHTML);


                  totalPrice += (quantity * price)
              });

              // show the updated total price
              document.getElementsByClassName('total-price')[0].innerText = '$' + TotalPrice().toFixed(2)




          }


      }

}
function TotalPrice(){
    var totalPrice = 0;


    let allItem = document.querySelectorAll(".detail-box");
    if(allItem == null){
        return 0;
    }
   // console.log(allItem)
    allItem.forEach(item => {
        let price = Number(item.children[1].innerText.replace('$',''));
        let quantity = Number(item.children[2].children[0].value);


        totalPrice += (quantity * price)
    });

    return totalPrice;
}
function getCartItems() {
    /* @TODO Comment out the current return statement */
    /* ========== REGION START ========== */
   /* return [
        {
            pid: 1, quantity: 1,
        },
        {
            pid: 2, quantity: 2
        }
    ];*/

    /* ========== REGION END ========== */

    /* @TODO Your Implementation Here. */
    /* ========== REGION START ========== */
    let output="";
    for (let i = 0; i < localStorage.length; i++) {

        //  let product = document.querySelector(``);

        let pid;
        let item_quantity;
        if ((localStorage.key(i) != "__paypal_storage__")) {
            pid = localStorage.key(i)
            item_quantity = localStorage.getItem(pid);

            output += "{ pid: " + pid + ", quantity: "+item_quantity +" },";
        }
        console.log([output]);

    }
    return [output];
        /* ========== REGION END ========== */

}

/**
 * This function clears all items in the cart.
 */
function clearCart() {
    /* @TODO Your Implementation Here. */
    /* ========== REGION START ========== */



    /* ========== REGION END ========== */
}