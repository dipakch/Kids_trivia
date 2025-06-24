<!DOCTYPE html>
<html>
<head>
    <title>Select Category and Level</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Fredoka One', cursive, Arial, sans-serif;
            background: #fff4e6;
            padding: 40px 20px;
            max-width: 450px;
            margin: auto;
            color: #444;
        }
        h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 40px;
            color: #ff7f50;
            text-shadow: 1px 1px 2px #ffb6a1;
        }
        label {
            font-size: 1.4rem;
            font-weight: 700;
            display: block;
            margin-bottom: 10px;
            color: #f08080;
        }
        select {
            width: 100%;
            padding: 14px;
            font-size: 1.25rem;
            border-radius: 12px;
            border: 2px solid #ff7f50;
            margin-bottom: 30px;
            background: #fff8f0;
            cursor: pointer;
            transition: border-color 0.3s;
        }
        select:focus {
            border-color: #ff4500;
            outline: none;
            background: #fff0e0;
        }
        button {
            width: 100%;
            background: #ff7f50;
            color: white;
            font-size: 1.8rem;
            padding: 18px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            box-shadow: 0 8px 15px rgba(255, 127, 80, 0.4);
            transition: transform 0.2s, box-shadow 0.2s;
            user-select: none;
        }
        button:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 20px rgba(255, 127, 80, 0.7);
        }
        button:active {
            transform: scale(0.95);
            box-shadow: 0 6px 10px rgba(255, 127, 80, 0.3);
        }
        .alert {
            background-color: #ffe1df;
            color: #9b2d26;
            font-weight: 700;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 1px 1px 5px #d99a97;
        }
        .alert-success {
            background-color: #d1f7d1;
            color: #247524;
        }
        .errors {
            color: #d9534f;
            font-weight: 700;
            margin-bottom: 15px;
            list-style: none;
            padding-left: 0;
        }
        .errors li::before {
            content: "⚠️ ";
        }
    </style>
</head>
<body>

    <h2>Select Category & Difficulty</h2>

    @if(session('error'))
        <div class="alert">{{ session('error') }}</div>
    @endif

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @if ($errors->any())
        <ul class="errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('quiz.startQuiz') }}">
        @csrf

        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
            <option value="" disabled selected>Select category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <label for="level_id">Difficulty Level:</label>
        <select id="level_id" name="level_id" required>
            <option value="" disabled selected>Select difficulty</option>
            @foreach ($levels as $level)
                <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                    {{ $level->name }}
                </option>
            @endforeach
        </select>

        <button type="submit">Start Quiz</button>
    </form>

</body>
</html>
