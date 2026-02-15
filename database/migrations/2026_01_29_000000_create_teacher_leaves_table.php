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
        if (!Schema::hasTable('teacher_leaves')) {
            Schema::create('teacher_leaves', function (Blueprint $table) {
                $table->id();
                $table->integer('teacher_id'); // Matching teachers.id usually int(11) in existing DB
                $table->integer('academic_session_id'); // Matching user SQL INT(11)
                $table->string('leave_type', 50);
                $table->date('start_date');
                $table->date('end_date');
                $table->integer('days_count')->default(1);
                $table->text('reason')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('admin_remark')->nullable();
                $table->timestamps();

                $table->foreign('teacher_id')
                    ->references('id')
                    ->on('teachers')
                    ->onDelete('cascade');

                // Assuming academic_sessions table exists, add foreign key if possible, 
                // but user SQL didn't explicitly ask for FK on academic_session_id, only comment "To match attendance table".
                // I will leave it as integer as requested.
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_leaves');
    }
};
