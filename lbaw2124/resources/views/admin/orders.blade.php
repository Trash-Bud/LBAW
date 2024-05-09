@extends('admin.app')

@section('title', 'Encomendas')

@section('c')

<section id="all-orders-list">
    <div id="users_header">
        <div id="search_container">
            <form action="{{ url('/orders/search') }}" method="GET" role="search">
                <input type="text" placeholder="Procurar por Track Number.." name="search">
                <button type="submit">Procurar</button>
            </form>
        </div>
    </div>
    <div>
        @foreach($orders as $order)
            <x-orders.orderCard :order=$order/>
        @endforeach
    </div>
</section>

@endsection
