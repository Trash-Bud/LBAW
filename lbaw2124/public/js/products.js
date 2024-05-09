const quantitySelector = document.querySelector('#product-quantity');
if (quantitySelector != null) {
    quantitySelector.value = 1; //place default value
    quantitySelector.addEventListener("change", change_total_price);
}

function change_total_price(event) {
    const unit_price = document.querySelector("#unit-price").innerHTML;
    const total_cost_span = document.querySelector("#total-price");
    const quantity = event.target.value;
    total_cost_span.innerHTML = (quantity * unit_price).toFixed(2);

    //Possible modification
    //price.toLocaleString('de-DE', { style: 'currency', currency: 'EUR' });
}


const product_cards = document.querySelectorAll(".product-card");
if (product_cards != null) {
    product_cards.forEach((c) => {
        c.addEventListener("click", (e) => {
            if (e.currentTarget.classList.contains("product-card")) {
                window.location = e.currentTarget.dataset.route;
            }
        }, false);
    });
}

function displayProducts(){
    let printerInput = document.getElementById('Impressora');
    let printerProducts = document.getElementsByClassName('Impressora');

    let computerInput = document.getElementById('Computador');
    let computerProducts = document.getElementsByClassName('Computador');

    let phoneInput = document.getElementById('Telemóvel');
    let phoneProducts = document.getElementsByClassName('Telemóvel');

    let tabletInput = document.getElementById('Tablet');
    let tabletProducts = document.getElementsByClassName('Tablet');

    if (!(printerInput.checked || computerInput.checked || phoneInput.checked || tabletInput.checked)) {
        changeDislay(printerProducts, "block");
        changeDislay(computerProducts, "block");
        changeDislay(phoneProducts, "block");
        changeDislay(tabletProducts, "block");
        return;
    }

    printerInput.checked ? changeDislay(printerProducts, "block") : changeDislay(printerProducts, "none");
    computerInput.checked ? changeDislay(computerProducts, "block") : changeDislay(computerProducts, "none");
    phoneInput.checked ? changeDislay(phoneProducts, "block") : changeDislay(phoneProducts, "none");
    tabletInput.checked ? changeDislay(tabletProducts, "block") : changeDislay(tabletProducts, "none");
}

function changeDislay(products, display){
    for (let product of products){
        product.style.display = display;
    }
}
