<div class="card details-card">
    <img class="card-img-details" src="{{asset('product_pictures/'.$product->photo)}}"
         alt="{{$product->name}}">
    <div class="card-body">
        <h5 class="card-title">{{$product->name}}</h5>
        <p class="card-text">
            {{$product->description}}
        </p>
        <p>Avaliação
            @if ($rating == 0 )
                <span>: Não existe nenhuma avaliação para este produto.
                            </span>
            @else
                <span>{{$rating}}</span>
                @for ($i = 0; $i <5; $i++, $rating--)
                    @if ($rating >0)
                        <span class="fa fa-star checked" aria-label="Rating Star Checked"></span>
                    @else
                        <span class="fa fa-star" aria-label="Rating Star Uncheck">></span>
                    @endif
                @endfor

            @endif
        </p>
    </div>
</div>
