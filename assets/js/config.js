console.log('config cargado')// Abrir modal con datos del usuario
document.querySelectorAll('.openModalEditar').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.getAttribute('data-id');
        const nombre = this.getAttribute('data-nombre');
        const usuario = this.getAttribute('data-usuario');
        const email = this.getAttribute('data-email');
        const cedula = this.getAttribute('data-cedula');
        const cargo = this.getAttribute('data-cargo');

        document.getElementById('id_usuario').value = id;
        document.getElementById('nombre').value = nombre;
        document.getElementById('usuario').value = usuario;
        document.getElementById('email').value = email;
        document.getElementById('cedula').value = cedula;
        document.getElementById('cargo').value = cargo;

        // Limpiar errores y clases
        document.querySelectorAll('input, select').forEach(el => {
            el.classList.remove('valid', 'invalid');
        });
        document.querySelectorAll('small.text-danger').forEach(el => el.textContent = '');

        document.getElementById('userModal').style.display = 'flex';
    });
});

// Cerrar modal al hacer clic fuera del contenido
document.getElementById('userModal').addEventListener('click', function (e) {
    if (e.target === this) {
        this.style.display = 'none';
    }
});

// Validaciones en tiempo real
document.getElementById('nombre').addEventListener('input', function () {
    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
    validateNombre();
});

document.getElementById('usuario').addEventListener('input', function () {
    this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ]/g, '');
    validateUsuario();
});

document.getElementById('email').addEventListener('input', validateEmail);
document.getElementById('cedula').addEventListener('input', validateCedula);
document.getElementById('cargo').addEventListener('change', validateCargo);

// Funciones de validación
function validateNombre() {
    const nombre = document.getElementById('nombre');
    const error = document.getElementById('nombreError');
    
    if (nombre.value.trim() === '') {
        nombre.classList.add('invalid');
        error.textContent = 'El nombre es obligatorio';
        return false;
    } else if (nombre.value.trim().length < 3) {
        nombre.classList.add('invalid');
        error.textContent = 'El nombre debe tener al menos 3 caracteres';
        return false;
    } else {
        nombre.classList.remove('invalid');
        nombre.classList.add('valid');
        error.textContent = '';
        return true;
    }
}

function validateUsuario() {
    const usuario = document.getElementById('usuario');
    const error = document.getElementById('usuarioError');
    
    if (usuario.value.trim() === '') {
        usuario.classList.add('invalid');
        error.textContent = 'El usuario es obligatorio';
        return false;
    } else if (usuario.value.trim().length < 4) {
        usuario.classList.add('invalid');
        error.textContent = 'El usuario debe tener al menos 4 caracteres';
        return false;
    } else {
        usuario.classList.remove('invalid');
        usuario.classList.add('valid');
        error.textContent = '';
        return true;
    }
}

function validateEmail() {
    const email = document.getElementById('email');
    const error = document.getElementById('emailError');
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email.value.trim() === '') {
        email.classList.add('invalid');
        error.textContent = 'El correo es obligatorio';
        return false;
    } else if (!re.test(email.value)) {
        email.classList.add('invalid');
        error.textContent = 'Ingrese un correo válido (ej: usuario@dominio.com)';
        return false;
    } else {
        email.classList.remove('invalid');
        email.classList.add('valid');
        error.textContent = '';
        return true;
    }
}

function validateCedula() {
    const cedula = document.getElementById('cedula');
    const error = document.getElementById('cedulaError');

    cedula.value = cedula.value.replace(/\D/g, '');

    if (cedula.value.trim() === '') {
        cedula.classList.add('invalid');
        error.textContent = 'La cédula es obligatoria';
        return false;
    } else if (cedula.value.length < 6) {
        cedula.classList.add('invalid');
        error.textContent = 'La cédula debe tener al menos 6 dígitos';
        return false;
    } else {
        cedula.classList.remove('invalid');
        cedula.classList.add('valid');
        error.textContent = '';
        return true;
    }
}

function validateCargo() {
    const cargo = document.getElementById('cargo');
    const error = document.getElementById('cargoError');

    if (cargo.value === '') {
        cargo.classList.add('invalid');
        error.textContent = 'Debe seleccionar un cargo';
        return false;
    } else {
        cargo.classList.remove('invalid');
        cargo.classList.add('valid');
        error.textContent = '';
        return true;
    }
}

// Búsqueda en la tabla
document.getElementById('searchInput').addEventListener('input', function(e) {
    console.log("Función de búsqueda cargada");
    let searchValue = e.target.value.replace(/[0-9]/g, '');
    console.log("Input cambiado a:", searchValue);

    // Actualizar el valor del input sin números
    if (e.target.value !== searchValue) {
        e.target.value = searchValue;
    }
    
    searchValue = searchValue.toLowerCase(); // Convertir a minúsculas todos los caracteres
    
    const rows = document.querySelectorAll('#tuTabla tbody tr'); //Obtiene todas las filas de la tabla
    console.log("Input ahora es:", searchValue);
    rows.forEach(row => {
        // Obtener el texto de las celdas de nombre y usuario
        const nombre = row.cells[1].textContent.toLowerCase();
        const usuario = row.cells[2].textContent.toLowerCase();
        
        // Mostrar u ocultar la fila según coincida con la búsqueda
        if (nombre.includes(searchValue) || usuario.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
