(function () {
  const form = document.getElementById('review_form');
  if (!form) return;

  const fields = {
    product: document.getElementById('review_product'),
    platform: document.getElementById('review_platform'),
    rating: document.getElementById('review_rating'),
    title: document.getElementById('review_title'),
    message: document.getElementById('review_message'),
  };

  const errors = {
    product: document.querySelector('[data-error-for="product"]'),
    platform: document.querySelector('[data-error-for="platform"]'),
    rating: document.querySelector('[data-error-for="rating"]'),
    title: document.querySelector('[data-error-for="title"]'),
    message: document.querySelector('[data-error-for="message"]'),
  };

  const params = new URLSearchParams(window.location.search);
  const orderItemId = String(params.get('order_item_id') || '').trim();
  const submitButton = form.querySelector('.review-submit-button');

  const ratingPicker = document.getElementById('review_rating_picker');
  const ratingSelected = ratingPicker?.querySelector('.review-rating-selected');
  const ratingSelectedValue = ratingSelected?.querySelector('.val');
  const ratingItems = ratingPicker?.querySelector('.review-rating-items');

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
    ['rating', 'title', 'message'].forEach((key) => {
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
    setError('product', message);
    syncRatingPlaceholderState();
  };

  const initRatingPicker = () => {
    if (!fields.rating || !ratingPicker || !ratingSelected || !ratingSelectedValue || !ratingItems) return;

    ratingItems.innerHTML = '';
    let glowSyncFrame = null;

    const stopGlowSync = () => {
      if (glowSyncFrame !== null) {
        cancelAnimationFrame(glowSyncFrame);
        glowSyncFrame = null;
      }
    };

    const syncGlow = () => {
      const glowStyles = window.getComputedStyle(ratingSelected, '::after');
      const borderColor = glowStyles.borderTopColor || glowStyles.borderColor;
      const boxShadow = glowStyles.boxShadow;

      if (borderColor) {
        ratingPicker.style.setProperty('--review-glow-color', borderColor);
      }

      if (boxShadow && boxShadow !== 'none') {
        ratingPicker.style.setProperty('--review-glow-shadow', boxShadow);
      }

      if (ratingPicker.classList.contains('is-open')) {
        glowSyncFrame = requestAnimationFrame(syncGlow);
      } else {
        glowSyncFrame = null;
      }
    };

    const startGlowSync = () => {
      if (glowSyncFrame === null) syncGlow();
    };

    syncRatingPlaceholderState = () => {
      const value = String(fields.rating.value || '').trim();
      const selectedOption = fields.rating.selectedOptions?.[0];
      ratingSelectedValue.textContent = selectedOption?.textContent || 'Select rating';
      ratingSelected.classList.toggle('is-placeholder', !value);
      ratingItems.querySelectorAll('.review-rating-option').forEach((item) => {
        item.classList.toggle('is-selected', item.dataset.value === value);
      });
    };

    const closePicker = () => {
      ratingPicker.classList.remove('is-open');
      ratingItems.classList.add('review-rating-hide');
      stopGlowSync();
    };

    Array.from(fields.rating.options).forEach((option) => {
      if (!String(option.value || '').trim()) return;

      const customOption = document.createElement('div');
      customOption.className = 'review-rating-option';
      customOption.dataset.value = option.value;
      customOption.textContent = option.textContent;
      customOption.addEventListener('click', (event) => {
        event.stopPropagation();
        fields.rating.value = option.value;
        syncRatingPlaceholderState();
        closePicker();
      });
      ratingItems.appendChild(customOption);
    });

    syncRatingPlaceholderState();

    if (ratingPicker.dataset.bound !== '1') {
      ratingPicker.dataset.bound = '1';

      ratingSelected.addEventListener('click', (event) => {
        event.stopPropagation();
        if (fields.rating.disabled) return;

        const willOpen = ratingItems.classList.contains('review-rating-hide');
        closePicker();
        if (willOpen) {
          ratingPicker.classList.add('is-open');
          ratingItems.classList.remove('review-rating-hide');
          startGlowSync();
        }
      });

      document.addEventListener('click', () => {
        closePicker();
      });
    }

    fields.rating.addEventListener('change', syncRatingPlaceholderState);
  };

  const loadReviewContext = async () => {
    fields.product.value = '';
    fields.platform.value = '';
    fields.title.value = '';
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
      const response = await fetch(`/reviews/context/${encodeURIComponent(orderItemId)}`, {
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
      fields.product.value = data.product_name || '';
      fields.platform.value = data.platform || 'Universal';
      clearErrors();

      if (data.already_reviewed) {
        lockReviewForm('You already reviewed this item.', 'Review Added');
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
      setError('product', 'Open this page from Order History');
      return;
    }

    let hasError = false;
    ['rating', 'title', 'message'].forEach((key) => {
      if (!fields[key] || String(fields[key].value || '').trim()) return;
      hasError = true;
      setError(key);
    });

    if (hasError) return;

    try {
      const response = await fetch('/reviews', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        credentials: 'include',
        body: JSON.stringify({
          order_item_id: activeOrderItemId,
          rating: fields.rating.value.trim(),
          title: fields.title.value.trim(),
          message: fields.message.value.trim(),
        }),
      });

      if (response.status === 409) {
        lockReviewForm('You already reviewed this item.', 'Review Added');
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
        window.showSiteToast('success', 'Review submitted.', {
          title: 'Review Added',
          duration: 2200,
        });
      }

      fields.rating.value = '';
      fields.title.value = '';
      fields.message.value = '';
      syncRatingPlaceholderState();
      lockReviewForm('Your review has been saved.', 'Review Added');
    } catch (error) {
      console.error('Error saving review:', error);
      setError('message', 'Unable to submit review right now');
    }
  });
})();
