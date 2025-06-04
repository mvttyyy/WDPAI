document.addEventListener('DOMContentLoaded', () => {
  const chatId    = document.querySelector('#chat').dataset.chatId;
  const userId    = document.querySelector('#chat').dataset.userId;
  const listEl    = document.querySelector('#messages');
  const formEl    = document.querySelector('#msg-form');
  const inputEl   = formEl.querySelector('input[name="text"]');

  async function loadMessages() {
    const res = await fetch(`/index.php?page=chat-messages&chat_id=${chatId}`);
    const msgs = await res.json();
    listEl.innerHTML = msgs.map(m => `<li><strong>${m.sender}</strong>: ${m.text}</li>`).join('');
    listEl.scrollTop = listEl.scrollHeight;
  }

  formEl.addEventListener('submit', async e => {
    e.preventDefault();
    const text = inputEl.value.trim();
    if (!text) return;
    await fetch('/index.php?page=chat-message', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({chat_id:chatId, user_id:userId, text})
    });
    inputEl.value = '';
    loadMessages();
  });

  loadMessages();
  setInterval(loadMessages, 5000);
});