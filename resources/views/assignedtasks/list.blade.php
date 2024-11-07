@extends('layouts.plantillas')

@section('title', 'Vivero El Rosal')
@include('assignedtasks.search')

<header class="d-flex justify-content-between">
    <h2 class="align-middle p-2 fs-1">Tareas</h2>
    <div class="d-flex btn-group p-3">
        <a class="btn btn-outline-light border-0 m-0 p-0 px-2" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fs-4 fa-solid fa-magnifying-glass"></i></a>
        <a class="btn btn-outline-light border-0 m-0 p-0 px-2" href="{{route('assignedtasks.list')}}"><i class="fs-4 fas fa-rotate"></i></a>
    </div>
</header>
@section('content')
<div class="container p-0" style="max-height: 70vh; overflow-y: auto;">
    <table class="table table-hover m-auto p-0">
        <thead class="sticky-top">
            <tr class="table-secondary">
                <th class="text-center">REPORTÓ</th>
                <th class="text-start text-nowrap">FECHA<br/>TIEMPO EST.</th>
                <th class="text-center">RESPONSABLE<br>ÁREA</th>
                <th class="text-center">DESCRIPCIÓN</th>
                <th class="text-center text-nowrap">PRIORIDAD</th>
                <th></th>
            </tr>
        </thead>
        <tbody style="max-height: 200px; overflow-y: scroll;">
                @foreach ( $assignedtasks as $assignedtask)
                    <tr>
                        <td class="text-center">
                            <p class="fw-bold">{{ $assignedtask->creation->username }}</p>
                        </td>
                        <td class="text-start">
                            <p class="fw-bold text-nowrap">{{ \Carbon\Carbon::parse($assignedtask->start_date)->format('Y-m-d') }}</p>
                            <p>{{ $assignedtask->estimated_time }} {!! $assignedtask->units !!}</p>
                        </td>
                        <td class="text-center text-nowrap">
                            <p>{!! optional($assignedtask->worker)->name !!}</p>
                            <p>{!! $assignedtask->department->department_name !!}</p>
                        </td>
                        <td class="text-center">
                            <p>{!! $assignedtask->task_description !!}</p>
                        </td>
                        <td class="text-center">
                            <p>{{ $assignedtask->priority }}</p>
                        </td>
                        <td class="text-end">
                            <br/><a href="{{ route('assignedtasks.view', $assignedtask->task_id) }}"><i class="fa-solid fa-eye"></i></a>
                        </td>
                    </tr>
                @endforeach
        </tbody>
    </table>
</div>
<div class="my-5">
    {{ $assignedtasks->onEachSide(1)->links() }}
</div>
@endsection
