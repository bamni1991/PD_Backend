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
        Schema::table('student_fees', function (Blueprint $table) {
            $table->string('paidBy', 256)->nullable()->after('status');
            $table->string('collectedBy', 256)->nullable()->after('paidBy');
            $table->string('mode', 256)->nullable()->after('collectedBy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_fees', function (Blueprint $table) {
            $table->dropColumn(['paidBy', 'collectedBy', 'mode']);
        });
    }
};
