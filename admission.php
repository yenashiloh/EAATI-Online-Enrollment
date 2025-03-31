<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Eastern Achiever Academy of Taguig Inc.</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="asset/img/logo.png" rel="icon">
    <link href="asset/img/logo.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="asset/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="asset/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="asset/vendor/aos/aos.css" rel="stylesheet">
    <link href="asset/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="asset/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="asset/css/main.css" rel="stylesheet">
    <style>
        .fixed-size {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }

        #home {
            scroll-margin-top: 80px;
        }

        #student-life {
            scroll-margin-top: 80px;
        }

        #about {
            scroll-margin-top: 80px;
        }

        #contact {
            scroll-margin-top: 130px;
        }

        .card {
            margin-bottom: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
        }

        .card-header {
            background-color: #f8f9fa;
            padding: 15px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .card-header:hover {
            background-color: #e9ecef;
        }

        .card-link {
            color: #333;
            text-decoration: none;
            font-weight: 600;
            width: 100%;
            display: block;
        }

        .card-link:hover {
            color: #981522;
        }

        .collapse {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .collapse.show {
            max-height: 1000px;
            overflow: visible;
        }

        .card-body {
            padding: 15px;
            background-color: #ffffff;
        }

        .card-header::after {
            content: '▼';
            margin-left: auto;
            color: #888;
            transition: transform 0.3s ease;
        }

        .card-header.active::after {
            transform: rotate(180deg);
        }
    </style>
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="index.php" class="logo d-flex align-items-center me-auto">
                <img src="asset/img/logo.png" alt="">
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="index.php#hero">Home<br></a></li>
                    <li><a href="index.php#about">About</a></li>
                    <li><a href="index.php#student-life">Student Life</a></li>
                    <li><a href="index.php#contact">Contact Us</a></li>
                    <li><a href="admission.php">Admission</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <a class="btn-getstarted" href="login.php">Login</a>

        </div>
    </header>

    <main class="main">

        <section class="teacher_section layout_padding-bottom">
            <div class="container">
                <div class="container section-title" data-aos="fade-up">
                    <h2>School Admission</h2>
                    <p>Admission</p>
                </div>
                <!-- End Section Title -->
                <?php
                if (isset($_SESSION['error_message'])) {
                    echo '<div class="alert alert-danger" role="alert">';
                    echo $_SESSION['error_message'];
                    echo '</div>';
                    unset($_SESSION['error_message']);
                }
                ?>

                <?php if (isset($_SESSION['error_message'])) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['error_message']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>

                <script>
                    setTimeout(function() {
                        let alerts = document.querySelectorAll('.alert');
                        alerts.forEach(alert => {
                            alert.classList.remove('show');
                            alert.classList.add('fade');
                            setTimeout(() => alert.remove(), 500);
                        });
                    }, 5000); // 5 seconds
                </script>

                <div id="accordion" data-aos="fade-up">
                    <div class="card">
                        <div class="card-header">
                            <a class="card-link" data-toggle="collapse" href="#collapseOne">
                                Admission Requirements
                            </a>
                        </div>
                        <div id="collapseOne" class="collapse show" data-parent="#accordion">
                            <div class="card-body">
                                <ul>
                                    <li>F138 (CARD)</li>
                                    <li>Birth Certificate Xerox</li>
                                    <li>Good moral Certificate</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">
                                Student Registration
                            </a>
                        </div>
                        <div id="collapseTwo" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                <form method="post" action="insert.php" onsubmit="return validateForm()">
                                    <div class="form-group mb-2">
                                        <label>Are you a new or old student?</label>
                                        <div>
                                            <input type="radio" id="new_student" name="student_type" value="new" onclick="toggleStudentType()"> New
                                            <input type="radio" id="old_student" name="student_type" value="old" onclick="toggleStudentType()"> Old
                                        </div>
                                    </div>

                                    <div id="student_details" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-2">
                                                    <label for="last_name">Last Name</label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                                    <small id="last_name_error" class="text-danger"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-2">
                                                    <label for="first_name">First Name</label>
                                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                                    <small id="first_name_error" class="text-danger"></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-2">
                                                    <label for="contact_number">Contact Number</label>
                                                    <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                                                    <small id="contact_number_error" class="text-danger"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group mb-2">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="old_student_fields" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-2">
                                                        <label for="username">Learner Reference Number (LRN)</label>
                                                        <input type="text" class="form-control" id="username" name="username">
                                                        <small class="form-text text-muted">For old students, please enter your LRN.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-2">
                                                        <label for="old_password">Password</label>
                                                        <input type="password" class="form-control" id="old_password" name="password">
                                                        <small id="old_password_error" class="text-danger"></small>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-2">
                                                        <label for="old_confirm_password">Confirm Password</label>
                                                        <input type="password" class="form-control" id="old_confirm_password" name="confirm_password">
                                                        <small id="old_confirm_password_error" class="text-danger"></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="new_student_fields" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-2">
                                                        <label for="new_password">Password</label>
                                                        <input type="password" class="form-control" id="new_password" name="password">
                                                        <small id="new_password_error" class="text-danger"></small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-2">
                                                        <label for="new_confirm_password">Confirm Password</label>
                                                        <input type="password" class="form-control" id="new_confirm_password" name="confirm_password">
                                                        <small id="new_confirm_password_error" class="text-danger"></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-3">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                        <div class="text-center mt-2">
                                            <medium><b><i>Note: Please login to submit your requirements.</i></b></medium>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="requirementsModal" tabindex="-1" role="dialog" aria-labelledby="requirementsModalLabel" aria-hidden="true" style="margin-top: 50px;">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="requirementsModalLabel">Requirements</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <ul>
                                                        <li>F138 (CARD)</li>
                                                        <li>Birth Certificate Xerox</li>
                                                        <li>Good moral Certificate</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <script>
                                function toggleStudentType() {
                                    var newStudent = document.getElementById('new_student').checked;
                                    var oldStudent = document.getElementById('old_student').checked;
                                    var studentDetails = document.getElementById('student_details');
                                    var newStudentFields = document.getElementById('new_student_fields');
                                    var oldStudentFields = document.getElementById('old_student_fields');

                                    studentDetails.style.display = 'block';

                                    if (newStudent) {
                                        newStudentFields.style.display = 'block';
                                        oldStudentFields.style.display = 'none';

                                        document.getElementById('new_password').required = true;
                                        document.getElementById('new_confirm_password').required = true;
                                        document.getElementById('username').required = false;
                                        document.getElementById('old_password').required = false;
                                        document.getElementById('old_confirm_password').required = false;
                                    } else if (oldStudent) {
                                        newStudentFields.style.display = 'none';
                                        oldStudentFields.style.display = 'block';

                                        document.getElementById('username').required = true;
                                        document.getElementById('old_password').required = true;
                                        document.getElementById('old_confirm_password').required = true;
                                        document.getElementById('new_password').required = false;
                                        document.getElementById('new_confirm_password').required = false;
                                    }
                                }

                                function validateForm() {
                                    var newStudent = document.getElementById('new_student').checked;
                                    var oldStudent = document.getElementById('old_student').checked;

                                    if (!newStudent && !oldStudent) {
                                        alert("Please select whether you are a new or old student.");
                                        return false;
                                    }

                                    var password, confirmPassword;

                                    if (newStudent) {
                                        password = document.getElementById('new_password').value;
                                        confirmPassword = document.getElementById('new_confirm_password').value;
                                    } else if (oldStudent) {
                                        password = document.getElementById('old_password').value;
                                        confirmPassword = document.getElementById('old_confirm_password').value;
                                    }

                                    if (password !== confirmPassword) {
                                        alert("Passwords do not match!");
                                        return false;
                                    }

                                    return true;
                                }
                            </script>
                        </div>
                    </div>
                </div>
                <div class="card" data-aos="fade-up">
                    <div class="card-header">
                        <a class="collapsed card-link" data-toggle="collapse" href="#collapseFour">
                            Enrollment
                        </a>
                    </div>
                    <div id="collapseFour" class="collapse" data-parent="#accordion">
                        <div class="card-body">
                            <ol>
                                <li>Student enlistment/registration</li>
                                <li>View student registration</li>
                                <li>View school fees</li>
                                <li>Process school fees
                                    <ul>
                                        <li>Installment</li>
                                        <li>Cash basis</li>
                                    </ul>
                                </li>
                                <li>Payment process
                                    <ul>
                                        <li>Online</li>
                                        <li>Onsite</li>
                                    </ul>
                                </li>
                                <li>Admin approval</li>
                                <li>Student view certificate of registration</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="asset/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="asset/vendor/php-email-form/validate.js"></script>
    <script src="asset/vendor/aos/aos.js"></script>
    <script src="asset/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="asset/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="asset/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="asset/js/main.js"></script>

    <!-- Active Nav -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const navLinks = document.querySelectorAll("#navmenu ul li a");
            let path = window.location.pathname;

            if (path === "/onlineenrollment/" || path.endsWith("index.php")) {
                navLinks.forEach(link => link.classList.remove("active"));
                document.querySelector("a[href='#hero']").classList.add("active");
            }

            navLinks.forEach(link => {
                link.addEventListener("click", function() {
                    navLinks.forEach(nav => nav.classList.remove("active"));
                    this.classList.add("active");
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const cardHeaders = document.querySelectorAll('.card-header .card-link');

            cardHeaders.forEach(header => {
                header.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href').replace('#', '');
                    const targetCollapse = document.getElementById(targetId);

                    const allCollapses = document.querySelectorAll('.collapse');
                    allCollapses.forEach(collapse => {
                        if (collapse.id !== targetId) {
                            collapse.classList.remove('show');
                        }
                    });

                    targetCollapse.classList.toggle('show');
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
    function showError(inputId, message) {
        document.getElementById(inputId + "_error").textContent = message;
    }

    function clearError(inputId) {
        document.getElementById(inputId + "_error").textContent = "";
    }

    // Restrict special characters and numbers in name fields
    document.getElementById("last_name").addEventListener("input", function () {
        this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
        if (!/^[a-zA-Z\s]+$/.test(this.value)) {
            showError("last_name", "Last name should only contain letters.");
        } else {
            clearError("last_name");
        }
    });

    document.getElementById("first_name").addEventListener("input", function () {
        this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
        if (!/^[a-zA-Z\s]+$/.test(this.value)) {
            showError("first_name", "First name should only contain letters.");
        } else {
            clearError("first_name");
        }
    });

    // Ensure contact number is exactly 11 digits
    document.getElementById("contact_number").addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 11);
        if (this.value.length !== 11) {
            showError("contact_number", "Contact number must be exactly 11 digits.");
        } else {
            clearError("contact_number");
        }
    });

    // Password validation function
    function validatePassword(passwordId, confirmPasswordId) {
        let password = document.getElementById(passwordId).value;
        let confirmPassword = document.getElementById(confirmPasswordId).value;
        let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/; // At least 8 characters, 1 uppercase, 1 lowercase, 1 special character

        if (!passwordPattern.test(password)) {
            showError(passwordId, "Password must be at least 8 characters, include an uppercase letter, a lowercase letter, and a special character.");
            return false;
        } else {
            clearError(passwordId);
        }

        if (password !== confirmPassword) {
            showError(confirmPasswordId, "Passwords do not match!");
            return false;
        } else {
            clearError(confirmPasswordId);
            return true;
        }
    }

    document.getElementById("new_password").addEventListener("input", function () {
        validatePassword("new_password", "new_confirm_password");
    });

    document.getElementById("new_confirm_password").addEventListener("input", function () {
        validatePassword("new_password", "new_confirm_password");
    });

    document.getElementById("old_password").addEventListener("input", function () {
        validatePassword("old_password", "old_confirm_password");
    });

    document.getElementById("old_confirm_password").addEventListener("input", function () {
        validatePassword("old_password", "old_confirm_password");
    });

    // Form validation before submitting
    window.validateForm = function () {
        let isValid = true;

        if (!/^[a-zA-Z\s]+$/.test(document.getElementById("last_name").value)) {
            showError("last_name", "Last name should only contain letters.");
            isValid = false;
        }

        if (!/^[a-zA-Z\s]+$/.test(document.getElementById("first_name").value)) {
            showError("first_name", "First name should only contain letters.");
            isValid = false;
        }

        if (document.getElementById("contact_number").value.length !== 11) {
            showError("contact_number", "Contact number must be exactly 11 digits.");
            isValid = false;
        }

        if (!validatePassword("new_password", "new_confirm_password") || !validatePassword("old_password", "old_confirm_password")) {
            isValid = false;
        }

        return isValid;
    };
});

    </script>

</body>

</html>
