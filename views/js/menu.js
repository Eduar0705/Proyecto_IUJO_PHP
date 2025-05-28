document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar-container');
    const mainContent = document.querySelector('.main-content');

    if (!menuToggle || !sidebar || !mainContent) return;

    // Toggle sidebar
    menuToggle.addEventListener('click', function () {
        sidebar.classList.toggle('active');
        mainContent.classList.toggle('sidebar-active');
    });

    // Responsive adjustments
    function handleResize() {
        if (window.innerWidth <= 992) {
            sidebar.classList.remove('active');
            mainContent.classList.remove('sidebar-active');
        } else {
            sidebar.classList.add('active');
            mainContent.classList.add('sidebar-active');
        }
    }

    // Initial check
    handleResize();
    window.addEventListener('resize', handleResize);
});