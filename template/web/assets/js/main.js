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
        (!ulSidebar.contains(e.target) && !ulSidebarOpener.contains(e.target)) ||
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
        if (type === "rooms" && bookingState.rooms < 3) bookingState.rooms++;
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
    guestRows.forEach((row) => {
      const type = row.getAttribute("data-type");
      const minusBtn = row.querySelector(".minus");
      const numEl = row.querySelector(".mhb-count-num");

      if (numEl) {
        if (type === "rooms") numEl.textContent = bookingState.rooms;
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
    });

    // Update Global text
    let text = `${bookingState.rooms} ${
      bookingState.rooms > 1 ? "Rooms" : "Room"
    }, ${bookingState.adults} ${bookingState.adults > 1 ? "Adults" : "Adult"}`;
    if (bookingState.children > 0) {
      text += `, ${bookingState.children} ${
        bookingState.children > 1 ? "Children" : "Child"
      }`;
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
      const modalElement = document.getElementById('enquiryModal');
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
        let text = `Hello, I am ${name}. I have an enquiry: ${message || 'Interested in booking a table.'}`;
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
      vtModal.addEventListener('show.bs.modal', () => {
        // Force the sidebar to be open when the modal launches
        vtSidebarWrapper.classList.remove("collapsed");
      });
    }

    // Handle Active state on menu clicks (Mock functionality)
    const menuItems = document.querySelectorAll(".m-vt-menu-item");
    menuItems.forEach(item => {
      item.addEventListener("click", function() {
        menuItems.forEach(btn => btn.classList.remove("active"));
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
  const otherWrapper = document.getElementById('otherEventTypeWrapper');
  const otherInput = document.getElementById('otherEventType');
  if (selectElement.value === 'other') {
      otherWrapper.classList.remove('d-none');
      otherInput.setAttribute('required', 'required');
  } else {
      otherWrapper.classList.add('d-none');
      otherInput.removeAttribute('required');
      otherInput.value = '';
  }
}

// Handle Event Plan Form Submission
document.addEventListener("DOMContentLoaded", function () {
  const eventPlanForm = document.getElementById("eventPlanForm");
  if (eventPlanForm) {
      eventPlanForm.addEventListener("submit", function (e) {
          e.preventDefault();
          
          const formData = new FormData(eventPlanForm);
          const data = Object.fromEntries(formData.entries());
          
          console.log("Event Plan Form Submitted:", data);
          alert("Thank you for your event enquiry! We will contact you soon to plan it together.");
          
          // Close modal
          const modalElement = document.getElementById('eventPlanModal');
          const modal = bootstrap.Modal.getInstance(modalElement);
          if (modal) modal.hide();
          
          eventPlanForm.reset();
          const otherWrapper = document.getElementById('otherEventTypeWrapper');
          if (otherWrapper) {
              otherWrapper.classList.add('d-none');
          }
});
  }
});

// ===== EVENTS PAGE: SCROLL TO ACCORDION & OPEN =====
document.addEventListener("DOMContentLoaded", function () {
  const scrollToAccordionLinks = document.querySelectorAll(".ul-scroll-to-accordion");
  scrollToAccordionLinks.forEach(link => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("href");
      if (!targetId || targetId === "#") return;

      const targetCollapse = document.querySelector(targetId);
      if (targetCollapse) {
        // Use Bootstrap Collapse API to open it if closed
        if (!targetCollapse.classList.contains("show") && typeof bootstrap !== "undefined") {
          const bsCollapse = bootstrap.Collapse.getOrCreateInstance(targetCollapse);
          bsCollapse.show();
        }

        // Scroll to the accordion header (offset by ~100px for sticky header)
        setTimeout(() => {
          const headerElement = targetCollapse.previousElementSibling;
          const elementToScrollTo = headerElement ? headerElement : targetCollapse;
          const offsetTop = elementToScrollTo.getBoundingClientRect().top + window.scrollY - 100;
          
          window.scrollTo({
            top: offsetTop,
            behavior: "smooth"
          });
        }, 150); // slight delay to allow collapse animation to start
      }
    });
  });

  // Link 'View Rates' buttons to open the header calendar
  const viewRatesBtns = document.querySelectorAll('.m-room-slide-btn');
  const datesField = document.querySelector('#mhb-dates-field');
  const datesValue = datesField ? datesField.querySelector('.mhb-value') : null;

  if (viewRatesBtns.length > 0 && datesValue) {
    viewRatesBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        // Scroll to the booking widget
        // datesField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        // Trigger the calendar dropdown
        setTimeout(() => {
          datesValue.click();
        }); // Wait for scroll to stabilize
      });
    });
  }

  // See More / See Less Toggle for Overview Grid
  const overviewGrid = document.getElementById('overviewGrid');
  const seeMoreBtn = document.getElementById('seeMoreBtn');
  const seeLessBtn = document.getElementById('seeLessBtn');

  if (overviewGrid && seeMoreBtn && seeLessBtn) {
    seeMoreBtn.addEventListener('click', (e) => {
      e.preventDefault();
      overviewGrid.classList.remove('collapsed');
      seeMoreBtn.classList.add('d-none');
      seeLessBtn.classList.remove('d-none');
    });

    seeLessBtn.addEventListener('click', (e) => {
      e.preventDefault();
      overviewGrid.classList.add('collapsed');
      seeLessBtn.classList.add('d-none');
      seeMoreBtn.classList.remove('d-none');
      // Optional: scroll back to the grid top if user is far down
      overviewGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  }

  // ==========================================
  // GALLERY LIGHTBOX LOGIC
  // ==========================================
  const lightbox = document.getElementById('m-gallery-lightbox');
  if (lightbox) {
    const lightboxImg = document.getElementById('m-lightbox-img');
    const lightboxCounter = document.getElementById('m-lightbox-counter');
    const lightboxTitle = document.getElementById('m-lightbox-title');
    const closeBtn = lightbox.querySelector('.m-lightbox-close');
    const prevBtn = lightbox.querySelector('.m-lightbox-prev');
    const nextBtn = lightbox.querySelector('.m-lightbox-next');
    const tabs = lightbox.querySelectorAll('.m-lightbox-tab');

    let currentCategory = '';
    let currentIndex = 0;
    let categoryImages = {};

    // Initialize category images
    const sections = document.querySelectorAll('.m-gallery-section');
    sections.forEach(section => {
      const category = section.id;
      const imgs = Array.from(section.querySelectorAll('.m-gallery-img-wrap img')).map(img => ({
        src: img.src,
        alt: img.alt || 'Gallery Image'
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
        tabs.forEach(tab => {
          tab.classList.toggle('active', tab.dataset.category === currentCategory);
        });
      }
    };

    const openLightbox = (category, index) => {
      currentCategory = category;
      currentIndex = index;
      updateLightbox();
      lightbox.classList.remove('d-none');
      document.body.style.overflow = 'hidden'; // Prevent scrolling
    };

    const closeLightbox = () => {
      lightbox.classList.add('d-none');
      document.body.style.overflow = '';
      lightboxImg.src = '';
    };

    // Click on gallery image
    document.querySelectorAll('.m-gallery-img-wrap').forEach(wrap => {
      wrap.addEventListener('click', () => {
        const section = wrap.closest('.m-gallery-section');
        if (section) {
          const category = section.id;
          const img = wrap.querySelector('img');
          const index = categoryImages[category].findIndex(i => i.src === img.src);
          openLightbox(category, index);
        }
      });
    });

    // Nav buttons
    prevBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const images = categoryImages[currentCategory];
      currentIndex = (currentIndex - 1 + images.length) % images.length;
      updateLightbox();
    });

    nextBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const images = categoryImages[currentCategory];
      currentIndex = (currentIndex + 1) % images.length;
      updateLightbox();
    });

    // Close button
    closeBtn.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', (e) => {
      if (e.target === lightbox || e.target.classList.contains('m-lightbox-content')) {
        closeLightbox();
      }
    });

    // Tabs in lightbox
    tabs.forEach(tab => {
      tab.addEventListener('click', (e) => {
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
    document.addEventListener('keydown', (e) => {
      if (lightbox.classList.contains('d-none')) return;

      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowLeft') prevBtn.click();
      if (e.key === 'ArrowRight') nextBtn.click();
    });
  }
});

