document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.remove-teacher-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault()
      const teacherId = btn.dataset.teacherId
      if (!confirm('Are you sure you want to remove this teacher?')) return

      const url = `${window.location.origin}/index.php?page=remove-teacher`
      console.log('Removing teacher:', teacherId, '→', url)

      try {
        const response = await fetch(url, {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: `teacher_id=${encodeURIComponent(teacherId)}`
        })

        console.log('Response status:', response.status)
        const result = await response.json()
        console.log('Result JSON:', result)

        if (result.success) {
          btn.closest('.teacher-card').remove()
        } else {
          alert(result.error || 'Error removing teacher')
        }
      } catch (err) {
        console.error('Fetch error:', err)
        alert('Network error')
      }
    })
  })
})