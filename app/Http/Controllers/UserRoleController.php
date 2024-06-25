<?php

namespace App\Http\Controllers;

use App\Models\UserRole;

class UserRoleController extends Controller
{
    // En UserController.php
    public function create(array $data)
    {
        $userRole = UserRole::create([
            'id_user' => $data['id_user'],
            'id_role' => $data['id_role'],
        ]);
        return $userRole;
    }
}
