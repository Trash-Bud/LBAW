@extends('layouts.app')

@section('title', 'Perguntas Frequentes')

@section('content')
    <div id="faq">
        <h1>Perguntas frequentes</h1>
        <table>
            <tr>
                <td>
                    <b>Q: </b> 
                    o que é que acontece quando eu apago a minha conta?
                </td>
                <td>
                    <b>A: </b>
                    Quando apaga a sua conta, informação do utilizador partilhada 
                    (avaliações) é mantida de forma anónima.
                </td> 
            </tr>
            <tr>
                <td>
                    <b>Q: </b>
                    Como funcionam cancelamento de encomendas e reembolsos?
                </td>
                <td>
                    <b>A: </b>
                    Os cancelamentos de encomendas e os reembolsos estão disponíveis até ao seu envio.
                </td>
            </tr>
            <tr>
                <td>
                    <b>Q: </b>
                    Estou bloqueado de fazer avaliações. E agora?
                </td>
                <td>
                    <b>A: </b>
                    Um utilizador, através do seu perfil, pode, uma vez por dia, fazer appeal do seu estado. 
                    Este será visto e avaliado por um administrador.
                </td>
            </tr>
        </table>
    </div>
@endsection