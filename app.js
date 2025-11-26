// assets/js/app.js
function postData(url, data) {
  return fetch(url, { method: 'POST', body: data })
    .then(r => r.json());
}

document.addEventListener('click', function(e){
  if (e.target.matches('.btn-curtir')) {
    const id = e.target.dataset.id;
    const fd = new FormData(); fd.append('post_id', id);
    postData('like.php', fd).then(res => {
      if (!res.ok && res.error === 'login') { alert('Faça login'); window.location='login.php'; return; }
      if (res.ok) e.target.textContent = res.action==='liked'?'Descurtir':'Curtir';
    });
  }
  if (e.target.matches('.btn-comentar')) {
    const id = e.target.dataset.id;
    const input = document.querySelector('.comment-input[data-id="'+id+'"]');
    if (!input) return;
    const text = input.value.trim(); if (!text) return alert('Digite um comentário');
    const fd = new FormData(); fd.append('post_id', id); fd.append('comment', text);
    postData('comment.php', fd).then(res => {
      if (!res.ok && res.error==='login'){ alert('Faça login'); window.location='login.php'; return; }
      if (res.ok){ input.value=''; alert('Comentário enviado'); }
    });
  }
  if (e.target.closest('.image-wrap') && e.target.closest('.censurada')) {
    const wrap = e.target.closest('.image-wrap');
    if (confirm('Você tem mais de 18 anos? Clique OK para visualizar.')) {
      wrap.classList.remove('censurada');
      const overlay = wrap.querySelector('.blur-overlay'); if (overlay) overlay.remove();
    }
  }
});
