function change_total_price(event) {
    const unit_price = document.querySelector("#unit-price").innerHTML;
    const total_cost_span = document.querySelector("#total-price");
    const quantity = event.target.value;
    total_cost_span.innerHTML = (quantity * unit_price).toString();
}

const quantitySelector = document.querySelector('#product-quantity');
if (quantitySelector != null){
    quantitySelector.value = 1; //place default value
    quantitySelector.addEventListener("change", change_total_price);
}
