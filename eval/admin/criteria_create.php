<?php
include("../database/dbconnect.php");
include("./criteria.php");
include('../admin/aside.php');
// session_start();

$selectedCriteria = "";
$criteriaId = "";

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $action = $_POST['action'];
  $criteria = $_POST['criteria'];
  $criteriaId = $_POST['id'];

  // Create action
  if ($action == "create" && !empty($criteria)) {
    createCriteria($criteria);
    header('Location: criteria_create.php'); // Redirect to the same page after creation
    exit();
  }

  // Update action
  if ($action == "update" && !empty($criteriaId) && !empty($criteria)) {
    updateCriteria($criteriaId, $criteria);
    header('Location: criteria_create.php'); // Redirect to the same page after updating
    exit();
  }

  // Delete action
  if ($action == "delete" && !empty($criteriaId)) {
    deleteCriteria($criteriaId);
    header('Location: criteria_create.php'); // Redirect to the same page after deletion
    exit();
  }
}

// Get criteria list for display
$criteriaList = displayCriteria();

// Check if criteria is selected for editing
if (isset($_GET['edit'])) {
  $criteriaId = $_GET['edit'];
  // Assuming displayCriteria returns an array with id and name
  foreach ($criteriaList as $criteriaItem) {
    if ($criteriaItem['criteria_id'] == $criteriaId) {
      $selectedCriteria = $criteriaItem['criteria'];
      break;
    }
  }
}

// Handle delete action via GET request
if (isset($_GET['delete'])) {
  $criteriaId = $_GET['delete'];
  deleteCriteria($criteriaId);
  header('Location: criteria_create.php'); // Redirect to the same page after deletion
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Display Criteria</title>

  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
<div class="fixed bottom-0 left-0 right-0 top-0 z-10 mx-[260px] w-[1200px] p-10 border">
    <h1>Create Criteria</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($criteriaId); ?>">
      <input type="hidden" name="action" value="<?php echo $criteriaId ? 'update' : 'create'; ?>">
      <input type="text" name="criteria" placeholder="Enter a criteria" value="<?php echo htmlspecialchars($selectedCriteria); ?>" required>
      <input type="submit" value="Submit" /> <!-- Combined submit button -->
    </form>

    <!-- Where the criteria will be displayed -->
    <div id="criterialist">
      <?php if (count($criteriaList) > 0): ?>
        <ol>
          <?php foreach ($criteriaList as $index => $listCriteria): ?>
            <li>
              <div class="criteria-item">
                <?php echo htmlspecialchars($listCriteria['criteria']); ?>
                <div>
                  <?php for ($i = 1; $i <= 4; $i++): ?>
                    <label>
                      <input type="radio" name="rating[<?php echo $criteria; ?>][<?php echo $index; ?>]" value="<?php echo $i; ?>" required>
                      <?php echo $i; ?>
                    </label>
                  <?php endfor; ?>
                </div>

                <div class="three-dot-menu">
                  <div class="dot"></div>
                  <div class="dot"></div>
                  <div class="dot"></div>
                </div>
                <div class="dropdown">
                  <!-- Using links to handle editing and deleting -->
                  <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?edit=<?php echo $listCriteria['criteria_id']; ?>">Edit</a>
                  <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?delete=<?php echo $listCriteria['criteria_id']; ?>" onclick="return confirm('Are you sure you want to delete this criteria?');">Delete</a>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
        </ol>
      <?php else: ?>
        <div>No Criteria Available</div>
      <?php endif; ?>
    </div>
  </div>
  <!-- Input Form -->

</body>

</html>