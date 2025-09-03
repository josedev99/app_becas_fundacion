const btnNewBecado = document.querySelector('#btn-new-becado');

document.addEventListener('DOMContentLoaded', (e)=> {
    $("#beca_id").selectize();
    if(btnNewBecado){
        btnNewBecado.addEventListener('click', (e)=>{
            e.preventDefault();
            //Abrir modal
            $("#modal-form-becado").modal('show');
            getBecas();
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