<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 150)->nullable();
            $table->string('mobile', 10)->unique('mobile'); // Using string(10) as per dump, not bigInteger
            $table->string('profile_image', 255)->nullable();
            $table->string('password', 100);
            $table->enum('role', ['admin', 'teacher', 'parent']);
            $table->enum('status', ['active', 'inactive'])->default('active')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
