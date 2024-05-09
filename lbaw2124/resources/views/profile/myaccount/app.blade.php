@extends('profile.app')

@section('title', 'Barra superior do perfíl')

@section('c')

<section class = "pi_all">
    <div id = "topnav">
        <button class  = "nav_button myaccount_topnav link_with_icon" onclick="window.location='{{ url('/personalinfo') }}'"> <i class="fa fa-address-card-o" aria-label="Conta"></i> <span class = " big_viewport">Informações Pessoais </span> </button>
        <button class  = "nav_button myaccount_topnav link_with_icon" onclick="window.location='{{ url('/addresses') }}'"> <i class="fa fa-address-book" aria-label="Moradas"></i> <span class = " big_viewport">Moradas</span></button>
        <button class  = "nav_button myaccount_topnav link_with_icon" disabled> <i class="fa fa-credit-card" aria-label="Metodos de Pagamento"></i> <span class = " big_viewport">Metodos de Pagamento</span></button>
    </div>
    <section class = "pi_main">
        @yield('c2')
    </section>
</section>

@endsection
