<?php

include 'config.php';

session_start();
error_reporting(0);
$parent_id = $_SESSION['parent_id'];

if(!isset($parent_id)){
   header('location:login.php');
}
else{
    if(isset($_POST['edit_user'])) {
        // You should add your database connection logic here
        // Assuming $conn is your database connection object

        $id = $_POST['id']; // Get user ID from the form
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $contact_number = $_POST['contact_number'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $role = $_POST['role'];

        // Check if the password field is not empty
        if(!empty($_POST['password'])) {
            $password = $_POST['password'];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
            $sql = "UPDATE users 
                    SET first_name = :first_name, last_name = :last_name, contact_number = :contact_number, 
                        email = :email, username = :username, password = :password, role = :role 
                    WHERE id = :id";
        } else {
            // If password field is empty, do not update password
            $sql = "UPDATE users 
                    SET first_name = :first_name, last_name = :last_name, contact_number = :contact_number, 
                        email = :email, username = :username, role = :role 
                    WHERE id = :id";
        }

        $query = $conn->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $query->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $query->bindParam(':contact_number', $contact_number, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        if(!empty($hashed_password)) {
            $query->bindParam(':password', $hashed_password, PDO::PARAM_STR); // Use hashed password if provided
        }
        $query->bindParam(':role', $role, PDO::PARAM_STR);
        
        if($query->execute()) {
            $msg = "User Updated Successfully";
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

    // Fetch user details for editing
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM users WHERE id = :id";
        $query = $conn->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
    }
?>

<main id="main" class="main">

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Edit User</h5>

        <form class="row g-3"  method="post" name="edit_user" onSubmit="return valid();">
            <?php if(isset($error)){?>
                <div class="alert alert-danger" role="alert">
                    <strong>ERROR</strong>: <?php echo htmlentities($error); ?>
                </div>
            <?php } else if(isset($msg)){?>
                <div class="alert alert-success" role="alert">
                    <strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?>
                </div>
            <?php }?>
            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="First Name" name="first_name" value="<?php echo $user['first_name']; ?>">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Last Name" name="last_name" value="<?php echo $user['last_name']; ?>">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Contact Number" name="contact_number" value="<?php echo $user['contact_number']; ?>">
            </div>
            <div class="col-md-6">
                <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo $user['email']; ?>">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Username" name="username" value="<?php echo $user['username']; ?>">
            </div>
            <div class="col-md-6">
                <input type="password" class="form-control" placeholder="New Password" name="password">
            </div>
            <div class="col-md-4">
                <select id="inputState" class="form-select" name="role">
                    <option selected>Choose role</option>
                    <option value="superadmin" <?php if($user['role'] == 'superadmin') echo 'selected'; ?>>SuperAdmin</option>
                    <option value="registrar" <?php if($user['role'] == 'registrar') echo 'selected'; ?>>Registrar</option>
                    <option value="accounting" <?php if($user['role'] == 'accounting') echo 'selected'; ?>>Accounting</option>
                    <option value="teacher" <?php if($user['role'] == 'teacher') echo 'selected'; ?>>Teacher</option>
                    <option value="user" <?php if($user['role'] == 'user') echo 'selected'; ?>>Student/Parent</option>
                </select>
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary" name="edit_user">Update</button>
                <a href="user.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

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
