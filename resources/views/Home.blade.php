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

</style>
<h1>Bienvenido {{ Auth()->user()->nombre }}</h1>

<div id="dashboard" class="tab-content">
    <div class="row">
        <div class="col-sm-12 col-md-3">
            <div class="card p-1 mb-2 mx-0" style="text-align: center;">
                <h3 style="font-size: 18px"><i class="bi bi-people-fill"></i> Becados</h3>
                <div style="font-size: 30px; color: #667eea; font-weight: bold; margin: 0px 0;">45</div>
                <p style="color: #666;">Total de becados</p>
            </div>
        </div>
        <div class="col-sm-12 col-md-3">
            <div class="card p-1 mb-2 mx-0" style="text-align: center;">
                <h3 style="font-size: 18px"><i class="bi bi-graph-up-arrow"></i> Becados</h3>
                <div style="font-size: 30px; color: #667eea; font-weight: bold; margin: 0px 0;">45</div>
                <p style="color: #666;">Nivel educativo</p>
            </div>
        </div>
        <div class="col-sm-12 col-md-3">
            <div class="card p-1 mb-2 mx-0" style="text-align: center;">
                <h3 style="font-size: 18px"><i class="bi bi-mortarboard"></i> Graduados</h3>
                <div style="font-size: 30px; color: #667eea; font-weight: bold; margin: 0px 0;">45</div>
                <p style="color: #666;">Porcentaje</p>
            </div>
        </div>
        <div class="col-sm-12 col-md-3">
            <div class="card p-1 mb-2 mx-0" style="text-align: center;">
                <h3 style="font-size: 18px"><i class="bi bi-pie-chart-fill"></i> Promedio</h3>
                <div style="font-size: 30px; color: #667eea; font-weight: bold; margin: 0px 0;">45</div>
                <p style="color: #666;">General</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm m-0 p-1">
                <div class="card-header bg-transparent m-0 p-1">
                    <h6><i class="bi bi-calendar-week me-2"></i>Próximos Seguimientos</h6>
                </div>
                <div class="card-body m-0 p-1">
                    <div class="timeline pt-2">
                        <div class="timeline-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Juan Pérez</h6>
                                    <p class="mb-1 text-muted">Seguimiento académico</p>
                                    <small class="text-primary"><i class="bi bi-clock"></i> Hoy 15:00</small>
                                </div>
                                <span class="priority-badge priority-media">Media</span>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">María González</h6>
                                    <p class="mb-1 text-muted">Reunión familiar</p>
                                    <small class="text-success"><i class="bi bi-clock"></i> Mañana 10:30</small>
                                </div>
                                <span class="priority-badge priority-alta">Alta</span>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Carlos Rodríguez</h6>
                                    <p class="mb-1 text-muted">Verificación socioeconómica</p>
                                    <small class="text-info"><i class="bi bi-clock"></i> 16 Sept 14:00</small>
                                </div>
                                <span class="priority-badge priority-baja">Baja</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
