<?php
include('../database/dbconnect.php');
// include('../admin/aside.php');
session_start();
?>

<?php
$sql = "SELECT s.school_id, s.name, s.email, s.year_level, s.image, 
d.department_name, sec.section_name, ss.is_regular
FROM tblstudent s
LEFT JOIN tbldepartment d ON s.department_id = d.department_id
LEFT JOIN tblstudent_section ss ON s.student_id = ss.student_id
LEFT JOIN tblsection sec ON ss.section_id = sec.section_id";

// Check if search term exists and modify the query accordingly
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $searchTerm = $conn->real_escape_string($_GET['search']);
  $sql .= " WHERE s.name LIKE '%$searchTerm%' OR s.school_id LIKE '%$searchTerm%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Student</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <aside>
    <div class="container">
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
  <main class="container mx-[260px] 
    fixed bottom-0 top-0 z-10 p-2 m-2 lg:w-[1170px] 
    border">
    <!-- class="fixed bottom-0 left-0 right-0 top-0 z-10 mx-[260px] w-[1200px] p-10 border" -->
    <div class="container-full p-2">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get">
        <div class="flex  justify-between items-center p-2">
          <div>
            <span class="
            px-5 py-2 mx-1 bg-blue-900 text-white 
            rounded-md hover:border-s-4 border-red-500 cursor-pointer js-modal">+ Add Student</span>
          </div>
          <div class="flex  justify-between items-center">
            <div>
              <input type="text" name="search" class="p-2 w-[300px] border" placeholder="Search students name, school id..."
                autocomplete="off" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            <div class="mx-1">
              <input
                type="submit"
                name="enter"
                value="Search"
                class="cursor-pointer rounded-sm px-8 py-2 bg-blue-900 text-white">
            </div>
          </div>
        </div>
      </form>
     
      <table class="table-auto w-full border shadow">
        <thead class="border bg-blue-900 text-white">
          <tr>
            <th class="px-4 py-2 text-start">School ID</th>
            <th class="px-4 py-2 text-start">Profile</th>
            <th class="px-4 py-2 text-start">Name</th>
            <th class="px-4 py-2 text-start">Email</th>
            <th class="px-4 py-2 text-start">Department</th>
            <th class="px-4 py-2 text-start">Section</th>
            <th class="px-4 py-2 text-start">Year Level</th>
            <th class="px-4 py-2 text-start">is_Regular</th>
            <th class="px-4 py-2 text-start">Action</th>
          </tr>
        </thead>
        <tbody class="border">
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td class="px-4 py-2 text-center border"><?php echo htmlspecialchars($row['school_id']); ?></td>
                <td class="px-4 py-2 text-center border">
                  <div class="flex justify-center items-center">
                    <div>
                      <?php if ($row['image']): ?>
                        <img src="../pic/pics/<?php echo htmlspecialchars($row['image']); ?>" alt="Student Image"
                          class="w-10 h-10">
                      <?php else: ?>
                        No Image
                      <?php endif; ?>
                    </div>

                  </div>
                </td>
                <td class="px-4 py-2 text-center border"><?php echo htmlspecialchars($row['name']); ?></td>
                <td class="px-4 py-2 text-center border"><?php echo htmlspecialchars($row['email']); ?></td>
                <td class="px-4 py-2 text-center border"><?php echo htmlspecialchars($row['department_name']); ?></td>
                <td class="px-4 py-2 text-center border"><?php echo htmlspecialchars($row['section_name']); ?></td>
                <td class="px-4 py-2 text-center border"><?php echo htmlspecialchars($row['year_level']); ?></td>
                <td class="px-4 py-2 text-center border"><?php echo $row['is_regular'] ? 'Yes' : 'No'; ?></td>
                <td>
                  <div class="flex justify-center items-center">
                    <div class="w-full mt-1">
                      <a href="<?php echo 'student_create.php ? updateid=' . $id . '' ?>">
                        <img id="image-preview"
                          src="../Profiles User/Student.png"
                          alt="Image Preview"
                          class="w-7 h-7 object-cover 
                          mt-4 rounded-md">
                      </a>
                    </div>
                    <div class="w-full mt-1">
                      <a href="<?php echo 'student_create.php ? updateid=' . $id . '' ?>">
                        <img id="image-preview"
                          src="../Profiles User/Student.png"
                          alt="Image Preview"
                          class="w-7 h-7 object-cover 
                          mt-4 rounded-md">
                      </a>
                    </div>
                    <div class="w-full mt-1">
                      <a href="<?php echo 'student_create.php ? updateid=' . $id . '' ?>">
                        <img id="image-preview"
                          src="../Profiles User/Student.png"
                          alt="Image Preview"
                          class="w-7 h-7 object-cover 
                          mt-4 rounded-md">
                      </a>
                    </div>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="8">No students found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    
    <div class="fixed top-20 z-10 w-3/4 h-4/6  mx-5  p-2 rounded-md bg-slate-900 text-white
             hidden modal">
        <div class="m-1">
          <h1 class="text-white text-lg font-semibold">ADD STUDENT</h1>
        </div>
        <form
          action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>"
          method="post"
          enctype="multipart/form-data">
          <div class="flex justify-evenly items-center">
            <div class="mt-4">
              <div class="m-1 flex justify-between items-center">
                <div>
                  <h1 class="text-white text-2xl">Image Preview</h1>
                </div>
              </div>
              <div>
                <img id="image-preview"
                  src="../Profiles User/Student.png"
                  alt="Image Preview"
                  class="w-64 h-64 object-cover 
                      mt-4 rounded-md">
              </div>
              <div class="mt-7">
                <label for="hen"
                  class="px-20 py-3 border 
                        rounded cursor-pointer shadow relative">
                  Upload Image
                </label>
                <input
                  type="file"
                  id="hen"
                  class="hidden w-full"
                  accept="image/*"
                  onchange="previewImage(event)"
                  required
                  name="hen"
                  value="" accept=".jpeg, .jpg, .png, .svg">
              </div>
            </div>
            <div class="grid grid-cols-2 gap-6">
              <div class="mt-8">
                <div class="m-1 flex justify-start items-center">
                  <label>School ID</label>
                </div>
                <div>
                  <input
                    type="text"
                    class="w-full px-3 py-2 border-s-4 text-black border-blue-900 rounded-sm"
                    placeholder="School ID"
                    minlength="7" maxlength="7" name="school_id"
                    autocomplete="off" value="<?php echo isset($schoolId); ?>">
                </div>
                <div class="m-1 flex justify-start items-center">
                  <label>First Name</label>
                </div>
                <div>
                  <input
                    type="text"
                    class="w-full px-3 py-2 border-s-4 text-black border-blue-900 rounded-sm"
                    placeholder="First Name"
                    name="fname" autocomplete="off">
                </div>
                <div class="m-1 flex justify-start items-center">
                  <label>Last Name</label>
                </div>
                <div>
                  <input
                    type="text"
                    class="w-full px-3 py-2 border-s-4 text-black border-blue-900 rounded-sm"
                    placeholder="Last Name"
                    name="lname" autocomplete="off">
                </div>
                <div class="m-1 flex justify-start items-center">
                  <label>Email</label>
                </div>
                <div>
                  <input
                    type="text"
                    class="w-full px-3 py-2 border-s-4 text-black border-blue-900 rounded-sm"
                    placeholder="Email"
                    name="email" autocomplete="off">
                  <input
                    class="w-full px-3 py-2 border-s-4 text-black border-blue-900 rounded-sm"
                    type="hidden"
                    name="password"
                    autocomplete="off" value="<?php echo isset($schoolId); ?>" readonly>
                </div>
              </div>
              <div class="mt-8">
                <div class="m-1 flex justify-start items-center">
                  <label>Year</label>
                </div>
                <div class="m-1 flex justify-start items-center">
                  <input
                    type="text"
                    class="w-full text-black px-3 py-2 border-s-4 border-blue-900 rounded-sm"
                    placeholder="Year"
                    name="year">
                </div>
                <div class="m-1 flex justify-start items-center">
                  <label>Section</label>
                </div>
                <div>
                  <select name="section_id" id="section" class="w-full text-black px-3 py-2 
                      border-s-4 border-blue-900 rounded-sm" required>
                    <option value="" disabled selected>Select Section</option>
                    <?php
                    $section = $conn->query("SELECT * FROM tblsection");
                    while ($row = $section->fetch_assoc()): ?>
                      <option value="<?php echo $row['section_id']; ?>"><?php echo htmlspecialchars($row['section_name']); ?></option>
                    <?php endwhile; ?>
                  </select>
                </div>
                <div class="mt-2 flex flex-col justify-start items-start">
                  <div class="m-1 flex justify-start items-start">
                    <div>
                      <label>Department</label>
                    </div>
                  </div>
                  <div>
                    <select
                      name="department_id"
                      required
                      class="px-3 py-2 w-full border-s-4 border-blue-900 text-black rounded-sm">
                      <option value="" disabled selected>Select Department</option>
                      <?php
                      $department = $conn->query("SELECT * FROM tbldepartment");
                      while ($row = $department->fetch_assoc()): ?>
                        <option value="<?php echo $row['department_id']; ?>"><?php echo htmlspecialchars($row['department_name']); ?></option>
                      <?php endwhile; ?>
                    </select>
                  </div>
                  <div class="m-1 flex justify-start items-center">
                    <label class="text-lg">Is Regular</label>
                  </div>
                  <div>
                    <input
                      type="checkbox"
                      class="w-full text-black px-3 py-2 border-s-4 border-blue-900 rounded-sm"
                      placeholder="Section"
                      name="is_regular"
                      value="1">
                  </div>
                </div>
                <div class="mt-5">
                  <div>
                    <button type="submit" name="submit"
                      class="w-full text-center bg-blue-900 py-2 mt-3 rounded-md hover:border-s-4 border-white">
                      Submit
                    </button>
                  </div>
                  <div>
                    <div class="w-full text-center bg-blue-900 py-2 mt-3 rounded-md hover:border-s-4 border-white
                          cursor-pointer js-close">Cancel</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>

  </main>

  <script>
    const btnModal = document.querySelector('.js-modal');
    const modal = document.querySelector('.modal');
    const btnClose = document.querySelectorAll('.js-close');

    btnModal.addEventListener('click', () => {
      modal.classList.remove('hidden');
    });


    btnClose.forEach((button) => {
      button.addEventListener('click', () => {
        modal.classList.add('hidden'); // Nya diri mawala ang modal
      });
    });


    function previewImage(event) {
      const file = event.target.files[0];
      const preview = document.getElementById('image-preview');

      if (file) {
        const reader = new FileReader();
        reader.onload = () => {
          preview.src = reader.result;
        };
        reader.readAsDataURL(file);
      } else {
        preview.src = ""; // Clear the preview if no file is selected
      }
    }

    document.addEventListener("DOMContentLoaded", function() {
      const sectionSelect = document.getElementById("section");
      const isRegularCheckbox = document.querySelector('input[name="is_regular"]');

      // Hide the section dropdown if the student is irregular
      function toggleSectionVisibility() {
        if (isRegularCheckbox.checked) {
          sectionSelect.disabled = false; // Enable section selection if regular
        } else {
          sectionSelect.disabled = true; // Disable section selection if irregular
        }
      }

      // Initialize visibility based on the checkbox state
      toggleSectionVisibility();

      // Listen for changes to the checkbox
      isRegularCheckbox.addEventListener('change', toggleSectionVisibility);
    });
  </script>
</body>

</html>

<?php

if (isset($_POST['section_id'])) {
  $section_id = $_POST['section_id'];
} else {
  // Handle the case where section_id is not set
  $section_id = null; // Or set to a default value, or handle the error
}

function addStudent($conn, $school_id, $fname, $lname, $email, $department_id, $year, $section_id, $is_regular, $password, $file)
{
  // Combine first and last names
  $name = $fname . " " . $lname;

  // Set default password as school_id
  $default_password = $school_id;

  // Password verification
  if ($password === $default_password) {
    echo 'Your password is ' . $default_password;
    return;
  }

  // Check if an image file is uploaded
  if ($file['error'] === 4) {
    echo "<script>alert('Image not exist');</script>";
    return;
  }

  // Handle file data
  $imgname = $file['name'];
  $imgsize = $file['size'];
  $imgtmp = $file['tmp_name'];

  // Validate image extension
  $imgvalid = ['jpeg', 'jpg', 'png', 'svg'];
  $imgEx = strtolower(pathinfo($imgname, PATHINFO_EXTENSION));

  // Validate extension and size
  if (!in_array($imgEx, $imgvalid)) {
    echo "<script>alert('Invalid extension');</script>";
    return;
  } elseif ($imgsize > 1000000) {
    echo "<script>alert('Image is too large');</script>";
    return;
  }

  // Create a unique filename and move the file
  $newimg = uniqid() . '.' . $imgEx;
  if (!move_uploaded_file($imgtmp, "../pic/pics/$newimg")) {
    echo "<script>alert('Image upload failed');</script>";
    return;
  }

  // Prepared statement to insert student data
  $sql = "INSERT INTO tblstudent (school_id, name, email, password, department_id, year_level, image) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

  if ($stmt = $conn->prepare($sql)) {
    // Bind parameters to the prepared statement
    $stmt->bind_param('sssssss', $school_id, $name, $email, $default_password, $department_id, $year, $newimg);

    // Execute the prepared statement
    if ($stmt->execute()) {
      // Get the student_id of the newly inserted student
      $student_id = $stmt->insert_id;

      // Handle irregular students (if no section)
      if ($is_regular) {
        // Insert into tblstudent_section if regular student and section is selected
        if (isset($section_id) && !empty($section_id)) {
          $stmt_section = $conn->prepare("INSERT INTO tblstudent_section (student_id, section_id, is_regular) VALUES (?, ?, ?)");
          $stmt_section->bind_param("iii", $student_id, $section_id, $is_regular);

          if ($stmt_section->execute()) {
            echo "Student and section information added successfully!";
           
          } else {
            echo "Error: " . $stmt_section->error;
          }

          // Close the stmt_section
          $stmt_section->close();
        } else {
          echo "<script>alert('Section is required for regular students.');</script>";
        }
      } else {
        // If the student is irregular, insert into tblstudent_section with section_id = 0 and is_regular = 0
        $section_id = 0;  // Irregular student doesn't have a section
        $stmt_section = $conn->prepare("INSERT INTO tblstudent_section (student_id, section_id, is_regular) VALUES (?, ?, ?)");
        $stmt_section->bind_param("iii", $student_id, $section_id, $is_regular);

        if ($stmt_section->execute()) {
          echo "Irregular student added successfully without section!";
          
        } else {
          echo "Error: " . $stmt_section->error;
        }
 header('Location: ../admin/student_view.php');  
        // Close the stmt_section
        $stmt_section->close();
        
      }
          
    } else {
      echo "Error: " . $stmt->error;
    }
    // Close the statement
    $stmt->close();
  } else {
    echo "<script>alert('Error: Could not prepare query.');</script>";
  }
}


// Call the function with form data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
  $section_id = isset($_POST['section_id']) ? $_POST['section_id'] : null; // Check if section_id is provided
  addStudent(
    $conn,
    $_POST['school_id'],
    $_POST['fname'],
    $_POST['lname'],
    $_POST['email'],
    $_POST['department_id'],
    $_POST['year'],
    $section_id,
    isset($_POST['is_regular']) ? 1 : 0,
    $_POST['password'],
    $_FILES['hen']
  );
}
?>