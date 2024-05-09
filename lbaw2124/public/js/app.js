function addEventListeners() {
    productsEventListeners();
    wishlistEventListeners();

    reviewsEventListeners();
}

function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}

function sendAjaxRequestAsync(method, url, data, handler) {
    let request = new XMLHttpRequest();

    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
}

/*
        .__                             .__            ____                               __         _____                        __   .__
  ______|  |__    ____  ______  ______  |__|  ____    / ___\       ____  _____  _______ _/  |_     _/ ____\__ __   ____    ____ _/  |_ |__|  ____    ____    ______
 /  ___/|  |  \  /  _ \ \____ \ \____ \ |  | /    \  / /_/  >    _/ ___\ \__  \ \_  __ \\   __\    \   __\|  |  \ /    \ _/ ___\\   __\|  | /  _ \  /    \  /  ___/
 \___ \ |   Y  \(  <_> )|  |_> >|  |_> >|  ||   |  \ \___  /     \  \___  / __ \_|  | \/ |  |       |  |  |  |  /|   |  \\  \___ |  |  |  |(  <_> )|   |  \ \___ \
/____  >|___|  / \____/ |   __/ |   __/ |__||___|  //_____/       \___  >(____  /|__|    |__|       |__|  |____/ |___|  / \___  >|__|  |__| \____/ |___|  //____  >
     \/      \/         |__|    |__|             \/                   \/      \/                                      \/      \/                        \/      \/


 */
function productsEventListeners() {
    //homepage redirect
    const product_names = document.querySelectorAll(".product-name");
    if (product_names != null && product_names.length !== 0)
        product_names.forEach((n) =>
            n.addEventListener("click", addLinkToProductName, true));

    //shopping cart details
    const delete_from_cart = document.querySelectorAll(".delete-from-cart");
    if (delete_from_cart != null)
        delete_from_cart.forEach((b) =>
            b.addEventListener('click', sendDeleteProductFromCartRequest, true));

    //Homepage add to cart buttons
    const add_to_cart_bts = document.querySelectorAll(".add-to-cart-btn");
    if (add_to_cart_bts != null)
        add_to_cart_bts.forEach(((btn) => {
            btn.addEventListener("click", sendAddOneProductToCartRequest, false);
        }))

    //product details add product
    const add_to_cart_btn = document.querySelector("#add-to-cart-btn");
    if (add_to_cart_btn != null)
        add_to_cart_btn.addEventListener("click", sendAddQuantProductToCartRequest);

    //shopping cart page selectors
    const quantity_selectors = document.querySelectorAll(".product-quantity");
    if (quantity_selectors != null)
        quantity_selectors.forEach(((sel) => {
            sel.addEventListener("change", sendUpdateCartRequest, false);
        }))

    const empty_cart = document.querySelector("#empty-cart");
    if (empty_cart != null)
        empty_cart.addEventListener("click", sendEmptyCartRequest, false);

}


function removeFadeOut(el, speed) {
    const seconds = speed / 1000;
    el.style.transition = "opacity " + seconds + "s ease";

    el.style.opacity = "0 ! important";
    setTimeout(function () {
        el.parentNode.removeChild(el);
    }, speed);
}

function insertAlertInContentSection(type, title, content) {
    const page = document.querySelector("#content");
    createAndAppendAlert(type, title, content, page);
}


function createAndAppendAlert(type, title, content, elem) {
    const alert = document.createElement("div");
    alert.innerHTML = `
    <div class="alert ${type} alert-dismissible fade show on_top"  className="close" data-dismiss="alert">
        <strong>${title}</strong> ${content}
    </div>
    `
    removeFadeOut(alert, 2000);
    elem.insertBefore(alert, elem.firstChild);
}


function sendEmptyCartRequest() {
    sendAjaxRequestAsync('delete', 'shoppingcart', null, emptyCartHandler);
}


function emptyCartHandler() {
    if (this.status != 200) {
        insertAlertInContentSection("alert-danger", "Error: ", "The cart was not emptied.");
        //window.location = '/shoppingcart';
    }

    const item = JSON.parse(this.responseText);

    if (item === 0) {
        insertAlertInContentSection("alert-warning", "Error: ", "No items were removed.");
        return;
    }

    const shopping_cart_list = document.querySelector("#shopping-cart-list");
    if (shopping_cart_list.childNodes)
        while (shopping_cart_list.firstChild) {
            //The list is LIVE so it will re-index each call
            shopping_cart_list.removeChild(shopping_cart_list.firstChild);
        }
    const total_cart_price = document.querySelector("#total-cart-price");
    total_cart_price.innerText = 0;
    document.getElementById('total_checkout').value = total_cart_price.innerText;

    insertAlertInContentSection("alert-success", "Emptied Cart: ", "Your " + item + " items have been removed");
}


function sendAddOneProductToCartRequest(event) {
    const id = event.target.closest(".product-card").dataset.id;
    sendAjaxRequestAsync('post', '/shoppingcart/' + id, {quantity: 1, id: id}, addProductToCartHandlerHomepage);
}

function testing() {
    console.log(this.responseText)
}


function sendAddQuantProductToCartRequest(event) {
    const quantity = document.querySelector("#product-quantity").value;
    const id = event.target.closest("#add-to-cart-btn").dataset.id;

    if(quantity != null && id != null){
        sendAjaxRequestAsync('post', '/shoppingcart/' + id, {quantity: quantity, id: id}, addProductToCartHandler);
    }
}


function addProductToCartHandlerHomepage() {
    if (this.status !== 200) {
        insertAlertInContentSection("alert-danger", "Error: ", "The product was not added.");
        //window.location = '/shoppingcart';
    }

    const item = JSON.parse(this.responseText);

    const product_card = document.querySelector('.product-card[data-id="' + item.id_product + '"] h5');

    if (product_card != null) {
        const in_cart_span = document.createElement("span");
        in_cart_span.classList.add("in-cart-span");
        in_cart_span.innerText = " (Added to Cart)";
        removeFadeOut(in_cart_span, 1000);
        product_card.appendChild(in_cart_span);
    }
}


function addProductToCartHandler() {
    if (this.status !== 200) {
        insertAlertInContentSection("alert-danger", "Error: ", "The product was not added.");
        //window.location = '/shoppingcart';
    }

    const item = JSON.parse(this.responseText);

    insertAlertInContentSection("alert-success", "Cart: ", "The product was added.");

}


//Should this be done using redirect/route in the button??
function addLinkToProductName(event) {
    window.location = event.target.dataset.route;
}

function sendUpdateCartRequest(event) {
    const id = event.target.closest(".shopping-cart-card").dataset.product;
    const quantity = event.target.value;


    sendAjaxRequestAsync('put', 'shoppingcart/' + id, {id: id, quantity: quantity}, updateCartHandler);

    event.target.value = quantity;
}


function updateCartHandler() {
    if (this.status != 200) {
        insertAlertInContentSection("alert-danger", "Error: ", "The product was not updated.");
        //window.location = '/shoppingcart';
    }

    const item = JSON.parse(this.responseText);
    const total_cart_price = document.querySelector("#total-cart-price");
    total_cart_price.innerText = parseFloat(item.total).toFixed(2);
    document.getElementById('total_checkout').value = total_cart_price.innerText;

    insertAlertInContentSection("alert-success", "Cart: ", "Your cart was updated.");
}


function sendDeleteProductFromCartRequest(event) {
    const id = event.target.dataset.product;
    sendAjaxRequestAsync('delete', '/shoppingcart/' + id, null, deleteProductFromCartHandler);
}


function deleteProductFromCartHandler() {

    if (this.status != 200) {
        insertAlertInContentSection("alert-danger", "Error: ", "The product was not removed.");
        //window.location = '/shoppingcart';
    }

    const item = JSON.parse(this.responseText);


    //remove product li
    const product_li = document.querySelector('.shopping-cart-card[data-product="' + item.id_product + '"]');
    product_li.remove();

    //change total price span
    const total_cart_price = document.querySelector("#total-cart-price");
    total_cart_price.innerText = parseFloat(item.total).toFixed(2);
    //total_cart_price.innerText = (total_cart_price.innerText - parseFloat(item.price) * item.amount).toFixed(2);
    document.getElementById('total_checkout').value = total_cart_price.innerText;

    insertAlertInContentSection("alert-success", "Cart: ", "The product was removed.");
}


function addToWishlist() {
    let id = event.target.closest("#wishlist-details-add").dataset.id;

    if(id == null)
        id = event.target.closest(".product-card").dataset.id;

    sendAjaxRequestAsync('put', '/api/wishlist/addProduct/' + id, {id: id}, addProductToWishlistHandler);
}

function addProductToWishlistHandler() {
    if (this.status !== 200) {
        insertAlertInContentSection("alert-danger", "Error: ", "The product was not added.");

    } else {
        const item = JSON.parse(this.responseText);

        const product_card = document.querySelector('.product-card[data-id="' + item.id_product + '"] h5');

        if (product_card != null) {
            const in_cart_span = document.createElement("span");
            in_cart_span.classList.add("in-cart-span");
            in_cart_span.innerHTML = " &hearts;";
            removeFadeOut(in_cart_span, 2000);
            product_card.appendChild(in_cart_span);
        }

        insertAlertInContentSection("alert-success", "Success: ", "The product was added to your wishlist.");
    }
}

function removeFromWishlist() {
    event.stopPropagation();
    const id = event.target.dataset.product;
    sendAjaxRequestAsync('delete', '/api/wishlist/removeProduct/' + id, {id: id}, removeProductFromWishlistHandler);
}

function removeProductFromWishlistHandler() {
    if (this.status !== 200) {
        insertAlertInContentSection("alert-danger", "Error: ", "The product was not removed.");
    } else {
        const item = JSON.parse(this.responseText);
        const product_li = document.querySelector('.wishlist-card[data-product="' + item.id_product + '"]');
        product_li.remove();
        insertAlertInContentSection("alert-success", "Success: ", "The product was removed from your wishlist.");
    }
}

function removeAllFromWishlist() {
    const wishlist = document.querySelector("#user-wishlist");
    if (wishlist.childNodes.length > 1) sendAjaxRequestAsync('delete', '/api/wishlist/emptyWishlist', {}, removeAllFromWishlistHandler);
    else insertAlertInContentSection("alert-warning", "Error: ", "Empty wishlist.");

}

function removeAllFromWishlistHandler() {
    if (this.status !== 200) {
        insertAlertInContentSection("alert-danger", "Error: ", "Operation not available");
    } else {
        const wishlist = document.querySelector("#user-wishlist");
        if (wishlist.childNodes)
            while (wishlist.firstChild)
                wishlist.removeChild(wishlist.firstChild);

        insertAlertInContentSection("alert-success", "Success: ", "Emptied wishlist");
    }
}

function addToCartFromWishlist() {
    let id = event.target.dataset.product;
    if (id == null){
        id = event.target.parentNode.dataset.product;
    }
    sendAjaxRequestAsync('post', '/shoppingcart/' + id, {quantity: 1, id: id}, addProductToCartHandler);
}


function wishlistEventListeners() {
    const add_to_wishlist_btns = document.querySelectorAll(".add-to-wishlist-btn");
    if (add_to_wishlist_btns != null && add_to_wishlist_btns.length !== 0) {
        add_to_wishlist_btns.forEach(b => b.addEventListener("click", addToWishlist, false));
    }

    const remove_from_wishlist = document.querySelectorAll(".delete-from-wishlist");
    if (remove_from_wishlist != null && remove_from_wishlist.length !== 0)
        remove_from_wishlist.forEach(b => b.addEventListener("click", removeFromWishlist, true));

    const add_to_cart_from_wishlist_btn = document.querySelectorAll(".add-to-cart-wishlist");
    if (add_to_cart_from_wishlist_btn != null && add_to_cart_from_wishlist_btn.length !== 0)
        add_to_cart_from_wishlist_btn.forEach(b => b.addEventListener("click", addToCartFromWishlist, false));

    const remove_all_from_wishlist = document.querySelectorAll(".remove-all-from-wishlist");
    if (remove_all_from_wishlist != null && remove_all_from_wishlist.length !== 0)
        remove_all_from_wishlist.forEach(b => b.addEventListener("click", removeAllFromWishlist, true));

    //Send all to shopping cart
}

function reportReview(){
    const id = event.target.dataset.review;
    sendAjaxRequestAsync('post', '/api/review/report/' + id, {id: id}, reportReviewHandler);
}


function reportReviewHandler(res){
    if (this.status != 200) {
        insertAlertInContentSection("alert-danger", "Error: ", "Could not report review");
    }else {
        const item = JSON.parse(this.responseText);

        const review_li = document.querySelector('.report-btn[data-review="' + item.review + '"]');
        insertAlertInContentSection("alert-success", "Success: ", "Admins were alerted of your report");

        const reported_banner = document.createElement("div");
        reported_banner.innerHTML = "<p>Already reported</p>";

        review_li.parentNode.appendChild(reported_banner);
        review_li.parentNode.removeChild(review_li);
    }
}


function reviewsEventListeners(){
    const edit_reviews_btn = document.querySelector("#edit-review-btn");

    const edit_review_form = document.querySelector("#edit-review-form");
    const own_review_box = document.querySelector("#own-review-box");

    const report_reviews = document.querySelectorAll(".report-btn");

    if(edit_review_form != null)
        edit_review_form.classList.add("hidden");

    if(edit_reviews_btn != null){
        edit_reviews_btn.addEventListener("click", (event) => {
            edit_review_form.classList.remove("hidden");
            own_review_box.classList.add("hidden");
        })
    }

    const go_back_btn = document.querySelector("#go-back-edit");
    if(go_back_btn != null){
        go_back_btn.addEventListener("click", (event) => {
            event.stopPropagation();
            own_review_box.classList.remove("hidden");
            edit_review_form.classList.add("hidden");
        })
    }

    if(report_reviews != null){
        report_reviews.forEach((btn) => btn.addEventListener('click', reportReview, true))
    }


}

addEventListeners();
