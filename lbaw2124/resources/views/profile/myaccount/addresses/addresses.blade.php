@extends('profile.myaccount.app')

@section('title', 'Moradas')

@section('c2')

<section class="scrolable_menu">

  <button  onclick="window.location='{{ url('/add_address') }}'"> Adicionar </button>
  @if ($errors->has('delete_address'))
    <span class="error">
        {{ $errors->first('delete_address') }}
    </span>
  @endif
  <div id = "addresses">
  @foreach($address_list as $address)
  <div class = "background_profile">
    <div> Rua: {{$address->street}}</div>
    <div> País: {{$address->country}}</div>
    <div> Código Postal: {{$address->postal_code}}</div>
    <button onclick="window.location='{{ url('edit_address/'. $address->id) }}'"> Editar </button>
    <button onclick="window.location='{{ url('delete_address/'. $address->id) }}'"> Apagar </button>
  </div>
  @endforeach
  </div>

</section>

@endsection
