@extends('layouts.plantillas')

@section('title', 'Vivero El Rosal')

<header class="d-flex justify-content-between">
    <h2 class="align-middle p-2 fs-1">Manuales</h2>
</header>
@section('content')
<div class="container p-0" style="max-height: 70vh; overflow-y: auto;">
    <table class="table table-hover m-auto p-0">
        <thead class="sticky-top">
            <tr class="table-secondary">
                <th class="text-center">CREÓ<br/>MODIFICÓ</th>
                <th class="text-center text-nowrap">ÁREA<br/>CARGO</th>
                <th class="text-center">TÍTULO<br>DESCRIPCIÓN</th>
                <th></th>
            </tr>
        </thead>
        <tbody style="max-height: 200px; overflow-y: scroll;">
            @foreach ( $handbooks as $handbook )
                <tr>
                    <td class="text-center">
                        <p>{{ $handbook->creation->username }}</p>
                    </td>
                    <td class="text-center">
                        <p>{!! optional($handbook->department)->department_name !!}</p>
                        <p>{!! $handbook->position_name !!}</p>
                    </td>
                    <td class="text-center">
                        <p>{!! $handbook->handbook_title !!}</p>
                        <p>{!! optional($handbook)->handbook_description !!}</p>
                    </td>
                    <td>
                        <br/><a href="{{ route('handbooks.view', $handbook->handbook_id) }}"><i class="fa-solid fa-eye"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="my-5">
    {{ $handbooks->onEachSide(1)->links() }}
</div>
@endsection
