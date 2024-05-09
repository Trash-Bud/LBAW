@extends('layouts.app')

@section('title', 'Página inicial')

@section('content')

    <div id="product-sidebar">
        <div id="product-sidebar-checkbox">
            <button id="product-sidebar-checkbox-header" onClick="toggle_categories()">
                Categorias
            </button>
            <div id="product-sidebar-checkbox-body">
                <form action="{{ url('/products/category') }}">
                    @foreach($categories as $category)
                        <div>
                            <input onChange="displayProducts()" type="checkbox" class="categories"
                            id="{{$category->category}}" name="category" value="{{$category->category}}">
                            <label for={{$category->category}}>{{$category->category}}</label>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>
    </div>

    @if(count($products)>0)
        <div class="product-list">
            @foreach($products as $product)
                <div class="product-card text-center ml-md-auto {{$product->category}}"
                data-id = {{$product->id}} data-route="{{ route('product.details', [$product->id])}}" style="display: block">
                    <div class="card-header">
                        {{$product->category}}
                    </div>
                    <img class="card-img-homepage"
                         src="{{asset('product_pictures/'.$product->photo)}}"
                         alt="{{$product->name}}">
                    <div class="card-body">
                        <h5 class="card-title">{{$product->name}}</h5>
                        <p class="card-text">{{$product->price}} €</p>
                        {{--<a href="{{ route('product.details', [$product->id])}}"
                           class="btn btn-dark product-details-link">Product details</a>--}}
                        <div class="product-card-btns">
                            <button class = "add-to-cart-btn ">Adicionar ao Carrinho</button>
                            @if(Auth::user())
                                <button class="add-to-wishlist-btn add-to-wishlist-btn-home"><i class="fa fa-heart" aria-label="Adicionar a wishlist"></i></button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        <h1 class = "no_product_message">Nenhum produto foi encontrado.</h1>
    @endif
@endsection
