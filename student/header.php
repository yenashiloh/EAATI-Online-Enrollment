<!-- ======= Header ======= -->
<!-- <header id="header" class="header fixed-top d-flex align-items-center">

<div class="d-flex align-items-center justify-content-between">
  <a href="user_dashboard.php" class="logo d-flex align-items-center">
  <span class="d-none d-lg-block">
      <img src="../images/logo1.png" alt="Logo">
    </span>
  </a>
  <i class="bi bi-list toggle-sidebar-btn"></i>
</div>

<nav class="header-nav ms-auto">
  <ul class="d-flex align-items-center">

    <li class="nav-item dropdown pe-3">

      <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
        <img src="../assets/img/profile.png" alt="Profile" class="rounded-circle">
        <span class="d-none d-md-block dropdown-toggle ps-2"></span>
      </a>

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li>
          <a class="dropdown-item d-flex align-items-center" href="logout.php">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sign Out</span>
          </a>
        </li>

      </ul>
    </li>

  </ul>
</nav>

</header> -->

<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list"></div>
        <div class="header-search">
        </div>
    </div>
    <div class="header-right">
        <div class="user-info-dropdown">
        <?php
        try {
            $sql = "SELECT u.first_name, u.last_name, s.image_path 
                    FROM users u 
                    LEFT JOIN student s ON u.id = s.userId 
                    WHERE u.id = :student_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Ensure the image path includes "../"
            $defaultImage = '../asset/img/user.png';
            $userImage = !empty($user['image_path']) ? '../' . $user['image_path'] : $defaultImage;

            $firstName = isset($user['first_name']) ? ucwords(strtolower($user['first_name'])) : '';
            $lastName = isset($user['last_name']) ? ucwords(strtolower($user['last_name'])) : '';
            $userName = !empty($firstName) && !empty($lastName) ? "$firstName $lastName" : 'Guest User';
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            $userImage = '../asset/img/user.png';
            $userName = 'Guest User';
        }
        ?>
        <div class="dropdown">
            <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                <span class="user-icon">
                    <img src="<?php echo $userImage; ?>" alt="User Image" class="rounded-circle" width="60" height="60"/>
                </span>
                <span class="user-name"><?php echo $userName; ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                <a class="dropdown-item" href="logout.php">
                    <i class="dw dw-logout"></i> Sign Out
                </a>
            </div>
        </div>
        </div>
    </div>
</div>
