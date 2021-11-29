(() => {
  window.addEventListener('scroll', event => {
    if (typeof LazyImage === 'function') {
      LazyImage();
    }
  });

  document.addEventListener('wpcf7mailsent', function (event) {
    window.location.href = window.location.protocol + '//' + window.location.hostname + '/thank-you/';
  }, false);

  window.addEventListener('load', () => {
    if (typeof LazyImage === 'function') {
      LazyImage();
    }
  });
})();
