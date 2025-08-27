var arrayModulos = [];
var permisosAsignadosUser = [];
function getPermisos(cuenta_id = 0, usuario_id = 0){
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
    axios.post(route('permiso.cuenta.obtener'), {cuenta_id: cuenta_id, usuario_id: usuario_id})
    .then((response) => {
        Swal.close();
        let data = response.data;
        data.forEach(({ id: modulo_id, permisos }) => {
            permisos
                .filter(p => p.asignadoPermiso)
                .forEach(({ nombre: permiso, permiso_id }) => {
                    permisosAsignadosUser.push({
                        permiso,
                        modulo_id,
                        permiso_id
                    });
                });
        });
        arrayModulos = data;
        showModulos(data);
    }).catch((err) => {
        Swal.close();
        console.log(err);
    });
}

function showModulos(data = []) {
    let content_items_modulo = document.getElementById('list-items-modulos');
    if(!content_items_modulo) return;
    content_items_modulo.innerHTML = ``;
    //clear content permisos
    let content_permisos = document.getElementById('list-items-permisos');
    if(!content_permisos) return;
    content_permisos.innerHTML = `<tr>
        <td colspan="3" style="text-align: center"><p class="m-0 p-0 text-danger">Módulo no seleccionado.</p></td>
    </tr>`;
    
    if (data.length === 0) {
        content_items_modulo.innerHTML = `<p class="m-0 p-0 text-danger text-center">No hay modulos.</p>`;
        return;
    }

    data.forEach((modulo, index) => {
        let isActivo = modulo.asignadoModulo;
        let button_module = document.createElement('button');
        button_module.style.fontSize = '12px';
        button_module.style.fontWeight = '700';
        let statusModuleIcon = 'bi-unlock';
        if(!isActivo){
            button_module.style.opacity = '0.4';
            button_module.style.cursor = 'not-allowed';
            button_module.style.position = 'relative';
            statusModuleIcon = 'bi-lock';
        }

        button_module.type = 'button';
        button_module.dataset.index = `button-${index}`;
        button_module.dataset.nombre = `button-${modulo.nombre}`;
        button_module.dataset.modulo_id = `button-${modulo.id}`;
        //styles
        button_module.classList.add('list-group-item', 'list-group-item-action');

        button_module.innerHTML = `<i id="icon-${index}" class="bi bi-check-circle me-1 text-info"></i> ${ modulo.nombre } <i class="bi ${statusModuleIcon} icon-status" style="position: absolute;right: 10px;"></i>`;
        button_module.onclick = ()=> {
            let index = data.findIndex((element) => element.id === modulo.id);
            if(index !== -1){
                let arrayPermisos = data[index].permisos;
                let nameModule = data[index].nombre;
                let module_id = data[index].id;
                showPermisos(arrayPermisos,nameModule, isActivo, module_id);
                document.getElementById('CheckAllContent').innerHTML = createCheckAllElement(module_id, isActivo);
            }
        }
        content_items_modulo.appendChild(button_module);
    });
}

function showPermisos(datos = [], nameModule = '', isActivo, modulo_id){
    let content_permisos = document.getElementById('list-items-permisos');
    if(!content_permisos) return;
    content_permisos.innerHTML = ``;
    if(datos.length === 0){
        content_permisos.innerHTML = `<tr>
            <td colspan="3" style="text-align: center"><p class="m-0 p-0 text-danger">No hay permisos asignados para el módulo seleccionado.</p></td>
        </tr>`;return;
    }
    datos.forEach((permiso, index) => {
        let rowContentTable = document.createElement('tr');
        //verificar el permiso
        let indexPermisoAsignado = permisosAsignadosUser.findIndex((item)=> item.permiso_id === permiso.permiso_id);
        let checkPermiso; //permiso.asignadoPermiso ->BD
        if(indexPermisoAsignado !== -1){
            checkPermiso = true;
        }else{
            checkPermiso = false;
        }
        let notAllowed = !isActivo
            ? `style="opacity: 0.4;cursor: not-allowed;position: relative;"`
            : ``;
        document.getElementById('display_module_selected').textContent = `${nameModule} - `;
        let item_row = `
            <td ${notAllowed}>
                <input style="cursor:pointer"
                    class="form-check-input moduloCheck${ modulo_id }"
                    type="checkbox" ${!isActivo ? 'disabled' : ''} ${checkPermiso ? 'checked'
                    : '' } id="permisoCheck${ permiso.id }"
                    onclick="checkPermiso(this)"
                    data-nombre="${permiso.nombre}"
                    data-modulo_id="${modulo_id}"
                    value="${ permiso.permiso_id }">
            </td>
            <td ${notAllowed}><label for="permisoCheck${ permiso.id }"
                    style="cursor:pointer">${permiso.nombre}</label></td>
            <td class="permiso-descripcion" ${notAllowed}>
                ${permiso.descripcion.charAt(0).toUpperCase() +
                permiso.descripcion.slice(1).toLowerCase()}
            </td>
        `;
        rowContentTable.innerHTML = item_row;
        content_permisos.appendChild(rowContentTable);
    });
}

function checkPermiso(element){
    let nombre = element.dataset.nombre;
    let modulo_id = element.dataset.modulo_id;
    let value = element.value;
    if(element.checked){
        permisosAsignadosUser.push({
            permiso: nombre,
            modulo_id: modulo_id,
            permiso_id: value
        });
    }else{
        let index = permisosAsignadosUser.findIndex((item)=> item.permiso_id === value && item.modulo_id === modulo_id);
        if(index !== -1){
            permisosAsignadosUser.splice(index, 1);
        }
    }
}

function checkAllPermisos(element){
    const { value, id: id_element, checked } = element;

    const modulo = arrayModulos.find(m => m.id === value);
    if (!modulo) return;

    const { permisos } = modulo;
    const inputs_checks_permiso = document.querySelectorAll(`.${id_element}`);

    inputs_checks_permiso.forEach(input => input.checked = checked);

    if (checked) {
        // Agregar todos los permisos de ese módulo
        permisosAsignadosUser.push(
            ...permisos.map(({ nombre: permiso, permiso_id }) => ({
                permiso,
                modulo_id: value,
                permiso_id
            }))
        );
    } else {
        permisosAsignadosUser = permisosAsignadosUser.filter(p => p.modulo_id !== value);
    }
}

function createCheckAllElement(modulo_id, isActive){
    return `
        <div class="form-check m-0" style="cursor:'pointer'">
            <label for="moduloCheck${ modulo_id }">Todos</label>
            <input class="form-check-input" ${!isActive ? 'disabled' : ''} type="checkbox"
                onclick="checkAllPermisos(this)" id="moduloCheck${ modulo_id }"
                name="modulos[]" value="${ modulo_id }" style="cursor:pointer">
        </div>
    `;
}