<?php

namespace App\View\Components\Products;

use Illuminate\View\Component;

class ReviewInfoCard extends Component
{
    public $review;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($review)
    {
        $this->review = $review;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.products.review-info-card');
    }
}
