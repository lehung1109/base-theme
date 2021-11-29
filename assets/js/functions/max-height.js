/**
 * @param selector
 * @constructor
 */
function MaxHeight (selector) {
  const elems = document.querySelectorAll(selector + ' .is-max-height');
  let max = 0;

  if (!elems.length) return;

  for (const elem of elems) {
    max = elem.clientHeight > max ? elem.clientHeight : max;
  }

  for (const elem of elems) {
    elem.style.height = max + 'px';
  }
}
