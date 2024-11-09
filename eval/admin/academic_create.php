<?php
include("../database/dbconnect.php");
// include('../admin/aside.php');
session_start();

// Define variables
$edit_id = null;
$edit_year = '';
$edit_semester = '';

// Handle form submission for adding or updating
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
  $year = $_POST['year'];
  $semester = $_POST['semester'];
  $years = (strpos($year, '-') === false) ? $year . ' - ' . ($year + 1) : $year;

  if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
    // Update existing school year
    $edit_id = $_POST['edit_id'];
    $stmt = $conn->prepare("UPDATE tblschoolyear SET school_year = ?, semester = ? WHERE schoolyear_id = ?");
    $stmt->bind_param("ssi", $years, $semester, $edit_id);
    if ($stmt->execute()) {
      echo "<script>alert('School year updated successfully!'); window.location.href='academic_create.php';</script>";
    } else {
      echo "<script>alert('Error updating school year.');</script>";
    }
    $stmt->close();
  } else {
    // Insert new school year
    $sc = $conn->prepare("SELECT COUNT(*) AS COUNT FROM tblschoolyear WHERE school_year = ?");
    $sc->bind_param('s', $years);
    $sc->execute();
    $rs = $sc->get_result();
    $r = $rs->fetch_assoc();
    $cs = $r['COUNT'];

    if ($cs < 2) {
      $stmt = $conn->prepare("INSERT INTO tblschoolyear (school_year, semester, is_status) VALUES (?, ?, 'Not Yet Started')");
      $stmt->bind_param("ss", $years, $semester);
      if ($stmt->execute()) {
        echo "<script>window.location.href='academic_create.php'; alert('School year saved successfully!'); </script>";
      } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
      }
      $stmt->close();
    } else {
      echo "<script>alert('The School year \"$years\" has already been added twice.');</script>";
    }
    $sc->close();
  }
}

// Handle delete operation
if (isset($_GET['delete_id'])) {
  $delete_id = $_GET['delete_id'];
  $stmt = $conn->prepare("DELETE FROM tblschoolyear WHERE schoolyear_id = ?");
  $stmt->bind_param("i", $delete_id);
  if ($stmt->execute()) {
    echo "<script>window.location.href='academic_create.php'; alert('School year deleted successfully!'); </script>";
  } else {
    echo "<script>alert('Error deleting school year.');</script>";
  }
  $stmt->close();
}

// Fetch record for editing
if (isset($_GET['editid'])) {
  $edit_id = $_GET['editid'];
  $result = $conn->query("SELECT * FROM tblschoolyear WHERE schoolyear_id = $edit_id");
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $edit_year = $row['school_year'];
    $edit_semester = $row['semester'];
  }
}

// Fetch existing school years
$school_years = $conn->query("SELECT * FROM tblschoolyear");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage School Year</title>
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
  <div class="container">

    <main>
      <div class="fixed bottom-0 left-0 right-0 top-0 z-10 mx-[260px] w-[1200px] p-10 border">
        <h1 class="text-5xl">Manage School Year</h1>
        <form action="" method="POST">
          <div class="mt-4 border-b-4 border-black p-2">
            <div>
              <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
              <label for="year" class="text-2xl">Academic Year :</label>
              <input 
                type="text" 
                id="year"
                name="year" 
                required 
                placeholder="Enter Academic Year (e.g., 2024 - 2025)"
                value="<?php echo $edit_year; ?>"
                class="px-2 py-1 border text-lg mx-2" ><br>
            </div>
            <div class="mx-9">
              <label for="semester" class="text-2xl mx-5">Semester :</label>
              <select 
                id="semester" 
                name="semester" 
                required
                class="px-2 py-1 border text-lg w-[220px] mx-[-12px] mt-1">
                <option value="1" <?php echo ($edit_semester == '1') ? 'selected' : ''; ?>>First Semester</option>
                <option value="2" <?php echo ($edit_semester == '2') ? 'selected' : ''; ?>>Second Semester</option>
              </select>
            </div>
            <div class="mx-44 mt-2 bg-blue-900 w-[219px] py-1 text-center text-white">
              <button type="submit" name="save"><?php echo $edit_id ? 'Update' : 'Submit'; ?></button>  
            </div>
            
          </div>
        

         

         
        </form>

        <h2>Existing School Years</h2>
        <table>
          <tr>
            <th>ID</th>
            <th>Academic Year</th>
            <th>Semester</th>
            <th>Default</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
          <tbody>
            <?php
            $i = 1;
            while ($row = $school_years->fetch_assoc()):
              $active = $row['is_default'];
            ?>
              <tr>
                <th class="text-center"><?php echo $i++ ?></th>
                <td><b><?php echo $row['school_year']; ?></b></td>
                <td><b><?php echo $row['semester']; ?></b></td>
                <td>
                  <button onclick="toggleActive(<?php echo $row['schoolyear_id']; ?>, '<?php echo $active; ?>')">
                    <?php echo $active === 'Yes' ? 'Yes' : 'No'; ?>
                  </button>
                </td>
                <td>
                  <select id="status-<?php echo $row['schoolyear_id']; ?>" onchange="updateStatus(<?php echo $row['schoolyear_id']; ?>, this.value)" <?php echo $active === 'Yes' ? '' : 'disabled'; ?>>
                    <option value="Not Yet Started" <?php echo $row['is_status'] === 'Not Yet Started' ? 'selected' : ''; ?>>Not Yet Started</option>
                    <option value="Started" <?php echo $row['is_status'] === 'Started' ? 'selected' : ''; ?>>Started</option>
                    <option value="Closed" <?php echo $row['is_status'] === 'Closed' ? 'selected' : ''; ?>>Closed</option>
                  </select>
                </td>
                <td class="text-center">
                  <div class="btn-group">
                    <button class="btn1"><a class="btn3" href="?editid=<?php echo $row['schoolyear_id']; ?>">Edit</a></button>
                    <a class="btn1" href="?delete_id=<?php echo $row['schoolyear_id']; ?>" onclick="return confirm('Are you sure you want to delete this school year?')">Delete</a>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    </main>
  </div>


  <script>
    function toggleActive(id, currentStatus) {
      const newStatus = currentStatus === 'Yes' ? 'No' : 'Yes';
      const statusDropdown = document.getElementById('status-' + id);

      // Create the AJAX request
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'academic.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      xhr.onload = function() {
        if (this.status === 200) {
          // Refresh to reflect the changes after the request completes successfully
          location.reload();
        } else {
          console.error('Error toggling active status:', this.responseText);
        }
      };

      if (newStatus === 'Yes') {
        // Set this school year as active, reset others to inactive, and enable the dropdown
        xhr.send(`schoolyear_id=${id}&status=${newStatus}&set_single_active=1`);
        statusDropdown.disabled = false;
      } else {
        // Set this school year to inactive, reset status to 'Not Yet Started', and disable the dropdown
        xhr.send(`schoolyear_id=${id}&status=${newStatus}&reset_status=1`);
        statusDropdown.value = 'Not Yet Started';
        statusDropdown.disabled = true;
      }
    }

    function updateStatus(id, status) {
      // Create the AJAX request for updating only the status
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'academicstatus.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      xhr.onload = function() {
        if (this.status === 200) {
          console.log('Status updated successfully.');
          location.reload();
        } else {
          console.error('Error updating status:', this.responseText);
        }
      };

      xhr.send(`schoolyear_id=${id}&status=${status}`);
    }
  </script>


</body>

</html>