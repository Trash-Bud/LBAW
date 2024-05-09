@extends('layouts.app')

@section('title', 'Formulário de Log in')

@section('content')
<form method="POST" class = "center" action="{{ route('login') }}">
    {{ csrf_field() }}

    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
    @if ($errors->has('email'))
        <span class="error">
          {{ $errors->first('email') }}
        </span>
    @endif

    <label for="password" >Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
    @endif

    <label>
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Lembra-te de mim
    </label>

    <button type="submit">
        Login
    </button>
    <div><a href="{{ route('password.request') }}"><u>Esqueci-me da minha password</u></a></div>
</form>
@endsection
