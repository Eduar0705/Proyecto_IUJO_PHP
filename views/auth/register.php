<br><br>
<div class="row justify-content-center">
    <br>
    <div class="col-md-6 col-lg-5">
        <br><br><br><br>
        <div class="auth-container shadow-sm p-4">
            <h2 class="text-center mb-4">Registro de Usuario</h2> 
            <form action="?action=inicio&method=registerStore" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Usuario</label>
                    <input type="text" class="form-control" name="username" id="username" required>
                </div>
                <div class="mb-3">
                    <label for="cedula" class="form-label">Cedula</label>
                    <input type="text" class="form-control" name="CI" id="CI" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" name="password2" id="password2" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                <br>
                </div
                <div class="text-center">
                    <p>¿Ya tienes una cuenta? <a href="?action=inicio&method=login" class="text-decoration-none">Inicia sesión aquí</a></p>
                </div>
            </form>
        </div>
    </div>
    <br>
</div>