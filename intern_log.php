<?php
require('Admin_connection.php');

session_start(); // Start the session

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connected successfully.<br>"; // Debugging line
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $InternID = $_POST['InternID'];
    $InternPass = $_POST['InternPass'];

    // Fetch the user from the database
    $sql = "SELECT * FROM intacc WHERE InternID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $InternID);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "SQL Query: $sql with InternID: $InternID<br>"; // Debugging line

    if ($result->num_rows > 0) {
        echo "User found.<br>"; // Debugging line
        $user = $result->fetch_assoc(); // Fetch user data as an associative array
        // Verify password
        if ($InternPass === $user['InternPass']) { // Correctly access InternPass from $user
            // Store user information in session
            $_SESSION['InternID'] = $InternID;
            echo "Login successful! Welcome, " . $_SESSION['InternID'] . "!<br>";
            // Redirect to the dashboard or next page
            header("Location: intern_page.php");
            exit();
        } else {
            echo "Password does not match.<br>"; // Debugging line
        }
    } else {
        echo "No user found with the provided InternID.<br>"; // Debugging line
    }

    $stmt->close();
}
$conn->close();
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intern</title>
</head>
<body>
    

<!DOCTYPE html>
<html lang="en">
<head>
  <title>task House</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <link rel="stylesheet" href="intern_style.css">
</head>
<body style="background-color:linear-gradient(to right, rgb(182, 244, 146), rgb(51, 139, 147));" class="abody">
<section class="vh-100" >
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem;">
          <div class="row g-0">
            <div class="col-md-6 col-lg-5 d-none d-md-block">
              <img src="image/cot.png" alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" width="500" height="600" position="center"/>
            </div>
            <div class="col-md-6 col-lg-7 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

                <!-- Single form element -->
                <form method="POST" action="">
                  <span class="s-intern">INTERN'S</span>
                  <span class="s-TH">TASK HOUSE</span>
                 
                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">INTERN LOG-IN</h5>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" id="form2Example17" name="InternID" class="form-control form-control-lg" />
                    <label class="form-label" for="form2Example17">Username</label>
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="password" id="form2Example27" name="InternPass" class="form-control form-control-lg" />
                    <label class="form-label" for="form2Example27">Password</label>
                  </div>

                  <button name="login" data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-lg btn-block" type="submit">
                    Login
                  </button>
                </form> 

         

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

    <script src="Intern_script.js"></script>

</body>
</html>