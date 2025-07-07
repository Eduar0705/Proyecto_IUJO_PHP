    // ===== VARIABLES GLOBALES =====
    let productoCounter = 0

    // ===== INICIALIZACIÓN =====
    document.addEventListener("DOMContentLoaded", () => {
    initializeForm()
    setupEventListeners()
    })

    // ===== CONFIGURACIÓN INICIAL =====
    function initializeForm() {
    // Configurar fecha mínima para campos de fecha
    const today = new Date().toISOString().split("T")[0]
    const dateInputs = document.querySelectorAll('input[type="date"]')
    dateInputs.forEach((input) => {
        input.min = today
    })

    // Configurar validación en tiempo real
    setupRealTimeValidation()
    }

    function setupEventListeners() {
    // Listener para cambio de tipo de solicitud
    document.getElementById("tipoSolicitud").addEventListener("change", handleTipoChange)

    // Listener para teclas de acceso rápido
    document.addEventListener("keydown", handleKeyboardShortcuts)
    }
    document.getElementById('solicitudForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    if (!validateForm()) return;

    showLoadingOverlay();

    const formData = new FormData(this);

    try {
        const response = await fetch(this.action, {
            method: "POST",
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            window.location.href = result.redirect;
        } else {
            showErrorMessage(result.message || "Error al enviar la solicitud");
        }
    } catch (error) {
        showErrorMessage("Error de conexión con el servidor");
    } finally {
        hideLoadingOverlay();
    }
});


    // ===== MANEJO DE TIPOS DE SOLICITUD =====
    function handleTipoChange() {
    const tipo = this.value
    const contenedor = document.getElementById("camposDinamicos")

    // Limpiar contenedor
    contenedor.innerHTML = ""
    productoCounter = 0

    // Mostrar loading
    showFieldsLoading(contenedor)

    // Simular carga (para mejor UX)
    setTimeout(() => {
        loadFieldsForType(tipo, contenedor)
    }, 300)
    }

    function showFieldsLoading(contenedor) {
    contenedor.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i>
                <p class="text-muted">Cargando campos...</p>
            </div>
        `
    }

    function loadFieldsForType(tipo, contenedor) {
    switch (tipo) {
        case "oficina":
        loadOficinaFields(contenedor)
        break
        case "comida":
        loadComidaFields(contenedor)
        break
        case "proyecto":
        loadProyectoFields(contenedor)
        break
        default:
        contenedor.innerHTML = ""
    }
    }

    // ===== CAMPOS PARA OFICINA =====
    function loadOficinaFields(contenedor) {
    contenedor.innerHTML = `
            <h5 class="sys-section-title">
                <i class="fas fa-clipboard-list me-2"></i>Detalles de Material de Oficina
            </h5>
            
            <div class="sys-form-group">
                <label class="sys-form-label">
                    <i class="fas fa-list me-2"></i>Productos solicitados
                    <span class="sys-required">*</span>
                </label>
                <textarea name="productos" 
                        id="productos"
                        class="sys-form-control" 
                        rows="5" 
                        placeholder="Lista detallada de productos necesarios:&#10;• Producto 1 - Cantidad - Especificaciones&#10;• Producto 2 - Cantidad - Especificaciones&#10;• ..." 
                        required></textarea>
                <div class="sys-form-help">
                    Sea específico con cantidades, marcas preferidas y especificaciones técnicas
                </div>
            </div>
            
            <div class="sys-form-group">
                <label class="sys-form-label">
                    <i class="fas fa-exclamation-circle me-2"></i>Nivel de urgencia
                    <span class="sys-required">*</span>
                </label>
                <select name="urgencia" id="urgencia" class="sys-form-control sys-form-control--lg" required>
                    <option value="">Seleccione el nivel de urgencia...</option>
                    <option value="normal">🟢 Normal (entrega en alrededor de 7 días hábiles)</option>
                    <option value="urgente">🟡 Urgente (entrega en 48 horas)</option>
                    <option value="muy_urgente">🔴 Muy urgente (entrega en 24 horas)</option>
                </select>
                <div class="sys-form-help">
                    El nivel de urgencia afecta la prioridad de procesamiento
                </div>
            </div>
            
            <div class="sys-form-group">
                <label class="sys-form-label">
                    <i class="fas fa-comment me-2"></i>Justificación de la solicitud
                </label>
                <textarea name="justificacion"
                        id="justificacion"
                        class="sys-form-control" 
                        rows="3" 
                        placeholder="Explique brevemente por qué necesita estos materiales..."></textarea>
                <div class="sys-form-help">
                    Una justificación clara ayuda a acelerar la aprobación
                </div>
            </div>
        `

    // Animar entrada
    contenedor.style.opacity = "0"
    contenedor.style.transform = "translateY(20px)"
    setTimeout(() => {
        contenedor.style.transition = "all 0.4s ease"
        contenedor.style.opacity = "1"
        contenedor.style.transform = "translateY(0)"
    }, 50)
    }

    // ===== CAMPOS PARA COMIDA =====
    function loadComidaFields(contenedor) {
    contenedor.innerHTML = `
            <h5 class="sys-section-title">
                <i class="fas fa-utensils me-2"></i>Detalles de Pedido de Comida
            </h5>
            
            <div class="sys-form-group">
                <label class="sys-form-label">
                    <i class="fas fa-shopping-cart me-2"></i>Productos solicitados
                    <span class="sys-required">*</span>
                </label>
                
                <div id="listaProductos" class="mb-3">
                    <!-- Primer producto -->
                    <div class="sys-product-item" data-index="0">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label small">Nombre del producto</label>
                                <input type="text" id="productos[0][nombre]"
                                    name="productos[0][nombre]" 
                                    class="sys-form-control" 
                                    placeholder="Ej: Arroz blanco" 
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Cantidad</label>
                                <input type="number" 
                                    name="productos[0][cantidad]" 
                                    id="productos[0][cantidad]"
                                    class="sys-form-control" 
                                    min="0.1" 
                                    step="0.1" 
                                    placeholder="1.5" 
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Unidad</label>
                                <select name="productos[0][unidad]" id="productos[0][unidad]" class="sys-form-control" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="kg">Kilogramos (kg)</option>
                                    <option value="g">Gramos (g)</option>
                                    <option value="l">Litros (l)</option>
                                    <option value="ml">Mililitros (ml)</option>
                                    <option value="unidades">Unidades</option>
                                    <option value="paquetes">Paquetes</option>
                                    <option value="cajas">Cajas</option>
                                    <option value="latas">Latas</option>
                                    <option value="bolsas">Bolsas</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label small">&nbsp;</label>
                                <button type="button" class="sys-remove-btn" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="button" id="btnAgregarProducto" class="sys-add-product-btn">
                    <i class="fas fa-plus me-2"></i>Agregar otro producto
                </button>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="sys-form-group">
                        <label class="sys-form-label">
                            <i class="fas fa-calendar me-2"></i>Fecha de entrega requerida
                            <span class="sys-required">*</span>
                        </label>
                        <input type="date" 
                            name="fecha_limite" id="fecha_limite"
                            class="sys-form-control sys-form-control--lg" 
                            required>
                        <div class="sys-form-help">
                            Seleccione la fecha en que necesita recibir los productos
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="sys-form-group">
                <label class="sys-form-label">
                    <i class="fas fa-comment-alt me-2"></i>Comentarios adicionales
                </label>
                <textarea name="comentarios" id="comentarios"
                        class="sys-form-control" 
                        rows="4" 
                        placeholder="Instrucciones especiales, alergias, preferencias de marca, lugar de entrega específico..."></textarea>
                <div class="sys-form-help">
                    Incluya cualquier información adicional que pueda ser útil
                </div>
            </div>
        `

    // Configurar eventos para productos
    setupProductEvents()
    productoCounter = 1

    // Animar entrada
    animateFieldsEntry(contenedor)
    }

    // ===== CAMPOS PARA PROYECTO =====
    function loadProyectoFields(contenedor) {
    contenedor.innerHTML = `
            <h5 class="sys-section-title">
                <i class="fas fa-project-diagram me-2"></i>Detalles del Proyecto
            </h5>
            
            <div class="sys-form-group">
                <label class="sys-form-label">
                    <i class="fas fa-tag me-2"></i>Nombre del proyecto
                    <span class="sys-required">*</span>
                </label>
                <input type="text" 
                    name="nombre_proyecto" id="nombre_proyecto"
                    class="sys-form-control sys-form-control--lg" 
                    placeholder="Ej: Implementación del nuevo sistema de gestión" 
                    required>
                <div class="sys-form-help">
                    Proporcione un nombre descriptivo y específico para el proyecto
                </div>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="sys-form-group">
                        <label class="sys-form-label">
                            <i class="fas fa-calendar-alt me-2"></i>Fecha límite
                            <span class="sys-required">*</span>
                        </label>
                        <input type="date" 
                            name="fecha_limite" id="fecha_limite"
                            class="sys-form-control sys-form-control--lg" 
                            required>
                        <div class="sys-form-help">
                            Fecha en que el proyecto debe estar completado
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="sys-form-group">
                        <label class="sys-form-label">
                            <i class="fas fa-dollar-sign me-2"></i>Presupuesto estimado
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" 
                                name="presupuesto" id="presupuesto"
                                class="sys-form-control sys-form-control--lg" 
                                min="0" 
                                step="0.01" 
                                placeholder="0.00">
                        </div>
                        <div class="sys-form-help">
                            Estimación del costo total del proyecto (opcional)
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="sys-form-group">
                <label class="sys-form-label">
                    <i class="fas fa-align-left me-2"></i>Descripción del proyecto
                    <span class="sys-required">*</span>
                </label>
                <textarea name="descripcion_proyecto" id="descripcion_proyecto"
                        class="sys-form-control" 
                        rows="6" 
                        placeholder="Describa detalladamente:&#10;• Objetivos del proyecto&#10;• Alcance y entregables&#10;• Beneficios esperados&#10;• Cualquier consideración especial..." 
                        required></textarea>
                <div class="sys-form-help">
                    Una descripción detallada facilita la evaluación y aprobación
                </div>
            </div>
            
            <div class="sys-form-group">
                <label class="sys-form-label">
                    <i class="fas fa-tools me-2"></i>Recursos necesarios
                </label>
                <textarea name="recursos" id="recursos"
                        class="sys-form-control" 
                        rows="4" 
                        placeholder="Especifique los recursos requeridos:&#10;• Personal adicional&#10;• Equipos y herramientas&#10;• Software o licencias&#10;• Materiales específicos&#10;• Servicios externos..."></textarea>
                <div class="sys-form-help">
                    Liste todos los recursos que considera necesarios para el proyecto
                </div>
            </div>
            
            <div class="sys-form-group">
                <label class="sys-form-label">
                    <i class="fas fa-exclamation-triangle me-2"></i>Prioridad del proyecto
                </label>
                <select name="prioridad" id="prioridad" class="sys-form-control sys-form-control--lg">
                    <option value="">Seleccione la prioridad...</option>
                    <option value="baja">🟢 Baja - Puede esperar</option>
                    <option value="media">🟡 Media - Importante</option>
                    <option value="alta">🟠 Alta - Urgente</option>
                    <option value="critica">🔴 Crítica - Máxima prioridad</option>
                </select>
                <div class="sys-form-help">
                    La prioridad ayuda a determinar el orden de procesamiento
                </div>
            </div>
        `

    // Animar entrada
    animateFieldsEntry(contenedor)
    }

    // ===== MANEJO DE PRODUCTOS =====
    function setupProductEvents() {
    // Botón agregar producto
    document.getElementById("btnAgregarProducto").addEventListener("click", agregarProducto)

    // Botones eliminar existentes
    document.querySelectorAll(".sys-remove-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
        eliminarProducto(this)
        })
    })
    }

    function agregarProducto() {
    const lista = document.getElementById("listaProductos")
    const nuevoIndex = productoCounter

    const nuevoProducto = document.createElement("div")
    nuevoProducto.className = "sys-product-item"
    nuevoProducto.setAttribute("data-index", nuevoIndex)
    nuevoProducto.innerHTML = `
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label small">Nombre del producto</label>
                    <input type="text" 
                        name="productos[${nuevoIndex}][nombre]" 
                        class="sys-form-control" 
                        placeholder="Ej: Aceite de cocina" 
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Cantidad</label>
                    <input type="number" 
                        name="productos[${nuevoIndex}][cantidad]" 
                        class="sys-form-control" 
                        min="0.1" 
                        step="0.1" 
                        placeholder="2.0" 
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Unidad</label>
                    <select name="productos[${nuevoIndex}][unidad]" class="sys-form-control" required>
                        <option value="">Seleccionar...</option>
                        <option value="kg">Kilogramos (kg)</option>
                        <option value="g">Gramos (g)</option>
                        <option value="l">Litros (l)</option>
                        <option value="ml">Mililitros (ml)</option>
                        <option value="unidades">Unidades</option>
                        <option value="paquetes">Paquetes</option>
                        <option value="cajas">Cajas</option>
                        <option value="latas">Latas</option>
                        <option value="bolsas">Bolsas</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label small">&nbsp;</label>
                    <button type="button" class="sys-remove-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `

    // Animar entrada del nuevo producto
    nuevoProducto.style.opacity = "0"
    nuevoProducto.style.transform = "translateX(-20px)"
    lista.appendChild(nuevoProducto)

    setTimeout(() => {
        nuevoProducto.style.transition = "all 0.3s ease"
        nuevoProducto.style.opacity = "1"
        nuevoProducto.style.transform = "translateX(0)"
    }, 50)

    // Configurar evento de eliminar
    nuevoProducto.querySelector(".sys-remove-btn").addEventListener("click", function () {
        eliminarProducto(this)
    })

    // Habilitar botones de eliminar si hay más de un producto
    updateRemoveButtons()

    productoCounter++
    }

    function eliminarProducto(btn) {
    const productoItem = btn.closest(".sys-product-item")

    // Animar salida
    productoItem.style.transition = "all 0.3s ease"
    productoItem.style.opacity = "0"
    productoItem.style.transform = "translateX(20px)"

    setTimeout(() => {
        productoItem.remove()
        updateRemoveButtons()
    }, 300)
    }

    function updateRemoveButtons() {
    const productos = document.querySelectorAll(".sys-product-item")
    const removeButtons = document.querySelectorAll(".sys-remove-btn")

    removeButtons.forEach((btn) => {
        btn.disabled = productos.length <= 1
    })
    }

    // ===== VALIDACIÓN EN TIEMPO REAL =====
    function setupRealTimeValidation() {
    document.addEventListener("input", (e) => {
        if (e.target.matches(".sys-form-control")) {
        validateField(e.target)
        }
    })
    }

    function validateField(field) {
    const isValid = field.checkValidity()

    if (isValid) {
        field.classList.remove("is-invalid")
        field.classList.add("is-valid")
    } else {
        field.classList.remove("is-valid")
        field.classList.add("is-invalid")
    }
    }

    // ===== MANEJO DEL FORMULARIO =====
    
    function validateForm() {
    const form = document.getElementById("solicitudForm")
    const isValid = form.checkValidity()

    if (!isValid) {
        form.reportValidity()
        showErrorMessage("Por favor, complete todos los campos requeridos.")
        return false
    }

    return true
    }
    
    // ===== UTILIDADES UI =====
    function showLoadingOverlay() {
    document.getElementById("loadingOverlay").style.display = "flex"
    }

    function hideLoadingOverlay() {
    document.getElementById("loadingOverlay").style.display = "none"
    }

    function showSuccessMessage() {
    // Implementar notificación de éxito
    alert("¡Solicitud enviada exitosamente!")
    }

    function showErrorMessage(message) {
    // Implementar notificación de error
    alert("Error: " + message)
    }

    function animateFieldsEntry(contenedor) {
    contenedor.style.opacity = "0"
    contenedor.style.transform = "translateY(20px)"
    setTimeout(() => {
        contenedor.style.transition = "all 0.4s ease"
        contenedor.style.opacity = "1"
        contenedor.style.transform = "translateY(0)"
    }, 50)
    }

    // ===== ATAJOS DE TECLADO =====
    function handleKeyboardShortcuts(e) {
    // Ctrl + Enter para enviar formulario


    // Escape para cancelar
    if (e.key === "Escape") {
        if (confirm("¿Está seguro de que desea cancelar? Se perderán los datos ingresados.")) {
        window.location.href = "?action=usuario&method=home"
        }
    }
    }

    // ===== AUTOGUARDADO (OPCIONAL) =====
    function setupAutoSave() {
    setInterval(() => {
        const formData = new FormData(document.getElementById("solicitudForm"))
        const data = Object.fromEntries(formData)
        localStorage.setItem("solicitud_draft", JSON.stringify(data))
    }, 30000) // Guardar cada 30 segundos
    }

    function loadDraft() {
    const draft = localStorage.getItem("solicitud_draft")
    if (draft) {
        const data = JSON.parse(draft)
        // Implementar lógica para cargar datos guardados
    }
    }
