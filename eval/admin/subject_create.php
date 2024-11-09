<?php
include("../database/dbconnect.php");
include("./subject.php");
include('../admin/aside.php');
// session_start();

$selectedSubject = "";
$subjectId = "";

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // Check if the keys exist before accessing them
  $action = isset($_POST['action']) ? $_POST['action'] : '';
  $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
  $subjectId = isset($_POST['id']) ? $_POST['id'] : '';

  // Create action
  if ($action == "create" && !empty($subject)) {
    createsubject($subject);
    header('Location: subject_create.php'); // Redirect to the same page after creation
    exit();
  }

  // Update action
  if ($action == "update" && !empty($subjectId) && !empty($subject)) {
    updatesubject($subjectId, $subject);
    header('Location: subject_create.php'); // Redirect to the same page after updating
    exit();
  }

  // Delete action
  if ($action == "delete" && !empty($subjectId)) {
    deleteCriteria($subjectId);
    header('Location: subject_create.php'); // Redirect to the same page after deletion
    exit();
  }
}

// Get subject list for display
$subjectList = displaysubject();

// Check if subject is selected for editing
if (isset($_GET['edit'])) {
  $subjectId = $_GET['edit'];
  // Assuming displaysubject returns an array with id and name
  foreach ($subjectList as $subjectItem) {
    if ($subjectItem['subject_id'] == $subjectId) {
      $selectedSubject = $subjectItem['subject_name'];
      break;
    }
  }
}

// Handle delete action via GET request
if (isset($_GET['delete'])) {
  $subjectId = $_GET['delete'];
  deletesubject($subjectId);
  header('Location: subject_create.php'); // Redirect to the same page after deletion
  exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Display subject</title>

   <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
<div class="fixed bottom-0 left-0 right-0 top-0 z-10 mx-[260px] w-[1200px] p-10 border">
    <h1>Create subject</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($subjectId); ?>">
      <input type="hidden" name="action" value="<?php echo $subjectId ? 'update' : 'create'; ?>">
      <input type="text" name="subject" placeholder="Enter a subject" value="<?php echo htmlspecialchars($selectedSubject); ?>" required>
      <input type="submit" value="Submit" /> <!-- Combined submit button -->
    </form>

    <!-- Where the subject will be displayed -->

    <div id="subjectlist">
      <?php if (count($subjectList) > 0): ?>
        <div>
          <?php foreach ($subjectList as $index => $listsubject): ?>
            <div class="subject-item">
              <div><?php echo htmlspecialchars($listsubject['subject_name']); ?></div>
              <div class="three-dot-menu">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
              </div>
              <div class="dropdown">
                <!-- Using links to handle editing and deleting -->
                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?edit=<?php echo $listsubject['subject_id']; ?>">Edit</a>
                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?delete=<?php echo $listsubject['subject_id']; ?>" onclick="return confirm(`Are you sure you want to delete this subject ?`);">Delete</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="no-subject">No subject Available</div>
      <?php endif; ?>
    </div>

  </div>




  <!-- Input Form -->

</body>

</html>