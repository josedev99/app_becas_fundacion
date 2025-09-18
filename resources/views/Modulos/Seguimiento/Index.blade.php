@extends('Layouts.App')

@section('title', 'Seguimiento - App Becados')

@section('page-title')
    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Seguimiento de becados</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection
@section('content')
    @include('Modulos.Seguimiento.modal.formSeguimiento')
    <div class="card p-1 m-0">
        <div class="card-header p-1">
            <button id="btn-new-seguimiento" class="btn btn-outline-success btn-sm">Nuevo seguimiento <i class="bi bi-plus-circle"></i></button>
        </div>
        <div class="card-body p-1">
            <table id="dt-seguimiento" width="100%"
                style="text-align: center;text-align:center ; padding:20px;"
                data-order='[[ 0, "desc" ]]' class="table-hover table-striped">
                <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                    <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                        <th style="text-align:center">#</th>
                        <th style="text-align:center">Nombre</th>
                        <th style="text-align:center">Ultimo seguimiento</th>
                        <th style="text-align:center">Estado</th>
                        <th style="text-align:center">Prioridad</th>
                        <th style="text-align:center">Responsable</th>
                        <th style="text-align:center">Acciones</th>
                    </tr>
                </thead>
                <tbody style="font-size: 12px;"></tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('app/modules/becado/seguimiento.js') }}?v={{ rand() }}"></script>
    <script>
        let telefono = new Cleave('#telefono', {
            delimiter: '-',
            blocks: [4, 4],
        });
        let contacto_emergencia = new Cleave('#contacto_emergencia', {
            delimiter: '-',
            blocks: [4, 4],
        });
        let documento = new Cleave('#documento', {
            delimiter: '-',
            blocks: [8, 1],
        });
        
    </script>
@endpush
