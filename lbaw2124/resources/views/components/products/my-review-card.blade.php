<div class="review-info">
    <div>
        <div class="review-stars">
            @for ($i = 0; $i < 5; $i++)
                @if ($my_review->rating <= $i)
                    <span class="fa fa-star"></span>
                @else
                    <span class="fa fa-star checked"></span>
                @endif
            @endfor
        </div>
        <div class="review_date">Data: {{$my_review->date}}</div>
    </div>
    @if (!empty($my_review->description))
        <div>
            <span class="bold">Descrição:</span>
            <span>{{$my_review->description}}</span>
        </div>
    @endif
</div>
<button class="review-btn" id="edit-review-btn">Editar Avaliação</button>
<form class="delete-review-form" method="POST"
      action="{{ url('/review/' . $my_review->id)}}">
    {{ csrf_field() }}
    @method('DELETE')
    <button class="delete-btn review-btn">&cross;</button>
</form>
