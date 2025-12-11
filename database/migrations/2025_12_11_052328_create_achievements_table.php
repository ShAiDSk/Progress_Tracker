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
        // 1. The Badges Table (The available achievements)
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');        // e.g., "Early Bird"
            $table->string('description'); // e.g., "Complete a goal before 8am"
            $table->string('icon');        // Emoji or icon class
            $table->timestamps();
        });

        // 2. The User-Badge Pivot Table (Who unlocked what)
        Schema::create('achievement_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('achievement_id')->constrained()->cascadeOnDelete();
            $table->timestamp('unlocked_at')->useCurrent();

            // Prevent unlocking the same badge twice
            $table->unique(['user_id', 'achievement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
