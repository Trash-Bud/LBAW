@extends('admin.app')

@section('title', 'Utilizadores')

@section('c')

<section class="info" id="users_menu">
    <div id="users_header">
        <div id="search_container">
            <form action="{{ url('/users/search') }}" method="GET" role="search">
                <input type="text" placeholder="Procurar utilizador.." name="search">
                <button type="submit">Procurar</button>
            </form>
        </div>
        <button onclick="window.location='{{ url('/users/create') }}'"> Criar novo utilizador </button>
    </div>
    <div id = "users">
        @foreach($users as $user)
            <div class = "user_window">
                <div class="user_info">
                    <p class = "user_names"> <a href = "users/orders/{{$user->id}}">{{$user->name}}</a> </p>
                    <p class = "break_words__when_small"> {{$user->email}} </p>
                    @if($user->blocked)
                        <form class="unblock" method="POST" action="{{route('unblock', $user->id)}}" enctype = "multipart/form-data">
                            {{ csrf_field() }}
                            @method('PUT')
                            <button type="submit"> Desbloquear </button>
                        </form>
                    @else
                        <form class="block" method="POST" action="{{route('block', $user->id)}}" enctype = "multipart/form-data">
                            {{ csrf_field() }}
                            @method('PUT')
                            <button type="submit"> Bloquear </button>
                        </form>
                    @endif
                </div>
                <div class="buttons">
                    <button onclick="window.location='{{route('view', $user->id)}}'"> Editar </button>
                    <button onclick="show_pop_up_admin_delete({{$user->id}})"> Apagar </button>
                </div>
                <div id = "delete_{{$user->id}}" class = "pop_up center">
                    Tem a certeza que quer apagar este utilizador?
                    <div class = "warning">Esta ação não pode ser revertida.</div>

                    <form method="POST" action="{{url('delete_account/'.$user->id)}}" enctype = "multipart/form-data">
                        {{ csrf_field() }}
                        @method('DELETE')
                        <button type = "submit"> Apagar {{$user->name}}</button>
                    </form>

                    <button onclick="hide_pop_up_admin_delete({{$user->id}})">Voltar</button>
                </div>
            </div>
        @endforeach
    </div>
</section>

@endsection
