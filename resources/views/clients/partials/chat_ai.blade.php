<!-- Floating Chat Widget - Professional Version -->
<div id="chat-widget">
    <div id="chat-toggle">
        <span class="toggle-icon">💬</span>
        <span class="toggle-text">Chat với chúng tôi</span>
    </div>

    <div id="chat-box" class="hidden">
        <div id="chat-header">
            <div class="header-info">
                <div class="status-dot"></div>
                <div>
                    <span class="header-title">Hỗ trợ trực tuyến</span>
                    <span class="header-status">Online · Trả lời ngay</span>
                </div>
            </div>
            <button id="chat-close">✕</button>
        </div>

        <div id="chat-messages">
            <div class="welcome-msg">
                <div class="bot-avatar">🤖</div>
                <div class="bot-content">
                    <div class="bot-name">Trợ lý ảo</div>
                    <div class="bot-text">Xin chào! Tôi có thể giúp gì cho bạn hôm nay? 😊</div>
                </div>
            </div>
        </div>

        <div class="typing-indicator hidden" id="typing-indicator">
            <span></span><span></span><span></span>
            <span class="typing-text">Đang soạn...</span>
        </div>

        <div id="chat-input-container">
            <div id="chat-input">
                <input type="text" id="message-input" placeholder="Nhập tin nhắn của bạn..." autocomplete="off">
                <button id="send-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </div>
            <div class="input-note">Nhấn Enter để gửi</div>
        </div>
    </div>
</div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    #chat-widget {
        position: fixed;
        bottom: 30px;
        right: 30px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
        z-index: 9999;
    }

    /* Nút toggle chuyên nghiệp */
    #chat-toggle {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border-radius: 50px;
        padding: 12px 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    #chat-toggle:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(102, 126, 234, 0.5);
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    .toggle-icon {
        font-size: 24px;
    }

    .toggle-text {
        letter-spacing: 0.5px;
    }

    /* Chat box */
    #chat-box {
        width: 380px;
        height: 550px;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        margin-bottom: 15px;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #chat-box.hidden {
        display: none;
    }

    /* Header */
    #chat-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .status-dot {
        width: 10px;
        height: 10px;
        background: #4ade80;
        border-radius: 50%;
        animation: pulse 2s infinite;
        box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.7);
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.7);
        }
        70% {
            box-shadow: 0 0 0 6px rgba(74, 222, 128, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(74, 222, 128, 0);
        }
    }

    .header-title {
        font-size: 16px;
        font-weight: 600;
        display: block;
    }

    .header-status {
        font-size: 12px;
        opacity: 0.9;
        display: block;
    }

    #chat-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    #chat-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    /* Messages area */
    #chat-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f8f9fa;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* Custom scrollbar */
    #chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    #chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    #chat-messages::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    /* Welcome message */
    .welcome-msg {
        display: flex;
        gap: 12px;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .bot-avatar {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .bot-content {
        flex: 1;
    }

    .bot-name {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 4px;
    }

    .bot-text {
        background: #fff;
        padding: 10px 14px;
        border-radius: 12px;
        color: #1e293b;
        font-size: 14px;
        line-height: 1.5;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        display: inline-block;
    }

    /* User message */
    .user-msg {
        display: flex;
        justify-content: flex-end;
        animation: fadeIn 0.3s ease;
    }

    .user-bubble {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 10px 14px;
        border-radius: 18px;
        max-width: 80%;
        font-size: 14px;
        line-height: 1.5;
        box-shadow: 0 2px 5px rgba(102, 126, 234, 0.3);
    }

    /* Bot message */
    .bot-msg {
        display: flex;
        gap: 12px;
        animation: fadeIn 0.3s ease;
    }

    .bot-msg .bot-bubble {
        background: #fff;
        padding: 10px 14px;
        border-radius: 12px;
        color: #1e293b;
        font-size: 14px;
        line-height: 1.5;
        max-width: 80%;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    /* Typing indicator */
    .typing-indicator {
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        margin: 0 20px 12px 20px;
        border-radius: 12px;
        width: fit-content;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .typing-indicator span:not(.typing-text) {
        width: 8px;
        height: 8px;
        background: #cbd5e1;
        border-radius: 50%;
        animation: typing 1.4s infinite;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {
        0%, 60%, 100% {
            transform: translateY(0);
            opacity: 0.4;
        }
        30% {
            transform: translateY(-10px);
            opacity: 1;
        }
    }

    .typing-text {
        font-size: 12px;
        color: #94a3b8;
        margin-left: 4px;
    }

    .typing-indicator.hidden {
        display: none;
    }

    /* Input area */
    #chat-input-container {
        border-top: 1px solid #e2e8f0;
        background: #fff;
        padding: 12px;
    }

    #chat-input {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    #chat-input input {
        flex: 1;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        outline: none;
        transition: all 0.3s;
        font-family: inherit;
    }

    #chat-input input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    #send-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: #fff;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    #send-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .input-note {
        font-size: 10px;
        color: #94a3b8;
        text-align: center;
        margin-top: 8px;
    }
</style>

{{-- <script>
    const chatToggle = document.getElementById('chat-toggle');
    const chatBox = document.getElementById('chat-box');
    const chatClose = document.getElementById('chat-close');
    const sendBtn = document.getElementById('send-btn');
    const messageInput = document.getElementById('message-input');
    const chatMessages = document.getElementById('chat-messages');
    const typingIndicator = document.getElementById('typing-indicator');

    // Toggle chat
    chatToggle.addEventListener('click', () => {
        chatBox.classList.toggle('hidden');
        if (!chatBox.classList.contains('hidden')) {
            messageInput.focus();
        }
    });

    chatClose.addEventListener('click', () => {
        chatBox.classList.add('hidden');
    });

    // Send message
    function addUserMessage(text) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'user-msg';
        messageDiv.innerHTML = `<div class="user-bubble">${escapeHtml(text)}</div>`;
        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    function addBotMessage(text) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'bot-msg';
        messageDiv.innerHTML = `
            <div class="bot-avatar">🤖</div>
            <div class="bot-bubble">${escapeHtml(text)}</div>
        `;
        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function showTyping() {
        typingIndicator.classList.remove('hidden');
        scrollToBottom();
    }

    function hideTyping() {
        typingIndicator.classList.add('hidden');
    }

    // Simulate bot response
    async function sendMessage() {
        const message = messageInput.value.trim();
        if (!message) return;

        addUserMessage(message);
        messageInput.value = '';
        messageInput.style.height = 'auto';

        showTyping();

        // Simulate AI response (replace with actual API call)
        setTimeout(() => {
            hideTyping();
            const botReply = getBotReply(message);
            addBotMessage(botReply);
        }, 1000);
    }

    function getBotReply(userMessage) {
        const replies = {
            'chào': 'Chào bạn! Rất vui được hỗ trợ bạn. Bạn cần giúp gì ạ?',
            'cảm ơn': 'Dạ không có gì! Rất hân hạnh được giúp đỡ bạn. 😊',
            'giá': 'Vui lòng cho tôi biết bạn quan tâm đến sản phẩm/dịch vụ nào để tôi báo giá chính xác nhé!',
            'hỗ trợ': 'Tôi có thể hỗ trợ bạn: tư vấn sản phẩm, báo giá, giải đáp thắc mắc. Bạn cần gì ạ?'
        };
        
        for (let [key, reply] of Object.entries(replies)) {
            if (userMessage.toLowerCase().includes(key)) {
                return reply;
            }
        }
        
        return `Cảm ơn bạn đã liên hệ! Nhân viên hỗ trợ sẽ phản hồi trong giây lát. Tin nhắn của bạn: "${userMessage}"`;
    }

    sendBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
</script> --}}