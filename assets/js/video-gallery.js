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
        var lb = root.querySelector(".addonext-video-gallery__lightbox");
        if (!lb) {
            return;
        }
        var embed = root.querySelector(".addonext-video-gallery__lightbox-embed");
        if (embed) {
            embed.innerHTML = "";
        }
        lb.hidden = true;
        lb.setAttribute("data-open", "0");
        document.body.classList.remove("addonext-video-gallery--scroll-lock");
    }

    function openLightbox(root, inner) {
        var lb = root.querySelector(".addonext-video-gallery__lightbox");
        var embed = root.querySelector(".addonext-video-gallery__lightbox-embed");
        if (!lb || !embed) {
            return;
        }

        var embedUrl = inner.getAttribute("data-embed") || "";
        var hostedUrl = inner.getAttribute("data-hosted") || "";
        var type = inner.getAttribute("data-type") || "";

        injectPlayer(embed, embedUrl, hostedUrl, type);
        lb.hidden = false;
        lb.setAttribute("data-open", "1");
        document.body.classList.add("addonext-video-gallery--scroll-lock");

        var closeBtn = root.querySelector(".addonext-video-gallery__lightbox-close");
        if (closeBtn) {
            closeBtn.focus();
        }
    }

    function bindGallery(root) {
        if (root.getAttribute("data-addonext-vg-bound") === "1") {
            return;
        }
        root.setAttribute("data-addonext-vg-bound", "1");

        root.addEventListener("click", function (e) {
            var play = e.target.closest(".addonext-video-gallery__play");
            if (play && root.contains(play)) {
                e.preventDefault();
                var inner = play.closest(".addonext-video-player__inner");
                if (inner && root.contains(inner)) {
                    openLightbox(root, inner);
                }
                return;
            }

            if (e.target.closest(".addonext-video-gallery__lightbox-close")) {
                e.preventDefault();
                closeLightbox(root);
                return;
            }

            if (e.target.classList.contains("addonext-video-gallery__lightbox-backdrop")) {
                closeLightbox(root);
            }
        });
    }

    if (!window.addonextVideoGalleryEscBound) {
        window.addonextVideoGalleryEscBound = true;
        document.addEventListener("keydown", function (e) {
            if (e.key !== "Escape") {
                return;
            }
            document.querySelectorAll('.addonext-video-gallery__lightbox[data-open="1"]').forEach(function (lb) {
                var root = lb.closest(".addonext-video-gallery");
                if (root) {
                    closeLightbox(root);
                }
            });
        });
    }

    function initScope(scope) {
        var rootEl = scope && scope.length ? scope[0] : document;
        rootEl.querySelectorAll(".addonext-video-gallery").forEach(bindGallery);
    }

    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/addonext-video-gallery.default",
            initScope
        );
        elementorFrontend.hooks.addAction("frontend/element_ready/addonext-video-gallery", initScope);
    });

    document.querySelectorAll(".elementor-widget-addonext-video-gallery").forEach(function (widget) {
        widget.querySelectorAll(".addonext-video-gallery").forEach(bindGallery);
    });
})(jQuery);
