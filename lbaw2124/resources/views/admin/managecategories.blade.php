@extends('admin.manageproducts')

@section('title', 'Gerir Categorias')

@section('c2')

<section class="info" id="manage_categories_info">
    <div id="manage_categories">
        @if ($errors->has('remove_category'))
        <span class="error">
            {{ $errors->first('remove_category') }}
        </span>
        @endif
        <h4>Categorias Existentes</h4>
        <div id="categories_info">
            @if(!empty($categories))
            @foreach($categories as $category)
                <div id="category">
                    <span>{{$category}}</span>
                    <form class="unblock" method="POST" action="{{route('removecategory')}}" enctype = "multipart/form-data">
                            {{ csrf_field() }}
                            <input id="name" type="hidden" name="name" value="{{$category}}">
                            <button id="btn_category" type="submit">Remover</button>
                    </form>
                </div>
            @endforeach
            @endif
        </div>
        <div id="add_category">
            <h4>Adicionar Categoria</h4>
            <form method="POST" action="{{ route('addcategory') }}"  enctype = "multipart/form-data">
                {{ csrf_field() }}
                @if ($errors->has('add_category'))
                <span class="error">
                    {{ $errors->first('add_category') }}
                </span>
                @endif
                <div class = "editinfo">
                    <label for="new_name">Nome: </label>
                    <input id="new_name" type="text" name="name" required autofocus>
                </div>
                @if ($errors->has('name'))
                <span class="error">
                    {{ $errors->first('name') }}
                </span>
                @endif

                <button type="submit" id="btn_category">Adicionar</button>
            </form>
        </div>
    </div>
</section>

@endsection
