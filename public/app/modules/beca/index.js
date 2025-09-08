const btnNewBeca = document.getElementById('btn-new-beca');

document.addEventListener('DOMContentLoaded', ()=>{
    if(btnNewBeca){
        btnNewBeca.addEventListener('click', ()=>{
            $("#modal-form-beca").modal('show');
        })
    }
})