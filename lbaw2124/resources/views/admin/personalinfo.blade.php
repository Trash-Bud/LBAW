@extends('admin.app')

@section('title', 'Informação de conta')

@section('c')

<section class="info" id="admin_info">
    <h3 id=admin_info_title> Informações da Conta </h3>
    <form method="POST" action="{{ route('update') }}" enctype = "multipart/form-data"  class = "edit_form">
        {{ csrf_field() }}
        @method('PUT')

            <div class = "editinfo">
                <label for = "name" >Nome: </label>
                <input name = "name" id = "name" placeholder = "{{ Auth::guard('admin')->user()->name }}"/>
            </div>
            @if ($errors->has('name'))
                <span class="error">
                    {{ $errors->first('name') }}
                </span>
            @endif

            <div class = "editinfo">
                <label for = "email"> Email:</label>
                <input  name = "email" id = "email" type = "email" placeholder = "{{ Auth::guard('admin')->user()->email }}"/>
            </div>
            @if ($errors->has('email'))
                <span class="error">
                    {{ $errors->first('email') }}
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


        <button type="submit"> Editar </button>
    </form>
</section>

@endsection
