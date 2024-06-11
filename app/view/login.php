<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">

</head>

<body id="login-page">
    <div class="login-container">
        <!--header-->
        <h2>Welcome to FaceLock!</h2>
        <!--image-->
        <img class="logo" src="images/face1.png" height="130" width="130" alt="Logo">

        <form method="post" action="login.php" id="loginForm" novalidate>
            <div class="dropdown text-center mt-3">
                <button type="button" class="btn btn-primary dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown">
                    Select Profile
                </button>
                <div class="dropdown-menu dropdown-menu-center">
                    <a class="dropdown-item" href="#" data-profile="System Admin" onclick="enableLogin(this)">System Admin</a>
                    <a class="dropdown-item" href="#" data-profile="Business Owner" onclick="enableLogin(this)">Business Owner</a>
                </div>
            </div>

            <div class="form-group mt-3">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required disabled>
                <div class="invalid-feedback">
                    Please enter a valid email address.
                </div>
            </div>
            
            <div class="form-group mt-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required disabled>
                <div class="invalid-feedback">
                    Please enter your password.
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-3" id="login-btn" onclick="handleLogin()" disabled>Login</button>
        </form>
    </div>

    <script>
        function enableLogin(element) {
            var selectedProfile = element.getAttribute('data-profile');
            document.getElementById('profileDropdown').innerText = selectedProfile;

            // Enable form elements if a user type is selected
            document.getElementById("email").disabled = false;
            document.getElementById("password").disabled = false;
            document.getElementById("login-btn").disabled = false;
        }

        function redirect(profile, email) {
            var formAction;
            switch (profile) {
                case "System Admin":
                    formAction = "sysAdminPg.php";
                    break;
                case "Business Owner":
                    formAction = "businessOwnerPg.html?email=" + email;
                    break;
                default:
                    formAction = "#";
            }
            window.location.href = formAction;
        }

        function handleLogin() {
            event.preventDefault(); // Prevent the default form submission

            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var profile = document.getElementById("profileDropdown").innerText;

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../controller/loginController.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert("Login Successful!")
                        redirect(profile, response.data);
                    } else {
                        alert(response.data);
                    }
                } else {
                    console.error("Error: " + xhr.status);
                }
            };
            xhr.send("email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password) + "&data-profile=" + encodeURIComponent(profile));
        };
    </script>

</body>
</html>
