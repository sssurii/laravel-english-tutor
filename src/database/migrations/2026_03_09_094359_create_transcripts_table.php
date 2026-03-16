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
        Schema::create('transcripts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_session_id')->constrained()->cascadeOnDelete();
            $table->string('speaker');
            $table->string('status')->default('completed');
            $table->longText('text')->nullable();
            $table->unsignedInteger('word_count')->default(0);
            $table->unsignedInteger('grammar_errors')->nullable();
            $table->timestamps();

            $table->index(['practice_session_id', 'created_at']);
            $table->index(['speaker', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transcripts');
    }
};
