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
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>Eastern Achiever Academy of Taguig</title>



  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <!-- progress barstle -->
  <link rel="stylesheet" href="css/css-circular-prog-bar.css">
  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
  <!-- font wesome stylesheet -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />




  <link rel="stylesheet" href="css/css-circular-prog-bar.css">


</head>

<body>
  <div class="top_container sub_pages">
    <!-- header section strats -->
    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
        <a class="navbar-brand" href="index.html">
            <img src="images/logo.png" alt="" style="width: 100px; height: auto;">
            <span style="font-size: 2em;">E A A T I</span>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="d-flex ml-auto flex-column flex-lg-row align-items-center">
              <ul class="navbar-nav  ">
                <li class="nav-item active">
                  <a class="nav-link" href="index.html"> Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item ">
                  <a class="nav-link" href="about.html"> About </a>
                </li>

                <li class="nav-item ">
                  <a class="nav-link" href="admission.php"> Admission </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="studentlife.html"> Student Life </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="contact.html">Contact Us</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link btn btn-primary btn-sm" href="login.php">
                      Login
                  </a>
                </li>
              </ul>
            </div>
        </nav>
      </div>
    </header>
  </div>
  <!-- end header section -->


  <!-- teacher section -->
  <section class="teacher_section layout_padding-bottom">
    <div class="container">
      <h2 class="main-heading">Admission</h2>
      <div id="accordion">
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
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Are you a new or old student?</label>
                    <div>
                        <input type="radio" id="new_student" name="student_type" value="new" onclick="toggleStudentType()"> New
                        <input type="radio" id="old_student" name="student_type" value="old" onclick="toggleStudentType()"> Old
                    </div>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            <div class="col-md-6">
                <div id="new_student_fields" style="display: none;">
                    <div class="form-group">
                        <label for="username">Learner Reference Number (LRN)</label>
                        <input type="text" class="form-control" id="usernameNew" name="usernameNew">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div id="old_student_fields" style="display: none;">
                    <div class="form-group">
                        <label for="username">Learner Reference Number (LRN)</label>
                        <input type="text" class="form-control" id="username" name="username">
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <medium><b><i>Note: Please login to submit your requirements. <a href="#" id="viewRequirements">Click here</a> to view the requirements.</i></b></medium>

        <!-- Modal -->
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
                        <!-- Your requirements content goes here -->
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
    var newStudentFields = document.getElementById('new_student_fields');
    var oldStudentFields = document.getElementById('old_student_fields');
    if (newStudent) {
        newStudentFields.style.display = 'block';
        oldStudentFields.style.display = 'none';
        document.getElementById('usernameNew').required = true;
        document.getElementById('password').required = true;
        document.getElementById('confirm_password').required = true;
        validateLRN();
    } else if(oldStudent) {
        newStudentFields.style.display = 'none';
        oldStudentFields.style.display = 'block';
        document.getElementById('username').required = true;
        document.getElementById('password').required = false;
        document.getElementById('confirm_password').required = false;
    }
}

function validateForm() {
    var newStudent = document.getElementById('new_student').checked;
    if (newStudent) {
      document.getElementById('username').disabled = false;
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirm_password').value;        
        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            return false;
        }
    }
    return true;
}

function validateLRN() {
    var base = "488041";
    var year = new Date().getFullYear().toString().slice(-2);
    var randomFourDigits = Math.floor(1000 + Math.random() * 9000).toString();
    var lrn = base + year + randomFourDigits;
    document.getElementById('username').disabled = false;
    console.log("Generated LRN: " + lrn); // Debugging line
}

</script>

            </div>
          </div>
        </div>
        <div class="card">
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

  <!-- teacher section -->


  <!-- footer section -->
  <section class="container-fluid footer_section">
    <div class="row justify-content-center" style="margin: 20px;">
        <div class="col-md-6 text-center">
            <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15449.82659698259!2d121.0388348!3d14.5158526!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397cf34aadc2c4b%3A0xa6a167660ed24ec7!2sEastern%20Achiever%20Academy%20Of%20Taguig!5e0!3m2!1sen!2sph!4v1716883828672!5m2!1sen!2sph" 
                style="width: 100%; height: 100%; border: 0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-center text-center">
            <div>
                <h4>Contact Us</h4>
                <p>
                    <strong>Address:</strong> Carlos, P. Garcia Avenue, Taguig, 1632 Metro Manila<br>
                    <strong>Tel. No:</strong> (02) 3489 4692<br>
                    <strong>Email:</strong> <a href="mailto:info@eaati.edu.ph">info@eaati.edu.ph</a>
                </p>
            </div>
        </div>
    </div>
    <div class="text-center" style="margin: 20px;">
        <p>
            Copyright &copy; 2024 All Rights Reserved By
            <a href="https://web.facebook.com/eaati2005">Eastern Achiever Academy of Taguig Inc.</a>
        </p>
    </div>
</section>
  <!-- footer section -->

  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <!-- progreesbar script -->

  </script>
  <script>
    // This example adds a marker to indicate the position of Bondi Beach in Sydney,
    // Australia.
    function initMap() {
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 11,
        center: {
          lat: 40.645037,
          lng: -73.880224
        },
      });

      var image = 'images/maps-and-flags.png';
      var beachMarker = new google.maps.Marker({
        position: {
          lat: 40.645037,
          lng: -73.880224
        },
        map: map,
        icon: image
      });
    }
  </script>
  <!-- google map js -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA8eaHt9Dh5H57Zh0xVTqxVdBFCvFMqFjQ&callback=initMap">
  </script>
  <!-- end google map js -->
</body>

</html>