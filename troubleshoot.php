<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Troubleshoot Creation</title>
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
    <div class="flex flex-col h-screen">
        <!-- Header -->
        <div class="bg-blue-100 p-4 flex justify-between items-center">
            <span class="text-lg font-semibold">System Admin Page</span>
            <i class="fas fa-shield-alt"></i>
        </div>
        
        <!-- Title -->
        <div class="bg-blue-100 p-4">
            <h1 class="text-2xl font-bold text-center">Troubleshoot</h1>
        </div>
        <div style="padding-left: 20px;">
            <button onclick="window.location.href='index.php'" class="text-2xl mb-4"><i class="fas fa-chevron-left"></i></button>
        </div>

        
        <!-- Main Content -->
        <div class="flex-grow flex items-center justify-center">
            <div class="bg-white shadow-md rounded-lg p-8 w-96">
                <div class="flex items-center mb-4">
                    <i class="fas fa-search mr-2"></i>
                    <h2 class="text-lg font-semibold">Troubleshoot Creation</h2>
                </div>
                <form>
                    <div class="mb-4">
                        <input type="text" id="username" placeholder="Username" class="w-full p-2 border border-gray-300 rounded">
                        <span id="usernameError" class="error"></span>
                    </div>
                    <div class="mb-4">
                        <input type="email" id="email" placeholder="Email" class="w-full p-2 border border-gray-300 rounded">
                        <span id="emailError" class="error"></span>
                    </div>
                    <div class="mb-4">
                        <textarea placeholder="Description" class="w-full p-2 border border-gray-300 rounded"></textarea>
                    </div>
                    <div class="flex justify-center">
                        <button type="button" onclick="showModal()" class="bg-black text-white px-4 py-2 rounded">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="p-4 text-right">
            <button class="text-black">Logout</button>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal flex justify-center items-center">
        <div class="bg-green-500 p-8 rounded-lg shadow-lg text-center">
            <p class="text-white text-xl mb-4">Confirm Create?</p>
            <div class="flex justify-around">
                <button class="bg-black text-white py-2 px-2 rounded" onclick="confirmAction()">Confirm</button>
                <button class="bg-black text-white py-2 px-2 rounded" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        closeModal();
        
        function showModal() {
            if (validateForm()) {
                document.getElementById('myModal').style.display = 'flex';
            }
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        function confirmAction() {
            // Confirm action here
            alert('Confirm Submission?');
            closeModal();
            window.location.href='index.php';
        }

        function validateForm() {
            var isValid = true;

            var username = document.getElementById('username').value.trim();

            document.getElementById('usernameError').innerText = "";

            if (username === "") {
                document.getElementById('usernameError').innerText = "Username cannot be empty.";
                isValid = false;
            }
            return isValid;
        }

    </script>
</body>

</html>
