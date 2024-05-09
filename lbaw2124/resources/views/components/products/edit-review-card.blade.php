<form id="edit-review-form" class="review-form edit-review-form"
      method="POST"
      action="{{ url('/review/' . $my_review->id)}}"
      enctype="multipart/form-data">
    {{ csrf_field() }}
    @method('PUT')
    <div>
        <label for="description">Texto: </label>
        <textarea id = "description" maxlength="100" placeholder="texto" name="description"
                  required></textarea>
    </div>
    <div>
        <label for="rating">Avaliação: </label>
        <input id = "rating" type="number" min="1" max="5" name="rating" required>
    </div>
    <button>Mudar</button>
    <button type="button" id="go-back-edit">&larr;</button>
</form>
