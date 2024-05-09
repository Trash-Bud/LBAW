@extends('profile.app')

@section('title', 'MyAccount')

@section('c')

<section class="scrolable_menu">


    @foreach($wish_list as $product)

      <div class = "background_profile">  
        <div class = "left">
          <div>Nome: {{$product->name}}</div>
          <div>Categoria: {{$product->category}}</div>
          <div>Preço: {{$product->price}}€</div>
        </div>
        <div> </div>
        <div class = "right">
          <img  src="{{asset('product_pictures/'. $product->photo)}}" alt="profile pic">
          <div><a  href = "products/{{$product->id}}">{{$product->name}}</a></div>
        </div>  
      </div>

    @endforeach

   
  </section>

@endsection