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
    .container-logo{
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        height: 150px;
        border: 2px dotted #aab8d1;
        border-radius: 6px;
    }
    #preview_logo{
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    #btnUploadLogo{
        background: none;
        color: #7a8fb4;
        border: none;
        width: 100%;
    }
    #btnUploadLogo:hover{
        border: 1px solid #f2f2ff;
    }
</style>
<div class="modal fade" id="modal-form-empresa" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white px-2 py-1">
                <h1 class="modal-title" style="font-size: 14px !important;" id="display_modal_empresa">REGISTRAR NUEVA
                    EMPRESA</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form-empresa" method="post" enctype="multipart/form-data">
                    <div class="card p-1 m-0">
                        <div class="card-header p-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="container-icheck">
                                        <div class="radio icheck-info d-inline">
                                            <input type="radio" checked id="icheckDtePrueba" name="checkAmbienteDte"
                                                value="00">
                                            <label for="icheckDtePrueba" style="font-size: 14px;">Amb. De prueba</label>
                                        </div>
                                        <div class="radio icheck-success d-inline">
                                            <input type="radio" id="icheckDteProductivo" name="checkAmbienteDte"
                                                value="01">
                                            <label for="icheckDteProductivo" style="font-size: 14px;">Amb.
                                                Productivo</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 mb-2 d-flex justify-content-end align-items-center">
                                    <span class="icon-file-cert mx-2" id="icon-file-cert"><i
                                            class="bi bi-file-earmark-plus" style="font-size: 16px;"></i> <b
                                            id="display_upload_file">Cargar certificado</b></span>
                                    <input type="file" name="file_cert" style="display: none" id="input_file_cert"
                                        accept=".crt">
                                    <span class="text-success" id="display_name_cert"></span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-2 px-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-9">
                                    <div class="card py-3 px-2 m-0">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                                <div class="content-input mb-2">
                                                    <input type="text" name="nombre" id="nombre" placeholder=" "
                                                        class="input mayus">
                                                    <label class="input-label">Nombre*:</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-2">
                                                <div class="content-input mb-2">
                                                    <input type="text" name="nrc" id="nrc" placeholder=" "
                                                        class="input input-number">
                                                    <label class="input-label">NRC*:</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-8 col-lg-8 col-xl-4">
                                                <div class="content-input mb-2">
                                                    <input type="text" name="nit" id="nit" placeholder=" "
                                                        class="input input-number">
                                                    <label class="input-label">NIT*:</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                <div class="input-group mb-2">
                                                    <label for="" class="select-title">Actividad económica*:</label>
                                                    <select class="form-select" name="actividad_economica" id="actividad_economica">
                                                        <option value="">Seleccionar</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-8 col-xl-4">
                                                <div class="content-input mb-2">
                                                    <input type="email" name="email" id="email" placeholder=" " class="input">
                                                    <label class="input-label">Correo electrónico*:</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-2">
                                                <div class="content-input mb-2">
                                                    <input type="text" name="telefono" id="telefono" placeholder=" "
                                                        class="input input-number">
                                                    <label class="input-label">Teléfono</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                                <div class="content-input mb-2">
                                                    <input type="password" name="clave_cert" id="clave_cert" placeholder=" "
                                                        class="input">
                                                    <label class="input-label">Clave certificado</label>
                                                    <i onclick="showPassword(this)" data-id_input="clave_cert"
                                                        class="bi bi-eye-slash icon-show-password icon-float-input"></i>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                                <div class="content-input mb-2">
                                                    <input type="password" name="clave_api" id="clave_api" placeholder=" "
                                                        class="input">
                                                    <label class="input-label">Clave api</label>
                                                    <i onclick="showPassword(this)" data-id_input="clave_api"
                                                        class="bi bi-eye-slash icon-show-password icon-float-input"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-3">
                                    <div class="card p-1 m-0">
                                        <div class="card-header p-1">
                                            <button type="button" id="btnUploadLogo" class="btn btn-success"><i class="bi bi-plus"></i> Cargar imagen</button>
                                        </div>
                                        <div class="card-body p-1">
                                            <div class="container-logo">
                                                <input type="file" name="logo" accept="image/*" id="logo_file" style="display: none">
                                                <div id="preview_logo">
                                                    <p class="mb-1">No se ha cargado un logo</p>
                                                    <i class="bi bi-card-image"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm" id="btnSaveEmp"><i
                                    class="bi bi-person-add"></i> Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>