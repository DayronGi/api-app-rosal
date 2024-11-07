@extends('layouts.plantillas')

@section('title', 'Vivero El Rosal')

<header class="d-flex justify-content-between border-bottom first-div">
    <h2 class="align-middle p-2 fs-1">Reportar labor</h2>
</header>
<br/>

@section('content')
    <form action="{{ route('tasks.store') }}" method="POST" class="row m-0 p-0">
        @csrf
        <div class="col-sm-6">
            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-calendar"></i></label>
                    <input class="form-control p-0 px-2" type="date" name="day" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required />
                </div>
            </div>

            <br>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-user"></i></label>
                    <span class="form-control p-0 px-2 select search" id="workers">
                        <input type="hidden" id="worker_id" name="worker_id" value="" />
                        <input class="border-0" id="worker_name_visible" type="text" size="30" placeholder="Seleccione empleado..." autocomplete="off" />

                        <ul class="select-content list-group" id="worker_list" style="display: none;">
                            @foreach($workers as $worker)
                            <li class="worker_ids list-group-item" data-value="{{ $worker->user_data_id }}">{!! $worker->name !!} <br/>[{{ $worker->document_type }} {{ $worker->document_number }}]</li>
                            @endforeach
                        </ul>
                    </span>
                </div>
            </div>

            <br>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-helmet-safety"></i></label>
                    <span class="form-control p-0 px-2 select search" id="jobs">
                        <input type="hidden" id="job_id" name="job_id" />
                        <input class="border-0" id="job_name_visible" type="text" size="30" placeholder="Seleccione labor..." autocomplete="off" />

                        <ul class="select-content list-group" id="job_list" style="display: none;">
                            @foreach($jobs as $job)
                            <li class="job_ids list-group-item" data-value="{{ $job->job_id }}">[{{ $job->internal_code }}] <br/>{{ $job->job_description }} <br/>(V.U. ${{ number_format($job->price, 2, ',', '.') }})</li>
                            @endforeach
                        </ul>
                    </span>
                </div>
            </div>


            <br>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-dollar-sign"></i></label>
                    <input class="form-control p-0 px-2" type="number" id="precio_unidad" name="precio_unidad"
                        min='0.1' max='9999999' step='0.01' readonly/>
                </div>
            </div>

            <br>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-seedling"></i></label>
                    <input class="form-control w-auto p-0 px-2" type="text" name="cantidad_ingresada" size="7" placeholder="Fil*Col+Uni" />
                    <span class="form-control w-auto p-0 px-2 select search" id="plants">
                        <input type="hidden" id="plant_id" name="plant_id" value="1" />
                        <input class="border-0" id="plant_name_visible" type="text" size="15"  autocomplete="off" />

                        <ul class="select-content list-group" id="plant_list" style="display: none;">
                            @foreach ($plants as $plant)
                            <li class="plant_ids list-group-item" data-value="{{ $plant->product_id }}" placeholder="Planta">{{ optional($plant->plant)->common_name }} [{{ $plant->packing }}]</li>
                            @endforeach
                        </ul>
                    </span>
                </div>
            </div>

            <br>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-arrow-up-from-bracket"></i></label>
                    <input class="form-control w-auto p-0 px-2" type="text" name="cantidad_usada" size="7" placeholder="Fil*Col+Uni" />
                    <span class="form-control w-auto p-0 px-2 select search" id="plant_froms">
                        <input type="hidden" id="plant_from_id" name="plant_from_id" value="1" />
                        <input class="border-0" id="plant_from_name_visible" type="text" size="15"  autocomplete="off" />

                        <ul class="select-content list-group" id="plant_from_list" style="display: none;">
                            @foreach ($plants as $plant_from)
                            <li class="plant_ids list-group-item" data-value="{{ $plant_from->product_id }} " placeholder="Desde">{{ optional($plant_from->plant)->common_name }} [{{ $plant_from->packing }}]</li>
                            @endforeach
                        </ul>
                    </span>
                </div>
            </div>

            <br>
            <br>

            <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> AGREGAR</button>
            <a class="btn btn-secondary" href="{{ route('tasks.list') }}"><i class="fa-solid fa-angle-left"></i> SALIR</a>

        </div>

        <div class="col-sm-6">
            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-table-cells"></i></label>
                    <input class="form-control p-0 px-1" type="text" name="seccion_origen" size="4" placeholder="Origen" />
                    <label class="input-group-text"><i class="fas fa-arrow-right-to-bracket"></i></label>
                    <input class="form-control p-0 px-1" type="text" name="seccion" size="5" placeholder="Sección" />
                </div>
            </div>

            <br>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-location-crosshairs"></i></label>
                    <input type="text" name="mesa" class="form-control" placeholder="Mesa" required>
                </div>
            </div>

            <br>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-comment-dots"></i></label>
                    <textarea name="observations" class="form-control" cols="45" rows="2" placeholder="Observaciones..."></textarea>
                </div>
            </div>

            <br>

            <div class="d-inline-flex flex-row gap-2">
                <div class="input-group my-2">
                    <label class="input-group-text"><i class="fas fa-star-half-stroke"></i></label>
                    <input type="number" name="calification" class="form-control" min="0" max="100" value="100">
                </div>
            </div>

            <br>

        </div>
    </form>
@endsection
