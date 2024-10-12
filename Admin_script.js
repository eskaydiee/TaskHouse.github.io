function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  

  const header = document.getElementById('header');
  const mainContent = document.getElementById('main-content');

  sidebar.classList.toggle('active');
  header.classList.toggle('shifted');
  mainContent.classList.toggle('shifted');
  
}

function showContent(section) {
  const sections = document.querySelectorAll('.content-section');

  sections.forEach((content) => {
      content.classList.remove('active'); 
  });

  const activeSection = document.getElementById(section);
  activeSection.classList.add('active');
}


function logout() {
  alert('Logging out...');
  window.location.href = "Admin_registration.php";
}

// Function to open a modal
function openModal(modalId) {
  document.getElementById(modalId).style.display = 'block';
}

// Function to close a modal
function closeModal(modal) {
  if (modal) {
      modal.style.display = "none"; // Hide the modal
  }
}

// Add event listeners to all close buttons
document.querySelectorAll('.modal .close').forEach(button => {
  button.addEventListener('click', function() {
      const modalId = this.closest('.modal').id; // Get the parent modal's ID
      closeModal(document.getElementById(modalId));
  });
});

// Handle form submission
document.getElementById('Interns_ShiftModal').addEventListener('submit', function(event) {
  event.preventDefault();
  const startTime = document.getElementById('startTimeInput').value;
  const endTime = document.getElementById('endTimeInput').value;

  if (startTime && endTime) {
      alert(`Shift Start: ${startTime}, Shift End: ${endTime}`);
      // Add logic here to handle the entered start and end times
  } else {
      alert('Please fill in both start and end times.');
  }
});

// Optional: Close modal when clicking outside of it
window.onclick = function(event) {
  var modals = document.getElementsByClassName('modal');
  for (var i = 0; i < modals.length; i++) {
      if (event.target === modals[i]) {
          closeModal(modals[i]);
      }
  }
}

// Basic client-side form validation
function validateForm() {
  var internName = document.getElementById("internID").value;
  var password = document.getElementById("InternPass").value;

  if (internName === "" || password === "") {
      alert("All fields must be filled out");
      return false;
  }
  return true;
}

