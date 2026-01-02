<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CalendarEventController extends Controller
{
    /**
     * Obtener eventos para el calendario
     */
    public function index(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            
            if ($start && $end) {
                $events = CalendarEvent::getEventsInRange($start, $end);
            } else {
                $events = CalendarEvent::all();
            }
            
            return response()->json(
                $events->map(function ($event) {
                    return $event->toEventArray();
                })
            );
            
        } catch (\Exception $e) {
            Log::error('Error al obtener eventos: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar eventos'], 500);
        }
    }

    /**
     * Guardar nuevo evento
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'start' => 'required|date',
                'end' => 'nullable|date',
                'all_day' => 'boolean',
                'color' => 'nullable|string',
                'op_number' => 'required|string|max:50',
                'cliente' => 'nullable|string|max:255',
                'cantidad_req' => 'nullable|integer|min:0',
                'fecha_entrega' => 'nullable|date',
                'fecha_produccion' => 'nullable|date',
                'estado' => 'nullable|string|in:pendiente,en_progreso,completado,cancelado',
                'codigos' => 'nullable|array'
            ]);

            $event = CalendarEvent::create($validated);
            
            return response()->json([
                'success' => true,
                'event' => $event->toEventArray(),
                'message' => 'Evento guardado correctamente'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al guardar evento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al guardar el evento'
            ], 500);
        }
    }

    /**
     * Actualizar evento existente
     */
    public function update(Request $request, $id)
    {
        try {
            $event = CalendarEvent::findOrFail($id);
            
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'start' => 'sometimes|date',
                'end' => 'nullable|date',
                'all_day' => 'sometimes|boolean',
                'color' => 'nullable|string',
                'op_number' => 'sometimes|string|max:50',
                'cliente' => 'nullable|string|max:255',
                'cantidad_req' => 'nullable|integer|min:0',
                'fecha_entrega' => 'nullable|date',
                'fecha_produccion' => 'nullable|date',
                'estado' => 'nullable|string|in:pendiente,en_progreso,completado,cancelado',
                'codigos' => 'nullable|array'
            ]);

            $event->update($validated);
            
            return response()->json([
                'success' => true,
                'event' => $event->toEventArray(),
                'message' => 'Evento actualizado correctamente'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar evento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al actualizar el evento'
            ], 500);
        }
    }

    /**
     * Eliminar evento
     */
    public function destroy($id)
    {
        try {
            $event = CalendarEvent::findOrFail($id);
            $event->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Evento eliminado correctamente'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al eliminar evento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al eliminar el evento'
            ], 500);
        }
    }

    /**
     * Obtener un evento específico
     */
    public function show($id)
    {
        try {
            $event = CalendarEvent::findOrFail($id);
            return response()->json($event->toEventArray());
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }
    }
    public function updateDateTime(Request $request, $id)
{
    try {
        $event = CalendarEvent::findOrFail($id);
        
        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'nullable|date',
            'all_day' => 'boolean',
            'estado' => 'nullable|string|in:pendiente,en_progreso,completado,cancelado'
        ]);
        
        $updateData = [
            'start' => $validated['start'],
            'end' => $validated['end'] ?? $event->end,
            'all_day' => $validated['all_day'] ?? $event->all_day
        ];
        
        // Si viene estado, actualizarlo también
        if (isset($validated['estado'])) {
            $updateData['estado'] = $validated['estado'];
        }
        
        $event->update($updateData);
        
        return response()->json([
            'success' => true,
            'message' => 'Evento actualizado'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error updateDateTime: ' . $e->getMessage());
        return response()->json(['success' => false], 500);
    }
}
}