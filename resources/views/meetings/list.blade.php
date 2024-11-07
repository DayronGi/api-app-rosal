@extends('layouts.plantillas')

@section('title', 'Vivero El Rosal')
@include('meetings.search')

<header class="d-flex justify-content-between">
    <h2 class="align-middle p-2 fs-1">Reuniones</h2>
    <div class="d-flex btn-group p-3">
        <a class="btn btn-outline-light border-0 m-0 p-0 px-2" data-bs-toggle="modal" data-bs-target="#searchModal"><i
                class="fs-4 fa-solid fa-magnifying-glass"></i></a>
        <a class="btn btn-outline-light border-0 m-0 p-0 px-2" href="{{ route('meetings.list') }}"><i
                class="fs-4 fas fa-rotate"></i></a>
    </div>
</header>
@section('content')
    <div class="container p-0">
        <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
            <table class="table table-hover m-auto p-0">
                <thead class="sticky-top">
                    <tr class="table-secondary">
                        <th class="text-center">REPORTÓ</th>
                        <th class="text-center text-nowrap">FECHA - LUGAR<br />CONVOCÓ</th>
                        <th class="text-center">ÁREA<br>DESCRIPCIÓN</th>
                        <th class="text-center">ORDEN DEL DIA</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($meetings as $meeting)
                        <tr>
                            <td class="text-center">
                                <p class="fw-bold">{{ $meeting->creation->username }}</p>
                            </td>
                            <td class="text-center">
                                <p>{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('Y-m-d') }}-{{ \Carbon\Carbon::parse($meeting->start_hour)->format('H:i') }}
                                    - {{ $meeting->placement }}</p>
                                <p>{!! optional($meeting->worker)->name !!}</p>
                            </td>
                            <td class="text-center">
                                <p>{!! optional($meeting->department)->department_name !!}</p>
                                <p>{!! $meeting->meeting_description !!}</p>
                            </td>
                            <td class="text-center">
                                <p>{!! $meeting->topics !!}</p>
                            </td>
                            <td class="text-end">
                                <br /><a href="{{ route('meetings.view', $meeting->meeting_id) }}"><i class="fa-solid fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="my-5">
            {{ $meetings->onEachSide(1)->links() }}
        </div>
    </div>
@endsection
