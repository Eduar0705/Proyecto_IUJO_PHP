//validacion para que en cargo escaja una opcion sea administrador o usuario
document.getElementById('cargo').addEventListener('change', function() {
    var cargo = this.value;
    if (cargo !== '1' && cargo !== '2') {
        alert('Por favor, seleccione un cargo válido (1 o 2).');
        this.value = ''; // Limpiar el campo
    }
});

//validacion de que en el campo nombre solo se ingresen letras
document.getElementById('nombre').addEventListener('input', function() {
    var nombre = this.value;
    if (!/^[a-zA-Z\s]+$/.test(nombre)) {
        alert('Por favor, ingrese un nombre válido (solo letras y espacios).');
        this.value = ''; // Limpiar el campo
    }
});

//validacion de que en el campo usuario solo se ingresen letras y numeros
document.getElementById('username').addEventListener('input', function() {
    var usuario = this.value;
    if (!/^[a-zA-Z0-9]+$/.test(usuario)) {
        alert('Por favor, ingrese un nombre de usuario válido (solo letras y números).');
        this.value = ''; // Limpiar el campo
    }
});