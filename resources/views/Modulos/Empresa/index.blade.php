@extends('Layouts.App')

@section('title', 'Empresas - Portal DTE')

@section('page-title')
    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Empresas</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection
@section('content')
    @include('Modulos.Empresa.modal.form_empresa')
    @include('Modulos.Empresa.modal.form_sucursal')
    @include('Modulos.Empresa.modal.configEmail')
    <div class="card p-1 m-0">
        <div class="card-header p-1">
            <button id="btnNewEmpresa" class="btn btn-outline-success btn-sm"><i class="bi bi-plus-circle"></i> Nueva empresa</button>
        </div>
        <div class="card-body p-1">
            <table id="dt-empresas" width="100%"
                style="text-align: center;text-align:center ; padding:20px;"
                data-order='[[ 0, "desc" ]]' class="table-hover table-striped">
                <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                    <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                        <th style="text-align:center">#</th>
                        <th style="text-align:center">AMBIENTE</th>
                        <th style="text-align:center">CUENTA</th>
                        <th style="text-align:center">NOMBRE</th>
                        <th style="text-align:center">TELÉFONO</th>
                        <th style="text-align:center">NRC</th>
                        <th style="text-align:center">NIT</th>
                        <th style="text-align:center">ESTADO</th>
                        <th style="text-align:center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody style="font-size: 12px;"></tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let nit = new Cleave('#nit', {
                delimiter: '-',
                blocks: [4, 6, 3, 1],
            });
            let nrc = new Cleave('#nrc', {
                delimiter: '-',
                blocks: [6, 1],
            });
            let tel = new Cleave('#telefono', {
                delimiter: '-',
                blocks: [4, 4],
            });
        });
    </script>
    <script src="{{ asset('app/modules/empresa/empresa.js') }}?v={{ rand() }}"></script>
    <script src="{{ asset('app/modules/empresa/sucursal.js') }}?v={{ rand() }}"></script>
    <script src="{{ asset('app/modules/empresa/email.js') }}?v={{ rand() }}"></script>
    <script src="{{ asset('app/modules/empresa/file_cert.js') }}?v={{ rand() }}"></script>
    <script src="{{ asset('app/modules/empresa/previewLogo.js') }}?v={{ rand() }}"></script>
    <script src="{{ asset('app/helpers/valid_input.js') }}?v={{ rand() }}"></script>
@endpush
