<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to PayFast...</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 400px;
        }
        .logo {
            width: 150px;
            margin-bottom: 30px;
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 15px;
        }
        p {
            color: #666;
            margin-bottom: 30px;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .btn {
            display: inline-block;
            padding: 15px 40px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #5a6fd6;
        }
        .secure {
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }
        .secure i {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://payfast.io/wp-content/uploads/2021/01/payfast-logo.svg" alt="PayFast" class="logo">
        <div class="spinner"></div>
        <h1>Redirecting to PayFast</h1>
        <p>Please wait while we redirect you to the secure PayFast payment page...</p>

        <form id="payfast-form" action="{{ $payfast_url }}" method="POST">
            @foreach($data as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <noscript>
                <button type="submit" class="btn">Click here to continue to PayFast</button>
            </noscript>
        </form>

        <p class="secure">
            <i class="fas fa-lock"></i> Secure 256-bit SSL encryption
        </p>
    </div>

    <script>
        // Auto-submit the form after a short delay
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('payfast-form').submit();
            }, 1500);
        });
    </script>
</body>
</html>
