<script src="./assets/js/swiper-bundle.min.js?v=<?php echo time(); ?>"></script>

<script src="./assets/js/script.js?v=<?php echo time(); ?>"></script>



<script>
    document.addEventListener("DOMContentLoaded", function () {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("reveal-active");
                        observer.unobserve(entry.target); // Animate once
                    }
                });
            },
            { threshold: 0.1 }
        );

        document.querySelectorAll(".scrollToReveal, .scrollToRevealRight, .scrollToRevealLeft, .scrollToRevealTop")
            .forEach(el => observer.observe(el));
    });
</script>