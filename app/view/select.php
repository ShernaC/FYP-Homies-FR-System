<?php

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_GET['username']))
{
    $username = $_GET['username'];
    $subscriptionId = $_GET['subscriptionId'];
}

include_once '../controller/businessOwnerController.php';
$businessOwner = new searchBusinessOwnerAccount();
$userData = $businessOwner->handleSearchRequest($username);

$list=[
    ['title'=>'Free Trial Plan','cost'=>'Free ',"body2"=>"for one-month","select"=>["3 facial slots for trial users","Basic facial recognition"]],
    ['title'=>'Small Business Plan','cost'=>'S$50 ',"body2"=>"per year","select"=>["50 face datasets","Upgrade face recognition","S$4.17 SGD charged monthly"]],
    ['title'=>'Medium-Sized Business Plan','cost'=>'S$100 ',"body2"=>"per year","select"=>["100 face datasets","Advanced facial recognition","Role based permission system","Attendance function","S$8.33 SGD charged monthly"]],
    ['title'=>'Large Enterprise Plan','cost'=>'S$125 ',"body2"=>"per year","select"=>["200 face datastes","All functions of Medium-Sized Business Plan are included","Identity recognition function","S$10.42 SGD charged monthly"]],
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/lux/bootstrap.min.css">
    <script src="../view/public.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <style>
        .bottom-right {
            position: fixed;
            bottom: 10px;
            right: 10px;
        }
        .card:hover {
            transform: scale(1.05);
            transition: transform 0.3s;
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
        .fas.fa-chevron-left {
            color: #000; /* Set chevron to black */
        }
        .fas.fa-chevron-left:hover {
            color: #555; /* Change to a lighter black or dark gray on hover */
        }
    </style>
        
</head>
<body>
<div class="container mb-4 my-5">
    <div class="d-flex justify-content-between align-items-center mb-4 w-100">
        <div class="fas fa-chevron-left" style="font-size: 24px; cursor: pointer;" onclick="navigatorTo('subscription.php?username=testuser&subscriptionId=testsubid')"></div>
        <h3 class="text-xl font-bold text-center">Select Subscription Plans</h3>
        <div></div>
    </div>

    <div class="d-flex justify-content-center">
    <div class="row flex-nowrap" style="overflow-x: auto; padding-top: 200px;">
        <?php
        foreach ($list as $key): ?>
        <div class="col-md-3 mb-4 d-flex align-items-stretch">
            <div class="card h-100 w-100">
                <div class="card-header text-center">
                    <h4><?= $key["title"] ?></h4>
                </div>
                <div class="card-body">
                    <?php foreach ($key["select"] as $value): ?>
                    <p><span class="fas fa-check text-success"></span> <?= $value ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="card-footer text-center">
                    <p class="h5"><?= $key["cost"] ?><sub><?= $key["body2"] ?></sub></p>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-id="<?= array_search($key, $list) + 1 ?>" onclick="handleSelect('<?= $key["title"] ?>')">Select</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    </div>


</div>

<div class="w-full max-w-6xl mt-3 text-right text-gray-500 bottom-right">
    <a href="login.php">Logout</a>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel" style="color:green;">Confirm selection</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to select this plan?
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-secondary">Cancel</button>
                <button onclick="loadNav()" data-dismiss="modal" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Redirecting Card -->
<div class="modal fade" id="loadd" tabindex="-1" aria-labelledby="redirectingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body">
                <p class="h5">Redirecting to third-party payment website</p>
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        // Event listener for showing the modal
        $('#exampleModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract info from data-* attributes
            // Store the ID in a global variable or local storage for later use
            window.selectedId = id;
            console.log('Modal triggered, selectedId set to:', window.selectedId);
        });
    });

    const loadNav=()=>{
        $("#loadd").modal('show');
        setTimeout(()=>{
            $("#loadd").modal('hide');

            console.log('Selected ID before AJAX:', window.selectedId)
            
            // Make an AJAX request to the PHP script        
            $.ajax({
                url: '../controller/businessOwnerController.php',
                type: 'POST',
                data: {
                    action: 'update',
                    subscriptionId: window.selectedId, 
                    username: '<?php echo $username;?>'
                },
                success: function(response) {
                    //alert('successfully!');
                    console.log(response);
                    window.location.replace("subscription.php?isOne=true&username=<?php echo urlencode($username);?>&subscriptionId=" + window.selectedId)
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('Failed to update subscription details. Please try again.');
                }
            });

            // Comment out this line to prevent immediate redirection
            // window.location.replace("subscription.php?isOne=true&username=<?php echo urlencode($username);?>&subscriptionId=<?php echo urlencode($subscriptionId);?>")
        },2000)
    }
    
</script>
</body>
</html>
