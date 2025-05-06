// --------------------  sticky header ------------------------

// -----------  Banner Slider  --------------------

let index = 0;

function showSlide() {
  const sliderWrapper = document.querySelector(".slider-wrapper");
  const banners = document.querySelectorAll(".banner");
  if (index >= banners.length) {
    index = 0;
  } else if (index < 0) {
    index = banners.length - 1;
  }
  sliderWrapper.style.transform = "translateX(" + -index * 100 + "%)";
}

function nextSlide() {
  index++;
  showSlide();
}

function prevSlide() {
  index--;
  showSlide();
}
// Auto-slide every 5 seconds
setInterval(() => {
  nextSlide();
}, 5000);

//////////////////////////////////////// Salling Product slider
var swiper = new Swiper(".slide-content-bigCard", {
  slidesPerView: 5,
  spaceBetween: 12,
  loop: true,
  centerSlide: "true",
  fade: "true",
  grabCursor: "true",
  pagination: {
    el: ".swiper-pagination-bigCard",
    clickable: true,
    dynamicBullets: true,
  },
  navigation: {
    nextEl: ".next-bigCard",
    prevEl: ".prev-bigCard",
  },

  breakpoints: {
    0: {
      slidesPerView: 2,
    },
    520: {
      slidesPerView: 4,
    },
    950: {
      slidesPerView: 5,
    },
  },
});

//////////////////////////////////////////// Category slider
var swiper = new Swiper(".slide-content-category", {
  slidesPerView: 5,
  spaceBetween: 12,
  loop: true,
  centerSlide: "true",
  fade: "true",
  grabCursor: "true",
  pagination: {
    el: ".swiper-pagination-category",
    clickable: true,
    dynamicBullets: true,
  },
  navigation: {
    nextEl: ".next-category",
    prevEl: ".prev-category",
  },

  breakpoints: {
    0: {
      slidesPerView: 5,
    },
    520: {
      slidesPerView: 6,
    },
    950: {
      slidesPerView: 6,
    },
    1350: {
      slidesPerView: 6,
    },
  },
});

// CATEGORY filter toggle
document
  .querySelector(".categoryFilterBtn")
  .addEventListener("click", function (e) {
    e.stopPropagation(); // Prevent triggering document click
    const categoryFilter = document.querySelector("#categoryFilter");
    const subCategoryFilter = document.querySelector("#subCategoryFilter");

    // Close sub-category filter if open
    subCategoryFilter.style.display = "none";

    // Toggle category filter
    categoryFilter.style.display =
      categoryFilter.style.display === "block" ? "none" : "block";
  });

// SUB-CATEGORY filter toggle
document
  .querySelector(".subCategoryFilterBtn")
  .addEventListener("click", function (e) {
    e.stopPropagation();
    const categoryFilter = document.querySelector("#categoryFilter");
    const subCategoryFilter = document.querySelector("#subCategoryFilter");

    // Close category filter if open
    categoryFilter.style.display = "none";

    // Toggle sub-category filter
    subCategoryFilter.style.display =
      subCategoryFilter.style.display === "block" ? "none" : "block";
  });

// Close buttons
document
  .querySelector(".closeCategoryFilter")
  .addEventListener("click", function () {
    document.querySelector("#categoryFilter").style.display = "none";
  });
document
  .querySelector(".closeSubCategoryFilter")
  .addEventListener("click", function () {
    document.querySelector("#subCategoryFilter").style.display = "none";
  });

// Close both if clicking outside
document.addEventListener("click", function (e) {
  const categoryFilter = document.querySelector("#categoryFilter");
  const subCategoryFilter = document.querySelector("#subCategoryFilter");

  // Check if click is outside both filters and buttons
  if (
    !e.target.closest("#categoryFilter") &&
    !e.target.closest(".categoryFilterBtn") &&
    !e.target.closest("#subCategoryFilter") &&
    !e.target.closest(".subCategoryFilterBtn")
  ) {
    categoryFilter.style.display = "none";
    subCategoryFilter.style.display = "none";
  }
});
