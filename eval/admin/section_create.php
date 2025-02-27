<?php
include('../database/dbconnect.php');
// include('../admin/aside.php');
session_start();
// Handle adding, editing, or deleting sections based on POST data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $action = $_POST['action'];
  $section_name = $_POST['section_name'] ?? '';
  $year_level = $_POST['year_level'] ?? '';
  $section_id = $_POST['section_id'] ?? '';

  if ($action == 'add') {
    $stmt = $conn->prepare("INSERT INTO tblsection (section_name, year_level) VALUES (?, ?)");
    $stmt->bind_param("si", $section_name, $year_level);
  } elseif ($action == 'edit') {
    $stmt = $conn->prepare("UPDATE tblsection SET section_name = ?, year_level = ? WHERE section_id = ?");
    $stmt->bind_param("sii", $section_name, $year_level, $section_id);
  } elseif ($action == 'delete') {
    $stmt = $conn->prepare("DELETE FROM tblsection WHERE section_id = ?");
    $stmt->bind_param("i", $section_id);
  }

  $stmt->execute();
  $stmt->close();
  header("Location: section_create.php"); // Redirect to avoid resubmission
  exit();
}

// Fetch all sections
$sections = $conn->query("SELECT * FROM tblsection");

// Set default form action to add
$form_action = 'add';
$section_id = $section_name = $year_level = '';

// If editing, prefill form fields
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
  $form_action = 'edit';
  $section_id = $_GET['section_id'];
  $section_name = $_GET['section_name'];
  $year_level = $_GET['year_level'];
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Section</title>
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
  <div class="fixed bottom-0 left-0 right-0 top-0 z-10 mx-[260px] w-[1200px] p-10 border">
  <h1>Manage Sections</h1>

<!-- Single form for adding, editing, and deleting -->
<form method="post" action="">
  <input type="hidden" name="action" value="<?php echo $form_action; ?>">
  <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
  <label>Section Name:</label><input type="text" name="section_name" value="<?php echo $section_name; ?>" required><br>
  <label>Year Level:</label><input type="number" name="year_level" value="<?php echo $year_level; ?>" required><br>
  <input type="submit" value="<?php echo $form_action == 'add' ? 'Add Section' : 'Update Section'; ?>">
</form>

<h3>Existing Sections</h3>
<table border="1">
  <tr>
    <th>Section ID</th>
    <th>Section Name</th>
    <th>Year Level</th>
    <th>Actions</th>
  </tr>
  <?php while ($row = $sections->fetch_assoc()) { ?>
    <tr>
      <td><?php echo $row['section_id']; ?></td>
      <td><?php echo $row['section_name']; ?></td>
      <td><?php echo $row['year_level']; ?></td>
      <td>
        <!-- Update link -->
        <button>
          <a href="?action=edit&section_id=<?php echo $row['section_id']; ?>&section_name=<?php echo $row['section_name']; ?>&year_level=<?php echo $row['year_level']; ?>">
            Update
          </a>
        </button>

        <!-- Delete link triggers the form submission with delete action -->
        <button>
          <a href="#" onclick="deleteSection(<?php echo $row['section_id']; ?>)">Delete</a>
        </button>

      </td>
    </tr>
  <?php } ?>
</table>
  </div>


  <script>
    function deleteSection(sectionId) {
      // Confirm deletion
      if (confirm("Are you sure you want to delete this section?")) {
        // Set form action to delete and submit the form
        const form = document.querySelector("form");
        form.action.value = "delete";
        form.section_id.value = sectionId;
        form.submit();
      }
    }
  </script>
</body>

</html>