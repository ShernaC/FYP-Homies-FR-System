<?php
// 模拟从后端获取的数据
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
    <title>System Admin Profile Managemnet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel=”stylesheet” href=”https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css” integrity=”sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm” crossorigin=”anonymous”>
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
    <script src="https://s3.pstatp.com/cdn/expire-1-M/jquery/3.0.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


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
    </style>
</head>
<body class="bg-gray-100">
<div class="flex flex-col items-center">
    <div class="bg-blue-100 w-full p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">System Admin Profile Managemnet</h1>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">Page01</a></li>
                <li class="page-item"><a class="page-link" href="#">Page02</a></li>
                <li class="page-item"><a class="page-link" href="#">Page03</a></li>
            </ul>
        </nav>
    </div>
    <div class="w-full max-w-6xl mt-4 bg-white shadow-md rounded-lg">
        <div class="flex justify-between p-4">
            <button class="text-2xl"><i class="fas fa-chevron-left"></i></button>
            <div class="relative">
                <input type="text" placeholder="Search" class="border rounded-full py-2 px-4">
                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
            </div>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profile</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($accounts as $key => $account) : ?>
                <!--            <tr ondblclick="editAccount(<?php $account[5]; ?>)">-->
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap"><?= $account['id'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?= $account['userName'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?= $account['name'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?= $account['email'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800"><?= $account['profile'] ?></span></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?= ""/*$account[5]*/ ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><button onclick="suspendAccount(<?= json_encode($account['id'])?>, '<?= htmlspecialchars($account['profile'])?>')" class="text-gray-600 hover:text-gray-900"><i class="fas fa-minus-circle"></i></button></td>
                </tr>               
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        <button onclick="window.location.href='accountAdd.php'" class="text-4xl text-gray-600 hover:text-gray-900"><i class="fas fa-plus-circle"></i></button>
    </div>
    <div class="w-full max-w-6xl mt-4 text-right text-gray-500">
        <a href="troubleshoot.php" class="mr-4">Troubleshoot</a>
        <a href="#">Logout</a>
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

    function editAccount(accountId) {
        window.location.href = 'accountUpdate.php?accountId=' + accountId;
    }

    function suspendAccount(accountId, profile) {
        console.log('yes');
        storedAccountId = accountId;
        storedProfile = profile;
        $('#exampleModal').modal('show');
    }
</script>

</body>
</html>