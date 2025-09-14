const btnNewSeguimiento = document.querySelector('#btn-new-seguimiento');
const formSeguimiento = document.querySelector('#form-seguimiento');

document.addEventListener('DOMContentLoaded', (e)=> {
    dataTable('dt-becados', route('becado.listar'));
    $("#becado_seguimiento").selectize();
    $("#estado_beca").selectize();
    $("#prioridad_segui").selectize();

    if(btnNewSeguimiento){
        btnNewSeguimiento.addEventListener('click', (e)=>{
            e.preventDefault();
            formSeguimiento.reset();
            //Abrir modal
            $("#modal-form-seguimiento").modal('show');
        })
    }
});