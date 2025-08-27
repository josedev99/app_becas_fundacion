document.addEventListener('DOMContentLoaded', ()=> {
    $("#tipo_email").selectize();
    $("#cifrado").selectize();
    //onSubmitFormEmail();
    let formEmailConfig = document.getElementById('form-email-config');
    if(formEmailConfig){
        formEmailConfig.addEventListener('submit', onSubmitFormEmail);
    }
})
function configEmail(element){
    let record_id = element.dataset.record_id;
    document.getElementById('form-email-config').setAttribute('empresa_id', record_id);
    $("#modal-form-email").modal('show');
    axios.post(route('empresa.email.get'), {empresa_id: record_id})
    .then((result) => {
        let {status, data} = result.data;
        if(status === "success"){
            let {email, clave, dominio, port, cifrado, tipo_email, estado} = data;
            document.getElementById('email_config').value = email;
            document.getElementById('clave_email').value = clave;
            document.getElementById('dominio_host').value = dominio;
            document.getElementById('port').value = port;
            $("#cifrado")[0].selectize.setValue(cifrado);
            $("#tipo_email")[0].selectize.setValue(tipo_email);
            //estado email
            if(estado === "Activo"){
                document.getElementById('email_activo').checked = true;
            }else if(estado === "Desactivado"){
                document.getElementById('email_desact').checked = true;
            }
        }else{
            document.getElementById('form-email-config').reset();
            $("#cifrado")[0].selectize.setValue('');
            $("#tipo_email")[0].selectize.setValue('');
        }
    }).catch((err) => {
        console.log(err);
        Swal.fire({
            title: "Error",
            text: 'Ha ocurrido un error inesperado. Por favor, inténtelo nuevamente.',
            icon: "error"
        });
    });
}

function onSubmitFormEmail(e){
    e.preventDefault();
    let empresa_id = document.getElementById('form-email-config').getAttribute('empresa_id');
    let formData = new FormData(e.target);

    if(empresa_id){
        formData.append('empresa_id', empresa_id);
    }

    let btnSaveEmail = document.getElementById('btnSaveEmail');
    btnSaveEmail.setAttribute('disabled', 'disabled');
    btnSaveEmail.innerHTML = `<span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span> procesando...`;

    axios.post(route('empresa.email.config'), formData)
    .then((result) => {
        let {status,message} = result.data;
        if(status === "success"){
            Swal.fire({
                title: "Éxito",
                text: message,
                icon: "success"
            });
        }else{
            Swal.fire({
                title: "Error",
                text: message,
                icon: "error"
            });
        }
        $("#modal-form-email").modal('hide');
        btnSaveEmail.removeAttribute('disabled');
        btnSaveEmail.innerHTML = `<i class="bi bi-floppy-fill"></i> Guardar`;
    }).catch((err) => {
        console.log(err);
        btnSaveEmail.removeAttribute('disabled');
        btnSaveEmail.innerHTML = `<i class="bi bi-floppy-fill"></i> Guardar`;
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