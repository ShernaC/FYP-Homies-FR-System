<?php
include_once '../controller/adminController.php';
$adminController = new viewAccountController();
$accounts = $adminController->viewAccount();
$accounts = json_decode($accounts, true)['accounts'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Admin Home Page Account Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/lux/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://s3.pstatp.com/cdn/expire-1-M/jquery/3.0.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <style>
        .bottom-right {
            position: fixed;
            bottom: 10px;
            right: 10px;
        }
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
        .modal-backdrop {
            z-index: 1;
        }
        .header {
            background-color: #333;
            color: white;
        }
    </style>
</head>

<body class="bg-gray-100">
<div class="flex flex-col items-center">
    <div class="header w-full p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-white">System Admin Account Management</h1>
    </div>
    <div class="w-full max-w-6xl mt-4 bg-white shadow-md rounded-lg">
        <div class="flex justify-between p-4">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search" class="border rounded-full py-2 px-4">
                <i class="fas fa-search absolute left-2 top-3 text-gray-400"></i>
            </div>
            <div>
                <select id="profileFilter" class="border rounded-full py-2 px-4">
                    <option value="all">All Profiles</option>
                    <option value="System Admin">System Admin</option>
                    <option value="Business Owner">Business Owner</option>
                </select>
            </div>
        </div>

        <style>
            .scrollable-tbody {
                max-height: 200px !important;
                overflow-y: auto !important;
            }
            .scrollable-table-container {
                display: block;
                max-height: 420px;
                overflow-y: auto;
            }

            thead th {
                background: #f8f9fa;
                z-index: 1;
                width: auto;
            }
        </style>

        <div class="scrollable-table-container">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profile</th>
                        <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th> -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($accounts as $key => $account) : ?>
                        <tr class="account-row" data-account="<?= $account['id'] ?>" data-profile="<?= $account['profile'] ?>" data-info="<?= strtolower(implode(' ', $account)) ?>">
                            <td class="px-6 py-4 whitespace-nowrap"><?= $account['id'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount('<?= $account['id'] ?>', '<?= $account['profile'] ?>')"><?= $account['userName'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount('<?= $account['id'] ?>', '<?= $account['profile'] ?>')"><?= $account['name'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount('<?= $account['id'] ?>', '<?= $account['profile'] ?>')"><?= $account['email'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount('<?= $account['id'] ?>', '<?= $account['profile'] ?>')"><span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800"><?= $account['profile'] ?></span></td>
                            <!-- <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><button onclick="editAccount('<?= $account['id'] ?>', '<?= $account['profile'] ?>')" class="text-gray-600 hover:text-gray-900"><i class="fas fa-user-edit"></i></button></td> -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><button onclick="suspendAccount('<?= $account['id'] ?>', '<?= $account['profile'] ?>')" class="text-gray-600 hover:text-gray-900"><i class="fas fa-minus-circle"></i></button></td>
                        </tr>            
                    <?php endforeach; ?>
                    <tr id="noAccountsFound" style="display: none;">
                        <td colspan="7" class="text-center py-4 text-black-500">No Accounts Found!</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        <button onclick="window.location.href='accountAdd.php'" class="text-4xl text-gray-600 hover:text-gray-900"><i class="bi bi-plus-circle-fill"></i></i></button>
    </div>
    <div class="w-full max-w-6xl mt-3 text-right text-gray-500 bottom-right">
        <a href="troubleshoot.php" class="mr-4">Troubleshoot</a>
        <a href="login.php">Logout</a>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel" style="color:green;">Confirm Suspend</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to suspend this account?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmAction()">Confirm</button>
            </div>
        </div>
    </div>
</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN">
</script>
<script src="https://code.jquery.com/jquery-3.7.1.js" 
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" 
        crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous">
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous">
</script>

<script>
    
    function confirmAction() {
        // Confirm action here
        $('#exampleModal').modal('hide');
        setTimeout(function() {
            // Make an AJAX request to the PHP script
            $.ajax({
                url: '../controller/adminController.php', // URL to your PHP script
                type: 'POST',
                data: {
                    action: 'suspend',
                    accountId: storedAccountId,
                    profile: storedProfile
                },
                success: function(response) {
                    alert('Suspend successful!');
                    console.log(response); // Log the response from the server
                    window.location.href = 'index.php';
                }
            });
            window.location.href = 'index.php';
        }, 500); // 延迟 500 毫秒后显示 alert
    }

    function editAccount(accountId, profile) {
        console.log(accountId, profile)
        window.location.href = 'accountUpdate.php?accountId=' + accountId + '&profile=' + profile;
    }

    function suspendAccount(accountId, profile) {
        //console.log('yes');
        storedAccountId = accountId;
        storedProfile = profile;
        $('#exampleModal').modal('show');
    }

    document.getElementById('back').addEventListener('click', function() {
        window.location.href = "sysAdminPg.php";
    });
    
    document.getElementById('searchInput').addEventListener('input', function() {
    var searchText = this.value.toLowerCase();
    var rows = document.querySelectorAll('.account-row');
    var found = false;

    rows.forEach(function(row) {
        var accountId = row.dataset.account.toLowerCase();
        var display = accountId.includes(searchText) ? 'table-row' : 'none';
        row.style.display = display;
        if (display === 'table-row') {
            found = true;
        }
    });

    document.getElementById('noAccountsFound').style.display = found ? 'none' : 'table-row';

    document.getElementById('profileFilter').addEventListener('change', function() {
        var selectedProfile = this.value.toLowerCase();
        var rows = document.querySelectorAll('.account-row');
        var found = false;

        rows.forEach(function(row) {
            var profile = row.dataset.profile.toLowerCase();
            var display = (selectedProfile === 'all' || profile === selectedProfile) ? 'table-row' : 'none';
            row.style.display = display;
            if (display === 'table-row') {
                found = true;
            }
        });

        document.getElementById('noAccountsFound').style.display = found ? 'none' : 'table-row';
    });
});
</script>

</body>
</html>


