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
  <div class="top_container sub_pages"></div>
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
                        <label for="usernameNew">Learner Reference Number (LRN)</label>
                        <input type="text" class="form-control" id="usernameNew" name="usernameNew">
                      </div>
                      <div class="form-group">
                        <label for="passwordNew">Password</label>
                        <input type="password" class="form-control" id="passwordNew" name="passwordNew">
                      </div>
                      <div class="form-group">
                        <label for="confirm_passwordNew">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_passwordNew" name="confirm_passwordNew">
                      </div>
                    </div>
                    <div id="old_student_fields" style="display: none;">
                      <div class="form-group">
                        <label for="usernameOld">Learner Reference Number (LRN)</label>
                        <input type="text" class="form-control" id="usernameOld" name="usernameOld">
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
          <h4 class="text-white fw-bold">Contact Us</h4>
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

  <script>
    function toggleStudentType() {
      var newStudent = document.getElementById('new_student').checked;
      var oldStudent = document.getElementById('old_student').checked;
      var newStudentFields = document.getElementById('new_student_fields');
      var oldStudentFields = document.getElementById('old_student_fields');

      if (newStudent) {
        newStudentFields.style.display = 'block';
        oldStudentFields.style.display = 'none';

        // Enable and require fields for new students
        document.getElementById('usernameNew').required = true;
        document.getElementById('passwordNew').required = true;
        document.getElementById('confirm_passwordNew').required = true;

        document.getElementById('passwordNew').disabled = false;
        document.getElementById('confirm_passwordNew').disabled = false;

      } else if (oldStudent) {
        newStudentFields.style.display = 'none';
        oldStudentFields.style.display = 'block';

        // Disable and remove required attribute for new student fields
        document.getElementById('usernameNew').required = false;
        document.getElementById('passwordNew').required = false;
        document.getElementById('confirm_passwordNew').required = false;

        document.getElementById('passwordNew').disabled = true;
        document.getElementById('confirm_passwordNew').disabled = true;
      }
    }

    function validateForm() {
      var newStudent = document.getElementById('new_student').checked;

      if (newStudent) {
        var password = document.getElementById('passwordNew').value;
        var confirmPassword = document.getElementById('confirm_passwordNew').value;

        if (password !== confirmPassword) {
          alert('Passwords do not match!');
          return false;
        }
      }

      return true;
    }
  </script>

  <!-- google map js -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA8eaHt9Dh5H57Zh0xVTqxVdBFCvFMqFjQ&callback=initMap">
  </script>
  <!-- end google map js -->
</body>

</html>