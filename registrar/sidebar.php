<!-- 
<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

  <li class="nav-item">
    <a class="nav-link collapsed" href="registrar_dashboard.php">
      <i class="bi bi-grid-fill"></i>
      <span>Dashboard</span>
    </a>
  </li>

  <li class="nav-item">
  <a class="nav-link collapsed" href="enrollment.php">
  <i class="bi bi-person-vcard-fill"></i>
      <span>Enrollment</span>
    </a>
  </li>

  <li class="nav-item">
  <a class="nav-link collapsed" href="students.php">
  <i class="bi bi-people-fill"></i>
      <span>Students</span>
    </a>
  </li>

  <li class="nav-item">
  <a class="nav-link collapsed" href="schedule.php">
      <i class="bi bi-calendar2-week-fill"></i>
      <span>Schedule</span>
    </a>
  </li>

  <li class="nav-item">
  <a class="nav-link collapsed" href="verify_grade.php">
  <i class="bi bi-people-fill"></i>
      <span>Verify Grade</span>
    </a>
  </li>

  <li class="nav-item">
  <a class="nav-link collapsed" href="subject.php">
  <i class="bi bi-book-fill"></i>
      <span>Subject</span>
    </a>
  </li>

  <li class="nav-item">
  <a class="nav-link collapsed" href="gradelevel.php">
      <i class="bi bi-mortarboard-fill"></i>
      <span>Grade Level</span>
    </a>
  </li>

  <li class="nav-item">
  <a class="nav-link collapsed" href="section.php">
  <i class="bi bi-list"></i>
      <span>Section</span>
    </a>
  </li>

  <li class="nav-item">
  <a class="nav-link collapsed" href="room.php">
  <i class="bi bi-door-closed-fill"></i>
      <span>Room</span>
    </a>
  </li>

  <li class="nav-item">
  <a class="nav-link collapsed" href="enrollmentschedule.php">
  <i class="bi bi-calendar-range-fill"></i>
      <span>Schedule of Enrollment</span>
    </a>
  </li>

 

</ul>

</aside> -->

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
                    <a href="registrar_dashboard.php" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-speedometer2"></span> 
                        <span class="mtext">Dashboard</span>
                    </a>
                </li>
                <li>
                  <a href="enrollmentschedule.php" class="dropdown-toggle no-arrow">
                      <span class="micon bi bi-calendar-check"></span> 
                      <span class="mtext">Enrollment</span>
                  </a>
              </li>
                <!-- <li>
                  <a href="enrollment.php" class="dropdown-toggle no-arrow">
                      <span class="micon bi bi-card-list"></span> 
                      <span class="mtext">Enrollment</span>
                  </a>
              </li> -->
              <?php
                        $current_page = basename($_SERVER['PHP_SELF']);
                        $is_students_page = strpos($current_page, 'students') !== false;
                        $is_view_student_page = strpos($current_page, 'view_record.php') !== false;
                        $is_add_room_page = strpos($current_page, 'add_room') !== false;
                    ?>
            
              <li>
                  <a href="students.php" class="dropdown-toggle no-arrow <?=  $is_view_student_page ? 'active' : ''; ?>">
                      <span class="micon bi bi-people"></span> 
                      <span class="mtext">Students</span>
                  </a>
              </li>
              <li>
                  <a href="subject.php" class="dropdown-toggle no-arrow">
                      <span class="micon bi bi-book"></span> 
                      <span class="mtext">Subject</span>
                  </a>
              </li>
              <li>
                  <a href="schedule.php" class="dropdown-toggle no-arrow">
                      <span class="micon bi bi-calendar-week"></span> 
                      <span class="mtext">Schedule</span>
                  </a>
              </li>
              <li>
                  <a href="gradelevel.php" class="dropdown-toggle no-arrow">
                      <span class="micon bi bi-list-ol"></span> 
                      <span class="mtext">Grade Level</span>
                  </a>
              </li>
              <li>
                  <a href="section.php" class="dropdown-toggle no-arrow">
                      <span class="micon bi bi-grid"></span> 
                      <span class="mtext">Section</span>
                  </a>
              </li>
              <li>
                  <a href="room.php" class="dropdown-toggle no-arrow <?=  $is_add_room_page ? 'active' : ''; ?>">
                      <span class="micon bi bi-door-open"></span> 
                      <span class="mtext">Room</span>
                  </a>
              </li>
              <li>
                  <a href="verify_grade.php" class="dropdown-toggle no-arrow">
                      <span class="micon bi bi-check2-circle"></span> 
                      <span class="mtext">Verify Grade</span>
                  </a>
              </li>
              <li>
                    <a href="form137.php" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-file-earmark-text"></span>
                        <span class="mtext">Form 137</span>
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
