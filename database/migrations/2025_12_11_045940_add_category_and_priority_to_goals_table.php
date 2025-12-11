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
        Schema::table('goals', function (Blueprint $table) {
            // Only add 'category' if it doesn't exist
            if (!Schema::hasColumn('goals', 'category')) {
                $table->string('category')->default('General')->after('description');
            }

            // Only add 'priority' if it doesn't exist
            if (!Schema::hasColumn('goals', 'priority')) {
                $table->string('priority')->default('medium')->after('category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            //
        });
    }
};
