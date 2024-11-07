@extends('layouts.plantillas')

@section('title', 'Vivero El Rosal')

<header class="d-flex justify-content-between border-bottom first-div fixed-top">
    <h2 class="align-middle p-2 fs-1">Manuales</h2>
    <div class="d-flex btn-group p-3">
        <a class="btn btn-outline-light border-0 m-0 p-0 px-2" href="{{route('handbooks.list')}}"><i class="fs-4 fas fa-arrow-left"></i></a>
    </div>
</header>

@section('content')
<div class="card-body grid m-0 mt-5 p-0 pt-5 mb-5 pb-5">
    <form class="row m-0 p-0">
        <div class="content col-sm-12">
            @foreach ($chapters as $chapter)
                <section class="chapter">
                    <h1 class="fs-5 text-uppercase fw-bold">
                        {!! $chapter['section_index'] !!}. {!! $chapter['section_title'] !!}
                    </h1>
                    <article>
                        {!! $chapter['section_content'] !!}
                    </article>
                    @if (isset($chapter['sections']))
                        @foreach ($chapter['sections'] as $section)
                            <section class="section">
                                <h2 class="fs-5 text-uppercase fw-bold">
                                    {{ $chapter['section_index'] }}.{{ $section['section_index'] }}. {!! $section['section_title'] !!}
                                </h2>
                                <article>
                                    {!! $section['section_content'] !!}
                                </article>
                            </section>
                            @if (isset($section['subsections']))
                                @foreach ($section['subsections'] as $subsection)
                                <section class="subsection">
                                    <h3 class="fs-5 text-capitalize">
                                        {{$chapter['section_index']}}.{{$section['section_index']}}.{{$subsection['section_index']}}. {!!$subsection['section_title']!!}
                                    </h3>
                                    <article>
                                        {!!$subsection['section_content']!!}
                                    </article>
                                </section>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </section>
            @endforeach
        </div>
    </form>
</div>
@endsection
