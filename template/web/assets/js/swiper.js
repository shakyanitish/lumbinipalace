document.addEventListener("DOMContentLoaded", function () {
  if (typeof Swiper !== "undefined") {
    // ===== ON-SITE OUTLETS SLIDER (SWIPER) =====
    if (document.querySelector(".m-outlets-swiper")) {
      new Swiper(".m-outlets-swiper", {
        slidesPerView: 3.2,
        spaceBetween: 24,
        loop: false,
        observer: true,
        observeParents: true,

        navigation: {
          nextEl: ".m-outlets-next",
          prevEl: ".m-outlets-prev",
        },

        pagination: {
          el: ".m-outlets-pagination",
          clickable: true,
        },

        breakpoints: {
          0: { slidesPerView: 1.2, spaceBetween: 16 },
          768: { slidesPerView: 2.1 },
          1024: { slidesPerView: 2.6 },
          1280: { slidesPerView: 3.2 },
        },
      });
    }

    // ===== BANNER SLIDER (SWIPER) =====
    let bannerAddressSlider;
    if (document.querySelector(".ul-banner-address-slider")) {
      bannerAddressSlider = new Swiper(".ul-banner-address-slider", {
        slidesPerView: 1,
        loop: true,
        autoplay: { delay: 5000, disableOnInteraction: false },
        speed: 1500,
      });
    }

    if (document.querySelector(".ul-banner-slider")) {
      const bannerConfig = {
        slidesPerView: 1,
        loop: true,
        autoplay: { delay: 5000, disableOnInteraction: false },
        speed: 1500,
        spaceBetween: 0,
        navigation: {
          nextEl: ".ul-banner-slider-nav .next",
          prevEl: ".ul-banner-slider-nav .prev",
        },
        pagination: {
          el: ".ul-banner-slider-pagination",
          clickable: true,
          renderBullet: function (index, className) {
            return (
              '<span class="' +
              className +
              '">' +
              String(index + 1).padStart(2, "0") +
              "</span>"
            );
          },
        },
      };

      if (bannerAddressSlider) {
        bannerConfig.thumbs = { swiper: bannerAddressSlider };
      }

      new Swiper(".ul-banner-slider", bannerConfig);
    }

    // ===== ROOMS SLIDER (SWIPER) =====
    if (document.querySelector(".m-rooms-swiper")) {
      new Swiper(".m-rooms-swiper", {
        slidesPerView: 2.5,
        spaceBetween: 30,
        loop: true,
        navigation: {
          nextEl: ".m-rooms-next, .m-rooms-next-mob",
          prevEl: ".m-rooms-prev, .m-rooms-prev-mob",
        },
        pagination: {
          el: ".m-rooms-pagination",
          type: "custom",
          renderCustom: function (swiper, current, total) {
            var mobPag = document.querySelectorAll(".m-rooms-pagination-mob");
            if (mobPag && mobPag.length > 0) {
                mobPag.forEach(function(el) {
                    el.innerHTML = String(current).padStart(2, "0") + " / " + String(total).padStart(2, "0");
                });
            }

            var activeWidth = (1 / total) * 100;
            var activeLeft = ((current - 1) / total) * 100;

            return (
              '<span class="number">' +
              String(current).padStart(2, "0") +
              "</span>" +
              '<span class="progress-line-container">' +
              '<span class="progress-line-active" style="width: ' +
              activeWidth +
              "%; left: " +
              activeLeft +
              '%;"></span>' +
              "</span>" +
              '<span class="total">' +
              String(total).padStart(2, "0") +
              "</span>"
            );
          },
        },
        breakpoints: {
          0: { slidesPerView: 1.2, spaceBetween: 20 },
          768: { slidesPerView: 2, spaceBetween: 24 },
          1024: { slidesPerView: 2.5, spaceBetween: 30 },
        },
      });
    }

    // ===== OFFERS SLIDER (SWIPER) =====
    if (document.querySelector(".m-offers-swiper")) {
      new Swiper(".m-offers-swiper", {
        slidesPerView: 3,
        spaceBetween: 25,
        loop: false,
        navigation: {
          nextEl: ".m-offers-next, .m-offers-next-mob",
          prevEl: ".m-offers-prev, .m-offers-prev-mob",
        },
        pagination: {
          el: ".m-offers-pagination",
          clickable: true,
        },
        breakpoints: {
          0: { slidesPerView: 1, spaceBetween: 15 },
          768: { slidesPerView: 2, spaceBetween: 20 },
          1024: { slidesPerView: 3, spaceBetween: 25 },
        },
      });
    }

    // ===== GREAT ROOM SLIDER (SWIPER) =====
    if (document.querySelector(".m-great-room-swiper")) {
      new Swiper(".m-great-room-swiper", {
        slidesPerView: 1,
        loop: true,
        speed: 800,
        effect: "fade",
        fadeEffect: { crossFade: true },
        navigation: {
          nextEl: ".m-great-room-next, .m-great-room-next-mob",
          prevEl: ".m-great-room-prev, .m-great-room-prev-mob",
        },
        pagination: {
          el: ".m-great-room-pagination",
          type: "custom",
          renderCustom: function (swiper, current, total) {
            var mobPag = document.querySelectorAll(".m-great-room-pagination-mob");
            if (mobPag && mobPag.length > 0) {
                mobPag.forEach(function(el) {
                    el.innerHTML = String(current).padStart(2, "0") + " / " + String(total).padStart(2, "0");
                });
            }
            return (
              String(current).padStart(2, "0") +
              " / " +
              String(total).padStart(2, "0")
            );
          },
        },
      });
    }
    // ===== BLOGS SLIDER (SWIPER) =====
    if (document.querySelector(".m-blogs-swiper")) {
      new Swiper(".m-blogs-swiper", {
        slidesPerView: 3,
        spaceBetween: 24,
        loop: false,
        navigation: {
          nextEl: ".m-blogs-next-mob",
          prevEl: ".m-blogs-prev-mob",
        },
        pagination: {
          el: ".m-blogs-pagination-mob",
          type: "custom",
          renderCustom: function (swiper, current, total) {
            return (
              String(current).padStart(2, "0") +
              "/" +
              String(total).padStart(2, "0")
            );
          },
        },
        breakpoints: {
          0: { slidesPerView: 1, spaceBetween: 20 },
          768: { slidesPerView: 2, spaceBetween: 20 },
          992: { slidesPerView: 3, spaceBetween: 24, allowTouchMove: false },
        },
      });
    }
  }
});
