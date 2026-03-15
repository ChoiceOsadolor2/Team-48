(function () {
  const reviewsContainer = document.getElementById('serviceReviewsList');
  const reviewsEmpty = document.getElementById('serviceReviewsEmpty');
  const reviewForm = document.getElementById('serviceReviewForm');
  const reviewMeta = document.getElementById('serviceReviewMeta');
  const reviewSuccess = document.getElementById('serviceReviewSuccess');
  const submitButton = reviewForm?.querySelector('.btn-submit');

  if (!reviewsContainer || !reviewsEmpty) return;

  function escapeHtml(value) {
    return String(value ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function renderStars(rating) {
    const safeRating = Math.max(0, Math.min(5, Number(rating || 0)));
    return `${'\u2605'.repeat(safeRating)}${'\u2606'.repeat(Math.max(0, 5 - safeRating))}`;
  }

  function expandReviewsForSlider(reviews) {
    const source = Array.isArray(reviews) ? reviews.filter(Boolean) : [];

    if (source.length <= 0) return [];
    if (source.length >= 6) return source;

    const expanded = [];

    while (expanded.length < 6) {
      source.forEach((review, index) => {
        if (expanded.length >= 6) return;

        expanded.push({
          ...review,
          _renderKey: `${review.id ?? 'review'}-${expanded.length}-${index}`,
        });
      });
    }

    return expanded;
  }

  function renderReviews(reviews) {
    reviewsContainer.innerHTML = '';

    const hasRealReviews = Array.isArray(reviews) && reviews.length > 0;

    if (!hasRealReviews) {
      reviewsEmpty.textContent = '';
      reviewsEmpty.style.display = 'none';
      for (let i = 0; i < 3; i += 1) {
        const card = document.createElement('div');
        card.className = 'review-card review-card--placeholder';
        reviewsContainer.appendChild(card);
      }
    } else {
      reviewsEmpty.style.display = 'none';

      expandReviewsForSlider(reviews).forEach((review, index) => {
        const card = document.createElement('div');
        card.className = 'review-card';
        card.dataset.reviewSource = String(review.id ?? index);
        card.innerHTML = `
          <div class="review-stars">${escapeHtml(renderStars(review.rating))}</div>
          <p class="review-text">"${escapeHtml(review.message || '')}"</p>
          <p class="review-author">${escapeHtml(review.user_name || 'Veltrix customer')}</p>
        `;
        reviewsContainer.appendChild(card);
      });
    }

    if (typeof window.initReviewsSlider === 'function') {
      window.initReviewsSlider();
    }
  }

  async function loadReviews() {
    reviewsEmpty.textContent = 'Loading reviews...';
    reviewsEmpty.style.display = 'block';

    try {
      const response = await fetch('/service-reviews', { credentials: 'include' });
      if (!response.ok) throw new Error('Unable to load service reviews');

      const data = await response.json();
      renderReviews(Array.isArray(data?.reviews) ? data.reviews : []);
    } catch (error) {
      console.error('Unable to load service reviews:', error);
      renderReviews([]);
    }
  }

  loadReviews();
})();
