@extends('admin.app')

@section('title', 'Formulário de edição de utilizadores.')

@section('c')

<section class="info" id="edit_user_menu">
    <h3 id="user_info_title"> Informações da Conta de {{ $user->name }}</h3>
    <div id="edit_user_info">
        <div class = "showinfo">
            <img src="{{asset('profile_pictures/'. $user->profile_pic)}}" alt="profile pic">
            <div>Nome: {{ $user->name }}</div>
            <div>Email: {{ $user->email }}</div>
            <div>NIF: {{ $user->nif }}</div>
            <div>Saldo: {{ $user->account_credit }} €</div>
        </div>
        <form method="POST" action="{{route('edit', $user->id)}}" enctype = "multipart/form-data">
        {{ csrf_field() }}
        @method('PUT')
            <div>
                <div class = "editinfo">
                    <label for = "profile_pic"> Imagem de perfil: </label>
                    <input name = "profile_pic" id = "profile_pic" type = "file"/>
                </div>
                @if ($errors->has('profile_pic'))
                <span class="error">
                    {{ $errors->first('profile_pic') }}
                </span>
                @endif

                <div class = "editinfo">
                    <label for = "name" >Nome: </label>
                    <input name = "name" id = "name"/>
                </div>
                @if ($errors->has('name'))
                <span class="error">
                    {{ $errors->first('name') }}
                </span>
                @endif

                <div class = "editinfo">
                    <label for = "email">Email:</label>
                    <input  name = "email" id = "email" type = "email" />
                </div>
                @if ($errors->has('email'))
                <span class="error">
                    {{ $errors->first('email') }}
                </span>
                @endif

                <div class = "editinfo">
                    <label for = "current_password" >Password Atual: </label>
                    <input name = "current_password" id = "current_password" type="password" required />
                </div>
                @if ($errors->has('current_password'))
                <span class="error">
                    {{ $errors->first('current_password') }}
                </span>
                @endif

            </div>
            <button type="submit"> Editar </button>
        </form>
    </div>
</section>

@endsection
