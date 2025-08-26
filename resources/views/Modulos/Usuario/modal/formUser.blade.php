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
<div class="modal fade" id="modal-form-user" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white px-2 py-1">
                <h1 class="modal-title" style="font-size: 14px !important;" id="display_title_user">REGISTRAR NUEVO
                    USUARIO</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-1">
                <form id="form-user" method="post">
                    <div class="card p-1 m-0">
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="content-input mb-2">
                                        <input type="text" name="nombre_user" id="nombre_user" placeholder=" "
                                            class="input mayus">
                                        <label class="input-label">Nombre (*)</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3">
                                    <div class="content-input mb-2">
                                        <input type="text" name="doc_user" id="doc_user" placeholder=" " class="input">
                                        <label class="input-label">Num. Doc (opcional)</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3">
                                    <div class="content-input mb-2">
                                        <input type="text" name="telefono_user" id="telefono_user" placeholder=" "
                                            class="input">
                                        <label class="input-label">Teléfono (opcional)</label>
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
                                        <input type="text" name="email_user" id="email_user" placeholder=" "
                                            class="input">
                                        <label class="input-label">Email (opcional)</label>
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
                                <div class="col-sm-12 col-md-6 col-lg-2">
                                    <div class="content-input mb-2">
                                        <input type="text" name="usuario_user" id="usuario_user" placeholder=" "
                                            class="input">
                                        <label class="input-label">Usuario (*)</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-2">
                                    <div class="content-input mb-2">
                                        <input type="password" name="clave_user" id="clave_user" placeholder=" "
                                            class="input">
                                        <label class="input-label">Clave (*)</label>
                                        <i id="icon-show-passwd" class="bi bi-eye-slash icon-float-input"></i>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="input-group mb-2">
                                        <label for="" class="select-title">Categoria (*)</label>
                                        <select class="form-select" name="categoria_user" id="categoria_user">
                                            <option value="">Seleccionar</option>
                                            @if(Gate::allows('user.superadmin'))
                                            <option value="SuperAdmin">SuperAdmin</option>
                                            @endif
                                            <option value="Administrador">Administrador</option>
                                            <option value="Usuario">Usuario</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-4">
                                    <div class="content-input mb-2">
                                        <input type="text" name="cargo_user" id="cargo_user" placeholder=" "
                                            class="input">
                                        <label class="input-label">Cargo (opcional)</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="input-group mb-2">
                                        <label for="" class="select-title">Empresa (*)</label>
                                        <select class="form-select" name="empresa_id" id="empresa_id">
                                            <option value="">Seleccionar</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(Auth::check() && in_array(Auth::user()->categoria,["SuperAdmin", "Administrador"]))
                        <div class="card-body p-1">
                            <hr>
                            <h5 style="text-align: center; color: rgb(8, 51, 207);font-size: 16px;">Asignación de
                                permisos al usuario</h5>
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <div class="card m-0 p-1">
                                        <div class="card-header p-1">
                                            <h5 class="card-title m-0 px-1 py-0"
                                                style="font-size: 12px;font-weight: 700;"><i
                                                    class="bi bi-house-lock"></i> MÓDULOS</h5>
                                        </div>
                                        <div class="card-body p-1">
                                            <div class="list-group" id="list-items-modulos">Sin módulos</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8">
                                    <div class="card m-0 p-1">
                                        <div class="card-header p-1">
                                            <h5 class="card-title m-0 px-1 py-0 text-dark"
                                                style="font-size: 12px;font-weight: 700;"> <span
                                                    id="display_module_selected" class="text-dark"></span> <i
                                                    class="bi bi-shield-lock"></i> PERMISOS
                                            </h5>
                                        </div>
                                        <div class="card-body p-1">
                                            <table class="table table-sm table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" id="CheckAllContent"></th>
                                                        <th scope="col">PERMISOS</th>
                                                        <th scope="col">DESCRIPCIÓN</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-size: 11px;" id="list-items-permisos">
                                                    <tr>
                                                        <td colspan="3" style="text-align: center"><p class="m-0 p-0 text-danger">Módulo no seleccionado.</p></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion" id="accordionPermisos">
                                <div class="row g-2" id="content_permisos">

                                </div>
                            </div>
                        </div>
                        @endif
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