@extends('layouts.app')

@section('title', 'Carrinho de compras')

@section('content')
    @if(isset($products))


        <div id="shopping-cart-page">
            <h1>Carrinho de Compras</h1>
            <ul id="shopping-cart-list">
            @foreach($products as $product)
                @php
                    //data structure for Auth user is model and session is Collection/array type struture
                    if(!Auth::user()){ $amount = $product["amount"]; $product = $product["item"];}
                    else {$amount = $product->pivot->amount;}
                @endphp
                <!--add event listener for details and change according to the products already in shopping cart-->
                    @if ($product->stock === 0)
                    <li id = "no_stock" class="shopping-cart-card" data-product="{{$product->id}}">
                        <div class="product-name unbroken_text"
                             data-route="{{ route('product.details', [$product->id])}}"> {{$product->name}}</div>
                        <div class="product-price-col"><span class="product-price">{{$product->price}}</span>€</div>

                        <!--place placeholder at current amount: PUT REQUEST HERE-->
                        <input type="number" data-product="{{$product->id}}" class="product-quantity" min=1
                               max="{{$product->stock}}" value="{{$product->pivot->amount}}" disabled><span>Sem Stock</span>

                        <!--Change to cross image: DELETE REQUEST HERE <i class="fa-light fa-trash-can">-->
                        <button class="btn-close delete-from-cart" data-product="{{$product->id}}">&times;</button>
                    </li>
                    @else
                    <li class="shopping-cart-card" data-product="{{$product->id}}">
                        <div class="product-name unbroken_text"
                             data-route="{{ route('product.details', [$product->id])}}"> {{$product->name}}</div>
                        <div class="product-price-col"><span class="product-price">{{$product->price}}</span>€</div>

                       <!--place placeholder at current amount: PUT REQUEST HERE-->

                       <input type="number" data-product="{{$product->id}}" class="product-quantity" min=1
                              max="{{$product->stock}}" value="{{$amount}}">

                        <!--Change to cross image: DELETE REQUEST HERE <i class="fa-light fa-trash-can">-->
                        <button class="btn-close delete-from-cart" data-product="{{$product->id}}">&times;</button>
                    </li>
                    @endif
                @endforeach
            </ul>
            @if ($errors->has('no_stock'))
            <span class="error">
                {{ $errors->first('no_stock') }}
            </span>
            @endif

            <div class="shopping-cart-page-footer">
                <div>Total: <span id="total-cart-price">{{$total}}</span> €</div>
                <!--IS THIS A FORM???-->
                <form action="{{ route('show_checkout') }}" method="post">
                    {{ csrf_field() }}
                    <input id = "total_checkout" type = "hidden" name = "total" value = "{{$total}}" />
                    <button class="btn"> Checkout </button>
                </form>
                <!--DELETE REQUEST HERE-->
                <button class="btn btn-dark" id="empty-cart">Remover Todos</button>
            </div>
        </div>

    @else
        <p>O teu carrinho de compras está vazio. Começa por adicionar produtos.</p>
    @endif
@endsection
