const formValDinamico = document.getElementById('form-val-dinamico');

document.addEventListener('DOMContentLoaded', () => {
    getValoresDinamicos();
    if(formValDinamico){
        formValDinamico.addEventListener('submit', (e) => {
            e.preventDefault();
            let formData = new FormData(e.target);
            let modulo = document.getElementById('form-val-dinamico').getAttribute('data-modulo');
            let identificador = document.getElementById('form-val-dinamico').getAttribute('data-identificador');
            if(modulo && identificador){
                formData.append('modulo', modulo);
                formData.append('identificador', identificador);
            }
            axios.post(route('val.form.save'), formData)
            .then((response) => {
                let data = response.data;
                if(data.status === "success"){
                    Swal.fire({
                        title: "Creado",
                        text: data.message,
                        icon: "success"
                    });
                    setValSelectize(data.result.nombre,data.result.identicador,true);
                    document.getElementById('form-val-dinamico').removeAttribute('data-modulo');
                    document.getElementById('form-val-dinamico').removeAttribute('data-identificador');
                    $("#modal-form-valores").modal('hide');
                    formValDinamico.reset();
                }else{
                    Swal.fire({
                        title: "Error",
                        text: data.message,
                        icon: "error"
                    });
                }
                console.log(response);
            }).catch((err) => {
                console.log(err);
            });
        })
    }
})

function getValoresDinamicos(){
    axios.post(route('val.form.get'))
    .then((response) => {
        console.log(response)
        let data = response.data;
        data.forEach(item => {
            setValSelectize(item.nombre, item.identicador);
        });
    }).catch((err) => {
        console.log(err);
    });
}

function setValSelectize(valores, identicador, selected = false){
    let seletizeElement = $(`#${identicador}`)[0].selectize;
    seletizeElement.addOption({
        value: valores,
        text: valores
    });
    if(selected){    
       seletizeElement.setValue(valores);
    }
}