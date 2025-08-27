/* 
Autor:@josedev99
::version::1.0.0
::mod:
*/

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