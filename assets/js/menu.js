document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar-container');
    const mainContent = document.querySelector('.main-content');
    const menuItems = document.querySelectorAll('.sidebar-menu a');

    // 1. Función para activar el ítem correcto basado en la URL actual
    function activateMenuItem() {
        const currentPath = window.location.pathname + window.location.search;
        
        menuItems.forEach(item => {
            // Extraer path + query params del enlace
            const itemUrl = new URL(item.href);
            const itemPath = itemUrl.pathname + itemUrl.search;
            
            // Comparar paths (sin hash)
            if (currentPath === itemPath) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }

    // 2. Manejar clicks en el menú (solo para móvil)
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            if (window.innerWidth <= 992) {
                // Cerrar el menú en móvil después de hacer clic
                sidebar.classList.remove('active');
                mainContent.classList.remove('sidebar-active');
            }
        });
    });

    // 3. Manejar el botón de toggle del menú
    if (menuToggle) {
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('sidebar-active');
        });
    }

    // 4. Cerrar menú al hacer clic fuera (solo móvil)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 992) {
            if (!sidebar.contains(e.target) && 
                !menuToggle.contains(e.target) && 
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                mainContent.classList.remove('sidebar-active');
            }
        }
    });

    // 5. Inicialización
    activateMenuItem();
    
    // 6. Manejo responsive
    function handleResize() {
        if (window.innerWidth <= 992) {
            // Versión móvil: menú oculto por defecto
            sidebar.classList.remove('active');
            mainContent.classList.remove('sidebar-active');
        } else {
            // Versión desktop: menú siempre visible
            sidebar.classList.add('active');
            mainContent.classList.add('sidebar-active');
        }
    }
    
    // Ejecutar al cargar y al cambiar tamaño
    handleResize();
    window.addEventListener('resize', handleResize);
});