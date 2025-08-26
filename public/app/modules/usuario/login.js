document.addEventListener('DOMContentLoaded', (e)=> {
    let icon_passwd = document.getElementById('icon-show-password');
    icon_passwd.addEventListener('click', showPassword);
})
function showPassword(e){
    let input_clave = document.getElementById('clave_user');
    let icon_passwd = document.getElementById('icon-show-password');
    if(input_clave.type === "password"){
        input_clave.type = 'text';
        icon_passwd.innerHTML = `<i class="bi bi-shield-slash-fill"></i>`;
    }else{
        icon_passwd.innerHTML = `<i class="bi bi-shield-lock"></i>`;
        input_clave.type = 'password';
    }
}

/* 
Autor:@josedev99
::version::1.0.0
::mod:
*/
document.addEventListener('DOMContentLoaded', initApp);

function initApp(){
    $("#empresa_id").selectize();
    const userInput = document.getElementById('usuario');
    if(userInput){
        userInput.addEventListener('change', (e) => {
            getEmpresaByAccount(e.target.value);
        })
    }
}

function getEmpresaByAccount(user) {
    let formData = new FormData();
    formData.append('user', user);
    let sel_empresa = $("#empresa_id")[0].selectize;
    // Mostrar spinner y bloquear
    sel_empresa.disable();
    sel_empresa.$control.addClass('loading-spinner');
    sel_empresa.clear();
    sel_empresa.clearOptions();

    axios.post('/empresas/obtener', formData)
        .then((response) => {
            let data = response.data;

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(item => {
                    sel_empresa.addOption({
                        value: item.id,
                        text: item.nombre
                    });
                });
            }
        })
        .catch((err) => {
            console.error(err);
        })
        .finally(() => {
            // Ocultar spinner y habilitar
            sel_empresa.$control.removeClass('loading-spinner');
            sel_empresa.enable();
        });
}