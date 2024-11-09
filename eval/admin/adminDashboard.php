<?php
include('../database/dbconnect.php');
session_start();

if (!isset($_SESSION['username']) && !isset($_SESSION['school_year']) && !isset($_SESSION['semester']) && !isset($_SESSION['is_status'])) {
  // Redirect to login page or display an error message
  header("Location: ../login.php");
  exit();
}

// Query to get the active academic year and semester if set to 'Started'
$schoolyear_query = $conn->query("SELECT school_year, semester, is_status FROM tblschoolyear WHERE is_status = 'Started'");
$schoolyear = $schoolyear_query->fetch_assoc();

// Set session variables only if there is an active academic year
if ($schoolyear) {
  $_SESSION['school_year'] = $schoolyear['school_year'];
  $_SESSION['semester'] = $schoolyear['semester'];
  $_SESSION['is_status'] = $schoolyear['is_status'];
} else {
  // Set default values if no academic year is active
  $_SESSION['school_year'] = "Not Set";
  $_SESSION['semester'] = "Not Yet Started";
  $_SESSION['is_status'] = "Inactive";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

  <aside>
    <div class="container">
      <div class="menu">

      </div>
      <hr>
      <div class="fixed bottom-0 left-0 top-0 z-50 w-[260px] border shadow">
        <div class=" text-2xl text-center hover:bg-blue-900 hover:text-white py-1 rounded-sm cursor-pointer">
          <a href="adminDashboard.php" class="cursor-pointer">Dashboard</a>
        </div>
        <div class="flex flex-col justify-evenly item-center text-center gap-2 mt-5">
          <div class="flex justify-center item-center gap-2 hover:bg-[#161D6F] py-2 hover:text-white cursor-pointer">
            <div class="h-10 w-10 border">
              <img src="#" alt="">
            </div>
            <div class="mt-2 text-lg">
              <a href="../admin/student_view.php">Manage Student</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2 hover:bg-[#161D6F] py-2 hover:text-white cursor-pointer">
            <div class="h-10 w-10 border">
              <img src="#" alt="">
            </div>
            <div class="mt-2 text-lg">
              <a href="../admin/teacher_view.php">Manage Teacher</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2 hover:bg-[#161D6F] py-2 hover:text-white cursor-pointer ms-[-25px]">
            <div class="h-10 w-10 border">
              <img src="#" alt="">
            </div>
            <div class="mt-2 text-lg">
              <a href="">Manage User</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2  hover:bg-[#161D6F] py-2 hover:text-white cursor-pointer ms-[18px]">
            <div class="h-10 w-10 border">
              <img src="#" alt="">
            </div>
            <div class="mt-2 text-lg">
              <a href="../admin/academic_create.php">Manage Academic</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2 hover:bg-[#161D6F] 
            py-2 hover:text-white cursor-pointer ms-[35px]">
            <div class="h-10 w-10 border">
              <img src="#" alt="">
            </div>
            <div class="mt-2 text-lg">
              <a href="../admin/department_create.php">Manage Department</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2 hover:bg-[#161D6F] py-2 hover:text-white cursor-pointer">
            <div class="h-10 w-10 border">
              <img src="#" alt="">
            </div>
            <div class="mt-2 text-lg">
              <a href="../admin/section_create.php">Manage Section</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2 hover:bg-[#161D6F] py-2 hover:text-white cursor-pointer">
            <div class="h-10 w-10 border">
              <img src="#" alt="">
            </div>
            <div class="mt-2 text-lg">
              <a href="../admin/criteria_create.php">Manage Criteria</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2 hover:bg-[#161D6F] py-2 hover:text-white cursor-pointer">
            <div class="h-10 w-10 border">
              <img src="#" alt="">
            </div>
            <div class="mt-2 text-lg">
              <a href="../admin/subject_create.php">Manage Subject</a>
            </div>
          </div>
          <div class="flex justify-center item-center gap-2 hover:bg-[#161D6F] py-2 hover:text-white cursor-pointer">
            <div class="h-10 w-10 border ms-[-63px]">
              <img src="#" alt="">
            </div>
            <div class="mt-2 text-lg mx-1">
              <a href="">Archive</a>
            </div>
          </div>
          <div class="flex justify-center 
            item-center gap-2 hover:bg-[#161D6F] 
              py-2 hover:text-white ms-[-73px]">
            <div class="h-10 w-10 border">
              <img src="#" alt="">
            </div>
            <div class="mt-2 text-lg">
              <a href="../logout.php">Logout</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </aside>
  <main>
    <div class="fixed bottom-0 left-0 right-0 top-0 z-10 mx-[260px] w-[1200px] p-10 border">
      <?php if (isset($_SESSION['username'])): ?>
        <div class="container mx-auto h-[700px] p-4">
          <div class="p-4  border-s-4 border-blue-700 border-b-2 rounded-md">
            <h2 class="text-4xl mb-4">Welcome, Admin: <?= htmlspecialchars($_SESSION['username']) ?></h2>

            <!-- Display academic year or default "Not Set" message -->
            <p class="text-[20px]">Academic Year:
              <?= htmlspecialchars($_SESSION['school_year'] === "Not Set" ? "Not Set" : $_SESSION['school_year']) ?>
            </p>

            <!-- Display semester information -->
            <p class="text-[20px]">Semester:
              <?= $_SESSION['semester'] == '1' ? 'First Semester' : ($_SESSION['semester'] == '2' ? 'Second Semester' : 'Not Yet Started') ?>
            </p>

            <!-- Display status -->
            <p class="text-[20px]">Status: <?= htmlspecialchars($_SESSION['is_status']) ?></p>
          <?php else: ?>
            <p>Academic year and semester not set. Please set the active semester.</p>
          <?php endif; ?>
          </div>

          <div class="flex justify-evenly items-center gap-3 mt-20">
            <div class="relative h-[150px] w-[350px] border-s-4 border-b-2 border-blue-900 rounded-md shadow-md shadow-blue-200">
              <div class="p-3">
                <h1 class="text-2xl">Teacher</h1>
              </div>
              <div class="absolute bottom-[30px] left-5 h-10 w-10 rounded-full bg-blue-900"></div>
              <div class="absolute bottom-[30px] left-10 text-4xl">
                <span class="badge bg-dark rounded-full fs-1" style="padding: 0.5em 1em;">
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM tblteacher";
                  $result = mysqli_query($conn, $sql);

                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>

                </span>
              </div>
            </div>
            <div class="relative h-[150px] w-[350px] border-s-4 border-b-2 border-red-900 rounded-md shadow-md shadow-red-200">
              <div class="p-3">
                <h1 class="text-4xl">Student</h1>
              </div>
              <div class="absolute bottom-[30px] left-5 h-10 w-10 rounded-full bg-red-900"></div>
              <div class="absolute bottom-[30px] left-20 text-4xl">
                <span class="badge bg-dark rounded-full fs-1" style="padding: 0.5em 1em;">
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM tblstudent";
                  $result = mysqli_query($conn, $sql);

                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>

                </span>
              </div>
            </div>
            <div class="relative h-[150px] w-[350px] border-s-4 border-b-2 border-green-900 rounded-md shadow-md shadow-green-200">
              <div class="p-3">
                <h1 class="text-2xl">Admin</h1>
              </div>
              <div class="absolute bottom-[30px] left-5 h-10 w-10 rounded-full bg-green-900"></div>
              <div class="absolute bottom-[30px] left-20 text-4xl">
                <!-- <span class="badge bg-dark rounded-full fs-1" style="padding: 0.5em 1em;">
                  <?php
                  $count = 0;
                  $sql = "SELECT COUNT(*) AS total FROM tbladmin";
                  $result = mysqli_query($conn, $sql);

                  if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $count = $row['total'];
                  }
                  echo $count;
                  ?>

                </span>-->
              </div>
            </div>
          </div>
        </div>

    </div>
  </main>
</body>

</html>