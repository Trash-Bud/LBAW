@extends('layouts.app')

@section('title', 'Formulário de mudança de password')

@section('content')
<form method="POST" class = "center" action="{{ url('/password/reset/' . $token)}}">
    {{ csrf_field() }}

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
      Alterar a password
    </button>
</form>
@endsection
