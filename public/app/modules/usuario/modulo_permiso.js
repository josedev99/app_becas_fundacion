//Buttons modulo
const btn_open_modulo = document.getElementById('btn-open-modulo');
const btnCancelEdicModulo = document.getElementById('btnCancelEdicModulo');
const form_modulo = document.getElementById('form-modulo');
const btnSaveModulo = document.getElementById('btnSaveModulo');
//Button permiso
const btnCancelEdicPermiso = document.getElementById('btnCancelEdicPermiso');
const form_modulo_permiso = document.getElementById('form-modulo-permiso');
const btnSavePermiso = document.getElementById('btnSavePermiso');

document.addEventListener('DOMContentLoaded', () => {
    dataTable('dt-modulos', route('modulo.listar.dt'));
    if (btn_open_modulo) {
        btn_open_modulo.addEventListener('click', () => {
            if (btnCancelEdicModulo) {
                btnCancelEdicModulo.style.display = 'none';
            }
            btnSaveModulo.innerHTML = `<i class="bi bi-shield-plus"></i> Crear módulo`;
            $("#modal-modulos").modal('show');
        })
    }
    if (btnCancelEdicModulo) {
        btnCancelEdicModulo.addEventListener('click', (e) => {
            form_modulo.reset();
            form_modulo.removeAttribute('modulo_id');
            btnCancelEdicModulo.style.display = 'none';
            btnSaveModulo.innerHTML = `<i class="bi bi-shield-plus"></i> Crear módulo`;
        })
    }
    //permiso edicion cancelar
    if (btnCancelEdicPermiso) {
        btnCancelEdicPermiso.addEventListener('click', (e) => {
            form_modulo_permiso.reset();
            form_modulo_permiso.removeAttribute('permiso_id');
            btnCancelEdicPermiso.style.display = 'none';
            btnSavePermiso.innerHTML = `<i class="bi bi-shield-plus"></i> Crear permiso`;
        })
    }
    if (form_modulo) {
        form_modulo.addEventListener('submit', (e) => {
            e.preventDefault();
            let formData = new FormData(e.target);
            let modulo_id = form_modulo.getAttribute('modulo_id');
            if (modulo_id) {
                formData.append('modulo_id', modulo_id);
            }
            btnSaveModulo.setAttribute('disabled', 'true');
            btnSaveModulo.innerHTML = `<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> procesando...`;
            Swal.fire({
                title: 'Procesando...',
                text: 'Por favor, espera mientras completamos la operación.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            axios.post(route('modulo.create'), formData)
                .then((response) => {
                    Swal.close();
                    let data = response.data;
                    if (data.status === "success") {
                        Swal.fire({
                            title: "Éxito",
                            text: data.message,
                            icon: data.status
                        });
                        form_modulo.reset();
                        $("#dt-modulos").DataTable().ajax.reload();
                        form_modulo.removeAttribute('modulo_id');
                        btnCancelEdicModulo.style.display = 'none';
                    } else {
                        Swal.fire({
                            title: "Aviso",
                            text: data.message,
                            icon: data.status
                        });
                    }
                    btnSaveModulo.removeAttribute('disabled');
                    btnSaveModulo.innerHTML = `<i class="bi bi-shield-plus"></i> Guardar`;
                    console.log(response);
                }).catch((err) => {
                    Swal.close();
                    btnSaveModulo.removeAttribute('disabled');
                    btnSaveModulo.innerHTML = `<i class="bi bi-shield-plus"></i> Guardar`;
                    console.log(err);
                    let errors = err.response?.data?.errors;
                    if (errors) {
                        for (let [key, arrayMessages] of Object.entries(errors)) {
                            let messageError = arrayMessages[0];
                            Swal.fire({
                                title: "Error",
                                text: messageError,
                                icon: "error"
                            }); return;
                        }
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: 'Ha ocurrido un error inesperado. Por favor, inténtelo nuevamente.',
                            icon: "error"
                        });
                    }
                });
        })
    }
    if (form_modulo_permiso) {
        form_modulo_permiso.addEventListener('submit', (e) => {
            e.preventDefault();
            let formData = new FormData(e.target);
            let modulo_id = form_modulo_permiso.getAttribute('modulo_id');
            let permiso_id = form_modulo_permiso.getAttribute('permiso_id');
            if (modulo_id) {
                formData.append('modulo_id', modulo_id);
            }
            if(permiso_id){
                formData.append('permiso_id', permiso_id);
            }
            btnSavePermiso.setAttribute('disabled', 'true');
            btnSavePermiso.innerHTML = `<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> procesando...`;
            Swal.fire({
                title: 'Procesando...',
                text: 'Por favor, espera mientras completamos la operación.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            axios.post(route('modulo.permiso.create'), formData)
                .then((response) => {
                    Swal.close();
                    let data = response.data;
                    if (data.status === "success") {
                        Swal.fire({
                            title: "Éxito",
                            text: data.message,
                            icon: data.status
                        });
                        form_modulo_permiso.reset();
                        $("#dt-modulos-permisos").DataTable().ajax.reload();
                        form_modulo_permiso.removeAttribute('permiso_id');
                        btnCancelEdicPermiso.style.display = 'none';
                    } else {
                        Swal.fire({
                            title: "Aviso",
                            text: data.message,
                            icon: data.status
                        });
                    }
                    btnSavePermiso.removeAttribute('disabled');
                    btnSavePermiso.innerHTML = `<i class="bi bi-shield-plus"></i> Crear permiso`;
                    console.log(response);
                }).catch((err) => {
                    Swal.close();
                    btnSavePermiso.removeAttribute('disabled');
                    btnSavePermiso.innerHTML = `<i class="bi bi-shield-plus"></i> Crear permiso`;
                    console.log(err);
                    let errors = err.response?.data?.errors;
                    if (errors) {
                        for (let [key, arrayMessages] of Object.entries(errors)) {
                            let messageError = arrayMessages[0];
                            Swal.fire({
                                title: "Error",
                                text: messageError,
                                icon: "error"
                            }); return;
                        }
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: 'Ha ocurrido un error inesperado. Por favor, inténtelo nuevamente.',
                            icon: "error"
                        });
                    }
                });
        })
    }
})

function crearPermiso(element) {
    let modulo_id = element.dataset.id;
    form_modulo_permiso.setAttribute('modulo_id',modulo_id);
    let datos_decoded = JSON.parse(atob(element.dataset.datos_encoded));
    document.getElementById('title_modulo').textContent = datos_decoded.nombre;
    //cargar dataTable
    dataTable('dt-modulos-permisos', route('modulo.listar.permiso.dt'), {modulo_id: modulo_id});
    $("#modal-modulos-permiso").modal('show');
    btnCancelEdicPermiso.style.display = 'none';
    form_modulo_permiso.reset();
}
function editarModulo(element) {
    let modulo_id = element.dataset.id;
    form_modulo.setAttribute('modulo_id', modulo_id);
    try {
        let datos_decoded = JSON.parse(atob(element.dataset.datos_encoded));
        document.getElementById('clave_modulo').value = datos_decoded.clave;
        document.getElementById('name_modulo').value = datos_decoded.nombre;
        document.getElementById('descripcion_modulo').value = datos_decoded.descripcion;
    } catch (err) {
        document.getElementById('clave_modulo').value = '';
        document.getElementById('name_modulo').value = '';
        document.getElementById('descripcion_modulo').value = '';
    }
    btnCancelEdicModulo.style.display = 'block';
    btnSaveModulo.innerHTML = `<i class="bi bi-shield-plus"></i> Guardar cambios`;
}
//permiso
function editarPermiso(element) {
    let permiso_id = element.dataset.id;
    form_modulo_permiso.setAttribute('permiso_id', permiso_id);
    try {
        let datos_decoded = JSON.parse(atob(element.dataset.datos_encoded));
        document.getElementById('clave_permiso').value = datos_decoded.clave;
        document.getElementById('name_permiso').value = datos_decoded.nombre;
        document.getElementById('descripcion_permiso').value = datos_decoded.descripcion;
    } catch (err) {
        document.getElementById('clave_permiso').value = '';
        document.getElementById('name_permiso').value = '';
        document.getElementById('descripcion_permiso').value = '';
    }
    btnCancelEdicPermiso.style.display = 'block';
    btnSavePermiso.innerHTML = `<i class="bi bi-shield-plus"></i> Guardar cambios`;
}

function deleteModulo(element) {
    let modulo_id = element.dataset.id;
    Swal.fire({
        title: "¿Eliminar módulo?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Procesando...",
                text: "Por favor espera.",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });
            axios.post(route('modulo.delete'), { modulo_id })
                .then(({ data }) => {
                    Swal.close();
                    Swal.fire({
                        title: data.status === "success" ? "Éxito" : "Aviso",
                        text: data.message,
                        icon: data.status,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    $("#dt-modulos").DataTable().ajax.reload(null, false);
                })
                .catch((error) => {
                    Swal.close();
                    console.error(error);
                    Swal.fire({
                        title: "Error",
                        text: "Ocurrió un problema al eliminar el módulo.",
                        icon: "error"
                    });
                });
        }
    });
}

function deletePermiso(element){
    let permiso_id = element.dataset.id;
    Swal.fire({
        title: "¿Eliminar el permiso?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Procesando...",
                text: "Por favor espera.",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });
            axios.post(route('modulo.permiso.delete'), { permiso_id })
                .then(({ data }) => {
                    Swal.close();
                    Swal.fire({
                        title: data.status === "success" ? "Éxito" : "Aviso",
                        text: data.message,
                        icon: data.status,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    $("#dt-modulos-permisos").DataTable().ajax.reload(null, false);
                })
                .catch((error) => {
                    Swal.close();
                    console.error(error);
                    Swal.fire({
                        title: "Error",
                        text: "Ocurrió un problema al eliminar el módulo.",
                        icon: "error"
                    });
                });
        }
    });
}