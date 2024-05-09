<form class="review-form add-review-form" method="POST"
      action="{{ url('/review/' . $product->id)}}"
      enctype="multipart/form-data">
    {{ csrf_field() }}
    @method('POST')
    <div>
        <label for="description">Texto: </label>
        <textarea id = "description" maxlength="100" placeholder="texto"
                  name="description"
                  required></textarea>
    </div>
    <div>
        <label for="rating">Avaliação: </label>
        <input id = "rating" type="number" min="1" max="5" name="rating">
    </div>
    <button type="submit" class="review-btn" >Adicionar Avaliação
    </button>
</form>

