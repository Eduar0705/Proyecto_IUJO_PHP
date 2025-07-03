<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $title ?? '' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/config.css">
    <link rel="icon" href="assets/img/Logo1.png" type="image/x-icon">
</head>
<body>
    <header class="admin-header">
        <?php include 'views/layout/header_Admin.php'; ?>
    </header>
    
    <div class="sidebar-container">
        <?php include 'views/layout/menuAdmin.php'; ?>
    </div>
    <main class="main-content">
        <div class="container">
            <h1 class="mt-4">Configuración</h1>
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Lista de Usuarios</h5>
                        <select name="cantidad" id="cantidad" onchange="filtrarRegistros()" style="width: 100px;">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <button id="btnAgregar" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Agregar configuracion</button>
                        <input type="text" class="form-control w-25" placeholder="Buscar por nombre o usuario" id="searchInput">
                    </div>
                    <div class="table-resposive">
                        <table class="table table-bordered" id="tuTabla">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Usuario</th>
                                    <th>Correo Electrónico</th>
                                    <th>Cedula</th>
                                    <th>Cargo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($fila = $res->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($fila['id'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($fila['nombre'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($fila['usuario'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($fila['email'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($fila['cedula'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars(($fila['id_cargo'] ?? '') == 1 ? 'Administrador' : 'Usuario'); ?></td>
                                        <td>
                                            <button 
                                                class="btn btn-primary btn-sm btn-success openModalEditar"
                                                data-id="<?php echo $fila['id']; ?>"
                                                data-nombre="<?php echo htmlspecialchars($fila['nombre']); ?>"
                                                data-usuario="<?php echo htmlspecialchars($fila['usuario']); ?>"
                                                data-email="<?php echo htmlspecialchars($fila['email']); ?>"
                                                data-cedula="<?php echo htmlspecialchars($fila['cedula']); ?>"
                                                data-cargo="<?php echo $fila['id_cargo']; ?>"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <a href="?action=admin&method=eliminarUsuario&id=<?php echo $fila['id']; ?>" 
                                                class="btn btn-danger btn-sm" onclick="confirmarEliminacion(event, this.href)">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <div class="footer-content">
            <p>&copy; 2025 INVILARA. Todos los derechos reservados.</p>
        </div>
    </footer>
    <div class="modal" id="userModal">
        <div class="modal-content">
            <div class="tilte">
                <h3 id="modalTitulo">Editar Usuario</h3>
                <i class="fa fa-users"></i>
            </div>

            <form id="userForm" method="post" action="">
                <!-- Campo oculto para el ID del usuario -->
                <input type="hidden" id="id_usuario" name="id_usuario">

                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre">
                    <small class="text-danger" id="nombreError"></small>
                </div>

                <div class="form-group">
                    <label for="usuario">Nombre de Usuario</label>
                    <input type="text" id="usuario" name="usuario">
                    <small class="text-danger" id="usuarioError"></small>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="text" id="email" name="email">
                    <small class="text-danger" id="emailError"></small>
                </div>

                <div class="form-group">
                    <label for="cedula">Cédula</label>
                    <input type="text" id="cedula" name="cedula" style="flex: 1;">
                    <small class="text-danger" id="cedulaError"></small>
                </div>

                <div class="form-group">
                    <label for="cargo">Cargo</label>
                    <select id="cargo" name="cargo">
                        <option value="">Seleccione un cargo</option>
                        <option value="1">Administrador</option>
                        <option value="2">Usuario</option>
                    </select>
                    <small class="text-danger" id="cargoError"></small>
                </div>

                <button type="submit" name="actualizar" id="actualizar" class="btn-submit">Actualizar Usuario</button>
            </form>
        </div>
    </div>

    <!-- Modal para validar clave -->
    <div class="modal fade" id="modalValidarClave" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Validar identidad</h5>
        </div>
        <div class="modal-body">
            <div class="mb-3">
            <label class="form-label">Clave de administrador</label>
            <input type="password" id="inputClaveValidacion" class="form-control">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" id="btnValidarClave" class="btn btn-primary">Validar</button>
        </div>
        </div>
    </div>
    </div>

    <!-- Modal para editar configuración -->
    <div class="modal fade" id="modalEditarConfig" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Editar configuración</h5>
        </div>
        <div class="modal-body">
            <div class="mb-3">
            <label class="form-label">¿Qué deseas modificar?</label>
            <select id="selectCampoEditar" class="form-select" style="width: 50%;">
                <option value="claveSuper">Clave de administrador</option>
                <option value="nombreAPP">Nombre de la aplicación</option>
                <option value="descripcionAPP">Descripción</option>
            </select>
            </div>
            <div id="campoEditarContainer"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" id="btnGuardarCambios" class="btn btn-primary">Guardar</button>
        </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/menu.js"></script>
    <script src="assets/js/config.js"></script>
    <script>
        // Función para confirmar eliminación
        function confirmarEliminacion(event, url) {
            event.preventDefault(); 
            let correctPassword = <?= json_encode(PASSWORD); ?>;
            
            Swal.fire({
                title: 'Confirmar eliminación',
                html: 'Ingrese la contraseña para continuar:<br><input type="password" id="passwordInput" class="swal2-input" placeholder="Contraseña">',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                focusConfirm: false,
                preConfirm: () => {
                    const enteredPassword = document.getElementById('passwordInput').value;
                    if (!enteredPassword) {
                        Swal.showValidationMessage('La contraseña es requerida');
                        return false;
                    }
                    if (enteredPassword !== correctPassword) {
                        Swal.showValidationMessage('Contraseña incorrecta');
                        return false;
                    }
                    return enteredPassword;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    window.location.href = url;
                } else if (result.isConfirmed && !result.value) {
                    // Mostrar alerta de contraseña incorrecta
                    Swal.fire({
                        title: 'Error',
                        text: 'Contraseña incorrecta. Intente nuevamente.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Volver a abrir el diálogo de confirmación
                        confirmarEliminacion(event, url);
                    });
                }
            });
        }
    </script>
    <script>
        document.getElementById('btnAgregar').addEventListener('click', function () {
            Swal.fire({
                title: 'Validar identidad',
                html: `<input type="password" id="swalClave" class="swal2-input" placeholder="Clave de administrador">`,
                confirmButtonText: 'Validar',
                focusConfirm: false,
                preConfirm: () => {
                    const clave = document.getElementById('swalClave').value;
                    if (!clave) {
                        Swal.showValidationMessage('Debes ingresar la clave');
                        return false;
                    }
                    return clave;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const claveCorrecta = <?= json_encode(PASSWORD); ?>;
                    if (result.value === claveCorrecta) {
                        mostrarOpcionesEdicion();
                    } else {
                        Swal.fire('Error', 'Clave incorrecta', 'error');
                    }
                }
            });
        });

        function mostrarOpcionesEdicion() {
            const configActual = {
                nombreAPP: <?= json_encode(APP_NAME); ?>,
                descripcionAPP: <?= json_encode(APP_DESC); ?>
            };

            let campoSeleccionado = 'claveSuper';

            Swal.fire({
                title: 'Editar configuración',
                html: `
                    <select id="swalCampo" class="swal2-select mb-2">
                        <option value="claveSuper">Clave de administrador</option>
                        <option value="nombreAPP">Nombre de la aplicación</option>
                        <option value="descripcionAPP">Descripción</option>
                    </select>
                    <div id="swalCampoContainer"></div>
                `,
                focusConfirm: false,
                didOpen: () => {
                    const selector = document.getElementById('swalCampo');
                    selector.addEventListener('change', function () {
                        campoSeleccionado = this.value;
                        actualizarCampo(configActual, campoSeleccionado);
                    });
                    selector.dispatchEvent(new Event('change')); // Disparar cambio al inicio
                },
                preConfirm: () => {
                    const valor = document.getElementById('swalValor').value.trim();
                    const confirmar = document.getElementById('swalConfirmar')?.value.trim();

                    if (!valor) {
                        Swal.showValidationMessage('Completa este campo');
                        return false;
                    }

                    if (campoSeleccionado === 'claveSuper') {
                        if (valor.length < 8) {
                            Swal.showValidationMessage('La clave debe tener al menos 8 caracteres');
                            return false;
                        }
                        if (valor !== confirmar) {
                            Swal.showValidationMessage('Las claves no coinciden');
                            return false;
                        }
                    }

                    return { campo: campoSeleccionado, valor };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'post';
                    form.style.display = 'none';

                    const campoInput = document.createElement('input');
                    campoInput.type = 'hidden';
                    campoInput.name = 'campo';
                    campoInput.value = result.value.campo;

                    const valorInput = document.createElement('input');
                    valorInput.type = 'hidden';
                    valorInput.name = 'valor';
                    valorInput.value = result.value.valor;

                    form.appendChild(campoInput);
                    form.appendChild(valorInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function actualizarCampo(configActual, campo) {
            let html = '';

            if (campo === 'claveSuper') {
                html = `
                    <input type="password" id="swalValor" class="swal2-input" placeholder="Nueva clave">
                    <input type="password" id="swalConfirmar" class="swal2-input mt-2" placeholder="Confirmar clave">
                    <small class="text-muted">Mínimo 8 caracteres</small>
                `;
            } else if (campo === 'nombreAPP') {
                const valor = configActual.nombreAPP || '';
                html = `<input type="text" id="swalValor" class="swal2-input" placeholder="Nuevo nombre" value="${valor}">`;
            } else if (campo === 'descripcionAPP') {
                const valor = configActual.descripcionAPP || '';
                html = `<textarea id="swalValor" class="swal2-textarea" placeholder="Nueva descripción">${valor}</textarea>`;
            }

            document.getElementById('swalCampoContainer').innerHTML = html;
        }

        // Ejecutar mensaje según resultado del POST (esto debe ir después de DOMContentLoaded)
        document.addEventListener('DOMContentLoaded', function () {
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['campo'])) {
                $campo = $_POST['campo'];
                $valor = $_POST['valor'];
                $resultado = actualizarCampoConfig($campo, $valor);

                if ($resultado === 'exito') {
                    echo "Swal.fire('¡Éxito!', 'Configuración actualizada', 'success').then(() => { location.reload(); });";
                } else {
                    echo "Swal.fire('Error', 'Error al actualizar la configuración', 'error');";
                }
            }
            ?>
        });
    </script>
    <script>
        function filtrarRegistros() {
            const cantidad = document.getElementById('cantidad').value;
            const filas = document.querySelectorAll('tbody tr');
            
            filas.forEach((fila, index) => {
                if (index < cantidad) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        }

        // Llamar a la función al cargar la página para mostrar la cantidad predeterminada
        document.addEventListener('DOMContentLoaded', function() {
            filtrarRegistros();
        });
    </script>
</body>
</html>