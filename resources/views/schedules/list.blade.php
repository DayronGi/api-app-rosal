@extends('layouts.plantillas')

@section('title', 'Vivero El Rosal')

<header class="d-flex justify-content-between fixed-top">
    <h2 class="align-middle p-2 fs-1">Programación</h2>
</header>
@section('content')
    <div class="container p-0" style="max-height: 80vh; overflow-y: auto;">
        <div class="d-flex flex-column flex-wrap justify-content-around bg-transparent gap-3 m-0 mt-5 p-0 pt-5 mb-5 pb-5">
            @foreach ($schedules as $schedule)
                <div class="card shadow p-0">
                    <div class="card-header m-0 p-2">
                        <p class="fw-semibold m-0 p-0">{{ $schedule->title }}</p>
                        <p class="m-0 p-0"><i class="fas fa-warehouse"></i> {{ $schedule->department->department_name }}</p>
                        <p class="m-0 p-0"><i class="fas fa-calendar-day"></i> {{ $schedule->date_ini }} -
                            {{ $schedule->date_end }}</p>
                        <p class="m-0 p-0"><i class="fas fa-clock"></i> {{ $schedule->time_ini }} -
                            {{ $schedule->time_end }}</p>
                    </div>

                    <div class="card-body">
                        <div style="height: 22em; overflow-y: scroll;">
                            <table class="table table-sm" style="font-size: 0.95em;">
                                <thead>
                                    <th>CC</th>
                                    <th>Empleado</th>
                                    <th>Día</th>
                                    <th>H. ent.</th>
                                    <th>H. sal.</th>
                                    <th>H. ent.</th>
                                    <th>H. sal.</th>
                                </thead>
                                <tbody>
                                    @foreach ($schedule->scheduledWorkers as $worker)
                                        <tr>
                                            <td>{{ $worker->worker_id }}</td>
                                            <td>{{ $worker->worker->name ?? '' }}</td>
                                            <td>{{ $worker->day }}</td>
                                            <td style="color: #006600;">
                                                @if ($worker->hour_ini1)
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $worker->hour_ini1)->format('H:i') }}
                                                @endif
                                            </td>
                                            <td style="color: #006600;">
                                                @if ($worker->hour_end1)
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $worker->hour_end1)->format('H:i') }}
                                                @endif
                                            </td>
                                            <td style="color: #006600;">
                                                @if ($worker->hour_ini2)
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $worker->hour_ini2)->format('H:i') }}
                                                @endif
                                            </td>
                                            <td style="color: #006600;">
                                                @if ($worker->hour_end2)
                                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $worker->hour_end2)->format('H:i') }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-inline-flex flex-row justify-content-end gap-1 m-0 p-2">
                        <a class="btn btn-sm btn-outline-secondary p-1"
                            href="{{ route('schedules.review', $schedule->schedule_id) }}" title="Review Schedule">
                            <i class="fas fa-th-list"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
