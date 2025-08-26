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
        margin-top: 0.1rem;
        margin-right: 0.25rem;
    }

    .form-check-label {
        margin-bottom: 0;
    }
</style>
<div class="modal fade" id="modal-form-cuenta" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white px-2 py-1">
                <h1 class="modal-title" style="font-size: 14px !important;" id="d_title_cuenta">REGISTRAR CUENTA NUEVA
                </h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form-cuenta" method="post" autocomplete="off">
                    <div class="card p-1 m-0">
                        <div class="card-body p-2">
                            <fieldset class="content-form mb-4" id="fieldset-cuenta">
                                <legend><i class="bi bi-person-circle"></i> Cuenta</legend>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-6">
                                        <div class="content-input mb-2">
                                            <input type="text" name="cuenta" id="cuenta" placeholder=" "
                                                class="input mayus valid" title="El nombre de la cuenta es obligatorio">
                                            <label class="input-label">Nombre*:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-6">
                                        <div class="content-input mb-2">
                                            <input type="text" name="propietario" id="propietario" placeholder=" "
                                                class="input mayus valid"
                                                title="El propietario de la cuenta es obligatorio">
                                            <label class="input-label">Propietario*:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-8 col-lg-6">
                                        <div class="content-input mb-2">
                                            <input type="email" name="email" id="email" placeholder=" "
                                                class="input valid" title="El correo de la cuenta es obligatorio">
                                            <label class="input-label">Email:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-lg-3">
                                        <div class="content-input mb-2">
                                            <input type="text" name="telefono" id="telefono" placeholder=" "
                                                class="input input-number valid"
                                                title="El teléfono de la cuenta es obligatorio">
                                            <label class="input-label">Teléfono:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3">
                                        <div class="content-input mb-2">
                                            <input type="number" name="cantidad_emp" id="cantidad_emp" placeholder=" "
                                                class="input max-4 mayus valid"
                                                title="La cantidad de empresas de la cuenta es obligatorio">
                                            <label class="input-label">Cantidad empresa:</label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="content-form d-none" id="fieldset-admin">
                                <legend><i class="bi bi-person-add"></i> Usuario administrador</legend>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-6">
                                        <div class="content-input mb-2">
                                            <input type="text" name="nombre_admin" id="nombre_admin" placeholder=" "
                                                class="input mayus">
                                            <label class="input-label">Nombre*:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3">
                                        <div class="content-input mb-2">
                                            <input type="text" name="user_admin" id="user_admin" placeholder=" "
                                                class="input">
                                            <label class="input-label">Usuario*:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-3">
                                        <div class="content-input mb-2">
                                            <input type="password" name="clave_admin" id="clave_admin" placeholder=" "
                                                class="input">
                                            <label class="input-label">Clave acceso:</label>
                                            <i onclick="showPassword(this)" data-id_input="clave_admin"
                                                class="bi bi-eye-slash icon-show-password icon-float-input"></i>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            @if(Auth::check() && Auth::user()->categoria == "SuperAdmin")
                                <div class="card-body p-1">
                                    <h5 style="text-align: center; color: rgb(8, 51, 207);font-size: 16px;">Permisos de la cuenta</h5>
                                    <div class="accordion" id="accordionPermisos">
                                        <div class="row g-2" id="content_modulos">
                                            
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-success btn-sm border-0" id="btnSaveCuenta"><i
                                    class="bi bi-floppy-fill"></i> Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>