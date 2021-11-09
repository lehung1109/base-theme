/**
 * lazy image loading
 * @param
 */
export function LazyImage() {
  let lazyImages = document.querySelectorAll('.lazy-image');

  for (let lazyImage of lazyImages) {
    if (isInViewport(lazyImage)) {
      lazyImage.classList.remove('lazy-image');
      lazyImage.src = lazyImage.dataset.src;
    }
  }
}

/**
 * 
 * @param el
 * @returns {boolean}
 */
function isInViewport(el) {
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

/**
 *
 * @param selectors
 */
export function AppendStyle (selectors) {
  let styleA = [];

  for (let selector of selectors) {
    let elem = document.querySelector(selector);

    if (elem && elem.getElementsByClassName('has-slide').length) {
      styleA.push(
        'https://unpkg.com/swiper/swiper-bundle.min.css',
        '/wp-content/themes/custom-theme/assets/css/components/custom-slide.css',
      );
    }
  }

  AppendStyleProcess(styleA, selectors);
}

/**
 * defer style when load page
 * @param $styles
 * @param selectors
 */
function AppendStyleProcess ($styles, selectors) {
  let styles = $styles;
  let styleSelector = selectors;

  $styles = $styles.filter(style => {
    let styleExists = document.querySelector('[href="' + style + '"]');

    return styleExists === null;
  });

  if ($styles.length === 0) {
    for (let selector of selectors) {
      let eventE = new Event('afterStyleLoaded');
      let elems = document.querySelectorAll(selector);

      if (!elems.length) continue;

      for (let elem of elems) {
        elem.dispatchEvent(eventE);
      }
    }

    styleSelector = undefined;
    styles = undefined;

    return;
  }

  let style = document.createElement('link');

  style.rel = 'stylesheet';
  style.href = $styles[0];

  style.onload = () => {
    AppendStyleProcess(styles, styleSelector);
  };

  style.onerror = style.onload;

  document.head.appendChild(style);
}

/**
 * load some image after image is loaded
 * @param selectors
 */
export function AppendJs (selectors) {
  let scriptA = [];

  for (let selector of selectors) {
    let elem = document.querySelector(selector);

    if (!elem) continue;

    if (elem.getElementsByClassName('has-slide').length) {
      scriptA.push(
        'https://unpkg.com/swiper/swiper-bundle.min.js',
      );
    }
  }

  AppendJsProcess(scriptA, selectors);
}

/**
 * defer js when load page
 * @param $scripts
 * @param selectors
 */
function AppendJsProcess ($scripts, selectors) {
  let scripts = $scripts;
  let jsSelector = selectors;

  $scripts = $scripts.filter(script => {
    let scriptExists = document.querySelector('[src="' + script + '"]');

    return scriptExists === null;
  });

  if ($scripts.length === 0) {
    for (let selector of selectors) {
      let eventE = new Event('afterJsLoaded');
      let elems = document.querySelectorAll(selector);

      if (!elems.length) continue;

      for (let elem of elems) {
        elem.dispatchEvent(eventE);
      }
    }

    jsSelector = undefined;
    scripts = undefined;

    return;
  }

  let script = document.createElement('script');

  script.type = 'text/javascript';
  script.src = $scripts[0];

  script.onload = () => {
    AppendJsProcess(scripts, jsSelector);
  };

  script.onerror = script.onload;

  document.head.appendChild(script);
}
