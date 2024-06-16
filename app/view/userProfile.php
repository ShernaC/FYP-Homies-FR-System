
<?php
include_once '../controller/profileController.php';
$profileController = new viewProfilePageController();
$profiles = $profileController->viewProfile();
$profiles = json_decode($profiles, true)['profiles'];


// $profileController = new viewProfilePageController();
// $profileData = $profileController->viewSingleProfile($profile);
// $profileData = json_decode($profileData, true);
// $description = $profileData['profile']['description'];
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

    <title>User Profile Management</title>

    <style>
        .custom-img-size {
            width: 100px;
            height: 100px;
        }

        .bottom-right {
            position: fixed;
            bottom: 10px;
            right: 10px;
        }
    </style>
</head>

<body id="system-admin-profile" class="bg-gray-100">
    <div class="flex flex-col items-center">
        <div class="bg-blue-100 w-full p-4 flex justify-between items-center">
            <h1 class="text-xl font-bold">System Admin Profile Management</h1>
        </div>
        <div class="w-full max-w-6xl mt-4 bg-white shadow-md rounded-lg">
            <div class="flex justify-between p-4">
                <button id="back" class="text-2xl"><i class="fas fa-chevron-left"></i></button>
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search" class="border rounded-full py-2 px-4">
                    <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                </div>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profiles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($profiles as $key => $profile) : ?>
                        <tr class="profile-row">
                            <td class="px-6 py-4 whitespace-nowrap" onclick="editProfile('<?php echo htmlspecialchars($profile['userProfile']); ?>')">
                                <?php echo htmlspecialchars($profile['userProfile']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="w-full max-w-6xl mt-4 text-right text-gray-500 bottom-right">
        <a href="troubleshoot.php" class="mr-4">Troubleshoot</a>
        <a href="login.php">Logout</a>
    </div>
    
    <script>
        function editProfile(profile) {
            window.location.href = 'view_editProfile.php?profile=' + profile;
        }

        document.getElementById('back').addEventListener('click', function() {
            window.location.href = "sysAdminPg.php";
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            var searchText = this.value.toLowerCase();
            var rows = document.querySelectorAll('.profile-row');
            
            rows.forEach(function(row) {
                var profileName = row.dataset.profile.toLowerCase();
                var display = profileName.includes(searchText) ? 'table-row' : 'none';
                row.style.display = display;
            });
        });
    </script>

</body>
</html>
