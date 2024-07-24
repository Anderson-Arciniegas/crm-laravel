<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectTeam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProjectsController extends Controller
{

    public function showProjects()
    {
        $user = Auth::user();
        // if (!$user) {
        //     return redirect()->route('dashboard')->with('error', 'Usuario no encontrado.');
        // }

        $projects = Project::where('id_user_creator', $user->id)->where('status', '!=', 'Deleted')->get();
        return view('projects.projects', ['projects' => $projects]);
    }

    public function showProjectDetails($id)
    {
        $project = $this->getById($id);
        $user = Auth::user();

        if ($this->checkUserIsAdmin($id, $user->id)) {
            return view('projects.details', ['project' => $project, 'isAdmin' => true]);
        } else {
            return view('projects.details', ['project' => $project, 'isAdmin' => false]);
        }
    }

    public function showProjectTeam($id)
    {
        $project = $this->getById($id);
        $members = $this->getAllTeamMembers($id);
        $user = Auth::user();
        Log::info('Members: ' . $members);

        if ($this->checkUserIsAdmin($id, $user->id)) {
            return view('projects.team', ['project' => $project, 'members' => $members, 'isAdmin' => true]);
        } else {
            return redirect()->route('projects') > with('error', 'No tienes permiso para ver este proyecto.');
        }
    }

    public function showProjectAddMember($id)
    {
        $project = $this->getById($id);
        $user = Auth::user();

        if ($this->checkUserIsAdmin($id, $user->id)) {
            return view('projects.new-member', ['project' => $project, 'isAdmin' => true]);
        } else {
            return redirect()->route('projects') > with('error', 'No tienes permiso para ver este proyecto.');
        }
    }

    public function showProjectEdit($id)
    {
        $project = $this->getById($id);
        $user = Auth::user();

        if ($this->checkUserIsAdmin($id, $user->id)) {
            return view('projects.edit', ['project' => $project, 'isAdmin' => true]);
        } else {
            return redirect()->route('projects') > with('error', 'No tienes permiso para ver este proyecto.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        $userLogged = Auth::user();
        if (!$userLogged) {
            return redirect()->route('auth.login')->with('error', 'Usuario no encontrado.');
        }
        $name = $request->query('search');

        $projectTeamIds = ProjectTeam::where('id_user', $userLogged->id)->pluck('id_project');
        if ($projectTeamIds) {
            $query = Project::query();
            // Filtrar por nombre si se proporciona
            if ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            }

            $query->whereIn('id', $projectTeamIds);
            $projects = $query->get();
            return view('projects.projects', ['projects' => $projects]);
        } else {
            return redirect()->route('projects') > with('error', 'No tienes permiso para ver este proyecto.');
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
        if (!$userLogged) {
            return redirect()->route('projects')->with('error', 'Usuario no encontrado.');
        }

        // Crear el proyecto con los datos validados
        $project = new Project();
        $project->name = $validatedData['name'];
        $project->status = 'Active';
        $project->description = $validatedData['description'];
        $project->start_date = $validatedData['start_date'];
        $project->end_date = $validatedData['end_date'];
        $project->id_user_creator = $userLogged->id;

        if ($project->save()) {
            $projectTeam = new ProjectTeam();
            $projectTeam->id_project = $project->id;
            $projectTeam->id_user = $userLogged->id;
            $projectTeam->status = 'Active';
            $projectTeam->id_user_creator = $userLogged->id;
            $projectTeam->is_admin = true;
            $projectTeam->save();
            // Si el proyecto se guarda correctamente, se envía un mensaje de éxito
            return redirect()->route('projects')->with('success', 'Proyecto creado con éxito.');
        } else {
            // Si el proyecto no se guarda correctamente, se envía un mensaje de error
            return redirect()->route('projects')->with('error', 'Error al crear el proyecto.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function getById(string $id)
    {
        $userLogged = Auth::user();
        if (!$userLogged) {
            return redirect()->route('auth.login')->with('error', 'Usuario no encontrado.');
        }

        $projectTeam = ProjectTeam::where('id_project', $id)->where('id_user', $userLogged->id)->first();

        if ($projectTeam->id_user == $userLogged->id) {
            return Project::where('id', $id)->where('status', '!=', 'Deleted')->first();
        } else {
            return redirect()->route('projects') > with('error', 'No tienes permiso para ver este proyecto.');
        }
    }

    public function checkUserIsAdmin(string $projectId, string $userId)
    {
        $projectTeam = ProjectTeam::where('id_project', $projectId)
            ->where('id_user', $userId)
            ->where('status', '!=', 'Deleted')
            ->first();
        if ($projectTeam) {
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
        if (!$userLogged) {
            return redirect()->route('/')->with('error', 'Usuario no encontrado.');
        }

        $user = User::where('email', $request->email)->where('status', '!=', 'Deleted')->first();
        if (!$user) {
            return redirect()->route('projects')->with('error', 'Usuario no encontrado.');
        }

        // Buscar si ya existe una entrada para este proyecto y usuario
        $projectTeamEntry = ProjectTeam::where('id_project', $projectId)->where('id_user', $user->id)->first();

        if (!$projectTeamEntry) {
            $projectTeamEntry = new ProjectTeam();
            $projectTeamEntry->id_project = $projectId;
            $projectTeamEntry->id_user = $user->id;
            $projectTeamEntry->status = 'Active';
            $projectTeamEntry->id_user_creator = $userLogged->id;
            $projectTeamEntry->is_admin = $validated['is_admin'];

            if ($projectTeamEntry->save()) {
                // Si se guarda correctamente, enviar un mensaje de éxito
                return redirect()->route('projects.team', $projectId)->with('success', 'Usuario agregado al equipo del proyecto con éxito.');
            } else {
                // Si hay un error al guardar, enviar un mensaje de error
                return redirect()->route('projects.team', $projectId)->with('error', 'Error al agregar el usuario al equipo del proyecto.');
            }
        } else {
            // Si la entrada ya existe, posiblemente quieras enviar un mensaje diferente o manejarlo de otra manera
            return redirect()->route('projects.team', $projectId)->with('info', 'El usuario ya es parte del equipo de este proyecto.');
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
            'description' => 'required|string',
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date',
        ]);

        $userLogged = Auth::user();
        if (!$userLogged) {
            return redirect()->route('projects')->with('error', 'Usuario no encontrado.');
        }

        // Buscar el proyecto por ID
        $project = Project::where('id', $id)->where('status', '!=', 'Deleted')->first();

        // Verificar si el proyecto fue encontrado
        if (!$project) {
            return redirect()->route('projects')->with('error', 'Proyecto no encontrado.');
        }

        // Actualizar el proyecto con los datos validados
        $project->name = $validatedData['name'];
        $project->description = $validatedData['description'];
        $project->start_date = $validatedData['start_date'];
        $project->end_date = $validatedData['end_date'];
        $project->id_user_modification = $userLogged->id;

        // Guardar el proyecto actualizado en la base de datos
        if ($project->save()) {
            // Si el proyecto se actualiza correctamente, se envía un mensaje de éxito
            return redirect()->route('projects')->with('success', 'Proyecto actualizado con éxito.');
        } else {
            // Si hay un error al guardar, enviar un mensaje de error
            return redirect()->route('projects')->with('error', 'Error al actualizar el proyecto.');
        }
    }

    /**
     * Complete project
     */
    public function complete(string $id)
    {
        $userLogged = Auth::user();
        if (!$userLogged) {
            return redirect()->route('projects')->with('error', 'Usuario no encontrado.');
        }

        // Buscar el proyecto por ID
        $project = Project::where('id', $id)->where('status', '!=', 'Deleted')->first();

        // Verificar si el proyecto fue encontrado
        if (!$project) {
            return redirect()->route('projects')->with('error', 'Proyecto no encontrado.');
        }

        // Actualizar el proyecto con los datos validados
        $project->status = 'Completed';

        // Guardar el proyecto actualizado en la base de datos
        if ($project->save()) {
            // Si el proyecto se actualiza correctamente, se envía un mensaje de éxito
            return redirect()->route('projects')->with('success', 'Proyecto actualizado con éxito.');
        } else {
            // Si hay un error al guardar, enviar un mensaje de error
            return redirect()->route('projects')->with('error', 'Error al actualizar el proyecto.');
        }
    }

    public function deleteUserMember(string $projectId, string $userId)
    {
        $userLogged = Auth::user();
        if (!$userLogged) {
            return redirect()->route('projects')->with('error', 'Usuario no encontrado.');
        }

        $projectTeam = ProjectTeam::where('id_project', $projectId)
            ->where('id_user', $userId)
            ->where('status', '!=', 'Deleted')
            ->first();

        if ($projectTeam) {
            $projectTeam->status = 'Deleted';
            $projectTeam->id_user_modification = $userLogged->id;
            if ($projectTeam->save()) {
                return redirect()->route('projects.team', $projectId)->with('success', 'Usuario eliminado del proyecto con éxito.');
            } else {
                return redirect()->route('projects.team', $projectId)->with('error', 'Error al eliminar el usuario del proyecto.');
            }
        } else {
            return redirect()->route('projects.team', $projectId)->with('error', 'Usuario no encontrado en el proyecto.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userLogged = Auth::user();
        if (!$userLogged) {
            return redirect()->route('projects')->with('error', 'Usuario no encontrado.');
        }

        $project = Project::where('id', $id)->where('status', '!=', 'Deleted')->first();

        if (!$project) {
            return redirect()->route('projects')->with('error', 'Proyecto no encontrado.');
        }

        // Actualizar el proyecto con los datos validados
        $project->status = 'Deleted';
        $project->id_user_modification = $userLogged->id;

        if ($project->save()) {
            // Si el proyecto se actualiza correctamente, se envía un mensaje de éxito
            return redirect()->route('projects')->with('success', 'Proyecto actualizado con éxito.');
        } else {
            // Si hay un error al guardar, enviar un mensaje de error
            return redirect()->route('projects')->with('error', 'Error al actualizar el proyecto.');
        }
    }
}
