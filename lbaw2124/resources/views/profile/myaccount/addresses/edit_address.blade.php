@extends('profile.myaccount.app')

@section('title', 'Formulário de editar morada')

@section('c2')

<section class="scrolable_menu">
 <button onclick="window.location='{{ url('/addresses') }}'"> Back </button>
 <form method="POST" action="{{ url('edit_address/' . $address->id)}}">
    {{ csrf_field() }}
    @method('PUT')
    <div>
        <div class = "editinfo">
            <label for = "street"> Rua: </label>
            <input name = "street" id = "street" value = "{{$address->street}}"/>
        </div>
        @if ($errors->has('street'))
        <span class="error">
            {{ $errors->first('street') }}
        </span>
        @endif

        <div class = "editinfo">
            <label for = "country"> País: </label>
            <input name = "country" id = "country" value = "{{$address->country}}"/>
        </div>
        @if ($errors->has('country'))
        <span class="error">
            {{ $errors->first('country') }}
        </span>
        @endif

        <div class = "editinfo">
            <label for = "postal_code"> Código Postal: </label>
            <input name = "postal_code" id = "postal_code" type = "number" value = "{{$address->postal_code}}"/>
        </div>
        @if ($errors->has('postal_code'))
        <span class="error">
            {{ $errors->first('postal_code') }}
        </span>
        @endif
    </div>
    <button type="submit"> Editar </button>
</form>

</section>

@endsection
