const btnNewBecado = document.querySelector('#btn-new-becado');
const formEstudiante = document.querySelector('#form-becado');
//button para agregar nueva instucion
const btnAddInstitucion = document.getElementById('btn-add-institucion');
const btnAddCarrera = document.getElementById('btn-add-carrera');

document.addEventListener('DOMContentLoaded', (e)=> {
    dataTable('dt-becados', route('becado.listar'));
    $("#beca_id").selectize();
    $("#nivel_educativo").selectize();
    $("#estado_academico").selectize();
    $("#situacion_familiar").selectize();
    //new selectize
    $("#institucion").selectize();
    $("#carrera").selectize();
    if(btnNewBecado){
        btnNewBecado.addEventListener('click', (e)=>{
            e.preventDefault();
            resetForm();
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
            let record_id = formEstudiante.getAttribute('data-record_id');
            if(record_id){
                console.log(record_id);
                formData.append('record_id', record_id);
            }
            axios.post(route('becado.save'),formData)
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
                    resetForm();
                    $("#dt-becados").DataTable().ajax.reload(null,false);
                }else{
                    Swal.fire({
                        title: "Atención",
                        text: data.message,
                        icon: "error"
                    });
                }
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
    //Nueva institucion
    if(btnAddInstitucion){
        btnAddInstitucion.addEventListener('click', (e)=> {
            e.stopPropagation();
            document.getElementById('form-val-dinamico').reset();
            document.getElementById('modal-title-valores').textContent = `Registrar nueva Institución`;
            document.getElementById('form-val-dinamico').setAttribute('data-modulo','becados');
            document.getElementById('form-val-dinamico').setAttribute('data-identificador','institucion');
            $("#modal-form-valores").modal('show');
        })
    }

    if(btnAddCarrera){
        btnAddCarrera.addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('form-val-dinamico').reset();
            document.getElementById('modal-title-valores').textContent = `Registrar nueva carrera`;
            document.getElementById('form-val-dinamico').setAttribute('data-modulo','becados');
            document.getElementById('form-val-dinamico').setAttribute('data-identificador','carrera');
            $("#modal-form-valores").modal('show');
        })
    }
})

function resetForm(){
    formEstudiante.reset();
    $("#modal-form-becado").modal('hide');
    $("#beca_id")[0].selectize.clear();
    $("#nivel_educativo")[0].selectize.clear();
    $("#situacion_familiar")[0].selectize.clear();
    $("#institucion")[0].selectize.clear();
    $("#carrera")[0].selectize.clear();
}

function getBecas(){
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
    axios.post(route('becas.obtener'))
    .then((response)=>{
        Swal.close();
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
        Swal.close();
        console.log(err);
    })
}

function editEstudiante(tag){
    getBecas();
    let record_id = tag.dataset.record_id;
    $("#modal-form-becado").modal('show');
    axios.post(route('becado.edit'), {record_id: record_id})
    .then((response)=>{
        resetForm();
        formEstudiante.setAttribute('data-record_id', record_id);
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
        $('#institucion')[0].selectize.setValue(data.institucion);
        $('#carrera')[0].selectize.setValue(data.carrera_grado);
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

function removeEstudiante(tag_button){
    let {record_id, nombre} = tag_button.dataset;
    Swal.fire({
        title: "¿Estás seguro?",
        html: `Se eliminará al becado <strong>${nombre}</strong> de forma permanente.`,
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
            axios.post(route('destroy.becado'), {record_id})
            .then((response)=>{
                let data = response.data;
                if(data.status === "success"){
                    Swal.fire({
                        title: "Eliminado",
                        text: `El becado ${nombre} fue eliminado correctamente.`,
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
                $("#dt-becados").DataTable().ajax.reload();
            }).catch((err)=>{
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