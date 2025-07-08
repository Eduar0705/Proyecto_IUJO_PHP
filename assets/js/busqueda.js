// Búsqueda en la tabla

document.addEventListener('DOMContentLoaded', function() {
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
});