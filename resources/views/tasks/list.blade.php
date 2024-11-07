@extends('layouts.plantillas')

@section('title', 'Vivero El Rosal')
@include('tasks.search')

<header class="d-flex justify-content-between">
    <h2 class="align-middle p-2 fs-1">Planillas</h2>
    <div class="d-flex btn-group p-3">
        <a class="btn btn-outline-light border-0 m-0 p-0 px-2" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fs-4 fa-solid fa-magnifying-glass"></i></a>
        <a class="btn btn-outline-light border-0 m-0 p-0 px-2" href="{{route('tasks.create')}}"><i class="fs-4 fa-solid fa-file-circle-plus"></i></a>
        <a class="btn btn-outline-light border-0 m-0 p-0 px-2" href="{{route('tasks.list')}}"><i class="fs-4 fas fa-rotate"></i></a>
    </div>
</header>
@section('content')
<div class="container p-0" style="max-height: 70vh; overflow-y: auto;">
    <table class="table table-hover m-auto p-0">
        <thead class="sticky-top">
            <tr class="table-secondary">
                <th class="text-center">FECHA<br>REPORTÓ</th>
                <th class="text-start">EMPLEADO</th>
                <th class="text-start">LABOR<br>L,S,M</th>
                <th class="text-start">OBSERVACIONES</th>
                <th class="text-end text-nowrap">CANT.[F*C+U]<br/>V.U <br/>TOTAL</th>
            </tr>
        </thead>
        <tbody style="max-height: 200px; overflow-y: scroll;">
            @foreach ($tasks as $task)
            <tr>
                <td class="text-center">
                    <p class="fw-bold text-nowrap">{{ \Carbon\Carbon::parse($task->day)->format('Y-m-d') }}</p>
                    <p>{{ $task->creation->username }}</p>
                </td>
                <td class="text-start">
                    <p class="fw-bold text-nowrap">[{{ $task->worker->document_type  }} {{ $task->worker->document_number }}]</p>
                    <p>{{ $task->worker->name }}</p>
                </td>
                <td class="text-start">
                    <p>{!! $task->job->job_description !!}</p>
                    <p>{!! optional($task->getPlant)->shortname !!}</p>
                </td>
                <td class="text-start">{!! $task->observations !!}</td>
                <td class="text-end">
                    <p class="fw-bold text-nowrap">{{ number_format($task->cantidad_unidades, 0, ',', '.') }} [{{ $task->cantidad_ingresada }}]</p>
                    <p>$ {{ number_format($task->precio_unidad, 0, ',', '.') }}</p>
                    <p style="color: green; font-weight: bold;">$ {{ number_format($task->total, 0, ',', '.') }}</p>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="my-5">
    {{ $tasks->onEachSide(1)->links() }}
</div>
@endsection
