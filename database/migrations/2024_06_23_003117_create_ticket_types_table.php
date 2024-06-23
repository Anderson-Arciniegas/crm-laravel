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
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('status', ['Active', 'Inactive', 'Pending', 'Deleted']);
            $table->unsignedBigInteger('id_user_creator');
            $table->unsignedBigInteger('id_user_modification')->nullable();
            $table->timestamps();

            $table->foreign('id_user_creator')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_user_modification')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
