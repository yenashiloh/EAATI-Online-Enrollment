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
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $_SESSION['success_message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

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
                                Student Admission
                            </a>
                        </div>
                        <div id="collapseTwo" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                <form method="post" action="insert.php" onsubmit="return validateForm()" enctype="multipart/form-data" id="myForm">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="first_name" class="form-label">First Name</label>
                                            <input type="text" id="first_name" name="first_name" class="form-control">
                                            <small class="text-danger error-message" id="first_name_error"></small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" id="last_name" name="last_name" class="form-control">
                                            <small class="text-danger error-message" id="last_name_error"></small>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" id="username" name="username" class="form-control">
                                            <small class="text-danger error-message" id="username_error"></small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" id="email" name="email" class="form-control">
                                            <small class="text-danger error-message" id="email_error"></small>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="contact_number" class="form-label">Contact Number</label>
                                            <input type="number" id="contact_number" name="contact_number" class="form-control">
                                            <small class="text-danger error-message" id="contact_number_error"></small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="photo" class="form-label">Upload Photo (2x2)</label>
                                            <input type="file" id="photo" name="photo" class="form-control">
                                            <small class="text-danger error-message" id="photo_error"></small>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" id="password" name="password" class="form-control">
                                            <small class="text-danger error-message" id="password_error"></small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="confirm_password" class="form-label">Confirm Password</label>
                                            <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                                            <small class="text-danger error-message" id="confirm_password_error"></small>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                                </form>
                            </div>
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

       //validation 
       function validateForm() {
    // Reset all error messages
    const errorElements = document.getElementsByClassName('error-message');
    for (let i = 0; i < errorElements.length; i++) {
        errorElements[i].textContent = '';
    }
    
    let isValid = true;
    
    // Validate first name
    const firstName = document.getElementById('first_name').value.trim();
    if (firstName === '') {
        document.getElementById('first_name_error').textContent = 'First name is required';
        isValid = false;
    }
    
    // Validate last name
    const lastName = document.getElementById('last_name').value.trim();
    if (lastName === '') {
        document.getElementById('last_name_error').textContent = 'Last name is required';
        isValid = false;
    }
    
    // Validate username
    const username = document.getElementById('username').value.trim();
    if (username === '') {
        document.getElementById('username_error').textContent = 'Username is required';
        isValid = false;
    } else if (username.length < 4) {
        document.getElementById('username_error').textContent = 'Username must be at least 4 characters';
        isValid = false;
    }
    
    // Validate email
    const email = document.getElementById('email').value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === '') {
        document.getElementById('email_error').textContent = 'Email is required';
        isValid = false;
    } else if (!emailRegex.test(email)) {
        document.getElementById('email_error').textContent = 'Please enter a valid email address';
        isValid = false;
    }
    
    // Validate contact number
    const contactNumber = document.getElementById('contact_number').value.trim();
    if (contactNumber === '') {
        document.getElementById('contact_number_error').textContent = 'Contact number is required';
        isValid = false;
    } else if (isNaN(contactNumber) || contactNumber.length < 10) {
        document.getElementById('contact_number_error').textContent = 'Please enter a valid contact number';
        isValid = false;
    }
    
    // Validate photo (optional validation)
    const photo = document.getElementById('photo').value;
    if (photo === '') {
        document.getElementById('photo_error').textContent = '2x2 Photo is required';
        isValid = false;
    } else {
        const validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        const fileExtension = photo.split('.').pop().toLowerCase();
        if (!validExtensions.includes(fileExtension)) {
            document.getElementById('photo_error').textContent = 'Only JPG, JPEG, PNG & GIF files are allowed';
            isValid = false;
        }
    }
    // Validate password
    const password = document.getElementById('password').value;
    if (password === '') {
        document.getElementById('password_error').textContent = 'Password is required';
        isValid = false;
    } else if (password.length < 6) {
        document.getElementById('password_error').textContent = 'Password must be at least 6 characters';
        isValid = false;
    }
    
        // Validate confirm password
        const confirmPassword = document.getElementById('confirm_password').value;
        if (confirmPassword === '') {
            document.getElementById('confirm_password_error').textContent = 'Please confirm your password';
            isValid = false;
        } else if (password !== confirmPassword) {
            document.getElementById('confirm_password_error').textContent = 'Passwords do not match';
            isValid = false;
        }
    
        return isValid; // Ensure the function returns the validation result
    }
    
    document.getElementById("myForm").addEventListener("submit", function() {
        let submitBtn = document.getElementById("submitBtn");
        submitBtn.innerHTML = 'Submitting...';
        submitBtn.disabled = true; // Prevents multiple clicks
    });
    

    </script>

</body>

</html>
