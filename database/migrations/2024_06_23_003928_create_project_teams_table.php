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
        Schema::create('project_teams', function (Blueprint $table) {
            $table->unsignedBigInteger('id_project');
            $table->unsignedBigInteger('id_user');
            $table->boolean('is_admin')->default(false);
            $table->enum('status', ['Active', 'Inactive', 'Pending', 'Deleted', 'Completed']);
            $table->unsignedBigInteger('id_user_creator');
            $table->unsignedBigInteger('id_user_modification')->nullable();
            $table->timestamps();

            $table->foreign('id_project')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_user_creator')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_user_modification')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_teams');
    }
};
