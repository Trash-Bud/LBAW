@extends('admin.app')

@section('title', 'Histórico de atividade')

@section('c')

<section class="scrolable_menu">

    @foreach($history as $h)
    <div class = "notification_square">
      @if (!empty($h->id_product))
      <button class = "notif product" onclick="window.location='products/{{$h->id_product}}'">Edição a um produto.</button>
      @elseif (!empty($h->id_order))
      <button class = "notif order" onclick="window.location='order/{{$h->id_order}}'">Edição a uma encomenda.</button>
      @elseif (!empty($h->id_review))
      <div class = "notif review">Review escondida.</div>
      @else
      <div class = "notif flagged">Ação de moderação ao utilizador: {{$h->user_email}}</div>
      @endif

    <div class = "notification_square_info">
        <div><b>Administrador:</b> {{$h->name}}</div>
        <div><b>Email:</b> {{$h->admin_email}}</div>
        <div><b>Descrição:</b> {{$h->comment}}</div>
        <div><b>Data:</b> {{$h->date}}</div>
    </div>

    </div>
    @endforeach

  </section>

@endsection
