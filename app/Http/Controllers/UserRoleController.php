<?php

namespace App\Http\Controllers;

use App\Models\UserRol;

class UserRoleController extends Controller
{
    // En UserController.php
    public function create(array $data)
    {
        $userRole = UserRol::create([
            'id_user' => $data['id_user'],
            'id_role' => $data['id_role'],
        ]);
        return $userRole;
    }
}
