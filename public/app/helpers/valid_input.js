
document.addEventListener('DOMContentLoaded', () => {
    let inputs_number = document.querySelectorAll('.input-number');
    inputs_number.forEach((input) => {
        input.addEventListener('keyup', (e) => {
            e.target.value = e.target.value.replace(/[^0-9\-]/g, '');
        });
    });
    //validacion para 4 caracteres maximos
    let inputs_valid_max = document.querySelectorAll('.max-4');
    inputs_valid_max.forEach((input) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length > 4) {
                e.target.value = e.target.value.slice(0, 4);
            }
        });
    });
});