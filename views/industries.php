<?php
  require __DIR__ . '/layouts/header.php'; 
?>


<body class="">
    <?php require __DIR__ . '/layouts/navbar.php'; ?>
    <!--==============================
    Breadcumb
============================== -->
    <div class="space">
            <div class="container">
                <div class="title-area text-center">
                    <span class="sub-title">What We Do?</span>
                    <h2 class="sec-title text-theme">Our Industries</h2>
                    <p class="sec-text">Since 1998, CENTRUY GROUP has been building a legacy of trust and innovation in Bangladesh.
                </div>
            </div>
        </div>
    <!--==============================
    Service Area  
    ==============================-->
    <section class="service-area-2 space bg-gray">
        <div class="sec-bg-shape2-1 spin shape-mockup d-xl-block d-none" data-bottom="5%" data-left="12%">
            <img src="assets/img/shape/section_shape_2_1.jpg" alt="img">
        </div>
        <div class="sec-bg-shape2-2 wave-anim shape-mockup d-xl-block d-none" data-top="-3%" data-left="5%" data-bg-src="assets/img/shape/section_shape_2_2.jpg"></div>
        <div class="sec-bg-shape2-3 jump shape-mockup d-xl-block d-none" data-top="10%" data-right="2%">
            <img src="assets/img/shape/section_shape_2_3.jpg" alt="img">
        </div>
        <div class="container">
            <div class="swiper th-slider" data-slider-options='{"breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":"1"},"768":{"slidesPerView":"2"},"992":{"slidesPerView":"2"},"1200":{"slidesPerView":"3"}}}'>
                <div class="swiper-wrapper" id="industries-container">
                </div>
            </div>
        </div>
    </section>

    <!--==============================
	Footer Area
==============================-->
    <?php require __DIR__ . '/layouts/footer.php'; ?>

    <!--********************************
			Code End  Here 
	******************************** -->

    <!-- Scroll To Top -->
    <div class="scroll-top">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;"></path>
        </svg>
    </div>

    <!--==============================
    All Js File
============================== -->
    <!-- Jquery -->
    <script src="assets/js/vendor/jquery-3.7.1.min.js"></script>
    <!-- Swiper Js -->
    <script src="assets/js/swiper-bundle.min.js"></script>
    <!-- Bootstrap -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Magnific Popup -->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <!-- Counter Up -->
    <script src="assets/js/jquery.counterup.min.js"></script>
    <!-- Range Slider -->
    <script src="assets/js/jquery-ui.min.js"></script>
    <!-- Isotope Filter -->
    <script src="assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <!-- Gsap -->
    <script src="assets/js/gsap.min.js"></script>

    <!-- Main Js File -->
    <script src="assets/js/main.js"></script>
    <script>
    $(document).ready(function () {
        $.ajax({
            url: "http://localhost/century-group/api/industries.php?action=list", // replace with your actual API endpoint
            method: "GET",
            success: function (response) {
    if (response.success && response.data.length > 0) {
        let industries = response.data;
        industries.sort((a, b) => a.id - b.id);

        let section = $("#industries-container").closest(".service-area-2 .container").parent();
        // remove the existing dynamic container to start fresh
        $("#industries-container").closest(".container").remove();

        // group industries into chunks of 3
        for (let i = 0; i < industries.length; i += 3) {
            let chunk = industries.slice(i, i + 3);

            // build a new container (copy of your original template)
            let containerHtml = `
                <div class="container" style= "overflow: visible;">
                    <div class="swiper th-slider" data-slider-options='{"breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":1},"768":{"slidesPerView":2},"1200":{"slidesPerView":3}}}'>
                        <div class="swiper-wrapper">
                            ${chunk.map(ind => `
                                <div class="swiper-slide">
                                    <div class="service-card style2">
                                        <div class="service-card-icon">
                                            <img src="${ind.icon}" alt="${ind.name}">
                                        </div>
                                        <h3 class="box-title"><a>${ind.name}</a></h3>
                                        <p class="box-text">${ind.description}</p>
                                        <div class="service-img img-shine">
                                            <img src="${ind.images[0]}" alt="${ind.name}">
                                        </div>
                                    </div>
                                </div>
                            `).join("")}
                        </div>
                    </div>
                </div>
                <br>
            `;

            section.append(containerHtml);
        }

        // re-init Swipers
        $(".th-slider").each(function () {
            new Swiper(this, {
                breakpoints: {
                    0: { slidesPerView: 1 },
                    576: { slidesPerView: 1 },
                    768: { slidesPerView: 2 },
                    1200: { slidesPerView: 3 }
                },
                spaceBetween: 30
            });
        });
    }
}

        });
    });
    </script>

</body>

</html>
