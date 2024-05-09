@extends('layouts.app')

@section('title', 'Formulário de recuperação da password')

@section('content')
<form method="POST" class = "center" action="{{ route('password.email') }}">
    {{ csrf_field() }}

    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
    @if ($errors->has('email'))
        <span class="error">
          {{ $errors->first('email') }}
        </span>
    @endif

    <button type="submit">
        Recuperar a password
    </button>
    <div><a href="{{ route('login') }}"><u>Voltar para o Login</u></a></div>
</form>
@endsection
