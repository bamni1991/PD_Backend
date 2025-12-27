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
        // academic_sessions
        Schema::create('academic_sessions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('session_name', 50);
            $table->tinyInteger('is_active')->default(1)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // caste_categories
        Schema::create('caste_categories', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('caste_name', 100);
            $table->enum('category', ['OPEN', 'OBC', 'SC', 'ST', 'NT', 'SBC', 'EWS']);
            $table->timestamp('created_at')->useCurrent();
        });

        // classes
        Schema::create('classes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('class_name', 50);
            $table->timestamp('created_at')->useCurrent();
        });

        // kit_items
        Schema::create('kit_items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('item_name', 100);
            $table->decimal('price', 10, 2)->default(0.00)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // mother_tongues
        Schema::create('mother_tongues', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('tongue_name', 100)->unique('unique_tongue');
            $table->tinyInteger('is_active')->default(1)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // nationalities
        Schema::create('nationalities', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nationality_name', 100)->unique('unique_nationality');
            $table->tinyInteger('is_active')->default(1)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // religions
        Schema::create('religions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('religion_name', 50);
            $table->tinyInteger('is_active')->default(1)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // states
        Schema::create('states', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('state_name', 100)->unique('unique_state');
            $table->integer('country_id')->nullable();
            $table->tinyInteger('is_active')->default(1)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
        Schema::dropIfExists('religions');
        Schema::dropIfExists('nationalities');
        Schema::dropIfExists('mother_tongues');
        Schema::dropIfExists('kit_items');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('caste_categories');
        Schema::dropIfExists('academic_sessions');
    }
};
