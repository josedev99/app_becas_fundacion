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
                text: `${element.nombre} - Tipo beca: ${element.tipo_beca}`
            });
        });
        console.log(response);
    }).catch((err)=>{
        console.log(err);
    })
}

function editEstudiante(tag){
    getBecas();
    let record_id = tag.dataset.record_id;
    $("#modal-form-becado").modal('show');
    axios.post(route('becado.edit'), {record_id: record_id})
    .then((response)=>{
        let data = response.data;
        //Elementos Estudiante
        document.getElementById('nombre_completo').value = data.nombre_completo;
        document.getElementById('documento').value = data.documento;
        document.getElementById('fecha_nacimiento').value = data.fecha_nacimiento;
        document.getElementById('direccion').value = data.direccion;
        document.getElementById('telefono').value = data.telefono;
        document.getElementById('contacto_emergencia').value = data.telefono_emergencia;
        document.getElementById('email_becado').value = data.email;
        $("#beca_id")[0].selectize.setValue(data.beca_id);
        //Datos academicos
        $("#nivel_educativo")[0].selectize.setValue(data.nivel_educativo);
        document.getElementById('institucion').value      = data.institucion;
        document.getElementById('carrera').value          = data.carrera_grado;
        document.getElementById('promedio').value         = data.promedio;
        $("#estado_academico")[0].selectize.setValue(data.estado_academico);
        document.getElementById('fInicio_beca').value     = data.fInicio;
        document.getElementById('fFin_beca').value        = data.fFin;
        //Datos socioeconomicos
        $("#situacion_familiar")[0].selectize.setValue(data.situacion_familiar);
        document.getElementById('ingreso_aprox').value       = data.ingresos;
        document.getElementById('numero_personas').value     = data.cantidad_personas;
        document.getElementById('necesidades_esp').value     = data.necesidades;
        document.getElementById('comunidad_residencia').value= data.comunidad;
        console.log(response);
    }).catch((err)=>{
        console.log(err);
    })
}