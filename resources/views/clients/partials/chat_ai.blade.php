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
    bottom: 140px;
    right: 3%;
    font-family: Arial, sans-serif;
    z-index: 9999;
  }

  #chat-toggle {
    background: #007bff;
    color: #fff;
    border-radius: 50%;
    width: 55px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 24px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.2);
  }

  #chat-box {
    width: 300px;
    height: 400px;
    border-radius: 10px;
    background: #fff;
    box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    margin-top: 10px;
  }

  #chat-box.hidden { display: none; }
  #scrollUp.hidden { display: none; }

  #chat-header {
    background: #007bff;
    color: #fff;
    padding: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 8px 8px 0 0;
  }

  #chat-messages {
    flex: 1;
    padding: 8px;
    overflow-y: auto;
    background: #f9f9f9;
  }

  #chat-input {
    display: flex;
    border-top: 1px solid #ccc;
  }

  #chat-input input {
    flex: 1;
    padding: 8px;
    border: none;
  }

  #chat-input button {
    padding: 8px 12px;
    border: none;
    background: #007bff;
    color: #fff;
    cursor: pointer;
  }

  /* Bong bóng chat */
  .user-msg {
    background: #007bff;
    color: #fff;
    padding: 6px 10px;
    border-radius: 10px;
    margin: 4px 0;
    text-align: right;
  }

  .bot-msg {
    background: #e9ecef;
    color: #000;
    padding: 6px 10px;
    border-radius: 10px;
    margin: 4px 0;
    text-align: left;
  }

  #scrollUp {
    position: absolute;
    bottom: 60px;
    right: 0;
    background: #007bff;
    color: #fff;
    padding: 6px 10px;
    border-radius: 5px;
    cursor: pointer;
  }
</style>
