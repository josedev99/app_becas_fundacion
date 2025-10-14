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
<div class="modal fade" id="modal-form-beca" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white px-2 py-1">
                <h1 class="modal-title" style="font-size: 14px !important;" id="display_title_becado">REGISTRAR NUEVA
                    BECA</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form-beca" method="post">
                    <div class="card p-1 m-0">
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-8">
                                    <div class="content-input mb-2">
                                        <input type="text" name="nombre_beca" id="nombre_beca" placeholder=" "
                                            class="input">
                                        <label class="input-label">Nombre</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-4">
                                    <div class="input-group mb-2">
                                        <label for="" class="select-title">Tipo de beca (*)</label>
                                        <select class="form-select" name="tipo_beca" id="tipo_beca">
                                            <option value="">Seleccionar</option>
                                            <option value="Total">Total</option>
                                            <option value="Parcial">Parcial</option>
                                            <option value="Alimentaria">Alimentaria</option>
                                            <option value="Transporte">Transporte</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-4">
                                    <div class="input-group mb-2">
                                        <label for="" class="select-title">Financiamiento (*)</label>
                                        <select class="form-select" name="financiamiento" id="financiamiento">
                                            <option value="">Seleccionar</option>
                                            <option value="Donante">Donante</option>
                                            <option value="Empresa aliada">Empresa aliada</option>
                                            <option value="Fondos internos">Fondos internos</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-4">
                                    <div class="input-group mb-2">
                                        <label for="" class="select-title">Monto asignado(*)</label>
                                        <select class="form-select" name="plazo_monto" id="plazo_monto">
                                            <option value="">Seleccionar</option>
                                            <option value="Mensual">Mensual</option>
                                            <option value="Anual">Anual</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-4">
                                    <div class="input-group mb-2">
                                        <label for="" class="select-title">Forma de entrega(*)</label>
                                        <select class="form-select" name="forma_entrega" id="forma_entrega">
                                            <option value="">Seleccionar</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Insumos">Insumos</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-4">
                                    <div class="input-group mb-2">
                                        <label for="" class="select-title">Compromisos(*)</label>
                                        <select class="form-select" name="compromiso" id="compromiso">
                                            <option value="">Seleccionar</option>
                                            <option value="Horas sociales">Horas sociales</option>
                                            <option value="Talleres">Talleres</option>
                                            <option value="Rendimiento minimo">Rendimiento minimo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-8">
                                    <div class="content-input mb-2">
                                        <input type="text" name="encargado_beca" id="encargado_beca" placeholder=" "
                                            class="input">
                                        <label class="input-label">Encargado</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm" id="btnSaveBeca"><i
                                    class="bi bi-person-add"></i> Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
