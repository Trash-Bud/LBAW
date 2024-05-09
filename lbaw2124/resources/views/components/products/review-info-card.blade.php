<li class="review-box">
    <div class="review-user">
        <img class="review-profile-img"
             src="{{asset('profile_pictures/'. $review->user->profile_pic)}}"
             alt="profile pic">
        <div> {{$review->user->name}} </div>
    </div>
    <div class="review-info">
        <div>
            <div class="review-stars">
            @for ($i = 0; $i < 5; $i++)
                @if ($review->rating <= $i)
                    <span class="fa fa-star" aria-label="Review Star Checked">></span>
                @else
                    <span class="fa fa-star checked" aria-label="Review Star Unchecked">></span>
                @endif
            @endfor
            </div>
            <div class="review_date">Data: {{$review->date}}</div>
        </div>
        @if (!empty($review->description))
            <div>
                <span class="bold">Descrição:</span>
                <span>{{$review->description}}</span>
            </div>
        @endif
    </div>
    @if(Auth::check())
        @if(count(Auth::user()->reportedReview($review->id)) > 0)
            <p>Reported!</p>
        @else
            <button class="report-btn" data-review = {{$review->id}}>Reportar</button>
        @endif
    @endif
</li>



