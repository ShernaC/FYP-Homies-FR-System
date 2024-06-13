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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
</head>

<body id="login-page">
    <div class="login-container">
        <!--header-->
        <h2>Welcome to FaceLock!</h2>
        <!--image-->
        <img class="logo" src="images/face1.png" height="130" width="130" alt="Logo">

        <form id="loginForm" novalidate>
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
                    Please enter your username.
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
            <button type="submit" class="btn btn-primary btn-block mt-3" id="login-btn" disabled>Login</button>
        </form>

        <div id="loginMessage" class="mt-3"></div>
    </div>

    <script>
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
                    formAction = "businessOwnerPg.html?username=" + username;
                    break;
                default:
                    formAction = "#";
            }
            console.log("Redirecting to:", formAction);
            window.location.href = formAction;
        }

        $("#loginForm").submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            var username = $("#username").val();
            var password = $("#password").val();
            var profile = $("#profile").val();

            console.log("Sending AJAX request with data:", { username, password, profile });

            $.ajax({
                type: "POST",
                url: "login.php",
                data: {
                    username: username,
                    password: password,
                    profile: profile,
                    ajax: 'login'
                },
                dataType: "json", // Ensure jQuery parses response as JSON
                success: function(response) {
                    var loginMessage = $("#loginMessage");
                    console.log("Response:", response);

                    if (response.success) {
                        loginMessage.html('<div class="alert alert-success">Login successful. Redirecting...</div>');
                        setTimeout(function() {
                            redirect(response.profile, response.data);
                        }, 1000);
                    } else {
                        loginMessage.html('<div class="alert alert-danger">' + response.data + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                    console.error("Status: " + status);
                    console.error("XHR: " + xhr.responseText);
                    $("#loginMessage").html('<div class="alert alert-danger">Server error: ' + xhr.responseText + '</div>');
                }
            });
        });
    </script>
</body>
</html>