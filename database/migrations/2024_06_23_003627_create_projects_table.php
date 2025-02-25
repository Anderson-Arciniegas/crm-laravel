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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('status', ['Active', 'Inactive', 'Pending', 'Deleted', 'Completed', 'In Progress']);
            $table->timestamps();
            $table->unsignedBigInteger('id_user_creator');
            $table->unsignedBigInteger('id_user_modification')->nullable();

            $table->foreign('id_user_creator')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_user_modification')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
