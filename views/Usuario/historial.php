<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-history me-2"></i>Historial de Solicitudes</h2>
    <link rel="stylesheet" href="assets/css/config2.css">
    <link rel="stylesheet" href="assets/css/menu.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="assets/img/Logo1.png" type="image/x-icon">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <nav class="navbar navbar-expand-lg navbar-light fixed-top sys-navbar">
        <?php include 'views/layout/header_User.php'; ?>
    </nav>
    <br><br><br>
<!-- Filtros -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <br>
<div class="d-flex align-items-center mb-4 w-100">
    <div class="input-group w-100">
        <span class="input-group-text"><i class="fas fa-search"></i></span>
        <input type="text" class="form-control" id="searchInput" placeholder="Buscar Solicitud...">
    </div>
</div>
</div>

    
    <!-- Tabla de solicitudes -->
    <?php if (empty($res)): ?>
        <div class="alert alert-info text-center py-4">
            <i class="fas fa-inbox fa-3x mb-3"></i>
            <h4>No hay solicitudes registradas</h4>
            <p class="mb-0">Comienza creando tu primera solicitud</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="solicitudesTable">
                <thead class="table-light">
                    <tr>
                        <th>Solicitante</th>
                        <th>Titulo</th>
                        <th>Tipo</th>
                        <th>Fecha de creación</th>
                        <th>Estado</th>
                        <th>Fecha Limite</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($res as $solicitud): $datos = json_decode($solicitud['datos'], true); ?>
                    <tr>
                        <td><?= $solicitud['solicitante'] ?></td>
                        <td><?= $solicitud['titulo'] ?></td>
                        <td><?= $solicitud['tipo'] ?></td>
                        <td><?= date('d/m/Y', strtotime($solicitud['fecha_creacion'])) ?></td>
                        <td>
                            <span class="badge 
                                <?= $solicitud['estado'] == 'Aprobada' ? 'bg-success' : 
                                   ($solicitud['estado'] == 'Rechazada' ? 'bg-danger' : 'bg-warning') ?>">
                                <?= $solicitud['estado'] ?>
                            </span>
                            <td><?= $solicitud['fecha_inminente'] ?></td>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
// Búsqueda en tiempo real
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#solicitudesTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>