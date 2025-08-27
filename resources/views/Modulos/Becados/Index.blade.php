@extends('Layouts.App')

@section('title', 'Estudiantes - App Becados')

@section('page-title')
    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Usuario</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
@endsection
@section('content')
    @include('Modulos.Becados.modal.formBecado')
    <div class="card p-1 m-0">
        <div class="card-header p-1">
            <button id="btn-new-becado" class="btn btn-outline-success btn-sm">Nuevo becado <i class="bi bi-plus-circle"></i></button>
        </div>
        <div class="card-body p-1">
            <table id="dt-users" width="100%"
                style="text-align: center;text-align:center ; padding:20px;"
                data-order='[[ 0, "desc" ]]' class="table-hover table-striped">
                <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                    <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                        <th style="text-align:center">#</th>
                        <th style="text-align:center">Cuenta</th>
                        <th style="text-align:center">Nombre</th>
                        <th style="text-align:center">Teléfono</th>
                        <th style="text-align:center">Usuario</th>
                        <th style="text-align:center">Estado</th>
                        <th style="text-align:center">Categoria</th>
                        <th style="text-align:center">Acciones</th>
                    </tr>
                </thead>
                <tbody style="font-size: 12px;"></tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('app/modules/becado/index.js') }}?v={{ rand() }}"></script>
@endpush
