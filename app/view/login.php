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
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/lux/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://s3.pstatp.com/cdn/expire-1-M/jquery/3.0.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
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

         <!-- Tabs for selecting login type -->
         <ul class="nav nav-tabs mt-4" id="loginTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="admin-login-tab" data-toggle="tab" href="#admin-login" role="tab" aria-controls="admin-login" aria-selected="true">Admin/Business Owner Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="employee-login-tab" data-toggle="tab" href="#employee-login" role="tab" aria-controls="employee-login" aria-selected="false">Employee Login</a>
            </li>
        </ul>

        <div class="tab-content" id="loginTabContent">
            <div class="tab-pane fade show active" id="admin-login" role="tabpanel" aria-labelledby="admin-login-tab">
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
            </div>
            
            <!-- Employee Login Form -->
            <div class="tab-pane fade" id="employee-login" role="tabpanel" aria-labelledby="employee-login-tab">
                <form method="post" action="login.php" id="employeeLoginForm" novalidate>
                    <div class="form-group mt-3">
                        <label for="companyName">Company Name</label>
                        <input type="text" class="form-control" id="companyName" name="companyName" required>
                        <div class="invalid-feedback">
                            Please enter a valid company name.
                        </div>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label for="employeePassword">Password</label>
                        <input type="password" class="form-control" id="employeePassword" name="password" required>
                        <div class="invalid-feedback">
                            Please enter your password.
                        </div>
                    </div>

                    <input type="hidden" id="employee" name="employee" value="Employee">
                    <button type="submit" class="btn btn-primary btn-block mt-3" onclick="handleEmployeeLogin(event)">Login</button>
                </form>
            </div>
        </div>
        
        <div id="loginMessage" class="mt-3"></div> 
    </div>

    <!-- OTP Modal -->
    <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="otpModalLabel">Enter OTP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>An OTP has been sent to your email. Please enter it below:</p>
                    <input type="text" class="form-control" id="otpInput" placeholder="Enter OTP">
                    <div class="invalid-feedback">
                        Invalid OTP. Please try again.
                    </div><br>
                    <div class="timer" id="timer">Time remaining: 05:00</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="validateOTP()">Submit</button>
                    <button type="button" class="btn btn-link" id="resendOTPButton" disabled>Resend OTP</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var curr_email = "";

        document.addEventListener("DOMContentLoaded", function() {
            var otpModalElement = document.getElementById('otpModal');
            
            if (otpModalElement) {
                var otpModal = new bootstrap.Modal(otpModalElement);

                otpModalElement.addEventListener('shown.bs.modal', function () {
                    sendOTP();
                });
            }
        });

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
                    formAction = "index.php";
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
            xhr.open("POST", "../controller/loginController.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        var loginMessage = document.getElementById("loginMessage");
                        
                        // save email
                        curr_email = response.data.email;

                        console.log("Response:", response);
                        console.log("email: ", curr_email);

                        if (response.success) {
                            // Show OTP modal
                            var otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
                            otpModal.show();
                        } else {
                            loginMessage.innerHTML = '<div class="alert alert-danger">' + response.message + '</div>';
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
            xhr.send("username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password) + "&profile=" + encodeURIComponent(profile) + "&action=login");
        }

        function handleEmployeeLogin(event) {
            event.preventDefault();
            var companyName = document.getElementById('companyName').value;
            var password = document.getElementById('employeePassword').value;
            console.log('companyName:', companyName);
            console.log('password:', password);

            $.ajax({
                url: '../controller/employeeLoginController.php',
                type: 'POST',
                data: {
                    action: 'login',
                    username: companyName,
                    password: password
                },
                success: function(response) {
                    console.log('Sending ajax request, Response:', response);
                    console.log(response.success);
                    response = JSON.parse(response);
                    if (response.success) {
                        console.log(companyName);
                        window.location.href = '../ImageCheckingSystem/CaptureCode.html';
                    } else {
                        $('#loginMessage').html('<div class="alert alert-danger">' + response.data + '</div>');
                    }
                },
                error: function() {
                    console.log('Error during login');
                    $('#loginMessage').html('<div class="alert alert-danger">Error during login. Please try again later.</div>');
                }
            });
        }

        function startTimer(duration, display) {
            let timer = duration, minutes, seconds;
            setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = "Time remaining: " + minutes + ":" + seconds;

                if (--timer < 0) {
                    timer = 0;
                    // Handle timer expiry here if needed
                }
            }, 1000);
        }

        window.onload = function () {
            const time = 60 * 5, // 5 minutes in seconds
                display = document.querySelector('#timer');
            startTimer(time, display);
        };

        //OTP related functions
        function validateOTP() {
            var otp = document.getElementById('otpInput').value;
            var username = document.getElementById("username").value;
            var profile = document.getElementById("profile").value;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../controller/loginController.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    console.log('response:', response);
                    if (response.success) {
                        redirect(profile, username);
                    } else {
                        document.getElementById('otpInput').classList.add('is-invalid');
                    }
                } else {
                    document.getElementById('otpInput').classList.add('is-invalid');
                }
            };
            xhr.onerror = function() {
                document.getElementById('otpInput').classList.add('is-invalid');
            };
            xhr.send("otp=" + encodeURIComponent(otp) + "&action=validate_otp");
        }

        function resendOTP() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../controller/loginController.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert('OTP has been resent to your email.');
                        startResendOTPTimer();
                    } else {
                        alert('Failed to resend OTP. Please try again.');
                    }
                } else {
                    alert('An error occurred. Please try again.');
                }
            };
            xhr.onerror = function() {
                alert('An error occurred. Please try again.');
            };
            // xhr.send("action=resend_otp");
            xhr.send("email=" + encodeURIComponent(curr_email) + "&action=send_otp");
        }

        function sendOTP() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../controller/loginController.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert('OTP has been sent to your email.');
                        startResendOTPTimer(); // Start the resend timer if needed
                    } else {
                        alert('Failed to send OTP. Please try again.');
                    }
                } else {
                    alert('An error occurred. Please try again.');
                }
            };
            xhr.onerror = function() {
                alert('An error occurred. Please try again.');
            };
            xhr.send("email=" + encodeURIComponent(curr_email) + "&action=send_otp");
        }


        // Function to handle OTP modal functionality
        function handleOTPModal() {
            console.log("handling otp modal")
            var otpModal = document.getElementById('otpModal');

            // Add event listener to the modal to send OTP immediately when shown
            otpModal.addEventListener('shown.bs.modal', function () {
                sendOTP();
            });
        }

        function startResendOTPTimer() {
            var resendButton = document.getElementById('resendOTPButton');
            var timer = 3; //change back to 15
            
            resendButton.disabled = true;
            resendButton.classList.add('disabled');
            resendButton.innerHTML = 'Resend OTP (' + timer + ')';
            
            var countdown = setInterval(function() {
                timer--;
                resendButton.innerHTML = 'Resend OTP (' + timer + ')';
                
                if (timer <= 0) {
                    clearInterval(countdown);
                    resendButton.disabled = false;
                    resendButton.classList.remove('disabled');
                    resendButton.innerHTML = 'Resend OTP';
                }
            }, 1000);
        }
        
        // Add event listener to the modal to start the timer when it is shown
        document.getElementById('otpModal').addEventListener('shown.bs.modal', startResendOTPTimer);

        document.getElementById('resendOTPButton').addEventListener('click', resendOTP);
        // document.getElementById('otpSubmitButton').addEventListener('click', validateOTP);
        
    </script>
</body>
</html>

