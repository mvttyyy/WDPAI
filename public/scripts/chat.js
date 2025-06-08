document.addEventListener('DOMContentLoaded', () => {
  const chatBox = document.querySelector('.chat-box')
  if (chatBox) {
    chatBox.scrollTop = chatBox.scrollHeight
    setTimeout(() => {
      chatBox.scrollTop = chatBox.scrollHeight
    }, 100)
  }
})