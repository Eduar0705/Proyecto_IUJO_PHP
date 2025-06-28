<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-history me-2"></i>Historial de Solicitudes</h2>
    
    <!-- Filtros -->
    <div class="d-flex justify-content-between mb-4">
        <div class="btn-group">
            <a href="?action=usuario&method=historial&filtro=todas" class="btn btn-outline-secondary">Todas</a>
            <a href="?action=usuario&method=historial&filtro=Pendiente" class="btn btn-outline-warning">Pendientes</a>
            <a href="?action=usuario&method=historial&filtro=Aprobada" class="btn btn-outline-success">Aprobadas</a>
            <a href="?action=usuario&method=historial&filtro=Rechazada" class="btn btn-outline-danger">Rechazadas</a>
        </div>
        
        <div class="d-flex align-items-center">
            <label class="me-2">Buscar:</label>
            <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Buscar solicitud...">
        </div>
    </div>
    
    <!-- Tabla de solicitudes -->
    <?php if (empty($solicitudes)): ?>
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
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($solicitudes as $solicitud): ?>
                    <tr>
                        <td><?= $solicitud['ID'] ?></td>
                        <td><?= $solicitud['tipo'] ?></td>
                        <td><?= date('d/m/Y', strtotime($solicitud['fecha_creacion'])) ?></td>
                        <td>
                            <span class="badge 
                                <?= $solicitud['estado'] == 'Aprobada' ? 'bg-success' : 
                                   ($solicitud['estado'] == 'Rechazada' ? 'bg-danger' : 'bg-warning') ?>">
                                <?= $solicitud['estado'] ?>
                            </span>
                        </td>
                        <td>
                            <div class="descripcion-resumen">
                                <?= nl2br(htmlspecialchars(substr($solicitud['descripcion'], 0, 100))) ?>
                                <?php if (strlen($solicitud['descripcion']) > 100): ?>...<?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <a href="index.php?action=usuario&method=verSolicitud&id=<?= $solicitud['ID'] ?>" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Ver
                            </a>
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