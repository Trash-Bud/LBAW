<?php

namespace App\View\Components\Orders;

use App\Models\Order;
use Illuminate\View\Component;

class OrderCard extends Component
{

    public $color;
    public $order;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
        $this->assignColor();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.orders.order-card');
    }

    protected function assignColor(){
        $status = ['À espera de pagamento', 'Em processamento', 'Em trânsito', 'Entregue', 'Cancelada'];
        $colors = ['bg-warning', 'bg-primary', 'bg-info', 'bg-success', 'bg-secondary'];
        $this->color = $colors[array_search($this->order->order_status, $status)];
    }
}
