import os
import json
import numpy as np
from PIL import Image
import cv2
from mtcnn import MTCNN
import tensorflow as tf
import pandas as pd
import matplotlib.pyplot as plt
import logging

# Set logging level
logging.basicConfig(level=logging.ERROR)

# Suppress Keras and TensorFlow verbose output
tf.get_logger().setLevel('ERROR')

# Import functions and variables from ImageCheckingSystem.py
from ImageCheckingSystem import calculate_average_feature_vector, extract_feature, preprocess_image, compare_features, read_excel_file, excel_file_path

sheet_data = read_excel_file(excel_file_path)

def compare_features(feature1, feature2, threshold=0.4):
    diff = np.linalg.norm(feature1 - feature2)
    return diff < threshold, diff

def compare_uploaded_image(image_path, representative_features, threshold=0.4):
    try:
        image = Image.open(image_path)
        processed_image = preprocess_image(image, image_path)
        if processed_image is None:
            print(f"Image rejected during preprocessing: {image_path}")
            return

        feature_vector = extract_feature(processed_image)
        if feature_vector is None:
            print(f"No face feature vector found in {image_path}")
            return

        best_match = None
        min_diff = float('inf')

        for person, rep_feature in representative_features:
            same_person, diff = compare_features(feature_vector, rep_feature, threshold)
            print(f"Comparing {os.path.basename(image_path)} with {person}: Difference = {diff}")
            if same_person and diff < min_diff:
                min_diff = diff
                best_match = person

        if best_match and min_diff < threshold:
            print(f"The best match is {best_match} with a difference of {min_diff}")
            print(f"Person is approved.")
            
            # Display the uploaded image with details from excel
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

        else:
            print(f"No acceptable match found for {os.path.basename(image_path)}. The image is rejected.")

    finally:
        # Ensure the image is always removed after processing
        if os.path.exists(image_path):
            os.remove(image_path)

def load_representative_features(filename='representative_features.json'):
    with open(filename, 'r') as f:
        features = json.load(f)
        return [(person, np.array(feature)) for person, feature in features]

def process_images():
    representative_features = load_representative_features(r"C:\xampp\htdocs\ImageCheckingSystem\Scripts\representative_features.json")
    if not representative_features:
        print({'error': 'No representative features found.'})
        return

    upload_dir = r"C:\xampp\htdocs\ImageCheckingSystem\TestUpload"
    if os.path.isdir(upload_dir):
        try:
            uploaded_files = [os.path.join(upload_dir, file) for file in os.listdir(upload_dir) if file.lower().endswith(('.png', '.jpg', '.jpeg'))]
            print("Uploaded files:")
            for file_path in uploaded_files:
                print(file_path)

            for file_path in uploaded_files:
                compare_uploaded_image(file_path, representative_features, threshold=0.4)
        except FileNotFoundError as e:
            print(f"FileNotFoundError: {e}")
        except Exception as e:
            print(f"An unexpected error occurred: {e}")
    else:
        print(f"The directory {upload_dir} does not exist.")

process_images()
