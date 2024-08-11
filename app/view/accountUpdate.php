<?php
include_once '../controller/adminController.php';

$account = null;
if (isset($_GET['accountId'])) {
    $accountId = $_GET['accountId'];
    $profile = $_GET['profile'];

    $adminController = new searchUserAccount();
    $account = $adminController->handleSearchRequest($accountId, $profile);
}

if ($account) {
    $username = $account['userName'];
    $name = $account['name'];
    $email = $account['email'];

    if ($profile === 'Business Owner') {
        $company = $account['company'];
    } else {
        $company = "";
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Update Account</title>
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
        <button onclick="window.location.href='index.php'" class="text-2xl mb-4"><i class="fas fa-chevron-left"></i></button>

        <div class="flex items-center mb-4">
            <img src="https://placehold.co/100" alt="User profile picture" class="w-16 h-16 rounded-full mr-4">
            <input value="<?php echo $name; ?>" type="text" id="name" placeholder="Name" class="border-b-2 flex-1 py-2 px-4">
            <span id="nameError" class="error"></span>
        </div>
        <form class="space-y-4" onsubmit="return validateForm()">
            <div>
                <label for="accountId" class="block text-sm font-medium text-gray-700">Account ID</label>
                <input value="<?php echo $accountId; ?>" type="text" id="accountId" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" disabled>
            </div>
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input value="<?php echo $username; ?>" type="text" id="username" placeholder="@username123" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <span id="usernameError" class="error"></span>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input value="<?php echo $email; ?>" type="email" id="email" placeholder="email@domain.com" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <span id="emailError" class="error"></span>
            </div>
            <div>
                <label for="profile" class="block text-sm font-medium text-gray-700">Profile</label>
                <select id="profile" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="System Admin"<?php echo ($profile === 'System Admin')? 'selected' : '';?>>System Admin</option>
                    <option value="Business Owner"<?php echo ($profile === 'Business Owner')? 'selected' : '';?>>Business Owner</option>
                </select>
            </div>
            <div>
                <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
                <input value="<?php echo $company; ?>" type="text" id="company" placeholder="Company(optional)" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label> 
                    <input type="checkbox" id="toggleCheckbox"> Change Password?
                </label>
            </div>

            <!-- New Password -->
            <div class="d-none" id="newpassword">
                <label for="password" class="block text-sm font-medium text-gray-700">New Password:</label>
                <div class="relative">
                    <input type="password" id="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <button type="button" class="absolute inset-y-0 right-0 px-3 py-2" onclick="togglePasswordVisibility()"><i id="passwordIcon" class="fas fa-eye"></i></button>
                </div>
                <span id="passwordError" class="error"></span>
            </div>
            <br>

            <!-- Confirm Password -->
            <div class="d-none" id="confirmpassword">
                <label for="password2" class="block text-sm font-medium text-gray-700">Confirm Password:</label>
                <div class="relative">
                    <input type="password" id="password2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <button type="button" class="absolute inset-y-0 right-0 px-3 py-2" onclick="togglePasswordVisibility()"><i id="passwordIcon" class="fas fa-eye"></i></button>
                </div>
                <span id="passwordError2" class="error"></span>
            </div>

            <!-- Change company code -->
            <div>
                <label> 
                    <input type="checkbox" id="toggleCodeCheckbox"> Change Code?
                </label>
            </div>
            <!-- New Employee Password -->
            <div class="d-none" id="newcode">
                <label for="password3" class="block text-sm font-medium text-gray-700">New Code:</label>
                <div class="relative">
                    <input type="password" id="password3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <button type="button" class="absolute inset-y-0 right-0 px-3 py-2" onclick="togglePasswordVisibility()"><i id="passwordIcon" class="fas fa-eye"></i></button>
                </div>
                <span id="passwordError3" class="error"></span>
            </div>
            <br>
            <!-- Confirm Password -->
            <div class="d-none" id="confirmcode">
                <label for="password4" class="block text-sm font-medium text-gray-700">Confirm Code:</label>
                <div class="relative">
                    <input type="password" id="password4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <button type="button" class="absolute inset-y-0 right-0 px-3 py-2" onclick="togglePasswordVisibility()"><i id="passwordIcon" class="fas fa-eye"></i></button>
                </div>
                <span id="passwordError3" class="error"></span>
            </div>

            <div class="text-right">
                <button type="button" onclick="showModal()" class="bg-black text-white py-2 px-4 rounded">Update</button>
            </div>
        </form>
    </div>
    <div class="w-full max-w-6xl mt-3 text-right text-gray-500 bottom-right">
        <a href="troubleshoot.php" class="mr-4">Troubleshoot</a>
        <a href="login.php">Logout</a>
    </div>
</div>

<!-- Modal -->

<div class="modal fade content-wrapper" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Confirm Update</h6>
                <button type="button" class="close" aria-label="Close" onclick="$('#myModal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            Are you sure you want to update this account?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmAction(accountId, username, name, email, profile, company, password)">Confirm</button>
            </div>
        </div>
    </div>
</div>

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
    closeModal();

    console.log('Account ID: <?php echo $accountId; ?>');
    console.log('Username: <?php echo $username; ?>');
    console.log('Name: <?php echo $name; ?>');
    console.log('Email: <?php echo $email; ?>');
    console.log('Profile: <?php echo $profile; ?>');
    console.log('Company: <?php echo $company; ?>');
    

    function showModal() {
        if (validateForm()) {
            $('#myModal').modal('show');
        }
    }

    function closeModal() {
        $('#myModal').modal('hide');
    }    

    function confirmAction() {
        // Collect form data
        var accountId = "<?php echo $accountId; ?>";
        var username = document.getElementById('username').value.trim();
        var name = document.getElementById('name').value.trim();
        var email = document.getElementById('email').value.trim();
        var profile = document.getElementById('profile').value;
        if (document.getElementById('toggleCheckbox').checked) {
            var password = document.getElementById('password').value.trim();
        }
        var company = (profile === 'Business Owner') ? document.getElementById('company').value.trim() : "";
        if (document.getElementById('toggleCodeCheckbox').checked) {
            var company_code = document.getElementById('password3').value.trim();
        }
        // Close the modal
        closeModal();

        // Make an AJAX request to the PHP script        
        $.ajax({
            url: '../controller/adminController.php',
            type: 'POST',
            data: {
                action: 'update',
                accountId: accountId,
                profile: profile,
                username: username,
                name: name,
                email: email,
                password: password,
                company: company,
                company_code: company_code
            },
            success: function(response) {
                alert('Account updated successfully!');
                console.log(response);
                window.location.href = 'index.php';
            }
        });
      
    }

    function validateForm() {
        var isValid = true;

        var name = document.getElementById('name').value.trim();
        var username = document.getElementById('username').value.trim();
        var password = document.getElementById('password').value.trim();
        var confirmPassword = document.getElementById('password2').value.trim();
        var email = document.getElementById('email').value.trim();
        var newcode = document.getElementById('password3').value.trim();
        var confirmcode = document.getElementById('password4').value.trim();
        document.getElementById('nameError').innerText = "";
        document.getElementById('usernameError').innerText = "";
        document.getElementById('passwordError').innerText = "";
        document.getElementById('passwordError2').innerText = "";
        document.getElementById('emailError').innerText = "";
        document.getElementById('passwordError3').innerText = "";

        var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$%*?&!])[A-Za-z\d@$%*?&!]{8,}$/;


        if (name === "") {
            document.getElementById('nameError').innerText = "Name cannot be empty.";
            isValid = false;
        }
        if (username === "") {
            document.getElementById('usernameError').innerText = "Username cannot be empty.";
            isValid = false;
        }
        if (password === "" && passwordRegex.test(password)) {
            document.getElementById('passwordError').innerText = "Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long..";
            isValid = false;
        }
        if (confirmPassword !== password) {
            document.getElementById('passwordError2').innerText = "Passwords do not match.";
            isValid = false;
        }
        if (confirmcode !== newcode) {
            document.getElementById('passwordError3').innerText = "Codes do not match.";
            isValid = false;
        }
        // Email validation
        if (!/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/.test(email)) {
            document.getElementById('emailError').innerText = "Invalid email format.";
            isValid = false;
        }

        return isValid;
    }

    function togglePasswordVisibility() {
        var passwordInput = document.getElementById('password');
        var passwordInput2 = document.getElementById('password2');
        var passwordIcon = document.getElementById('passwordIcon');
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passwordIcon.classList.remove("fa-eye");
            passwordIcon.classList.add("fa-eye-slash");
        } else if (passwordInput2.type === "password") {
            passwordInput2.type = "text";
            passwordIcon.classList.remove("fa-eye");
            passwordIcon.classList.add("fa-eye-slash");
        } else if (passwordInput.type !== "password"){
            passwordInput.type = "password";
            passwordIcon.classList.remove("fa-eye-slash");
            passwordIcon.classList.add("fa-eye");
        } else {
            passwordInput2.type = "password";
            passwordIcon.classList.remove("fa-eye-slash");
            passwordIcon.classList.add("fa-eye");
        }
    }

    document.getElementById('toggleCheckbox').addEventListener('change', function() {
        var newPassword = document.getElementById('newpassword');
        var confirmPassword = document.getElementById('confirmpassword');
        if (this.checked) {
            newPassword.classList.remove('d-none');
            confirmPassword.classList.remove('d-none');
        } else {
            newPassword.classList.add('d-none');
            confirmPassword.classList.add('d-none');
        }
    });

    document.getElementById('toggleCodeCheckbox').addEventListener('change', function() {
        var newcode = document.getElementById('newcode');
        var confirmcode = document.getElementById('confirmcode');
        if (this.checked) {
            newcode.classList.remove('d-none');
            confirmcode.classList.remove('d-none');
        } else {
            newcode.classList.add('d-none');
            confirmcode.classList.add('d-none');
        }
    });



</script>
</body>
</html>
