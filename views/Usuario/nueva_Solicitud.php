<?php
$title = "Nueva Solicitud";
$nombre = $_SESSION['nombre'] ?? 'Usuario';
?>

<body>
    <main class="container py-5" style="margin-top: 80px;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card form-card">
                    <div class="card-header d-flex align-items-center">
                        <h2 class="mb-0"><i class="fas fa-file-alt me-2"></i> <?= $title ?></h2>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" id="solicitudForm">
                            <!-- Título común -->
                            <div class="mb-4">
                                <label class="form-label h5">Título de la solicitud</label>
                                <input type="text" name="titulo" class="form-control form-control-lg" 
                                    placeholder="Ej: Solicitud de material para oficina" required>
                            </div>
                            
                            <!-- Tipo de solicitud -->
                            <div class="mb-4">
                                <label class="form-label h5">Tipo de solicitud</label>
                                <select name="tipo" id="tipoSolicitud" class="form-select form-select-lg" required>
                                    <option value="">Seleccione un tipo...</option>
                                    <option value="oficina">Material de Oficina</option>
                                    <option value="comida">Comida</option>
                                    <option value="proyecto">Proyecto</option>
                                </select>
                            </div>
                            
                            <!-- Campos dinámicos según tipo -->
                            <div id="camposDinamicos">
                                <!-- Los campos específicos se cargarán aquí con JavaScript -->
                            </div>
                            
                            <!-- Botones -->
                            <div class="d-grid gap-3 d-md-flex justify-content-md-end mt-5 pt-3">
                                <a href="?action=usuario&method=home" class="btn btn-outline-secondary btn-lg me-md-2">
                                    <i class="fas fa-times me-2"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i> Enviar Solicitud
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- JavaScript para campos dinámicos -->
    <script>
    document.getElementById('tipoSolicitud').addEventListener('change', function() {
        const tipo = this.value;
        const contenedor = document.getElementById('camposDinamicos');
        contenedor.innerHTML = '';
        
        if (tipo === 'oficina') {
            contenedor.innerHTML = `
                <h5 class="section-title"><i class="fas fa-clipboard-list me-2"></i>Detalles de Material de Oficina</h5>
                <div class="mb-4">
                    <label class="form-label">Productos solicitados</label>
                    <textarea name="productos" class="form-control" rows="4" 
                              placeholder="Lista de productos necesarios (por favor sea específico con cantidades y descripciones)..." required></textarea>
                </div>
                <div class="mb-4">
                    <label class="form-label">Nivel de urgencia</label>
                    <select name="urgencia" class="form-select form-select-lg" required>
                        <option value="normal">Normal (entrega en 5 días)</option>
                        <option value="urgente">Urgente (entrega en 48 horas)</option>
                        <option value="muy_urgente">Muy urgente (entrega en 24 horas)</option>
                    </select>
                </div>
            `;
        } 
        else if (tipo === 'comida') {
            contenedor.innerHTML = `
                <h5 class="section-title"><i class="fas fa-utensils me-2"></i>Detalles de Pedido de Comida</h5>
                <div class="mb-4">
                    <label class="form-label">Productos solicitados</label>
                    
                    <div id="listaProductos" class="mb-4">
                        <!-- Productos se añadirán aquí dinámicamente -->
                        <div class="producto-item">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <input type="text" name="productos[0][nombre]" class="form-control" 
                                           placeholder="Nombre del producto" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="productos[0][cantidad]" class="form-control" 
                                           min="0.1" step="0.1" placeholder="Cantidad" required>
                                </div>
                                <div class="col-md-3">
                                    <select name="productos[0][unidad]" class="form-select" required>
                                        <option value="">Unidad...</option>
                                        <option value="kg">Kilogramos (kg)</option>
                                        <option value="g">Gramos (g)</option>
                                        <option value="l">Litros (l)</option>
                                        <option value="ml">Mililitros (ml)</option>
                                        <option value="unidades">Unidades</option>
                                        <option value="paquetes">Paquetes</option>
                                        <option value="cajas">Cajas</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-quitar" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" id="btnAgregarProducto" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-1"></i> Agregar otro producto
                    </button>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Fecha de entrega requerida</label>
                        <input type="date" name="fecha_entrega" class="form-control form-control-lg" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Hora de entrega</label>
                        <input type="time" name="hora_entrega" class="form-control form-control-lg">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Comentarios adicionales</label>
                    <textarea name="comentarios" class="form-control" rows="3" 
                              placeholder="Instrucciones especiales, alergias, preferencias..."></textarea>
                </div>
            `;
            
            // Configurar botón para añadir más productos
            document.getElementById('btnAgregarProducto').addEventListener('click', agregarProducto);
            
            // Añadir funcionalidad para eliminar productos
            setTimeout(() => {
                document.querySelectorAll('.btn-quitar').forEach(btn => {
                    btn.addEventListener('click', function() {
                        this.closest('.producto-item').remove();
                    });
                });
            }, 100);
        } 
        else if (tipo === 'proyecto') {
            contenedor.innerHTML = `
                <h5 class="section-title"><i class="fas fa-project-diagram me-2"></i>Detalles del Proyecto</h5>
                <div class="mb-4">
                    <label class="form-label">Nombre del proyecto</label>
                    <input type="text" name="nombre_proyecto" class="form-control form-control-lg" 
                        placeholder="Ej: Implementación del nuevo sistema..." required>
                </div>
                <div class="row g-3">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Fecha límite</label>
                        <input type="date" name="fecha_limite" class="form-control form-control-lg" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Presupuesto estimado ($)</label>
                        <input type="number" name="presupuesto" class="form-control form-control-lg" 
                            min="0" step="0.01" placeholder="0.00">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Descripción del proyecto</label>
                    <textarea name="descripcion_proyecto" class="form-control" rows="5" 
                            placeholder="Detalles del proyecto, objetivos, requerimientos..." required></textarea>
                </div>
                <div class="mb-4">
                    <label class="form-label">Recursos necesarios</label>
                    <textarea name="recursos" class="form-control" rows="3" 
                            placeholder="Equipo, materiales, personal adicional requerido..."></textarea>
                </div>
            `;
        }
    });

    function agregarProducto() {
        const lista = document.getElementById('listaProductos');
        const totalProductos = lista.querySelectorAll('.producto-item').length;
        const nuevoIndex = totalProductos;
        
        const nuevoProducto = document.createElement('div');
        nuevoProducto.className = 'producto-item';
        nuevoProducto.innerHTML = `
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="productos[${nuevoIndex}][nombre]" class="form-control" 
                           placeholder="Nombre del producto" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="productos[${nuevoIndex}][cantidad]" class="form-control" 
                           min="0.1" step="0.1" placeholder="Cantidad" required>
                </div>
                <div class="col-md-3">
                    <select name="productos[${nuevoIndex}][unidad]" class="form-select" required>
                        <option value="">Unidad...</option>
                        <option value="kg">Kilogramos (kg)</option>
                        <option value="g">Gramos (g)</option>
                        <option value="l">Litros (l)</option>
                        <option value="ml">Mililitros (ml)</option>
                        <option value="unidades">Unidades</option>
                        <option value="paquetes">Paquetes</option>
                        <option value="cajas">Cajas</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-quitar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        lista.appendChild(nuevoProducto);
        
        // Habilitar botones de eliminar
        nuevoProducto.querySelector('.btn-quitar').addEventListener('click', function() {
            this.closest('.producto-item').remove();
        });
        
        // Habilitar el primer botón de eliminar si hay más de un producto
        if (totalProductos === 1) {
            lista.querySelector('.producto-item .btn-quitar').disabled = false;
        }
    }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>