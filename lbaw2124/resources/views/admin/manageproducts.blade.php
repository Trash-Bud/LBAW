@extends('admin.app')

@section('title', 'Gerir Categorias')

@section('c')

<section class = "pi_all">
    <div id = "topnav">
        <button class  = "nav_button myaccount_topnav link_with_icon" onclick="window.location='{{ url('/add_product') }}'"> <i class="fa fa-plus" aria-label="Adicionar produtos"></i> <span class = " big_viewport">Adicionar Produto</span></button>
        <button class  = "nav_button myaccount_topnav link_with_icon" onclick="window.location='{{ url('/products') }}'"> <i class="fa fa-list-alt" aria-label="Ver produtos" ></i> <span class = " big_viewport">Ver Produtos</span></button>
        <button class  = "nav_button myaccount_topnav link_with_icon" onclick="window.location='{{ url('/manage_categories') }}'"> <i class="fa fa-television" aria-label="Gerir categorias"></i> <span class = " big_viewport">Ver Categorias</span></button>
    </div>
    <section class = "pi_main">
        @yield('c2')
    </section>
</section>

@endsection
