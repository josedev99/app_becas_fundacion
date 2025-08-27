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
</style>
<div class="modal fade" id="modal-form-becado" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white px-2 py-1">
                <h1 class="modal-title" style="font-size: 14px !important;" id="display_title_becado">REGISTRAR NUEVO
                    BECARIO</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form-becado" method="post">
                    <div class="card p-1 m-0">
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="content-input mb-2">
                                        <input type="text" name="nombre_user" id="nombre_user" placeholder=" "
                                            class="input mayus">
                                        <label class="input-label">Nombre completo*:</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3">
                                    <div class="content-input mb-2">
                                        <input type="text" name="doc_user" id="doc_user" placeholder=" " class="input">
                                        <label class="input-label">Num. Documento</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3">
                                    <div class="content-input mb-2">
                                        <input type="text" name="fecha_nacimiento" id="fecha_nacimiento" placeholder=" "
                                            class="input">
                                        <label class="input-label">Fecha nacimiento</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="content-input mb-2">
                                        <input type="text" name="direccion_user" id="direccion_user" placeholder=" "
                                            class="input">
                                        <label class="input-label">Dirección (opcional)</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <div class="content-input mb-2">
                                        <input type="text" name="telefono" id="telefono" placeholder=" "
                                            class="input">
                                        <label class="input-label">Teléfono*:</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <div class="content-input mb-2">
                                        <input type="text" name="contacto_emergencia" id="contacto_emergencia" placeholder=" "
                                            class="input">
                                        <label class="input-label"> Contacto emergencia*:</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <div class="content-input mb-2">
                                        <input type="text" name="email_user" id="email_user" placeholder=" "
                                            class="input">
                                        <label class="input-label">Email*:</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <div class="input-group mb-2">
                                        <label for="" class="select-title">Estado (*)</label>
                                        <select class="form-select" name="estado_user" id="estado_user">
                                            <option value="">Seleccionar</option>
                                            <option value="Activo">Activo</option>
                                            <option value="Desactivado">Desactivado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm" id="btnSaveUser"><i
                                    class="bi bi-person-add"></i> Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>