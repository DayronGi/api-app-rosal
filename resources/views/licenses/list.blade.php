@extends('layouts.plantillas')

@section('title', 'Vivero El Rosal')
@include('licenses.search')

<header class="d-flex justify-content-between">
    <h2 class="align-middle p-2 fs-1">Permisos</h2>
    <div class="d-flex btn-group p-3">
        <a class="btn btn-outline-light border-0 m-0 p-0 px-2" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fs-4 fa-solid fa-magnifying-glass"></i></a>
        <a class="btn btn-outline-light border-0 m-0 p-0 px-2" href="{{route('licenses.create')}}"><i class="fs-4 fa-solid fa-file-circle-plus"></i></a>
        <a class="btn btn-outline-light border-0 m-0 p-0 px-2" href="{{route('licenses.list')}}"><i class="fs-4 fas fa-rotate"></i></a>
    </div>
</header>
@section('content')
<div class="container p-0" style="max-height: 70vh; overflow-y: auto;">
    <table class="table table-hover m-auto p-0">
        <thead class="sticky-top">
            <tr class="table-secondary">
                <th class="text-center">FECHA<br/>REPORTÓ</th>
                <th class="text-start">EMPLEADO</th>
                <th class="text-center">DESDE<br>HASTA</th>
                <th class="text-center">TIPO (MOTIVO)</th>
                <th class="text-center text-nowrap">PLANILLA<br/>CONSECUTIVO</th>
                <th></th>
            </tr>
        </thead>
        <tbody style="max-height: 200px; overflow-y: scroll;">
                @foreach ( $licenses as $license)
                    <tr>
                        <td class="text-center">
                            <p class="fw-bold text-nowrap">{{ \Carbon\Carbon::parse($license->start_date)->format('Y-m-d') }}</p>
                            <p>{{ $license->creation->username }}</p>
                        </td>
                        <td class="text-start">
                        <p class="fw-bold text-nowrap">[{{ optional($license->worker)->document_type  }} {{ optional($license->worker)->document_number }}]</p>
                            <p>{!! optional($license->worker)->name !!}</p>
                        </td>
                        <td class="text-center text-nowrap">
                            <p>{{ substr($license->start_date, 11, 15) }}</p>
                            <p>{{ substr($license->end_date, 11, 15) }}</p>
                        </td>
                        <td class="text-center">
                            <p>{!! $license->motive !!}</p>
                        </td>
                        <td class="text-center">
                            <p>{{ $license->spreadsheet_id }}</p>
                        </td>
                        <td class="text-end">
                            @if( $license->status == '29' )
                            <br/><a href="{{ route('licenses.edit', $license->license_id) }}"><i class="fa-regular fa-pen-to-square"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
        </tbody>
    </table>
</div>
<div class="my-5">
    {{ $licenses->onEachSide(1)->links() }}
</div>
@endsection
