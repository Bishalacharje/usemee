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

//////////////////////////////////////////// mobile and tab category Filter
document
  .querySelector(".categoryFilterBtn")
  .addEventListener("click", function () {
    var categoryFilter = document.querySelector("#categoryFilter");
    // Toggle display property
    if (categoryFilter.style.display === "block") {
      categoryFilter.style.display = "none";
    } else {
      categoryFilter.style.display = "block";
    }
  });

// Close button functionality
document
  .querySelector(".closeCategoryFilter")
  .addEventListener("click", function () {
    document.querySelector("#categoryFilter").style.display = "none";
  });

//////////////////////////////////////////// mobile and tab Sub category Filter
document
  .querySelector(".subCategoryFilterBtn")
  .addEventListener("click", function () {
    var subCategoryFilter = document.querySelector("#subCategoryFilter");
    // Toggle display property
    if (subCategoryFilter.style.display === "block") {
      subCategoryFilter.style.display = "none";
    } else {
      subCategoryFilter.style.display = "block";
    }
  });

// Close button functionality
document
  .querySelector(".closeSubCategoryFilter")
  .addEventListener("click", function () {
    document.querySelector("#subCategoryFilter").style.display = "none";
  });
