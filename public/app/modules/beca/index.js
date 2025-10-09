const btnNewBeca = document.getElementById('btn-new-beca');
const formBeca = document.getElementById('form-beca');

document.addEventListener('DOMContentLoaded', ()=>{
    //Listar datos
    dataTable("dt-becas", route('becas.listar'));
    $("#tipo_beca").selectize();
    $("#financiamiento").selectize();
    $("#plazo_monto").selectize();
    $("#forma_entrega").selectize();
    $("#compromiso").selectize({
        maxItems: null
    });
    if(btnNewBeca){
        btnNewBeca.addEventListener('click', ()=>{
            formBeca.removeAttribute('record_id');
            showModalNewBeca();
        })
    }
    //
    if(formBeca){
        formBeca.addEventListener('submit', (e) => {
            e.preventDefault();
            let formData = new FormData(e.target);
            let compromiso = $("#compromiso")[0].selectize.getValue().join(',');
            if(compromiso){
                formData.append('compromiso', compromiso);
            }
            Swal.fire({
                title: 'Solicitud en proceso...',
                html: `
                    <div class="d-flex flex-column align-items-center">
                        <div class="spinner-border text-success mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <span>Por favor espere</span>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
            });
            let record_id = formBeca.getAttribute('record_id');
            if(record_id){
                formData.append('record_id', record_id);
            }
            axios.post(route('becas.save'), formData)
            .then((response) => {
                console.log(response);
                Swal.close();
                let data = response.data;
                if(data.status === "success"){
                    Swal.fire({
                        title: "Creado",
                        text: data.message,
                        icon: "success"
                    });
                    formBeca.reset();
                    $("#tipo_beca")[0].selectize.clear();
                    $("#financiamiento")[0].selectize.clear();
                    $("#plazo_monto")[0].selectize.clear();
                    $("#forma_entrega")[0].selectize.clear();
                    $("#compromiso")[0].selectize.clear();
                    $("#modal-form-beca").modal('hide');
                    //Refresh datos
                    dataTable("dt-becas", route('becas.listar'));
                }else{
                    Swal.fire({
                        title: "Atención",
                        text: data.message,
                        icon: data.status
                    });
                }
            }).catch((err) => {
                console.log(err);
                Swal.close();
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
function showModalNewBeca(){
    formBeca.reset();
    $("#tipo_beca")[0].selectize.clear();
    $("#financiamiento")[0].selectize.clear();
    $("#plazo_monto")[0].selectize.clear();
    $("#forma_entrega")[0].selectize.clear();
    $("#compromiso")[0].selectize.clear();
    $("#modal-form-beca").modal('show');
}

function editBeca(tag){
    let record_id = tag.dataset.record_id;
    formBeca.setAttribute('record_id', record_id);
    Swal.fire({
        title: 'Solicitud en proceso...',
        html: `
            <div class="d-flex flex-column align-items-center">
                <div class="spinner-border text-success mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <span>Por favor espere</span>
            </div>
        `,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
    });
    axios.post(route('beca.edit'), {record_id: record_id})
    .then((response)=>{
        Swal.close();
        showModalNewBeca();
        let data = response.data;
        $("#tipo_beca")[0].selectize.setValue(data.result.tipo_beca);
        $("#financiamiento")[0].selectize.setValue(data.result.financiamiento);
        $("#plazo_monto")[0].selectize.setValue(data.result.plazo_monto);
        $("#forma_entrega")[0].selectize.setValue(data.result.forma_entrega);
        $("#compromiso")[0].selectize.setValue(data.result.compromisos.split(','));

        document.getElementById('nombre_beca').value = data.result.nombre;
        document.getElementById('encargado_beca').value = data.result.responsable;

    })
    .catch((err)=>{
        Swal.close();
        console.log(err);
    })
    console.log(record_id);
}

function destroyBeca(){
    let record_id = tag.dataset.record_id;
    console.log(record_id);
}

function destroyBeca(tag){
    let {record_id, nombre} = tag.dataset;

    Swal.fire({
        title: "¿Estás seguro?",
        html: `Se eliminará la beca <strong>${nombre}</strong> de forma permanente.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545", // rojo tipo Bootstrap
        cancelButtonColor: "#6c757d",  // gris neutro
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Solicitud en proceso...',
                html: `
                    <div class="d-flex flex-column align-items-center">
                        <div class="spinner-border text-success mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <span>Por favor espere</span>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
            });
            axios.post(route('beca.destroy'), {record_id})
            .then((response)=>{
                Swal.close();
                let data = response.data;
                if(data.status === "success"){
                    Swal.fire({
                        title: "Eliminado",
                        text: `La beca: ${nombre} fue eliminado correctamente.`,
                        icon: "success",
                        timer: 2500,
                        showConfirmButton: true
                    });
                }else{
                    Swal.fire({
                        title: "Error",
                        text: data.message,
                        icon: "error",
                        timer: 3500,
                        showConfirmButton: true
                    });
                }
                $("#dt-becas").DataTable().ajax.reload();
            }).catch((err)=>{
                Swal.close();
                console.log(err);
                Swal.fire({
                    title: "Error",
                    text: `Ha ocurrido un error al procesar la solicitud.`,
                    icon: "error",
                    timer: 2500,
                    showConfirmButton: true
                });
            })
        }
    });
}