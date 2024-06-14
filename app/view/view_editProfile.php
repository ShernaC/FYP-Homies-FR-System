<?php

include '../controller/profileController.php';
include '../controller/adminController.php';

if (isset($_GET['profile'])) {
    $profile = $_GET['profile'];
}

$profileController = new viewProfilePageController();
$profileData = $profileController->viewSingleProfile($profile);
$profileData = json_decode($profileData, true);
$description = $profileData['profile']['description'];

// Get accounts of the same profile type
$adminController = new viewAccountController();
if ($profile == 'System Admin') {
    $accounts = $adminController->viewSystemAdminController();
} else if ($profile == 'Business Owner') {
    $accounts = $adminController->viewBusinessOwnerController();
}
$accounts = json_decode($accounts, true)['accounts'];

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
                <h3 class="ms-4 font-medium text-gray-500 uppercase">User Profile: <span id="existingUserProfile"> <?= $profile ?></span></h3>
            </div>
            <div class="mb-4">
                <h3 class="ms-4 font-medium text-gray-500 uppercase">Description: <span id="description"></span> <?= $description ?></span></h3>
            </div>


            <div class="table-responsive">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <?php if ($profile == 'System Admin') : ?>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profile</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                        <?php elseif ($profile == 'Business Owner') : ?>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profile</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                        <?php endif; ?>
                    </thead>
                    <tbody id="view-profile-information" class="bg-white divide-y divide-gray-200">
                    <?php foreach ($accounts as $key => $account) : 
                        if ($profile== 'System Admin') : ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $account['id'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount(<?= json_encode($account['id'])?>, '<?= htmlspecialchars($account['profile'])?>')"><?= $account['userName'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount(<?= json_encode($account['id'])?>, '<?= htmlspecialchars($account['profile'])?>')"><?= $account['name'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount(<?= json_encode($account['id'])?>, '<?= htmlspecialchars($account['profile'])?>')"><?= $account['email'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount(<?= json_encode($account['id'])?>, '<?= htmlspecialchars($account['profile'])?>')"><span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800"><?= $account['profile'] ?></span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><button onclick="editAccount(<?= json_encode($account['id'])?>, '<?= htmlspecialchars($account['profile'])?>')" class="text-gray-600 hover:text-gray-900"><i class="fas fa-user-edit"></i></button></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><button onclick="suspendAccount(<?= json_encode($account['id'])?>, '<?= htmlspecialchars($account['profile'])?>')" class="text-gray-600 hover:text-gray-900"><i class="fas fa-minus-circle"></i></button></td>
                            </tr>
                    <?php elseif ($account['profile'] == 'Business Owner') : ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $account['id'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount(<?= json_encode($account['id'])?>, '<?= htmlspecialchars($account['profile'])?>')"><?= $account['userName'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount(<?= json_encode($account['id'])?>, '<?= htmlspecialchars($account['profile'])?>')"><?= $account['name'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount(<?= json_encode($account['id'])?>, '<?= htmlspecialchars($account['profile'])?>')"><?= $account['email'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount(<?= json_encode($account['id'])?>, '<?= htmlspecialchars($account['profile'])?>')"><span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800"><?= $account['profile'] ?></span></td>
                                <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount(<?= json_encode($account['id'])?>, '<?= htmlspecialchars($account['profile'])?>')"><?= $account['company'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><button onclick="editAccount(<?= json_encode($account['id'])?>, '<?= htmlspecialchars($account['profile'])?>')" class="text-gray-600 hover:text-gray-900"><i class="fas fa-user-edit"></i></button></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><button onclick="suspendAccount(<?= json_encode($account['id'])?>, '<?= htmlspecialchars($account['profile'])?>')" class="text-gray-600 hover:text-gray-900"><i class="fas fa-minus-circle"></i></button></td>
                            </tr>            
                    <?php endif; ?>
                <?php endforeach; ?>
                    </tbody>
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
  function editAccount(accountId, profile) {
        window.location.href = 'accountUpdate.php?accountId=' + accountId + '&profile=' + profile;
    }

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
