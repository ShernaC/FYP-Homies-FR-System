<?php
// editProfile.php

if (isset($_GET['profile'])) {
    $profileName = $_GET['profile'];
    
    // Here, you would typically retrieve the profile data from a database.
    // For this example assuming a simple associative array of profile data.
    $profiles = [
        "System Admin" => [
            "name" => "System Admin",
            "description" => "Administrator of the system."
        ],
        "Business Owner" => [
            "name" => "Business Owner",
            "description" => "Owner of a business."
        ]
    ];
    
    // Check if the profile exists in the array
    if (array_key_exists($profileName, $profiles)) {
        $profile = $profiles[$profileName];
        
        // Update the description if a new description is provided
        if (isset($_POST['description']) && !empty($_POST['description'])) {
            $newDescription = $_POST['description'];
            $profile['description'] = $newDescription;
        }
    } else {
        // Handle the case where the profile does not exist
        $error = "Profile not found.";
    }
} else {
    // Handle the case where the profile parameter is missing
    $error = "No profile specified.";
}

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


    <title>Edit Profile</title>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        .error {
            color: red;
            font-size: 0.875rem;
        }
        .modal-backdrop{
            z-index: 1;
        }

        .bottom-right {
            position: fixed;
            bottom: 10px;
            right: 10px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="profile-container flex flex-col items-center">
        <div class="bg-blue-100 w-full p-4 flex justify-between items-center">
            <h1 class="text-xl font-bold">System Admin Profile Management</h1>
            <div class="flex items-center space-x-4">
                <h2 class="text-lg">View Profile</h2>
            </div>
        </div>

        <div class="w-full max-w-6xl mt-4 bg-white shadow-md rounded-lg">
            <div class="flex justify-between p-4">
                <button id="back" class="text-2xl"><i class="fas fa-chevron-left"></i></button>
                <div class="relative">
                    <!-- Update button -->
                    <button id="updatebtn" type="button" class="btn btn-primary update-btn" data-bs-toggle="modal" data-bs-target="#updateDescriptionModal">
                        Update
                    </button>

                </div>
            </div>

            <div class="mb-4">
                <h3 class="ms-4 font-medium text-gray-500 uppercase">User Profile: <span id="existingUserProfile"> <?php echo isset($profile) ? htmlspecialchars($profile['name']) : ''; ?></span></h3>
            </div>
            <div class="mb-4">
                <h3 class="ms-4 font-medium text-gray-500 uppercase">Description: <span id="description"></span> <?php echo isset($profile) ? htmlspecialchars($profile['description']) : ''; ?></span></h3>
            </div>


            <div class="table-responsive">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profile</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody id="view-profile-information"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!--Modal-->
    <div class="modal fade" id="updateDescriptionModal" tabindex="-1" aria-labelledby="updateDescriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateDescriptionModalLabel">Update Description</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateDescriptionForm">
                <div class="mb-3">
                    <label for="newDescription" class="form-label">New Description</label>
                    <textarea class="form-control" id="newDescription" name="newDescription" rows="3"></textarea>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitUpdate()">Save changes</button>
            </div>
            </div>
        </div>
    </div>


    <div class="w-full max-w-6xl mt-4 text-right text-gray-500 bottom-right">
        <a href="troubleshoot.php" class="mr-4">Troubleshoot</a>
        <a href="login.php">Logout</a>
    </div>
</body>

<script>
  // Function to parse URL parameters using regex
  var userProfile = new URLSearchParams(window.location.search).get('userProfile');

  // Function to display the information based on user id
  window.onload = function () {
    // Existing code for displaying user profile

    // Get and display userProfile
    document.getElementById("current_userProfile").innerHTML = userProfile;

    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Configure the request
    xhr.open("POST", "../viewProfilePageController.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Define what happens on successful data submission/ if send and receive successful, alert response
    xhr.onload = function () {
      if (xhr.status >= 200 && xhr.status < 300) {
        // Handle successful response
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          // Access and display search results
          var viewData = response.data;
          if (viewData && viewData.length > 0) {
            // Clear previous search results
            var profileInfo = document.getElementById("view-profile-information");
            profileInfo.innerHTML = "";

            // Display profile information
            for (var i = 0; i < viewData.length; i++) {
              var profile = viewData[i];
              var output = '<tr>' +
                '<th scope="row">' + profile.account_id + '</th>' +
                '<td>' + profile.firstName + '</td>' +
                '<td>' + profile.lastName + '</td>' +
                '<td>' + profile.email + '</td>' +
                '<td>' + profile.profile + '</td>' +
                '<td>' + profile.company + '</td></tr>';
              profileInfo.innerHTML += output;
            }
          }
        } else {
          // No view results found (theoretically impossible, for debugging only)
          document.getElementById("view-profile-information").innerHTML = "<tr><td colspan='8' style='padding-top: 20px;'>No results found</td></tr>";
        }
      } else {
        // Handle error response
        console.error("Error: " + xhr.status);
      }
    };

    // Construct the form data string
    var formData = "userProfile=" + encodeURIComponent(userProfile);

    // Send the request with the form data
    xhr.send(formData);
  };

  // Event listener for the back button
  document.getElementById('back').addEventListener('click', function() {
    window.location.href = "userProfile.php";
  });

  // Function to submit the update description form
  function submitUpdate() {
    var newDescription = document.getElementById("newDescription").value;
    if (newDescription.trim() !== "") {
      var updateRequest = new XMLHttpRequest();
      updateRequest.open("POST", "view_editProfile.php", true);
      updateRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      updateRequest.onreadystatechange = function() {
        if (updateRequest.readyState === XMLHttpRequest.DONE) {
          if (updateRequest.status === 200) {
            // Handle successful update
            alert("Description updated successfully!");
            // Optionally, update the description displayed on the page
            document.getElementById("description").innerHTML = newDescription;
            // Close the modal
            var modal = bootstrap.Modal.getInstance(document.getElementById("updateDescriptionModal"));
            modal.hide();
          } else {
            // Handle error
            alert("Error occurred while updating description.");
          }
        }
      };
      updateRequest.send("profile=" + encodeURIComponent(userProfile) + "&description=" + encodeURIComponent(newDescription));
    } else {
      alert("Description cannot be empty.");
    }
  }
</script>


</html>
