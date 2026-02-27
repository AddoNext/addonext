(function () {
    function getAttr(el, name, fallback) {
        var v = el.getAttribute(name);
        if (v == null || v === '' || v === 'undefined') return fallback == null ? '' : fallback;
        return v;
    }

    function buildMapSrc(wrapper) {
        var source = getAttr(wrapper, 'data-map-source', '');
        var url = getAttr(wrapper, 'data-map-url', '');
        var lat = getAttr(wrapper, 'data-latitude', '');
        var lng = getAttr(wrapper, 'data-longitude', '');
        var address = getAttr(wrapper, 'data-address', '');
        var zoom = getAttr(wrapper, 'data-zoom', '14');
        var mapSrc = '';
        if (source === 'url' && url) {
            mapSrc = url;
        } else if (source === 'coordinates' && lat && lng) {
            mapSrc = 'https://www.google.com/maps?q=' + lat + ',' + lng + '&output=embed&z=' + zoom;
        } else if (source === 'address') {
            mapSrc = 'https://www.google.com/maps?q=' + encodeURIComponent(address || 'London, UK') + '&output=embed&z=' + zoom;
        }
        return mapSrc;
    }

    function updateMapIframes() {
        var wrappers = document.querySelectorAll('.elementrify-advanced-google-map[data-map-source]');
        wrappers.forEach(function (wrapper) {
            var frame = wrapper.querySelector('.elementrify-advanced-google-map__frame');
            if (!frame) return;
            var mapSrc = buildMapSrc(wrapper);
            if (mapSrc && frame.getAttribute('src') !== mapSrc) {
                frame.setAttribute('src', mapSrc);
            }
        });
    }

    document.addEventListener('click', function () {
        setTimeout(updateMapIframes, 100);
    }, true);

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updateMapIframes);
    } else {
        updateMapIframes();
    }

    if (typeof elementor !== 'undefined' && elementor.on) {
        elementor.on('frontend:init', function () {
            elementor.hooks.addAction('frontend/element_ready/elementrify-advanced-google-map.default', function () {
                updateMapIframes();
            });
        });
    }
})();
