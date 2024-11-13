<?php

include 'config.php';

session_start();
error_reporting(0);
$student_id = $_SESSION['student_id'];

if(!isset($student_id)){
   header('location:login.php');
}
else{
    if(isset($_POST['add_user'])) {
        // You should add your database connection logic here
        // Assuming $conn is your database connection object

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
        $query->bindParam(':password', $hashed_password, PDO::PARAM_STR); // Use hashed password
        $query->bindParam(':role', $role, PDO::PARAM_STR);
        
        if($query->execute()) {
            $msg = "User Added Successfully";
        } else {
            $error = "Something went wrong. Please try again";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <?php include 'asset.php';?>

</head>

<body>

<?php 
    include 'header.php';
    include 'sidebar.php';
?>

<main id="main" class="main">

<div class="card">
            <div class="card-body">
              <h5 class="card-title"></h5>

              <form class="row g-3"  method="post" name="add_user" onSubmit="return valid();">
              <?php if($error){?>
                                <div class="alert alert-danger" role="alert">
                                    <strong>ERROR</strong>: <?php echo htmlentities($error); ?>
                                </div>
                                <?php } else if($msg){?>
                                <div class="alert alert-success" role="alert">
                                    <strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?>
                                </div>
                                <?php }?>
                <div class="col-md-6">
                  <input type="text" class="form-control" placeholder="First Name" name="first_name" required>
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control" placeholder="Last Name" name="last_name" required>
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control" placeholder="Contact Number" name="contact_number" required>
                </div>
                <div class="col-md-6">
                  <input type="email" class="form-control" placeholder="Email" name="email" required>
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control" placeholder="Username" name="username" required>
                </div>
                <div class="col-md-6">
                  <input type="password" class="form-control" placeholder="Password" name="password" required>
                </div>
                <div class="col-md-4">
                  <select id="inputState" class="form-select" name="role" required>
                    <option selected>Choose role</option>
                    <option value="superadmin">SuperAdmin</option>
                    <option value="registrar">Registrar</option>
                    <option value="accounting">Accounting</option>
                    <option value="teacher">Teacher</option>
                    <option value="user">Student/Parent</option>
                  </select>
                </div>
                
                <div class="text-center">
                  <button type="submit" class="btn btn-primary" name="add_user">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End No Labels Form -->

            </div>
          </div>
</main><!-- End #main -->


  <?php
    include 'footer.php';
    include 'script.php';
  ?>

</body>

</html>
<?php } ?>