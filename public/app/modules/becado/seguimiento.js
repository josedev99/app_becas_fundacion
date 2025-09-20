const btnNewSeguimiento = document.querySelector('#btn-new-seguimiento');
const formSeguimiento = document.querySelector('#form-seguimiento');

//detalle
const modalContentDetalle = document.getElementById('modalContentDetalle');

document.addEventListener('DOMContentLoaded', (e) => {
    dataTable('dt-seguimiento', route('seguimiento.listar'));
    $("#becado_seguimiento").selectize();
    $("#estado_beca").selectize();
    $("#prioridad_segui").selectize();

    if (btnNewSeguimiento) {
        btnNewSeguimiento.addEventListener('click', (e) => {
            e.preventDefault();
            getBecadosAll();
            resetForm();
            //Abrir modal
            $("#modal-form-seguimiento").modal('show');
        })
    }

    if (formSeguimiento) {
        formSeguimiento.addEventListener('submit', (e) => {
            e.preventDefault();
            let formData = new FormData(e.target);
            Swal.fire({
                title: 'Solicitud en proceso...',
                html: `
                    <div class="d-flex flex-column align-items-center">
                        <div class="spinner-border text-success mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">procesando...</span>
                        </div>
                        <span>Por favor espere</span>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
            });
            axios.post(route('seguimiento.save'), formData)
                .then((response) => {
                    Swal.close();
                    let { status, message } = response.data;
                    if (status === "success") {
                        Swal.fire({
                            title: "Creado",
                            text: message,
                            icon: "success"
                        });
                        resetForm();
                        $("#modal-form-seguimiento").modal('hide');
                        $("#dt-seguimiento").DataTable().ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            title: "Atención",
                            text: message,
                            icon: "error"
                        });
                    }
                    console.log(response);
                }).catch((err) => {
                    Swal.close();
                    console.log(err);
                    let errors = err.response?.data?.errors;
                    if (errors) {
                        for (let [key, arrayMessages] of Object.entries(errors)) {
                            let messageError = arrayMessages[0];
                            Swal.fire({
                                title: "Error",
                                text: messageError,
                                icon: "error"
                            }); return;
                        }
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: 'Ha ocurrido un error inesperado. Por favor, inténtelo nuevamente.',
                            icon: "error"
                        });
                    }
                });
        })
    }
});

function resetForm() {
    formSeguimiento.reset();
    $("#becado_seguimiento")[0].selectize.clear();
    $("#estado_beca")[0].selectize.clear();
    $("#prioridad_segui")[0].selectize.clear();
}

function getBecadosAll() {
    axios.post(route('becado.getAll'))
        .then((response) => {
            let data = response.data;
            let selectizeEstudiante = $("#becado_seguimiento")[0].selectize;
            selectizeEstudiante.clear();
            selectizeEstudiante.clearOptions();
            data.forEach(becado => {
                selectizeEstudiante.addOption({
                    value: becado.id,
                    text: `[${becado.documento}] - ${becado.nombre_completo}`
                });
            });
            console.log(response)
        }).catch((err) => {
            console.log(err);
        });
}

function showDetails(tag_element){
    let record_id = tag_element.dataset.record_id;
    modalContentDetalle.innerHTML = ``;
    axios.post(route('seguimiento.detalle'), {record_id})
    .then((response) => {
        console.log(response);
        let data = response.data;
        let seguimiento = data.seguimiento;
        document.getElementById('fecha_seg').textContent = seguimiento.fecha_reporte;
        modalContentDetalle.innerHTML = `
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">INFORMACIÓN BÁSICA</h6>
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Becado</label>
                                <div>${data.nombre_completo} - ${data.carrera_grado}</div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label fw-bold">Fecha</label>
                                    <div>${data.created_at}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">ESTADO Y SEGUIMIENTO</h6>
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Responsable</label>
                                <div>${seguimiento.responsable_seguimiento}</div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label fw-bold">Estado</label>
                                    <div>
                                        <span class="status-badge status-${seguimiento.estado_beca}">
                                            ${seguimiento.estado_beca}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold">Prioridad</label>
                                    <div>
                                        <span class="priority-badge priority-${seguimiento.proridad}">
                                            ${seguimiento.proridad}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="text-muted mb-2">PARTICIPACIÓN EN ACTIVIDADES</h6>
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <p class="mb-2">${seguimiento.participacion_actividades}</p>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="text-muted mb-2">OBSERVACIONES DEL TUTOR</h6>
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <p class="mb-0">${seguimiento.observaciones_tutor}</p>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="text-muted mb-2">NOTAS ADICIONALES</h6>
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <p class="mb-0">${seguimiento.nota_adicional}</p>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="bi bi-calendar-check me-2"></i>
                <div>
                    <strong>Próximo seguimiento:</strong> ${seguimiento.fecha_proximo}
                </div>
            </div>
        `;
        $("#modal-seg-detalle").modal('show');
    }).catch((err) => {
        console.log(err);
    });
}
// Exportar datos
function exportarDatos() {
    const csvContent = "data:text/csv;charset=utf-8," +
        "Becado,Carrera,Fecha,Estado,Prioridad,Responsable,Rating\n" +
        seguimientos.map(seg =>
            `"${seg.becado}","${seg.carrera}","${seg.fecha}","${seg.estado}","${seg.prioridad}","${seg.responsable}","${seg.rating}"`
        ).join('\n');

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `seguimientos_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    mostrarToast('Archivo CSV descargado', 'success');
}

// Atajos de teclado
document.addEventListener('keydown', function (e) {
    if (e.ctrlKey || e.metaKey) {
        switch (e.key) {
            case 'n':
                e.preventDefault();
                document.querySelector('[data-bs-target="#nuevoSeguimientoModal"]').click();
                break;
            case 's':
                e.preventDefault();
                const form = document.getElementById('seguimientoForm');
                if (form && !document.getElementById('nuevoSeguimientoModal').classList.contains('show') === false) {
                    form.dispatchEvent(new Event('submit'));
                }
                break;
        }
    }
});