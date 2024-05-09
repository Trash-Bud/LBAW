@extends('admin.manageproducts')

@section('title', 'Adicionar Produto')

@section('c2')

<section class="info" id="add_product">
    <form method="POST" action="{{ route('addproduct') }}"  enctype = "multipart/form-data">
        {{ csrf_field() }}

        <div class = "editinfo">
            <label for = "photo"> Imagem: </label>
            <input name = "photo" id = "photo" type = "file" required/>
        </div>
        @if ($errors->has('photo'))
        <span class="error">
            {{ $errors->first('photo') }}
        </span>
        @endif

        <div class = "editinfo">
            <label for="name">Nome: </label>
            <input id="name" type="text" name="name" required autofocus>
        </div>
        @if ($errors->has('name'))
        <span class="error">
            {{ $errors->first('name') }}
        </span>
        @endif

        <div class = "editinfo">
            <label for="description">Descrição: </label>
            <textarea id = "description" name="description" cols="40" rows="3"></textarea>
        </div>

        <div class = "editinfo">
            <label for="stock">Stock: </label>
            <input name="stock" id="stock" type="number" required>
        </div>

        <div class = "editinfo">
            <label for="original_price">Preço (€): </label>
            <input name="original_price" id="original_price" type="number" step="0.01" required>
        </div>

        <div class = "editinfo">
            <label for="choose_category">Categoria: </label>
            <select id = "choose_category" name="category" class="select" required>
                <option value="">Escolha uma Categoria</option>
                @if(!empty($categories))
                @foreach($categories as $category)
                    <option value="{{$category}}">{{$category}}</option>
                @endforeach
                @endif
            </select>
        </div>

        <div class = "editinfo">
            <label for="id_warehouse">Armazém: </label>
            <select id ="id_warehouse" name="id_warehouse" class="select" required>
                <option value="">Escolha um Armazém</option>
                @if(!empty($warehouses))
                @foreach($warehouses as $warehouse)
                    <option value="{{$warehouse->id}}">{{$warehouse->code}}-{{$warehouse->location}}</option>
                @endforeach
                @endif
            </select>
        </div>

        <button type="submit">Adicionar Produto</button>
    </form>
</section>

@endsection
