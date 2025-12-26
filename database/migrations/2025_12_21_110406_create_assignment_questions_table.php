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
        Schema::create('assignment_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->enum('question_type', ['Pilihan Ganda', 'Essay']);
            $table->text('question');
            $table->text('picture')->nullable();
            $table->text('answer_a')->nullable();
            $table->text('answer_b')->nullable();
            $table->text('answer_c')->nullable();
            $table->text('answer_d')->nullable();
            $table->text('answer_e')->nullable();
            $table->text('correct_answer')->nullable();
            $table->decimal('score',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_questions');
    }
};
