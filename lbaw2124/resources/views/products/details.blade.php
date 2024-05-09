@extends('layouts.app')

@section('title', $product->name)

@section('content')

    @if(isset($product))

        <div class="details-page">
            <div class="main-details-area">
                <x-products.productDetailsCard :product=$product :rating=$rating :reviews=$reviews/>
                    <div class="details-actions">
                        <p>Preço por unidade: <span id="unit-price">{{$product->price}}</span> €</p>
                        @if(!Auth::guard('admin')->check())
                            @if($product->stock > 0)
                                <p>Preço total: <span id="total-price">0</span> €</p>
                                <div class="add-to-form">
                                    <!-- change to max quantity-->
                                    <label for="product-quantity">Quantidade</label>
                                    <input id="product-quantity" placeholder="1" type="number" min=1
                                           max={{$product->stock}}>
                                    <button id="add-to-cart-btn" class="btn link_with_icon" data-id="{{$product->id}}"> <i class="fa fa-shopping-basket"  aria-label="Adicionar ao Carrinho"></i>
                                        <span class = "big_viewport">Adicionar ao
                                        Carrinho</span>
                                    </button>
                                </div>
                                <!--Should this be done in the blade view or the Controller???-->
                            @else
                                <p>Este produto não existe em stock de momento</p>
                            @endif

                            @if(Auth::user())
                                <button class="add-to-wishlist-btn" id="wishlist-details-add"
                                        data-id="{{$product->id}}"> <i class="fa fa-heart" aria-label="Adicionar a Wishlist"></i> <span class = "big_viewport">
                                    Adicionar à WishList</span>
                                </button>
                            @endif

                        @else
                            <p>Stock: {{$product->stock}}</p>
                        @endif
                        @if(Auth::guard('admin')->check())
                            <button class="btn btn-dark" id="edit-btn"
                                    onclick="window.location='{{route('editproductform', $product->id)}}'">Editar
                            </button>
                        @endif
                    </div>

            </div>
            <div class="details-text-area">
                <div class="description">
                    <h3>Características</h3>
                    <ul>
                        @foreach($attributes as $attr)
                            <li><span>{{$attr->attribute_type}}:</span> <span>{{$attr->value}}</span></li>
                        @endforeach
                    </ul>
                </div>
            </div>


            <div class="reviews-section">
                <h3>Avaliações</h3>
                @if (count($reviews)<1 && is_null($my_review))
                    <p>Ainda não foi feita nenhuma avaliação...</p>
                    @if($has_bought)
                        <x-products.addReviewCard :product=$product/>
                            @endif
                            @else
                                @if(!is_null($my_review))
                                    <h4>A minha avaliação: </h4>
                                    <div id="own-review-box" class="own-review own-review-info">
                                        <x-products.myReviewCard :review=$my_review/>
                                    </div>
                                    <x-products.editReviewCard :review=$my_review/>
                                        @else
                                            @if($has_bought)
                                                <p>Gostaste do producto?</p>
                                                <x-products.addReviewCard :product=$product/>
                                                    @endif
                                                    @endif
                                                    <h4>Avaliações</h4>
                                                    <ul class="reviews-list">
                                                        @foreach($reviews as $review)
                                                            <x-products.reviewInfoCard :review=$review/>
                                                        @endforeach
                                                    </ul>
                    @endif
            </div>

        </div>
    @endif
@endsection
