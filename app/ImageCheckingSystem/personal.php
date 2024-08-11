<?php
session_start();
// include_once '../controller/businessOwnerController.php';
// include_once '../controller/subscriptionController.php';

// if (isset($_GET['username'])) {
//     $username = $_GET['username'];
// }

// $businessOwner = new searchBusinessOwnerAccount();
// $userData = $businessOwner->handleSearchRequest($username);

// $accountId = $userData['id'];
// $name = $userData['name'];
// $subscriptId = $userData['subscription_id'];

// $subscription = new viewSubscriptionController();
// $subscriptionData = $subscription->viewSubscription($subscriptId);
// $subscriptionPrice = $subscriptionData['price'];
// $subscriptionDescription = $subscriptionData['description'];

// $list=[
//     ['icon'=>'icon-tags', 'label'=>'Username:', 'value'=>$userData['userName'], 'img'=>'../view/images/id-card-regular.svg'],
//     ['icon'=>'icon-envelope', 'label'=>'Email:', 'value'=>$userData['email'], 'img'=>'../view/images/envelope-regular.svg'],
//     ['icon'=>'icon-zoom-in', 'label'=>'Subscription Type:','value'=>$subscriptionData['name'], 'img'=>'../view/images/calendar-days-regular.svg'],
//     ['icon'=>'icon-home', 'label'=>'Company Name:', 'value'=>$userData['company'], 'img'=>'../view/images/building-regular.svg'],
// ];

// Test data
$username = "testuser";
$name = "John Doe";
$subscriptId = "12345";
$list = [
    ['icon'=>'icon-tags', 'label'=>'Username:', 'value'=>'testuser', 'img'=>'images/id-card-regular.svg'],
    ['icon'=>'icon-envelope', 'label'=>'Email:', 'value'=>'testuser@example.com', 'img'=>'images/envelope-regular.svg'],
    ['icon'=>'icon-zoom-in', 'label'=>'Subscription Type:','value'=>'Premium', 'img'=>'images/calendar-days-regular.svg'],
    ['icon'=>'icon-home', 'label'=>'Company Name:', 'value'=>'Test Company', 'img'=>'images/building-regular.svg'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Business Owner Home Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/lux/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
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
            margin-bottom: -5rem;
            font-size: 1rem;
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
            background-color: #333;
            border-color: #333;
        }
        .btn-primary:hover {
            background-color: #444;
            border-color: #444;
        }
        .btn-secondary {
            text-transform: none !important;
        }
        .hidden {
            display: none;
        }
        .output-group {
            display: flex;
            /* justify-content: flex-end; Align items to the right */
            margin-top: 2.6rem; /* Space between the button group and the output */
            margin-left: 535px; /* Move to the left */
        }
        #output {
            margin-top: 1rem; /* Add margin to ensure it's spaced nicely below the button */
            display: block;
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
                <button class="btn btn-primary" id="viewSubscriptionBtn" style="font-size: 12px;">View my Subscription</button>
            </div>
            <div class="item">
                <button class="btn btn-primary" onclick="showUploadModal()" style="font-size: 12px;">Upload Datasets</button>
            </div>
        </div>

        <div class="output-group">
            <div id="output" style="font-size: 12px;"></div>
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

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="uploadForm" enctype="multipart/form-data" action="Scripts/upload_and_process.php" method="POST">
                <input type="hidden" name="MAX_FILE_SIZE" value="52428800" />
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel" style="color: white;">Upload Zip File and Excel File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="color: black;">

                    <div id="tier" style="display:block;">
                    <label for="tierDropdown">Tier:</label>
                    <select id="tierDropdown" name="tierDropdown" required>
                        <option value="None">None</option>
                        <option value="Basic">Basic</option>
                        <option value="Upgraded">Upgraded</option>
                        <option value="Advanced">Advanced</option>
                    </select>
                    </div>

                    <label for="zipFile">Select Zip File:</label>
                    <input type="file" name="zipFile" id="zipFile" accept=".zip" required><br><br>

                    <label for="excelFile">Select Excel File:</label>
                    <input type="file" name="excelFile" id="excelFile" accept=".xlsx" required><br><br>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: space-between; width: 100%;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

                    <div style="display: flex; align-items: center;">
                        <div id="loading" style="display: none; text-align: center; margin-right: 10px;">
                            <div class="spinner-border text-primary" role="status"></div>                                                       
                        </div>
                        <button type="submit" class="btn btn-primary" style="border-radius: 5px;">Upload and Process</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>



<script>
    function showUploadModal() {
        console.log("showUploadModal called");
        $('#uploadModal').modal('show');
    }

    document.getElementById('viewSubscriptionBtn').addEventListener('click', function() {
        console.log("viewSubscriptionBtn clicked");
        window.location.href = "subscription.php?username=" + "<?php echo $username;?>" + "&subscriptionId=" + "<?php echo $subscriptId;?>";
    });


    document.getElementById('uploadForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const zipFileInput = document.getElementById('zipFile');
        const excelFileInput = document.getElementById('excelFile');
        const zipFile = zipFileInput.files[0];
        const excelFile = excelFileInput.files[0];

        console.log("Files selected:");
        console.log("ZIP file:", zipFile);
        console.log("Excel file:", excelFile);

        if (!zipFile || !excelFile) {
            document.getElementById('output').innerHTML = '<p>Please upload both files.</p>';
            return;
        }

        // Check file formats
        if (zipFile.type !== 'application/x-zip-compressed' && zipFile.type !== 'application/zip') {
            document.getElementById('output').innerHTML = '<p>The uploaded zip file is not in the correct format.</p>';
            return;
        }
        if (!excelFile.name.endsWith('.xlsx')) {
            document.getElementById('output').innerHTML = '<p>The uploaded Excel file is not in the correct format.</p>';
            return;
        }

        const zip = new JSZip();
        const reader = new FileReader();

        reader.onload = function(e) {
            zip.loadAsync(e.target.result).then(function(zip) {
                let valid = true;
                const zipNames = new Set();
                zip.forEach(function (relativePath, zipEntry) {
                    if (!zipEntry.dir && !zipEntry.name.match(/\.(jpe?g|png)$/i)) {
                        valid = false;
                    }
                    if (zipEntry.dir) {
                        const folderName = zipEntry.name.split('/')[1];
                        zipNames.add(folderName);
                    }
                });

                if (!valid) {
                    document.getElementById('output').innerHTML = '<p>The zip file contains invalid files. Only images are allowed.</p>';
                    return;
                }

                const excelReader = new FileReader();
                excelReader.onload = function(e) {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });

                    if (workbook.SheetNames.length === 0) {
                        document.getElementById('output').innerHTML = '<p>The Excel file is empty.</p>';
                        return;
                    }

                    const sheet = workbook.Sheets[workbook.SheetNames[0]];
                    const json = XLSX.utils.sheet_to_json(sheet);

                    if (json.length === 0) {
                        document.getElementById('output').innerHTML = '<p>The Excel sheet is empty.</p>';
                        return;
                    }

                    const excelNames = new Set();
                    json.forEach(row => {
                        if (row['Name']) {
                            excelNames.add(row['Name']);
                        }
                    });

                    console.log("zipNames:", Array.from(zipNames));
                    console.log("excelNames:", Array.from(excelNames));

                    const missingInExcel = Array.from(zipNames).filter(name => !excelNames.has(name));
                    const missingInZip = Array.from(excelNames).filter(name => !zipNames.has(name));

                    console.log("missingInExcel:", missingInExcel);
                    console.log("missingInZip:", missingInZip);

                    let warningMessage = '<p><strong>Validation Errors:</strong></p>';

                    if (missingInExcel.length > 0) {
                        warningMessage += '<p><strong>Folders in the zip file not found in the Excel file:</strong></p>';
                        warningMessage += '<ul>';
                        missingInExcel.forEach(name => {
                            warningMessage += `<li>${name}</li>`;
                        });
                        warningMessage += '</ul>';
                    }

                    if (missingInZip.length > 0) {
                        warningMessage += '<p><strong>Names in the Excel file not found in the zip file:</strong></p>';
                        warningMessage += '<ul>';
                        missingInZip.forEach(name => {
                            warningMessage += `<li>${name}</li>`;
                        });
                        warningMessage += '</ul>';
                    }

                    if (missingInExcel.length > 0 || missingInZip.length > 0) {
                        document.getElementById('output').innerHTML = warningMessage;
                        return;
                    }

                    // If everything is valid, submit the form
                    const formData = new FormData();
                    formData.append('zipFile', zipFile);
                    formData.append('excelFile', excelFile, 'companyNameList.xlsx');

                    document.getElementById('loading').style.display = 'block';

                    try {
                        console.log("Sending files to server...");
                        fetch('Scripts/upload_and_process.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok. Status: ' + response.status);
                            }
                            return response.json();  // Return the JSON data from the response
                        })
                        .then(data => {
                            console.log("Response received from server:", data);
                            if (data.status === 'success') {
                                document.getElementById('output').innerHTML = '<a href="download_result.php" class="btn btn-primary" id="downloadResultsBtn" style="display: none;">Download Results</a>';
                                //document.getElementById('output').innerHTML = '<a href="download_result.php" id="downloadResultsBtn">Download Results</a>';
                                document.getElementById('downloadResultsBtn').style.display = 'block';
                                console.log("Button should be visible now");
                                $('#uploadModal').modal('hide'); // Close the modal
                            } else {
                                console.error('Error processing files:', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('There was a problem with the fetch operation:', error);
                        })
                        .finally(() => {
                            document.getElementById('loading').style.display = 'none';
                        });
                    } catch (error) {
                        console.error('An error occurred:', error);
                    }
                };
                excelReader.readAsArrayBuffer(excelFile);
            });
        };

        reader.readAsArrayBuffer(zipFile);
    });


</script>
</body>
</html>
