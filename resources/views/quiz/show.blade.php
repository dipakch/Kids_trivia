{{-- 
<pre>
Options: {{ print_r($options, true) }}
Correct Answer: {{ $correct_answer }}
Mode: {{ $mode }}
</pre>
--}}

<!DOCTYPE html>
<html>
<head>
    <title>Kids Quiz</title>

    <!-- Alpine.js for frontend reactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Fonts: Fredoka One for English, Noto Sans Devanagari for Nepali -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari&display=swap" rel="stylesheet">

    <style>
        /* Question heading color */
        .question-heading {
            color: royalblue;
        }

        /* Nepali font for Nepali text */
        .nepali-text {
            font-family: 'Noto Sans Devanagari', sans-serif;
        }

        /* Background gradient */
        body {
            font-family: 'Fredoka One', cursive;
            background: linear-gradient(to right, #fbc2eb, #a6c1ee);
            margin: 0;
            padding: 0;
        }

        /* Container styling */
        .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 40px 20px;
            text-align: center;
            color: #333;
        }

        h2 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 1px 1px 2px #aaa;
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        /* Image box styling */
        .image-box {
            background: #fff;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            margin: 30px auto;
            width: 600px;
            height: 600px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image-box img {
            width: 100%;
            height: 100%;
            border-radius: 15px;
            object-fit: contain;
        }

        /* Option buttons */
        .option-button {
            background: #ff7f50;
            color: white;
            padding: 18px 32px;
            margin: 10px;
            font-size: 1.2rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 5px 10px rgba(255, 111, 97, 0.3);
        }

        .option-button:hover {
            transform: scale(1.05);
            background: #ff4c4c;
        }

        /* Modal backdrop */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 40;
        }

        /* Modal box */
        .modal-box {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
            z-index: 50;
            width: 300px;
            text-align: center;
        }
    </style>
</head>
<body x-data="quizComponent()">

    <div class="container">
        <!-- Question heading (language depends on mode) -->
        <h2 class="question-heading">
            @if($mode === 'nepali-english')
                <span class="nepali-text">यो के हो ?</span>
            @else
                What is this?
            @endif
        </h2>

        <p>Question {{ $progress }} of {{ $total }}</p>

        <div class="image-box">
            <img src="{{ $question->image_url }}" alt="Quiz Image">
        </div>

        <div>
            <!-- Loop through options using Alpine.js -->
            <template x-for="option in options" :key="option">
                <button class="option-button" x-text="option" @click="checkAnswer(option)"></button>
            </template>
        </div>
    </div>

    <!-- Modal for feedback -->
    <template x-if="showModal">
        <div>
            <div class="modal-backdrop"></div>
            <div class="modal-box">
                <!-- Correct answer modal -->
                <template x-if="result === 'correct'">
                    <div>
                        <h2 style="color: green;">🎉 Correct!</h2>
                        <button @click="nextQuestion()" class="option-button" style="background-color:#28a745;">
                            Next
                        </button>
                    </div>
                </template>

                <!-- Incorrect answer modal -->
                <template x-if="result === 'incorrect'">
                    <div>
                        <h2 style="color: red;">❌ Try Again</h2>
                        <div class="mt-4 flex justify-around">
                            <button @click="tryAgain()" class="option-button" style="background:#ffc107;">
                                Try Again
                            </button>
                            <button @click="skipQuestion()" class="option-button" style="background:#6c757d;">
                                Skip
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>

    <script>
        function quizComponent() {
            return {
                showModal: false,
                result: '',
                correct_answer: @json($correct_answer),
                options: @json($options),

                checkAnswer(option) {
                    fetch('{{ route('quiz.answer') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            selected_option: option,
                            correct_answer: this.correct_answer
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.result = data.result;
                        this.showModal = true;

                        // Automatically go to next question if correct after 1 second delay
                        if (data.result === 'correct') {
                            setTimeout(() => {
                                window.location.href = "{{ route('quiz.show') }}";
                            }, 1000);
                        }
                    })
                    .catch(e => console.error('Error:', e));
                },

                nextQuestion() {
                    // Just reload to get next question
                    window.location.href = "{{ route('quiz.show') }}";
                },

                skipQuestion() {
                    // Async POST skip request with fetch API
                    fetch('{{ route("quiz.skip") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(res => {
                        if (res.ok) {
                            // After skip success, reload question page
                            window.location.href = "{{ route('quiz.show') }}";
                        } else {
                            console.error('Skip request failed');
                        }
                    })
                    .catch(e => console.error('Error:', e));
                },

                tryAgain() {
                    this.result = '';
                    this.showModal = false;
                }
            }
        }
    </script>
</body>
</html>
