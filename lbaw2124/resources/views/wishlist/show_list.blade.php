@extends('profile.app')

@section('title', 'Wishlist')

@section('c')
    @if(isset($products))
        <div id="wishlist-page">
            <h1>User wishlist</h1>
            <ul id="user-wishlist">
                @foreach($products as $product)
                    {{--product name categoryprice--}}
                    <li class="wishlist-card" data-product="{{$product->id}}">
                        <div class="product-name"
                             data-route="{{ route('product.details', [$product->id])}}"> {{$product->name}}</div>
                        <div class="product-price-col"><span class="product-price">{{$product->price}}</span>€</div>
                        <div class="product-category">{{$product->category}}</div>
                        <button data-product="{{$product->id}}" class="add-to-cart-wishlist"><i class="fa fa-shopping-basket" aria-hidden="true"></i></button>
                        <button class="delete-from-wishlist" data-product="{{$product->id}}">×</button>
                    </li>
                @endforeach
                </ul>
            <button class="remove-all-from-wishlist wishlist-details-add">Esvaziar</button>
            @else
                <p> A sua wishlist está vazia! Comece a adicionar produtos.</p>
            @endif
        </div>
@endsection
