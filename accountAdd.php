<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
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
                <h2 class="text-lg">Create Account</h2>
            </div>
        </div>
        <div class="w-full max-w-6xl mt-4 bg-white shadow-md rounded-lg p-4">
            <button onclick="window.location.href='index.php'" class="text-2xl mb-4"><i class="fas fa-chevron-left"></i></button>
            <div class="flex items-center mb-4">
                <img src="https://placehold.co/100" alt="User profile picture" class="w-16 h-16 rounded-full mr-4">
                <input type="text" id="name" placeholder="Name" class="border-b-2 flex-1 py-2 px-4">
                <span id="nameError" class="error"></span>
            </div>
            <form class="space-y-4" onsubmit="return validateForm()">
                <div>
                    <label for="accountId" class="block text-sm font-medium text-gray-700">Account ID</label>
                    <input type="text" id="accountId" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" placeholder="@username123" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <span id="usernameError" class="error"></span>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" placeholder="email@domain.com" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <span id="emailError" class="error"></span>
                </div>
                <div>
                    <label for="profile" class="block text-sm font-medium text-gray-700">Profile</label>
                    <select id="profile" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option>Profile type</option>
                    </select>
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" id="phone" placeholder="Phone number" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <span id="phoneError" class="error"></span>
                </div>
                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
                    <input type="text" id="company" placeholder="Company(optional)" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" placeholder="System Admin, Business Owner only" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <span id="passwordError" class="error"></span>
                </div>
                <div class="text-right">
                    <button type="button" onclick="showModal()" class="bg-black text-white py-2 px-4 rounded">Create</button>
                </div>
            </form>
        </div>
        <div class="w-full max-w-6xl mt-4 text-right text-gray-500">
            <a href="#" class="mr-4">Troubleshoot</a>
            <a href="#">Logout</a>
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
            alert('Account created!');
            closeModal();
            window.location.href='index.php';
        }

        function validateForm() {
            var isValid = true;

            var name = document.getElementById('name').value.trim();
            var username = document.getElementById('username').value.trim();
            var password = document.getElementById('password').value.trim();
            var email = document.getElementById('email').value.trim();
            var phone = document.getElementById('phone').value.trim();

            document.getElementById('nameError').innerText = "";
            document.getElementById('usernameError').innerText = "";
            document.getElementById('passwordError').innerText = "";
            document.getElementById('emailError').innerText = "";
            document.getElementById('phoneError').innerText = "";

            if (name === "") {
                document.getElementById('nameError').innerText = "Name cannot be empty.";
                isValid = false;
            }
            if (username === "") {
                document.getElementById('usernameError').innerText = "Username cannot be empty.";
                isValid = false;
            }
            if (password === "") {
                document.getElementById('passwordError').innerText = "Password cannot be empty.";
                isValid = false;
            }
            // Email validation
            if (!/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/.test(email)) {
                document.getElementById('emailError').innerText = "Invalid email format.";
                isValid = false;
            }
            // Phone number validation
            if (!/^\d{11}$/.test(phone)) {
                document.getElementById('phoneError').innerText = "Invalid phone number format.";
                isValid = false;
            }

            return isValid;
        }

    </script>
</body>
</html>
