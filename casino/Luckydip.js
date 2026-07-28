window.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.flip-card');
  const resetBtn = document.getElementById('resetBtn');

  cards.forEach(card => {
    card.addEventListener('click', () => {
      // 裏返し済みなら処理しない（カード1回だけめくれるように）
      if (card.classList.contains('flipped')) return;

      card.classList.add('flipped');

      const result = card.getAttribute('data-result');
      console.log('カード結果:', result);
    });
  });

  if (!resetBtn) {
    console.error('resetBtnが見つかりません');
    return;
  }

  resetBtn.addEventListener('click', () => {
    console.log('リセットボタンが押されました');
    location.reload(); // ページ再読み込みでシャッフル反映
  });
});
