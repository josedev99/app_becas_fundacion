const btnNewSeguimiento = document.querySelector('#btn-new-seguimiento');
const formSeguimiento = document.querySelector('#form-seguimiento');

document.addEventListener('DOMContentLoaded', (e)=> {
    dataTable('dt-seguimiento', route('seguimiento.listar'));
    $("#becado_seguimiento").selectize();
    $("#estado_beca").selectize();
    $("#prioridad_segui").selectize();

    if(btnNewSeguimiento){
        btnNewSeguimiento.addEventListener('click', (e)=>{
            e.preventDefault();
            getBecadosAll();
            resetForm();
            //Abrir modal
            $("#modal-form-seguimiento").modal('show');
        })
    }

    if(formSeguimiento){
        formSeguimiento.addEventListener('submit', (e) => {
            e.preventDefault();
            let formData = new FormData(e.target);
            Swal.fire({
                title: 'Solicitud en proceso...',
                html: `
                    <div class="d-flex flex-column align-items-center">
                        <div class="spinner-border text-success mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">procesando...</span>
                        </div>
                        <span>Por favor espere</span>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
            });
            axios.post(route('seguimiento.save'), formData)
            .then((response) => {
                Swal.close();
                let {status, message} = response.data;
                if(status === "success"){
                    Swal.fire({
                        title: "Creado",
                        text: message,
                        icon: "success"
                    });
                    resetForm();
                    $("#modal-form-seguimiento").modal('hide');
                    $("#dt-seguimiento").DataTable().ajax.reload(null,false);
                }else{
                    Swal.fire({
                        title: "Atención",
                        text: message,
                        icon: "error"
                    });
                }
                console.log(response);
            }).catch((err) => {
                Swal.close();
                console.log(err);
            });
        })
    }
});

function resetForm(){
    formSeguimiento.reset();
    $("#becado_seguimiento")[0].selectize.clear();
    $("#estado_beca")[0].selectize.clear();
    $("#prioridad_segui")[0].selectize.clear();
}

function getBecadosAll(){
    axios.post(route('becado.getAll'))
    .then((response) => {
        let data = response.data;
        let selectizeEstudiante = $("#becado_seguimiento")[0].selectize;
        selectizeEstudiante.clear();
        selectizeEstudiante.clearOptions();
        data.forEach(becado => {
            selectizeEstudiante.addOption({
                value: becado.id,
                text: `[${becado.documento}] - ${becado.nombre_completo}`
            });
        });
        console.log(response)
    }).catch((err) => {
        console.log(err);
    });
}