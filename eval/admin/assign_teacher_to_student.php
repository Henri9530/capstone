<?php
include('../database/dbconnect.php');
session_start();

$selected_student = $selected_teacher = $selected_subject = ""; // Initialize selected values

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the student_id from the form submission
  $student_id = $_POST['student_id'];
  $teacher_id = $_POST['teacher_id'];
  $subject_id = $_POST['subject_id'];

  // Store selected values to retain them
  $selected_student = $student_id;
  $selected_teacher = $teacher_id;
  $selected_subject = $subject_id;

  // Check if the student is irregular
  $stmt = $conn->prepare("SELECT is_regular FROM tblstudent_section WHERE student_id = ?");
  $stmt->bind_param("i", $student_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($row = $result->fetch_assoc()) {
    $is_regular = $row['is_regular'];
  } else {
    echo "<script>alert('No data found for this student.');</script>";
    exit; // Stop further processing
  }
  $stmt->close();

  // If the student is regular, don't allow the assignment
  if ($is_regular == 1) {
    echo "<script>alert('This student is regular. Assignments must be handled through sections.');</script>";
  } else {
    // Check how many teachers are already assigned to this student
    $stmt = $conn->prepare("SELECT COUNT(*) AS teacher_count FROM tblstudent_teacher_subject WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $teacher_count = $row['teacher_count'];
    $stmt->close();

    // If the student already has 8 teachers, display an alert and stop further execution
    if ($teacher_count >= 8) {
      echo "<script>alert('This student has already been assigned the maximum of 8 teachers.');</script>";
    } else {
      // Check if the teacher is already assigned to the subject
      $stmt = $conn->prepare("SELECT COUNT(*) AS teacher_subject_count FROM tblstudent_teacher_subject WHERE subject_id = ? AND teacher_id != ?");
      $stmt->bind_param("ii", $subject_id, $teacher_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      $teacher_subject_count = $row['teacher_subject_count'];
      $stmt->close();

      // If the subject is already assigned to another teacher, prevent the assignment
      if ($teacher_subject_count > 0) {
        echo "<script>alert('This subject is already assigned to another teacher.');</script>";
      } else {
        // Check if the student is already assigned to this teacher for the subject
        $stmt = $conn->prepare("SELECT COUNT(*) AS assignment_count FROM tblstudent_teacher_subject WHERE student_id = ? AND teacher_id = ? AND subject_id = ?");
        $stmt->bind_param("iii", $student_id, $teacher_id, $subject_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $assignment_count = $row['assignment_count'];
        $stmt->close();

        // If the assignment already exists, show an alert
        if ($assignment_count > 0) {
          echo "<script>alert('This student is already assigned to this teacher for the selected subject.');</script>";
        } else {
          // Prepare and bind the statement for inserting into tblstudent_teacher_subject
          $stmt = $conn->prepare("INSERT INTO tblstudent_teacher_subject (student_id, teacher_id, subject_id) VALUES (?, ?, ?)");
          $stmt->bind_param("iii", $student_id, $teacher_id, $subject_id);

          // Execute the statement
          if ($stmt->execute()) {
            echo "<script>alert('Teacher and subject successfully assigned to student!');</script>";
          } else {
            echo "<script>alert('Error in assignment: " . $stmt->error . "');</script>";
          }
          $stmt->close();
        }
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Assign Teacher and Subject to Student</title>
</head>

<body>
  <h2>Assign Teacher and Subject to Student For Irregular Student</h2>
  <form action="" method="POST">
    <!-- Student Selection -->
    <label for="student">Select Student:</label>
    <select name="student_id" id="student" required>
      <?php
      // Fetch only irregular students from the database
      $query = $conn->query("SELECT s.student_id, s.name FROM tblstudent s JOIN tblstudent_section ss ON s.student_id = ss.student_id WHERE ss.is_regular = 0");
      while ($row = $query->fetch_assoc()) {
        // Retain the previously selected student
        $selected = ($row['student_id'] == $selected_student) ? "selected" : "";
        echo "<option value='" . htmlspecialchars($row['student_id']) . "' $selected>" . htmlspecialchars($row['name']) . "</option>";
      }
      ?>
    </select><br><br>

    <!-- Teacher Selection -->
    <label for="teacher">Select Teacher:</label>
    <select name="teacher_id" id="teacher" required>
      <?php
      // Fetch teachers from the database
      $query = $conn->query("SELECT teacher_id, name FROM tblteacher");
      while ($row = $query->fetch_assoc()) {
        // Retain the previously selected teacher
        $selected = ($row['teacher_id'] == $selected_teacher) ? "selected" : "";
        echo "<option value='" . htmlspecialchars($row['teacher_id']) . "' $selected>" . htmlspecialchars($row['name']) . "</option>";
      }
      ?>
    </select><br><br>

    <!-- Subject Selection -->
    <label for="subject">Select Subject:</label>
    <select name="subject_id" id="subject" required>
      <?php
      // Fetch subjects from the database
      $query = $conn->query("SELECT subject_id, subject_name FROM tblsubject");
      while ($row = $query->fetch_assoc()) {
        // Retain the previously selected subject
        $selected = ($row['subject_id'] == $selected_subject) ? "selected" : "";
        echo "<option value='" . htmlspecialchars($row['subject_id']) . "' $selected>" . htmlspecialchars($row['subject_name']) . "</option>";
      }
      ?>
    </select><br><br>

    <button type="submit">Assign Teacher and Subject</button>
  </form>
</body>

</html>

<?php
$conn->close();
?>