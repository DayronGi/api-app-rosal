@extends('layouts.plantillas')

@section('title', 'Vivero El Rosal')

<header class="d-flex justify-content-between border-bottom first-div fixed-top">
    <h2 class="align-middle p-2 fs-1">Organizacional</h2>
</header>
<br/>

@section('content')
<div class="d-flex flex-row justify-content-center align-items-center m-0 mt-5 p-0 pt-5 mb-5 pb-5">
    <div class="card shadow p-4" style="width: 28em;">
        <img src="{{ asset('Resources/logoh.png') }}" class="card-img-top p-4 img-fluid" alt="..." style="max-width: 100%; height: auto;" />
        <h2 style="color: #006600;">MISI&Oacute;N</h2>
        <p style="text-align: justify;">
            Vivero El Rosal es una empresa que se dedica a la producci&oacute;n, comercializaci&oacute;n y distribuci&oacute;n de gran variedad de plantas ornamentales de excelente calidad con el fin de satisfacer las necesidades de nuestros clientes, adem&aacute;s de crear espacios ambientalmente arm&oacute;nicos con el conocimiento y la experiencia de nuestro talento humano altamente calificado. Contribuimos en la fomentación y protección del medio ambiente.
        </p>
        <br/>
        <h2 style="color: #006600;">VISI&Oacute;N</h2>
        <p style="text-align: justify;">Consolidar al Vivero El Rosal como una empresa l&iacute;der en el mercado nacional. Lograr competitividad siendo eficientes e innovadores en la producci&oacute;n de plantas ornamentales de alta calidad.
        </p>
        <br/>
        <h2 style="color: #006600;">OBJETIVO</h2>
        <p style="text-align: justify;">Mejorar continuamente la calidad en nuestra producci&oacute;n y distribuci&oacute;n de material vegetal, ofreciendo una excelente presentaci&oacute;n de las plantas, &oacute;ptimo servicio y atenci&oacute;n al cliente, entrega oportuna y satisfactoria a nuestro consumidor final.
        </p>
    </div>
</div>
@endsection