@extends('Layouts.App')

@section('title','Inicio - App becas')

@section('page-title')
<div class="pagetitle">
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div><!-- End Page Title -->
@endsection
@section('content')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #667eea;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 25px;
        padding: 15px 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -37px;
        top: 20px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #667eea;
    }

    h5{
        font-size: 16px;
    }

    .display-7{
        font-size: 1.6rem;
    }

    .text-muted{
        font-size: 0.85rem;
    }
</style>
<h1 style="font-size: 25px;">Bienvenido {{ Auth()->user()->nombre }}</h1>
<p class="p-0 m-0"><strong>Conectando con historias</strong></p>

<div id="dashboard" class="tab-content">
    <div class="p-3 my-2">
        <div class="row g-3">
            <!-- Número total de becados -->
            <div class="col-12 col-md-3">
                <div class="card shadow-sm text-center border-0 p-2 m-0">
                    <h5 class="text-secondary mb-2">
                        <i class="bi bi-people-fill text-primary me-1"></i> Becados
                    </h5>
                    <div id="counter-becados" class="display-7 fw-bold text-primary">0</div>
                    <p class="text-muted mb-0">Total de becados</p>
                </div>
            </div>

            <!-- Becados por nivel educativo -->
            <div class="col-12 col-md-3">
                <div class="card shadow-sm border-0 p-2 m-0">
                    <h6 class="text-secondary mb-2">
                        <i class="bi bi-graph-up-arrow text-success me-1"></i> Becados por nivel
                    </h6>
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between px-1 py-1">
                            Básico <span class="badge bg-primary" id="nivel-Basico">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-1 py-1">
                            Bachillerato <span class="badge bg-success" id="nivel-Bachillerato">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-1 py-1">
                            Universidad <span class="badge bg-warning text-dark" id="nivel-Universidad">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-1 py-1">
                            Técnico <span class="badge bg-info text-dark" id="nivel-Tecnico">0</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Porcentaje de graduados -->
            <div class="col-12 col-md-3">
                <div class="card shadow-sm text-center border-0 p-2 m-0">
                    <h5 class="text-secondary mb-2">
                        <i class="bi bi-mortarboard text-warning me-1"></i> Graduados
                    </h5>
                    <div class="display-7 fw-bold text-warning" id="porcentaje-graduados">0%</div>
                    <p class="text-muted mb-0 small">
                        de <strong class="fw-semibold" id="total-becados"></strong> becados
                    </p>
                </div>
            </div>

            <!-- Promedio general -->
            <div class="col-12 col-md-3">
                <div class="card shadow-sm text-center border-0 p-2 m-0">
                    <h5 class="text-secondary mb-2">
                        <i class="bi bi-pie-chart-fill text-info me-1"></i> Promedio
                    </h5>
                    <div class="display-7 fw-bold text-info" id="promedio_gen">0.0</div>
                    <p class="text-muted mb-0">Promedio general</p>
                </div>
            </div>

            <!-- Inversión anual en becas -->
            <div class="col-12 col-md-3">
                <div class="card shadow-sm text-center border-0 p-2 m-0">
                    <h5 class="text-secondary mb-2">
                        <i class="bi bi-cash-stack text-danger me-1"></i> Inversión
                    </h5>
                    <div class="display-7 fw-bold text-danger" id="inversion_becas">$0.00</div>
                    <p class="text-muted mb-0">Inversión anual en becas</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm m-0 p-1">
                <div class="card-header bg-transparent m-0 p-1">
                    <h6><i class="bi bi-calendar-week me-2"></i>Próximos Seguimientos</h6>
                </div>
                <div class="card-body m-0 p-2">
                    <div class="timeline pt-2" id="lista-seguimientos">
                        <div class="text-center p-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Sin datos.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('app/helpers/dashboard.js') }}?v={{ rand() }}"></script>
@endpush
