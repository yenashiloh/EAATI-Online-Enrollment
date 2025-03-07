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
                    <a href="superadmin_dashboard.php" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-speedometer2"></span>
                        <span class="mtext">Dashboard</span>
                    </a>
                    </li>
                   
                    <?php
                        $current_page = basename($_SERVER['PHP_SELF']);
                        $is_user_page = strpos($current_page, 'user') !== false;
                        $is_edit_user_page = strpos($current_page, 'edit_user') !== false;
                        $is_approval_page = strpos($current_page, 'approvalschedule') !== false;
                    ?>
                    <li>
                        <a href="user.php" class="dropdown-toggle no-arrow <?= $is_user_page ? 'active' : ''; ?>">
                            <span class="micon bi bi-person"></span>
                            <span class="mtext">User</span>
                        </a>
                    </li>
                    <li>
                        <a href="approvalschedule.php" class="dropdown-toggle no-arrow <?= $is_approval_page ? 'active' : ''; ?>">
                            <span class="micon bi bi-calendar-check"></span>
                            <span class="mtext">Schedule of Approval</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a href="logout.php" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-box-arrow-right"></span>
                            <span class="mtext">Sign Out</span>
                        </a>
                    </li>
					</ul>
				</div>
			</div>
		</div>