@extends('layouts.app')

@section('title', 'Formulário de registo')

@section('content')
<form method="POST" class = "center" action="{{ route('register') }}">
    {{ csrf_field() }}

    <label for="name">Nome</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
    @if ($errors->has('name'))
      <span class="error">
          {{ $errors->first('name') }}
      </span>
    @endif

    <label for="email">Endereço de email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <label for="password-confirm">Confirmar Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" required>

    <button type="submit">
      Criar conta
    </button>
</form>
@endsection
