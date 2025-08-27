var file_cert = '';

document.addEventListener('DOMContentLoaded', () => {
    let icon_file_cert = document.getElementById('icon-file-cert');
    const input_file_cert = document.getElementById('input_file_cert');
    if(icon_file_cert && input_file_cert){
        icon_file_cert.addEventListener('click', () => {
            input_file_cert.click();
        })
    }
    //event
    if(input_file_cert){
        input_file_cert.addEventListener('change', (event)=> {
            let array_files = event.target.files;
            if(array_files.length > 0){
                file_cert = array_files[0];
                let new_name_file_cert = file_cert.name.split('_');
                if(new_name_file_cert.length > 1){
                    new_name_file_cert = new_name_file_cert[1];
                }else{
                    new_name_file_cert = file_cert.name;
                }
                file_cert.name = new_name_file_cert;
                document.getElementById('display_name_cert').innerHTML = `<b>${new_name_file_cert}</b> <i class="bi bi-check2-circle" style="font-size: 16px;"></i>`;
            }
        })
    }
    //display_name_cert
})