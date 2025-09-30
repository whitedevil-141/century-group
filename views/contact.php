<?php
  require __DIR__ . '/layouts/header.php'; 
?>


<body class="">
    <?php require __DIR__ . '/layouts/navbar.php'; ?>
        <!--==============================
Contact Area  
==============================-->
        <div class="space">
            <div class="container">
                <div class="title-area text-center">
                    <span class="sub-title">Get In Touch</span>
                    <h2 class="sec-title text-theme">Our Contact Information</h2>
                </div>
                <div class="row gy-4 justify-content-center">
                    <div class="col-xl-4 col-lg-6">
                        <div class="about-contact-grid">
                            <div class="about-contact-icon text-white">
                                <img src="assets/img/icon/location-dot.svg" alt="icon">
                            </div>
                            <div class="about-contact-details">
                                <h6 class="about-contact-details-title">Location:</h6>
                                <p class="about-contact-details-text">Kha-225, Progati Sarani, Merul, Badda</p>
                                <p class="about-contact-details-text"> Dhaka 1212, Bangladesh</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6">
                        <div class="about-contact-grid">
                            <div class="about-contact-icon text-white">
                                <img src="assets/img/icon/phone.svg" alt="icon">
                            </div>
                            <div class="about-contact-details">
                                <h6 class="about-contact-details-title">Phone:</h6>
                                <p class="about-contact-details-text"><a href="tel:880255055046">+8802 55055046</a></p>
                                <p class="about-contact-details-text"><a href="tel:55055047">55055047</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6">
                        <div class="about-contact-grid">
                            <div class="about-contact-icon text-white">
                                <img src="assets/img/icon/envelope.svg" alt="icon">
                            </div>
                            <div class="about-contact-details">
                                <h6 class="about-contact-details-title">Email:</h6>
                                <p class="about-contact-details-text"><a href="mailto:info@centurygroup.info">info@centurygroup.info</a></p>
                                <p class="about-contact-details-text"><a href="mailto:support@centurygroup.info">support@centurygroup.info</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--==============================
    Contact Area   
    ==============================-->
        <div class="space contact-area-3 z-index-common" >
            <div class="contact-bg-shape3-1 spin shape-mockup " data-bottom="5%" data-left="12%">
                <img src="assets/img/shape/section_shape_2_1.jpg" alt="img">
            </div>
            <div class="container">
                <div class="row gx-35">
                    <div class="col-lg-6">
                        <div class="appointment-wrap2 bg-white me-xxl-5" style="box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.3);">
                            <h2 class="form-title text-theme">Schedule a visit</h2>
                            <form action="mail.php" method="POST" class="appointment-form ajax-contact">
                                <div class="row">
                                    <div class="form-group style-border style-radius col-12">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Your Name*">
                                        <i class="fal fa-user"></i>
                                    </div>
                                    <div class="form-group style-border style-radius col-12">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Your Email*">
                                        <i class="fal fa-envelope"></i>
                                    </div>
                                    <div class="form-group style-border style-radius col-md-12">
                                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone Number*">
                                        <i class="fal fa-phone"></i>
                                    </div>
                                    <div class="col-12 form-group style-border style-radius">
                                        <i class="far fa-comments"></i>
                                        <textarea placeholder="Type Your Message" class="form-control"></textarea>
                                    </div>
                                    <div class="col-12 form-btn mt-4">
                                        <button class="th-btn">Submit Message <span class="btn-icon"><img src="assets/img/icon/paper-plane.svg" alt="img"></span></button>
                                    </div>
                                </div>
                                <p class="form-messages mb-0 mt-3"></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="location-map contact-sec-map z-index-common">
                <div class="contact-map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1825.6546380025704!2d90.4238235579207!3d23.771998683038504!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c7919634c8e7%3A0x105a467ba636f58a!2sCentury%20Group!5e0!3m2!1sen!2sbd!4v1758966596934!5m2!1sen!2sbd" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
        
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
</body>

</html>
