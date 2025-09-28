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
<div class="modal fade" id="modal-form-becado" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white px-2 py-1">
                <h1 class="modal-title" style="font-size: 14px !important;" id="display_title_becado">REGISTRAR NUEVO
                    BECARIO</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form-becado" method="post">
                    <div class="card p-1">
                        <div class="card-body p-1">
                            <!-- Bordered Tabs Justified -->
                            <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100 active" id="form-personal-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-personal" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="bi bi-person-vcard"></i> Datos personales</button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="academicos-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-academicos" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1"> <i class="bi bi-mortarboard"></i> Datos academicos</button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="socioeconomico-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-socioeconomico" type="button" role="tab" aria-controls="contact" aria-selected="false" tabindex="-1"><i class="bi bi-cash"></i> Datos socioeconomicos</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                                <div class="tab-pane fade active show" id="bordered-justified-personal" role="tabpanel" aria-labelledby="form-personal-tab">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-6">
                                            <div class="content-input mb-2">
                                                <input type="text" name="nombre_completo" id="nombre_completo" placeholder=" " class="input mayus">
                                                <label class="input-label">Nombre completo*:</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <div class="content-input mb-2">
                                                <input type="text" name="documento" id="documento" placeholder=" " class="input">
                                                <label class="input-label">Num. Documento</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <div class="content-input mb-2">
                                                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" placeholder=" " class="input">
                                                <label class="input-label">Fecha nacimiento</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-9">
                                            <div class="content-input mb-2">
                                                <input type="text" name="direccion" id="direccion" placeholder=" " class="input">
                                                <label class="input-label">Dirección (opcional)</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-3">
                                            <div class="content-input mb-2">
                                                <input type="text" name="telefono" id="telefono" placeholder=" " class="input">
                                                <label class="input-label">Teléfono*:</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-3">
                                            <div class="content-input mb-2">
                                                <input type="text" name="contacto_emergencia" id="contacto_emergencia" placeholder=" " class="input">
                                                <label class="input-label"> Contacto emergencia*:</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-3">
                                            <div class="content-input mb-2">
                                                <input type="text" name="email_becado" id="email_becado" placeholder=" " class="input">
                                                <label class="input-label">Email*:</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-6">
                                            <div class="input-group mb-2">
                                                <label for="" class="select-title">Becas (*)</label>
                                                <select class="form-select" name="beca_id" id="beca_id">
                                                    <option value="">Seleccionar</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="bordered-justified-academicos" role="tabpanel" aria-labelledby="academicos-tab">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-4">
                                            <div class="input-group mb-2">
                                                <label for="" class="select-title">Nivel educativo(*)</label>
                                                <select class="form-select" name="nivel_educativo" id="nivel_educativo">
                                                    <option value="">Seleccione nivel</option>
                                                    <option value="Basico">Básico</option>
                                                    <option value="Bachillerato">Bachillerato</option>
                                                    <option value="Universidad">Universidad</option>
                                                    <option value="Tecnico">Técnico</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-8">
                                            <div class="input-group mb-2">
                                                <label for="institucion" class="select-title">Institución</label>
                                                <select class="form-select" name="institucion" id="institucion" required>
                                                    <option value="">Seleccionar</option>
                                                </select>
                                                <span class="select-icon" title="Agregar nueva institución" id="btn-add-institucion" style="cursor: pointer;">
                                                    <i class="bi bi-plus-circle"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-6">
                                            <div class="input-group mb-2">
                                                <label for="carrera" class="select-title">Carrera/grado</label>
                                                <select class="form-select" name="carrera" id="carrera" required>
                                                    <option value="">Seleccionar</option>
                                                </select>
                                                <span class="select-icon" title="Agregar carrera" id="btn-add-carrera" style="cursor: pointer;">
                                                    <i class="bi bi-plus-circle"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <div class="content-input mb-2">
                                                <input type="number" step="0.01" min="1" max="10" name="promedio" id="promedio" placeholder=" " class="input">
                                                <label class="input-label">Promedio</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-3 col-lg-3">
                                            <div class="input-group mb-2">
                                                <label for="" class="select-title">Estado (*)</label>
                                                <select class="form-select" name="estado_academico" id="estado_academico">
                                                    <option value="">Seleccionar</option>
                                                    <option value="Activo">Activo</option>
                                                    <option value="Graduado">Graduado</option>
                                                    <option value="Suspendida">Suspendida</option>
                                                    <option value="Retirado">Retirado</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-3">
                                            <div class="content-input mb-2">
                                                <input type="date" name="fInicio_beca" id="fInicio_beca" placeholder=" " class="input">
                                                <label class="input-label">Fecha inicio</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-3">
                                            <div class="content-input mb-2">
                                                <input type="date" name="fFin_beca" id="fFin_beca" placeholder=" " class="input">
                                                <label class="input-label">Fecha finalización</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="bordered-justified-socioeconomico" role="tabpanel" aria-labelledby="socioeconomico-tab">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-6">
                                            <div class="input-group mb-2">
                                                <label for="" class="select-title">Situación familiar*:</label>
                                                <select class="form-select" name="situacion_familiar" id="situacion_familiar">
                                                    <option value="">Seleccione nivel</option>
                                                    <option value="Nuclear">Nuclear</option>
                                                    <option value="Monoparental">Monoparental</option>
                                                    <option value="Tutor">Tutor</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <div class="content-input mb-2">
                                                <input type="number" step="0.01" name="ingreso_aprox" id="ingreso_aprox" placeholder=" " class="input">
                                                <label class="input-label">Ingreso</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <div class="content-input mb-2">
                                                <input type="text" name="numero_personas" id="numero_personas" placeholder=" " class="input">
                                                <label class="input-label">Numero de personas</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-6">
                                            <div class="content-input mb-2">
                                                <input type="text" name="necesidades_esp" id="necesidades_esp" placeholder=" " class="input">
                                                <label class="input-label">Necesidades especiales</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-6">
                                            <div class="content-input mb-2">
                                                <input type="text" name="comunidad_residencia" id="comunidad_residencia" placeholder=" " class="input">
                                                <label class="input-label">Comunidad residencia</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- End Bordered Tabs Justified -->
                        </div>
                    </div>

                    <div class="card p-1 m-0">
                        <div class="card-body p-1">

                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm" id="btnSaveUser"><i class="bi bi-person-add"></i> Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
