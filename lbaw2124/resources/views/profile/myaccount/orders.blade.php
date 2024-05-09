@extends('profile.app')

@section('title', 'Encomendas')

@section('c')
    @if(count($orders)>0)
        @php
            $unpaid =  $orders->where('order_status', 'like', 'À espera de pagamento');
            $others = $orders->where('order_status', '<>', 'À espera de pagamento');
        @endphp
        <div class="list-order-section">
            <h2 class="order-list-title">À espera de pagamento</h2>
            <div class="orders-list-unpaid scrollable_menu">
                @foreach($unpaid as $unpaid_order)
                    <x-orders.orderCard :order=$unpaid_order/>
                @endforeach
            </div>
            <h2 class="order-list-title">Outras</h2>
            <div class="scrollable_menu orders-list ">
                @foreach($others as $other)
                    <x-orders.orderCard :order=$other/>
                @endforeach
            </div>
        </div>
    @else
        <div>Nenhuma encomenda foi encontrada</div>
    @endif

@endsection
