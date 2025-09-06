const btnNewBecado = document.querySelector('#btn-new-becado');
const formEstudiante = document.querySelector('#form-becado');

document.addEventListener('DOMContentLoaded', (e)=> {
    dataTable('dt-becados', route('becado.listar'));
    $("#beca_id").selectize();
    $("#nivel_educativo").selectize();
    $("#estado_academico").selectize();
    $("#situacion_familiar").selectize();
    if(btnNewBecado){
        btnNewBecado.addEventListener('click', (e)=>{
            e.preventDefault();
            //Abrir modal
            $("#modal-form-becado").modal('show');
            getBecas();
        })
    }
    //Enviar datos
    if(formEstudiante){
        formEstudiante.addEventListener('submit', (e)=> {
            e.preventDefault();
            let formData = new FormData(e.target);
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
            axios.post(route('becado.save'),formData)
            .then((response) => {
                Swal.close();
                console.log(response);
            }).catch((err) => {
                Swal.close();
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

function getBecas(){
    axios.post(route('becas.obtener'))
    .then((response)=>{
        let data = response.data;
        let becas_selectize = $("#beca_id")[0].selectize;
        becas_selectize.clear();
        becas_selectize.clearOptions();
        data.forEach(element => {
            becas_selectize.addOption({
                value: element.id,
                text: element.tipo_beca
            });
        });
        console.log(response);
    }).catch((err)=>{
        console.log(err);
    })
}