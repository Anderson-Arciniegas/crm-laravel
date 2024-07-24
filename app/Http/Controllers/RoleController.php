<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Get roles where status is not Deleted
        return Rol::where('status', '!=', 'Deleted')->get();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //Get one by id and status is no Deleted
        return Rol::where('id', $id)->where('status', '!=', 'Deleted')->first();
    }

    /* 
     * Get by code 
     */
    public function getByCode(string $code)
    {
        return Rol::where('code', $code)->where('status', '!=', 'Deleted')->first();
    }
}
