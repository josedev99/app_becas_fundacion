<div class="modal fade" id="modal-form-email" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white px-2 py-1">
                <h1 class="modal-title" style="font-size: 14px !important;" id="d_config_email">CONFIGURACIÓN DE CORREO</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form-email-config" method="post" enctype="multipart/form-data">
                    <div class="card p-1 m-0">
                        <div class="card-header p-1">
                            <div class="col-sm-12 col-md-12">
                                <div class="container-icheck">
                                    <div class="radio icheck-success d-inline">
                                        <input type="radio" checked id="email_activo" name="estadoEmail" value="Activo">
                                        <label for="email_activo" style="font-size: 14px;">Activo</label>
                                    </div>
                                    <div class="radio icheck-success d-inline">
                                        <input type="radio" id="email_desact" name="estadoEmail" value="Desactivado">
                                        <label for="email_desact" style="font-size: 14px;">Desactivado</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-3 pb-2 px-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="input-group mb-2">
                                        <label for="" class="select-title">Tipo de correo*:</label>
                                        <select class="form-select" name="tipo_email" id="tipo_email">
                                            <option value="">Seleccionar</option>
                                            <option value="imap">IMAP</option>
                                            <option value="pop">POP</option> 
                                            <option value="smtp">SMTP</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="input-group mb-2">
                                        <label for="" class="select-title">Cifrado*:</label>
                                        <select class="form-select" name="cifrado" id="cifrado">
                                            <option value="">Seleccionar</option>
                                            <option value="tls">TLS</option>
                                            <option value="ssl">SSL</option> 
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="content-input mb-2">
                                        <input type="number" name="port" id="port" min="1" placeholder=" "
                                            class="input input-number">
                                        <label class="input-label">Puerto*:</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="content-input mb-2">
                                        <input type="search" name="dominio_host" id="dominio_host" placeholder=" "
                                            class="input">
                                        <label class="input-label">Dominio/Host*:</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="content-input mb-2">
                                        <input type="email" name="email" id="email_config" placeholder=" " class="input">
                                        <label class="input-label">Email*:</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="content-input mb-2">
                                        <input type="password" name="clave_email" id="clave_email" placeholder=" " class="input">
                                        <label class="input-label">Clave email</label>
                                        <i onclick="showPassword(this)" data-id_input="clave_email" class="bi bi-eye-slash icon-show-password icon-float-input"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm" id="btnSaveEmail"><i class="bi bi-floppy-fill"></i> Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>