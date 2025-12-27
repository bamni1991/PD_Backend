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
        // student_fees
        Schema::create('student_fees', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('student_id');
            $table->integer('class_fee_id')->nullable();
            $table->integer('academic_session_id');
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['fees', 'discount'])->default('fees');
            $table->enum('status', ['pending', 'paid'])->default('pending')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('student_id', 'student_fees_ibfk_1')->references('id')->on('students');
            $table->foreign('class_fee_id', 'student_fees_ibfk_2')->references('id')->on('class_fees');
            $table->foreign('academic_session_id', 'student_fees_ibfk_3')->references('id')->on('academic_sessions');
        });

        // student_kits
        Schema::create('student_kits', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('student_id');
            $table->integer('kit_item_id');
            $table->integer('academic_session_id');
            $table->date('issued_date')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['student_id', 'kit_item_id', 'academic_session_id'], 'unique_student_kit');

            $table->foreign('student_id', 'student_kits_ibfk_1')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('kit_item_id', 'student_kits_ibfk_2')->references('id')->on('kit_items');
            $table->foreign('academic_session_id', 'student_kits_ibfk_3')->references('id')->on('academic_sessions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_kits');
        Schema::dropIfExists('student_fees');
    }
};
