/**
 * lazy image loading
 * @param
 */
function LazyImage () {
  const lazyImages = document.querySelectorAll('.lazy-image');

  for (const lazyImage of lazyImages) {
    if (isInViewport(lazyImage)) {
      lazyImage.classList.remove('lazy-image');
      lazyImage.src = lazyImage.dataset.src;
    }
  }
}

/**
 * @param el
 * @returns {boolean}
 */
function isInViewport (el) {
  const rect = el.getBoundingClientRect();
  return (
    (
      rect.top >= 0 &&
      rect.top <= (window.innerHeight || document.documentElement.clientHeight)
    ) ||
    (
      rect.bottom >= 0 &&
      rect.bottom <= (window.innerHeight || document.documentElement.clientHeight)
    )
  );
}
