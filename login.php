<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = "enrollment"; // Replace with your database name

try {
  // Create connection
  $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
  // Set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  try {
    // Prepare SQL statement to retrieve user data
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $row = $stmt->fetch();

    // Check if user exists
    if ($row) {
      $hashed_password = $row["password"];

      // Verify password
      if (password_verify($password, $hashed_password)) {
        // Password is correct, start a new session
        session_start();
        $_SESSION["username"] = $row["username"];
        $_SESSION["role"] = $row["role"];
        // Redirect based on user role
        if ($row["role"] == "superadmin") {
          $_SESSION['superadmin_id'] = $row['id'];
          header("Location:superadmin/superadmin_dashboard.php");
          exit;
        } elseif ($row["role"] == "registrar") {
          $_SESSION['registrar_id'] = $row['id'];
          header("Location:registrar/registrar_dashboard.php");
          exit;
        } elseif ($row["role"] == "accounting") {
          $_SESSION['accounting_id'] = $row['id'];
          header("Location:accounting/accounting_dashboard.php");
          exit;
        } elseif ($row["role"] == "teacher") {
          $_SESSION['teacher_id'] = $row['id'];
          header("Location:teacher/teacher_dashboard.php");
          exit;
        } elseif ($row["role"] == "parent") {
          $_SESSION['parent_id'] = $row['id'];
          header("Location:parent/parent_dashboard.php");
          exit;
        } else {
          $_SESSION['student_id'] = $row['id'];
          header("Location:student/student_dashboard.php");
          exit;
        }
        exit();
      } else {
        $error_message = "Invalid Learner Reference Number (LRN) or password";
      }
    } else {
      $error_message = "Invalid Learner Reference Number (LRN) or password";
    }
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

$conn = null; // Close connection
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
    <title>Sign In</title>

		<!-- Site favicon -->
    <link href="asset/img/logo.png" rel="icon">
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="asset/img/logo.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="asset/img/logo.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>
    
		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="vendors/styles/icon-font.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script
			async
			src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"
		></script>
		<script
			async
			src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
			crossorigin="anonymous"
		></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag() {
				dataLayer.push(arguments);
			}
			gtag("js", new Date());

			gtag("config", "G-GBZ3SGGX85");
		</script>
	
	</head>
	<body class="login-page">
    <div class="login-wrap d-flex align-items-center justify-content-center min-vh-100">
      <div class="container">
          <div class="row justify-content-center align-items-center">
              <div class="col-md-6 col-lg-5">
                  <div class="login-box bg-white box-shadow border-radius-10 p-4">
                  <div class="d-flex justify-content-center align-items-center mb-4 ">
                      <a href="index.php">
                        <img src="assets/img/logo.png" alt="" height="100px" width="100px">
                      </a>
                    </div>
                      <div class="login-title">
                          <h2 class="text-center text-primary">Sign In</h2>
                      </div>
                      <?php

                    // Check for success message
                    if (isset($_SESSION['success_message'])) {
                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                        echo $_SESSION['success_message'];
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        echo '<span aria-hidden="true">&times;</span>';
                        echo '</button>';
                        echo '</div>';
                        // Clear the success message from the session
                        unset($_SESSION['success_message']);
                    }

                    // Also check for error messages (if you want to display them on login page)
                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                        echo $_SESSION['error_message'];
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        echo '<span aria-hidden="true">&times;</span>';
                        echo '</button>';
                        echo '</div>';
                        // Clear the error message from the session
                        unset($_SESSION['error_message']);
                    }
                    ?>

                      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <?php if (isset($error_message)) { ?>
                          <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php } ?>
                          <div class="form-group">
                              <label for="usernameOrLRN">Email or LRN</label>
                              <div class="input-group custom">
                                  <input type="text" id="usernameOrLRN" class="form-control form-control-lg" name="username"
                                      placeholder="Enter Email or LRN" required />
                                  <div class="input-group-append custom">
                                      <span class="input-group-text">
                                          <i class="icon-copy dw dw-user1"></i>
                                      </span>
                                  </div>
                              </div>
                          </div>

                          <div class="form-group">
                              <label for="password">Password</label>
                              <div class="input-group custom">
                                  <input type="password" id="password" class="form-control form-control-lg" name="password"
                                      placeholder="Enter Password" required />
                                  <div class="input-group-append custom">
                                      <span class="input-group-text">
                                          <i class="dw dw-padlock1"></i>
                                      </span>
                                  </div>
                              </div>
                          </div>

                          <div class="row">
                              <div class="col-sm-12">
                                  <div class="input-group mb-3">
                                      <button class="btn btn-primary btn-lg btn-block" type="submit">Sign In</button>
                                  </div>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
		<!-- welcome modal start -->
	
		<!-- welcome modal end -->
		<!-- js -->
		<script src="vendors/scripts/core.js"></script>
		<script src="vendors/scripts/script.min.js"></script>
		<script src="vendors/scripts/process.js"></script>
		<script src="vendors/scripts/layout-settings.js"></script>
		<!-- Google Tag Manager (noscript) -->
		<noscript
			><iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe
		></noscript>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
