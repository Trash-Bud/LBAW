@extends('layouts.app')

@section('title', 'Wishlist')

@section('content')

    <section class = "profile">
        <div id="profile_nav">

            <button class="nav_button profile_side_nav small_viewport" onclick="toggle_nav()"><i class="fa fa-bars" ></i></button>
            <button class="nav_button profile_side_nav link_with_icon" onclick="window.location='{{ url('/personalinfo') }}'"> <i class="fa fa-user-circle-o" aria-label="A minha conta" ></i><span class = "break_words__when_small"> A minha
                conta</span>
            </button>
            <button class="nav_button profile_side_nav link_with_icon" onclick="window.location='{{ route('orders') }}'"> <i class="fa fa-envelope" aria-label="As minhas encomendas"></i><span class = "break_words__when_small"> As
                minhas
                encomendas</span>
            </button>

            <button class="nav_button profile_side_nav link_with_icon" onclick="window.location='{{ url('/reviews') }}'"> <i class="fa fa-pencil" aria-label="As minhas avaliações" ></i> <span class = "break_words__when_small">As minhas
                avaliações</span>
            </button>

            <button class="nav_button profile_side_nav link_with_icon" onclick="window.location='{{ url('/wishlist') }}'"> <i class="fa fa-heart" aria-label="A minha wishlist"></i><span class = "break_words__when_small"> A minha
                wishlist</span>
            </button>

            <button class="nav_button profile_side_nav link_with_icon" onclick="window.location='{{ url('/notifications') }}'"> <i class="fa fa-bell" aria-label="As minhas notificações"></i><span class = "break_words__when_small"> As
                minhas notificações</span>
            </button>


        </div>
        <section class = "main">
            @yield('c')
        </section>
    </section>

@endsection
