@extends('layouts.plantillas')

@section('title', 'Vivero El Rosal')

<header class="d-flex justify-content-between">
    <h2 class="align-middle p-2 fs-1">Programación</h2>
</header>
@section('content')
    <div class="card-body grid m-0 p-0">
        <form class="row m-0 p-0" method="POST" action="{{ route('schedules.store') }}">
        @csrf
            @php
                $list_workers = $scheduled_workers;
                $current_date = now()->format('Y-m-d');
            @endphp

            <div class="col-sm-12 border">
                <div class="d-inline-flex flex-row gap-2">
                    <div class="input-group my-2">
                        <label class="input-group-text"><i class="fas fa-calendar-day"></i></label>
                        <input class="form-control w-auto p-0 ps-2 pe-1" type="date" name="review_date" value="{{ $current_date }}" required/>
                        <input type="hidden" id="schedule_day_select" data-selected-workers="{{ json_encode($list_workers) }}"/>
                        <select class="form-select w-auto p-0 ps-2 pe-5" name="schedule_day" id="schedule_day_select_visible">
                            <option value="Select day">Elegir día</option>
                            @foreach ($schedule_days as $day)
                                <option value="{{ $day->day }}" {{ in_array($day->day, array_column($list_workers->toArray(), 'day')) ? 'selected' : '' }}>
                                    {{ $day->day }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="height:31.05em; overflow-y:scroll;">
                    <table class="table table-hover">
                        <thead class="text-uppercase">
                            <tr class="sticky-top z-3">
                                <th class="text-center"><input id='check_all' type='checkbox' /></th>
                                <th>Documento<br>Nombre</th>
                                <th>Día</th>
                                <th>Horario 1</th>
                                <th>Horario 2</th>
                                <th class="text-end">Programado</th>
                                <th class="text-end">Normal</th>
                                <th class="text-end">Extra</th>
                            </tr>
                        </thead>

                        <tbody id="schedule_tbody">
                            @foreach ($list_workers as $i => $worker)
                            <tr>
                                <td class="text-center"><input type="checkbox" name="hours[{{ $i }}]" class="worker-checkbox" /></td>
                                <td>
                                    <p class="m-0 p-0">[{{ $worker->worker->document_type }} {{ $worker->worker->document_number }}]</p>
                                    <p class="m-0 p-0">{{ $worker->worker->name }}</p>
                                </td>
                                <td><p class="m-0 p-0">{{ $worker->day }}</p></td>
                                <td>
                                    <input type="hidden" name="type[{{ $i }}]" value="a" />
                                    <input type="hidden" name="worker_id[{{ $i }}]" value="{{ $worker->worker_id }}" />
                                    <input type="hidden" name="day[{{ $i }}]" value="{{ $worker->day }}"/>
                                    <input type="hidden" name="normal[{{ $i }}]" value="{{ $worker->normal }}" />
                                    <input type="hidden" name="extra[{{ $i }}]" value="{{ $worker->extra }}" />
                                    <input type="time" style="width: 7em" name="hour_ini1[{{ $i }}]" value="{{ $worker->hour_ini1 }}" />
                                    <input type="time" style="width: 7em" name="hour_end1[{{ $i }}]" value="{{ $worker->hour_end1 }}" />
                                </td>
                                <td>
                                    <input type="time" style="width: 7em" name="hour_ini2[{{ $i }}]" value="{{ $worker->hour_ini2 }}"/>
                                    <input type="time" style="width: 7em" name="hour_end2[{{ $i }}]" value="{{ $worker->hour_end2 }}"/>
                                </td>
                                <td class="quantity">{{ number_format($worker->program, 2, ',', '.') }} Horas</td>
                                <td class="quantity">{{ number_format($worker->normal, 2, ',', '.') }}</td>
                                <td class="quantity outcome">{{ number_format($worker->extra, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-row gap-2 actions justify-content-end mt-auto p-2">
                    <button class="btn btn-sm btn-success" name="action"><i class="fas fa-save"></i> GUARDAR</button>
                </div>
            </div>
        </form>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scheduleDaySelect = document.getElementById('schedule_day_select_visible');
        const scheduleTbody = document.getElementById('schedule_tbody');

        scheduleDaySelect.addEventListener('change', function() {
            const selectedDay = this.value;
            const rows = scheduleTbody.querySelectorAll('tr');

            rows.forEach(row => {
                const dayCell = row.querySelector('td:nth-child(3) p').textContent.trim();
                if (selectedDay === 'Select day' || dayCell === selectedDay) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        const checkAllBox = document.getElementById('check_all');
        checkAllBox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.worker-checkbox');
            checkboxes.forEach(box => box.checked = this.checked);
        });

        const form = document.querySelector('form');
        form.addEventListener('submit', function() {
            const checkboxes = document.querySelectorAll('.worker-checkbox');
            checkboxes.forEach((box, index) => {
                if (!box.checked) {
                    const parentRow = box.closest('tr');
                    parentRow.querySelectorAll('input').forEach(input => {
                        input.disabled = true;
                    });
                }
            });
        });
    });
</script>