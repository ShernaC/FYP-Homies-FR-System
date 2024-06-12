<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  
  <title>Facial Verification</title>
  <style>
    .header {
      width: 100%;
      background-color: #cfe2ff; /* Bootstrap blue-100 */
      padding: 16px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    body {
      font-family: 'Poppins', sans-serif;
    }
    .video-container {
      position: relative;
      width: 100%;
      height: 500px; /* Adjust this value to whatever fits your design */
    }
    #video {
      position: absolute; /* Set the position to absolute */
      top: 50%; /* Center vertically */
      left: 50%; /* Center horizontally */
      width: 100%;
      height: auto; /* Adjust height automatically to maintain aspect ratio */
      max-width: 700px; /* 100% of viewport width */
      max-height: 500px; /* Limit the height */
      transform: translate(-50%, -50%);
      z-index: 1; /* Ensure video appears on top of other content */
    }
    #canvas {
      display: none;
    }

    .button-container {
      position: absolute; /* Set the position to absolute */
      top: 50%; /* Center the button vertically */
      left: 50%; /* Center the button horizontally */
      transform: translate(-50%, -50%); /* Adjust the position to center the button */
      z-index: 2; /* Ensure button appears below the video */
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .bottom-right {
      position: fixed;
      bottom: 10px;
      right: 10px;
    }
    .modal-error .modal-header {
      background-color: #f8d7da;
    }
    .modal-success .modal-header {
      background-color: #d4edda;
    }

    .verifying-container {
      display: none;
      text-align: center;
      margin-top: 16px;
    }
  </style>
</head>
<body class="bg-gray-100">
  <div class="header">
    <h1 class="text-xl font-bold">Facial Verification</h1>
  </div>
  <div class="container flex flex-col items-center mt-4">
    <div class="button-container">
      <button id="startVerificationBtn" class="btn btn-primary mb-4">Begin Verification</button>
      <button id="triggerSuccessModal" class="btn btn-success mb-4">Test Success Modal</button>
      <button id="triggerErrorModal" class="btn btn-danger mb-4">Test Error Modal</button>
    </div>
    <div class="video-container">
      <video id="video" playsinline autoplay></video>
      <canvas id="canvas"></canvas>
    </div>
  </div>

  <!-- Success Modal -->
  <div class="modal fade modal-success" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="successModalLabel">Success</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Facial verification successful. You may proceed.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Error Modal -->
  <div class="modal fade modal-error" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="errorModalLabel">Error</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          No facial verification detected for more than 3 seconds. Please try again.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="w-full max-w-6xl mt-4 text-right text-gray-500 bottom-right">
    <a href="login.php" class="mr-4">Login</a>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    let verificationTimer;

    document.getElementById('startVerificationBtn').addEventListener('click', function() {
      // Access the webcam
      navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
          const video = document.getElementById('video');
          video.srcObject = stream;
          video.play();
          
          // Start the verification timer
          startVerificationTimer();
        })
        .catch(err => {
          console.error("Error accessing webcam: ", err);
        });
    });

    document.getElementById('video').addEventListener('click', function() {
      const video = document.getElementById('video');
      const canvas = document.getElementById('canvas');
      const context = canvas.getContext('2d');

      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

      // Convert the image to a data URL
      const imageDataURL = canvas.toDataURL('image/png');
      console.log(imageDataURL);  // This is the image data that you would send to your server for verification

      // For demonstration purposes, stop the video stream
      const stream = video.srcObject;
      const tracks = stream.getTracks();
      tracks.forEach(track => track.stop());
      video.srcObject = null;

      // Reset the verification timer
      resetVerificationTimer();

      // Trigger the success modal after 2 seconds
      setTimeout(function() {
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
      }, 2000);
    });

    function startVerificationTimer() {
      verificationTimer = setTimeout(function() {
        // Stop the video stream
        const video = document.getElementById('video');
        const stream = video.srcObject;
        if (stream) {
          const tracks = stream.getTracks();
          tracks.forEach(track => track.stop());
          video.srcObject = null;
        }

        // Show the error modal if no verification occurs within 3 seconds
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
      }, 3000);
    }

    function resetVerificationTimer() {
      clearTimeout(verificationTimer);
    }

    // Manual triggers for testing
    document.getElementById('triggerSuccessModal').addEventListener('click', function() {
      const successModal = new bootstrap.Modal(document.getElementById('successModal'));
      successModal.show();
    });

    document.getElementById('triggerErrorModal').addEventListener('click', function() {
      const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
      errorModal.show();
    });
  </script>
</body>
</html>
