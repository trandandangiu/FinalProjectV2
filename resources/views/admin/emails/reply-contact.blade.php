<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phản hồi liên hệ</title>
    <style>
    body { font-family: sans-serif; line-height: 1.5; color: #333; }
    .email-content { border: 1px solid #eee; padding: 20px; border-radius: 5px; }
    h2 { color: #2d3748; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    .message { white-space: pre-line; } /* Giữ các dòng xuống hàng tự nhiên */
</style>

<body>
    <div class="email-content">
        <h2>Phản hồi từ quản trị viên</h2>
        <p class="message">
            {!! $content !!} </p>
    </div>
</body>
</body>
</html>