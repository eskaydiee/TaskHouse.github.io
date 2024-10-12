<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expandable Sidebar with User Info</title>
    <link rel="stylesheet" href="intern_styles.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar hide-content">
            <div class="user-info">
                <img src="image/cot.png" alt="Toggle Sidebar" class="user-icon">
                <div class="user-details">
                    <p class="user-name">Last name, First name</p>
                    <p class="role">INTERN</p>
                    <div class="button-container"> <!-- New container for buttons -->
                        <button class="btn break-btn">Break</button>
                        <button class="btn back-to-work-btn">Back to Work</button>
                    </div>
                </div>
            </div>
            <nav class="navigation">
                <a href="#" class="home-link" onclick="showContent('dashboard')">
                    <i class="fa fa-home"></i><span> Dashboard</span>
                </a>
                <a href="#" onclick="showContent('attendance')">
                    <i class="fa fa-cog"></i><span> Attendance</span>
                </a>
                <a href="#" onclick="showContent('requests')">
                    <i class="fa fa-cog"></i><span> Requests</span>
                </a>
            </nav>
            
            <!-- Profile Button in Sidebar -->
            <a href="#" onclick="showContent('My_profile')" class="bottom-right-corner profile-button">
                <i class="fas fa-power-off"></i><span>Profile</span>
            </a>
        </div>
        
        <!-- Header -->
        <div class="header" id="header">
            <button class="logout-btn" onclick="logout()">
                <img src="image/logout.png" alt="logout icon" class="logout-icon">
                | LOG OUT
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-section" id="dashboard">
    <div class="main-content">
        
            <div class="announcement-board">
                <h2>ANNOUNCEMENT BOARD</h2>
                <p class="announcement">This announcement's from CUT COT admin</p>
                <p class="announcement-details">
                    GDSAKFHASDFKNBASDF <br>
                    NFDSKAJFSDFLKNDSLAFLKNVADSLVNLSALVN <br>
                    SDAFDSAPFKJASDLFJASDLDFKMNF
                </p>
            </div>
        </div>
    

    <div class="time-content" >
    <div class="time-clock-container" >
        <h3>Online Time Clock</h3>
        <p id="time-display">Wednesday, March 20, 2024 13:41:51</p>
        <p>Last login at: <span id="last-login-time">3/19/2024 10:15</span></p>
        <div class="tasks">
            <input type="text" id="text">
            <button class="login-btn">Log in</button>
            <button class="logut-butn">Log out</button>
        </div>
    </div>
</div>
</div>

<div class="content-section" id="attendance">
    <div class="attend-content">
    <div class="attendance-container">
        <h1>REQUIRED HOURS: 700 HRS</h1>
    </div>
</div>
    </div>

    <div class="content-section" id="requests">
        <div class="req-content">
            <div class="wrapper">
            <button class="styled-button">Print</button>
        </div>
        </div>
    </div>

    <!-- Profile Modal -->
<div id="My_profile" class="content-section">
    <div class = "profile-cont">
        
    <div class="Coverimg">
      <!-- <img src="image/cot.png" alt="Logo" class="myprofileimg"> -->
    </div>

    <div class="profile-details">
      <form action="" method="POST">
        <div class="form-group">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" class="profile-input" placeholder="<?php echo $Firstname; ?>">
        </div>

        <div class="form-group">
            <label for="internImage">Insert Intern Image:</label>
            <input type="file" id="internImage" name="internImage">
          </div>

          <div class="form-group">
            <label for="shift">Shift:</label>
            <select id="shift" name="shift">
              <option value="morning">Morning</option>
              <option value="afternoon">Afternoon</option>
              <option value="evening">Evening</option>
            </select>
          </div>

          <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email">
          </div>

          <div class="form-group">
            <label for="hoursRequired">Hours Required:</label>
            <input type="number" id="hoursRequired" name="hoursRequired" min="1" max="24">
          </div>

          <div class="form-group">
            <label for="internId">Intern ID:</label>
            <input type="text" id="internId" name="internId">
          </div>

          <div class="form-group">
            <label for="dateStart">Date Started:</label>
            <input type="date" id="dateStart" name="dateStart">
          </div>

          <div class="form-group">
            <label for="internName">Intern Name:</label>
            <input type="text" id="internName" name="internName">
          </div>

          <div class="form-group">
            <label for="dateEnd">Date Ended:</label>
            <input type="date" id="dateEnd" name="dateEnd">
          </div>

          <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob">
          </div>

          <div class="form-group">
            <label for="companyFacilitatorName">Company Facilitator Name:</label>
            <input type="text" id="companyFacilitatorName" name="companyFacilitatorName">
          </div>
         
          <div class="form-group">
            <label for="courseSection">Course & Section:</label>
            <input type="text" id="courseSection" name="courseSection">
          </div>

          <div class="form-group">
            <label for="companyFacilitatorContact">Company Facilitator Contact:</label>
            <input type="text" id="companyFacilitatorContact" name="companyFacilitatorContact">
          </div>

          <div class="form-group gender-group">
            <label>Gender:</label>
            <label><input type="radio" name="gender" value="male"> Male</label>
            <label><input type="radio" name="gender" value="female"> Female</label>
          </div>

          <div class="form-group">
            <label for="companyName">Company Name</label>
            <input type="text" id="companyName" name="companyName">
          </div>

        <button type="submit" class="update-btn">Update</button>
      </form>
      <?php if (!empty($updateMessage)) : ?>
        <p><?php echo htmlspecialchars($updateMessage); ?></p>
      <?php endif; ?>
    </div>
  </div>
      </div>
   

    <script src="Intern_script.js"></script>
</body>
</html>
