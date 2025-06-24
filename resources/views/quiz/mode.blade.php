<!DOCTYPE html>
<html>
<head>
    <title>Select Quiz Language Mode</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Fredoka One', cursive, Arial, sans-serif;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #333;
        }
        h2 {
            font-size: 2.8rem;
            margin-bottom: 40px;
            text-shadow: 1px 1px 2px #ffa07a;
        }
        form {
            display: flex;
            gap: 30px;
        }
        button {
            background: #ff6f61;
            border: none;
            color: white;
            padding: 25px 45px;
            font-size: 1.8rem;
            border-radius: 15px;
            cursor: pointer;
            box-shadow: 0 8px 15px rgba(255, 111, 97, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
            user-select: none;
        }
        button:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 20px rgba(255, 111, 97, 0.5);
        }
        button:active {
            transform: scale(0.95);
            box-shadow: 0 6px 10px rgba(255, 111, 97, 0.2);
        }
        button span.emoji {
            font-size: 2.8rem;
            line-height: 1;
        }
        .alert {
            margin-bottom: 20px;
            font-size: 1.2rem;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 12px;
            background-color: #ffccd5;
            color: #b33232;
            box-shadow: 1px 1px 5px #d98c8c;
        }
    </style>
</head>
<body>

    <h2>Choose Language Mode</h2>

    @if(session('error'))
        <div class="alert">{{ session('error') }}</div>
    @endif
    @if(session('message'))
        <div class="alert" style="background-color:#d1f7d1; color:#247524;">{{ session('message') }}</div>
    @endif

    <form method="POST" action="{{ route('quiz.setMode') }}">
        @csrf
        <button type="submit" name="mode" value="english-nepali">
            <span class="emoji">📖</span> English → Nepali
        </button>
        <button type="submit" name="mode" value="nepali-english">
            <span class="emoji">📚</span> Nepali → English
        </button>
    </form>

</body>
</html>
