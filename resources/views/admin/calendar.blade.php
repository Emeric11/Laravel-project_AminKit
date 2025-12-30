@extends('layouts.adminkit')

@section('title', 'Calendario de Tareas')
@section('page-title', '📅 Calendario')

@push('fullcalendar-css')
    <link href="{{ asset('vendor/fullcalendar/lib/main.min.css') }}" rel="stylesheet">
    <style>
        /* Calendario compacto */
        #calendar {
            font-size: 0.875rem;
        }

        .fc .fc-toolbar-title {
            font-size: 1.25rem;
        }

        .fc .fc-button {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .fc .fc-daygrid-day-frame {
            min-height: 60px;
        }

        .fc-event {
            padding: 1px 3px;
            margin: 1px 0;
            font-size: 0.75rem;
        }

        .fc-timegrid-slot {
            height: 1.5em;
        }

        .sidebar-actions {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
            height: 100%;
        }

        .quick-action-btn {
            width: 100%;
            margin-bottom: 0.5rem;
            text-align: left;
            padding: 0.5rem 0.75rem;
        }

        .event-preview {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        /*-----------------+NUEVOS ESRTILOS PARA CALENDARIO DE PRODUCCION+-----------------*/
        .codigo-row {
            background: #f8f9fa;
            transition: all 0.2s;
        }

        .codigo-row:hover {
            background: #e9ecef;
        }


        /* Estilo para fila allDay */
        .fc .fc-timegrid-axis-cushion,
        .fc .fc-timegrid-slot-label-cushion {
            font-weight: 600;
        }

        /* Eventos allDay más visibles */
        .fc-event.fc-event-all-day {
            border-left: 4px solid #666;
            font-size: 0.8rem;
        }

        /* En vista mensual */
        .fc-daygrid-day-events .fc-event {
            margin: 1px 0;
        }


        /*ESTILOS HACWR CLICK EN LA FECHA*/
        /* ESTILOS PARA FECHA SELECCIONADA */
        .fc-day-selected,
        .fc-day-highlight {
            background-color: rgba(59, 130, 246, 0.1) !important;
            border: 2px solid #3b82f6 !important;
            position: relative;
        }

        .fc-day-highlight::after {
            content: "📍";
            position: absolute;
            top: 2px;
            right: 2px;
            font-size: 12px;
            z-index: 10;
        }

        .fc-timegrid-col-selected {
            background-color: rgba(59, 130, 246, 0.15) !important;
            border-left: 3px solid #3b82f6 !important;
        }

        /* ANIMACIÓN DE DESTELLO */
        @keyframes pulse-date {
            0% {
                background-color: rgba(59, 130, 246, 0.1);
            }

            50% {
                background-color: rgba(59, 130, 246, 0.2);
            }

            100% {
                background-color: rgba(59, 130, 246, 0.1);
            }
        }

        .fc-day-selected {
            animation: pulse-date 2s infinite;
        }

        /* NOTIFICACIÓN MEJORADA */
        #fecha-seleccionada-notif {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-left: 4px solid #3b82f6;
        }
    </style>
@endpush

@section('content')
    <div class="row">

        <!-- Calendario principal (3/4 ancho) -->
        <div class="col-lg-9 col-m  d-8">
            <div class="card">
                <div class="card-body p-3">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- Sidebar acciones rápidas (1/4 ancho) -->
        <div class="col-lg-3 col-md-4">
            <div class="sidebar-actions">






                <!-- Vista previa del evento seleccionado -->
                <!-- REEMPLAZA todo el div "event-preview" con este código: -->
                <div class="event-preview" id="eventPreview">



                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small">Fecha Entrega</label>
                            <input type="date" class="form-control form-control-sm" id="fechaEntrega"
                                value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Fecha Producción</label>
                            <input type="date" class="form-control form-control-sm" id="fechaProduccion"
                                value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <h6 class="mb-2">
                        <span id="previewTitle">📦 Nueva Orden de Producción</span>
                        <small class="text-muted float-end" id="previewMode">(Nuevo)</small>
                    </h6>


                    <div class="mb-2">
                        <label class="form-label small">Cliente</label>
                        <input type="text" class="form-control form-control-sm" id="cliente"
                            placeholder="Nombre del cliente">
                    </div>
                    <!-- Campo OP (Orden de Producción) -->
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small">Orden de Producción</label>
                            <input type="text" class="form-control form-control-sm" id="opNumber" placeholder="OP-001"
                                value="OP-">
                        </div>

                        <div class="col-6">
                            <label class="form-label small">Cantidad Requerida</label>
                            <input type="number" class="form-control form-control-sm" id="cantidadReq" placeholder="1000"
                                min="1">
                        </div>
                    </div>
                    <!-- Estado -->
                    <div class="mb-2">
                        <label class="form-label small">Estado</label>
                        <select class="form-select form-select-sm" id="eventStatus">
                            <option value="pendiente">🟡 Pendiente</option>
                            <option value="en_progreso">🔵 En Progreso</option>
                            <option value="completado">🟢 Completado</option>
                            <option value="retrasado">🔴 Cancelado</option>
                        </select>
                    </div>

                    <!-- Lista de Códigos -->
                    <div class="mb-2">
                        <label class="form-label small">Productos</label>
                        <div id="codigosList" class="small mb-2">
                            <!-- Se llenará dinámicamente -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="addCodigoRow()">
                            <i class="fas fa-plus"></i> Agregar Producto
                        </button>
                    </div>
                    <!-- Fecha y Horas -->
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small">Fecha</label>
                            <input type="date" class="form-control form-control-sm" id="eventDate"
                                value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-3">
                            <label class="form-label small">Inicio</label>
                            <input type="time" class="form-control form-control-sm" id="startTime" value="08:00"
                                step="300"> <!-- 300 segundos = 5 minutos -->
                        </div>
                        <div class="col-3">
                            <label class="form-label small">Fin</label>
                            <input type="time" class="form-control form-control-sm" id="endTime" value="17:00"
                                step="300">
                        </div>
                    </div>
                    <!-- Botones de Acción -->

                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary flex-fill" id="saveBtn" onclick="saveProductionEvent()">
                            <i class="fas fa-plus me-1"></i> Crear Nuevo
                        </button>
                        <button class="btn btn-sm btn-success flex-fill" id="updateBtn" onclick="updateProductionEvent()"
                            style="display: none;">
                            <i class="fas fa-save me-1"></i> Actualizar-
                        </button>
                        <button class="btn btn-sm btn-danger" id="deleteBtn" onclick="deleteCurrentEvent()"
                            style="display: none;">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-secondary" id="cancelBtn" onclick="resetToNewEvent()"
                            style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                </div>
                <h6 class="mb-3">⚡ Acciones Rápidas</h6>

                <div class="mb-4">
                    <button class="btn btn-primary quick-action-btn" onclick="addQuickEvent('comedor')">
                        <i class="fas fa-users me-2"></i>comedor
                    </button>
                    <button class="btn btn-success quick-action-btn" onclick="addQuickEvent('falla electrica')">
                        <i class="fas fa-file-alt me-2"></i>falla electrica
                    </button>
                    <button class="btn btn-warning quick-action-btn" onclick="addQuickEvent('falla sistema')">
                        <i class="fas fa-bell me-2"></i>falla sistema
                    </button>
                    <button class="btn btn-info quick-action-btn" onclick="addQuickEvent('mantenimiento')">
                        <i class="fas fa-phone me-2"></i>mantenimiento
                    </button>
                </div>

                <!-- Información del evento actual -->
                <input type="hidden" id="currentEventId" value="">
            </div>

            <!-- Template oculto para filas de código -->
            <template id="codigoTemplate">
                <div class="codigo-row mb-1 border rounded p-1">
                    <div class="row g-1">
                        <div class="col-4">
                            <input type="text" class="form-control form-control-sm codigo" placeholder="Código"
                                required>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control form-control-sm descripcion"
                                placeholder="Descripción" required>
                        </div>

                        <div class="col-6">
                            <input type="text" class="form-control form-control-sm factura" placeholder="factura"
                                min="1" required>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control form-control-sm ordenCompra"
                                placeholder="ordenCompra" required>
                        </div>
                        <div class="col-6">
                            <input type="number" class="form-control form-control-sm cantidad" placeholder="Cant"
                                min="1" required>
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-sm btn-danger p-0 w-100"
                                onclick="removeCodigoRow(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </template>

      
        </div>
    
    </div>
                              <!-- Eventos de hoy -->
            <div class="mt-4">
                <h6 class="mb-2">📅 Hoy</h6>
                <div class="list-group" id="todayEvents">
                    <div class="list-group-item list-group-item-action py-2">
                        <small class="text-primary">10:00 AM</small>
                        <div>Reunión de equipo</div>
                    </div>
                </div>
            </div>

<!-- Toast container -->
<div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
    <div id="toastContainer"></div>
</div>

@endsection

@push('fullcalendar-js')
    <script src="{{ asset('vendor/fullcalendar/lib/main.min.js') }}"></script>
    <script src="{{ asset('vendor/fullcalendar/lib/locales/es.js') }}"></script>
@endpush

@section('scripts')
    <script>
        // ==================== CONFIGURACIÓN GLOBAL ====================
        let calendar;
        let currentEvent = null;
        let codigosCounter = 0;
        // Variable global para guardar la última fecha seleccionada
        let ultimaFechaSeleccionada = null;

        // ==================== UTILIDADES: Toasts y escape ====================
        function escapeHtml(unsafe) {
            if (unsafe === null || unsafe === undefined) return '';
            return String(unsafe)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function showToast(message, type = 'info', timeout = 3000) {
            const container = document.getElementById('toastContainer');
            if (!container) {
                // Fallback a alert si no existe el contenedor
                try { alert(message); } catch (e) { console.log(message); }
                return;
            }

            const bgClass = (type === 'success') ? 'bg-success text-white' :
                            (type === 'danger') ? 'bg-danger text-white' :
                            (type === 'warning') ? 'bg-warning text-dark' : 'bg-info text-white';

            const toast = document.createElement('div');
            toast.className = `toast align-items-center ${bgClass} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.dataset.bsDelay = timeout;

            const inner = document.createElement('div');
            inner.className = 'd-flex';

            const body = document.createElement('div');
            body.className = 'toast-body';
            body.textContent = message;

            const btnClose = document.createElement('button');
            btnClose.type = 'button';
            btnClose.className = 'btn-close btn-close-white me-2 m-auto';
            btnClose.dataset.bsDismiss = 'toast';
            btnClose.setAttribute('aria-label', 'Close');

            inner.appendChild(body);
            inner.appendChild(btnClose);

            toast.appendChild(inner);
            container.appendChild(toast);

            try {
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
                toast.addEventListener('hidden.bs.toast', () => toast.remove());
            } catch (e) {
                // Fallback a alert si bootstrap no está disponible
                alert(message);
            }
        }



    
        // ==================== FUNCIÓN PARA MOSTRAR FECHA SELECCIONADA ====================
        function mostrarSeleccionFecha(fecha, vista = 'mensual') {
            // 1. Quitar selección anterior
            document.querySelectorAll('.fc-day-selected').forEach(el => {
                el.classList.remove('fc-day-selected', 'fc-day-highlight');
            });

            document.querySelectorAll('.fc-timegrid-col-selected').forEach(el => {
                el.classList.remove('fc-timegrid-col-selected', 'fc-timegrid-highlight');
            });

            // 2. Crear fecha a partir del parámetro
            const fechaObj = new Date(fecha);

            // 3. Guardar para uso posterior
            ultimaFechaSeleccionada = fechaObj;

            // 4. Mostrar en consola para debug
            console.log('📍 Fecha seleccionada:', fechaObj.toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }));

            // 5. ACTUALIZAR FORMULARIO CON FECHA SELECCIONADA
            // Solo actualizar si el formulario está en modo "Nuevo"
            if (!currentEvent) {
                const fechaFormatoInput = fechaObj.toISOString().split('T')[0];
                document.getElementById('eventDate').value = fechaFormatoInput;

                // Mostrar notificación visual
                mostrarNotificacionFecha(fechaObj);
            }

            // ✅ ACTUALIZAR DISPLAY EN SIDEBAR
            const fechaDisplay = document.getElementById('fechaActualDisplay');
            if (fechaDisplay) {
                fechaDisplay.textContent = fechaObj.toLocaleDateString('es-ES', {
                    weekday: 'short',
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }
            // 6. Resaltar visualmente en calendario
            setTimeout(() => {
                const cellSelector = vista === 'mensual' ?
                    `.fc-day[data-date="${fechaObj.toISOString().split('T')[0]}"]` :
                    `.fc-timegrid-col[data-date="${fechaObj.toISOString().split('T')[0]}"]`;

                const celda = document.querySelector(cellSelector);
                if (celda) {
                    celda.classList.add('fc-day-selected', 'fc-day-highlight');
                }
            }, 50);
        }

        // ==================== NOTIFICACIÓN VISUAL ====================
        function mostrarNotificacionFecha(fecha) {
            // Crear o actualizar notificación
            let notificacion = document.getElementById('fecha-seleccionada-notif');

            if (!notificacion) {
                notificacion = document.createElement('div');
                notificacion.id = 'fecha-seleccionada-notif';
                notificacion.className = 'alert alert-info alert-dismissible fade show';
                notificacion.style.cssText = `
            position: fixed;
            top: 70px;
            right: 20px;
            z-index: 9999;
            max-width: 300px;
             `;

                document.body.appendChild(notificacion);
            }

            const opcionesFecha = {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };

            notificacion.innerHTML = `
              <strong>📅 Fecha seleccionada:</strong><br>
              <small>${fecha.toLocaleDateString('es-ES', opcionesFecha)}</small>
              <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
          `;

            // Auto-ocultar después de 3 segundos
            setTimeout(() => {
                if (notificacion && notificacion.parentNode) {
                    notificacion.remove();
                }
            }, 3000);
        }
        // ==================== FUNCIONES AUXILIARES ====================
        function getColorByStatus(estado) {
            const colores = {
                'pendiente': '#f59e0b',
                'en_progreso': '#3b82f6',
                'completado': '#10b981',
                'retrasado': '#ef4444'
            };
            return colores[estado] || '#6b7280';
        }
        // ==================== OBTENER CODIGOS ====================
        function getCodigosFromForm() {
            const codigos = [];
            document.querySelectorAll('.codigo-row').forEach(row => {
                const codigo = row.querySelector('.codigo').value.trim();
                const descripcion = row.querySelector('.descripcion').value.trim();
                const cantidad = parseInt(row.querySelector('.cantidad').value) || 0;
                const factura = row.querySelector('.factura')?.value.trim() || '';
                const ordenCompra = row.querySelector('.ordenCompra')?.value.trim() || '';

                if (codigo && descripcion && cantidad > 0) {
                    codigos.push({
                        codigo,
                        descripcion,
                        cantidad,
                        factura,
                        ordenCompra
                    });
                }
            });
            return codigos;
        }
        // ==================== AGREGAR CODIGOS CODIGOS ====================
        function addCodigoRow(codigoData = null) {
            const template = document.getElementById('codigoTemplate');
            const clone = template.content.cloneNode(true);
            const container = document.getElementById('codigosList');

            if (codigoData) {
                clone.querySelector('.codigo').value = codigoData.codigo || '';
                clone.querySelector('.descripcion').value = codigoData.descripcion || '';
                clone.querySelector('.cantidad').value = codigoData.cantidad || 1;
                clone.querySelector('.factura').value = codigoData.factura || '';
                clone.querySelector('.ordenCompra').value = codigoData.ordenCompra || '';
            }

            container.appendChild(clone);
            codigosCounter++;
        }
        function removeCodigoRow(button) {
            button.closest('.codigo-row').remove();
        }
        // ==================== GESTIÓN DE LOCALSTORAGE ====================
        function cargarEventosDesdeStorage() {
            const eventosStorage = JSON.parse(localStorage.getItem('calendar_events')) || [];
            const eventosCalendario = [];

            eventosStorage.forEach(eventoBD => {
                try {
                    // VERIFICAR que los datos existan
                    if (!eventoBD || !eventoBD.fecha_inicio) {
                        console.warn('⚠️ Evento sin fecha_inicio:', eventoBD);
                        return;
                    }

                    // Convertir fechas
                    const startDate = new Date(eventoBD.fecha_inicio);

                    let endDate = null;
                    if (eventoBD.fecha_fin && eventoBD.fecha_fin !== eventoBD.fecha_inicio) {
                        endDate = new Date(eventoBD.fecha_fin);
                    }

                    // DETERMINAR si es allDay (forma segura)
                    let isAllDay = false;

                    if (eventoBD.is_all_day !== undefined) {
                        // Usar el flag si existe
                        isAllDay = eventoBD.is_all_day === true;
                    } else if (typeof eventoBD.fecha_inicio === 'string') {
                        // Detectar por formato (sin 'T' o sin hora)
                        isAllDay = !eventoBD.fecha_inicio.includes('T') ||
                            eventoBD.fecha_inicio.endsWith('T00:00:00.000Z');
                    } else {
                        // Por defecto: si no tiene hora específica
                        isAllDay = startDate.getHours() === 0 &&
                            startDate.getMinutes() === 0 &&
                            startDate.getSeconds() === 0;
                    }

                    // Ajustar allDay a medio día
                    if (isAllDay) {
                        startDate.setHours(12, 0, 0, 0);
                        if (endDate) {
                            endDate.setHours(12, 0, 0, 0);
                        }
                    }

                    const eventoCalendario = {
                        id: eventoBD.id,
                        title: eventoBD.op_number || 'Sin título',
                        start: startDate,
                        allDay: isAllDay,
                        color: getColorByStatus(eventoBD.estado || 'pendiente'),
                        extendedProps: {
                            cliente: eventoBD.cliente || '',
                            cantidadReq: eventoBD.cantidad_req || 0,
                            fechaEntrega: eventoBD.fecha_entrega || '',
                            fechaProduccion: eventoBD.fecha_produccion || '',
                            estado: eventoBD.estado || 'pendiente',
                            codigos: JSON.parse(eventoBD.codigos_json || '[]'),
                            tipo: 'production'
                        }
                    };

                    if (endDate && endDate.getTime() !== startDate.getTime()) {
                        eventoCalendario.end = endDate;
                    }

                    eventosCalendario.push(eventoCalendario);

                } catch (error) {
                    console.error('❌ Error cargando evento:', error, 'Datos:', eventoBD);
                }
            });

            console.log(`📂 ${eventosCalendario.length} eventos cargados`);
            return eventosCalendario;
        }
        function guardarEventoEnStorage(eventoData, esActualizacion = false) {
            let eventos = JSON.parse(localStorage.getItem('calendar_events')) || [];

            // CONVERTIR fechas de forma segura
            const convertirFecha = (fecha) => {
                if (!fecha) return '';
                if (fecha instanceof Date) return fecha.toISOString();
                if (typeof fecha === 'string') return fecha;
                return new Date().toISOString();
            };

            const startISO = convertirFecha(eventoData.start);
            const endISO = eventoData.end ? convertirFecha(eventoData.end) : startISO;

            const eventoParaBD = {
                id: eventoData.id || 'event_' + Date.now(),
                op_number: eventoData.title || '',
                fecha_inicio: startISO,
                fecha_fin: endISO,
                is_all_day: eventoData.allDay === true,
                cliente: eventoData.extendedProps?.cliente || '',
                cantidad_req: eventoData.extendedProps?.cantidadReq || 0,
                fecha_entrega: eventoData.extendedProps?.fechaEntrega || '',
                fecha_produccion: eventoData.extendedProps?.fechaProduccion || '',
                estado: eventoData.extendedProps?.estado || 'pendiente',
                codigos_json: JSON.stringify(eventoData.extendedProps?.codigos || []),
                updated_at: new Date().toISOString()
            };

            const index = eventos.findIndex(e => e.id === eventoParaBD.id);

            if (esActualizacion && index !== -1) {
                eventoParaBD.created_at = eventos[index].created_at || new Date().toISOString();
                eventos[index] = eventoParaBD;
            } else if (!esActualizacion) {
                eventoParaBD.created_at = new Date().toISOString();
                eventos.push(eventoParaBD);
            }

            localStorage.setItem('calendar_events', JSON.stringify(eventos));
            return eventoParaBD;
        }


        // ==================== FUNCIONES DEL FORMULARIO ====================
        function loadEventToForm(event) {
            currentEvent = event;
            const props = event.extendedProps || {};

            // Configurar título
            document.getElementById('previewTitle').textContent = '📦 Editando: ' + event.title;
            document.getElementById('previewMode').textContent = '(Editando)';
            document.getElementById('currentEventId').value = event.id;

            // Datos básicos
            document.getElementById('opNumber').value = event.title;
            document.getElementById('cliente').value = props.cliente || '';
            document.getElementById('cantidadReq').value = props.cantidadReq || '';
            document.getElementById('fechaEntrega').value = props.fechaEntrega || '';
            document.getElementById('fechaProduccion').value = props.fechaProduccion || '';

            // Fecha y hora del evento
            if (event.start) {
                const fecha = event.start.toISOString().split('T')[0];
                document.getElementById('eventDate').value = fecha;

                if (!event.allDay && event.start) {
                    const horaInicio = event.start.toTimeString().substring(0, 5);
                    document.getElementById('startTime').value = horaInicio;
                } else {
                    document.getElementById('startTime').value = '00:00';
                }
            }

            if (event.end && !event.allDay) {
                const horaFin = event.end.toTimeString().substring(0, 5);
                document.getElementById('endTime').value = horaFin;
            } else {
                document.getElementById('endTime').value = '01:00';
            }

            // Estado
            document.getElementById('eventStatus').value = props.estado || 'pendiente';

            // Productos
            document.getElementById('codigosList').innerHTML = '';
            codigosCounter = 0;
            const codigos = props.codigos || [];
            if (codigos.length > 0) {
                codigos.forEach(c => addCodigoRow(c));
            } else {
                addCodigoRow();
            }

            // Botones
            document.getElementById('saveBtn').style.display = 'none';
            document.getElementById('updateBtn').style.display = 'block';
            document.getElementById('deleteBtn').style.display = 'block';
            document.getElementById('cancelBtn').style.display = 'block';
        }
        function resetToNewEvent() {
            currentEvent = null;

            // Resetear formulario
            document.getElementById('previewTitle').textContent = '📦 Nueva Orden de Producción';
            document.getElementById('previewMode').textContent = '(Nuevo)';
            document.getElementById('currentEventId').value = '';

            document.getElementById('opNumber').value = 'OP-';
            document.getElementById('cliente').value = '';
            document.getElementById('cantidadReq').value = '';
            document.getElementById('fechaEntrega').value = '';
            document.getElementById('fechaProduccion').value = new Date().toISOString().split('T')[0];
            document.getElementById('eventDate').value = new Date().toISOString().split('T')[0];
            document.getElementById('startTime').value = '00:00';
            document.getElementById('endTime').value = '01:00';
            document.getElementById('eventStatus').value = 'pendiente';

            document.getElementById('codigosList').innerHTML = '';
            codigosCounter = 0;
            addCodigoRow();

            // Botones
            document.getElementById('saveBtn').style.display = 'block';
            document.getElementById('updateBtn').style.display = 'none';
            document.getElementById('deleteBtn').style.display = 'none';
            document.getElementById('cancelBtn').style.display = 'none';
        }
        // ==================== CREAR EVENTO (allDay por defecto) ====================
        function saveProductionEvent() {
            // 1. Validar OP
            const opNumber = document.getElementById('opNumber').value.trim();
            if (!opNumber) { showToast('❌ Ingresa un número de OP', 'danger'); return; }

            // 2. ✅ ASEGURAR QUE SE USA LA FECHA SELECCIONADA
            let fechaEvento;

            if (ultimaFechaSeleccionada) {
                // Usar la fecha seleccionada por el usuario
                fechaEvento = ultimaFechaSeleccionada.toISOString().split('T')[0];
                console.log('📅 Usando fecha seleccionada:', fechaEvento);
            } else {
                // Fallback a la fecha del formulario
                fechaEvento = document.getElementById('eventDate').value;
                console.log('⚠️ Usando fecha del formulario:', fechaEvento);
            }

            // Validar que tenemos fecha
            if (!fechaEvento) {
                showToast('❌ No hay fecha seleccionada. Haz click en una fecha del calendario primero.', 'danger');
                return;
            }

            // 3. Obtener códigos
            const codigos = getCodigosFromForm();
            if (codigos.length === 0) { showToast('❌ Agrega al menos un producto', 'danger'); return; }

            // 4. Crear evento allDay por defecto EN LA FECHA SELECCIONADA
            const eventoData = {
                title: opNumber,
                start: fechaEvento, // ✅ Fecha seleccionada por el usuario
                allDay: true,
                color: getColorByStatus('pendiente'),
                extendedProps: {
                    cliente: document.getElementById('cliente').value || '',
                    cantidadReq: parseInt(document.getElementById('cantidadReq').value) || 0,
                    fechaEntrega: document.getElementById('fechaEntrega').value || '',
                    fechaProduccion: document.getElementById('fechaProduccion').value || '',
                    estado: 'pendiente',
                    codigos: codigos,
                    tipo: 'production'
                }
            };

            // 5. Agregar al calendario
            const nuevoEvento = calendar.addEvent(eventoData);
            const eventId = 'event_' + Date.now();
            nuevoEvento.setProp('id', eventId);

            // 6. Guardar en localStorage
            guardarEventoEnStorage({
                id: eventId,
                ...eventoData
            }, false);

            // 7. Cargar para editar
            loadEventToForm(nuevoEvento);

            // 8. Feedback al usuario
            showToast(`✅ OP ${escapeHtml(opNumber)} creada para el ${new Date(fechaEvento).toLocaleDateString('es-ES')}`, 'success');

            // 9. Actualizar y limpiar
            updateTodayEvents();

            // 10. Limpiar selección visual
            setTimeout(() => {
                document.querySelectorAll('.fc-day-selected, .fc-timegrid-col-selected').forEach(el => {
                    el.classList.remove('fc-day-selected', 'fc-day-highlight',
                        'fc-timegrid-col-selected', 'fc-timegrid-highlight');
                });
                ultimaFechaSeleccionada = null;
            }, 500);
        }
        // ==================== ACTUALIZAR EVENTO (solo datos del formulario) ====================
        function updateProductionEvent() {
            if (!currentEvent) { showToast('❌ No hay evento seleccionado', 'danger'); return; }

            const opNumber = document.getElementById('opNumber').value.trim();
            if (!opNumber) { showToast('❌ Ingresa un número de OP', 'danger'); return; }

            const codigos = getCodigosFromForm();
            if (codigos.length === 0) { showToast('❌ Agrega al menos un producto', 'danger'); return; }

            // ✅ NO actualizar fecha/hora - solo datos del formulario
            const eventoData = {
                id: currentEvent.id,
                title: opNumber,
                start: currentEvent.start, // ✅ Mantener fecha/hora actual
                end: currentEvent.end, // ✅ Mantener fecha/hora actual
                allDay: currentEvent.allDay, // ✅ Mantener tipo
                color: getColorByStatus(document.getElementById('eventStatus').value),
                extendedProps: {
                    cliente: document.getElementById('cliente').value || '',
                    cantidadReq: parseInt(document.getElementById('cantidadReq').value) || 0,
                    fechaEntrega: document.getElementById('fechaEntrega').value || '',
                    fechaProduccion: document.getElementById('fechaProduccion').value || '',
                    estado: document.getElementById('eventStatus').value,
                    codigos: codigos,
                    tipo: 'production'
                }
            };

            // Actualizar solo propiedades del evento (no fecha/hora)
            currentEvent.setProp('title', eventoData.title);
            currentEvent.setProp('color', eventoData.color);

            // Actualizar extendedProps
            Object.keys(eventoData.extendedProps).forEach(key => {
                currentEvent.setExtendedProp(key, eventoData.extendedProps[key]);
            });

            // Guardar en localStorage
            guardarEventoEnStorage(eventoData, true);

            showToast(`✅ OP ${escapeHtml(opNumber)} actualizada`, 'success');
            updateTodayEvents();
            resetToNewEvent();
        }
        // ==================== ELIMINAR EVENTO ====================
        function deleteCurrentEvent() {
            if (!currentEvent) return;

            if (confirm(`¿Estás seguro de eliminar la OP "${currentEvent.title}"?`)) {
                // Eliminar de localStorage
                let eventos = JSON.parse(localStorage.getItem('calendar_events')) || [];
                eventos = eventos.filter(e => e.id !== currentEvent.id);
                localStorage.setItem('calendar_events', JSON.stringify(eventos));

                // Eliminar del calendario
                currentEvent.remove();

                showToast('🗑️ Evento eliminado', 'success');
                resetToNewEvent();
                updateTodayEvents();
            }
        }
  
        // ==================== FUNCIONES ADICIONALES ====================
        function updateTodayEvents() {
            const today = new Date().toDateString();
            const events = calendar.getEvents();
            const todayEvents = events.filter(event =>
                event.start && event.start.toDateString() === today
            );

            const container = document.getElementById('todayEvents');
            if (todayEvents.length === 0) {
                container.innerHTML = `
            <div class="list-group-item py-3 text-center text-muted">
                <small>No hay eventos para hoy</small>
            </div>
            `;
                return;
            }

            let html = '';
            todayEvents.forEach(event => {
                // ✅ FORMATO 24 HORAS
                const time = event.allDay ? 'Todo el día' :
                    event.start.toLocaleTimeString('es-ES', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false // ✅ 24 horas
                    });

                const estado = event.extendedProps?.estado || 'pendiente';
                const estadoColor = estado === 'completado' ? 'success' :
                    estado === 'en_progreso' ? 'primary' :
                    estado === 'retrasado' ? 'danger' : 'warning';

                const safeTitle = escapeHtml(event.title);
                const safeEstado = escapeHtml(estado);
                const safeId = escapeHtml(event.id);

                html += `
            <div class="list-group-item list-group-item-action py-2" 
                 onclick="loadEventById('${safeId}')" 
                 style="cursor: pointer;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-primary">${time}</small>
                        <div>${safeTitle}</div>
                        <span class="badge bg-${estadoColor}">${safeEstado}</span>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" 
                            onclick="event.stopPropagation(); removeEvent('${safeId}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
          `;
            });

            container.innerHTML = html;
        }
        function loadEventById(eventId) {
            const event = calendar.getEventById(eventId);
            if (event) {
                loadEventToForm(event);
                document.querySelector('.sidebar-actions').scrollTop = 0;
            }
        }
        function removeEvent(eventId) {
            const event = calendar.getEventById(eventId);
            if (event && confirm(`¿Eliminar "${event.title}"?`)) {
                // Eliminar de localStorage
                let eventos = JSON.parse(localStorage.getItem('calendar_events')) || [];
                eventos = eventos.filter(e => e.id !== eventId);
                localStorage.setItem('calendar_events', JSON.stringify(eventos));

                // Eliminar del calendario
                event.remove();

                // Si es el evento en edición, resetear
                if (currentEvent && currentEvent.id === eventId) {
                    resetToNewEvent();
                }

                updateTodayEvents();
            }
        }
        // ==================== NAVEGAR A VISTA DE FECHA SELECCIONADA ====================
        function irAVistaFecha(fecha, vista = 'diaria') {
            if (!fecha) {
                showToast('❌ Primero selecciona una fecha haciendo click en el calendario', 'warning');
                return;
            }

            const fechaObj = new Date(fecha);

            // Validar que sea una fecha válida
            if (isNaN(fechaObj.getTime())) {
                showToast('❌ Fecha no válida', 'danger');
                return;
            }

            console.log(`🚀 Navegando a vista ${vista} de:`, fechaObj.toLocaleDateString('es-ES'));

            // Cambiar a la vista solicitada
            switch (vista) {
                case 'diaria':
                    calendar.changeView('timeGridDay', fechaObj);
                    console.log('📅 Vista diaria cargada');
                    break;

                case 'semanal':
                    calendar.changeView('timeGridWeek', fechaObj);
                    console.log('📅 Vista semanal cargada');
                    break;

                case 'mensual':
                    calendar.changeView('dayGridMonth', fechaObj);
                    console.log('📅 Vista mensual cargada');
                    break;

                default:
                    showToast('❌ Vista no válida', 'danger');
                    return;
            }

            // Resaltar la fecha en la nueva vista
            setTimeout(() => {
                mostrarSeleccionFecha(fecha, vista === 'mensual' ? 'mensual' : 'semanal');
            }, 300);

            // Feedback al usuario
            mostrarNotificacionNavegacion(fechaObj, vista);
        }
        // ==================== NOTIFICACIÓN DE NAVEGACIÓN ====================
        function mostrarNotificacionNavegacion(fecha, vista) {
            let notificacion = document.getElementById('navegacion-notif');

            if (!notificacion) {
                notificacion = document.createElement('div');
                notificacion.id = 'navegacion-notif';
                notificacion.className = 'alert alert-success alert-dismissible fade show';
                notificacion.style.cssText = `
            position: fixed;
            top: 110px;
            right: 20px;
            z-index: 9999;
            max-width: 300px;
             `;

                document.body.appendChild(notificacion);
            }

            const textoVista = {
                'diaria': 'vista diaria',
                'semanal': 'vista semanal',
                'mensual': 'vista mensual'
            } [vista] || 'vista';

            notificacion.innerHTML = `
          <strong>🚀 Navegando a ${textoVista}</strong><br>
           <small>${fecha.toLocaleDateString('es-ES', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
             })}</small>
          <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
         `;

            setTimeout(() => notificacion.remove(), 2500);
        }

              // ==================== INICIALIZACIÓN DEL CALENDARIO ====================
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMultiMonth today'
                },

                // ✅ CONFIGURACIÓN 24 HORAS
                // Formato de hora en 24h
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false, // ✅ Esto cambia a formato 24h
                    meridiem: false
                },

                // Formato de etiquetas de tiempo
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false, // ✅ 24 horas
                    meridiem: false
                },

                // Vistas con configuración 24h
                views: {
                    dayGridMonth: {
                        dayMaxEventRows: 3,
                        dayMaxEvents: true,
                        // Eventos en vista mensual mostrarán hora en 24h si tienen
                        eventTimeFormat: {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        }
                    },
                    listMultiMonth: {
                        type: 'list',
                        duration: {
                            months: 3
                        },
                        buttonText: '3 Meses',
                        // Formato 24h en lista
                        listDayFormat: {
                            weekday: 'short',
                            day: 'numeric',
                            month: 'short'
                        }
                    },
                    timeGridWeek: {
                        allDaySlot: true,
                        allDayText: 'Todo el día',
                        slotDuration: '01:00:00',
                        slotLabelInterval: '01:00:00',
                        slotMinTime: '00:00:00', // ✅ Empieza a medianoche
                        slotMaxTime: '24:00:00', // ✅ Termina a medianoche
                        slotLabelFormat: {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false // ✅ 24 horas
                        }
                    },
                    timeGridDay: {
                        allDaySlot: true,
                        allDayText: 'Todo el día',
                        slotDuration: '00:30:00',
                        slotMinTime: '00:00:00', // ✅ 0:00
                        slotMaxTime: '24:00:00', // ✅ 24:00
                        slotLabelFormat: {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false // ✅ 24 horas
                        }
                    }
                },

                // Configuración general
                allDaySlot: true,
                allDayText: 'Todo el día',
                height: 'auto',
                contentHeight: 500,
                editable: true,
                droppable: true,
                events: cargarEventosDesdeStorage(),

                // ✅ ACTUALIZAR eventDrop PARA HORAS 24H
                eventDrop: function(info) {
                    console.log('🎮 EventDrop - Vista:', info.view.type);

                    try {
                        // 1. DETECTAR SI CAMBIÓ DE FECHA
                        let cambioDeFecha = false;

                        if (info.oldEvent && info.oldEvent.start) {
                            cambioDeFecha = info.oldEvent.start.toDateString() !== info.event.start
                                .toDateString();
                        } else {
                            cambioDeFecha = true;
                        }

                        // 2. DETERMINAR TIPO DE MOVIMIENTO
                        const esVistaMensual = info.view.type === 'dayGridMonth';
                        const esVistaHoraria = info.view.type.includes('timeGrid');

                        // 3. APLICAR REGLAS
                        if (esVistaMensual) {
                            // ✅ VISTA MENSUAL: Siempre a allDay
                            const fechaAllDay = new Date(info.event.start);
                            fechaAllDay.setHours(12, 0, 0, 0);

                            info.event.setAllDay(true);
                            info.event.setStart(fechaAllDay);
                            info.event.setEnd(null);

                            console.log('📅 Movido en vista mensual -> allDay');

                        } else if (esVistaHoraria && info.event.allDay) {
                            // ✅ De allDay a horario (formato 24h)
                            const hora = 8; // 08:00 en formato 24h
                            const horaFin = hora + 1; // 09:00

                            const startDate = new Date(info.event.start);
                            startDate.setHours(hora, 0, 0, 0);

                            const endDate = new Date(startDate);
                            endDate.setHours(horaFin, 0, 0, 0);

                            info.event.setAllDay(false);
                            info.event.setStart(startDate);
                            info.event.setEnd(endDate);

                            console.log('⏰ AllDay -> Horario 24h:', hora + ':00 - ' + horaFin + ':00');
                        }

                        // 4. CAMBIAR ESTADO SI HUBO CAMBIO
                        if (esVistaHoraria) {
                            const estadoActual = info.event.extendedProps?.estado || 'pendiente';

                            if (estadoActual === 'pendiente') {
                                info.event.setExtendedProp('estado', 'en_progreso');
                                info.event.setProp('color', getColorByStatus('en_progreso'));
                                console.log('🔵 Estado -> en_progreso');
                            }
                        }

                        // 5. GUARDAR CAMBIOS
                        guardarEventoEnStorage(info.event, true);

                        // 6. ACTUALIZAR INTERFAZ
                        if (currentEvent && currentEvent.id === info.event.id) {
                            setTimeout(() => {
                                loadEventToForm(info.event);
                            }, 100);
                        }

                        updateTodayEvents();

                    } catch (error) {
                        console.error('❌ Error en eventDrop:', error);
                        if (info.revert && typeof info.revert === 'function') {
                            info.revert();
                        }
                    }
                },

                // ==================== ACTUALIZAR dateClick ====================
                // En la configuración del calendario:
                // En la configuración del calendario, añade:
                dateClick: function(info) {
                    console.log('🖱️ Click en fecha:', info.dateStr);

                    // Guardar fecha para doble click
                    if (!this.ultimoClick) this.ultimoClick = {
                        time: 0,
                        date: null
                    };

                    const ahora = Date.now();
                    const esDobleClick = (ahora - this.ultimoClick.time < 300) &&
                        this.ultimoClick.date === info.dateStr;

                    this.ultimoClick = {
                        time: ahora,
                        date: info.dateStr
                    };

                    if (esDobleClick) {
                        // Doble click: ir a vista diaria automáticamente
                        console.log('🖱️🖱️ Doble click detectado');
                        irAVistaFecha(info.date, 'diaria');
                        return;
                    }

                    // Click simple: seleccionar fecha normalmente
                    const tipoVista = info.view.type === 'dayGridMonth' ? 'mensual' : 'semanal';
                    mostrarSeleccionFecha(info.date, tipoVista);
                    document.getElementById('eventDate').value = info.dateStr;
                    resetToNewEvent();

                    setTimeout(() => {
                        document.getElementById('opNumber').focus();
                    }, 100);
                },

                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    loadEventToForm(info.event);
                },

                eventResize: function(info) {
                    try {
                        console.log('📏 EventResize');

                        const estadoActual = info.event.extendedProps?.estado || 'pendiente';
                        if (estadoActual === 'pendiente') {
                            info.event.setExtendedProp('estado', 'en_progreso');
                            info.event.setProp('color', getColorByStatus('en_progreso'));
                        }

                        guardarEventoEnStorage(info.event, true);

                        if (currentEvent && currentEvent.id === info.event.id) {
                            loadEventToForm(info.event);
                        }

                        updateTodayEvents();
                    } catch (error) {
                        console.error('❌ Error en eventResize:', error);
                    }
                }
            });

            calendar.render();
            updateTodayEvents();
            resetToNewEvent();
        });
    </script>
@endsection
