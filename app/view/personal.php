<?php
include_once '../controller/businessOwnerController.php';
include_once '../controller/subscriptionController.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];
}

$businessOwner = new searchBusinessOwnerAccount();
$userData = $businessOwner->handleSearchRequest($username);

$accountId = $userData['id'];
$name = $userData['name'];
$subscriptId = $userData['subscription_id'];

$subscription = new viewSubscriptionController();
$subscriptionData = $subscription->viewSubscription($subscriptId);
$subscriptionPrice = $subscriptionData['price'];
$subscriptionDescription = $subscriptionData['description'];

$list=[
    ['icon'=>'icon-tags', 'label'=>'Username:', 'value'=>$userData['userName'], 'img'=>'../view/images/id-card-regular.svg'],
    ['icon'=>'icon-envelope', 'label'=>'Email:', 'value'=>$userData['email'], 'img'=>'../view/images/envelope-regular.svg'],
    ['icon'=>'icon-zoom-in', 'label'=>'Subscription Type:','value'=>$subscriptionData['name'], 'img'=>'../view/images/calendar-days-regular.svg'],
    ['icon'=>'icon-home', 'label'=>'Company Name:', 'value'=>$userData['company'], 'img'=>'../view/images/building-regular.svg'],
];

// Test data
// $username = "testuser";
// $name = "John Doe";
// $subscriptId = "12345";
// $list = [
//     ['icon'=>'icon-tags', 'label'=>'Username:', 'value'=>'testuser', 'img'=>'images/id-card-regular.svg'],
//     ['icon'=>'icon-envelope', 'label'=>'Email:', 'value'=>'testuser@example.com', 'img'=>'images/envelope-regular.svg'],
//     ['icon'=>'icon-zoom-in', 'label'=>'Subscription Type:','value'=>'Premium', 'img'=>'images/calendar-days-regular.svg'],
//     ['icon'=>'icon-home', 'label'=>'Company Name:', 'value'=>'Test Company', 'img'=>'images/building-regular.svg'],
// ];

?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src='../view/public.js'></script>
    <title>Business Owner Home Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/lux/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .profile-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }
        .profile-header {
            background-color: #333;
            color: white;
            padding: 1rem;
            border-radius: 0.5rem 0.5rem 0 0;
            text-align: center;
            font-size: 1.25rem;
        }
        .profile-content {
            padding: 2rem;
            font-size: 1.10rem;
        }
        .profile-content img {
            width: 20px;
            height: 20px;
            margin-right: 0.5rem;
        }
        .profile-content .item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .profile-content .item i {
            margin-left: 0.5rem;
        }
        .profile-content button {
            background-color: #000;
            color: #FFF;
            padding: 0.5rem 1rem;
            border-radius: 0.3rem;
            border: none;
            margin-right: 1rem;
            font-size: 1rem;
        }
        .profile-content .spinner-border {
            display: none;
        }
        .profile-content .spinner-border.show {
            display: inline-block;
        }
        .modal-header {
            background-color: #333;
            color: white;
        }
        .bottom-right {
            position: fixed;
            bottom: 10px;
            right: 10px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-primary {
            background-color: #333; /* Lighter black for the primary button */
            border-color: #333; /* Match the border color */
        }
        .btn-primary:hover {
            background-color: #444; /* Even lighter black or dark gray on hover */
            border-color: #444; /* Match the border color */
        }
        .btn-secondary {
            text-transform: none !important;
        }
    </style>
</head>
<body>
<div class="profile-container">
    <div class="profile-header">
        <h2 class="font-bold text-white">Profile Page</h2>
    </div>
    <div class="profile-content">
        <div class="item">
            <i>Welcome, <?php echo $name; ?> ...</i>
        </div>
        <?php foreach ($list as $key): ?>
        <div class="item">
            <img src="<?= $key['img'] ?>" alt="<?= $key['label'] ?> icon">
            <p><?= $key['label'] ?><?= $key['value'] ?></p>
        </div>
        <?php endforeach; ?>
        <div class="button-group">
            <div class="item">
                <button class="btn btn-secondary" id="viewSubscriptionBtn">View my Subscription</button>
            </div>
            <div class="item">
                <button class="btn btn-secondary" onclick="upload()">Upload Datasets</button>
                <div class="spinner-border hidden" id="load" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <div class="item">
            <p id="font1" class="hidden" style="font-weight: bold;">Verified</p>
        </div>
        <div class="item">
            <p id="font2" class="hidden" style="color: red; font-weight: bold;">Rejected File Name XXX. Please Reupload.</p>
        </div>
    </div>
</div>
<div class="w-full max-w-6xl mt-3 text-right text-gray-500 bottom-right">
    <a href="login.php">Logout</a>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Cancel successfully!
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" style="background-color: #2F67EF">OK</button>
            </div>
        </div>
    </div>
</div>
<script>
    (function () {
        let paramValue = getURLParameter('isOne')
        if (paramValue === 'true') $('#exampleModal').modal()
    })();
    const upload = () => {
        document.getElementById("load").classList.add("show");
        setTimeout(() => {
            document.getElementById("load").classList.remove("show");
            document.getElementById("font1").classList.remove("hidden");
        }, 2000);
    };
    document.getElementById('viewSubscriptionBtn').addEventListener('click', function() {
        window.location.href = "subscription.php?username=" + "<?php echo $username;?>" + "&subscriptionId=" + "<?php echo $subscriptId;?>";
    });
</script>
</body>
</html>
