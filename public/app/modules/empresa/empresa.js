document.addEventListener('DOMContentLoaded', (event)=> {
    dataTable('dt-empresas',route('empresa.listar'));
    //selectize init
    $("#actividad_economica").selectize();
    //init function
    getActividadesEcono();
    //button new empresa
    let btnNewEmpresa = document.getElementById('btnNewEmpresa');
    if(btnNewEmpresa){
        btnNewEmpresa.addEventListener('click', (e)=> {
            resetFormEmpresa();

            document.getElementById('display_modal_empresa').textContent = 'REGISTRAR NUEVA EMPRESA';
            $("#modal-form-empresa").modal('show');
            document.getElementById('form-empresa').removeAttribute('record_id');
            document.getElementById('form-empresa').reset();
            e.stopPropagation();
        })
    }
    //procesar form
    let form_empresa = document.getElementById('form-empresa');
    if(form_empresa){
        form_empresa.addEventListener('submit', procesarFormEmpresa);
    }
})

function getActividadesEcono(done = null){
    axios.get(route('actividad.economicas.get'))
    .then((result) => {
        let data = result.data;
        let act_economicas_selectize = $("#actividad_economica")[0].selectize;
        act_economicas_selectize.clear();
        act_economicas_selectize.clearOptions();
        if(data.length > 0){
            data.forEach(item => {
                act_economicas_selectize.addOption({
                    value: `${item.codigo} - ${item.nombre}`,
                    text: `${item.codigo} - ${item.nombre}`
                });
            });
            if(done && typeof done === "function"){
                done();
            }
        }
    }).catch((err) => {
        console.log(err);
    });
}

function getMunicipios(id,codeDepto,done = null){
    axios.get('app/json/municipios.json')
    .then((result) => {
        let data = result.data;
        let munic_selectize = $(`#${id}`)[0].selectize;
        munic_selectize.clear();
        munic_selectize.clearOptions();
        if(data.length > 0){
            let municipios_filter = data.filter((item)=> item.departCode === codeDepto);
            municipios_filter.forEach((munic)=> {
                munic_selectize.addOption({
                    value: munic.codigo,
                    text: munic.nombre
                });
            });
            if(done && typeof done === "function"){
                done();
            }
        }
    }).catch((err) => {
        console.log(err);
    });
}

function procesarFormEmpresa(event){
    event.preventDefault();
    let formData = new FormData(event.target);
    let ambiente = document.querySelector('input[name="checkAmbienteDte"]:checked');
    if (!ambiente) {
        Toast.fire({ icon: 'error', title: 'Por favor, seleccionar el ambiente de prueba o productivo!' }); return 0;
    }

    let record_id = document.getElementById('form-empresa').getAttribute('record_id');

    formData.append('empresa_id', record_id);

    let btnSaveEmp = document.getElementById('btnSaveEmp');
    btnSaveEmp.setAttribute('disabled', 'disabled');
    btnSaveEmp.innerHTML = `<span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span> procesando...`;

    axios.post(route('empresa.save'),formData,{
        headers: {'Content-Type' : 'multipart/form-data'}
    }).then((result) => {
        let {status,message} = result.data;
        if(status === "success"){
            Swal.fire({
                title: "Éxito",
                text: message,
                icon: "success"
            });
            resetFormEmpresa();
            $("#modal-form-empresa").modal('hide');
            $("#dt-empresas").DataTable().ajax.reload(null,false);
        }else{
            btnSaveEmp.removeAttribute('disabled');
            Swal.fire({
                title: "Error",
                text: message,
                icon: "error"
            });
        }
        btnSaveEmp.removeAttribute('disabled');
        btnSaveEmp.innerHTML = `<i class="bi bi-person-add"></i> Registrar`;
    }).catch((err) => {
        console.log(err);
        btnSaveEmp.removeAttribute('disabled');
        btnSaveEmp.innerHTML = `<i class="bi bi-person-add"></i> Registrar`;
        let errors = err.response?.data?.errors;
        if(errors){
            for (let [key, arrayMessages] of Object.entries(errors)) {
                let messageError = arrayMessages[0];
                Swal.fire({
                    title: "Error",
                    text: messageError,
                    icon: "error"
                });return;
            }
        }else{
            Swal.fire({
                title: "Error",
                text: 'Ha ocurrido un error inesperado. Por favor, inténtelo nuevamente.',
                icon: "error"
            });
        }
    });
}

function resetFormEmpresa(){
    document.getElementById('form-empresa').reset();
    $("#actividad_economica")[0].selectize.clear();

    document.getElementById('display_name_cert').innerHTML = '';
    document.getElementById('display_upload_file').textContent = 'Cargar certificado';
}

function editEmpresa(element){
    resetFormEmpresa();
    document.getElementById('display_modal_empresa').textContent = 'ACTUALIZAR EMPRESA';
    let record_id = element.dataset.record_id;
    document.getElementById('form-empresa').setAttribute('record_id',record_id);
    axios.post(route('empresa.obtener.id'), {empresa_id: record_id})
    .then((result) => {
        let data = result.data.result;
        if(data.ambiente === "00"){
            document.getElementById('icheckDtePrueba').checked = true;
        }else{
            document.getElementById('icheckDteProductivo').checked = true;
        }

        //file certificado
        document.getElementById('display_name_cert').innerHTML = (data.certificado_path !== "") ? `<b>${data.certificado_path}</b> <i class="bi bi-check2-circle" style="font-size: 16px;"></i>` : '';
        
        if(data.certificado_path === ""){
            document.getElementById('display_upload_file').textContent = 'Cargar certificado';
        }else{
            document.getElementById('display_upload_file').textContent = 'Actualizar certificado';
        }

        document.getElementById('nombre').value = data.nombre;
        document.getElementById('nrc').value = data.nrc;
        document.getElementById('nit').value = data.nit;
        document.getElementById('email').value = data.email;
        document.getElementById('telefono').value = data.telefono;
        document.getElementById('clave_cert').value = data.clave_cert;
        document.getElementById('clave_api').value = data.clave_api;

        getActividadesEcono(()=> {
            $("#actividad_economica")[0].selectize.setValue(`${data.act_economica} - ${data.desc_actividad}`);
        });

        //logo preview image
        showLogo(data.logo);

        $("#modal-form-empresa").modal('show');
    }).catch((err) => {
        console.log(err);
    });
}

function showPassword(element){
    let input = document.getElementById(element.dataset.id_input);
    let icon = element;
    if(input.type === "password"){
        input.type = 'text';
        icon.classList.replace('bi-eye-slash','bi-eye');
    }else{
        input.type = 'password';
        icon.classList.replace('bi-eye','bi-eye-slash');
    }
}

function refreshToken(element){
    let {record_id,empresa} = element.dataset;
    Swal.fire({
        title: "¿Refrescar token?",
        text: `Esta acción actualizará el token de acceso al Ministerio de Hacienda para la empresa: ${empresa}.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, refrescar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            let contador = 1;
            const loader = Swal.fire({
                title: "Procesando...",
                html: `Por favor espere <b>${contador}</b> segundos...`,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                    const interval = setInterval(() => {
                        contador++;
                        Swal.getHtmlContainer().querySelector("b").textContent = contador;
                    }, 1000);
                    axios.post(route('mh.refresh.token'), {empresa_id: record_id})
                        .then((result) => {
                            clearInterval(interval);
                            if (result.data.status === "success") {
                                Swal.fire({
                                    title: "Éxito",
                                    text: result.data.message,
                                    icon: "success"
                                });
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: result.data.message,
                                    icon: "error"
                                });
                            }
                        }).catch((err) => {
                            console.log(err);
                            clearInterval(interval);
                            Swal.fire({
                                title: "Error",
                                text: 'Ha ocurrido un error inesperado. Por favor, inténtelo nuevamente.',
                                icon: "error"
                            });
                        });
                }
            });
        }
    });
}

function toggleStatus(element){
    let {record_id,estado} = element.dataset;

    let messageAlert = estado === "Activado" ? '¿Desea desactivar la empresa seleccionada?' : '¿Desea activar la empresa seleccionada?';
    //message
    Swal.fire({
        title: messageAlert,
        text: "Esta acción actualizará el estado de la empresa seleccionada.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
            axios.post(route('empresa.upd.estado'), {empresa_id: record_id})
            .then((result) => {
                if(result.data.status === "success"){
                    Swal.fire({
                        title: "Éxito",
                        text: result.data.message,
                        icon: "success"
                    });
                    $("#dt-empresas").DataTable().ajax.reload();
                }else{
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

function addSucursal(element){
    let record_id = element.dataset.record_id;
    let empresa = element.dataset.empresa;
    document.getElementById('d_modal_sucursal').textContent = `REGISTRAR SUCURSAL PARA ${empresa}`;
    document.getElementById('form-sucursal').setAttribute('empresaId',record_id);
    dataTable("dt-sucursales", route('sucursal.listar'), {empresa_id: record_id});
    resetFormSucursal();//reset form sucursal
    $("#modal-form-sucursal").modal('show');
}