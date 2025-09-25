// Helper functions for dashboard
const tag_counter_becados = document.getElementById('counter-becados');
const tag_promedio_gen = document.getElementById('promedio_gen');
const tag_porcentaje_graduados = document.getElementById('porcentaje-graduados');
const tag_total_becados = document.getElementById('total-becados');

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

function setBecadosPorNivel(nivel, cantidad){
    const tag_nivel = document.getElementById(`nivel-${nivel}`);
    if(tag_nivel){
        tag_nivel.innerText = cantidad;
    }
}

function getGraduados(cantidadBecados, porcentaje=0){
    if(tag_porcentaje_graduados && tag_total_becados){
        tag_porcentaje_graduados.innerText = `${porcentaje}%`;
        tag_total_becados.innerText = cantidadBecados;
    }
}

function getDataDashboard(){
    axios.post(route('dashboard.datos'))
    .then((response) => {
        console.log(response);
        let data = response.data;
        getCounterBecados(data.total_becados);
        getPromedioGeneral(parseFloat(data.promedio_general).toFixed(2));
        data.total_nivel_educativo.forEach(item => {
            setBecadosPorNivel(item.nivel, item.count);
        });
        getGraduados(data.graduados.total_becados, data.graduados.porcentaje);
    }).catch((err) => {
        console.error(err);
    });
}