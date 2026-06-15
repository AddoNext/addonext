(function ($) {
    "use strict";

    function injectPlayer(container, embedUrl, hostedUrl, type) {
        container.innerHTML = "";
        if (type === "hosted" && hostedUrl) {
            var video = document.createElement("video");
            video.setAttribute("src", hostedUrl);
            video.setAttribute("controls", "");
            video.setAttribute("playsinline", "");
            video.setAttribute("autoplay", "");
            video.style.position = "absolute";
            video.style.inset = "0";
            video.style.width = "100%";
            video.style.height = "100%";
            video.style.objectFit = "contain";
            container.appendChild(video);
        } else if (embedUrl) {
            var iframe = document.createElement("iframe");
            iframe.setAttribute("src", embedUrl);
            iframe.setAttribute("frameborder", "0");
            iframe.setAttribute(
                "allow",
                "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            );
            iframe.setAttribute("allowfullscreen", "");
            iframe.style.position = "absolute";
            iframe.style.top = "0";
            iframe.style.left = "0";
            iframe.style.width = "100%";
            iframe.style.height = "100%";
            iframe.style.border = "none";
            container.appendChild(iframe);
        }
    }

    function closeLightbox(root) {
        var lb = root.querySelector(".veloria-video-gallery__lightbox");
        if (!lb) {
            return;
        }
        var embed = root.querySelector(".veloria-video-gallery__lightbox-embed");
        if (embed) {
            embed.innerHTML = "";
        }
        lb.hidden = true;
        lb.setAttribute("data-open", "0");
        document.body.classList.remove("veloria-video-gallery--scroll-lock");
    }

    function openLightbox(root, inner) {
        var lb = root.querySelector(".veloria-video-gallery__lightbox");
        var embed = root.querySelector(".veloria-video-gallery__lightbox-embed");
        if (!lb || !embed) {
            return;
        }

        var embedUrl = inner.getAttribute("data-embed") || "";
        var hostedUrl = inner.getAttribute("data-hosted") || "";
        var type = inner.getAttribute("data-type") || "";

        injectPlayer(embed, embedUrl, hostedUrl, type);
        lb.hidden = false;
        lb.setAttribute("data-open", "1");
        document.body.classList.add("veloria-video-gallery--scroll-lock");

        var closeBtn = root.querySelector(".veloria-video-gallery__lightbox-close");
        if (closeBtn) {
            closeBtn.focus();
        }
    }

    function bindGallery(root) {
        if (root.getAttribute("data-veloria-vg-bound") === "1") {
            return;
        }
        root.setAttribute("data-veloria-vg-bound", "1");

        root.addEventListener("click", function (e) {
            var play = e.target.closest(".veloria-video-gallery__play");
            if (play && root.contains(play)) {
                e.preventDefault();
                var inner = play.closest(".veloria-video-player__inner");
                if (inner && root.contains(inner)) {
                    openLightbox(root, inner);
                }
                return;
            }

            if (e.target.closest(".veloria-video-gallery__lightbox-close")) {
                e.preventDefault();
                closeLightbox(root);
                return;
            }

            if (e.target.classList.contains("veloria-video-gallery__lightbox-backdrop")) {
                closeLightbox(root);
            }
        });
    }

    if (!window.veloriaVideoGalleryEscBound) {
        window.veloriaVideoGalleryEscBound = true;
        document.addEventListener("keydown", function (e) {
            if (e.key !== "Escape") {
                return;
            }
            document.querySelectorAll('.veloria-video-gallery__lightbox[data-open="1"]').forEach(function (lb) {
                var root = lb.closest(".veloria-video-gallery");
                if (root) {
                    closeLightbox(root);
                }
            });
        });
    }

    function initScope(scope) {
        var rootEl = scope && scope.length ? scope[0] : document;
        rootEl.querySelectorAll(".veloria-video-gallery").forEach(bindGallery);
    }

    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/veloria-video-gallery.default",
            initScope
        );
        elementorFrontend.hooks.addAction("frontend/element_ready/veloria-video-gallery", initScope);
    });

    document.querySelectorAll(".elementor-widget-veloria-video-gallery").forEach(function (widget) {
        widget.querySelectorAll(".veloria-video-gallery").forEach(bindGallery);
    });
})(jQuery);
