<?php
// Database connection
require('Admin_connection.php'); // This should properly set up the database connection

// Assume you have a valid `$pdo` object for the database connection
// Example: $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Initialize a variable for the update message
$updateMessage = "";

// Define the user ID to be updated (you need to set this dynamically or pass it as a hidden field)
$user_id = 1; // Replace this with the appropriate value for the user

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form values
    $name = $_POST['name'];
    $position = $_POST['position'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $profile_image = $_FILES['profile_image']['name'];

    // Process image upload if a new image is provided
    if (!empty($profile_image)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_image);
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            // File upload successful
        } else {
            // File upload failed
            $updateMessage = "Failed to upload image.";
        }
    }

    // Prepare SQL update query
    $query = "UPDATE users SET name = :name, position = :position, username = :username";
    
    if (!empty($password)) {
        // Hash password if provided
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query .= ", password = :password";
    }

    if (!empty($profile_image)) {
        $query .= ", profile_image = :profile_image";
    }

    $query .= " WHERE id = :user_id";

    // Prepare the statement with the existing PDO connection
    $stmt = $pdo->prepare($query);

    // Bind values to the statement
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':position', $position);
    $stmt->bindValue(':username', $username);
    
    if (!empty($password)) {
        $stmt->bindValue(':password', $hashed_password);
    }
    
    if (!empty($profile_image)) {
        $stmt->bindValue(':profile_image', $profile_image);
    }

    $stmt->bindValue(':user_id', $user_id);

    // Execute the update query
    if ($stmt->execute()) {
        $updateMessage = "Profile updated successfully!";
    } else {
        $updateMessage = "Failed to update profile.";
    }
}
?>
    <div class="profile-details">
      <form action="" method="POST">
        <div class="form-group">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" class="profile-input" placeholder="<?php echo $Firstname; ?>">
        </div>
        <div class="form-group">
          <label for="position">Position:</label>
          <input type="text" id="position" name="position" class="profile-input" placeholder="<?php echo $Roles; ?>">
        </div>
        <div class="form-group">
          <label for="username">User Name:</label>
          <input type="text" id="username" name="username" class="profile-input" placeholder="<?php echo $Uname; ?>">
        </div>
        <div class="form-group">
          <label for="password">User Password:</label>
          <input type="password" id="password" name="password" class="profile-input" placeholder="Enter new password">
        </div>

        <div class="form-group">
            <label for="profile_image">Change Profile Image:</label>
            <input type="file" id="profile_image" name="profile_image" class="profile-input">
          </div>



        <button type="submit" class="update-btn">Update</button>
      </form>
      <?php if (!empty($updateMessage)) : ?>
        <p><?php echo htmlspecialchars($updateMessage); ?></p>
      <?php endif; ?>
    </div>
  </div>