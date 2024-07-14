<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserRol;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getNotAssigned()
    {
        return Ticket::where('status', '!=', 'Deleted')
            ->where('status', '!=', 'Inactive')
            ->where('status', '!=', 'Pending')
            ->whereNull('id_admin')
            ->get();
    }

    public function getAssigned()
    {
        return Ticket::where('status', '!=', 'Deleted')
            ->where('status', '!=', 'Inactive')
            ->whereNotNull('id_admin')
            ->get();
    }

    public function getCompleted()
    {
        return Ticket::where('status', '=', 'Completed')
            ->whereNotNull('id_admin')
            ->get();
    }

    public function getMyTickets()
    {
        $userLogged = Auth::user();
        return Ticket::where('status', '!=', 'Deleted')
            ->andWhere('id_user_creator', '=', $userLogged->id)
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'ticket_types' => 'required',
        ]);

        $userLogged = Auth::user();
        if(!$userLogged) {
            return redirect()->route('tickets.index')->with('error', 'Usuario no encontrado.');
        }
        
        // Creación del ticket
        $ticket = new Ticket();
        $ticket->title = $validatedData['title'];
        $ticket->description = $validatedData['description'];
        $ticket->id_ticket_type = $validatedData['ticket_types'];
        $ticket->status = 'Active';
        $ticket->id_user_creator = $userLogged->id;
        $adminUser = UserRol::where('id_role', '2')->inRandomOrder()->first();

        if($adminUser) {
            $ticket->id_admin = $adminUser->id_user;
        }

        if($ticket->save()) {
            // Si el ticket se guarda correctamente, se envía un mensaje de éxito
            return redirect()->route('auth.login')->with('success', 'Ticket creado con éxito.');
        } else {
            // Si el ticket no se guarda correctamente, se envía un mensaje de error
            return redirect()->route('auth.login')->with('error', 'Error al crear el ticket.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function getById(string $id)
    {
        return Ticket::where('id', $id)->where('status', '!=', 'deleted')->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function assign(string $id, string $idAdmin)
    {
        $userLogged = Auth::user();
        if(!$userLogged) {
            return redirect()->route('tickets.index')->with('error', 'Usuario no encontrado.');
        }

        $ticket = Ticket::where('id', $id)->where('status', '!=', 'deleted')->first();
        // Verificar si el ticket fue encontrado
        if ($ticket) {
            $ticket->idAdmin = $idAdmin;
            $ticket->id_user_modification = $userLogged->id;
            if ($ticket->save()) {
                // Si el ticket se actualiza correctamente, se envía un mensaje de éxito
                return redirect()->route('tickets.index')->with('success', 'Ticket actualizado con éxito.');
            } else {
                // Si hay un error al guardar, enviar un mensaje de error
                return redirect()->route('tickets.index')->with('error', 'Error al actualizar el Ticket.');
            }
        } else {
            return redirect()->route('tickets.index')->with('error', 'Ticket no encontrado.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function manage(string $id, string $status)
    {
        $userLogged = Auth::user();
        if(!$userLogged) {
            return redirect()->route('tickets.index')->with('error', 'Usuario no encontrado.');
        }

        $ticket = Ticket::where('id', $id)->where('status', '!=', 'deleted')->first();
        // Verificar si el ticket fue encontrado
        if ($ticket) {
            $ticket->status = $status;
            $ticket->id_user_modification = $userLogged->id;
            if ($ticket->save()) {
                return redirect()->route('tickets.index')->with('success', 'Ticket actualizado con éxito.');
            } else {
                return redirect()->route('tickets.index')->with('error', 'Error al actualizar el Ticket.');
            }
        } else {
            return redirect()->route('tickets.index')->with('error', 'Ticket no encontrado.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $userLogged = Auth::user();
        if(!$userLogged) {
            return redirect()->route('tickets.index')->with('error', 'Usuario no encontrado.');
        }

        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'title' => 'max:255',
            'description',
        ]);
    
        // Buscar el ticket por ID
        $ticket = Ticket::where('id', $id)->where('status', '!=', 'deleted')->first();
    
        // Verificar si el ticket fue encontrado
        if (!$ticket) {
            return redirect()->route('tickets.index')->with('error', 'Ticket no encontrado.');
        }
    
        // Actualización del ticket
        $ticket->title = $validatedData['title'];
        $ticket->description = $validatedData['description'];
        $ticket->id_user_modification = $userLogged->id;

        if ($ticket->save()) {
            // Si el ticket se actualiza correctamente, se envía un mensaje de éxito
            return redirect()->route('tickets.index')->with('success', 'Ticket actualizado con éxito.');
        } else {
            // Si hay un error al guardar, enviar un mensaje de error
            return redirect()->route('tickets.index')->with('error', 'Error al actualizar el Ticket.');
        }
    }
}
