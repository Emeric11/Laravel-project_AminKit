<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel Admin')</title>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel Admin')</title>
    
    <!-- AdminKit LOCAL -->
   
      <link href="{{ asset('adminkit/dist/css/app.css') }}" rel="stylesheet">
    
    @stack('fullcalendar-css')
    <style>
        :root {
            --sidebar-bg: #3b82f6;
            --sidebar-text: white;
        }
        .sidebar {
            background: var(--sidebar-bg) !important;
            color: var(--sidebar-text) !important;
        }
        .nav-link {
            color: var(--sidebar-text) !important;
            border-radius: 0.375rem;
            margin: 0.125rem 0.5rem;
        }
        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.15) !important;
        }
        .sidebar-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 0.5rem;
            margin-bottom: 1rem;
        }
        .sidebar-header h3 {
            color: white;
            font-weight: 600;
            font-size: 1.25rem;
            margin: 0;
            text-align: center;
        }
        #calendar {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .fc .fc-button-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        .fc .fc-button-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        .fc-day-today {
            background-color: rgba(59, 130, 246, 0.1) !important;
        }
        .fc-event {
            border-radius: 0.25rem;
            padding: 0.125rem 0.25rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h3>📅 Gestor Tareas</h3>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" 
                       href="{{ url('/admin') }}">
                        <span style="margin-right: 0.5rem;">📊</span> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/calendar') ? 'active' : '' }}" 
                       href="{{ url('/admin/calendar') }}">
                        <span style="margin-right: 0.5rem;">📅</span> Calendario
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/tasks') ? 'active' : '' }}" 
                       href="{{ url('/admin/tasks') }}">
                        <span style="margin-right: 0.5rem;">✅</span> Tareas
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <main class="main">
            <nav class="navbar navbar-expand navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <h4 class="mb-0 text-dark">@yield('page-title', 'Dashboard')</h4>
                    <div class="navbar-nav">
                        <span class="nav-link">{{ now()->format('d/m/Y') }}</span>
                    </div>
                </div>
            </nav>
            
            <div class="main-content">
            <div class="main-content">
                <div class="container-fluid px-4 py-4">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- AdminKit JS LOCAL -->
         <script src="{{ asset('adminkit/dist/js/app.js') }}"></script>
    
    @stack('fullcalendar-js')
    @yield('scripts')
</body>
</html>