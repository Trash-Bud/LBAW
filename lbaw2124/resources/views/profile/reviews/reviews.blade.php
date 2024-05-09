@extends('profile.app')

@section('title', 'Avaliações')

@section('c')

<section class="scrolable_menu">


    @foreach($review_list as $review)
    <div class = "background_profile">
      <div class = "left">
          <div>Avaliação: {{$review->rating}}
            @for ($i = 0; $i <5; $i++, $review->rating--)
              @if ($review->rating >0)
                  <span class="fa fa-star checked" aria-label="Rating Star Checked">></span>
              @else
                  <span class="fa fa-star" aria-label="Rating Star unchecked">></span>
              @endif
          @endfor
        </div>
          <div>Data: {{$review->date}}</div>
          <div>Descrição: {{$review->description}}</div>
      </div>
      <div class = "right">
        <img  src="{{asset('product_pictures/'. $review->photo)}}" alt="profile pic">
        <div><a  href = "products/{{$review->id_product}}">{{$review->name}}</a></div>
      </div>
    </div>
    @endforeach


  </section>

@endsection
