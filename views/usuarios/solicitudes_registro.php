<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $title ?? '' ?></title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/config.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            <h1 class="mt-4">Solicitudes de Registro</h1>
            <a href="?action=admin&method=solicitudes" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Lista de Usuarios</h5>
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
                                            <a href="?action=admin&method=validarSolicitud&id=<?php echo $fila['id']; ?>" 
                                                class=" btn btn-success" onclick="confirmarAgregacion(event, this.href)">
                                                <i class="bi bi-check"></i>
                                            </a>
                                            <a href="?action=admin&method=eliminarSolicitud&id=<?php echo $fila['id']; ?>" 
                                                class=" btn btn-danger btn-sm" onclick="confirmarEliminacion(event, this.href)">
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

     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/menu.js"></script> <!-- Antes de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/config.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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
        function confirmarAgregacion(event, url) {
            event.preventDefault(); 
            
            Swal.fire({
                title: '¿Añadir el usuario al sistéma?',
                text: "¡Debe estar capacitado para usar el sistéma!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, Aceptar',
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