<style>
    .accordion-button {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }

    .accordion-body {
        padding: 0.25rem;
    }

    .table {
        font-size: 0.75rem;
        margin-bottom: 0;
    }

    .table td,
    .table th {
        padding: 0.2rem;
    }

    .accordion-item {
        margin-bottom: 0.25rem;
    }

    .form-check-input {
        border-radius: 6px;
        padding: 6px;
        margin-top: 0.1rem;
        margin-right: 0.25rem;
    }

    .form-check-label {
        margin-bottom: 0;
        font-size: 11px;
        font-weight: 700;
    }

    .form-check {
        display: inline-block;
        min-height: 0px;
    }

    .form-section h3 {
        color: #333;
        margin-bottom: 15px;
        font-size: 18px;
    }
</style>

<div class="modal fade" id="modal-form-seguimiento" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white px-2 py-1">
                <h1 class="modal-title fs-6" id="display_title_becado">REGISTRAR NUEVO SEGUIMIENTO</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-1">
                <form id="form-seguimiento" class="form-section" method="post">
                    <!-- Información Becado -->
                    <div class="card p-1 mb-2" style="border-left: 4px solid #667eea;">
                        <h3><i class="bi bi-person-badge-fill"></i> Información del becado</h3>
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="content-input mb-2">
                                        <label for="becado_seguimiento" class="select-title">Becado*</label>
                                        <select class="form-select form-select-sm" name="becado_seguimiento" id="becado_seguimiento">
                                            <option value="">Seleccionar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-4">
                                    <div class="content-input mb-2">
                                        <input type="date" name="fecha_seguimiento" id="fecha_seguimiento" placeholder=" "
                                            class="input" value="{{ date('Y-m-d') }}">
                                        <label class="input-label">Fecha seguimiento*</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="content-input mb-2">
                                        <input type="text" name="responsable_seguimiento" id="responsable_seguimiento" class="input mayus" placeholder=" ">
                                        <label for="responsable_seguimiento" class="input-label">Responsable*</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Evaluación -->
                    <div class="card p-1 mb-2" style="border-left: 4px solid #667eea;">
                        <h3><i class="bi bi-graph-up-arrow"></i> Evaluación</h3>
                        <div class="card-body p-1">
                            <div class="mb-2">
                                <label for="participacion" class="form-label">Participación en actividades</label>
                                <textarea name="participacion" id="participacion" rows="3" class="form-control form-control-sm"></textarea>
                            </div>
                            <div class="mb-2">
                                <label for="observaciones_tutor" class="form-label">Observaciones del tutor</label>
                                <textarea name="observaciones_tutor" id="observaciones_tutor" rows="3" class="form-control form-control-sm"></textarea>
                            </div>
                            <div class="mb-2">
                                <label for="notas_add" class="form-label">Notas adicionales</label>
                                <textarea name="notas_add" id="notas_add" rows="3" class="form-control form-control-sm"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Seguimiento y estado -->
                    <div class="card p-1 mb-2" style="border-left: 4px solid #667eea;">
                        <h3><i class="bi bi-graph-up-arrow"></i> Seguimiento y estado</h3>
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="content-input mb-2">
                                        <label for="estado_beca" class="select-title">Estado de beca*</label>
                                        <select class="form-select form-select-sm" name="estado_beca" id="estado_beca">
                                            <option value="">Seleccionar</option>
                                            <option value="Activo">Activo</option>
                                            <option value="Graduado">Graduado</option>
                                            <option value="Suspendida">Suspendida</option>
                                            <option value="Retirado">Retirado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="content-input mb-2">
                                        <label for="prioridad_segui" class="select-title">Prioridad*</label>
                                        <select class="form-select form-select-sm" name="prioridad_segui" id="prioridad_segui">
                                            <option value="">Seleccionar</option>
                                            <option value="Baja">Baja</option>
                                            <option value="Media">Media</option>
                                            <option value="Alta">Alta</option>
                                            <option value="Urgente">Urgente</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-4">
                                    <div class="content-input mb-2">
                                        <input type="date" name="fecha_proximo" id="fecha_proximo" placeholder=" "
                                            class="input">
                                        <label class="input-label">Próx. Seguimiento(opcional)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Guardar -->
                    <div class="card p-1 m-0">
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm" id="btnSaveUser">
                                <i class="bi bi-person-add"></i> Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>