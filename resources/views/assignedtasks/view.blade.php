@extends('layouts.plantillas')

@section('title', 'Vivero El Rosal')

<header class="d-flex justify-content-between border-bottom first-div">
    <h2 class="align-middle p-2 fs-1">Tareas</h2>
</header>
<br/>

@section('content')
    <form class="row m-0 p-0">
        @csrf
        <div class="col-sm-7">
            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-calendar"></i></label>
                    <input class="form-control p-0 px-2" type="date" value="{{ $assignedtasks->start_date }}" readonly />
                </div>
            </div>

            <br/>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-stopwatch"></i></label>
                    <input class="form-control w-auto p-0 ps-2" type="text" size="15" value="{{ $assignedtasks->estimated_time > 0 ? $assignedtasks->estimated_time : '' }} {{ $assignedtasks->units }}" readonly />
                </div>
            </div>

            <br/>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-signature"></i></label>
                    <input class="form-control w-auto fw-bold fst-italic p-0 px-2" type="text" size="6" value="{{ $assignedtasks->type->type_description}}" readonly />
                    <input class="form-control w-auto p-0 px-2" type="text" name="assignedtask_description" size="35" placeholder="Descripción..." value="{!! $assignedtasks->task_description !!}" readonly />
                </div>
            </div>

            <br/>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-helmet-safety"></i></label>
                    <input class="form-control w-auto p-0 px-2" type="text" size="50" value="{!! optional($assignedtasks->worker)->name !!}" readonly />
                </div>
            </div>

            <br/>

            <div class="d-inline-flex flex-row gap-2">
                <div class="col-auto input-group my-2">
                    <label class="input-group-text"><i class="fas fa-warehouse"></i></label>
                    <input class="form-control w-auto p-0 px-2" type="text" size="20" value="{{$assignedtasks->department->department_name}}" readonly />
                </div>
            </div>

            <br/>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-comment"></i></label>
                    <textarea class="form-control p-0 px-2" placeholder="Observaciones..." cols="50" rows="5" readonly>{!! $assignedtasks->observations !!}</textarea>
                </div>
            </div>

            <br/>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-star-half-stroke"></i></label>
                    <input class="form-control p-0 ps-2" type="number" min="0" max="100" value="{{$assignedtasks->score}}" readonly />
                </div>
            </div>

            <br/>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-arrow-up-wide-short"></i></label>
                    <input class="form-control p-0 ps-2" type="number" min="0" max="10" value="{{$assignedtasks->priority}}" readonly />
                </div>
            </div>

            <br/>
            <br/>

            <a class="btn btn-secondary" href="{{ route('assignedtasks.list') }}"><i class="fa-solid fa-angle-left"></i> SALIR</a>
        </div>
    </form>
@endsection