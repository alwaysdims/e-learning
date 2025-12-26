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
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_question_id')->constrained('assignment_questions')->cascadeOnDelete();
            $table->foreignId('student_task_id')->constrained('student_tasks')->cascadeOnDelete();
            $table->text('answer');
            $table->boolean('is_correct')->nullable();
            $table->decimal('score_obtained',10,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};
