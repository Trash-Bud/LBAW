@extends('admin.manageproducts')

@section('title', 'Editar Produto')

@section('c2')

<section class="info" id="edit_product">

    <div id="product_info">
        <p id="info">Informação Atual</p>
        <img id="product_image" src="{{asset('product_pictures/'.$product->photo)}}"alt="Card image cap">
        <p>Nome:        <div>{{$product->name}}</div></p>
        <p>Descrição:   <div>{{$product->description}}</div></p>
        <p>Preço:       <div>{{$product->price}} €</div></p>
        <p>Stock:       <div>{{$product->stock}}</div></p>
        <p>Desconto:    @if($product->on_sale)
                            <div>{{round(100 - ($product->price/$product->original_price)*100)}}%</div>
                        @else
                            <div>Não</div>
                        @endif
                         </p>
    </div>

    <div id="form_edit_product">
        <form method="POST" action="{{ route('editproduct', $product->id) }}" enctype = "multipart/form-data">
            {{ csrf_field() }}
            @method('PUT')

            <div class = "editinfo">
                <label for = "photo"> Imagem: </label>
                <input name = "photo" id = "photo" type = "file"/>
            </div>
            @if ($errors->has('photo'))
            <span class="error">
                {{ $errors->first('photo') }}
            </span>
            @endif
            
            <div class = "editinfo">
                <label for="name">Nome: </label>
                <input id="name" type="text" name="name" value='{{$product->name}}' autofocus>
            </div>
            @if ($errors->has('name'))
            <span class="error">
                {{ $errors->first('name') }}
            </span>
            @endif

            <div class = "editinfo">
                <label for="description">Descrição: </label>
                <textarea name="description" cols="40" rows="3"></textarea>
            </div>

            <div class = "editinfo">
                <label for="stock">Stock: </label>
                <input name="stock" id="stock" type="number" value='{{$product->stock}}'>
            </div>

            <div class = "editinfo">
                <label for="price">Preço (€): </label>
                <input name="price" id="price" type="number" step="0.01" value='{{$product->price}}'>
            </div>

            <button type="submit">Editar Produto</button>
        </form>
    </div>
</section>

@endsection
