(function () {
  'use strict';

  function scan(root) {
    if (!root || !root.querySelectorAll) {
      return;
    }
    var links = root.querySelectorAll('.veloria-mini-cart__trigger');
    for (var i = 0; i < links.length; i += 1) {
      var link = links[i];
      if (link.getAttribute('data-veloria-bound') === '1') {
        continue;
      }
      link.setAttribute('data-veloria-bound', '1');
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      scan(document);
    });
  } else {
    scan(document);
  }

  if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks && elementorFrontend.hooks.addAction) {
    elementorFrontend.hooks.addAction('frontend/element_ready/veloria-mini-cart.default', function ($scope) {
      var el = $scope && $scope[0] ? $scope[0] : $scope;
      if (!el || !el.querySelectorAll) {
        return;
      }
      scan(el);
    });
  }
})();
