<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="auth-container shadow-sm p-4">
            <h2 class="text-center mb-4">Registro de Usuario</h2> 
            <form action="?action=register&method=store" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Usuario</label>
                    <input type="text" class="form-control" name="username" id="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Cedula</label>
                    <input type="text" class="form-control" name="CI" id="CI" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Correo</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                </div
                <div class="text-center">
                    <p>¿Ya tienes una cuenta? <a href="?action=login" class="text-decoration-none">Inicia sesión aquí</a></p>
                </div>
            </form>
        </div>
    </div>
</div>