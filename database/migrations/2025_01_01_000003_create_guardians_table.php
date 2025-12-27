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
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('father_name', 255)->nullable();
            $table->string('mother_name', 255)->nullable();
            $table->string('father_occupation', 255)->nullable();
            $table->string('mother_occupation', 255)->nullable();
            $table->string('mobile', 255)->unique('guardians_mobile_unique');
            $table->string('alt_mobile', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('aadhar_no', 255)->nullable();
            $table->timestamps();

            $table->foreign('user_id', 'guardians_user_id_foreign')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};
