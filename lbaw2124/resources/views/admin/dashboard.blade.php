@extends('admin.app')

@section('title', 'Painel de notificações')

@section('c')

<section class="scrolable_menu">
    @foreach($notifications as $notif)
    <div class = "notification_square">
      @if (!empty($notif->id_review))
      <button class = "notif review" onclick="window.location='reviews/{{$h->id_review}}'">Edição a um produto.</button>
      @else
      <div class = "notif flagged">O utilizador pediu para ser desbloqueado: {{$notif->email}}</div>
      @endif
      <div class = "notification_square_info">
        <div>Descrição: {{$notif->comment}}</div>
        <div>Data: {{$notif->date}}</div>
      </div>
    </div>
    @endforeach

  </section>

@endsection
