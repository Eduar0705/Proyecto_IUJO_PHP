    </main>
    <footer class="py-4 mt-5" style="background-color: #ff4d4d; color:white" >
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold"><?= APP_NAME ?></h5>
                    <p class="mb-0"><?= APP_DESC ?></p> <br>
                    <h5 class="fw-bold">Redes</h5>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/profile.php?id=100064560497841" class="text-white me-2"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-2"><i class=""></i></a>
                        <a href="#" class="text-white me-2"><i class=""></i></a>
                        <a href="https://www.instagram.com/invilaraoficial/" class="text-white"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold">Contacto</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-geo-alt-fill me-2"></i> Av. Fuezas Armadas con 57, Barquisimeto</li>
                        <li class="mb-2"><i class="bi bi-telephone-fill me-2"></i> (0251) 442-4548 </li>
                        <li><i class="bi bi-envelope-fill me-2"></i> info@invilaraoficial@gmail.com</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold">Enlaces rápidos</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="?action=inicio" class="text-white text-decoration-none">Inicio</a></li>
                        <li class="mb-2"><a href="?action=projects" class="text-white text-decoration-none">Proyectos</a></li>
                        <li class="mb-2"><a href="?action=inicio&method=about" class="text-white text-decoration-none">Nosotros</a></li>
                        <li><a href="?action=inicio&method=contact" class="text-white text-decoration-none">Contacto</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> <?= APP_NAME ?>. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>