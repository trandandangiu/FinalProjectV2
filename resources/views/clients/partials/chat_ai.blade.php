<!-- Floating Chat Widget -->
<div id="chat-widget">
    <!-- Nút toggle -->
    <div id="chat-toggle">💬</div>

    <!-- Khung chat -->
    <div id="chat-box" class="hidden">
        <div id="chat-header">
            <span>Hỗ trợ trực tuyến</span>
            <button id="chat-close">&#9587;</button>
        </div>
        <div id="chat-messages"></div>
        <div id="chat-input">
            <input type="text" id="message-input" placeholder="Nhập tin nhắn...">
            <button id="send-btn">Gửi</button>
        </div>
    </div>

    <!-- Nút scroll lên (để đồng bộ với JS) -->
    <div id="scrollUp" class="hidden">⬆️</div>
</div>

<style>
    #chat-widget {
        position: fixed;
        bottom: 120px;
        right: 3%;
        font-family: 'Segoe UI', sans-serif;
        z-index: 9999;
    }

    #chat-toggle {
        background: #4a90e2;
        color: #fff;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 26px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        transition: background 0.3s ease;
    }

    #chat-toggle:hover {
        background: #357ab8;
    }

    #chat-box {
        width: 320px;
        height: 440px;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        margin-top: 10px;
        transition: all 0.3s ease;
    }

    #chat-box.hidden {
        display: none;
    }

    #chat-header {
        background: #4a90e2;
        color: #fff;
        padding: 10px 12px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 12px 12px 0 0;
    }

    #chat-messages {
        flex: 1;
        padding: 10px;
        overflow-y: auto;
        background: #f5f7fa;
    }

    #chat-input {
        display: flex;
        border-top: 1px solid #ddd;
    }

    #chat-input input {
        flex: 1;
        padding: 10px;
        border: none;
        font-size: 14px;
    }

    #chat-input button {
        padding: 10px 14px;
        border: none;
        background: #4a90e2;
        color: #fff;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    #chat-input button:hover {
        background: #357ab8;
    }

    .user-msg {
        background: #4a90e2;
        color: #fff;
        padding: 8px 12px;
        border-radius: 16px;
        margin: 6px 0;
        text-align: right;
        max-width: 80%;
        margin-left: auto;
    }

    .bot-msg {
        background: #e0e0e0;
        color: #000;
        padding: 8px 12px;
        border-radius: 16px;
        margin: 6px 0;
        text-align: left;
        max-width: 80%;
    }

    #scrollUp {
        position: absolute;
        bottom: 60px;
        right: 0;
        background: #4a90e2;
        color: #fff;
        padding: 6px 10px;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    #scrollUp:hover {
        background: #357ab8;
    }
</style>
