<div class="right-sidebar">
    <div class="sidebar-title">
    </div>
</div>

<div class="left-side-bar">
    <div class="brand-logo">
        <a href="student_dashboard.php" class="d-flex align-items-center text-decoration-none">
            <img src="../asset/img/logo.png" alt="" class="dark-logo" style="width: 60px; height: auto; margin-right: 10px;" />
            <span class="font-weight-bold text-dark" style="font-size: 20px;">EAATI</span>
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <li>
                    <div class="sidebar-small-cap">Menu</div>
                </li>
                <li>
                    <a href="teacher_dashboard.php" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-speedometer2"></span> <!-- Dashboard Icon -->
                        <span class="mtext">Dashboard</span>
                    </a>
                </li>
                <?php
                        $current_page = basename($_SERVER['PHP_SELF']);
                        $is_encoded_grade_page = strpos($current_page, 'encode_studentgrade') !== false;
                    ?>
                  
                <!-- <li>
                    <a href="schedule.php" class="dropdown-toggle no-arrow <?= $is_approval_page ? 'active' : ''; ?>">
                        <span class="micon bi bi-calendar-event"></span> 
                        <span class="mtext">Schedule</span>
                    </a>
                </li> -->
                <li>
                    <a href="students.php" class="dropdown-toggle no-arrow <?= $is_approval_page ? 'active' : ''; ?>">
                        <span class="micon bi bi-person-bounding-box"></span> <!-- Students Icon -->
                        <span class="mtext">Students</span>
                    </a>
                </li>
                <li>
                    <a href="encodegrade.php" class="dropdown-toggle no-arrow <?= $is_encoded_grade_page ? 'active' : ''; ?>">
                        <span class="micon bi bi-pencil-square"></span> <!-- Encode Grade Icon -->
                        <span class="mtext">Encode Grades</span>
                    </a>
                </li>
                <li>
                    <a href="form138.php" class="dropdown-toggle no-arrow <?= $is_approval_page ? 'active' : ''; ?>">
                        <span class="micon bi bi-file-earmark-text"></span> <!-- Form 138 Icon -->
                        <span class="mtext">Form 138</span>
                    </a>
                </li>
                <li>
                    <a href="form137.php" class="dropdown-toggle no-arrow <?= $is_approval_page ? 'active' : ''; ?>">
                        <span class="micon bi bi-file-earmark-text"></span> <!-- Form 137 Icon -->
                        <span class="mtext">Form 137</span>
                    </a>
                </li>
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <a href="logout.php" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-box-arrow-right"></span> <!-- Sign Out Icon -->
                        <span class="mtext">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
