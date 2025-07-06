<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $title ?? '' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/config2.css">
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
        <h2 class="table-title mb-4"><i class="fas fa-users me-2"></i> Gestión de Usuarios</h2>
        
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <!-- Barra de búsqueda -->
            <div class="search-container flex-grow-1">
                <input type="text" class="form-control py-2" placeholder="Buscar por nombre o usuario..." id="searchInput">
            </div>
            
        </div>

        <!-- Tabla de usuarios -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">ID</th>
                            <th class="py-3">Nombre</th>
                            <th class="py-3">Usuario</th>
                            <th class="py-3">Correo</th>
                            <th class="py-3">Cédula</th>
                            <th class="py-3">Cargo</th>
                            <th class="py-3 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $res->fetch_assoc()): ?>
                            <tr>
                                <td class="py-3 fw-medium"><?= htmlspecialchars($fila['id'] ?? ''); ?></td>
                                <td class="py-3"><?= htmlspecialchars($fila['nombre'] ?? ''); ?></td>
                                <td class="py-3"><?= htmlspecialchars($fila['usuario'] ?? ''); ?></td>
                                <td class="py-3"><?= htmlspecialchars($fila['email'] ?? ''); ?></td>
                                <td class="py-3"><?= htmlspecialchars($fila['cedula'] ?? ''); ?></td>
                                <td class="py-3">
                                    <span class="badge bg-<?= ($fila['id_cargo'] ?? '') == 1 ? 'primary' : 'secondary' ?>">
                                        <?= ($fila['id_cargo'] ?? '') == 1 ? 'Administrador' : 'Usuario' ?>
                                    </span>
                                </td>
                                <td class="py-3 text-end">
                                    <div class="d-flex gap-2 justify-content-end table-actions">
                                        <!-- Botón Editar -->
                                        <a href="#" 
                                           class="btn btn-outline-primary btn-sm openModalEditar"
                                           data-id="<?= $fila['id'] ?>"
                                           data-nombre="<?= htmlspecialchars($fila['nombre']); ?>"
                                           data-usuario="<?= htmlspecialchars($fila['usuario']); ?>"
                                           data-email="<?= htmlspecialchars($fila['email']); ?>"
                                           data-cedula="<?= htmlspecialchars($fila['cedula']); ?>"
                                           data-cargo="<?= $fila['id_cargo']; ?>">
                                            <i class="fas fa-edit me-1"></i> 
                                        </a>
                                        
                                        <!-- Botón Eliminar -->
                                        <a href="?action=admin&method=eliminarUsuario&id=<?= $fila['id'] ?>" 
                                           class="btn btn-outline-danger btn-sm" 
                                           onclick="confirmarEliminacion(event, this.href)">
                                            <i class="fa-solid fa-trash me-1"></i> 
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/menu.js"></script>
    <script src="assets/js/config.js"></script>
    <script>
        // Función para confirmar eliminación
        function confirmarEliminacion(event, url) {
            event.preventDefault(); 
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>
</body>
</html>