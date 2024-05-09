@extends('layouts.app')

@section('title', 'Contactos')


@section('content')
    <div id="contacts">
        <h1>Contactos</h1>
        <div class = "scroll">
        <table>
            <tr>
                <td>
                    email:
                </td>
                <td>
                    executivesEmail@example.com
                </td>
            </tr>
            <tr>
                <td>
                    telefone:
                </td>
                <td>
                    +351 21 999 99 99
                </td>
            </tr>
            <tr>
                <td>
                    sede:
                </td>
                <td>
                    <div>Edifício 256</div>
                    <div>Rua António Sampaio, 256</div>
                    <div>1600-500, Lisboa</div>
                    <div>Portugal</div>
                </td>
            </tr>
        </table>
        </div>
    </div>
@endsection
