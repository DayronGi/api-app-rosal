@extends('layouts.plantillas')

@section('title', 'Vivero El Rosal')

<header class="d-flex justify-content-between border-bottom first-div">
    <h2 class="align-middle p-2">Modificar permiso</h2>
</header>
<br/>

@section('content')
    <form action="{{ route('licenses.update', $license->license_id) }}" method="POST" class="row m-0 p-0">
        @csrf
        @method('PUT')
        <div class="col-sm-6">
            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-user"></i></label>
                    <span class="form-control p-0 px-2 select search" id="workers">
                        <input type="hidden" id="worker_id" name="worker_id" value="{{ $license->worker_id }}" />
                        <input class="border-0" id="worker_name_visible" type="text" size="30" placeholder="Seleccione empleado..." value="{{$license->worker->name}}" autocomplete="off" readonly />
                    </span>
                </div>
            </div>

            <br>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-calendar-week"></i></label>
                    <input class="form-control w-auto p-0 px-2" type="text" name="start_date" value="{{ substr($license->start_date, 0, 10) }}" required />
                    <input class="form-control w-auto p-0 ps-2" type="time" name="start_hour" value="{{ substr($license->start_date, 11, 15) }}" />
                    <input class="form-control w-auto p-0 ps-2" type="time" name="end_hour" value="{{ substr($license->end_date, 11, 15) }}" />
                </div>
            </div>

            <br>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-folder-tree"></i></label>
                    <select class="form-select w-auto p-0 ps-2 pe-5" name="motive">
                        @foreach ($motives as $motive)
                        <option value="{!! $motive !!}">{!! $motive !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <br>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-money-check"></i></label>
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" id="btnradio1" name="paid" value="1" />
                        <label class="btn btn-sm btn-outline-secondary" for="btnradio1">Remunerado</label>

                        <input type="radio" class="btn-check" id="btnradio2" name="paid" value="0" checked />
                        <label class="btn btn-sm btn-outline-secondary" for="btnradio2">No remunerado</label>
                    </div>
                </div>
            </div>
            <br>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-barcode"></i></label>
                    <input class="form-control p-0 px-2" type="text" name="spreadsheet_id" size="15" value="{{ $license->spreadsheet_id }}" />
                </div>
            </div>

            <br>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-comment"></i></label>
                    <textarea class="form-control p-0 px-2" name="observations" cols="50">{!! $license->observations !!}</textarea>
                </div>
            </div>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-trash"></i></label>
                    <div class="form-control form-check ps-5">
                        <input class="form-check-input" type="checkbox" id="status" name="status" value="28" />
                        <label class="form-check-label" for="status">Eliminar?</label>
                    </div>
                </div>
            </div>

            <br/>
            <br/>

            <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> GUARDAR</button>
            <a class="btn btn-secondary" href="{{ route('licenses.list') }}"><i class="fa-solid fa-angle-left"></i> SALIR</a>
        </div>
    </form>
@endsection
