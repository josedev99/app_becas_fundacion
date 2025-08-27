document.addEventListener('DOMContentLoaded', (event) => {
    //selectize init
    $("#departamento_suc").selectize();
    $("#municipio_suc").selectize();
    $("#tipo_estable_suc").selectize();
    //eventos change
    let deptosSuc = $("#departamento_suc").selectize()[0].selectize;
    deptosSuc.on('change', function (value) {
        getMunicipios('municipio_suc', value);
    });
    //registrar sucursal
    let formSucursal = document.getElementById('form-sucursal');
    if (formSucursal) {
        formSucursal.addEventListener('submit', onSubmitFormSucursal);
    }

    //cancelar edicion
    let btnCancelEdi = document.getElementById('btnCancelEdi');
    if (btnCancelEdi) {
        btnCancelEdi.addEventListener('click', () => {
            resetFormSucursal();
        });
    }
})

function resetFormSucursal() {
    document.getElementById('form-sucursal').reset();
    document.getElementById('form-sucursal').removeAttribute('sucursalId');
    document.getElementById('btnCancelEdi').style.display = 'none';
    document.getElementById('btnSaveSuc').innerHTML = `<i class="bi bi-floppy-fill"></i> Guardar`;
    $("#tipo_estable_suc")[0].selectize.setValue('');
    $("#departamento_suc")[0].selectize.setValue('');
    $("#municipio_suc")[0].selectize.setValue('');
}

function onSubmitFormSucursal(e) {
    e.preventDefault();

    let formData = new FormData(e.target);
    let empresa_id = document.getElementById('form-sucursal').getAttribute('empresaId');
    if (empresa_id) {
        formData.append('empresa_id', empresa_id);
    }

    let sucursal_id = document.getElementById('form-sucursal').getAttribute('sucursalId');
    if (sucursal_id) {
        formData.append('sucursal_id', sucursal_id);
    }

    //disabled button save
    let btnSaveSuc = document.getElementById('btnSaveSuc');
    btnSaveSuc.setAttribute('disabled', 'disabled');
    btnSaveSuc.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> procesando...`;

    axios.post(route('sucursal.save'), formData)
        .then((result) => {
            if (result.data.status === "success") {
                Swal.fire({
                    title: "Éxito",
                    text: result.data.message,
                    icon: "success"
                })
                resetFormSucursal();
            } else {
                Swal.fire({
                    title: "Error",
                    text: result.data.message,
                    icon: "error"
                })
            }
            btnSaveSuc.removeAttribute('disabled');
            btnSaveSuc.innerHTML = `<i class="bi bi-floppy-fill"></i> Guardar`;
            $("#dt-sucursales").DataTable().ajax.reload();
        }).catch((err) => {
            console.log(err);
            btnSaveSuc.removeAttribute('disabled');
            btnSaveSuc.innerHTML = `<i class="bi bi-floppy-fill"></i> Guardar`;
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

function editSucursal(element) {
    let sucursal_id = element.dataset.sucursal_id;
    let empresa_id = document.getElementById('form-sucursal').getAttribute('empresaId');

    //reset form
    document.getElementById('form-sucursal').reset();

    document.getElementById('form-sucursal').setAttribute('sucursalId', sucursal_id);

    axios.post(route('sucursal.obtener.id'), { sucursal_id: sucursal_id, empresa_id: empresa_id })
        .then((result) => {
            if (result.data.status === "success") {
                document.getElementById('btnCancelEdi').style.display = 'block';
                document.getElementById('btnSaveSuc').innerHTML = `<i class="bi bi-check"></i> Actualizar`;

                document.getElementById('nombre_suc').value = result.data.sucursal.nombre_comercial;
                $('#tipo_estable_suc')[0].selectize.setValue(result.data.sucursal.tipo_establecimiento);
                $("#departamento_suc")[0].selectize.setValue(result.data.sucursal.depto_code);
                getMunicipios('municipio_suc', result.data.sucursal.depto_code, () => {
                    $("#municipio_suc")[0].selectize.setValue(result.data.sucursal.munic_code);
                });

                document.getElementById('direccion_suc').value = result.data.sucursal.direccion;
                document.getElementById('cod_establecimiento_suc').value = result.data.sucursal.cod_establecimiento;
                document.getElementById('punto_venta_suc').value = result.data.sucursal.cod_punto_venta;
                document.getElementById('email_suc').value = result.data.sucursal.email;
                document.getElementById('telefono_suc').value = result.data.sucursal.telefono;
            }
            console.log(result);
        }).catch((err) => {
            console.log(err);
        });
}

function deleteSucursal(element) {
    let sucursal_id = element.dataset.sucursal_id;
    let nombre_comercial = element.dataset.nombre_comercial;
    let empresa_id = document.getElementById('form-sucursal').getAttribute('empresaId');

    Swal.fire({
        title: 'Eliminar sucursal',
        text: `Esta acción eliminara la sucursal: ${nombre_comercial}.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post(route('sucursal.delete'), { sucursal_id: sucursal_id, empresa_id: empresa_id })
                .then((result) => {
                    if (result.data.status === "success") {
                        Swal.fire({
                            title: "Éxito",
                            text: result.data.message,
                            icon: "success"
                        })
                        $("#dt-sucursales").DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: result.data.message,
                            icon: "error"
                        })
                    }
                }).catch((err) => {
                    console.log(err);
                    Swal.fire({
                        title: "Error",
                        text: 'Ha ocurrido un error al momento de actualizar el estado.',
                        icon: "error"
                    });
                });
        }
    });
}