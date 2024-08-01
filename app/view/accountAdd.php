<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/lux/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
        .bottom-right {
            position: fixed;
            bottom: 10px;
            right: 10px;
        }
        .header {
            background-color: #333;
            color: white;
        }
        .has-danger .form-control.is-invalid {
            border-color: #dc3545;
        }
        .has-danger .invalid-feedback {
            display: block;
            color: #dc3545;
        }

        .btn-primary, .btn-secondary {
            text-transform: none !important; /* Ensure text is not in uppercase */
        }

        .btn-primary {
            background-color: #000 ; /* Black background */
            border-color: #000 ; /* Black border */
            color: #FFF ; 
            padding: 0.5rem 1rem;
            border-radius: 0.3rem;
            border: none;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background-color: #444 ; /* Dark gray background on hover */
            border-color: #444 ; /* Dark gray border on hover */
        }
        .fas.fa-chevron-left {
            color: #000; /* Set chevron to black */
        }
        .fas.fa-chevron-left:hover {
            color: #555; /* Change to a lighter black or dark gray on hover */
        }
    </style>
</head>
<body class="bg-gray-100">
<div class="flex flex-col items-center">
    <div class="header w-full p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-white">System Admin Account Management</h1>
    </div>
    <div class="w-full max-w-6xl mt-4 bg-white shadow-md rounded-lg p-4">
        <button onclick="window.location.href='index.php'"><i class="text-xl mb-4 fas fa-chevron-left"></i></button>
        <div class="flex items-center mb-4">
            <img src="https://placehold.co/100" alt="User profile picture" class="w-16 h-16 rounded-full mr-4">
            <input type="text" id="name" placeholder="Name" class="border-b-2 flex-1 py-2 px-4">
            <span id="nameError" class="error"></span>
        </div>
        <form class="space-y-4" onsubmit="return validateForm()">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" placeholder="username" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
                    <option value="" disabled selected>Select profile type</option>
                    <option value="System Admin">System Admin</option>
                    <option value="Business Owner">Business Owner</option>
                </select>

            </div>

            <div>
                <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
                <input type="text" id="company" placeholder="Company(optional)" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" id="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <button type="button" class="absolute inset-y-0 right-0 px-3 py-2" onclick="togglePasswordVisibility()"><i id="passwordIcon" class="fas fa-eye"></i></button>
                </div>
                <span id="passwordError" class="error"></span>
            </div>
            <div class="text-right">
                <button type="button" onclick="showModal()" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
    <div class="w-full max-w-6xl mt-3 text-right text-gray-500 bottom-right">
        <a href="troubleshoot.php" class="mr-4">Troubleshoot</a>
        <a href="login.php">Logout</a>
    </div>
</div>

<!-- Modal -->
<!--<div id="myModal" class="modal flex justify-center items-center">-->
<!--    <div class="bg-green-500 p-8 rounded-lg shadow-lg text-center">-->
<!--        <p class="text-white text-xl mb-4">Confirm Create?</p>-->
<!--        <div class="flex justify-around">-->
<!--            <button class="bg-black text-white py-2 px-2 rounded" onclick="confirmAction()">Confirm</button>-->
<!--            <button class="bg-black text-white py-2 px-2 rounded" onclick="closeModal()">Cancel</button>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel" style="color:green;">Confirm Create</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to create this account?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmAction()">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    closeModal();

    function togglePasswordVisibility() {
        var passwordInput = document.getElementById('password');
        var passwordIcon = document.getElementById('passwordIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        }
    }


    function showModal() {
        if (validateForm()) {
            $('#myModal').modal('show');
        }
    }

    function closeModal() {
        $('#myModal').modal('hide');
    }

    function confirmAction() {
        // Confirm action here
        closeModal();
        setTimeout(function() {
            // Make an AJAX request to the PHP script
            $.ajax({
                url: '../controller/adminController.php', // URL to your PHP script
                type: 'POST',
                data: {
                    action: 'create',
                    username: document.getElementById('username').value,
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    profile: document.getElementById('profile').value,
                    company: document.getElementById('company').value,
                    password: document.getElementById('password').value
                },
                success: function(response) {
                    alert('Creation successful!');
                    console.log(response); // Log the response from the server
                    window.location.href = 'index.php';
                }
            });

            alert('Account created successful!');
            window.location.href = 'index.php';
        }, 500); // 延迟 500 毫秒后显示 alert
    }

    function validateForm() {
        var isValid = true;

        var name = document.getElementById('name').value.trim();
        var username = document.getElementById('username').value.trim();
        var password = document.getElementById('password').value.trim();
        var email = document.getElementById('email').value.trim();

        document.getElementById('nameError').innerText = "";
        document.getElementById('usernameError').innerText = "";
        document.getElementById('passwordError').innerText = "";
        document.getElementById('emailError').innerText = "";

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

        return isValid;
    }

</script>
</body>

</html>