/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*************************!*\
  !*** ./src/settings.js ***!
  \*************************/
document.addEventListener("DOMContentLoaded", function () {
  const fetchButton = document.getElementById('fetch-templates');
  const generateButton = document.getElementById('generate-css');
  const clearButton = document.getElementById('clear-css');
  fetchButton.addEventListener('click', function fetchButtonHandler(e) {
    e.preventDefault();
    fetchButton.textContent = 'Fetching..';
    fetch(`${ajaxurl}?action=gutailberg_get_fse_templates`).then(res => res.json()).then(({
      data
    }) => {
      console.log('Fetched templates:', data.templates);
      document.getElementById('fse-templates').innerHTML = data.contents;
      fetchButton.textContent = 'Fetched!';
      fetchButton.classList.remove('button-secondary');
      fetchButton.classList.add('button-disabled');
      generateButton.classList.remove('hidden');
      fetchButton.removeEventListener('click', fetchButtonHandler);
    }).catch(err => console.log(err));
  });
  generateButton.addEventListener('click', function (e) {
    e.preventDefault();
    const xpath = "//style[contains(text(),'! tailwindcss')]";
    const matchingElement = document.evaluate(xpath, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
    console.log(matchingElement);
    document.getElementById('gutailberg_field_tailwind_output').value = matchingElement.textContent;
    generateButton.textContent = 'Done! Don\'t forget to save the settings below ^^.';
  });
  clearButton.addEventListener('click', function (e) {
    e.preventDefault();
    document.getElementById('gutailberg_field_tailwind_output').value = '';
  });
});
/******/ })()
;
//# sourceMappingURL=settings.js.map