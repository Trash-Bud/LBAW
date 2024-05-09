@extends('profile.app')

@section('title', 'Notificações')

@section('c')

<section class="scrolable_menu">


    @foreach($notification_list as $notification)
    <div class = "notification_square">
      @if (!empty($notification->id_product))
      <button class = "notif product" onclick="window.location='products/{{$notification->id_product}}'">Sobre um produto em que está interessado(a)!</button>
      @elseif (!empty($notification->id_order))
      <button class = "notif order" onclick="window.location='order/{{$notification->id_order}}'">Sobre a sua encomenda!</button>
      @else
      <div class = "notif flagged">Advertência</div>
      @endif
      <div class = "notification_square_info">
      <div>Descrição: {{$notification->description}}</div>
      <div>Data: {{$notification->date}}</div>
      </div>
    </div>
    @endforeach


  </section>

@endsection
