// Helper functions for dashboard
const tag_counter_becados = document.getElementById('counter-becados');
const tag_promedio_gen = document.getElementById('promedio_gen');

//Load data when the document is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    getDataDashboard();
});


//function
function getCounterBecados(cantidad){
    tag_counter_becados.innerText = cantidad;
}

function getPromedioGeneral(promedio_gen){
    tag_promedio_gen.innerText = promedio_gen;
}

function getDataDashboard(){
    axios.post(route('dashboard.datos'))
    .then((response) => {
        let data = response.data;
        getCounterBecados(data.total_becados);
        getPromedioGeneral(parseFloat(data.promedio_general).toFixed(2));
        console.log(response)
    }).catch((err) => {
        console.error(err);
    });
}