<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Course Certificate</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .certificate {
            border: 8px solid #333;
            padding: 40px;
        }
        .title {
            font-size: 32px;
            font-weight: bold;
        }
        .subtitle {
            font-size: 18px;
            margin-top: 20px;
        }
        .code {
            margin-top: 30px;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="title">Certificate of Completion</div>

        <p class="subtitle">This certifies that</p>
        <h2>{{ $studentName }}</h2>

        <p class="subtitle">has successfully completed</p>
        <h3>{{ $courseTitle }}</h3>

        <p>Instructor: {{ $instructorName }}</p>
        <p>Date: {{ $date }}</p>

        <div class="code">
            Verification Code: {{ $verificationCode }}
        </div>
    </div>
</body>
</html>