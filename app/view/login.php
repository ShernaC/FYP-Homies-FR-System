<?php
session_start();

include_once '../db/db.php';
include_once '../model/account.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax']) && $_POST['ajax'] == 'login') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $profile = $_POST['profile'];

    $response = array('success' => false, 'data' => '', 'profile' => '');

    try {
        if ($profile == 'System Admin') {
            $sysAdmin = new SysAdmin();
            $result = json_decode($sysAdmin->login($username, $password), true);
        } elseif ($profile == 'Business Owner') {
            $businessOwner = new BusinessOwner();
            $result = json_decode($businessOwner->login($username, $password), true);
        } else {
            $response['data'] = 'Invalid profile type';
            throw new Exception('Invalid profile type');
        }

        if (isset($result['success']) && $result['success']) {
            $_SESSION['userID'] = $result['data']['id'];
            $_SESSION['userName'] = $result['data']['userName'];
            $_SESSION['userProfile'] = $profile;

            $response['success'] = true;
            $response['data'] = $result['data']['userName'];
            $response['profile'] = $profile;
        } else {
            $response['data'] = isset($result['message']) ? $result['message'] : 'Invalid username or password';
        }
    } catch (Exception $e) {
        error_log("Error during login: " . $e->getMessage());
        $response['data'] = 'Server error. Please try again later.';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax']) && $_POST['ajax'] == 'logout') {
    session_destroy();
    $response = array('success' => true, 'data' => 'Logged out successfully');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
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
        <!-- Button for Verification -->
        <button class="btn btn-primary position-fixed top-0 start-0 mt-3 ms-3" onclick="openVerificationPage()">Verification</button>

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
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required disabled>
                <div class="invalid-feedback">
                    Please enter a valid username.
                </div>
            </div>
            
            <div class="form-group mt-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required disabled>
                <div class="invalid-feedback">
                    Please enter your password.
                </div>
            </div>

            <input type="hidden" id="profile" name="profile">
            <button type="submit" class="btn btn-primary btn-block mt-3" id="login-btn" onclick="handleLogin(event)" disabled>Login</button>
        </form>

        <div id="loginMessage" class="mt-3"></div> 
    </div>

    <script>
        function openVerificationPage() {
        window.location.href = 'endUserVerification.php'; // Change 'verification.php' to the desired PHP page
        }

        function enableLogin(element) {
            var selectedProfile = element.getAttribute('data-profile');
            document.getElementById('profileDropdown').innerText = selectedProfile;

            // Enable form elements if a user type is selected
            document.getElementById("username").disabled = false;
            document.getElementById("password").disabled = false;
            document.getElementById("login-btn").disabled = false;

            // Set hidden profile field
            document.getElementById("profile").value = selectedProfile;
        }

        function redirect(profile, username) {
            var formAction;
            switch (profile) {
                case "System Admin":
                    formAction = "sysAdminPg.php";
                    break;
                case "Business Owner":
                    formAction = "personal.php?username=" + username;
                    break;
                default:
                    formAction = "#";
            }
            console.log("Redirecting to:", formAction);
            window.location.href = formAction;
        }

        function handleLogin(event) {
            event.preventDefault(); // Prevent the default form submission

            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            var profile = document.getElementById("profile").value;

            console.log("Sending AJAX request with data:", { username, password, profile }); 

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "login.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        var loginMessage = document.getElementById("loginMessage");

                        console.log("Response:", response);

                        if (response.success) {
                            loginMessage.innerHTML = '<div class="alert alert-success">Login successful. Redirecting...</div>';
                            setTimeout(function() {
                                redirect(response.profile, response.data); 
                            }, 1000);
                        } else {
                            loginMessage.innerHTML = '<div class="alert alert-danger">' + response.data + '</div>';
                        }
                    } catch (e) {
                        console.error("Error parsing response:", e);
                        document.getElementById("loginMessage").innerHTML = '<div class="alert alert-danger">Invalid server response.</div>';
                    }
                } else {
                    console.error("Error: " + xhr.status);
                    document.getElementById("loginMessage").innerHTML = '<div class="alert alert-danger">Server error.</div>';
                }
            };
            xhr.onerror = function() {
                console.error("Request failed");
                document.getElementById("loginMessage").innerHTML = '<div class="alert alert-danger">Request failed.</div>';
            };
            xhr.send("username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password) + "&profile=" + encodeURIComponent(profile) + "&ajax=login");
        }
    </script>

</body>
</html>