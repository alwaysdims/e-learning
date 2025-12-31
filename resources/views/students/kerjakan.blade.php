@extends('students.layouts.main',['title' => 'Kerjakan Tugas - '. $task->title])

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">{{ $task->title }}</h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <div class="text-slate-500 text-sm">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="clock" data-lucide="clock" class="lucide lucide-clock w-4 h-4 mr-2"></svg>
                <span id="timer" class="font-medium"></span>
            </div>
        </div>
    </div>
</div>

<div class="intro-y grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 lg:col-span-4 2xl:col-span-3">
        <div class="box p-5 sticky top-5">
            <!-- Progress Navigation -->
            <div class="mb-5">
                <h3 class="text-lg font-medium mb-3">Daftar Soal</h3>
                <div class="grid grid-cols-5 gap-2">
                    @foreach($task->questions as $index => $question)
                        <button type="button"
                                class="nav-btn w-10 h-10 rounded-full flex items-center justify-center {{ $index == 0 ? 'bg-primary text-white' : 'bg-slate-100 text-slate-600' }}"
                                data-index="{{ $index }}">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Timer Info -->
            <div class="border-t border-slate-200/60 pt-5">
                <h4 class="text-base font-medium mb-3">Informasi Waktu</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Dimulai:</span>
                        <span class="font-medium">
                            {{ \Carbon\Carbon::parse($studentTask->started_at)->format('H:i') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Batas Waktu:</span>
                        <span class="font-medium text-danger">{{ \Carbon\Carbon::parse($studentTask->due_at)->format('H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Sisa Waktu:</span>
                        <span id="time-remaining" class="font-medium text-warning"></span>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="border-t border-slate-200/60 pt-5">
                <h4 class="text-base font-medium mb-3">Progress</h4>
                <div class="flex justify-between text-sm text-slate-500 mb-1">
                    <span>Tersimpan: <span id="saved-count">0</span>/{{ count($task->questions) }}</span>
                    <span id="progress-percent" class="ml-2">0%</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2">
                    <div id="progress-bar" class="bg-primary h-2 rounded-full" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
        <form id="assignment-form" action="{{ route('student.assignment.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="student_task_id" value="{{ $studentTask->id }}">

            @foreach($task->questions as $index => $question)
                <div class="question-slide {{ $index == 0 ? '' : 'hidden' }}" data-index="{{ $index }}" data-question-id="{{ $question->id }}">
                    <!-- Box Midone -->
                    <div class="box p-5">
                        <div class="flex items-center mb-5">
                            <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-medium">
                                {{ $index + 1 }}
                            </div>
                            <div class="ml-3">
                                <h3 class="font-medium">Soal {{ $index + 1 }}</h3>
                                <div class="text-xs text-slate-500">
                                    @if($question->question_type == 'Pilihan Ganda')
                                        <span class="bg-primary/10 text-primary px-2 py-1 rounded">Pilihan Ganda</span>
                                    @else
                                        <span class="bg-warning/10 text-warning px-2 py-1 rounded">Essay</span>
                                    @endif
                                    <span class="ml-2">Skor: {{ $question->score }} poin</span>
                                </div>
                            </div>
                        </div>

                        <!-- Question Content -->
                        <div class="border border-slate-200/60 rounded-lg p-4 mb-4">
                            {!! $question->question !!}
                        </div>

                        <!-- Question Image -->
                        <div class="mb-6">
                            @if($question->picture)
                                <div class="border border-slate-200/60 rounded-lg overflow-hidden">
                                    <img src="{{ Storage::url($question->picture) }}"
                                         alt="Gambar Soal {{ $index + 1 }}"
                                         class="w-full h-auto max-h-96 object-contain">
                                </div>
                            @else
                                <div class="border border-slate-200/60 rounded-lg p-8 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="image" data-lucide="image" class="lucide lucide-image w-12 h-12 mx-auto text-slate-400"></svg>
                                    <p class="text-slate-500 mt-2">Tidak ada gambar</p>
                                </div>
                            @endif
                        </div>

                        <!-- Answer Options -->
                        <div class="answer-section">
                            @if($question->question_type == 'Pilihan Ganda')
                                <h4 class="text-base font-medium mb-3">Pilih Jawaban:</h4>
                                <div class="space-y-3">
                                    @foreach(['a', 'b', 'c', 'd', 'e'] as $opt)
                                        @if($question->{'answer_'.$opt})
                                            <label class="flex items-center p-4 border border-slate-200/60 rounded-lg hover:border-primary transition-all cursor-pointer answer-option">
                                                <input type="radio"
                                                       name="answers[{{ $question->id }}]"
                                                       value="{{ $opt }}"
                                                       class="hidden">
                                                <div class="w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center mr-3 radio-indicator">
                                                    <div class="w-3 h-3 rounded-full bg-primary hidden"></div>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center">
                                                        <span class="w-6 h-6 rounded bg-primary/10 text-primary flex items-center justify-center font-medium mr-3">
                                                            {{ strtoupper($opt) }}
                                                        </span>
                                                        <span>{{ $question->{'answer_'.$opt} }}</span>
                                                    </div>
                                                </div>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <h4 class="text-base font-medium mb-3">Jawaban Essay:</h4>
                                <textarea name="answers[{{ $question->id }}]"
                                          class="form-control min-h-[200px]"
                                          placeholder="Tulis jawaban essay Anda di sini..."
                                          rows="6"></textarea>
                                <div class="text-xs text-slate-500 mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="info" data-lucide="info" class="lucide lucide-info inline-block mr-1"></svg>
                                    Jawaban akan dinilai oleh guru secara manual
                                </div>
                            @endif
                        </div>

                        <!-- Save Button -->
                        <div class="mt-6">
                            <button type="button"
                                    class="save-answer btn btn-outline-primary w-full sm:w-auto"
                                    data-question-id="{{ $question->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="save" data-lucide="save" class="lucide lucide-save w-4 h-4 mr-2"></svg>
                                Simpan Jawaban
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Navigation Buttons -->
            <div class="mt-5 flex flex-col sm:flex-row justify-between items-center gap-3">
                <button type="button" id="prev-btn" class="btn btn-outline-secondary w-full sm:w-auto shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-left" data-lucide="chevron-left" class="lucide lucide-chevron-left w-4 h-4 mr-2"></svg>
                    Soal Sebelumnya
                </button>

                <div class="flex items-center space-x-3">
                    <button type="button" id="next-btn" class="btn btn-primary w-full sm:w-auto shadow-md">
                        Soal Berikutnya
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-right" data-lucide="chevron-right" class="lucide lucide-chevron-right w-4 h-4 ml-2"></svg>
                    </button>

                    <button type="submit" id="submit-btn" class="btn btn-success w-full sm:w-auto shadow-md hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="send" data-lucide="send" class="lucide lucide-send w-4 h-4 mr-2"></svg>
                        Kumpulkan Tugas
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    // Initialize variables
    let currentStep = 0;
    const slides = document.querySelectorAll('.question-slide');
    const totalQuestions = slides.length;
    const savedAnswers = {};

    // Timer functionality
    const dueTime = new Date('{{ \Carbon\Carbon::parse($studentTask->due_at)->format('Y-m-d H:i:s') }}');

    function updateTimer() {
        const now = new Date();
        const diff = dueTime - now;

        if (diff <= 0) {
            document.getElementById('timer').textContent = 'Waktu Habis!';
            document.getElementById('time-remaining').textContent = '0:00';
            // Auto submit when time is up
            document.getElementById('assignment-form').submit();
            return;
        }

        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        document.getElementById('timer').textContent =
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        const timeRemaining = `${hours > 0 ? hours + ' jam ' : ''}${minutes} menit`;
        document.getElementById('time-remaining').textContent = timeRemaining;
    }

    // Update progress
    function updateProgress() {
        const savedCount = Object.keys(savedAnswers).length;
        const progressPercent = (savedCount / totalQuestions) * 100;

        document.getElementById('saved-count').textContent = savedCount;
        document.getElementById('progress-percent').textContent = `${Math.round(progressPercent)}%`;
        document.getElementById('progress-bar').style.width = `${progressPercent}%`;

        // Update navigation buttons
        document.querySelectorAll('.nav-btn').forEach((btn, index) => {
            const questionId = slides[index].dataset.questionId;
            if (savedAnswers[questionId]) {
                btn.classList.remove('bg-slate-100');
                btn.classList.add('bg-success', 'text-white');
            } else if (index === currentStep) {
                btn.classList.remove('bg-slate-100');
                btn.classList.add('bg-primary', 'text-white');
            } else {
                btn.classList.remove('bg-primary', 'text-white', 'bg-success');
                btn.classList.add('bg-slate-100', 'text-slate-600');
            }
        });
    }

    // Save answer function
    function saveAnswer(questionId) {
        const slide = document.querySelector(`[data-question-id="${questionId}"]`);
        const answerInput = slide.querySelector('input[name^="answers"]') ||
                           slide.querySelector('textarea[name^="answers"]');

        if (answerInput) {
            let value = '';
            if (answerInput.type === 'radio') {
                const selectedRadio = slide.querySelector(`input[name="answers[${questionId}]"]:checked`);
                value = selectedRadio ? selectedRadio.value : '';
            } else {
                value = answerInput.value;
            }

            if (value.trim() !== '') {
                savedAnswers[questionId] = true;
                updateProgress();

                // Show success message
                const saveBtn = slide.querySelector('.save-answer');
                const originalText = saveBtn.innerHTML;
                saveBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="check-circle" data-lucide="check-circle" class="lucide lucide-check-circle w-4 h-4 mr-2"></svg>
                    Tersimpan
                `;
                saveBtn.classList.remove('btn-outline-primary');
                saveBtn.classList.add('btn-success');

                setTimeout(() => {
                    saveBtn.innerHTML = originalText;
                    saveBtn.classList.remove('btn-success');
                    saveBtn.classList.add('btn-outline-primary');
                }, 2000);
            }
        }
    }

    // Slide navigation
    function updateSlide() {
        slides.forEach((slide, index) => {
            slide.classList.toggle('hidden', index !== currentStep);
        });

        // Update navigation buttons
        document.getElementById('prev-btn').classList.toggle('hidden', currentStep === 0);

        if (currentStep === totalQuestions - 1) {
            document.getElementById('next-btn').classList.add('hidden');
            document.getElementById('submit-btn').classList.remove('hidden');
        } else {
            document.getElementById('next-btn').classList.remove('hidden');
            document.getElementById('submit-btn').classList.add('hidden');
        }

        // Update active navigation button
        document.querySelectorAll('.nav-btn').forEach((btn, index) => {
            if (index === currentStep && !savedAnswers[slides[index].dataset.questionId]) {
                btn.classList.remove('bg-slate-100');
                btn.classList.add('bg-primary', 'text-white');
            }
        });
    }

    // Radio button selection
    document.addEventListener('click', function(e) {
        if (e.target.closest('.answer-option')) {
            const label = e.target.closest('.answer-option');
            const radio = label.querySelector('input[type="radio"]');
            const allOptions = label.parentElement.querySelectorAll('.answer-option');

            // Uncheck all others in this group
            allOptions.forEach(opt => {
                opt.classList.remove('border-primary', 'bg-primary/5');
                opt.querySelector('.radio-indicator').classList.remove('border-primary');
                opt.querySelector('.radio-indicator > div').classList.add('hidden');
            });

            // Check selected
            radio.checked = true;
            label.classList.add('border-primary', 'bg-primary/5');
            label.querySelector('.radio-indicator').classList.add('border-primary');
            label.querySelector('.radio-indicator > div').classList.remove('hidden');

            // Auto save for multiple choice
            setTimeout(() => {
                const questionId = label.closest('.question-slide').dataset.questionId;
                saveAnswer(questionId);
            }, 500);
        }
    });

    // Navigation buttons
    document.getElementById('next-btn').addEventListener('click', () => {
        if (currentStep < totalQuestions - 1) {
            currentStep++;
            updateSlide();
        }
    });

    document.getElementById('prev-btn').addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            updateSlide();
        }
    });

    // Navigation buttons click
    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            currentStep = parseInt(this.dataset.index);
            updateSlide();
        });
    });

    // Save answer buttons
    document.querySelectorAll('.save-answer').forEach(btn => {
        btn.addEventListener('click', function() {
            const questionId = this.dataset.questionId;
            saveAnswer(questionId);
        });
    });

    // Form submit confirmation
    document.getElementById('assignment-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const savedCount = Object.keys(savedAnswers).length;
        if (savedCount < totalQuestions) {
            if (confirm(`Anda hanya menjawab ${savedCount} dari ${totalQuestions} soal. Yakin ingin mengumpulkan?`)) {
                this.submit();
            }
        } else {
            if (confirm('Yakin ingin mengumpulkan tugas ini?')) {
                this.submit();
            }
        }
    });

    // Auto save on textarea blur
    document.addEventListener('blur', function(e) {
        if (e.target.tagName === 'TEXTAREA' && e.target.name.startsWith('answers')) {
            const questionId = e.target.closest('.question-slide').dataset.questionId;
            saveAnswer(questionId);
        }
    }, true);

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateTimer();
        updateProgress();
        updateSlide();

        // Update timer every second
        setInterval(updateTimer, 1000);

        // Auto save on page unload
        window.addEventListener('beforeunload', function(e) {
            if (Object.keys(savedAnswers).length > 0) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    });
</script>
@endsection

