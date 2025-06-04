document.addEventListener('DOMContentLoaded', () => {
  const params = new URLSearchParams(window.location.search);
  if (params.get('success') === '1') {
    const toast = document.createElement('div');
    toast.className = 'toast-success';
    toast.textContent = 'Registration successful!';
    const header = document.querySelector('header');
    const headerBottom = header.getBoundingClientRect().bottom;
    Object.assign(toast.style, {
      position: 'fixed',
      top: `${headerBottom + 8}px`,
      left: '50%',
      transform: 'translateX(-50%)',
      background: '#2ecc71',
      color: '#fff',
      padding: '12px 24px',
      borderRadius: '4px',
      zIndex: '1000',
      boxShadow: '0 2px 8px rgba(0,0,0,0.2)'
    });
    document.body.append(toast);
    setTimeout(() => toast.remove(), 3000);
  }
});