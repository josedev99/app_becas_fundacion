<style>
    .icon-file-cert {
        border-radius: 6px;
        border: 1px solid #dadce0;
        padding: 4px 6px;
        cursor: pointer;
    }

    .icon-file-cert:hover {
        border: none;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }
</style>
<div class="modal fade" id="modal-form-sucursal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white px-2 py-1">
                <h1 class="modal-title" style="font-size: 14px !important;" id="d_modal_sucursal">GESTIONAR SUCURSALES</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form-sucursal" method="post">
                    <div class="card p-1 m-0">
                        <div class="card-header p-1">
                            <div class="card p-1 m-0">
                                <div class="card-header p-1">
                                    <h1 class="card-title m-0 p-1" style="font-size: 15px !important;">Datos de la
                                        sucursal</h1>
                                </div>
                                <div class="card-body pt-2 pb-1 px-1">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-8 col-lg-6">
                                            <div class="content-input mb-2">
                                                <input type="text" name="nombre_suc" id="nombre_suc" placeholder=" "
                                                    class="input mayus">
                                                <label class="input-label">Nombre comercial*:</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-3">
                                            <div class="input-group mb-2">
                                                <label for="" class="select-title">Tipo establecimiento*:</label>
                                                <select class="form-select" name="tipo_estable_suc"
                                                    id="tipo_estable_suc">
                                                    <option value="">Seleccionar</option>
                                                    <option value="01">Sucursal</option>
                                                    <option value="02">Casa Matriz</option>
                                                    <option value="04">Bodega</option>
                                                    <option value="07">Patio</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <div class="input-group mb-2">
                                                <label for="" class="select-title">Departamento*:</label>
                                                <select class="form-select" name="departamento_suc"
                                                    id="departamento_suc">
                                                    <option value="">Seleccionar</option>
                                                    <option value="01">Ahuachapán</option>
                                                    <option value="02">Santa Ana</option>
                                                    <option value="03">Sonsonate</option>
                                                    <option value="04">Chalatenango</option>
                                                    <option value="05">La Libertad</option>
                                                    <option value="06">San Salvador</option>
                                                    <option value="07">Cuscatlán</option>
                                                    <option value="08">La Paz</option>
                                                    <option value="09">Cabañas</option>
                                                    <option value="10">San Vicente</option>
                                                    <option value="11">Usulután</option>
                                                    <option value="12">San Miguel</option>
                                                    <option value="13">Morazán</option>
                                                    <option value="14">La Unión</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <div class="input-group mb-2">
                                                <label for="" class="select-title">Municipio*:</label>
                                                <select class="form-select" name="municipio_suc" id="municipio_suc">
                                                    <option value="">Seleccionar</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-8">
                                            <div class="content-input mb-2">
                                                <input type="text" name="direccion_suc" id="direccion_suc"
                                                    placeholder=" " class="input">
                                                <label class="input-label">Dirección*:</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <div class="content-input mb-2">
                                                <input type="search" name="cod_establecimiento_suc"
                                                    id="cod_establecimiento_suc" placeholder=" "
                                                    class="input max-4 mayus">
                                                <label class="input-label">Cod. Establecimiento (MH)</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <div class="content-input mb-2">
                                                <input type="search" name="punto_venta_suc" id="punto_venta_suc"
                                                    placeholder=" " class="input max-4 mayus">
                                                <label class="input-label">Cod. Punto de venta</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <div class="content-input mb-2">
                                                <input type="email" name="email_suc" id="email_suc" placeholder=" " class="input">
                                                <label class="input-label">Correo electrónico*:</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                            <div class="content-input mb-2">
                                                <input type="text" name="telefono_suc" id="telefono_suc" placeholder=" "
                                                    class="input input-number">
                                                <label class="input-label">Teléfono</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer p-1 d-flex justify-content-end">
                                    <button type="button" class="btn btn-outline-danger btn-sm" id="btnCancelEdi" style="display: none"><i class="bi bi-x"></i> Cancelar edición</button>
                                    <button type="submit" class="btn btn-outline-success btn-sm mx-2" id="btnSaveSuc"><i class="bi bi-floppy-fill"></i> Guardar</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-1">
                            <div class="card p-1">
                                <table id="dt-sucursales" width="100%"
                                    style="text-align: center;text-align:center ; padding:20px;"
                                    data-order='[[ 0, "desc" ]]' class="table-hover table-striped">
                                    <thead style="color:white;min-height:10px;border-radius: 2px;" class="bg-dark">
                                        <tr
                                            style="min-height:10px;border-radius: 3px;font-style: normal;font-size: 12px">
                                            <th style="text-align:center">#</th>
                                            <th style="text-align:center">Nombre</th>
                                            <th style="text-align:center">Tipo establecimiento</th>
                                            <th style="text-align:center">Departamento</th>
                                            <th style="text-align:center">Municipio</th>
                                            <th style="text-align:center">Direccion</th>
                                            <th style="text-align:center">Teléfono</th>
                                            <th style="text-align:center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12px;"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>