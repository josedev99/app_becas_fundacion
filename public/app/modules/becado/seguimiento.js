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
    modalContentDetalle.innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    `;
    axios.post(route('seguimiento.detalle'), {record_id})
    .then((response) => {
        console.log(response);
        let data = response.data;
        let seguimiento = data.seguimiento;
        document.getElementById('fecha_seg').textContent = seguimiento.fecha_reporte;
        modalContentDetalle.innerHTML = `
            <div class="row g-4">
                <!-- Información Básica -->
                <div class="col-md-6">
                    <h6 class="text-secondary mb-3 d-flex align-items-center">
                        <i class="bi bi-person-vcard me-2 text-primary"></i> Información Básica
                    </h6>
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body">
                            <p class="fw-bold text-dark mb-1">Becado</p>
                            <p class="mb-3"><i class="bi bi-person-circle me-1"></i>${data.nombre_completo} - ${data.carrera_grado}</p>
                            <div class="d-flex flex-wrap">
                                <div class="me-4">
                                    <p class="fw-bold text-dark mb-1">Fecha de Registro</p>
                                    <span class="badge bg-light text-dark shadow-sm">
                                        <i class="bi bi-calendar-event me-1"></i>${data.created_at}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estado y Seguimiento -->
                <div class="col-md-6">
                    <h6 class="text-secondary mb-3 d-flex align-items-center">
                        <i class="bi bi-clipboard-check me-2 text-success"></i> Estado y Seguimiento
                    </h6>
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body">
                            <p class="fw-bold mb-1">Responsable</p>
                            <p class="mb-3"><i class="bi bi-person-badge me-1"></i>${seguimiento.responsable_seguimiento}</p>
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <p class="fw-bold mb-1">Estado</p>
                                    <span class="badge bg-${seguimiento.estado_beca === 'Activo' ? 'success' : 'danger'} px-3 py-2 shadow-sm">
                                        <i class="bi bi-flag me-1"></i>${seguimiento.estado_beca}
                                    </span>
                                </div>
                                <div class="col-6 mb-2">
                                    <p class="fw-bold mb-1">Prioridad</p>
                                    <span class="badge bg-${seguimiento.proridad === 'Alta' ? 'warning text-dark' : 'secondary'} px-3 py-2 shadow-sm">
                                        <i class="bi bi-exclamation-triangle me-1"></i>${seguimiento.proridad}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participación en Actividades -->
            <div class="mt-4">
                <h6 class="text-secondary mb-3 d-flex align-items-center">
                    <i class="bi bi-people-fill me-2 text-info"></i> Participación en Actividades
                </h6>
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <p class="mb-0">${seguimiento.participacion_actividades || '<span class="text-muted">Sin información</span>'}</p>
                    </div>
                </div>
            </div>

            <!-- Observaciones -->
            <div class="mt-4">
                <h6 class="text-secondary mb-3 d-flex align-items-center">
                    <i class="bi bi-chat-dots me-2 text-primary"></i> Observaciones del Tutor
                </h6>
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <p class="mb-0">${seguimiento.observaciones_tutor || '<span class="text-muted">Sin observaciones</span>'}</p>
                    </div>
                </div>
            </div>

            <!-- Notas Adicionales -->
            <div class="mt-4">
                <h6 class="text-secondary mb-3 d-flex align-items-center">
                    <i class="bi bi-journal-plus me-2 text-success"></i> Notas Adicionales
                </h6>
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <p class="mb-0">${seguimiento.nota_adicional || '<span class="text-muted">No hay notas adicionales</span>'}</p>
                    </div>
                </div>
            </div>

            <!-- Próximo seguimiento -->
            <div class="alert alert-info mt-4 d-flex align-items-center shadow-sm rounded-3" role="alert">
                <i class="bi bi-calendar-check me-2 fs-5"></i>
                <div>
                    <strong>Próximo seguimiento:</strong> ${seguimiento.fecha_proximo || 'No programado'}
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