<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTeam extends Model
{
    use HasFactory;

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'id_project',
        'id_user',
        'is_admin',
        'status',
        'id_user_creator',
        'id_user_modification',
    ];
}
