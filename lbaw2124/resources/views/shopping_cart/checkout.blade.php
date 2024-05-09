@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div id="checkout_page">
        <h1>Checkout</h1>
        <div>
            <form method="POST" action="{{ route('checkout') }}" enctype = "multipart/form-data">
            {{ csrf_field() }}
            @method('PUT')
                <label for="address">Endereço de Entrega</label>
                <select id = "address" name="address" class="select">
                    @if(!empty($addresses))
                    @foreach($addresses as $address)
                    <option value="{{$address->id}}">{{$address->street}}</option>
                    @endforeach
                    @endif
                </select>
                <button type=button onclick="window.location='{{ url('/addresses') }}'">Adicionar morada</button>

                <label for="payment">Método de Pagamento</label>
                <select id = "payment" name="payment" class="select">
                    <option value="Stripe">Stripe</option>
                    <option value="Paypal">Paypal</option>
                    <option value="Account_credit">Account Credit</option>
                </select>

                <input type = "hidden" name = "total" value = "{{$total}}" />

                <div id="total_price">
                    <h4>Total</h4>
                    <p>{{$total}} €</p>
                </div>

                <button type=submit>Comprar</button>
                <button type=button onclick="window.location='{{ url('/shoppingcart') }}'">Voltar ao carrinho</button>
            </form>
            </div>
        </div>
@endsection
