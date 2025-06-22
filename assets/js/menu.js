document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar-container');
    const mainContent = document.querySelector('.main-content');
    const menuItems = document.querySelectorAll('.sidebar-menu a');

    // 1. Función para activar el ítem correcto
    function activateMenuItem() {
        const currentUrl = new URL(window.location.href);
        
        menuItems.forEach(item => {
            if (item.href === '#') return;
            
            const itemUrl = new URL(item.href, window.location.origin);
        
            if (currentUrl.pathname === itemUrl.pathname) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }

    // 2. Manejar clicks en el menú
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            if (this.href === '#') return;
            
            if (!this.href.includes('#')) {
                e.preventDefault();
                
                menuItems.forEach(i => i.classList.remove('active'));
                
                this.classList.add('active');
                
                setTimeout(() => {
                    window.location.href = this.href;
                }, 300);
            }
        });
    });

    // 3. Inicialización
    activateMenuItem();
    
    // Responsive
    function handleResize() {
        if (window.innerWidth <= 992) {
            sidebar.classList.remove('active');
            mainContent.classList.remove('sidebar-active');
        } else {
            sidebar.classList.add('active');
            mainContent.classList.add('sidebar-active');
        }
    }
    
    handleResize();
    window.addEventListener('resize', handleResize);
});