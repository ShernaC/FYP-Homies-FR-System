<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

  <title>Facial Verification</title>
  <style>
    #video {
      width: 100%;
      max-width: 500px;
      margin: 0 auto;
      display: block;
    }
    #canvas {
      display: none;
    }
  </style>
</head>
<body class="bg-gray-100">
  <div class="container flex flex-col items-center">
    <div class="bg-blue-100 w-full p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Facial Verification</h1>
    </div>
    <button id="startVerificationBtn" class="btn btn-primary mb-4">Start Verification</button>
    <div class="video-container">
      <video id="video" playsinline autoplay></video>
      <canvas id="canvas"></canvas>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('startVerificationBtn').addEventListener('click', function() {
      // Access the webcam
      navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
          const video = document.getElementById('video');
          video.srcObject = stream;
          video.play();
        })
        .catch(err => {
          console.error("Error accessing webcam: ", err);
        });
    });

    // Capture the image from the video feed
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
    });
  </script>
</body>
</html>
