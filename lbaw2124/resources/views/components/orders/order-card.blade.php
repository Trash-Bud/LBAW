<div>
    <!-- The best way to take care of the future is to take care of the present moment. - Thich Nhat Hanh -->
    <div class="order-card" onclick='window.location="/order/{{$order->id}}"'>
        <div class="track-number-div">Track number: <span> {{ \Illuminate\Support\Str::limit($order->track_number, 5, $end='..') }}</span></div>
        <div>Encomendado em: <span>{{$order->date_of_order}}</span></div>
        <div class="status-badge badge rounded-pill danger {{$color}}">{{$order->order_status}}</div>
        {{--@if($order->order_status === 'À espera de pagamento'
            <button class="edit-order-button">PAY</button>
        @endif--}}
        {{--@if($order->order_status === 'À espera de pagamento' or  $order->order_status === 'Processing')
            <button class="edit-order-button">EDIT</button>
        @endif--}}
    </div>
</div>
