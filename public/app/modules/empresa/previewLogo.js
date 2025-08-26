document.addEventListener('DOMContentLoaded', () => {
    let btnUploadLogo = document.getElementById('btnUploadLogo');
    let logo_file = document.getElementById('logo_file');

    if(btnUploadLogo){
        btnUploadLogo.addEventListener('click', ()=>{
            logo_file.click();
        });
    }

    if(logo_file){
        logo_file.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                updatePreview(file);
            } else {
                resetPreview();
            }
        })
    }
})

function updatePreview(file) {
    const reader = new FileReader();
    reader.onload = () => {
        document.getElementById('preview_logo').innerHTML = `<img src="${reader.result}" alt="Vista previa de logo" style="max-width: 140px;">`;
    };
    reader.readAsDataURL(file);
}

function showLogo(url_logo){
    if(url_logo !== ""){
        document.getElementById('preview_logo').innerHTML = `<img src="${url_logo}" alt="Vista previa de logo" style="max-width: 140px;">`;
    }else{
        resetPreview();
    }
}

function resetPreview() {
    document.getElementById('preview_logo').innerHTML = `<p class="mb-1">No se ha cargado un logo</p><i class="bi bi-card-image"></i>`;
}