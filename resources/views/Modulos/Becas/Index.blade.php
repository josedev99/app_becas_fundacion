@extends('Layouts.App')

@section('title', 'Becas - App Becados')

@section('page-title')
    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Becas</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection
@section('content')
    @include('Modulos.Becas.modal.formBeca')
    <div class="card p-1 m-0">
        <div class="card-header p-1">
            <button id="btn-new-beca" class="btn btn-outline-success btn-sm">Nuevo beca <i class="bi bi-plus-circle"></i></button>
        </div>
        <div class="card-body p-1">
            <table id="dt-becas" width="100%"
                style="text-align: center;text-align:center ; padding:20px;"
                data-order='[[ 0, "desc" ]]' class="table-hover table-striped">
                <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                    <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                        <th style="text-align:center">#</th>
                        <th style="text-align:center">Nombre</th>
                        <th style="text-align:center">Tipo</th>
                        <th style="text-align:center">Financiamiento</th>
                        <th style="text-align:center">Monto asignado</th>
                        <th style="text-align:center">Forma de entrega</th>
                        <th style="text-align:center">Encargado</th>
                        <th style="text-align:center">Acciones</th>
                    </tr>
                </thead>
                <tbody style="font-size: 12px;"></tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('app/modules/beca/index.js') }}?v={{ rand() }}"></script>
@endpush