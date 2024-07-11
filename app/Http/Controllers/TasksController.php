<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        $query = Task::query();

        // Filtrar por nombre si se proporciona
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
    
        // Filtro por asignado a (suponiendo que 'assigned_to' es un campo en tu tabla de tareas)
        if ($request->has('id_user')) {
            $query->where('id_user', $request->input('id_user'));
        }
    
        // Filtro por fecha de creación
        if ($request->has('created_at')) {
            $date = $request->input('created_at');
            $query->whereDate('created_at', '=', $date);
        }
    
        // Obtener las tareas filtradas
        return $query->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Validación de los datos recibidos
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255', // Nombre de la tarea
            'description' => 'string', // Descripción de la tarea
            'due_date' => 'required|date', // Fecha de vencimiento de la tarea
            'id_project' => 'required|exists:projects,id', // ID del proyecto (asegurarse de que el proyecto exista)
            'id_user' => 'required|int', // Usuarios asignados (IDs de usuarios)
        ]);
    
        // Si la validación falla, redirigir de vuelta con errores
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userLogged = Auth::user();
        if(!$userLogged) {
            return redirect()->route('projects.index')->with('error', 'Usuario no encontrado.');
        }
    
        // Creación de la tarea
        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->id_project = $request->id_project;
        $task->id_user_creator = $userLogged->id;

        if($request->id_user) {
            $task->id_user = $request->id_user;
        } else {
            $task->id_user = $userLogged->id;
        }

        if($task->save()) {
            return redirect()->route('tasks.index')->with('success', 'Tarea creada con éxito.');
        } else {
            return redirect()->route('tasks.index')->with('error', 'Error al crear la tarea.');
        }

        // Redirigir a alguna ruta, por ejemplo, a la lista de tareas con un mensaje de éxito
        return redirect()->route('tasks.index')->with('success', 'Tarea creada y asignada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function getById(string $id)
    {
        $task = Task::where('id', $id)->where('status', '!=', 'deleted')->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validación de los datos recibidos
        $validator = Validator::make($request->all(), [
            'description' => 'string', // Descripción de la tarea
            'due_date' => 'date', // Fecha de vencimiento de la tarea
            'status' => 'string', // ID del proyecto (asegurarse de que el proyecto exista)
        ]);

        $userLogged = Auth::user();
        if (!$userLogged) {
            return redirect()->route('projects.index')->with('error', 'Usuario no encontrado.');
        }

        $task = Task::where('id', $id)->where('status', '!=', 'deleted')->first();
        if (!$task) {
            return redirect()->route('tasks.index')->with('error', 'Tarea no encontrada.');
        }
    
        // Actualizar detalles de la tarea
        $task->description = $request->input('description', $task->description); // Si no se proporciona una nueva descripción, se mantiene la actual
        $task->due_date = $request->input('due_date', $task->due_date); // Si no se proporciona una nueva fecha de vencimiento, se mantiene la actual
        $task->status = $request->input('status', $task->status); // Si no se proporciona un nuevo estado, se mantiene el actual
        $task->id_user_modification = $userLogged->id;

        if ($task->save()) {
            return redirect()->route('tasks.index')->with('success', 'Tarea actualizada con éxito.');
        } else {
            return redirect()->route('tasks.index')->with('error', 'Error al actualizar la tarea.');
        }
    }

    public function assignTask(Request $request, string $id, string $id_user)
    {
        $userLogged = Auth::user();
        if (!$userLogged) {
            return redirect()->route('tasks.index')->with('error', 'Usuario no autenticado.');
        }
        $task = Task::where('id', $id)->where('status', '!=', 'deleted')->first();
        if (!$task) {
            return redirect()->route('tasks.index')->with('error', 'Tarea no encontrada.');
        }
        
        $task->id_user = $id_user;
        $task->id_user_modification = $userLogged->id;

        if ($task->save()) {
            return redirect()->route('tasks.index')->with('success', 'Tarea asignada con éxito.');
        } else {
            return redirect()->route('tasks.index')->with('error', 'Error al asignar la tarea.');
        }
    }

    public function complete(string $id)
    {
        $userLogged = Auth::user();
        if (!$userLogged) {
            return redirect()->route('tasks.index')->with('error', 'Usuario no autenticado.');
        }
        $task = Task::where('id', $id)->where('status', '!=', 'deleted')->first();
        if (!$task) {
            return redirect()->route('tasks.index')->with('error', 'Tarea no encontrada.');
        }

        $task->status = 'Completed';
        $task->id_user_modification = $userLogged->id;

        if ($task->save()) {
            return redirect()->route('tasks.index')->with('success', 'Tarea asignada con éxito.');
        } else {
            return redirect()->route('tasks.index')->with('error', 'Error al asignar la tarea.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userLogged = Auth::user();
        if (!$userLogged) {
            return redirect()->route('projects.index')->with('error', 'Usuario no encontrado.');
        }

        $task = Task::where('id', $id)->where('status', '!=', 'deleted')->first();
        if (!$task) {
            return redirect()->route('tasks.index')->with('error', 'Tarea no encontrada.');
        }
    
        $task->status = 'Deleted';
        $task->id_user_modification = $userLogged->id;

        // Eliminar la tarea
        if ($task->save()) {
            return redirect()->route('tasks.index')->with('success', 'Tarea eliminada con éxito.');
        } else {
            return redirect()->route('tasks.index')->with('error', 'Error al eliminar la tarea.');
        }
    }
}
