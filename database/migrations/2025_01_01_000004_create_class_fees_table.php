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
        Schema::create('class_fees', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('class_id');
            $table->integer('academic_session_id');
            $table->decimal('fee_amount', 10, 2);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('class_id', 'class_fees_ibfk_1')->references('id')->on('classes');
            $table->foreign('academic_session_id', 'class_fees_ibfk_2')->references('id')->on('academic_sessions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_fees');
    }
};
