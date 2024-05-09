<?php

namespace App\View\Components\Products;

use Illuminate\View\Component;

class ProductDetailsCard extends Component
{

    public $product;
    public $reviews;
    public $rating;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($product, $rating, $reviews)
    {
        $this->product = $product;
        $this->reviews = $reviews;
        $this->rating = $rating;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.products.product-details-card');
    }
}
