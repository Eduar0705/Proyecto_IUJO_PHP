<div class="row justify-content-center" style="margin-top: 150px;">
    <div class="col-md-6 col-lg-5">
        <div class="auth-container shadow-sm p-4">
            <h2 class="text-center mb-4">Iniciar Sesión</h2>
            
            <form action="?action=login&method=authenticate" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="user" name="user" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" name="init" id="init" class="btn btn-primary">Ingresar</button>
                </div>
                <div class="text-center">
                    <a href="?action=forgot-password" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
                    <hr>
                    <p>¿No tienes una cuenta? <a href="?action=register" class="text-decoration-none">Regístrate aquí</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
<br><br><br><br><br><br><br>