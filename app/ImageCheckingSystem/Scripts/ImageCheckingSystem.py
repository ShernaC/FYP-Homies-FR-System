import subprocess
import sys
import io
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
sys.stderr = io.TextIOWrapper(sys.stderr.buffer, encoding='utf-8')
print(sys.version)

import os
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '2'  # Suppress INFO and WARNING messages
import zipfile
import numpy as np
from PIL import Image
import cv2
from mtcnn import MTCNN
import sys
#import tensorflow as tf
import pandas as pd
import logging
import json


# Set logging level
logging.basicConfig(level=logging.ERROR)

# %%
def read_excel_file(file_path):
    # Read the Excel file into a DataFrame
    sheet_data = pd.read_excel(file_path)
    
    return sheet_data

#'C:/xampp/htdocs/new/uploadsExcel/companyNameList.xlsx'

# excel_file_path = r'C:\xampp\htdocs\ImageCheckingSystem\sendsy\ImageCheckingSystem\uploadsExcel\companyNameList.xlsx'
excel_file_path = r'C:\xampp\htdocs\otp_test\FYP-Homies-FR-System\app\ImageCheckingSystem\uploadsExcel\companyNameList.xlsx'
sheet_data = read_excel_file(excel_file_path)


# %%
# Define allowed image extensions
ALLOWED_EXTENSIONS = ('.png', '.jpg', '.jpeg')

# %%
# Initialize MTCNN detector
mtcnn_detector = MTCNN()

# %%
# Function to suppress output
def suppress_output(func, *args, **kwargs):
    class DummyFile(object):
        def write(self, x): pass
        def flush(self): pass

    old_stdout = sys.stdout
    sys.stdout = DummyFile()
    try:
        result = func(*args, **kwargs)
    finally:
        sys.stdout = old_stdout
    return result


# Function to detect faces
def detect_faces(image):
    # Convert image to RGB as MTCNN expects RGB images
    image_rgb = np.array(image.convert('RGB'))

    # Suppress the output of detect_faces
    results = suppress_output(mtcnn_detector.detect_faces, image_rgb)

    # Extract face bounding boxes
    faces = []
    for result in results:
        x, y, w, h = result['box']
        faces.append((x, y, w, h))

    # Post-processing: Filter out false positives based on aspect ratio
    filtered_faces = []
    for (x, y, w, h) in faces:
        aspect_ratio = float(w) / h
        if 0.5 < aspect_ratio < 1.5:  # Assuming faces are roughly square
            filtered_faces.append((x, y, w, h))

    return filtered_faces


# %%
def preprocess_image(image, image_path):
    faces = detect_faces(image)
    # Handle case where no faces or more than one face is detected
    if len(faces) != 1:
        print(f"Image {image_path} rejected: No faces/ More than 1 face detected")
        return None
    # Crop the image to include only the first detected face
    x, y, w, h = faces[0]
    cropped_image = image.crop((x, y, x+w, y+h))
    # Resize the cropped image to a consistent size
    resized_image = cropped_image.resize((100, 100))  # Adjust the size as needed
    return resized_image

# %%
def check_blurriness(image, threshold=100):
    gray = cv2.cvtColor(np.array(image), cv2.COLOR_BGR2GRAY)
    variance_of_laplacian = cv2.Laplacian(gray, cv2.CV_64F).var()
    std_dev = np.std(gray)
    return variance_of_laplacian > threshold and std_dev > 20

def check_lighting(image, threshold=100):
    # Convert the image to a NumPy array
    img_array = np.array(image)
    # Convert the image to the HSV color space
    hsv = cv2.cvtColor(img_array, cv2.COLOR_BGR2HSV)
    # Calculate the mean brightness using the V channel (Value/Brightness)
    brightness = np.mean(hsv[:,:,2])
    return brightness > threshold

def check_edges(image, edge_threshold=100):
    gray = cv2.cvtColor(np.array(image), cv2.COLOR_BGR2GRAY)
    edges = cv2.Canny(gray, 100, 200)
    num_edges = np.sum(edges > 0)
    return num_edges > edge_threshold or num_edges < 100


# ----------------------------------------------------------------
# Integrating GAN
import os
import zipfile
import torch
from torch import nn
import torchvision.transforms as transforms

# Set logging level
import logging

# Set logging level
logging.basicConfig(level=logging.ERROR)

# Load the discriminator 
class Discriminator(nn.Module):
    def __init__(self, ngpu):
        super(Discriminator, self).__init__()
        self.ngpu = ngpu
        self.main = nn.Sequential(
            # input is ``(nc) x 64 x 64``
            nn.Conv2d(nc, ndf, 4, 2, 1, bias=False),
            nn.LeakyReLU(0.2, inplace=True),
            # state size. ``(ndf) x 32 x 32``
            nn.Conv2d(ndf, ndf * 2, 4, 2, 1, bias=False),
            nn.BatchNorm2d(ndf * 2),
            nn.LeakyReLU(0.2, inplace=True),
            # state size. ``(ndf*2) x 16 x 16``
            nn.Conv2d(ndf * 2, ndf * 4, 4, 2, 1, bias=False),
            nn.BatchNorm2d(ndf * 4),
            nn.LeakyReLU(0.2, inplace=True),
            # state size. ``(ndf*4) x 8 x 8``
            nn.Conv2d(ndf * 4, ndf * 8, 4, 2, 1, bias=False),
            nn.BatchNorm2d(ndf * 8),
            nn.LeakyReLU(0.2, inplace=True),
            # state size. ``(ndf*8) x 4 x 4``
            nn.Conv2d(ndf * 8, 1, 4, 1, 0, bias=False),
            nn.Sigmoid()
        )

    def forward(self, input):
        return self.main(input)

def load_model(model, path, device):
    model.load_state_dict(torch.load(path, map_location=device))
    model.to(device)
    return model

# Parameters used in the model
image_size = 64
ngpu = 1
nz = 100
ndf = 64
nc = 3

device = torch.device("cuda:0" if torch.cuda.is_available() else "cpu")
model_path = r"C:\xampp\htdocs\otp_test\FYP-Homies-FR-System\app\ImageCheckingSystem\modelDownload\disc_stop_15_lr1.pth" #change file path accordingly

# Check if the file exists
if not os.path.isfile(model_path):
    print(f"Error: The file at {model_path} does not exist.")
else:
    print(f"File exists: {model_path}")

    # Attempt to load the model to check if the path is correct and the file is usable
    try:
        # Assuming the model architecture is known and a class `Discriminator` exists
        model = Discriminator(ngpu)  # Define the model
        model = load_model(model, model_path, device)  # Try to load the model
        print("Model loaded successfully.")
    except Exception as e:
        print(f"Error loading model: {e}")

#--------------------------------------------

model = Discriminator(ngpu).to(device)




model = load_model(model, model_path, device)
model.eval()

def preprocess_image_GAN(image_path, image_size):
    # Open and convert the image to RGB
    image = Image.open(image_path).convert('RGB')
    
    # Define the transformations
    transform = transforms.Compose([
        transforms.Resize(image_size),
        transforms.CenterCrop(image_size),
        transforms.ToTensor(),
        transforms.Normalize((0.5, 0.5, 0.5), (0.5, 0.5, 0.5)),
    ])
    
    # Apply the transformations
    image_tensor = transform(image).unsqueeze(0)
    return image_tensor

# Function to predict real or fake using the GAN model
def is_fake_or_real(model, image_path, device, image_size):
    # Preprocess the image
    image_tensor = preprocess_image_GAN(image_path, image_size).to(device)
    
    # Predict
    with torch.no_grad():
        output = model(image_tensor).view(-1).item()
        
    threshold = 0.4
    
    return output < threshold #for testing purposes (apparently this is the correct one..)


def evaluate_and_save_image(image_path, model, device, image_size, tier, approved_images_dir, extract_path):
    # Open the image
    image = Image.open(image_path)

    # Check if the image is fake or real
    if not is_fake_or_real(model, image_path, device, image_size):
        print(f"Image {image_path} rejected: Fake image detected by GAN model")
        return False

    # Preprocess the image
    resized_image = preprocess_image(image, image_path)

    # Enhance the image
    preprocessed_image = enhance_image(resized_image, tier)
    
    if preprocessed_image is None:
        print(f"Image {image_path} rejected: No faces/ More than 1 face detected")
        return False
    
    elif not check_blurriness(preprocessed_image):
        print(f"Image {image_path} rejected: Blurriness")
        return False

    elif not check_lighting(preprocessed_image):
        print(f"Image {image_path} rejected: Extreme Lighting")
        return False

    elif not check_edges(preprocessed_image):
        print(f"Image {image_path} rejected: Edges")
        return False

    else:
        # Convert numpy array to PIL.Image if necessary
        if isinstance(preprocessed_image, np.ndarray):
            preprocessed_image = Image.fromarray(cv2.cvtColor(preprocessed_image, cv2.COLOR_BGR2RGB))

        # Determine the relative path from the extraction path
        relative_path = os.path.relpath(image_path, extract_path)

        # Create the same directory structure in the approved_images_dir
        save_path = os.path.join(approved_images_dir, relative_path)
        os.makedirs(os.path.dirname(save_path), exist_ok=True)

        # Save the processed image
        preprocessed_image.save(save_path)
        #print(f"Image {image_path} accepted and saved to {save_path}")

        return True




# Define the function to check if the zip file contains only image files
def is_all_images(zip_file_path):
    with zipfile.ZipFile(zip_file_path, 'r') as zip_ref:
        for file_info in zip_ref.infolist():
            # Skip directories
            if file_info.is_dir():
                continue
            # Debug print to see which files are being checked
            #print(f"Checking file: {file_info.filename}")
            # Check if file extension is allowed
            if not any(file_info.filename.lower().endswith(ext) for ext in ALLOWED_EXTENSIONS):
                print(f"Non-image file found: {file_info.filename}")
                return False
    return True


def process_images(image_paths, model, device, image_size, tier, approved_images_dir, extract_path):
    # Create the directory if it doesn't exist
    os.makedirs(approved_images_dir, exist_ok=True)

    for image_path in image_paths:
        evaluate_and_save_image(image_path, model, device, image_size, tier, approved_images_dir, extract_path)

           

# %%
import os
import shutil
import numpy as np
from PIL import Image
import cv2
import dlib
import matplotlib.pyplot as plt
import io


def enhance_image(image, tier='none'):
    #print(f"Enhancement tier received: {tier}")

    def basic_enhancement(img):
        # Basic enhancement: apply Gaussian Blur
        return cv2.GaussianBlur(img, (5, 5), 0)

    def upgraded_enhancement(img):
        # Upgraded enhancement: CLAHE (Contrast Limited Adaptive Histogram Equalization)
        lab = cv2.cvtColor(img, cv2.COLOR_BGR2LAB)
        l, a, b = cv2.split(lab)
        clahe = cv2.createCLAHE(clipLimit=3.0, tileGridSize=(8, 8))
        cl = clahe.apply(l)
        limg = cv2.merge((cl, a, b))
        img = cv2.cvtColor(limg, cv2.COLOR_LAB2BGR)
        img = cv2.inpaint(img, np.zeros(img.shape[:2], dtype=np.uint8), 3, cv2.INPAINT_TELEA)
        return img

    def advanced_enhancement(img):
        # Advanced enhancement: Combine CLAHE and additional scaling
        lab = cv2.cvtColor(img, cv2.COLOR_BGR2LAB)
        l, a, b = cv2.split(lab)
        clahe = cv2.createCLAHE(clipLimit=3.0, tileGridSize=(8, 8))
        cl = clahe.apply(l)
        limg = cv2.merge((cl, a, b))
        img = cv2.cvtColor(limg, cv2.COLOR_LAB2BGR)
        img = cv2.inpaint(img, np.zeros(img.shape[:2], dtype=np.uint8), 3, cv2.INPAINT_TELEA)
        lab = cv2.cvtColor(img, cv2.COLOR_BGR2LAB)
        l, a, b = cv2.split(lab)
        clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8, 8))
        l_eq = clahe.apply(l)
        lab_eq = cv2.merge((l_eq, a, b))
        img = cv2.cvtColor(lab_eq, cv2.COLOR_LAB2BGR)
        img = cv2.convertScaleAbs(img, alpha=1.2, beta=-20)
        return img

    # Check if the input is a PIL image and convert to OpenCV format
    if isinstance(image, Image.Image):
        image = cv2.cvtColor(np.array(image), cv2.COLOR_RGB2BGR)
    elif isinstance(image, np.ndarray):
        # If it's already a numpy array, ensure it's in the right format
        if image.ndim == 2:  # Grayscale image
            image = cv2.cvtColor(image, cv2.COLOR_GRAY2BGR)
        elif image.ndim == 3 and image.shape[2] == 4:  # RGBA image
            image = cv2.cvtColor(image, cv2.COLOR_RGBA2BGR)
    else:
        raise ValueError("Input is not a valid image")

    # Convert tier to string and ensure it's in lowercase
    tier = str(tier).lower()

    if tier == 'none':
        return image
    elif tier == 'basic':
        return basic_enhancement(image)
    elif tier == 'upgraded':
        return upgraded_enhancement(image)
    elif tier == 'advanced':
        return advanced_enhancement(image)
    else:
        raise ValueError("Invalid tier. Choose from 'none', 'basic', 'upgraded', or 'advanced'.")
    


# %%
def get_top_level_folder(zip_path):
    with zipfile.ZipFile(zip_path, 'r') as zip_ref:
        # Get the list of all files and folders in the zip archive
        file_list = zip_ref.namelist()
        
        # Initialize a set to store top-level folders
        top_level_folders = set()
        
        # Iterate through the file list to find top-level folders
        for file in file_list:
            # Extract the top-level folder from the path
            parts = file.split('/')
            if len(parts) > 1:
                top_level_folders.add(parts[0])
        
        # Return the top-level folder if there is exactly one unique folder
        if len(top_level_folders) == 1:
            return top_level_folders.pop()
        return None


# Paths to the model files on your computer
local_paths = {
    'shape_predictor_68_face_landmarks.dat': r'C:\xampp\htdocs\otp_test\FYP-Homies-FR-System\app\ImageCheckingSystem\modelDownload\shape_predictor_68_face_landmarks.dat',
    'dlib_face_recognition_resnet_model_v1.dat': r'C:\xampp\htdocs\otp_test\FYP-Homies-FR-System\app\ImageCheckingSystem\modelDownload\dlib_face_recognition_resnet_model_v1.dat'
}


# Initialize Dlib face detector, shape predictor, and face recognition model
detector = dlib.get_frontal_face_detector()
shape_predictor = dlib.shape_predictor(local_paths['shape_predictor_68_face_landmarks.dat'])
face_rec_model = dlib.face_recognition_model_v1(local_paths['dlib_face_recognition_resnet_model_v1.dat'])


# Define the function here or import it from another module
def calculate_average_feature_vector(feature_vectors):
    if len(feature_vectors) == 0:
        return None
    return np.mean(feature_vectors, axis=0)

def extract_feature(image):
    # Convert the image to grayscale
    gray = cv2.cvtColor(np.array(image), cv2.COLOR_RGB2GRAY)

    # Detect faces
    faces = detector(gray)

    # If no faces are detected
    if len(faces) == 0:
        return None

    # Process the first detected face
    face = faces[0]
    shape = shape_predictor(gray, face)

    # Extract face descriptor
    try:
        face_descriptor = face_rec_model.compute_face_descriptor(np.array(image), shape)
        feature_vector = np.array(face_descriptor)
        return feature_vector
    except Exception as e:
        print(f"Error extracting features: {e}")
        return None

def get_all_images_from_folder(folder_path):
    image_paths = []
    for root, dirs, files in os.walk(folder_path):
        for file in files:
            if file.lower().endswith(ALLOWED_EXTENSIONS):
                image_paths.append(os.path.join(root, file))
    return image_paths


# Define the function to generate representative features for a person
def generate_representative_feature(person_folder_path):
    images = get_all_images_from_folder(person_folder_path)
    #print(f"Images in {person_folder_path}: {images}")
    features = []

    for img_path in images:
        img = Image.open(img_path)
        #print(f"Processing image: {img_path}")
        feature = extract_feature(img)

        if feature is not None:
            features.append(feature)
        else:
            continue
            #print(f"No face feature vector found in {img_path}.")
            

    if not features:
        print(f"No features extracted for {person_folder_path}.")
        return None

    features_array = np.array(features)
    #print(f"Features array shape: {features_array.shape}")

    average_features = calculate_average_feature_vector(features_array)
    if average_features is None:
        print(f"Failed to calculate average features for {person_folder_path}.")

    return average_features



# %%
def compare_features(feature1, feature2, threshold=0.4):
    diff = np.linalg.norm(feature1 - feature2)
    return diff < threshold, diff

def compare_uploaded_image(image_path, representative_features, threshold=0.4):
    image = Image.open(image_path)
    processed_image = preprocess_image(image, image_path)
    if processed_image is None:
        return  # Image was rejected in preprocessing

    feature_vector = extract_feature(processed_image)
    if feature_vector is None:
        print(f"No face feature vector found in {image_path}")
        return

    print(f"Extracted feature vector for {os.path.basename(image_path)}: {feature_vector}")

    best_match = None
    min_diff = float('inf')

    # Ensure representative_features is a list of tuples
    for person, rep_feature in representative_features:
        same_person, diff = compare_features(feature_vector, rep_feature, threshold)
        print(f"Comparing {os.path.basename(image_path)} with {person}: Difference = {diff}")
        if same_person and diff < min_diff:
            min_diff = diff
            best_match = person

    if best_match and min_diff < threshold:
        print(f"The best match is {best_match} with a difference of {min_diff}")
        print(f"Person is approved.")
        # Display the uploaded image with details from Google Sheets
        folder_name = best_match
        person_data = sheet_data[sheet_data['Name'] == folder_name]
        if not person_data.empty:
            person_data = person_data.iloc[0]
            name = person_data['Name']
            job_title = person_data['Job Title']
            department = person_data['Department']
            access_level = person_data['Access Level']
        else:
            name = folder_name
            job_title = 'Unknown'
            department = 'Unknown'
            access_level = 'Unknown'

        # Display the image and information
        plt.imshow(processed_image)
        plt.title(f"Name: {name}\nJob Title: {job_title}\nDepartment: {department}\nAccess Level: {access_level}\n")
        plt.axis('off')
        plt.show()
        # Optionally, delete the image after capturing for privacy issues
        # os.remove(image_path)
    else:
        print(f"No acceptable match found for {os.path.basename(image_path)}. The image is rejected.")
        # Optionally, delete the image with no acceptable match found
        # os.remove(image_path)



# Function to handle the main processing when run as a script
def main(zip_path, excel_file_path, tier, model, device, image_size):

    # Use the received arguments in your script
    logging.info(f"Zip file path: {zip_path}")
    logging.info(f"Excel file path: {excel_file_path}")
    logging.info(f"Tier: {tier}")
    approved_images_dir = r'C:\xampp\htdocs\otp_test\FYP-Homies-FR-System\app\ImageCheckingSystem\ApprovedImages'
    # Your processing code here
    # Example: print the received arguments
    print(f"Zip file path: {zip_path}")
    print(f"Excel file path: {excel_file_path}")
    print(f"Tier: {tier}")

    # Check if the zip file exists
    if os.path.exists(zip_path):
        if is_all_images(zip_path):
            zip_name = os.path.splitext(os.path.basename(zip_path))[0]
            extract_path = os.path.join('C:/xampp/htdocs/ImageCheckingSystem/new/uploads', zip_name)
            os.makedirs(extract_path, exist_ok=True)
            
            with zipfile.ZipFile(zip_path, 'r') as zip_ref:
                zip_ref.extractall(extract_path)
            
            subfolder_approval = {}
            subfolder_rejections = {}

            processed_images = 0
            
            for root, dirs, files in os.walk(extract_path):
                for dir_name in dirs:
                    subfolder_path = os.path.join(root, dir_name)
                    # Skip the main folder
                    if subfolder_path == extract_path:
                        continue
                    
                    image_paths = []
                    for sub_root, _, sub_files in os.walk(subfolder_path):
                        for file in sub_files:
                            if any(file.lower().endswith(ext) for ext in ALLOWED_EXTENSIONS):
                                image_paths.append(os.path.join(sub_root, file))
                            else:
                                print(f"Non-image file found: {os.path.join(sub_root, file)}")
                                print("Dataset contains non-image files. Dataset not approved.")
                                return  # Exit the function if non-image files are found
                    
                    if image_paths:
                        num_images_processed = len(image_paths)
                        pass_count = 0
                        for image_path in image_paths:
                            if evaluate_and_save_image(image_path, model, device, image_size, tier, approved_images_dir, extract_path):
                                pass_count += 1
                    
                            processed_images += 1
                            
                        if num_images_processed > 0:
                            pass_rate = pass_count / num_images_processed
                            if pass_rate >= 0.55:
                                subfolder_approval[subfolder_path] = pass_rate
                            else:
                                subfolder_rejections[subfolder_path] = pass_rate
                        else:
                            subfolder_rejections[subfolder_path] = 0.0
                    else:
                        subfolder_rejections[subfolder_path] = 0.0

            # Calculate overall pass rate
            total_subfolders = len(subfolder_approval) + len(subfolder_rejections)
            total_pass_count = sum(1 for rate in subfolder_approval.values() if rate >= 0.45)
            overall_pass_rate = total_pass_count / total_subfolders if total_subfolders > 0 else 0
            
            if overall_pass_rate < 0.45:
                print("Error: The overall pass rate is below 0.45. Please try again.")
                return
            
            for approved_subfolder, pass_rate in subfolder_approval.items():
                subfolder_name = os.path.basename(approved_subfolder)
                print(f"\nSubfolder '{subfolder_name}' is approved. Pass rate: {pass_rate:.2f}".encode('utf-8', 'replace').decode('utf-8'))
                # Process approved images for each approved subfolder
                subfolder_image_paths = []
                for sub_root, _, sub_files in os.walk(approved_subfolder):
                    for file in sub_files:
                        if any(file.lower().endswith(ext) for ext in ALLOWED_EXTENSIONS):
                            subfolder_image_paths.append(os.path.join(sub_root, file))

                approved_images_dir = r'C:\xampp\htdocs\otp_test\FYP-Homies-FR-System\app\ImageCheckingSystem\ApprovedImages'
                process_images(subfolder_image_paths, model, device, image_size, tier, approved_images_dir, extract_path)
            
            if subfolder_rejections:
                print("WARNING! Subfolders not approved:")
                for rejected_subfolder, pass_rate in subfolder_rejections.items():
                    subfolder_name = os.path.basename(rejected_subfolder)
                    print(f"- {subfolder_name} (Pass rate: {pass_rate:.2f})".encode('utf-8', 'replace').decode('utf-8'))
        else:
            print("Dataset contains non-image files. Dataset not approved.")
            return
    else:
        print(f"The file {zip_path} does not exist.")




    # Get the top-level folder name
    target_subfolder = get_top_level_folder(zip_path)
    #print(f'Target subfolder: {target_subfolder}')

    # %%
    # Define paths
    approved_images_dir = r'C:\xampp\htdocs\otp_test\FYP-Homies-FR-System\app\ImageCheckingSystem\ApprovedImages'
    target_subfolder = get_top_level_folder(zip_path)  # The folder containing individual person folders

    # Path to the specific target folder
    target_folder_path = os.path.join(approved_images_dir, target_subfolder)

    # Generate representative features from the individual person folders under target_folder_path
    representative_features = []
    for person_folder in sorted(os.listdir(target_folder_path)):
        person_folder_path = os.path.join(target_folder_path, person_folder)
        if os.path.isdir(person_folder_path):
            print("")
            print(f"Processing folder: {person_folder_path}")
            representative_feature = generate_representative_feature(person_folder_path)
            if representative_feature is not None:
                print(f"Representative features for {person_folder}: {representative_feature}")
                representative_features.append((person_folder, representative_feature))
            else:
                print(f"No representative features found for {person_folder}")
        else:
            print(f"{person_folder_path} is not a directory. Skipping.")

    # Save the representative features to a file
    with open('representative_features.json', 'w') as f:
        # Convert numpy arrays to lists before saving
        features_to_save = [(person, feature.tolist()) for person, feature in representative_features]
        json.dump(features_to_save, f)

    
    

# Check if the script is run directly
if __name__ == "__main__":
    if len(sys.argv) != 4:
        print("Usage: python ImageCheckingSystem.py <zip_path> <excel_file_path> <tier>")
        sys.exit(1)

    zip_path = sys.argv[1]
    excel_file_path = sys.argv[2]
    tier = sys.argv[3]

    main(zip_path, excel_file_path, tier, model, device, image_size)



