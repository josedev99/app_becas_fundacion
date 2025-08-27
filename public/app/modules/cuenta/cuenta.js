document.addEventListener('DOMContentLoaded', (e) => {
    dataTable("dt-cuentas", route('cuenta.listar'));
    //modal form cuenta
    let btnNewCuenta = document.getElementById('btnNewCuenta');
    if (btnNewCuenta) {
        btnNewCuenta.addEventListener('click', (e) => {
            modulosAsignadosCuenta = [];//reset modulos
            getModulosPermisos();
            document.getElementById('form-cuenta').removeAttribute('cuentaId');
            document.getElementById('fieldset-admin').classList.replace('d-none', 'd-block');
            $("#modal-form-cuenta").modal('show');
            //button submit
            document.getElementById('btnSaveCuenta').textContent = 'Crear cuenta';
            document.getElementById('form-cuenta').reset();
            //modal title
            document.getElementById('d_title_cuenta').textContent = 'REGISTRAR NUEVA CUENTA';
        });
    }
    //capture form submit cuenta
    let formCuenta = document.getElementById('form-cuenta');
    if (formCuenta) {
        formCuenta.addEventListener('submit', onSubmitCuenta);
    }
})

function showPassword(element) {
    let input = document.getElementById(element.dataset.id_input);
    let icon = element;
    if (input.type === "password") {
        input.type = 'text';
        icon.classList.replace('bi-eye-slash','bi-eye');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye','bi-eye-slash');
    }
}

function onSubmitCuenta(e) {
    e.preventDefault();
    let form = document.getElementById('form-cuenta');
    let inputs = form.querySelectorAll('.valid');
    for (let index = 0; index < inputs.length; index++) {
        let input = inputs[index];
        if (input.value === '') {
            Swal.fire({
                title: "Aviso",
                text: input.title,
                icon: "warning"
            });
            return;
        }
    }

    if(modulosAsignadosCuenta.length === 0){
        Swal.fire({
            title: "Atención",
            text: 'No hay módulos seleccionados para asignar.',
            icon: "warning"
        });return;
    }
    let formData = new FormData(form);
    formData.append('modulosAsignadosCuenta', JSON.stringify(modulosAsignadosCuenta));
    //disable button submit
    let btnSaveCuenta = document.getElementById('btnSaveCuenta');
    btnSaveCuenta.setAttribute('disabled', 'true');
    btnSaveCuenta.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
    if (form.getAttribute('cuentaId')) {
        formData.append('cuentaId', form.getAttribute('cuentaId'));
    }
    axios.post(route('cuenta.save'), formData)
        .then((result) => {
            let { status, message } = result.data;
            if (status === "success") {
                Swal.fire({
                    title: "Éxito",
                    text: message,
                    icon: "success"
                })
                $("#modal-form-cuenta").modal('hide');
                form.reset();
                modulosAsignadosCuenta = [];
            } else {
                Swal.fire({
                    title: "Error",
                    text: message,
                    icon: "error"
                });
            }
            btnSaveCuenta.removeAttribute('disabled');
            btnSaveCuenta.innerHTML = '<i class="bi bi-floppy-fill"></i> Guardar';
            $("#dt-cuentas").DataTable().ajax.reload();
        }).catch((err) => {
            console.log(err);
            btnSaveCuenta.removeAttribute('disabled');
            btnSaveCuenta.innerHTML = '<i class="bi bi-floppy-fill"></i> Guardar';
            let errors = err.response?.data?.errors;
            if (errors) {
                for (const [key, arrayError] of Object.entries(errors)) {
                    if (arrayError.length > 0) {
                        Swal.fire({
                            title: "Aviso",
                            text: arrayError[0],
                            icon: "warning"
                        });
                        break;
                    }
                }
            } else {
                Swal.fire({
                    title: "Error",
                    text: "Ocurrió un error al procesar la solicitud",
                    icon: "error"
                });
            }
        });
}

function editAccount(element) {
    modulosAsignadosCuenta = []; //reset modulos permisos
    document.getElementById('fieldset-admin').classList.replace('d-block', 'd-none');
    let cuentaId = element.dataset.record_id;

    //button submit
    document.getElementById('btnSaveCuenta').innerHTML = `<i class="bi bi-check"></i> Actualizar cuenta`;
    //modal title
    document.getElementById('d_title_cuenta').textContent = 'ACTUALIZAR CUENTA';
    getModulosPermisos(cuentaId);

    $("#modal-form-cuenta").modal('show');

    axios.post(route('cuenta.obtener'), { cuentaId: cuentaId })
        .then((result) => {
            if (result.data) {
                document.getElementById('form-cuenta').setAttribute('cuentaId', cuentaId);
                //reset form cuenta
                document.getElementById('form-cuenta').reset();

                document.getElementById('cuenta').value = result.data.nombre;
                document.getElementById('propietario').value = result.data.propietario;
                document.getElementById('email').value = result.data.email;
                document.getElementById('telefono').value = result.data.telefono;
                document.getElementById('cantidad_emp').value = result.data.limite_empresas;
            }
        }).catch((err) => {
            console.log(err);
        });
}

function toggleStatus(element) {
    let { record_id, estado } = element.dataset;

    let messageAlert = estado === "Activado" ? '¿Desea desactivar la cuenta seleccionada?' : '¿Desea activar la cuenta seleccionada?';
    //message
    Swal.fire({
        title: messageAlert,
        text: "Esta acción actualizará el estado de la cuenta seleccionada.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post(route('cuenta.estado'), { cuenta_id: record_id })
                .then((result) => {
                    if (result.data.status === "success") {
                        Swal.fire({
                            title: "Éxito",
                            text: result.data.message,
                            icon: "success"
                        });
                        $("#dt-cuentas").DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: result.data.message,
                            icon: "error"
                        });
                    }
                }).catch((err) => {
                    Swal.fire({
                        title: "Error",
                        text: 'Ha ocurrido un error al momento de actualizar el estado.',
                        icon: "error"
                    });
                    console.log(err);
                });
        }
    });
}