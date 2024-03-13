/**
 * Waits for an element matching the given selector to be available in the DOM.
 * @param {string} selector - The CSS selector to match the element.
 * @returns {Promise<Element>} - A promise that resolves with the matching element.
 */
export function waitForElm(selector) {
  return new Promise(resolve => {
      if (document.querySelector(selector)) {
          return resolve(document.querySelector(selector));
      }

      const observer = new MutationObserver(() => {
          if (document.querySelector(selector)) {
              observer.disconnect();
              resolve(document.querySelector(selector));
          }
      });

      observer.observe(document.body, {
          childList: true,
          subtree: true
      });
  });
}