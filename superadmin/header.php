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
            $sql = "SELECT first_name, last_name 
                    FROM users 
                    WHERE role = 'superadmin'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $userImage = '../asset/img/user.png';
            $firstName = isset($user['first_name']) ? ucwords(strtolower($user['first_name'])) : '';
            $lastName = isset($user['last_name']) ? ucwords(strtolower($user['last_name'])) : '';
            $userName = !empty($firstName) && !empty($lastName) ? "$firstName $lastName" : 'Guest User';
        } catch(PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            $userImage = './asset/img/user.png';
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