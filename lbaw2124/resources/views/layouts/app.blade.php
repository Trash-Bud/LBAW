<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="{{ asset('css/milligram.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{asset('css/products.css')}}" rel="stylesheet">
    <link href="{{asset('css/orders.css')}}" rel="stylesheet">
    <link href="{{asset('css/shopping_cart.css')}}" rel="stylesheet">
    <link href="{{asset('css/profile.css')}}" rel="stylesheet">
    <link href="{{asset('css/reviews.css')}}" rel="stylesheet">
    <link href="{{asset('css/wishlist.css')}}" rel="stylesheet">
    <link href="{{asset('css/static_pages.css')}}" rel="stylesheet">

    <link href='https://fonts.googleapis.com/css?family=Roboto Slab' rel='stylesheet'>
    <script type="application/javascript " src={{ asset('js/app.js') }} defer></script>
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>

    <!--TODO:remove and switch to newer version using npm-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous" defer></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous" defer></script><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--TODO:include in specific viewer-->
    <script type="text/javascript " src="{{URL::asset('js/products.js')}}" defer></script>
    <script type="text/javascript" src="{{URL::asset('js/shopping_cart.js')}}" defer></script>
    <script type="text/javascript" src="{{URL::asset('js/buttons.js')}}" defer></script>
    <link rel="stylesheet" type="css" href="boostrap.min.css">
</head>
  <body>
    <main>
            <header>
                <h1 id = title><a href="{{ url('/products') }}"><span class = "big_viewport unbroken_text">Feup-Tech </span><i  class="fa fa-home small_viewport"  aria-label="Pagina Inicial"></i></a></h1>
                <div id = "search_bar" class = "header_part">
                    <i class="fa fa-search-plus small_viewport"  onclick="toggle_search()" aria-label="Expandir pesquisar"></i>

                    <form class = "search_form_big big_viewport"  action="{{ url('/products/search') }}" method="GET" role="search">
                        <label for="search" >Pesquisar:</label>
                        <input class="search" name = "search" id="search"><br><br>
                    </form>

                </div>
                @if (!Auth::guard('admin')->check())
                <div id = "shop_cart" class = "header_part">
                    <a  href="{{ url('/shoppingcart') }}" class = "link_with_icon"><span class = " big_viewport">Carrinho</span>
                    <i class="fa fa-shopping-basket"  aria-label="Carrinho"></i></a>

                </div>

                @endif

                @if (Auth::check())


                <a id = "header_user" href="{{ url('/personalinfo') }}">  <img  src="{{asset('profile_pictures/'. Auth::user()->profile_pic)}}" alt="profile pic">   {{ Auth::user()->name }} </a>

                <a class="header_auth" href="{{ url('/logout') }}"> <span class = "big_viewport">Log out</span> <i class="fa fa-sign-out " aria-label="Logout"></i></a>

                @elseif (Auth::guard('admin')->check())
                    <a  id = "header_user" href="{{ url('/admin') }}"> <img src="{{asset('profile_pictures/'. Auth::guard('admin')->user()->profile_pic)}}" alt="profile pic"> {{ Auth::guard('admin')->user()->name }}</a>

                <a class="header_auth" href="{{ url('/logout') }}"> <span class = "big_viewport">Log out</span> <i class="fa fa-sign-out " aria-label="Logout"></i> </a>
                @else
                    <a class="header_auth" href="{{ url('/login') }}"> <span class = "big_viewport">Log in</span>  <i class="fa fa-sign-in " aria-label="Login"></i></a>
                    <a class="header_auth" href="{{ url('/register') }}"> <span class = "big_viewport">Criar conta</span> <i class="fa fa-user-plus "  aria-label="Registar"></i></a>
                @endif
            </header>
                <div id = "search_toggle" class = "small_viewport">
                    <form class = "search_form_small" action="{{ url('/products/search') }}" method="GET" role="search">
                        <input class="search" name="search">
                        <button type= "submit"> <i class="fa fa-search small_viewport"  onclick="toggle_search()"  aria-label="Pesquisa"></i></button>
                    </form>
                </div>
                <section id="content" class="height_content base_layout">
                    @yield('content')
                </section>

                <div id="footer">
                    <a href="{{ url('/contacts') }}" class = "link_with_icon"> <span class = "big_viewport ">Contactos</span>  <i class="fa fa-phone"  aria-label="Contactos"></i></a>
                    <a href="{{ url('/faq') }}" class = "link_with_icon"> <span class = "big_viewport ">Questões Frequentes</span> <i class="fa fa-question-circle-o"  aria-label="FAQ"></i> </a>
                    <a href="{{ url('/aboutus') }}" class = "link_with_icon"><span class = "big_viewport "> Sobre Nós </span><i class="fa fa-users" aria-label="Sobre Nos" ></i> </a>
                </div>


</main>

</body>
</html>
