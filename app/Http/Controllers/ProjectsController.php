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
        $userLogged = Auth::user();
        if(!$userLogged) {
            return redirect()->route('auth.login')->with('error', 'Usuario no encontrado.');
        }

        $projectTeamIds = ProjectTeam::where('id_user', $userLogged->id)->pluck('id_project');
        if($projectTeam) {
            $query = Project::query();
            // Filtrar por nombre si se proporciona
            if ($request->has('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
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
            $query->whereIn('id', $projectTeamIds);
            return $query->get();
        } else {
            return redirect()->route('projects')>with('error', 'No tienes permiso para ver este proyecto.');
        }
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
        $userLogged = Auth::user();
        if(!$userLogged) {
            return redirect()->route('auth.login')->with('error', 'Usuario no encontrado.');
        }

        $projectTeam = ProjectTeam::where('id_project', $id)->where('id_user', $userLogged->id)->first();

        if($projectTeam->id_user == $userLogged->id) {
            return Project::where('id', $id)->where('status', '!=', 'deleted')->first();
        } else {
            return redirect()->route('projects')>with('error', 'No tienes permiso para ver este proyecto.');
        }
    }

    public function checkUserIsAdmin(string $projectId, string $userId)
    {
        $projectTeam = ProjectTeam::where('id_project', $projectId)
            ->where('id_user', $userId)
            ->where('status', '!=', 'Deleted')
            ->first();
        if($projectTeam) {
            return $projectTeam->is_admin;
        } else {
            return false;
        }
    }

    public function getAllTeamMembers(string $projectId)
    {
        $projectTeam = ProjectTeam::where('id_project', $projectId)->where('status', '!=', 'Deleted')->get();
        $users = User::whereIn('id', $projectTeam->pluck('id_user'))->get();
        return $users;
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

    public function deleteUserMember(string $projectId, string $userId)
    {
        $userLogged = Auth::user();
        if(!$userLogged) {
            return redirect()->route('projects.index')->with('error', 'Usuario no encontrado.');
        }

        $projectTeam = ProjectTeam::where('id_project', $projectId)
            ->where('id_user', $userId)
            ->where('status', '!=', 'Deleted')
            ->first();

        if($projectTeam) {
            if($projectTeam->update(['status' => 'Deleted', 'id_user_modification' => $userLogged->id])) {
                return redirect()->route('projects.show', $projectId)->with('success', 'Usuario eliminado del proyecto con éxito.');
            } else {
                return redirect()->route('projects.show', $projectId)->with('error', 'Error al eliminar el usuario del proyecto.');
            }
        } else {
            return redirect()->route('projects.show', $projectId)->with('error', 'Usuario no encontrado en el proyecto.');
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
