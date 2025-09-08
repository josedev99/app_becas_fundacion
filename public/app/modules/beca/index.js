const btnNewBeca = document.getElementById('btn-new-beca');
const formBeca = document.getElementById('form-beca');

document.addEventListener('DOMContentLoaded', ()=>{
    $("#tipo_beca").selectize();
    $("#financiamiento").selectize();
    $("#plazo_monto").selectize();
    $("#forma_entrega").selectize();
    $("#compromiso").selectize();
    if(btnNewBeca){
        btnNewBeca.addEventListener('click', ()=>{
            $("#modal-form-beca").modal('show');
        })
    }
    //
    if(formBeca){
        formBeca.addEventListener('submit', (e) => {
            e.preventDefault();
            let formData = new FormData(e.target);
            axios.post(route('becas.save'), formData)
            .then((response) => {
                console.log(response);
            }).catch((err) => {
                console.log(err);
            });
        })
    }
})