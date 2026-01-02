<!DOCTYPE html>
<html>
<head>
    <title>Kích hoạt tài khoản</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .activation-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Kích Hoạt Tài Khoản</h2>
        </div>
        
        <h3>Xin chào, {{ $user->name }}</h3>
        
        <p>Cảm ơn bạn đã đăng ký sử dụng website của chúng tôi. Để kích hoạt tài khoản của bạn, vui lòng nhấp vào liên kết dưới đây:</p>
        
        <p style="text-align: center;">
            <a href="{{ url('/activate/' . $token) }}" class="activation-button">
                Kích hoạt tài khoản
            </a>
        </p>
        
        <p>Nếu nút trên không hoạt động, bạn có thể sao chép và dán đường dẫn sau vào trình duyệt:</p>
        <p style="background-color: #eee; padding: 10px; border-radius: 4px; word-break: break-all;">
            {{ url('/activate/' . $token) }}
        </p>
        
        <p>Liên kết kích hoạt sẽ hết hạn sau 24 giờ.</p>
        
        <div class="footer">
            <p>Trân trọng,</p>
            <p>Bộ phận hỗ trợ khách hàng</p>
        </div>
    </div>
</body>
</html>