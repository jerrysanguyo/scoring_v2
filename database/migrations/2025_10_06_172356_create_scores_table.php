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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('participant_id')
                ->nullable()
                ->constrained('participants')
                ->nullOnDelete();

            $table->foreignId('criteria_id')
                ->nullable()
                ->constrained('criteria_details')
                ->nullOnDelete();

            $table->foreignId('scored_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->decimal('score', 5, 2);

            $table->timestamps();

            $table->unique(['participant_id', 'criteria_id', 'scored_by'], 'unique_score_per_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};