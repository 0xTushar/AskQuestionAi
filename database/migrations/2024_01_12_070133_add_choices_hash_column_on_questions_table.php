<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Add a generated column with MD5 hash and binary representation
            DB::statement('ALTER TABLE questions ADD COLUMN hashed_choices BINARY(16) GENERATED ALWAYS AS (UNHEX(MD5(choices))) STORED');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
