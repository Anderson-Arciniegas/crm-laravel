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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->unsignedBigInteger('id_ticket_type');
            $table->unsignedBigInteger('id_admin');
            $table->enum('status', ['Active', 'Inactive', 'Pending', 'Deleted', 'Completed']);
            $table->unsignedBigInteger('id_user_creator');
            $table->unsignedBigInteger('id_user_modification')->nullable();
            $table->timestamps();

            $table->foreign('id_ticket_type')->references('id')->on('ticket_types')->onDelete('cascade');
            $table->foreign('id_admin')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_user_creator')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_user_modification')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
