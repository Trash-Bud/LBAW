@extends('profile.myaccount.app')

@section('title', 'Formulário de adicionar morada')

@section('c2')

<section class="scrolable_menu">
 <button onclick="window.location='{{ url('/addresses') }}'"> Voltar Atrás </button>
 <form method="POST" action="add_address">
    {{ csrf_field() }}
    @method('POST')
    <div>
        <div class = "editinfo">
            <label for = "street"> Rua: </label>
            <input name = "street" id = "street"/>
        </div>
        @if ($errors->has('street'))
        <span class="error">
            {{ $errors->first('street') }}
        </span>
        @endif

        <div class = "editinfo">
            <label for = "country"> País: </label>
            <input name = "country" id = "country"/>
        </div>
        @if ($errors->has('country'))
        <span class="error">
            {{ $errors->first('country') }}
        </span>
        @endif

        <div class = "editinfo">
            <label for = "postal_code"> Código Postal: </label>
            <input name = "postal_code" id = "postal_code" type = "number"/>
        </div>
        @if ($errors->has('postal_code'))
        <span class="error">
            {{ $errors->first('postal_code') }}
        </span>
        @endif
    </div>
    <button type="submit"> Adicionar </button>
</form>

</section>

@endsection
