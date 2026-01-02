<?php
use App\Http\Controllers\CalendarEventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas del calendario FUERA de /admin para que funcionen con tu JS
Route::prefix('api')->group(function () {
    Route::get('/calendar/events', [CalendarEventController::class, 'index']);
    Route::post('/calendar/events', [CalendarEventController::class, 'store']);
    Route::patch('/calendar/events/{id}', [CalendarEventController::class, 'update']);
    Route::patch('/calendar/events/{id}/datetime', [CalendarEventController::class, 'updateDateTime']); // ← NUEVA
    Route::delete('/calendar/events/{id}', [CalendarEventController::class, 'destroy']);
    Route::get('/calendar/events/{id}', [CalendarEventController::class, 'show']);
});

// Ruta de la vista del calendario (puede estar en /admin)
Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    });
    
    Route::get('/calendar', function () {
        return view('admin.calendar');
    });
});