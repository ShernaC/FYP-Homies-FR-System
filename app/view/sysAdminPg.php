<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>

    <title>System Admin Home Page</title>

    <style>
        .fixed-top-heading {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 30px 0; 
            z-index: 1000; 
            text-align: center;
            background-color: #d2e3fc; 
        }

        .custom-img-size {
            width: 100px;
            height: 100px;
        }

        .bottom-right {
            position: fixed;
            bottom: 10px;
            right: 10px;
        }
    </style>
</head>

<body id="system-admin" class="flex justify-center items-center h-screen bg-gray-100">
    <div class="flex flex-col items-center">
        <h3 class="fixed-top-heading text-xl font-bold">System Admin Page</h3>
        <div class="flex w-full justify-center mt-25 space-x-12">

            <div class="flex flex-col items-center m-8">
                <img src="images/fileicon.png" class="custom-img-size mb-3" alt="User Profile Management">
                <button id="profile" class="btn btn-primary w-full">User Profile Management</button>   
            </div>

            <div class="flex flex-col items-center m-8">
                <img src="images/accicon.png" class="custom-img-size mb-3" alt="User Account Management">
                <button id="account" class="btn btn-primary w-full">User Account Management</button>
            </div>
        </div>
    </div>

    <div class="w-full max-w-6xl mt-3 text-right text-gray-500 bottom-right">
        <a href="troubleshoot.php" class="mr-4">Troubleshoot</a>
        <a href="login.php">Logout</a>
    </div>
    
    <script>
        document.getElementById('profile').addEventListener('click', function() {
            window.location.href = "userProfile.php";
        });

        document.getElementById('account').addEventListener('click', function() {
            window.location.href = "test.php"; /** changed from test.php to index.php */
        });
    </script>
</body>
</html>
