<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //Crear user admin
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@kench.com',
            'password' => bcrypt('secret'),
            'email_verified_at' => now(),
            'client_type' => 'person',
            'status' => 'Active',
        ]);
        //Crear roles admin
        DB::table('roles')->insert(
            ['code' => 'ADM01','name' => 'Admin','status' => 'Active', 'id_user_creator' => 1],
            ['code' => 'CLI02','name' => 'Client','status' => 'Active', 'id_user_creator' => 1],
            ['code' => 'LEA03','name' => 'Lead','status' => 'Active', 'id_user_creator' => 1],
        );
        //Crear user roles
        DB::table('user_roles')->insert(
            ['id_user' => 1,'id_role' => 1,'status' => 'Active']
        );
        //Crear Ticket Types
        DB::table('ticket_types')->insert(
            ['code' => 'GEN01','name' => 'General','status' => 'Active', 'id_user_creator' => 1],
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Borrar
        DB::table('users')->where('email', 'admin@kench.com')->delete();
        DB::table('roles')->where('code', 'ADM01')->delete();
        DB::table('roles')->where('code', 'CLI02')->delete();
        DB::table('roles')->where('code', 'LEA03')->delete();
        DB::table('user_roles')->where([['id_user', '=', 1], ['id_role', '=', 1]])->delete();
        DB::table('ticket_types')->where('code', 'GEN01')->delete();
    }
};
