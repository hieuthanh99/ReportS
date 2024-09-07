<!-- resources/views/errors/500.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
        .container { max-width: 600px; margin: auto; }
        .redirect { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>500 - Server Error</h1>
        <p>Oops! Something went wrong on our end.</p>
        <div class="redirect">
            <p>Quay về <a href="{{ url('/') }}">trang chủ</a> sau <span id="countdown">5</span> giây...</p>
        </div>
    </div>

    <script>
        let countdownElement = document.getElementById('countdown');
        let countdown = 5;
        setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                window.location.href = "{{ url('/') }}";
            } else {
                countdownElement.textContent = countdown;
            }
        }, 1000);
    </script>
</body>
</html>
