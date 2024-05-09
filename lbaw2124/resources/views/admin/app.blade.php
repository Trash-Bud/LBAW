@extends('layouts.app')

@section('title', 'Perfíl')

@section('content')

<section class = "profile">
        <div id="profile_nav">
            <button class="nav_button profile_side_nav small_viewport" onclick="toggle_nav()"><i class="fa fa-bars"
                                                                                                 aria-label="Expandir menu"></i>
                                                                                                </button>

            <button class="nav_button profile_side_nav link_with_icon"
                    onclick="window.location='{{ url('/dashboard') }}'"><i class="fa fa-bell" aria-label="Painel de notificacões"> </i> <span class = "break_words__when_small">Painel
                de notificações
            </span></button>

            <button class="nav_button profile_side_nav link_with_icon" onclick="window.location='{{ url('/admin') }}'">
                <i class="fa fa-user-circle-o" aria-label="Conta"></i> <span class = "break_words__when_small">A minha conta
                </span></button>

            <button class="nav_button profile_side_nav link_with_icon" onclick="window.location='{{ url('/users') }}'">
                <i class="fa fa-user" aria-label="Utilizadores"></i > <span class = "break_words__when_small" >Utilizadores
                </span></button>

            <button class="nav_button profile_side_nav link_with_icon"
                    onclick="window.location='{{ url('/all_orders') }}'"><i class="fa fa-envelope" aria-label="Encomendas"
                                                                            ></i> <span class = "break_words__when_small">Encomendas
                                                                            </span></button>

            <button class="nav_button profile_side_nav link_with_icon"
                    onclick="window.location='{{ url('/history') }}'"><i class="fa fa-history" aria-label="Histórico"></i>
                    <span class = "break_words__when_small">Historico</span>
            </button>

            <button class="nav_button profile_side_nav link_with_icon" onclick="window.location='{{ url('/manage_products') }}'"> <i class="fa fa-pencil-square" aria-label="Productos"></i> <span class = "break_words__when_small"> Gerir
                Produtos</span>
            </button>

            <button class="nav_button profile_side_nav link_with_icon" disabled><i class="fa fa-building" aria-label="Fornecedores"></i> <span class = "break_words__when_small">Armazéns
            </span></button>

        </div>
  <section class = "main">
            @yield('c')
        </section>
    </section>

@endsection
