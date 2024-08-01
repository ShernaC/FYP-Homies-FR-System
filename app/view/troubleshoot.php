<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Troubleshoot Creation</title>
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
<div class="flex flex-col h-screen">

    <!-- Title -->
    <div class="header w-full p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-white">System Admin Account Management</h1>
    </div>
    
    <!-- Main Content -->
    <div class="flex-grow flex items-center justify-center">
        <div class="bg-white shadow-md rounded-lg p-8 w-96">
            <div class="flex items-center mb-4">
                <!-- <i class="fas fa-search mr-2"></i> -->
                <button onclick="window.location.href='sysAdminPg.php'"><i class="fas fa-chevron-left mr-3"></i></button>
                <h2 class="text-lg font-semibold">Troubleshoot Request</h2>
            </div>
            <form>
                <div class="mb-4">
                    <input type="text" id="accountid" placeholder="Account ID" class="w-full p-2 border border-gray-300 rounded">
                    <span id="idError" class="error"></span>
                </div>
                <!-- <div class="mb-4">
                    <input type="email" id="email" placeholder="Email" class="w-full p-2 border border-gray-300 rounded">
                    <span id="emailError" class="error"></span>
                </div> -->
                <div class="mb-4">
                    <textarea placeholder="Description" class="w-full p-2 border border-gray-300 rounded"></textarea>
                </div>
                <div class="flex justify-center">
                    <button class="btn btn-primary" type="button" onclick="showModal()" class="bg-black text-white px-4 py-2 rounded">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <div class="w-full max-w-6xl mt-3 text-right text-gray-500 bottom-right">
        <a href="login.php">Logout</a>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel" style="color:green;">Confirm submission</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            Are you sure you want to submit this troubleshoot request?
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

    function showModal() {
        if (validateForm()) {
            // document.getElementById('myModal').style.display = 'flex';
            $('#myModal').modal('show');
        }
    }

    function closeModal() {
        $('#myModal').modal('hide');
    }

    function confirmAction() {
        // Confirm action here
        $('#myModal').modal('hide');
        setTimeout(function() {
            alert('Submit successful!');
            window.location.href = 'index.php';
        }, 500); // 延迟 500 毫秒后显示 alert
    }

    function validateForm() {
        var isValid = true;

        var accountid = document.getElementById('accountid').value.trim();

        document.getElementById('idError').innerText = "";

        if (accountid === "") {
            document.getElementById('idError').innerText = "Account ID cannot be empty.";
            isValid = false;
        }
        return isValid;
    }

</script>
</body>

</html>
