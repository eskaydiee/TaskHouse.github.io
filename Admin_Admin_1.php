<?php
session_start(); // Start the session
require('Admin_connection.php');


// Fetch current user data
$query = "SELECT Firstname, Roles, admin_profile, Uname, Upass FROM users WHERE id = 79";
$query_run = mysqli_query($conn, $query);

// Error handling for the query
if ($query_run === false) {
    die('Error in SQL query: ' . mysqli_error($conn));
}

$admin = mysqli_fetch_assoc($query_run);

if (!$admin) {
    die('No admin found.');
}

// Initialize message variable
$updateMessage = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $Firstname = $_POST['Firstname'] ?? null;
    $Roles = $_POST['Roles'] ?? null;
    $Uname = $_POST['Uname'] ?? null;
    $Upass = $_POST['Upass'] ?? null;

    // Handle file upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $file = $_FILES['profile_image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($file['type'], $allowedTypes)) {
            $imageData = file_get_contents($file['tmp_name']);
            $imageBase64 = base64_encode($imageData);
        } else {
            $updateMessage = 'Invalid image type.';
            $imageBase64 = $admin['admin_profile']; // Use existing image if invalid
        }
    } else {
        $imageBase64 = $admin['admin_profile']; // Use existing image if no new image is uploaded
    }

    // Validate input
    if (empty($Firstname) || empty($Roles) || empty($Uname)) {
        $_SESSION['message'] = 'Please fill all required fields.'; // Set message in session
    } else {
        // Password handling
        $hashedPassword = password_hash($Upass, PASSWORD_DEFAULT);

        // Update query
        $sql = "UPDATE users SET Firstname = ?, Roles = ?, Uname = ?, Upass = ?, admin_profile = ? WHERE id = 79";

        // Prepare and execute the query
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssss", $Firstname, $Roles, $Uname, $hashedPassword, $imageBase64);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Profile updated successfully."; // Set message in session
            } else {
                $_SESSION['message'] = "Error updating profile: " . $stmt->error; // Set message in session
            }

            $stmt->close();
        } else {
            $_SESSION['message'] = "Error preparing statement: " . $conn->error; // Set message in session
        }
    }
}

// Handle adding intern account
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['internID'])) {
    $internID = $_POST['internID'];
    $InternPass = $_POST['InternPass'] ?? '';

    // Hash the password for security
    $hashed_password = password_hash($InternPass, PASSWORD_DEFAULT);

    // Prepare SQL query to insert data
    $sql = "INSERT INTO intacc (internID, Internpass) VALUES (?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $internID, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Intern account added successfully!'; // Set success message in session
        } else {
            $_SESSION['message'] = 'Error: Could not add intern account.'; // Set error message in session
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = 'Error preparing statement: ' . $conn->error; // Set error message in session
    }
}

// Handle updating intern account
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateIntern'])) {
    $internID = $_POST['internID'];
    $InternPass = $_POST['InternPass'];

    // Hash the new password
    $hashed_password = password_hash($InternPass, PASSWORD_DEFAULT);

    // Update query
    $sql = "UPDATE intacc SET Internpass = ? WHERE internID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $hashed_password, $internID);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Intern account updated successfully!'; // Set success message in session
        } else {
            $_SESSION['message'] = 'Error updating intern account: ' . $stmt->error; // Set error message in session
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = 'Error preparing statement: ' . $conn->error; // Set error message in session
    }
}

// Handle deleting intern account
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteIntern'])) {
    $internID = $_POST['internID'];

    // Check if the ID exists
    $checkQuery = "SELECT * FROM intacc WHERE internID = ?";
    if ($checkStmt = $conn->prepare($checkQuery)) {
        $checkStmt->bind_param("s", $internID);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Delete query
            $sql = "DELETE FROM intacc WHERE internID = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $internID);
                if ($stmt->execute()) {
                    $_SESSION['message'] = 'Intern account deleted successfully!'; // Set success message in session
                } else {
                    $_SESSION['message'] = 'Error deleting intern account: ' . $stmt->error; // Set error message in session
                }
                $stmt->close();
            } else {
                $_SESSION['message'] = 'Error preparing statement: ' . $conn->error; // Set error message in session
            }
        } else {
            $_SESSION['message'] = 'Intern ID does not exist.'; // Set error message in session
        }
    }
}

// Fetch intern account data for display
$internQuery = "SELECT internID, Internpass FROM intacc";
$internQueryRun = mysqli_query($conn, $internQuery);

if ($internQueryRun === false) {
    die('Error in SQL query: ' . mysqli_error($conn));
}

// -------------------------------------------------
// Handle adding facilitator account
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['faciID']) && !isset($_POST['updateFaci']) && !isset($_POST['deleteFaci'])) {
    $faciID = $_POST['faciID'];
    $faciPass = $_POST['faciPass'] ?? '';

    // Hash the password for security
    $hashed_password = password_hash($faciPass, PASSWORD_DEFAULT);

    // Prepare SQL query to insert data
    $sql = "INSERT INTO facacc (faciID, faciPass) VALUES (?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $faciID, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Facilitator account added successfully!'; // Set success message in session
        } else {
            $_SESSION['message'] = 'Error: Could not add facilitator account.'; // Set error message in session
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = 'Error preparing statement: ' . $conn->error; // Set error message in session
    }
}

// Handle updating facilitator account

// Fetch facilitator account data for display
$faciQuery = "SELECT faciID, faciPass FROM facacc";
$faciQueryRun = mysqli_query($conn, $faciQuery);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateFaci'])) {
    $faciID = $_POST['faciID'];
    $faciPass = $_POST['faciPass'];

    // Hash the new password
    $hashed_password = password_hash($faciPass, PASSWORD_DEFAULT);

    // Update query
    $sql = "UPDATE facacc SET faciPass = ? WHERE faciID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $hashed_password, $faciID);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Facilitator account updated successfully!'; // Set success message in session
        } else {
            $_SESSION['message'] = 'Error updating facilitator account: ' . $stmt->error; // Set error message in session
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = 'Error preparing statement: ' . $conn->error; // Set error message in session
    }
}

// Handle deleting facilitator account
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteFaci'])) {
    $faciID = $_POST['faciID'];

    // Check if the ID exists
    $checkQuery = "SELECT * FROM facacc WHERE faciID = ?";
    if ($checkStmt = $conn->prepare($checkQuery)) {
        $checkStmt->bind_param("s", $faciID);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Delete query
            $sql = "DELETE FROM facacc WHERE faciID = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $faciID);
                if ($stmt->execute()) {
                    $_SESSION['message'] = 'Facilitator account deleted successfully!'; // Set success message in session
                } else {
                    $_SESSION['message'] = 'Error deleting facilitator account: ' . $stmt->error; // Set error message in session
                }
                $stmt->close();
            } else {
                $_SESSION['message'] = 'Error preparing statement: ' . $conn->error; // Set error message in session
            }
        } else {
            $_SESSION['message'] = 'Facilitator ID does not exist.'; // Set error message in session
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Sidebar with Sample Content</title>
  <!-- Link to external CSS -->
  <link rel="stylesheet" href="Admin_style.css">

  

</head>
<body>

<?php
 
    if (isset($_SESSION['message'])) {
        echo '<div class="alert">' . htmlspecialchars($_SESSION['message']) . '</div>'; // Display the message
        unset($_SESSION['message']); // Clear the message after displaying
    }
    ?>
  <!-- Sidebar Menu -->
  <div id="sidebar" class="sidebar" onclick="toggleSidebar()">
    <img src="<?php echo $logoImage; ?>" alt="Logo" class="logo" onclick="toggleSidebar()">
    <span class="close-btn" onclick="toggleSidebar()"></span>
    <div id="detail">
        <?php echo "$Firstname <br> $Roles"; ?>
    </div>
    <a href="#" class="home-link" onclick="showContent('Dashboard')"><i class="fa fa-home"></i><span> Dashboard</span></a>
    <a href="#" onclick="showContent('Intern_profile')"><i class="fa fa-cog"></i><span> Intern Profiles</span></a>
    <a href="#" onclick="showContent('Intern_Account')"><i class="fa fa-info-circle"></i><span> Intern Logins</span></a>
    <a href="#" onclick="showContent('FaccAcc')"><i class="fa fa-phone"></i><span> Facilitator Logins</span></a>
   
    <a href="#" onclick="showContent('report')"><i class="fa fa-phone"></i><span> Report</span></a>
      <!-- Profile Button in Sidebar -->

      <button class="profile-button" onclick="openModal('admin_modal')">Profile</button>

    


  </div>


  <!-- Header with LOG OUT Button -->
  <div id="header" class="header">
    <button class="logout-btn" onclick="logout()">
      <img src="image/logout.png" alt="Logout Icon" class="logout-icon"> | LOG OUT
    </button>
  </div>

  <!-- Main Content -->
  <div id="main-content" class="main-content">
    <div id="Dashboard" class="content-section active">
      <div class="dbmain-content">
        <h1 class="dashboard-title">Dashboard</h1>
        <div class="dashboard-cards">
            <div class="card course"><h2>Course & Section</h2><p>1 Course & Section</p></div>
            <div class="card shift"><h2>Intern’s Shift</h2><p>2 Interns's  Shift</p></div>
            <div class="card intern"><h2>Intern</h2><p>3 Intern</p></div>
            <div class="card company"><h2>Company</h2><p>4 Company</p></div>
        </div>
        <div class="announcement-board">
          <h2>Announcement Board</h2>
          <div class="input-container">
            <input type="text" placeholder="Enter your Announcement here" class="styled-input">
          </div>
          <button class="post-button">POST</button>
        </div>
      </div>
    </div>

     <!-- Intern Admin provide account -->
     <div id="Intern_profile" class="content-section">
      <h1>Intern Profile</h1>
      <h1 class="db_Details">Database here!</h1>
      

    </div>
    

    <!-- Intern_Account Content -->
    <div id="Intern_Account" class="content-section">
    <h1>Intern Account</h1>
    <button class="intern_acc" onclick="openModal('InternAccModal')">Intern Account</button>

    <h1 class="db_Details">Database here!</h1>
       <!-- Display message if available -->
       
     <!-- HTML to display data in a table -->
     <table border="1">
                <tr>
                    <th>Intern ID</th>
                    <th>Password (hashed)</th>
                    <th >Actions</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($internQueryRun)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['internID']); ?></td>
                        <td><?php echo htmlspecialchars($row['Internpass']); ?></td>
                        <td>
                            <!-- Button to Update -->
                            <form action="" method="POST" style="display:center-block;"> 
                                <input type="hidden" name="internID" value="<?php echo htmlspecialchars($row['internID']); ?>">
                                <input type="password" name="InternPass" placeholder="New Password" required>
                                <button type="submit" name="updateIntern">Update</button>
                            </form>

                            <!-- Button to Delete -->
                            <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                  <input type="hidden" name="internID" value="<?php echo htmlspecialchars($row['internID']); ?>">
                                  <button type="submit" name="deleteIntern">Delete</button>
                              </form>

                        </td>
                    </tr>
                <?php endwhile; ?>
                </table>

    </div>

    

      <!-- Facilitor Admin provide account -->

      <div id="FaccAcc" class="content-section">
      <h1>Add Facilitator Account</h1>
      <button class="faci_acc" onclick="openModal('FaccAccModal')">Facilitator Account</button>
      <h1 class="db_Details">Database here!</h1>

       
                <!-- HTML to display facilitator data in a table -->
            <table border="1">
                <tr>
                    <th>Facilitator ID</th>
                    <th>Password (hashed)</th>
                    <th>Actions</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($faciQueryRun)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['faciID']); ?></td>
                        <td><?php echo htmlspecialchars($row['faciPass']); ?></td>
                        <td>
                            <!-- Button to Update -->
                            <form action="" method="POST" style="display: inline-block;">
                                <input type="hidden" name="faciID" value="<?php echo htmlspecialchars($row['faciID']); ?>">
                                <input type="password" name="faciPass" placeholder="New Password" required>
                                <button type="submit" name="updateFaci">Update</button>
                            </form>

                            <!-- Button to Delete -->
                            <form action="" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                <input type="hidden" name="faciID" value="<?php echo htmlspecialchars($row['faciID']); ?>">
                                <button type="submit" name="deleteFaci">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>


         


    <div id="report" class="content-section">
    <h1 class="db_Details">Database here! </h1>
    <h6>Filtering must be in here</h6>
    <h6>Can also be printable</h6>
    </div>

    <div id="admin_modal" class="modal">
    <div class="admin_modal-content">
        <span class="close" onclick="closeModal('admin_modal')">&times;</span>
        <div class="profile-details">
        <form id="admin_modal_form" method="POST" >
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="Firstname" name="Firstname" class="profile-input" placeholder="Your Name">
                </div>
                <div class="form-group">
                    <label for="position">Position:</label>
                    <input type="text" id="Roles" name="Roles" class="profile-input" placeholder="Your Position">
                </div>
                <button type="submit" class="update-btn">Update</button>
            </form>
        </div>
    </div>
</div>

    



<!-- Modal for add intern Account -->
<div id="InternAccModal" class="modal">
    <div class="Accmodal-content">
        <span class="close" onclick="closeModal('InternAccModal')">&times;</span>
        <h2>Add Intern Account</h2>
        <form id="addInterAccForm" method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="internID">Intern ID:</label>
                <input type="text" id="internID" name="internID" required>
            </div>
            <div class="form-group">
                <label for="InternPass">Password:</label>
                <input type="password" id="InternPass" name="InternPass" required> <!-- Fix the name attribute here -->
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>


      <!-- Modal for adding Facilitator Account -->
<div id="FaccAccModal" class="modal">
  <div class="Accmodal-content">
    <span class="close" onclick="closeModal('FaccAccModal')">&times;</span>
    <h2>Add Facilitator Account</h2>
    <form id="addFaccAccForm" method="POST">
      <div class="form-group">
        <label for="faciID">Facilitator ID:</label>
        <input type="text" id="faciID" name="faciID" required>
      </div>
      <div class="form-group">
        <label for="faciPass">Password:</label>
        <input type="password" id="faciPass" name="faciPass" required>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>

  <!-- Link to external JS -->
  <script src="Admin_script.js"></script>

  <!-- Font Awesome Icons -->
</body>
</html>