// Helper functions for dashboard
const tag_counter_becados = document.getElementById('counter-becados');
const tag_promedio_gen = document.getElementById('promedio_gen');
const tag_porcentaje_graduados = document.getElementById('porcentaje-graduados');
const tag_total_becados = document.getElementById('total-becados');
//Seguimientos dashboard
const tag_lista_seguimientos = document.getElementById('lista-seguimientos');

//Load data when the document is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    getDataDashboard();
    getSeguimientosBecados();
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
        tag_porcentaje_graduados.innerText = `${parseFloat(porcentaje).toFixed(1)}%`;
        tag_total_becados.innerText = cantidadBecados;
    }
}

function inversionAnualDashboard(monto){
    const tag_inversion_anual = document.getElementById('inversion_becas');
    if(tag_inversion_anual){
        tag_inversion_anual.innerText = `$ ${monto}`;
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
        inversionAnualDashboard(data.inversion_anual);
    }).catch((err) => {
        console.error(err);
    });
}

//Seguimientos de becados
function getSeguimientosBecados(){
    tag_lista_seguimientos.innerHTML = `
        <div class="text-center p-3">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    axios.get(route('dashboard.seguimientos'))
    .then((response) => {
        console.log(response);
        let data = response.data;
        if(data.length > 0){
            let htmlContent = '';
            data.forEach(item => {
                htmlContent += `
                    <div class="timeline-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${item.nombre_completo} - ${item.carrera_grado}</h6>
                                <p class="mb-1 text-muted">Seguimiento académico</p>
                                <small class="text-primary"><i class="bi bi-clock"></i> ${item.fecha_proximo}</small>
                            </div>
                            <span class="priority-badge priority-media">${item.proridad}</span>
                        </div>
                    </div>
                `;
            });
            tag_lista_seguimientos.innerHTML = htmlContent;
        }else{
            tag_lista_seguimientos.innerHTML = `
                <div class="text-center p-3">
                    <p class="text-muted">No hay seguimientos próximos.</p>
                </div>
            `;
        }
    }).catch((err) => {
        console.error(err);
    });
}