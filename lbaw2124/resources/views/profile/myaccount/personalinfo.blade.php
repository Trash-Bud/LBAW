@extends('profile.myaccount.app')

@section('title', 'Informação da conta')

@section('c2')

<section id="personal_info">

    @if (Auth::guard('web')->check())

        <div>
            <div class = "showinfo">
            <img src="{{asset('profile_pictures/'. Auth::user()->profile_pic)}}" alt="profile pic">
            <div>Nome: {{ Auth::user()->name }}</div>
            <div>Email: {{ Auth::user()->email }}</div>
            <div>NIF: {{ Auth::user()->nif }}</div>
            <div>Saldo: {{ Auth::user()->account_credit }} €</div>
            @if (Auth::user()->blocked)
                <form method="POST" action="{{ url('appeal_block/') }}" enctype = "multipart/form-data">
                    {{ csrf_field() }}
                    <button  type = "submit" class = "responsive_width_buttons"> Apelar para desbloqueio. </button>
                </form>
                @if ($errors->has('block'))
                    <span class="error">
                        {{ $errors->first('block') }}
                    </span>
                @endif
            @endif
            <button class="responsive_width_buttons" onclick="show_pop_up_user_delete()"> Apagar Conta </button>
            </div>

        </div>
    <form method="POST" action="{{ route('personalinfo') }}" enctype = "multipart/form-data" class = "edit_form">
        {{ csrf_field() }}
        @method('PUT')

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
                <input name = "name" id = "name" value = "{{Auth::user()->name}}" />
            </div>
            @if ($errors->has('name'))
            <span class="error">
                {{ $errors->first('name') }}
            </span>
            @endif
            <div class = "editinfo">
                <label for = "email">Email:</label>
                <input  name = "email" id = "email" type = "email" value = "{{Auth::user()->email}}" />
            </div>
            @if ($errors->has('email'))
            <span class="error">
                {{ $errors->first('email') }}
            </span>
            @endif
            <div class = "editinfo">
                <label for = "nif">NIF: </label>
                <input name = "nif" id = "nif" type = "number" value = "{{Auth::user()->nif}}" />

            </div>
            @if ($errors->has('nif'))
            <span class="error">
                {{ $errors->first('nif') }}
            </span>
            @endif
            @if ($errors->has('invalid_nif'))
            <span class="error">
                {{ $errors->first('invalid_nif') }}
            </span>
            @endif
            <div class = "editinfo">
                <label for = "password">Password Nova:</label>
                <input name = "password" id = "password" type="password" />

            </div>
            <div class = "editinfo">
                <label for = "password_confirmation">Confirme a Password Nova:</label>
                <input name = "password_confirmation" id = "password_confirmation" type="password"/>

            </div>
                @if ($errors->has('password'))
                <span class="error">
                    {{ $errors->first('password') }}
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
            <button type="submit" class="responsive_width_buttons"> Editar </button>
    </form>
    <div id = "user_delete" class = "pop_up center">
        Tem a certeza que quer apagar este utilizador?
        <div class = "warning">Esta ação não pode ser revertida.</div>

        <form method="POST" action="{{url('delete_account/'.Auth::user()->id)}}" enctype = "multipart/form-data" class = "edit_form">
            {{ csrf_field() }}
            @method('DELETE')
            <button type = "submit"> Apagar {{Auth::user()->name}}</button>
        </form>
        <button onclick="hide_pop_up_user_delete()">Voltar</button>
    </div>
@endif

</section>

@endsection
