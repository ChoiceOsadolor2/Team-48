(function () {
  const reviewsContainer = document.getElementById('serviceReviewsList');
  const reviewsEmpty = document.getElementById('serviceReviewsEmpty');
  const reviewForm = document.getElementById('serviceReviewForm');
  const reviewMeta = document.getElementById('serviceReviewMeta');
  const reviewSuccess = document.getElementById('serviceReviewSuccess');
  const submitButton = reviewForm?.querySelector('.btn-submit');

  if (!reviewsContainer || !reviewsEmpty || !reviewForm) return;

  const fields = {
    rating: document.getElementById('service_review_rating'),
    title: document.getElementById('service_review_title'),
    message: document.getElementById('service_review_message'),
  };

  const errors = {
    rating: document.getElementById('serviceReviewRatingError'),
    title: document.getElementById('serviceReviewTitleError'),
    message: document.getElementById('serviceReviewMessageError'),
  };

  let isAuthenticated = false;
  let authUser = null;

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

  function clearErrors() {
    Object.values(errors).forEach((node) => {
      if (node) node.textContent = '';
    });
  }

  function setError(key, message) {
    if (errors[key]) {
      errors[key].textContent = message;
    }
  }

  function setSubmitState(text, disabled) {
    if (!submitButton) return;
    submitButton.textContent = text;
    submitButton.disabled = disabled;
    submitButton.style.opacity = disabled ? '0.6' : '1';
    submitButton.style.cursor = disabled ? 'not-allowed' : 'pointer';
  }

  function updateFormAccessState() {
    const enabled = isAuthenticated;

    Object.values(fields).forEach((field) => {
      if (field) field.disabled = !enabled;
    });

    setSubmitState(enabled ? 'Submit Review' : 'Sign In To Review', !enabled);

    if (reviewMeta) {
      reviewMeta.textContent = enabled
        ? `Signed in as ${authUser?.name || 'Veltrix customer'}. You can leave one overall service review here.`
        : 'Sign in to leave an overall service review.';
    }
  }

  function renderReviews(reviews) {
    reviewsContainer.innerHTML = '';

    if (!Array.isArray(reviews) || !reviews.length) {
      reviewsEmpty.textContent = 'No customer reviews yet. Be the first to share your experience.';
      reviewsEmpty.style.display = 'block';
      return;
    }

    reviewsEmpty.style.display = 'none';

    reviews.forEach((review) => {
      const card = document.createElement('div');
      card.className = 'review-card';
      card.innerHTML = `
        <div class="review-stars">${escapeHtml(renderStars(review.rating))}</div>
        <p class="review-card-title">${escapeHtml(review.title || 'Customer Review')}</p>
        <p class="review-text">"${escapeHtml(review.message || '')}"</p>
        <p class="review-author">${escapeHtml(review.user_name || 'Veltrix customer')}${review.created_at ? ` • ${escapeHtml(review.created_at)}` : ''}</p>
      `;
      reviewsContainer.appendChild(card);
    });

    if (typeof window.initReviewsSlider === 'function') {
      window.initReviewsSlider();
    }
  }

  async function loadAuthState() {
    try {
      const response = await fetch('/auth/status', { credentials: 'include' });
      if (!response.ok) throw new Error('Unable to load auth status');

      const data = await response.json();
      isAuthenticated = Boolean(data?.authenticated);
      authUser = data?.user || null;
    } catch (error) {
      console.error('Unable to load auth status:', error);
      isAuthenticated = false;
      authUser = null;
    }

    updateFormAccessState();
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
      reviewsContainer.innerHTML = '';
      reviewsEmpty.textContent = 'Unable to load reviews right now.';
      reviewsEmpty.style.display = 'block';
    }
  }

  reviewForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    clearErrors();
    if (reviewSuccess) {
      reviewSuccess.textContent = '';
      reviewSuccess.style.display = 'none';
    }

    if (!isAuthenticated) {
      setError('rating', 'Please sign in to leave a review.');
      return;
    }

    let hasError = false;

    if (!String(fields.rating?.value || '').trim()) {
      setError('rating', 'Select a rating');
      hasError = true;
    }

    if (!String(fields.title?.value || '').trim()) {
      setError('title', 'Empty Field');
      hasError = true;
    }

    if (!String(fields.message?.value || '').trim()) {
      setError('message', 'Empty Field');
      hasError = true;
    }

    if (hasError) return;

    setSubmitState('Submitting...', true);

    try {
      const response = await fetch('/service-reviews', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        credentials: 'include',
        body: JSON.stringify({
          rating: Number(fields.rating.value),
          title: fields.title.value.trim(),
          message: fields.message.value.trim(),
        }),
      });

      const payload = await response.json().catch(() => ({}));

      if (response.status === 401) {
        isAuthenticated = false;
        authUser = null;
        updateFormAccessState();
        setError('rating', payload?.message || 'Please sign in to leave a review.');
        return;
      }

      if (!response.ok) {
        if (payload?.errors) {
          Object.entries(payload.errors).forEach(([key, messages]) => {
            setError(key, Array.isArray(messages) ? messages[0] : String(messages));
          });
          return;
        }

        throw new Error(payload?.message || 'Unable to submit review right now.');
      }

      reviewForm.reset();

      if (reviewSuccess) {
        reviewSuccess.textContent = payload?.message || 'Service review submitted.';
        reviewSuccess.style.display = 'block';
      }

      await loadReviews();
    } catch (error) {
      console.error('Unable to save service review:', error);
      setError('message', error.message || 'Unable to submit review right now.');
    } finally {
      updateFormAccessState();
    }
  });

  loadAuthState();
  loadReviews();
})();
