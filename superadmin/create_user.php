<?php

include 'config.php';

session_start();
error_reporting(0);
$superadmin_id = $_SESSION['superadmin_id'];

if(!isset($superadmin_id)){
   header('location:../login.php');
}
else{
    if(isset($_POST['add_user'])) {

        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $contact_number = $_POST['contact_number'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = $_POST['role'];

        $sql = "INSERT INTO users (first_name, last_name, contact_number, email, username, password, role) 
                VALUES (:first_name, :last_name, :contact_number, :email, :username, :password, :role)";
        $query = $conn->prepare($sql);
        $query->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $query->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $query->bindParam(':contact_number', $contact_number, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $hashed_password, PDO::PARAM_STR); 
        $query->bindParam(':role', $role, PDO::PARAM_STR);
        
        if($query->execute()) {
            $msg = "User Added Successfully!";
        } else {
            $error = "Something went wrong. Please try again";
        }
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Edit User</title>

        <?php 
            include 'link.php';    
        ?>
	
	</head>
	<body class="sidebar-light">

    <?php 
        include 'header.php';
        include 'sidebar.php';
    ?>

        <div class="mobile-menu-overlay"></div>
                <div class="main-container">
                    <div class="pd-ltr-20 xs-pd-20-10">
                        <div class="min-height-200px">
                            <div class="page-header">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="title">
                                            <h4>Add User</h4>
                                        </div>
                                        <nav aria-label="breadcrumb" role="navigation">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item">
                                                    <a href="superadmin_dashboard.php">Dashboard</a>
                                                </li>
                                                <li class="breadcrumb-item">
                                                    <a href="user.php">User</a>
                                                </li>
                                                <li class="breadcrumb-item active" aria-current="page">
                                                    Add User
                                                </li>
                                            </ol>
                                        </nav>
                                    </div>
                                </div>
                            </div>

                            <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                              
                            <form class="row g-3" method="post" name="add_user" onSubmit="return valid();">
                              <?php if($error){?>
                                  <div class="col-12">
                                      <div class="alert alert-danger" role="alert">
                                          <?php echo htmlentities($error); ?>
                                      </div>
                                  </div>
                              <?php } else if($msg){?>
                                  <div class="col-12">
                                      <div class="alert alert-success" role="alert">
                                          <?php echo htmlentities($msg); ?>
                                      </div>
                                  </div>
                              <?php }?>
                              
                              <div class="col-md-6 mb-3">
                                  <label for="first_name" class="form-label">First Name</label>
                                  <input type="text" class="form-control" placeholder="First Name" name="first_name" required>
                              </div>
                              <div class="col-md-6 mb-3">
                                  <label for="last_name" class="form-label">Last Name</label>
                                  <input type="text" class="form-control" placeholder="Last Name" name="last_name" required>
                              </div>
                              <div class="col-md-6 mb-3">
                                  <label for="contact_number" class="form-label">Contact Number</label>
                                  <input type="text" class="form-control" placeholder="Contact Number" name="contact_number" required>
                              </div>
                              <div class="col-md-6 mb-3">
                                  <label for="email" class="form-label">Email</label>
                                  <input type="email" class="form-control" placeholder="Email" name="email" required>
                              </div>
                              <div class="col-md-6 mb-3">
                                  <label for="username" class="form-label">Username</label>
                                  <input type="text" class="form-control" placeholder="Username" name="username" required>
                              </div>
                              <div class="col-md-6 mb-3">
                                  <label for="password" class="form-label">Password</label>
                                  <input type="password" class="form-control" placeholder="Password" name="password" required>
                              </div>
                              <div class="col-md-4 mb-3">
                                  <label for="role" class="form-label">Role</label>
                                  <select id="inputState" class="custom-select col-12" name="role" required>
                                      <option selected>Choose role</option>
                                      <option value="superadmin">SuperAdmin</option>
                                      <option value="registrar">Registrar</option>
                                      <option value="accounting">Accounting</option>
                                      <option value="teacher">Teacher</option>
                                  </select>
                              </div>
                              <div class="col-12 mt-2">
                                  <button type="submit" class="btn btn-primary" name="add_user">Submit</button>
                                  <button type="reset" class="btn btn-secondary">Reset</button>
                              </div>
                          </form>
                        </div>
                    </div>
                </div>
            </div>

        <?php
		    include 'footer.php';
        ?>
	</body>
</html>
<?php } ?>
