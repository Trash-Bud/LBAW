@extends('admin.app')

@section('title', 'Formulário de edição de encomendas')

@section('c')

<section class="info" id = "order_edit">

    <table class="order-table table">
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
    </table>

        <form method="POST" action="{{url('order/'. $order->id.'/edit_status')}}" enctype = "multipart/form-data">
        {{ csrf_field() }}
        @method('PUT')
            <div>
                <div class = "editinfo">
                    <label for = "status"> Novo Estado: </label>
                    <select name="status" id="status">
                        <option value="À espera de pagamento">À espera de pagamento</option>
                        <option value="Em processamento">Em processamento</option>
                        <option value="Em trânsito">Em trânsito</option>
                        <option value="Entregue">Entregue</option>
                        <option value="Cancelada">Cancelada</option>
                      </select>
                </div>
                @if ($errors->has('status'))
                <span class="error">
                    {{ $errors->first('status') }}
                </span>
                @endif

            </div>
            <button type="submit"> Alterar </button>
        </form>
    </div>
</section>

@endsection
