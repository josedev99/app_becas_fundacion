var arrayModulos = [];
var modulosAsignadosCuenta = [];
function getModulosPermisos(cuenta_id = 0, usuario_id = 0){
    Swal.fire({
        title: 'Obteniendo datos...',
        text: 'Por favor, espera mientras completamos la operación.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    axios.post(route('modulo.permiso.obtener'), {cuenta_id: cuenta_id, usuario_id: usuario_id})
    .then((response) => {
        Swal.close();
        let data = response.data;
        data.forEach((item) => {
            if(item.asignadoModulo){
                modulosAsignadosCuenta.push({
                    modulo_id: item.id,
                    nombre: item.nombre
                })
            }
        })
        arrayModulos = data;
        showModulos(data);
    }).catch((err) => {
        Swal.close();
        console.log(err);
    });
}

function showModulos(datos = []){
    let content_modulos = document.getElementById('content_modulos');
    if(!content_modulos) return;
    content_modulos.innerHTML = ``;

    datos.forEach((modulo, index)=>{
        let divColElement = document.createElement('div');
        divColElement.classList.add('col-md-4');
        let accordionItemElement = `
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading${ index }">
                    <button class="accordion-button collapsed py-2" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapse${ index }"
                        aria-expanded="false" aria-controls="collapse${ index }">
                        <div class="form-check m-0">
                            <input class="form-check-input" ${modulo.asignadoModulo ? 'checked' : ''} type="checkbox"
                                id="moduloCheck${ modulo.id }" onclick="checkModulo(this)" data-nombre="${modulo.nombre}" name="modulos[]"
                                value="${ modulo.id }">
                            <label class="form-check-label"
                                for="moduloCheck${ modulo.id }">
                                ${ modulo.nombre }
                            </label>
                        </div>
                    </button>
                </h2>
                <div id="collapse${ index }" class="accordion-collapse collapse"
                    aria-labelledby="heading${ index }"
                    data-bs-parent="#accordionPermisos">
                    <div class="accordion-body p-2">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Permiso</th>
                                    <th scope="col">Descripción</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 11px;">
                                ${modulo.permisos.map((permiso, index) => {
                                    return `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${permiso.nombre}</td>
                                            <td class="permiso-descripcion">
                                                ${permiso.descripcion.charAt(0).toUpperCase() + permiso.descripcion.slice(1).toLowerCase()}
                                            </td>
                                        </tr>
                                    `;
                                }).join('')
                                }
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
        divColElement.innerHTML = accordionItemElement;
        content_modulos.appendChild(divColElement);
    })
}

function checkModulo(element){
    let modulo_id = element.value;
    let nombre = element.dataset.nombre;
    if(element.checked){
        modulosAsignadosCuenta.push({
            modulo_id: modulo_id,
            nombre: nombre
        });
    }else{
        let index = modulosAsignadosCuenta.findIndex((item)=> item.modulo_id === modulo_id);
        if(index !== -1){
            modulosAsignadosCuenta.splice(index, 1);
        }
    }
}