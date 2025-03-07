<?php
session_start();

// Check for error message from the previous submission
if (isset($_SESSION['error_message'])) {
  echo '<div class="alert alert-danger" role="alert">';
  echo $_SESSION['error_message'];
  echo '</div>';
  // Clear the error message from the session
  unset($_SESSION['error_message']);
}
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
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
      max-height: 1000px; /* Adjust as needed */
      overflow: visible;
  }

  .card-body {
      padding: 15px;
      background-color: #ffffff;
  }

  /* Optional: Add a subtle indicator */
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
          <li><a href="index.php#hero" >Home<br></a></li>
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

      </div><!-- End Section Title -->

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
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="contact_number">Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number" required>
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
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="confirm_password">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="new_student_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="confirm_password">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
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

                    <!-- Modal remains the same as before -->
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
                    
                    // Show the main student details section
                    studentDetails.style.display = 'block';
                    
                    if (newStudent) {
                        newStudentFields.style.display = 'block';
                        oldStudentFields.style.display = 'none';
                        document.getElementById('username').required = false;
                    } else if (oldStudent) {
                        newStudentFields.style.display = 'none';
                        oldStudentFields.style.display = 'block';
                        document.getElementById('username').required = true;
                    }
                }

                function validateForm() {
                    var newStudent = document.getElementById('new_student').checked;
                    var oldStudent = document.getElementById('old_student').checked;
                    
                    if (newStudent || oldStudent) {
                        var password = document.getElementById('password').value;
                        var confirmPassword = document.getElementById('confirm_password').value;
                        if (password !== confirmPassword) {
                            alert("Passwords do not match!");
                            return false;
                        }
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

   <!--Active Nav -->
  <script>
  document.addEventListener("DOMContentLoaded", function () {
      const navLinks = document.querySelectorAll("#navmenu ul li a");
      let path = window.location.pathname;

      if (path === "/onlineenrollment/" || path.endsWith("index.php")) {
          navLinks.forEach(link => link.classList.remove("active"));
          document.querySelector("a[href='#hero']").classList.add("active");
      }

      navLinks.forEach(link => {
          link.addEventListener("click", function () {
              navLinks.forEach(nav => nav.classList.remove("active"));
              this.classList.add("active");
          });
      });
  });

  document.addEventListener('DOMContentLoaded', function() {
    // Select all card headers
    const cardHeaders = document.querySelectorAll('.card-header .card-link');

    cardHeaders.forEach(header => {
        header.addEventListener('click', function(e) {
            e.preventDefault();

            // Get the target collapse div
            const targetId = this.getAttribute('href').replace('#', '');
            const targetCollapse = document.getElementById(targetId);

            // Close all other collapses first
            const allCollapses = document.querySelectorAll('.collapse');
            allCollapses.forEach(collapse => {
                if (collapse.id !== targetId) {
                    collapse.classList.remove('show');
                }
            });

            // Toggle the clicked collapse
            targetCollapse.classList.toggle('show');
        });
    });
});
  </script>

</body>

</html>