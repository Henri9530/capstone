<?php
include('../database/dbconnect.php');
session_start();

// Assuming you are working with a form to assign a teacher and subject to a section
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the form data
  $section_id = $_POST['section_id'];
  $teacher_id = $_POST['teacher_id'];
  $subject_id = $_POST['subject_id'];

  // Call function to assign teacher and subject to section
  assignTeacherToSection($conn, $section_id, $teacher_id, $subject_id);
}

// Function to assign a teacher and subject to a section
function assignTeacherToSection($conn, $section_id, $teacher_id, $subject_id)
{
  // Check how many teachers are already assigned to the section
  $checkTeacherCount = $conn->prepare("SELECT COUNT(*) AS teacher_count FROM tblsection_teacher_subject WHERE section_id = ?");
  $checkTeacherCount->bind_param("i", $section_id);
  $checkTeacherCount->execute();
  $result = $checkTeacherCount->get_result();
  $row = $result->fetch_assoc();
  $teacher_count = $row['teacher_count'];

  if ($teacher_count >= 8) {
    echo "Cannot assign more than 8 teachers to this section.";
    return; // Stop further processing if the section already has 8 teachers
  }

  // Check if the teacher-subject assignment already exists for this section
  $checkAssignment = $conn->prepare("SELECT * FROM tblsection_teacher_subject WHERE section_id = ? AND teacher_id = ? AND subject_id = ?");
  $checkAssignment->bind_param("iii", $section_id, $teacher_id, $subject_id);
  $checkAssignment->execute();
  $result = $checkAssignment->get_result();

  if ($result->num_rows == 0) {
    // If not assigned, insert the teacher-subject assignment to the section
    $assignQuery = $conn->prepare("INSERT INTO tblsection_teacher_subject (section_id, teacher_id, subject_id) VALUES (?, ?, ?)");
    $assignQuery->bind_param("iii", $section_id, $teacher_id, $subject_id);

    if ($assignQuery->execute()) {
      echo "Teacher and subject assigned to section successfully!";
    } else {
      echo "Error: " . $assignQuery->error;
    }
  } else {
    echo "Teacher and subject are already assigned to this section.";
  }
}

// Handle adding a regular student to a section (if needed)
function addRegularStudent($conn, $student_id, $section_id, $is_regular)
{
  if ($is_regular) {
    // If the student is regular, automatically assign the teacher and subject from the section
    // First, get the teacher and subject assigned to the section (make sure to fetch only one record)
    $getAssignment = $conn->prepare("SELECT teacher_id, subject_id FROM tblsection_teacher_subject WHERE section_id = ? LIMIT 1");
    $getAssignment->bind_param("i", $section_id);
    $getAssignment->execute();
    $result = $getAssignment->get_result();

    if ($result->num_rows > 0) {
      // Get the teacher and subject
      $assignment = $result->fetch_assoc();
      $teacher_id = $assignment['teacher_id'];
      $subject_id = $assignment['subject_id'];

      // Now assign the student to the section with the teacher and subject
      $assignStudent = $conn->prepare("INSERT INTO tblstudent_section (student_id, section_id, teacher_id, subject_id) VALUES (?, ?, ?, ?)");
      $assignStudent->bind_param("iiii", $student_id, $section_id, $teacher_id, $subject_id);

      if ($assignStudent->execute()) {
        echo "Regular student added to section with assigned teacher and subject.";
      } else {
        echo "Error: " . $assignStudent->error;
      }
    } else {
      echo "No teacher and subject assigned to this section.";
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Assign Teacher and Subject to Section</title>
</head>

<body>
  <h1>Assign Teacher and Subject to Section</h1>

  <form action="" method="POST">
    <!-- Section Selection -->
    <label for="section_id">Select Section:</label>
    <select name="section_id" id="section_id" required>
      <?php
      // Fetch sections from the database
      $query = "SELECT * FROM tblsection";
      $result = $conn->query($query);

      while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['section_id'] . "'>" . $row['section_name'] . "</option>";
      }
      ?>
    </select><br><br>

    <!-- Teacher Selection -->
    <label for="teacher_id">Select Teacher:</label>
    <select name="teacher_id" id="teacher_id" required>
      <?php
      // Fetch teachers from the database
      $query = "SELECT * FROM tblteacher";
      $result = $conn->query($query);

      while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['teacher_id'] . "'>" . $row['name'] . "</option>";
      }
      ?>
    </select><br><br>

    <!-- Subject Selection -->
    <label for="subject_id">Select Subject:</label>
    <select name="subject_id" id="subject_id" required>
      <?php
      // Fetch subjects from the database
      $query = "SELECT * FROM tblsubject";
      $result = $conn->query($query);

      while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['subject_id'] . "'>" . $row['subject_name'] . "</option>";
      }
      ?>
    </select><br><br>

    <!-- Submit Button -->
    <button type="submit">Assign Teacher and Subject</button>
  </form>
</body>

</html>