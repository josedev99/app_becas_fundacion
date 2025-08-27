const btnNewBecado = document.querySelector('#btn-new-becado');

document.addEventListener('DOMContentLoaded', (e)=> {
    if(btnNewBecado){
        btnNewBecado.addEventListener('click', (e)=>{
            e.preventDefault();
            //Abrir modal
            $("#modal-form-becado").modal('show');
        })
    }    
})