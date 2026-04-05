$(function () {
  const $chatBox = $("#chat-box");
  const $chatWidget = $("#chat-widget");
  const $scrollUp = $("#scrollUp");
  const $messageInput = $("#message-input");
  const $chatMessages = $("#chat-messages");

  $("#chat-toggle").on("click", function () {
    $chatBox.toggleClass("hidden");
    $scrollUp.toggleClass("hidden");
    $chatWidget.css("bottom", $chatBox.hasClass("hidden") ? "140px" : "20px");
    if (!$chatBox.hasClass("hidden")) loadMessages();
  });

  $("#chat-close").on("click", function () {
    $chatBox.addClass("hidden");
    $chatWidget.css("bottom", "140px");
    $scrollUp.show();
  });

  $("#send-btn").on("click", sendMessage);
  $messageInput.on("keypress", function (e) {
    if (e.which === 13) {
      sendMessage();
      return false;
    }
  });

  function sendMessage() {
    const msg = $messageInput.val().trim();
    if (!msg) return;

    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });

    $.post("/chat/send", { message: msg })
      .done(function (res) {
        if (res.user) appendMessage(res.user);
        if (res.bot) appendMessage(res.bot);
        $messageInput.val("");
      })
      .fail(function () {
        appendMessage({ sender: "bot", message: "❌ Không gửi được tin nhắn." });
      });
  }

  function loadMessages() {
    $chatMessages.empty();
    $.get("/chat/messages", function (msgs) {
      if (!msgs || !msgs.length) {
        appendMessage({ sender: "bot", message: "Xin chào 👋, tôi có thể giúp gì cho bạn?" });
        return;
      }
      msgs.forEach(appendMessage);
      scrollToBottom();
    });
  }

  function appendMessage(m) {
    const cls = m.sender === "user" ? "user-msg" : "bot-msg";
    $chatMessages.append(`<div class="${cls}">${escapeHtml(m.message)}</div>`);
    scrollToBottom();
  }

  function scrollToBottom() {
    $chatMessages.scrollTop($chatMessages[0].scrollHeight);
  }

  function escapeHtml(text) {
    return $("<div>").text(text).html();
  }
});
