<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectTeam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        $query = Project::query();
        // Filtrar por nombre si se proporciona
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
    
        // Filtrar por estado si se proporciona
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
    
        // Filtrar por fecha de creación si se proporciona
        if ($request->has('created_at')) {
            $date = date('Y-m-d', strtotime($request->created_at)); // Asegúrate de que el formato de fecha coincide con tu base de datos
            $query->whereDate('created_at', '=', $date);
        }
    
        // Obtener los proyectos filtrados
        return $query->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
    
        $userLogged = Auth::user();
        if(!$userLogged) {
            return redirect()->route('projects.index')->with('error', 'Usuario no encontrado.');
        }
    
        // Crear el proyecto con los datos validados
        $project = new Project();
        $project->name = $validatedData['name'];
        $project->status = $validatedData['status'];
        $project->description = $validatedData['description'];
        $project->start_date = $validatedData['start_date'];
        $project->end_date = $validatedData['end_date'];
        $project->id_user_creator = $userLogged->id;
    
        if($project->save()) {
            $projectTeam = new ProjectTeam();
            $projectTeam->id_project = $project->id;
            $projectTeam->id_user = $userLogged->id;
            $projectTeam->status = 'Active';
            $projectTeam->id_user_creator = $userLogged->id;
            $projectTeam->is_admin = true;
            $projectTeam->save();
            // Si el proyecto se guarda correctamente, se envía un mensaje de éxito
            return redirect()->route('projects.index')->with('success', 'Proyecto creado con éxito.');
        } else {
            // Si el proyecto no se guarda correctamente, se envía un mensaje de error
            return redirect()->route('projects.index')->with('error', 'Error al crear el proyecto.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function getById(string $id)
    {
        return Project::where('id', $id)->where('status', '!=', 'deleted')->first();
    }

    public function addUserToProjectTeam(Request $request, string $projectId)
    {
        // Validar la entrada (asegúrate de ajustar los nombres de los campos según tu formulario)
        $validated = $request->validate([
            'email' => 'required|string', // Asegura que el user_id enviado exista en la tabla de usuarios
            'is_admin' => 'required|boolean', // Asegura que el is_admin enviado sea un booleano
        ]);

        $userLogged = Auth::user();
        if(!$userLogged) {
            return redirect()->route('projects.index')->with('error', 'Usuario no encontrado.');
        }

        $user = User::where('email', $email)->where('status', '!=', 'deleted')->first();
        if(!$user) {
            return redirect()->route('projects.index')->with('error', 'Usuario no encontrado.');
        }

        // Buscar si ya existe una entrada para este proyecto y usuario
        $projectTeamEntry = ProjectTeam::where('project_id', $projectId)->where('user_id', $validated['user_id'])->first();
    
        if (!$projectTeamEntry) {
            $projectTeamEntry = new ProjectTeam();
            $projectTeamEntry->id_project = $projectId;
            $projectTeamEntry->id_user = $user->id;
            $projectTeamEntry->status = 'Active';
            $projectTeamEntry->id_user_creator = $userLogged->id;
            $projectTeamEntry->is_admin = $validated['is_admin'];

            if ($projectTeamEntry->save()) {
                // Si se guarda correctamente, enviar un mensaje de éxito
                return redirect()->route('projects.show', $projectId)->with('success', 'Usuario agregado al equipo del proyecto con éxito.');
            } else {
                // Si hay un error al guardar, enviar un mensaje de error
                return redirect()->route('projects.show', $projectId)->with('error', 'Error al agregar el usuario al equipo del proyecto.');
            }
        } else {
            // Si la entrada ya existe, posiblemente quieras enviar un mensaje diferente o manejarlo de otra manera
            return redirect()->route('projects.show', $projectId)->with('info', 'El usuario ya es parte del equipo de este proyecto.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'max:255',
            'description',
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date',
        ]);

        $userLogged = Auth::user();
        if(!$userLogged) {
            return redirect()->route('projects.index')->with('error', 'Usuario no encontrado.');
        }
    
        // Buscar el proyecto por ID
        $project = Project::where('id', $id)->where('status', '!=', 'deleted')->first();
    
        // Verificar si el proyecto fue encontrado
        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Proyecto no encontrado.');
        }
    
        // Actualizar el proyecto con los datos validados
        $project->name = $validatedData['name'];
        $project->description = $validatedData['description'];
        $project->start_date = $validatedData['start_date'];
        $project->end_date = $validatedData['end_date'];
        $project->id_user_modification = $userLogged->id;

        // Guardar el proyecto actualizado en la base de datos
        if($project->save()) {
            // Si el proyecto se actualiza correctamente, se envía un mensaje de éxito
            return redirect()->route('projects.index')->with('success', 'Proyecto actualizado con éxito.');
        } else {
            // Si hay un error al guardar, enviar un mensaje de error
            return redirect()->route('projects.index')->with('error', 'Error al actualizar el proyecto.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userLogged = Auth::user();
        if(!$userLogged) {
            return redirect()->route('projects.index')->with('error', 'Usuario no encontrado.');
        }

        $project = Project::where('id', $id)->where('status', '!=', 'deleted')->first();
        
        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Proyecto no encontrado.');
        }
    
        // Actualizar el proyecto con los datos validados
        $project->status = 'Deleted';
        $project->id_user_modification = $userLogged->id;

        if($project->save()) {
            // Si el proyecto se actualiza correctamente, se envía un mensaje de éxito
            return redirect()->route('projects.index')->with('success', 'Proyecto actualizado con éxito.');
        } else {
            // Si hay un error al guardar, enviar un mensaje de error
            return redirect()->route('projects.index')->with('error', 'Error al actualizar el proyecto.');
        }
    }
}
