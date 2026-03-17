(function () {
  const form = document.getElementById('service_review_form');
  if (!form) return;

  const getCsrfToken = () => {
    const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (metaToken) return metaToken;

    const cookieMatch = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
    return cookieMatch ? decodeURIComponent(cookieMatch[1]) : '';
  };

  const ensureCsrfToken = async () => {
    const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (metaToken) return metaToken;

    try {
      const response = await fetch('/csrf-token', {
        method: 'GET',
        headers: { Accept: 'application/json' },
        credentials: 'include',
      });

      if (!response.ok) return '';

      const data = await response.json().catch(() => ({}));
      const refreshedToken = data?.token || getCsrfToken();

      if (refreshedToken) {
        let metaTag = document.querySelector('meta[name="csrf-token"]');
        if (!metaTag) {
          metaTag = document.createElement('meta');
          metaTag.setAttribute('name', 'csrf-token');
          document.head.appendChild(metaTag);
        }
        metaTag.setAttribute('content', refreshedToken);
      }

      return refreshedToken || getCsrfToken() || '';
    } catch (error) {
      console.error('Failed to refresh CSRF token:', error);
      return getCsrfToken() || '';
    }
  };

  const fields = {
    name: document.getElementById('service_review_name'),
    rating: document.getElementById('service_review_rating'),
    message: document.getElementById('service_review_message'),
  };

  const errors = {
    name: document.querySelector('[data-error-for="name"]'),
    rating: document.querySelector('[data-error-for="rating"]'),
    message: document.querySelector('[data-error-for="message"]'),
  };

  const params = new URLSearchParams(window.location.search);
  const orderItemId = String(params.get('order_item_id') || '').trim();
  const submitButton = form.querySelector('.review-submit-button');

  const ratingPicker = document.getElementById('service_review_rating_picker');
  const ratingStarPicker = ratingPicker?.querySelector('.review-star-picker');

  let activeOrderItemId = '';
  let canSubmitReview = false;
  let syncRatingPlaceholderState = () => {};

  const clearErrors = () => {
    Object.values(errors).forEach((node) => {
      if (node) node.textContent = '';
    });
  };

  const setError = (key, message = 'Empty Field') => {
    if (errors[key]) {
      errors[key].textContent = message;
    }
  };

  const setEditableState = (enabled) => {
    ['rating', 'message'].forEach((key) => {
      if (fields[key]) fields[key].disabled = !enabled;
    });
  };

  const setSubmitState = (text, disabled) => {
    if (!submitButton) return;
    submitButton.textContent = text;
    submitButton.disabled = disabled;
    submitButton.style.opacity = disabled ? '0.6' : '1';
    submitButton.style.cursor = disabled ? 'not-allowed' : 'pointer';
  };

  const lockReviewForm = (message, buttonText = 'Order Required') => {
    canSubmitReview = false;
    setEditableState(false);
    setSubmitState(buttonText, true);
    clearErrors();
    setError('name', message);
    syncRatingPlaceholderState();
  };

  const initRatingPicker = () => {
    if (!fields.rating || !ratingPicker || !ratingStarPicker) return;

    ratingStarPicker.innerHTML = '';

    const caption = document.createElement('div');
    caption.className = 'review-rating-caption';
    ratingPicker.appendChild(caption);

    const formatRatingLabel = (value) => {
      const numeric = Number(value || 0);
      if (!numeric) return 'Hover to choose your rating';
      return `${numeric} / 5 stars`;
    };

    const updateStars = (previewValue = null) => {
      const selectedValue = Number(fields.rating.value || 0);
      const activeValue = previewValue === null ? selectedValue : Number(previewValue || 0);

      ratingStarPicker.querySelectorAll('.review-star-segment').forEach((button) => {
        const segmentValue = Number(button.dataset.value || 0);
        button.classList.toggle('is-active', previewValue === null && segmentValue <= selectedValue);
        button.classList.toggle('is-preview', previewValue !== null && segmentValue <= activeValue);
      });

      const labelValue = previewValue === null ? selectedValue : activeValue;
      caption.textContent = formatRatingLabel(labelValue);
      caption.classList.toggle('has-value', labelValue > 0);
    };

    for (let star = 1; star <= 5; star += 1) {
      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'review-star-segment';
      button.dataset.value = String(star);
      button.setAttribute('aria-label', `${star} stars`);

      button.addEventListener('mouseenter', () => {
        if (fields.rating.disabled) return;
        updateStars(star);
      });

      button.addEventListener('focus', () => {
        if (fields.rating.disabled) return;
        updateStars(star);
      });

      button.addEventListener('click', () => {
        if (fields.rating.disabled) return;
        fields.rating.value = String(star);
        updateStars();
      });

      ratingStarPicker.appendChild(button);
    }

    ratingPicker.addEventListener('mouseleave', () => {
      updateStars();
    });

    syncRatingPlaceholderState = () => {
      updateStars();
    };

    fields.rating.addEventListener('change', syncRatingPlaceholderState);
    syncRatingPlaceholderState();
  };

  const loadReviewContext = async () => {
    fields.name.value = '';
    fields.message.value = '';
    fields.rating.value = '';
    setEditableState(false);
    setSubmitState('Loading...', true);
    syncRatingPlaceholderState();

    if (!orderItemId) {
      lockReviewForm('Open this page from Order History');
      return;
    }

    try {
      const response = await fetch(`/service-reviews/context/${encodeURIComponent(orderItemId)}`, {
        credentials: 'include',
      });

      if (!response.ok) {
        if (response.status === 401 || response.status === 403 || response.status === 404) {
          lockReviewForm('Open this page from Order History');
          return;
        }

        throw new Error('Failed to load review context');
      }

      const data = await response.json();

      activeOrderItemId = String(data.order_item_id || '');
      fields.name.value = data.user_name || 'Veltrix customer';
      clearErrors();

      if (data.already_reviewed) {
        lockReviewForm('You already submitted a service review.', 'Review Added');
        return;
      }

      canSubmitReview = true;
      setEditableState(true);
      setSubmitState('Submit Review', false);
      syncRatingPlaceholderState();
    } catch (error) {
      console.error('Error loading review context:', error);
      lockReviewForm('Unable to load this review right now.');
    }
  };

  initRatingPicker();
  loadReviewContext();

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearErrors();

    if (!canSubmitReview || !activeOrderItemId) {
      setError('name', 'Open this page from Order History');
      return;
    }

    let hasError = false;
    ['rating', 'message'].forEach((key) => {
      if (!fields[key] || String(fields[key].value || '').trim()) return;
      hasError = true;
      setError(key);
    });

    if (hasError) return;

    try {
      const csrfToken = await ensureCsrfToken();
      const response = await fetch('/service-reviews', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
        },
        credentials: 'include',
        body: JSON.stringify({
          order_item_id: activeOrderItemId,
          rating: fields.rating.value.trim(),
          message: fields.message.value.trim(),
        }),
      });

      if (response.status === 409) {
        lockReviewForm('You already submitted a service review.', 'Review Added');
        return;
      }

      if (response.status === 401 || response.status === 403 || response.status === 404) {
        lockReviewForm('Open this page from Order History');
        return;
      }

      if (!response.ok) {
        throw new Error('Failed to save review');
      }

      if (typeof window.showSiteToast === 'function') {
        window.showSiteToast('success', 'Service review submitted.', {
          title: 'Review Added',
          duration: 2200,
        });
      }

      fields.rating.value = '';
      fields.message.value = '';
      syncRatingPlaceholderState();
      lockReviewForm('Your service review has been saved.', 'Review Added');
    } catch (error) {
      console.error('Error saving review:', error);
      setError('message', 'Unable to submit review right now');
    }
  });
})();
