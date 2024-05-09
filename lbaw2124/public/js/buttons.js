


function show_pop_up_admin_delete(id){
    document.getElementById("delete_"+id).style.display = "flex";
}

function hide_pop_up_admin_delete(id){
    document.getElementById("delete_"+id).style.display = "none";
}

function toggle_nav(){
    const navButtons = document.getElementById('profile_nav');
    navButtons.classList.toggle('open');
}

function toggle_search(){
    const search = document.getElementById('search_toggle');
    search.classList.toggle('open');
}
function toggle_categories(){
    const categories = document.getElementById('product-sidebar');
    categories.classList.toggle('open');
}




function show_pop_up_user_delete(id){
    document.getElementById("user_delete").style.display = "flex";
}

function hide_pop_up_user_delete(id){
    document.getElementById("user_delete").style.display = "none";
}


function show_pop_up_order_cancel(){
    document.getElementById("order_cancel").style.display = "flex";
}

function hide_pop_up_order_cancel(id){
    document.getElementById("order_cancel").style.display = "none";
}

