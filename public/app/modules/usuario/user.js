document.addEventListener('DOMContentLoaded', () => {
    $("#categoria_user").selectize();
    $("#estado_user").selectize();
    $("#empresa_id").selectize();
    dataTable("dt-users", route('user.listar'))
    try {
        let btnUserForm = document.getElementById('btn-form-usuario')
        if (btnUserForm) {
            btnUserForm.addEventListener('click', (e) => {
                permisosAsignadosUser = [];
                resetFormUser();
                getPermisos();//get permisos
                document.getElementById('display_title_user').textContent = 'REGISTRAR NUEVO USUARIO';
                document.getElementById('form-user').removeAttribute('record_id');
                $("#modal-form-user").modal('show');
                getEmpresasCuenta(0);//empresas
                e.stopPropagation();
            })
        }
        //Procesar formulario de usuario
        let formUser = document.getElementById('form-user');
        if (formUser) {
            formUser.addEventListener('submit', (e) => {
                e.preventDefault();
                let formData = new FormData(formUser);
                if (formData.get('nombre_user').trim() === "") {
                    Swal.fire({
                        title: "Aviso",
                        text: `El nombre del usuario es requerido.`,
                        icon: "warning"
                    });
                    return;
                }
                if (formData.get('estado_user').trim() === "") {
                    Swal.fire({
                        title: "Aviso",
                        text: `El estado del usuario es requerido.`,
                        icon: "warning"
                    });
                    return;
                }
                if (formData.get('usuario_user').trim() === "") {
                    Swal.fire({
                        title: "Aviso",
                        text: `El usuario es requerido.`,
                        icon: "warning"
                    });
                    return;
                }
                if (formData.get('clave_user').trim() === "") {
                    Swal.fire({
                        title: "Aviso",
                        text: `La clave del usuario es requerido.`,
                        icon: "warning"
                    });
                    return;
                }
                if (formData.get('categoria_user').trim() === "") {
                    Swal.fire({
                        title: "Aviso",
                        text: `La categoria del usuario es requerido.`,
                        icon: "warning"
                    });
                    return;
                }
                //validacion de permisos
                if (permisosAsignadosUser.length === 0) {
                    Swal.fire({
                        title: "Aviso",
                        text: `No hay permisos seleccionados.`,
                        icon: "warning"
                    });
                    return;
                }
                formData.append('permisosAsignadosUser', JSON.stringify(permisosAsignadosUser));
                //add record update
                let record_id = (formUser.getAttribute('record_id') !== null) ? formUser.getAttribute('record_id') : 0;
                formData.append('record_id', record_id);
                //disabled button
                let btnSaveUser = document.getElementById('btnSaveUser');
                btnSaveUser.disabled = true;
                btnSaveUser.innerHTML = ` <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span> guardando...`;
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
                axios.post(route('user.save'), formData)
                    .then((res) => {
                        Swal.close();
                        if (res.data.status === "success") {
                            permisosAsignadosUser = [];
                            Swal.fire({
                                title: "Éxito",
                                text: res.data.message,
                                icon: "success"
                            });
                            $("#modal-form-user").modal('hide');
                            resetFormUser();
                            $("#dt-users").DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: res.data.message,
                                icon: "error"
                            });
                        }
                        btnSaveUser.disabled = false;
                        btnSaveUser.innerHTML = `<i class="bi bi-person-add"></i> Registrar`;
                    })
                    .catch(err => {
                        Swal.close();
                        console.log(err);
                        btnSaveUser.disabled = false;
                        btnSaveUser.innerHTML = `<i class="bi bi-person-add"></i> Registrar`;
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
                    })
            })
        }
        //Show password
        let iconShowPasswd = document.getElementById('icon-show-passwd');
        if (iconShowPasswd) {
            iconShowPasswd.addEventListener('click', (e) => {
                e.stopPropagation();
                let input_passwd = document.getElementById('clave_user');
                //let array_icon_class = ['', 'bi-eye'];
                if (clave_user && input_passwd.type === "password") {
                    clave_user.type = 'text';
                    iconShowPasswd.classList.replace('bi-eye-slash', 'bi-eye');
                    iconShowPasswd.title = 'Ocultar password';
                } else {
                    clave_user.type = 'password';
                    iconShowPasswd.classList.replace('bi-eye', 'bi-eye-slash');
                    iconShowPasswd.title = 'Mostrar clave';
                }
            })
        }
    } catch (err) {
        console.log(err)
    }
})

function editUser(element) {
    permisosAsignadosUser = [];
    resetFormUser();
    document.getElementById('display_title_user').textContent = 'ACTUALIZAR DATOS DEL USUARIO';
    let record_id = element.dataset.record_id;
    let cuenta_id = element.dataset.cuenta_id;

    getEmpresasCuenta(cuenta_id);

    getPermisos(cuenta_id, record_id);//permisos

    document.getElementById('form-user').setAttribute('record_id', record_id);
    axios.post(route('user.by.id'), { record_id })
        .then((response) => {
            let { status, message, result } = response.data;

            if (status === "success") {
                let data = result;
                document.getElementById('nombre_user').value = data.nombre;
                document.getElementById('doc_user').value = data.documento;
                document.getElementById('telefono_user').value = data.telefono;
                document.getElementById('direccion_user').value = data.direccion;
                document.getElementById('email_user').value = data.email;
                document.getElementById('clave_user').value = data.clave;
                $("#estado_user").selectize()[0].selectize.setValue(data.estado);
                $("#categoria_user").selectize()[0].selectize.setValue(data.categoria);
                document.getElementById('usuario_user').value = data.usuario;
                document.getElementById('cargo_user').value = data.cargo;
                $("#empresa_id").selectize()[0].selectize.setValue(data.empresa_id);
                $('#modal-form-user').modal('show');
            } else {
                Swal.fire({
                    title: "Error",
                    text: message,
                    icon: "error"
                });
            }
        }).catch((err) => {
            console.log(err);
            Swal.fire({
                title: "Error",
                text: 'Ha ocurrido un error al obtener la información del usuario',
                icon: "error"
            });
        })
}

function destroyUser(element) {
    Swal.fire({
        title: "Aviso",
        text: `Esta funcionalidad esta en desarrollo.`,
        icon: "warning"
    });
}

function resetFormUser() {
    document.getElementById('form-user').reset();
    $("#estado_user").selectize()[0].selectize.clear();
    $("#categoria_user").selectize()[0].selectize.clear();
}

function getEmpresasCuenta(cuenta_id) {
    axios.post(route('user.empresas.obtener'), { cuenta_id: cuenta_id })
        .then((result) => {
            let data = result.data;
            let empresas = $("#empresa_id").selectize()[0].selectize;
            empresas.clear();
            empresas.clearOptions();
            if (data.length > 0) {
                data.forEach((empresa) => {
                    empresas.addOption({ value: empresa.id, text: empresa.nombre });
                });
            }
        }).catch((err) => {
            console.log(err);
        });
}