<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Rol extends Model
{
    use HasFactory;
    protected $table = 'roles';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'code',
        'status',
        'id_user_creator',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'id_role', 'id_user');
    }
}
