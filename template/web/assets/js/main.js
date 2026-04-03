document.addEventListener("DOMContentLoaded", function () {
  // ===== PRELOADER =====
  const preloader = document.getElementById("preloader");
  if (preloader) {
    preloader.style.display = "none";
    document.body.style.position = "static";
  }

  // ===== SIDEBAR MOBILE MENU =====
  const ulSidebarOpener = document.querySelector(".ul-header-sidebar-opener");
  const ulSidebarCloser = document.querySelector(".ul-sidebar-closer");
  const ulSidebar = document.querySelector(".ul-sidebar");

  // Create overlay if it doesn't exist
  let overlay = document.querySelector(".ul-sidebar-overlay");
  if (!overlay) {
    overlay = document.createElement("div");
    overlay.className = "ul-sidebar-overlay";
    document.body.appendChild(overlay);
  }

  const toggleSidebar = (show) => {
    if (show) {
      ulSidebar.classList.add("active");
      overlay.classList.add("active");
    } else {
      ulSidebar.classList.remove("active");
      overlay.classList.remove("active");
    }
  };

  if (ulSidebarOpener && ulSidebar) {
    ulSidebarOpener.addEventListener("click", (e) => {
      e.stopPropagation();
      const isActive = ulSidebar.classList.contains("active");
      toggleSidebar(!isActive);
    });
  }
  if (ulSidebarCloser && ulSidebar) {
    ulSidebarCloser.addEventListener("click", (e) => {
      e.stopPropagation();
      toggleSidebar(false);
    });
  }

  // Close sidebar when clicking outside or on overlay
  document.addEventListener("click", (e) => {
    if (ulSidebar && ulSidebar.classList.contains("active")) {
      if (
        (!ulSidebar.contains(e.target) &&
          !ulSidebarOpener.contains(e.target)) ||
        e.target === overlay
      ) {
        toggleSidebar(false);
      }
    }
  });

  overlay.addEventListener("click", () => toggleSidebar(false));

  // ===== STICKY MOBILE HEADER =====
  const ulHeader = document.querySelector(".ul-header");
  if (ulHeader) {
    window.addEventListener("scroll", () => {
      ulHeader.classList.toggle("sticky", window.scrollY > 80);
    });
  }

  // ===== STICKY MARRIOTT HEADER BARS (DESKTOP) =====
  (function () {
    const stickyPairs = [
      {
        wrapper: ".marriott-header-middle-wrapper",
        bar: ".marriott-header-middle",
      },
      {
        wrapper: ".marriott-header-bottom-wrapper",
        bar: ".marriott-header-bottom",
      },
    ];

    function handleSticky() {
      stickyPairs.forEach((pair) => {
        const wrapperEl = document.querySelector(pair.wrapper);
        const barEl = document.querySelector(pair.bar);
        if (!wrapperEl || !barEl) return;
        const wrapperTop = wrapperEl.getBoundingClientRect().top;
        barEl.classList.toggle("sticky", wrapperTop <= 0);
      });
    }

    window.addEventListener("scroll", handleSticky, { passive: true });
    handleSticky();
  })();

  // ===== WOW.JS — SCROLL ANIMATIONS =====
  if (typeof WOW !== "undefined") {
    new WOW({}).init();
  }

  // ===== BANNER SLIDES — SET BACKGROUND IMAGES =====
  document.querySelectorAll(".ul-banner-slide").forEach(function (slide) {
    const img = slide.getAttribute("data-img");
    if (img) {
      slide.style.backgroundImage = "url('" + img + "')";
      slide.style.backgroundSize = "cover";
      slide.style.backgroundPosition = "center";
      slide.removeAttribute("data-img");
    }
  });

  // ===== TAB NAVIGATION (Amenities & Enjoy Stay) =====
  const tabButtons = document.querySelectorAll(".tab-nav");
  const containers = new Set();
  tabButtons.forEach((btn) => {
    const container = btn.closest(".m-enjoy-stay, .m-amenities, section, div");
    if (container) containers.add(container.closest("section") || container);
  });

  containers.forEach((container) => {
    const btns = container.querySelectorAll(".tab-nav");
    const contents = container.querySelectorAll(".ul-tab");

    btns.forEach((button) => {
      button.addEventListener("click", () => {
        const tabId = button.getAttribute("data-tab");
        contents.forEach((content) => {
          content.classList.toggle("active", content.id === tabId);
        });
        btns.forEach((btn) => btn.classList.remove("active"));
        button.classList.add("active");
      });
    });
  });

  // ===== AMENITIES SEE MORE / LESS TOGGLE =====
  const toggleBtn = document.getElementById("amenity-toggle-btn");
  const amenitiesGrid = document.querySelector(
    "#amenity-all .m-amenities-grid",
  );

  if (toggleBtn && amenitiesGrid) {
    toggleBtn.addEventListener("click", () => {
      const isExpanded = amenitiesGrid.classList.toggle("is-expanded");
      toggleBtn.textContent = isExpanded ? "See Less" : "See More";

      if (!isExpanded) {
        const amenitiesSection = document.querySelector(".m-amenities");
        if (amenitiesSection) {
          window.scrollTo({
            top: amenitiesSection.offsetTop - 100,
            behavior: "smooth",
          });
        }
      }
    });
  }

  // ===== HERO BANNER PARALLAX SCROLL =====
  const heroBanner = document.querySelector(".marriott-style-banner");
  if (heroBanner) {
    window.addEventListener(
      "scroll",
      () => {
        const scrollY = window.scrollY;
        const bannerHeight = heroBanner.offsetHeight;
        const bannerTop = heroBanner.offsetTop;

        // Only calculate if banner is in view
        if (scrollY < bannerTop + bannerHeight) {
          const offset = (scrollY - bannerTop) * 0.4; // Parallax factor
          heroBanner.style.setProperty("--parallax-y", `${offset}px`);
        }
      },
      { passive: true },
    );
  }

  // ===== MARRIOTT BOOKING WIDGET POPUPS =====
  const mhbFields = document.querySelectorAll(".mhb-field");
  const mhbDropdowns = document.querySelectorAll(".mhb-dropdown");

  // Toggle dropdowns
  mhbFields.forEach((field) => {
    const valueEl = field.querySelector(".mhb-value");
    const dropdown = field.querySelector(".mhb-dropdown");
    if (valueEl && dropdown) {
      valueEl.addEventListener("click", (e) => {
        e.stopPropagation();
        const isOpen = dropdown.classList.contains("show");
        // Close all first
        mhbDropdowns.forEach((d) => d.classList.remove("show"));
        if (!isOpen) {
          dropdown.classList.add("show");
        }
      });
    }
  });

  // Close on 'Done' or 'X'
  document.querySelectorAll(".mhb-done-btn, .mhb-close-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      const dropdown = btn.closest(".mhb-dropdown");
      
      if (dropdown && dropdown.classList.contains("mhb-rooms-dropdown") && btn.classList.contains("mhb-done-btn")) {
        if (bookingState.rooms > 9) {
          window.location.href = "events.html#eventPlanModal";
          return;
        }
      }
      
      if (dropdown) dropdown.classList.remove("show");
    });
  });

  // Close on outside click
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".mhb-field")) {
      mhbDropdowns.forEach((d) => d.classList.remove("show"));
    }
  });

  // Prevent closing when clicking inside dropdown
  mhbDropdowns.forEach((d) => {
    d.addEventListener("click", (e) => e.stopPropagation());
  });

  // guest counters logic
  const roomsFieldText = document.querySelector(
    "#mhb-rooms-field .mhb-value span",
  );
  const guestRows = document.querySelectorAll(".mhb-rooms-dropdown .mhb-row");

  let bookingState = {
    rooms: 1,
    adults: 1,
    children: 0,
  };

  guestRows.forEach((row) => {
    const type = row.getAttribute("data-type");
    const minusBtn = row.querySelector(".minus");
    const plusBtn = row.querySelector(".plus");
    const numEl = row.querySelector(".mhb-count-num");

    if (plusBtn && minusBtn && numEl) {
      plusBtn.addEventListener("click", () => {
        if (type === "rooms" && bookingState.rooms < 10) bookingState.rooms++;
        else if (
          type === "adults" &&
          bookingState.adults + bookingState.children < 8
        )
          bookingState.adults++;
        else if (
          type === "children" &&
          bookingState.adults + bookingState.children < 8
        )
          bookingState.children++;
        updateCountersForAll();
      });

      minusBtn.addEventListener("click", () => {
        if (type === "rooms" && bookingState.rooms > 1) bookingState.rooms--;
        else if (type === "adults" && bookingState.adults > 1)
          bookingState.adults--;
        else if (type === "children" && bookingState.children > 0)
          bookingState.children--;
        updateCountersForAll();
      });
    }
  });

  function updateCountersForAll() {
    const roomsMsg = document.querySelector(".mhb-rooms-dropdown .mhb-rooms-msg");
    if (roomsMsg) {
      roomsMsg.style.display = bookingState.rooms > 9 ? "none" : "block";
    }

    guestRows.forEach((row) => {
      const type = row.getAttribute("data-type");
      const minusBtn = row.querySelector(".minus");
      const plusBtn = row.querySelector(".plus");
      const numEl = row.querySelector(".mhb-count-num");

      if (numEl) {
        if (type === "rooms") numEl.textContent = bookingState.rooms > 9 ? "10+" : bookingState.rooms;
        if (type === "adults") numEl.textContent = bookingState.adults;
        if (type === "children") numEl.textContent = bookingState.children;
      }

      if (minusBtn) {
        if (type === "rooms")
          minusBtn.classList.toggle("disabled", bookingState.rooms <= 1);
        if (type === "adults")
          minusBtn.classList.toggle("disabled", bookingState.adults <= 1);
        if (type === "children")
          minusBtn.classList.toggle("disabled", bookingState.children <= 0);
      }
      
      if (plusBtn) {
        if (type === "rooms")
          plusBtn.classList.toggle("disabled", bookingState.rooms >= 10);
      }

      if (type === "adults" || type === "children") {
        row.style.display = bookingState.rooms > 9 ? "none" : "flex";
      }
    });

    const isGroupBooking = bookingState.rooms > 9;

    // Update Global text
    let text = `${bookingState.rooms > 9 ? '10+' : bookingState.rooms} ${
      bookingState.rooms > 1 ? "Rooms" : "Room"
    }`;
    
    if (!isGroupBooking) {
      text += `, ${bookingState.adults} ${bookingState.adults > 1 ? "Adults" : "Adult"}`;
      if (bookingState.children > 0) {
        text += `, ${bookingState.children} ${
          bookingState.children > 1 ? "Children" : "Child"
        }`;
      }
    }
    if (roomsFieldText) roomsFieldText.textContent = text;
  }

  // Rates logic
  const ratesFieldText = document.querySelector(
    "#mhb-rates-field .mhb-value span",
  );
  const rateItems = document.querySelectorAll(".mhb-rate-item");
  const promoField = document.querySelector(".mhb-promo-field");
  const promoInput = document.querySelector(".mhb-promo-input");
  const promoClear = document.querySelector(".mhb-promo-clear");

  rateItems.forEach((item) => {
    item.addEventListener("click", () => {
      rateItems.forEach((i) => i.classList.remove("active"));
      item.classList.add("active");
      const rateLabel = item.getAttribute("data-rate");
      if (ratesFieldText) ratesFieldText.textContent = rateLabel;

      // Toggle promo field
      if (rateLabel === "Corp/Promo Code") {
        if (promoField) promoField.style.display = "block";
      } else {
        if (promoField) promoField.style.display = "none";
      }
    });
  });

  if (promoClear && promoInput) {
    promoClear.addEventListener("click", () => {
      promoInput.value = "";
    });
  }

  // ===== LIGHTGALLERY INITIALIZATION =====
  const roomGallery = document.querySelector(".m-rooms-swiper .swiper-wrapper");
  if (roomGallery && typeof lightGallery !== "undefined") {
    lightGallery(roomGallery, {
      selector: ".m-room-gallery-btn",
      plugins: [lgZoom, lgThumbnail],
      speed: 500,
      download: false,
    });
  }
  // ===== ENQUIRY FORM & WHATSAPP INTEGRATION =====
  const enquiryForm = document.getElementById("enquiryForm");
  if (enquiryForm) {
    enquiryForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(enquiryForm);
      const data = Object.fromEntries(formData.entries());

      // Basic check for reCAPTCHA (client-side only)
      const recaptchaResponse = grecaptcha.getResponse();
      if (!recaptchaResponse) {
        alert("Please complete the reCAPTCHA.");
        return;
      }

      console.log("Enquiry Form Submitted:", data);
      alert("Thank you for your enquiry! We will get back to you soon.");

      // Close modal
      const modalElement = document.getElementById("enquiryModal");
      const modal = bootstrap.Modal.getInstance(modalElement);
      if (modal) modal.hide();

      enquiryForm.reset();
      grecaptcha.reset();
    });
  }

  // Update WhatsApp link with form data (optional enhancement)
  const whatsappBtn = document.querySelector(".m-btn-whatsapp");
  if (whatsappBtn && enquiryForm) {
    whatsappBtn.addEventListener("click", function (e) {
      const name = enquiryForm.querySelector('[name="name"]').value;
      const message = enquiryForm.querySelector('[name="message"]').value;

      if (name) {
        let text = `Hello, I am ${name}. I have an enquiry: ${message || "Interested in booking a table."}`;
        this.href = `https://wa.me/9779857018135?text=${encodeURIComponent(text)}`;
      }
    });
  }

  /* ==================================================
     VIRTUAL TOUR SIDEBAR TOGGLE & MODAL RESET
     ================================================== */
  const vtSidebarWrapper = document.getElementById("vtSidebarWrapper");
  const vtSidebarToggle = document.getElementById("vtSidebarToggle");
  const vtModal = document.getElementById("virtualTourModal");

  if (vtSidebarWrapper && vtSidebarToggle) {
    // Toggle the sidebar open/closed when the tab is clicked
    vtSidebarToggle.addEventListener("click", () => {
      vtSidebarWrapper.classList.toggle("collapsed");
    });

    // Optional: Reset the sidebar state every time the modal opens
    if (vtModal) {
      vtModal.addEventListener("show.bs.modal", () => {
        // Force the sidebar to be open when the modal launches
        vtSidebarWrapper.classList.remove("collapsed");
      });
    }

    // Handle Active state on menu clicks (Mock functionality)
    const menuItems = document.querySelectorAll(".m-vt-menu-item");
    menuItems.forEach((item) => {
      item.addEventListener("click", function () {
        menuItems.forEach((btn) => btn.classList.remove("active"));
        this.classList.add("active");

        // Here you would typically postMessage to the iframe to change the tour location
        // Example: document.querySelector('.m-vt-iframe').contentWindow.postMessage(...)
      });
    });
  }
});

/* ================================================
   INSTAGRAM PAGE: Lightgallery Init
   ================================================ */
const igGallery = document.getElementById("lightgallery-instagram");
if (igGallery && typeof lightGallery !== "undefined") {
  lightGallery(igGallery, {
    selector: "a",
    plugins: [lgZoom, lgThumbnail],
    speed: 500,
    thumbnail: true,
    zoomFromOrigin: false,
    animateThumb: true,
  });
}

// ===== EVENT PLAN FORM: Toggle "Other" Event Type =====
function toggleOtherEventType(selectElement) {
  const otherWrapper = document.getElementById("otherEventTypeWrapper");
  const otherInput = document.getElementById("otherEventType");
  if (selectElement.value === "other") {
    otherWrapper.classList.remove("d-none");
    otherInput.setAttribute("required", "required");
  } else {
    otherWrapper.classList.add("d-none");
    otherInput.removeAttribute("required");
    otherInput.value = "";
  }
}

// Handle Event Plan Form Submission
document.addEventListener("DOMContentLoaded", function () {
  if (window.location.hash === "#eventPlanModal" && typeof bootstrap !== "undefined") {
    const eventModalEl = document.getElementById("eventPlanModal");
    if (eventModalEl) {
      const eventModal = new bootstrap.Modal(eventModalEl);
      eventModal.show();
    }
  }

  const eventPlanForm = document.getElementById("eventPlanForm");
  if (eventPlanForm) {
    eventPlanForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(eventPlanForm);
      const data = Object.fromEntries(formData.entries());

      console.log("Event Plan Form Submitted:", data);
      alert(
        "Thank you for your event enquiry! We will contact you soon to plan it together.",
      );

      // Close modal
      const modalElement = document.getElementById("eventPlanModal");
      const modal = bootstrap.Modal.getInstance(modalElement);
      if (modal) modal.hide();

      eventPlanForm.reset();
      const otherWrapper = document.getElementById("otherEventTypeWrapper");
      if (otherWrapper) {
        otherWrapper.classList.add("d-none");
      }
    });
  }
});

// ===== EVENTS PAGE: SCROLL TO ACCORDION & OPEN =====
document.addEventListener("DOMContentLoaded", function () {
  const scrollToAccordionLinks = document.querySelectorAll(
    ".ul-scroll-to-accordion",
  );
  scrollToAccordionLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("href");
      if (!targetId || targetId === "#") return;

      const targetCollapse = document.querySelector(targetId);
      if (targetCollapse) {
        // Use Bootstrap Collapse API to open it if closed
        if (
          !targetCollapse.classList.contains("show") &&
          typeof bootstrap !== "undefined"
        ) {
          const bsCollapse =
            bootstrap.Collapse.getOrCreateInstance(targetCollapse);
          bsCollapse.show();
        }

        // Scroll to the accordion header (offset by ~100px for sticky header)
        setTimeout(() => {
          const headerElement = targetCollapse.previousElementSibling;
          const elementToScrollTo = headerElement
            ? headerElement
            : targetCollapse;
          const offsetTop =
            elementToScrollTo.getBoundingClientRect().top +
            window.scrollY -
            100;

          window.scrollTo({
            top: offsetTop,
            behavior: "smooth",
          });
        }, 150); // slight delay to allow collapse animation to start
      }
    });
  });

  // Link 'View Rates' buttons to open the header calendar or mobile booking overlay
  const viewRatesBtns = document.querySelectorAll(".m-room-slide-btn");
  const datesField = document.querySelector("#mhb-dates-field");
  const datesValue = datesField ? datesField.querySelector(".mhb-value") : null;
  const mobBookingOverlay = document.getElementById("m-mob-booking-overlay");
  const mobCalendarOverlay = document.getElementById("m-mob-calendar-overlay");

  if (viewRatesBtns.length > 0) {
    viewRatesBtns.forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        if (window.innerWidth < 992 && mobBookingOverlay) {
          // Open mobile booking overlay
          mobBookingOverlay.classList.add("active");
          document.body.style.overflow = "hidden"; // prevent background scrolling
        } else if (datesValue) {
          // Trigger the calendar dropdown on desktop
          setTimeout(() => {
            datesValue.click();
          }); // Wait for scroll to stabilize
        }
      });
    });
  }

  // Floating Check Availability button logic (Mobile)
  const mobileCheckBtn = document.getElementById(
    "mobile-check-availability-btn",
  );
  if (mobileCheckBtn && mobBookingOverlay) {
    mobileCheckBtn.addEventListener("click", (e) => {
      e.preventDefault();
      mobBookingOverlay.classList.add("active");
      document.body.style.overflow = "hidden";
    });
  }

  // Mobile Booking Overlay Interactions
  if (mobBookingOverlay) {
    const mobBookingClose = document.getElementById("m-mob-booking-close");
    const mobDatesTrigger = document.getElementById("m-mob-dates-trigger");

    if (mobBookingClose) {
      mobBookingClose.addEventListener("click", () => {
        mobBookingOverlay.classList.remove("active");
        document.body.style.overflow = "";
      });
    }

    if (mobDatesTrigger && mobCalendarOverlay) {
      mobDatesTrigger.addEventListener("click", () => {
        mobCalendarOverlay.classList.add("active");
        document.body.style.overflow = "hidden";
      });
    }
  }

  // ── Mobile Calendar Overlay: close / done ──────────────────────────────
  if (mobCalendarOverlay) {
    const mobCalClose = document.getElementById("m-mob-calendar-close");
    const mobCalDone  = document.getElementById("m-mob-calendar-done");

    const closeCalendarOverlay = () => {
      mobCalendarOverlay.classList.remove("active");
      document.body.style.overflow = "";
    };

    if (mobCalClose) mobCalClose.addEventListener("click", closeCalendarOverlay);
    if (mobCalDone)  mobCalDone.addEventListener("click", closeCalendarOverlay);

    // ── Mobile tab switching (Specific ↔ Flexible) ──────────────────────
    const mobTabs         = mobCalendarOverlay.querySelectorAll(".mob-date-tab");
    const mobSpecificWrap = document.getElementById("mob-cal-specific-wrap");
    const mobFlexWrap     = document.getElementById("mob-cal-flexible-wrap");
    const mobCalDoneBtn   = document.getElementById("m-mob-calendar-done");

    mobTabs.forEach(tab => {
      tab.addEventListener("click", () => {
        mobTabs.forEach(t => t.classList.remove("active"));
        tab.classList.add("active");

        if (tab.dataset.mobTab === "flexible") {
          if (mobSpecificWrap) mobSpecificWrap.classList.add("d-none");
          if (mobFlexWrap)     mobFlexWrap.classList.remove("d-none");
          if (mobCalDoneBtn)   mobCalDoneBtn.textContent = "Check Availability";
        } else {
          if (mobSpecificWrap) mobSpecificWrap.classList.remove("d-none");
          if (mobFlexWrap)     mobFlexWrap.classList.add("d-none");
          if (mobCalDoneBtn)   mobCalDoneBtn.textContent = "View Rates";
        }
      });
    });

    // ── Mobile flexible months ───────────────────────────────────────────
    const monthNames = ["January","February","March","April","May","June",
                        "July","August","September","October","November","December"];
    let mobFlexMonthIdx  = 0;
    let mobFlexNights    = 1;
    let mobMonthsRendered = false;

    function initMobFlexMonths() {
      const grid = document.getElementById("mob-flex-months-grid");
      if (!grid || mobMonthsRendered) return;
      mobMonthsRendered = true;
      const now = new Date();
      grid.innerHTML = "";
      for (let i = 0; i < 12; i++) {
        const d = new Date(now.getFullYear(), now.getMonth() + i, 1);
        const btn = document.createElement("button");
        btn.className = "mhb-month-pill" + (i === mobFlexMonthIdx ? " active" : "");
        btn.textContent = `${monthNames[d.getMonth()]} ${d.getFullYear()}`;
        btn.addEventListener("click", () => {
          mobFlexMonthIdx = i;
          grid.querySelectorAll(".mhb-month-pill").forEach((b, j) =>
            b.classList.toggle("active", j === i));
        });
        grid.appendChild(btn);
      }
    }

    // Nights counter (mobile)
    const mobMinus  = document.getElementById("mob-flex-minus");
    const mobPlus   = document.getElementById("mob-flex-plus");
    const mobNights = document.getElementById("mob-flex-nights-count");

    if (mobMinus && mobPlus && mobNights) {
      mobPlus.addEventListener("click", () => {
        if (mobFlexNights < 30) mobFlexNights++;
        mobNights.textContent = mobFlexNights;
        mobMinus.classList.toggle("disabled", mobFlexNights <= 1);
      });
      mobMinus.addEventListener("click", () => {
        if (mobFlexNights > 1) mobFlexNights--;
        mobNights.textContent = mobFlexNights;
        mobMinus.classList.toggle("disabled", mobFlexNights <= 1);
      });
    }
  }

  // See More / See Less Toggle for Overview Grid
  const overviewGrid = document.getElementById("overviewGrid");
  const seeMoreBtn = document.getElementById("seeMoreBtn");
  const seeLessBtn = document.getElementById("seeLessBtn");

  if (overviewGrid && seeMoreBtn && seeLessBtn) {
    seeMoreBtn.addEventListener("click", (e) => {
      e.preventDefault();
      overviewGrid.classList.remove("collapsed");
      seeMoreBtn.classList.add("d-none");
      seeLessBtn.classList.remove("d-none");
    });

    seeLessBtn.addEventListener("click", (e) => {
      e.preventDefault();
      overviewGrid.classList.add("collapsed");
      seeLessBtn.classList.add("d-none");
      seeMoreBtn.classList.remove("d-none");
      // Optional: scroll back to the grid top if user is far down
      overviewGrid.scrollIntoView({ behavior: "smooth", block: "start" });
    });
  }

  // ==========================================
  // GALLERY LIGHTBOX LOGIC
  // ==========================================
  const lightbox = document.getElementById("m-gallery-lightbox");
  if (lightbox) {
    const lightboxImg = document.getElementById("m-lightbox-img");
    const lightboxCounter = document.getElementById("m-lightbox-counter");
    const lightboxTitle = document.getElementById("m-lightbox-title");
    const closeBtn = lightbox.querySelector(".m-lightbox-close");
    const prevBtn = lightbox.querySelector(".m-lightbox-prev");
    const nextBtn = lightbox.querySelector(".m-lightbox-next");
    const tabs = lightbox.querySelectorAll(".m-lightbox-tab");

    let currentCategory = "";
    let currentIndex = 0;
    let categoryImages = {};

    // Initialize category images
    const sections = document.querySelectorAll(".m-gallery-section");
    sections.forEach((section) => {
      const category = section.id;
      const imgs = Array.from(
        section.querySelectorAll(".m-gallery-img-wrap img"),
      ).map((img) => ({
        src: img.src,
        alt: img.alt || "Gallery Image",
      }));
      categoryImages[category] = imgs;
    });

    const updateLightbox = () => {
      const images = categoryImages[currentCategory];
      if (images && images[currentIndex]) {
        lightboxImg.src = images[currentIndex].src;
        lightboxImg.alt = images[currentIndex].alt;
        lightboxCounter.textContent = `${currentIndex + 1} of ${images.length}`;
        lightboxTitle.textContent = images[currentIndex].alt;

        // Update active tab
        tabs.forEach((tab) => {
          tab.classList.toggle(
            "active",
            tab.dataset.category === currentCategory,
          );
        });
      }
    };

    const openLightbox = (category, index) => {
      currentCategory = category;
      currentIndex = index;
      updateLightbox();
      lightbox.classList.remove("d-none");
      document.body.style.overflow = "hidden"; // Prevent scrolling
    };

    const closeLightbox = () => {
      lightbox.classList.add("d-none");
      document.body.style.overflow = "";
      lightboxImg.src = "";
    };

    // Click on gallery image
    document.querySelectorAll(".m-gallery-img-wrap").forEach((wrap) => {
      wrap.addEventListener("click", () => {
        const section = wrap.closest(".m-gallery-section");
        if (section) {
          const category = section.id;
          const img = wrap.querySelector("img");
          const index = categoryImages[category].findIndex(
            (i) => i.src === img.src,
          );
          openLightbox(category, index);
        }
      });
    });

    // Nav buttons
    prevBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      const images = categoryImages[currentCategory];
      currentIndex = (currentIndex - 1 + images.length) % images.length;
      updateLightbox();
    });

    nextBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      const images = categoryImages[currentCategory];
      currentIndex = (currentIndex + 1) % images.length;
      updateLightbox();
    });

    // Close button
    closeBtn.addEventListener("click", closeLightbox);
    lightbox.addEventListener("click", (e) => {
      if (
        e.target === lightbox ||
        e.target.classList.contains("m-lightbox-content")
      ) {
        closeLightbox();
      }
    });

    // Tabs in lightbox
    tabs.forEach((tab) => {
      tab.addEventListener("click", (e) => {
        e.stopPropagation();
        const category = tab.dataset.category;
        if (categoryImages[category] && categoryImages[category].length > 0) {
          currentCategory = category;
          currentIndex = 0;
          updateLightbox();
        }
      });
    });

    // Keyboard support
    document.addEventListener("keydown", (e) => {
      if (lightbox.classList.contains("d-none")) return;

      if (e.key === "Escape") closeLightbox();
      if (e.key === "ArrowLeft") prevBtn.click();
      if (e.key === "ArrowRight") nextBtn.click();
    });
  }

  // ==========================================
  // MOBILE OVERLAYS LOGIC (ROOMS & RATES)
  // ==========================================

  // Rooms & Guests Overlay Logic
  const mobRoomsGuestsOverlay = document.getElementById(
    "m-mob-rooms-guests-overlay",
  );
  const mobRoomsGuestsTrigger = document.getElementById(
    "m-mob-rooms-guests-trigger",
  );
  const mobRoomsGuestsVal = document.getElementById("m-mob-rooms-guests-val");
  const mobRoomsGuestsCurrent = document.getElementById(
    "m-mob-rooms-guests-current",
  );
  const mobRoomsGuestsClose = document.getElementById(
    "m-mob-rooms-guests-close",
  );
  const mobRoomsGuestsDone = document.getElementById("m-mob-rooms-guests-done");

  let guestsState = {
    rooms: 1,
    adults: 1,
    children: 0,
  };

  const updateGuestsDisplay = () => {
    const rCount = document.getElementById("m-mob-count-rooms");
    const aCount = document.getElementById("m-mob-count-adults");
    const cCount = document.getElementById("m-mob-count-children");

    if (rCount) rCount.textContent = guestsState.rooms;
    if (aCount) aCount.textContent = guestsState.adults;
    if (cCount) cCount.textContent = guestsState.children;

    const label = `${guestsState.rooms} Room${guestsState.rooms > 1 ? "s" : ""}, ${guestsState.adults} Adult${guestsState.adults > 1 ? "s" : ""}${guestsState.children > 0 ? ", " + guestsState.children + " Child" + (guestsState.children > 1 ? "ren" : "") : ""}`;
    if (mobRoomsGuestsCurrent) mobRoomsGuestsCurrent.textContent = label;
  };

  if (mobRoomsGuestsTrigger && mobRoomsGuestsOverlay) {
    mobRoomsGuestsTrigger.addEventListener("click", () => {
      mobRoomsGuestsOverlay.classList.add("active");
    });

    if (mobRoomsGuestsClose) {
      mobRoomsGuestsClose.addEventListener("click", () => {
        mobRoomsGuestsOverlay.classList.remove("active");
      });
    }

    if (mobRoomsGuestsDone) {
      mobRoomsGuestsDone.addEventListener("click", () => {
        const label = `${guestsState.rooms} Room${guestsState.rooms > 1 ? "s" : ""}, ${guestsState.adults} Adult${guestsState.adults > 1 ? "s" : ""}${guestsState.children > 0 ? ", " + guestsState.children + " Child" + (guestsState.children > 1 ? "ren" : "") : ""}`;
        if (mobRoomsGuestsVal) mobRoomsGuestsVal.textContent = label;
        mobRoomsGuestsOverlay.classList.remove("active");
      });
    }

    // Counter buttons
    mobRoomsGuestsOverlay
      .querySelectorAll(".m-mob-counter-btn")
      .forEach((btn) => {
        btn.addEventListener("click", (e) => {
          e.preventDefault();
          const type = btn.dataset.type;
          const isPlus = btn.classList.contains("plus");

          if (type === "rooms") {
            if (isPlus && guestsState.rooms < 9) guestsState.rooms++;
            else if (!isPlus && guestsState.rooms > 1) guestsState.rooms--;
          } else if (type === "adults") {
            if (isPlus && guestsState.adults + guestsState.children < 8)
              guestsState.adults++;
            else if (!isPlus && guestsState.adults > 1) guestsState.adults--;
          } else if (type === "children") {
            if (isPlus && guestsState.adults + guestsState.children < 8)
              guestsState.children++;
            else if (!isPlus && guestsState.children > 0)
              guestsState.children--;
          }
          updateGuestsDisplay();
        });
      });
  }

  // Special Rates Overlay Logic
  const mobSpecialRatesOverlay = document.getElementById(
    "m-mob-special-rates-overlay",
  );
  const mobSpecialRatesTrigger = document.getElementById(
    "m-mob-special-rates-trigger",
  );
  const mobSpecialRatesVal = document.getElementById("m-mob-special-rates-val");
  const mobSpecialRatesCurrent = document.getElementById(
    "m-mob-special-rates-current",
  );
  const mobSpecialRatesClose = document.getElementById(
    "m-mob-special-rates-close",
  );
  const mobSpecialRatesDone = document.getElementById(
    "m-mob-special-rates-done",
  );
  const mobPromoField = document.getElementById("m-mob-promo-field");

  if (mobSpecialRatesTrigger && mobSpecialRatesOverlay) {
    mobSpecialRatesTrigger.addEventListener("click", () => {
      mobSpecialRatesOverlay.classList.add("active");
    });

    if (mobSpecialRatesClose) {
      mobSpecialRatesClose.addEventListener("click", () => {
        mobSpecialRatesOverlay.classList.remove("active");
      });
    }

    const rateRadios = mobSpecialRatesOverlay.querySelectorAll(
      'input[name="m-mob-rate"]',
    );
    rateRadios.forEach((radio) => {
      radio.addEventListener("change", () => {
        if (mobSpecialRatesCurrent)
          mobSpecialRatesCurrent.textContent = radio.value;
        if (mobPromoField) {
          if (radio.id === "m-mob-rate-promo-radio") {
            mobPromoField.classList.remove("d-none");
          } else {
            mobPromoField.classList.add("d-none");
          }
        }
      });
    });

    if (mobSpecialRatesDone) {
      mobSpecialRatesDone.addEventListener("click", () => {
        const selected = mobSpecialRatesOverlay.querySelector(
          'input[name="m-mob-rate"]:checked',
        );
        if (selected) {
          if (mobSpecialRatesVal)
            mobSpecialRatesVal.textContent = selected.value;
        }
        mobSpecialRatesOverlay.classList.remove("active");
      });
    }

    // Clear promo code
    const promoClear =
      mobSpecialRatesOverlay.querySelector(".m-mob-promo-clear");
    if (promoClear && mobPromoField) {
      promoClear.addEventListener("click", () => {
        const input = mobPromoField.querySelector("input");
        if (input) input.value = "";
      });
    }
  }

  // ==========================================
  // CUSTOM ROOM LIGHTBOX LOGIC
  // ==========================================
  const rlb = document.getElementById("m-room-lightbox");
  if (rlb) {
    const rlbImg = document.getElementById("m-rlb-img");
    const rlbCounter = document.getElementById("m-rlb-counter");
    const rlbTitle = document.getElementById("m-rlb-title");
    const rlbLink = document.getElementById("m-rlb-details-link");
    const rlbThumbs = document.getElementById("m-rlb-thumbs");
    const rlbClose = document.getElementById("m-rlb-close");
    const rlbPrev = document.getElementById("m-rlb-prev");
    const rlbNext = document.getElementById("m-rlb-next");

    let currentImages = [];
    let currentIdx = 0;

    function rlbUpdate() {
      if (!currentImages.length) return;
      rlbImg.src = currentImages[currentIdx];
      rlbCounter.textContent = `${currentIdx + 1} of ${currentImages.length}`;

      // Update thumbnails active state
      rlbThumbs.querySelectorAll(".m-rlb-thumb").forEach((t, i) => {
        t.classList.toggle("active", i === currentIdx);
      });

      // Show/hide arrows if only 1 image
      const showNav = currentImages.length > 1;
      rlbPrev.style.display = showNav ? "" : "none";
      rlbNext.style.display = showNav ? "" : "none";
    }

    function rlbBuildThumbs() {
      rlbThumbs.innerHTML = "";
      currentImages.forEach((src, i) => {
        const thumb = document.createElement("div");
        thumb.className = "m-rlb-thumb" + (i === currentIdx ? " active" : "");
        thumb.innerHTML = `<img src="${src}" alt="">`;
        thumb.addEventListener("click", () => {
          currentIdx = i;
          rlbUpdate();
        });
        rlbThumbs.appendChild(thumb);
      });
    }

    function rlbOpen(btn) {
      const images = JSON.parse(btn.dataset.images || "[]");
      const roomName = btn.dataset.roomName || "";
      const roomLink = btn.dataset.roomLink || "#";

      currentImages = images;
      currentIdx = 0;

      rlbTitle.textContent = roomName;
      rlbLink.href = roomLink;

      rlbBuildThumbs();
      rlbUpdate();

      rlb.classList.add("active");
      document.body.style.overflow = "hidden";
    }

    function rlbCloseFn() {
      rlb.classList.remove("active");
      document.body.style.overflow = "";
      rlbImg.src = "";
    }

    // Attach click events to all zoom buttons
    document.querySelectorAll(".m-room-zoom-btn").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        rlbOpen(btn);
      });
    });

    rlbClose.addEventListener("click", rlbCloseFn);

    rlbPrev.addEventListener("click", () => {
      currentIdx = (currentIdx - 1 + currentImages.length) % currentImages.length;
      rlbUpdate();
    });

    rlbNext.addEventListener("click", () => {
      currentIdx = (currentIdx + 1) % currentImages.length;
      rlbUpdate();
    });

    // Close on backdrop click
    rlb.addEventListener("click", (e) => {
      if (e.target === rlb) rlbCloseFn();
    });

    // Keyboard nav
    document.addEventListener("keydown", (e) => {
      if (!rlb.classList.contains("active")) return;
      if (e.key === "Escape") rlbCloseFn();
      if (e.key === "ArrowLeft") rlbPrev.click();
      if (e.key === "ArrowRight") rlbNext.click();
    });

    // Touch swipe support
    let touchStartX = 0;
    rlb.addEventListener("touchstart", (e) => {
      touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    rlb.addEventListener("touchend", (e) => {
      const diff = e.changedTouches[0].screenX - touchStartX;
      if (Math.abs(diff) > 50) {
        if (diff > 0) rlbPrev.click();
        else rlbNext.click();
      }
    }, { passive: true });
  }

  /* =========================================================================
     Local Attractions Slider (Experiences Page)
     ========================================================================= */
  const attractionsSwiperEl = document.querySelector('.m-attractions-swiper');
  if (attractionsSwiperEl) {
    new Swiper('.m-attractions-swiper', {
      slidesPerView: 1,
      spaceBetween: 20,
      watchOverflow: true, // Hide if only 1 slide
      pagination: {
        el: '.m-attractions-pagination',
        clickable: true
      },
      navigation: {
        nextEl: '.m-attractions-next',
        prevEl: '.m-attractions-prev',
      },
      breakpoints: {
        768: {
          enabled: false // Disable swiper completely on desktop
        }
      }
    });
  }
});
