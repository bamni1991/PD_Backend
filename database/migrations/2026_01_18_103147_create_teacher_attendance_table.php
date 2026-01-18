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
        Schema::create('teacher_attendance', function (Blueprint $table) {
            $table->id();
            $table->integer('teacher_id');
            $table->integer('academic_session_id');
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'half_day', 'leave', 'outdoor'])->default('present');
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->string('remarks')->nullable();
            $table->enum('marked_by', ['teacher', 'admin'])->default('teacher');
            $table->timestamps();

            $table->unique(['teacher_id', 'attendance_date'], 'unique_teacher_day');

            // $table->foreign('teacher_id')
            //     ->references('id')
            //     ->on('teachers')
            //     ->onDelete('cascade');

            // $table->foreign('academic_session_id')
            //     ->references('id')
            //     ->on('academic_sessions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_attendance');
    }
};
