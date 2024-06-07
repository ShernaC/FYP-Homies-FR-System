<?php
    // 模拟从后端获取的数据
    $accounts = [
        ["#0121", "FR-121", "System Admin", "2415121", "", 1],
        ["#0122", "FR-122", "System Admin", "2415122", "", 2],
        ["#0123", "FR-123", "Business Owner", "2415123", "CompanyA", 3],
        ["#0124", "FR-124", "Business Owner", "2415124", "CompanyB", 4],
        ["#0125", "FR-125", "User", "2415125", "", 5]
    ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Admin Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex flex-col items-center">
        <div class="bg-blue-100 w-full p-4 flex justify-between items-center">
            <h1 class="text-xl font-bold">System Admin Page</h1>
            <div class="flex items-center space-x-4">
                <a href="#" class="text-gray-500">Page01</a>
                <a href="#" class="text-gray-500">Page02</a>
                <a href="#" class="text-gray-500">Page03</a>
            </div>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profiles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($accounts as $account) : ?>
                        <tr ondblclick="editAccount(<?php $account[5]; ?>)">
                            <td class="px-6 py-4 whitespace-nowrap"><?= $account[0] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $account[1] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800"><?= $account[2] ?></span></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $account[3] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $account[4] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><button onclick="suspendAccount(<?php $account[5]; ?>)" class="text-gray-600 hover:text-gray-900"><i class="fas fa-minus-circle"></i></button></td>
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
    <div id="myModal" class="modal flex justify-center items-center">
        <div class="bg-red-500 p-8 rounded-lg shadow-lg text-center">
            <p class="text-white text-xl mb-4">Confirm suspend?</p>
            <div class="flex justify-around">
                <button class="bg-black text-white py-2 px-2 rounded" onclick="confirmAction()">Confirm</button>
                <button class="bg-black text-white py-2 px-2 rounded" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        closeModal();
        
        function showModal() {
            document.getElementById('myModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        function confirmAction() {
            // Confirm action here
            alert('Suspend successful!');
            closeModal();
            window.location.href='index.php';
        }

        function editAccount(accountId) {
            window.location.href = 'accountUpdate.php?accountId=' + accountId;
        }

        function suspendAccount(accountId) {
            showModal();
            // window.location.href = 'accountUpdate.php?accountId=' + accountId;
        }
    </script>
</body>
</html>
