<?php
// 模拟从后端获取的数据
$accounts = [
    ["#0121", "FN","LN","1@gmail.com", "System Admin", "", 1],
    ["#0122", "FN","LN", "2@gmail.com","System Admin",  "", 2],
    ["#0123", "FN","LN", "3@gmail.com", "Business Owner", "CompanyA", 3],
    ["#0124", "FN","LN", "4@gmail.com","Business Owner",  "CompanyB", 4],
    ["#0125", "FN","LN", "5@gmail.com","Business Owner",  "CompanyC", 5],
    ["#0126", "FN","LN", "5@gmail.com","Business Owner",  "CompanyD", 5],
    ["#0127", "FN","LN", "5@gmail.com","Business Owner",  "CompanyE", 5],
    ["#0128", "FN","LN", "5@gmail.com","Business Owner",  "CompanyF", 5],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Admin Profile Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel=”stylesheet” href=”https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css” integrity=”sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm” crossorigin=”anonymous”>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!--    <script src="https://s3.pstatp.com/cdn/expire-1-M/jquery/3.0.0/jquery.min.js"></script>-->
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
        .modal-backdrop{
            z-index: 1;
        }
    </style>
</head>
<body class="bg-gray-100">
<div class="flex flex-col items-center">
    <div class="bg-blue-100 w-full p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">System Admin Account Management</h1>
        <!--        <nav aria-label="Page navigation">-->
        <!--            <ul class="pagination">-->
        <!--                <li class="page-item"><a class="page-link" href="#">Page01</a></li>-->
        <!--                <li class="page-item"><a class="page-link" href="#">Page02</a></li>-->
        <!--                <li class="page-item"><a class="page-link" href="#">Page03</a></li>-->
        <!--            </ul>-->
        <!--        </nav>-->
    </div>
    <div class="w-full max-w-6xl mt-4 bg-white shadow-md rounded-lg">
        <div class="flex justify-between p-4">
            <button id="back" class="text-2xl"><i class="fas fa-chevron-left"></i></button>
            <div class="relative">

                <input type="text" id="searchInput" placeholder="Search" class="border rounded-full py-2 px-4">
                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
            </div>
        </div>

        <style>
            .scrollable-tbody {
                max-height: 200px !important;
                overflow-y: auto !important;
            }
            .scrollable-table-container {
                max-height: 600px;
                overflow-y: auto;
            }

            thead th {
                position: sticky;
                top: 0;
                background: #f8f9fa; 
                z-index: 1;
                width:auto
                /* box-shadow: 0 2px 2px -1px rgba(0,0,0,0.4); */
            }
        

        </style>
    <div class="scrollable-table-container">
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($accounts as $index=>$account) : ?>
                    <tr class="account-row" data-account="<?= $account[0] ?>" data-info="<?= strtolower(implode(' ', $account)) ?>">
                            <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount(<?= $index ?>)"><?= $account[0] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount(<?= $index ?>)"><?= $account[1] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount(<?= $index ?>)"><?= $account[2] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount(<?= $index ?>)"><?= $account[3] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount(<?= $index ?>)"><span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800"><?= $account[4] ?></span></td>
                            <td class="px-6 py-4 whitespace-nowrap" onclick="editAccount(<?= $index ?>)"><?= $account[5] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><button onclick="editAccount(<?= $index ?>)" class="text-gray-600 hover:text-gray-900"><i class="fas fa-user-edit"></i></button></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><button onclick="suspendAccount(<?= $account[6] ?>)" class="text-gray-600 hover:text-gray-900"><i class="fas fa-minus-circle"></i></button></td>
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
        <button onclick="window.location.href='accountAdd.php'" class="text-4xl text-gray-600 hover:text-gray-900"><i class="fas fa-plus-circle"></i></button>
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
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
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
            alert('Suspend successful!');
            window.location.href = 'index.php';
        }, 500); // 延迟 500 毫秒后显示 alert
    }

    function editAccount(accountId) {
        window.location.href = 'accountUpdate.php?accountId=' + accountId;
    }

    function suspendAccount(accountId) {
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
});
</script>

</body>
</html>
