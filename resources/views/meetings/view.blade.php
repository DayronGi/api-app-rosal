@extends('layouts.plantillas')

@section('title', 'Vivero El Rosal')

<header class="d-flex justify-content-between border-bottom first-div">
    <h2 class="align-middle p-2 fs-1">Reuniones</h2>
</header>
<br/>

@section('content')
<div class="card-body grid m-0 p-0">
        <form class="row m-0 p-0">
            <div class="col-sm-6 border">
                <h2 class="fs-6 border-bottom fw-semibold">Datos</h2>

                <div class="d-inline-flex flex-row gap-2">
                    <div class="input-group my-2">
                        <label class="input-group-text"><i class="fas fa-barcode"></i></label>
                        <input class="form-control p-0 px-2" type="text" size="4" value="{{ $meeting->meeting_id }}" readonly />
                    </div>
                </div>

                <br/>

                <div class="d-inline-flex flex-row gap-2">
                    <div class="input-group my-2">
                        <label class="input-group-text"><i class="fas fa-calendar-day"></i></label>
                        <input class="form-control p-0 px-2" type="date" value="{{ $meeting->meeting_date }}" readonly />
                    </div>
                </div>

                <div class="d-inline-flex flex-row gap-2">
                    <div class="input-group my-2">
                        <label class="input-group-text"><i class="fas fa-clock"></i></label>
                        <input class="form-control p-0 px-2" type="time" value="{{ $meeting->start_hour }}" readonly />
                    </div>
                </div>

                <br/>

                <div class="d-inline-flex flex-row gap-2">
                    <div class="input-group my-2">
                        <label class="input-group-text"><i class="fas fa-microphone"></i></label>
                        <input class="form-control p-0 px-2" type="text" size="50" value="{!! $meeting->worker->name !!}" readonly />
                    </div>
                </div>

                <br/>

                <div class="d-inline-flex flex-row gap-2">
                    <div class="input-group my-2">
                        <label class="input-group-text"><i class="fas fa-location-dot"></i></label>
                        <input class="form-control p-0 px-2" type="text" size="50" value="{!! $meeting->placement !!}" readonly />
                    </div>
                </div>

                <br/>

                <div class="d-inline-flex flex-row gap-2">
                    <div class="col-auto input-group my-2">
                        <label class="input-group-text"><i class="fas fa-warehouse"></i></label>
                        <input class="form-control p-0 px-2" type="text" size="25" value="{!! $meeting->department->department_name !!}" readonly />
                    </div>
                </div>

                <br/>

                <div class="d-inline-flex flex-row gap-2">
                    <div class="input-group my-2">
                        <label class="input-group-text"><i class="fas fa-signature"></i></label>
                        <input class="form-control p-0 px-2" type="text" size="50" value="{!! $meeting->meeting_description !!}" />
                    </div>
                </div>

                <br/>

                <div class="d-inline-flex flex-row gap-2">
                    <div class="input-group my-2">
                        <label class="input-group-text"><i class="fas fa-users"></i></label>
                        <div class="form-control p-1 d-flex flex-row flex-wrap gap-1">
                            @foreach ( $meeting->assistants as $assistant )
                            <span class="badge text-bg-light border border-secondary d-inline-flex align-items-center justify-content-between m-0 p-1 gap-1">
                                <span class="align-middle m-0 p-0">{{ $assistant->worker->name }}</span>
                            </span>
                            @endforeach
                         </div>
                    </div>
                </div>
                <br/>
                <br/>
                <a class="btn btn-secondary" href="{{ route('meetings.list') }}"><i class="fa-solid fa-angle-left"></i> SALIR</a>
            </div>

            <div class="col-sm-6 border">
                <h2 class="fs-6 border-bottom fw-semibold">Orden del día</h2>

                <div style="height:36.25em; overflow-y:scroll;" class="d-flex flex-column gap-2">
                    @foreach ( $meeting->meeting_topics as $topic )
                    @if ( $topic->status == 2)
                    <span class="alert alert-info d-inline-flex text-wrap align-items-top m-0 me-2 p-1 gap-1">
                        <span class="align-middle text-wrap m-0 p-0"></span>{!! $topic->topic !!}</span>
                    </span>
                    @endif
                    @endforeach
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
