@extends('layouts.app')


@section('content')
        <div id="product-sidebar">
            <div id="product-sidebar-checkbox">
                <div id="product-sidebar-checkbox-header">
                    Categorias
                </div>
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
        <div>
            @yield('products')
        </div>
@endsection
