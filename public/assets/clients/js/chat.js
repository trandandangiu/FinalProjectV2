$(function () {
  const $chatBox = $("#chat-box");
  const $chatWidget = $("#chat-widget");
  const $messageInput = $("#message-input");
  const $chatMessages = $("#chat-messages");

  // Mở/đóng chat box
  $("#chat-toggle").on("click", function (e) {
    e.stopPropagation();
    $chatBox.toggleClass("hidden");
    
    // Focus vào input khi mở chat
    if (!$chatBox.hasClass("hidden")) {
      $messageInput.focus();
      loadMessages(); // Tải tin nhắn cũ
    }
  });

  // Đóng chat box
  $("#chat-close").on("click", function () {
    $chatBox.addClass("hidden");
  });

  // Gửi tin nhắn
  $("#send-btn").on("click", sendMessage);
  
  $messageInput.on("keypress", function (e) {
    if (e.which === 13) { // Enter key
      sendMessage();
      return false;
    }
  });

  function sendMessage() {
    const msg = $messageInput.val().trim();
    if (!msg) return;

    // Thêm tin nhắn user vào chat
    appendMessage({ sender: "user", message: msg });
    $messageInput.val("");

    // Hiển thị typing indicator
    showTyping();

    // Giả lập phản hồi từ bot (thay bằng API thật nếu cần)
    setTimeout(function() {
      hideTyping();
      const botReply = getBotReply(msg);
      appendMessage({ sender: "bot", message: botReply });
    }, 1000);
  }

  function getBotReply(userMessage) {
    const msg = userMessage.toLowerCase();
    const replies = {
      'chào': 'Chào bạn! Rất vui được hỗ trợ bạn. Bạn cần giúp gì ạ?',
      'cảm ơn': 'Dạ không có gì! Rất hân hạnh được giúp đỡ bạn. 😊',
      'giá': 'Vui lòng cho tôi biết bạn quan tâm đến sản phẩm/dịch vụ nào để tôi báo giá chính xác nhé!',
      'hỗ trợ': 'Tôi có thể hỗ trợ bạn: tư vấn sản phẩm, báo giá, giải đáp thắc mắc. Bạn cần gì ạ?'
    };
    
    for (let key in replies) {
      if (msg.includes(key)) {
        return replies[key];
      }
    }
    
    return `Cảm ơn bạn đã liên hệ! Nhân viên hỗ trợ sẽ phản hồi trong giây lát. Tin nhắn của bạn: "${userMessage}"`;
  }

  function loadMessages() {
    // Nếu bạn muốn tải tin nhắn từ server
    // $.get("/chat/messages", function(msgs) {
    //   $chatMessages.empty();
    //   if (!msgs || !msgs.length) {
    //     appendMessage({ sender: "bot", message: "Xin chào 👋, tôi có thể giúp gì cho bạn?" });
    //     return;
    //   }
    //   msgs.forEach(appendMessage);
    //   scrollToBottom();
    // });
    
    // Tạm thời chỉ hiển thị welcome message nếu chưa có tin nhắn
    if ($chatMessages.children().length === 0) {
      appendMessage({ sender: "bot", message: "Xin chào! Tôi có thể giúp gì cho bạn hôm nay? 😊" });
    }
  }

  function appendMessage(m) {
    let html = '';
    if (m.sender === "user") {
      html = `<div class="user-msg"><div class="user-bubble">${escapeHtml(m.message)}</div></div>`;
    } else {
      html = `<div class="bot-msg">
        <div class="bot-avatar">🤖</div>
        <div class="bot-bubble">${escapeHtml(m.message)}</div>
      </div>`;
    }
    $chatMessages.append(html);
    scrollToBottom();
  }

  function scrollToBottom() {
    $chatMessages.scrollTop($chatMessages[0].scrollHeight);
  }

  function escapeHtml(text) {
    return $("<div>").text(text).html();
  }

  function showTyping() {
    // Tạo typing indicator nếu chưa có
    if ($("#typing-indicator").length === 0) {
      $chatMessages.append(`
        <div id="typing-indicator" class="typing-indicator">
          <span></span><span></span><span></span>
          <span class="typing-text">Đang soạn...</span>
        </div>
      `);
    } else {
      $("#typing-indicator").removeClass("hidden");
    }
    scrollToBottom();
  }

  function hideTyping() {
    $("#typing-indicator").addClass("hidden");
  }
});