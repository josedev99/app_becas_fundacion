@extends('Layouts.App')

@section('title', 'Cuentas - Portal DTE')

@section('page-title')
    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Cuenta</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection
@section('content')
@include('Modulos.Cuenta.modal.cuentaForm')
    <div class="card p-1 m-0">
        @if(Gate::allows('cuenta.create'))
            <div class="card-header p-1">
                <button id="btnNewCuenta" class="btn btn-outline-success btn-sm border-0"><i class="bi bi-plus-circle"></i> Nueva cuenta</button>
            </div>
        @endif
        <div class="card-body p-1">
            <table id="dt-cuentas" width="100%"
                style="text-align: center;text-align:center ; padding:20px;"
                data-order='[[ 0, "desc" ]]' class="table-hover table-striped">
                <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                    <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                        <th style="text-align:center">#</th>
                        <th style="text-align:center">NOMBRE</th>
                        <th style="text-align:center">PROPIETARIO</th>
                        <th style="text-align:center">TELÉFONO</th>
                        <th style="text-align:center">CORREO</th>
                        <th style="text-align:center">ESTADO</th>
                        <th style="text-align:center">CANT. EMPRESAS</th>
                        <th style="text-align:center">EMPRESAS</th>
                        <th style="text-align:center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody style="font-size: 12px;"></tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('app/modules/cuenta/cuenta.js') }}?v={{ rand() }}"></script>
    <script src="{{ asset('app/modules/asignar_permisos/modulo_cuenta.js') }}?v={{ rand() }}"></script>
    <script src="{{ asset('app/helpers/valid_input.js') }}?v={{ rand() }}"></script>
@endpush
