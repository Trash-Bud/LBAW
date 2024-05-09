@extends('layouts.app')

@section('title', 'Detalhes da encomenda: $order->track_number')

@section('content')
    @if(isset($order))

        <!-- Is there another way to add padding to the page?-->
        <div class="order-details-section">
            <h1 >Informação da encomenda:</h1>
            <div>
                <table class="order-table table">
                    <tbody>
                    <tr>
                        <td>Track Number</td>
                        <td>{{$order->track_number}}</td>
                    </tr>
                    <tr>
                        <td>Data de encomenda</td>
                        <td>{{$order->date_of_order}}</td>
                    </tr>
                    <tr>
                        <td>Estado</td>
                        <td>{{$order->order_status}}</td>
                    </tr>
                    <tr>
                        <td>Date de expedição</td>
                        <td>{{$order->date_of_departure}}</td>
                    </tr>
                    @if(isset($order->date_of_arrival))
                        <tr>
                            <td>Data de chegada</td>
                            <td>{{$order->date_of_arrival}}</td>
                        </tr>
                    @endif
                    @if(isset($address))
                        <tr>
                            <td>Morada</td>
                            <td>{{$address->street}}, {{$address->country}}, Código Postal: {{$address->postal_code}}</td>
                        </tr>
                    @endif
                    <tr>
                        <td>Pagamento</td>
                        <td>{{$order->payment}}</td>
                    </tr>
                </tbody>
                </table>
                @if($order->order_status === 'À espera de pagamento' or  $order->order_status === 'Em processamento')
                    @if (Auth::check())
                        <button>Editar Morada</button>
                    @endif
                @endif
                @if($order->order_status === 'À espera de pagamento')
                    @if (Auth::check())
                        <button>Pagar</button>
                    @endif
                @endif
                @if($order->order_status != 'Cancelada' && $order->order_status != 'Entregue')
                    @if (Auth::guard('admin')->check())
                        <button onclick="window.location='{{ url('order/'. $order->id.'/edit_status') }}'">Editar Estado</button>
                    @endif
                @endif
                <div class="order-product-list ">
                    <table class="products-table table">
                        <thead>
                        <tr>
                            <th>Nome do Produto</th>
                            <th>Quantidade</th>
                            <th>Preço</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($products))
                            @foreach($products as $product)
                                <tr>
                                    <td>{{$product->name}}</td>
                                    <td>{{$product->quantity}}</td>
                                    <td>{{$product->current_price}} €</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                        <tr>
                        <td>Total</td>
                        <td colspan="2" >{{$order->total_price}} €</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

                <button type="submit" class="responsive_width_buttons" onclick="show_pop_up_order_cancel()"> Cancelar Encomenda </button>
                @if ($errors->has('invalid_order'))
                <span class="error">
                    {{ $errors->first('invalid_order') }}
                </span>
                @endif
                <div id = "order_cancel" class = "pop_up center">
                    Tem a certeza que quer cancelar esta encomenda?
                    <div class = "warning">Esta ação não pode ser revertida.</div>

                    <form method="POST" action="{{url('delete_order/'.$order->id)}}" enctype = "multipart/form-data" class = "edit_form">
                        {{ csrf_field() }}
                        @method('DELETE')
                        <button type = "submit">Cancelar Encomenda</button>
                    </form>

                    <button onclick="hide_pop_up_order_cancel()">Voltar</button>
                </div>

            </div>
        </div>
    @endif
@endsection
