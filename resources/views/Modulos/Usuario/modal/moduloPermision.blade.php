<div class="modal fade" id="modal-modulos" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white px-2 py-1">
                <h1 class="modal-title" style="font-size: 14px !important;">ADMINISTRACIÓN DE MÓDULOS <i
                        class="bi bi-house-lock"></i></h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form-modulo" method="post">
                    <div class="card p-1 m-0">
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="content-input mb-2">
                                        <input type="text" name="clave_modulo" id="clave_modulo" placeholder=" "
                                            class="input">
                                        <label class="input-label">Clave*:</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-8">
                                    <div class="content-input mb-2">
                                        <input type="text" name="name_modulo" id="name_modulo" placeholder=" "
                                            class="input mayus">
                                        <label class="input-label">Nombre*:</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="content-input mb-2">
                                        <input type="text" name="descripcion_modulo" id="descripcion_modulo" placeholder=" " class="input">
                                        <label class="input-label">Descripción (opcional):</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-success btn-sm" id="btnCancelEdicModulo"><i class="bi bi-x-square"></i> Cancelar</button>
                            <button type="submit" class="btn btn-outline-success btn-sm" id="btnSaveModulo" style="margin-left: 12px;"><i class="bi bi-shield-plus"></i> Guardar</button>
                        </div>
                        <div class="card-body p-1">
                            <table id="dt-modulos" width="100%"
                                style="text-align: center;text-align:center ; padding:20px;"
                                data-order='[[ 0, "desc" ]]' class="table-hover table-striped">
                                <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                                    <tr style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                                        <th style="text-align:center">#</th>
                                        <th style="text-align:center">Clave</th>
                                        <th style="text-align:center">Nombre</th>
                                        <th style="text-align:center">Descripción</th>
                                        <th style="text-align:center">Estado</th>
                                        <th style="text-align:center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12px;"></tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>