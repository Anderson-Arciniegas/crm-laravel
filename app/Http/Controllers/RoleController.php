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
        //Get roles where status is not deleted
        return Rol::where('status', '!=', 'deleted')->get();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //Get one by id and status is no deleted
        return Rol::where('id', $id)->where('status', '!=', 'deleted')->first();
    }

    /* 
     * Get by code 
     */
    public function getByCode(string $code)
    {
        return Rol::where('code', $code)->where('status', '!=', 'deleted')->first();
    }
}
