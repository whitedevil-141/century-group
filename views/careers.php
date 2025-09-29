<?php
require __DIR__ . '/layouts/header.php';
?>


<body class="">
    <?php require __DIR__ . '/layouts/navbar.php'; ?>
    <!--==============================
    Breadcumb
============================== -->
    <div class="container">
        <div class="title-area text-center">
            <span class="sub-title">Join Us</span>
            <h2 class="sec-title text-theme">Careers at Century Group</h2>
        </div>
    </div>
    <!--==============================
    Careers Area  
    ==============================-->
    <section class="careers-area space bg-title-dark">
        <div class="container">
            <!-- Filter Bar -->
            <div class="row mb-5 gy-3">
                <div class="col-md-3">
                    <select class="form-select">
                        <option>Department</option>
                        <option>Real Estate</option>
                        <option>Hospitality</option>
                        <option>Aviation</option>
                        <option>Finance</option>
                        <option>Textiles</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option>Location</option>
                        <option>Dhaka</option>
                        <option>Kuakata</option>
                        <option>Chattogram</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option>Job Type</option>
                        <option>Full Time</option>
                        <option>Part Time</option>
                        <option>Internship</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Search by title...">
                </div>
            </div>

            <!-- Job Listings -->
            <div class="row gy-4">
                <!-- Job Card -->
                <div class="col-md-6">
                    <div class="job-card p-4 bg-gray shadow-sm rounded h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="job-title mb-2">Marketing Executive</h5>
                            <p class="job-meta text-muted mb-2">
                                <i class="fa-solid fa-building"></i> Hospitality |
                                <i class="fa-solid fa-location-dot"></i> Dhaka |
                                <i class="fa-solid fa-clock"></i> Full Time
                            </p>
                            <p class="job-desc">We are looking for an energetic Marketing Executive to support branding campaigns across our hotels and resorts.</p>
                        </div>
                        <div class="text-end">
                            <button 
                                class="th-btn mb-0 style1 th-btn-icon apply-btn"
                                data-bs-toggle="modal" 
                                data-bs-target="#applyModal"
                                data-id="1"
                                data-title="Marketing Executive">
                                Apply Now
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Another Job Card -->
                <div class="col-md-6">
                    <div class="job-card p-4 bg-gray shadow-sm rounded h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="job-title mb-2">Finance Officer</h5>
                            <p class="job-meta text-muted mb-2">
                                <i class="fa-solid fa-building"></i> Finance |
                                <i class="fa-solid fa-location-dot"></i> Chattogram |
                                <i class="fa-solid fa-clock"></i> Full Time
                            </p>
                            <p class="job-desc">Join our finance team to support auditing, compliance, and reporting across multiple Century Group industries.</p>
                        </div>
                        <div class="text-end">
                            <button 
                                class="th-btn mb-0 style1 th-btn-icon apply-btn"
                                data-bs-toggle="modal" 
                                data-bs-target="#applyModal"
                                data-id="2"
                                data-title="Finance Officer">
                                Apply Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--==============================
Application Modal v1 (Stacked Fields)
==============================-->
    <div class="th-modal modal fade" id="applyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 600px;">
            <div class="modal-content bg-white rounded-20 p-4 shadow-lg position-relative">

                <!-- Close Button -->
                <button type="button" class="icon-btn btn-close bg-title-dark" data-bs-dismiss="modal" aria-label="Close"><i class="fa-regular fa-xmark"></i></button>
                <!-- Title -->
                <h2 class="h3 page-title text-theme fw-bold mb-4">Submit Your Application</h2>

                <!-- Application Form -->
                <form id="applyForm" action="http://localhost/century-group/api/apply.php" method="POST" enctype="multipart/form-data" class="appointment-form">
                    <div class="row g-3">
                        <input type="hidden" name="job_id" id="job_id">
                        <!-- Name -->
                        <div class="col-12">
                            <label class="form-label">Your Name*</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fal fa-user"></i></span>
                                <input type="text" class="form-control" name="name" placeholder="Enter your full name" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-12">
                            <label class="form-label">Your Email*</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fal fa-envelope"></i></span>
                                <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-12">
                            <label class="form-label">Your Phone*</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fal fa-phone"></i></span>
                                <input type="text" class="form-control" name="phone" placeholder="Enter your phone number" required>
                            </div>
                        </div>

                        <!-- Position -->
                        <div class="col-12">
                            <label class="form-label">Position*</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fal fa-briefcase"></i></span>
                                <input type="text" class="form-control" id="job_title" readonly>
                            </div>
                        </div>

                        <!-- CV Upload -->
                        <div class="col-12">
                            <label class="form-label">Upload CV (PDF/DOC)*</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fal fa-file-alt"></i></span>
                                <input type="file" class="form-control" name="cv" accept=".pdf,.doc,.docx" required>

                            </div>
                        </div>

                        <!-- Message -->
                        <div class="col-12">
                            <label class="form-label">Additional Message (optional)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-comments"></i></span>
                                <textarea name="message" placeholder="Type your message here..." class="form-control" rows="4" ></textarea>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="th-btn">
                                Submit Application
                                <span class="btn-icon">
                                    <img src="assets/img/icon/paper-plane.svg" alt="icon">
                                </span>
                            </button>
                        </div>
                    </div>
                    <p class="form-messages mb-0 mt-3"></p>
                </form>
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
    <script>
    document.getElementById("applyForm").addEventListener("submit", async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        const res = await fetch(this.action, {
            method: "POST",
            body: formData
        });

        const json = await res.json();
        alert(json.message);

        if (json.success) {
            this.reset();
            bootstrap.Modal.getInstance(document.getElementById("applyModal")).hide();
        } else {
            console.error(json.debug);
        }
    });
    </script>

    <script>
    document.querySelectorAll(".apply-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            document.getElementById("job_id").value = this.dataset.id;
            document.getElementById("job_title").value = this.dataset.title;
        });
    });
    </script>

</body>

</html>