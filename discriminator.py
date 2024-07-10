import torch
import torch.nn as nn
import torchvision.transforms as transforms
from PIL import Image
from sklearn.metrics import confusion_matrix, ConfusionMatrixDisplay
import seaborn as sns
import matplotlib.pyplot as plt

ngpu = 1
image_size = 64
nc = 3
ndf = 64

class Discriminator(nn.Module):
    def __init__(self, ngpu):
        super(Discriminator, self).__init__()
        self.ngpu = ngpu
        self.main = nn.Sequential(
            nn.Conv2d(nc, ndf, 4, 2, 1, bias=False),
            nn.LeakyReLU(0.2, inplace=True),
            nn.Conv2d(ndf, ndf * 2, 4, 2, 1, bias=False),
            nn.BatchNorm2d(ndf * 2),
            nn.LeakyReLU(0.2, inplace=True),
            nn.Conv2d(ndf * 2, ndf * 4, 4, 2, 1, bias=False),
            nn.BatchNorm2d(ndf * 4),
            nn.LeakyReLU(0.2, inplace=True),
            nn.Conv2d(ndf * 4, ndf * 8, 4, 2, 1, bias=False),
            nn.BatchNorm2d(ndf * 8),
            nn.LeakyReLU(0.2, inplace=True),
            nn.Conv2d(ndf * 8, 1, 4, 1, 0, bias=False),
            nn.Sigmoid()
        )

    def forward(self, input):
        return self.main(input)

def load_model(model, path, device):
    model.load_state_dict(torch.load(path, map_location=device))
    model.to(device)
    return model

def is_fake_or_real(discriminator_path, image_paths, device, true_labels=None):
    transform = transforms.Compose([
        transforms.Resize(image_size),
        transforms.CenterCrop(image_size),
        transforms.ToTensor(),
        transforms.Normalize((0.5, 0.5, 0.5), (0.5, 0.5, 0.5)),
    ])

    netD = Discriminator(ngpu).to(device)
    netD = load_model(netD, discriminator_path, device)
    netD.eval()

    predictions = []
    for image_path, true_label in zip(image_paths, true_labels):
        image = Image.open(image_path).convert('RGB')
        image = transform(image).unsqueeze(0).to(device)

        with torch.no_grad():
            output = netD(image).view(-1).item()

        predictions.append(int(output > 0.3))  # Append 1 if real, 0 if fake

    if true_labels is not None:
        accuracy = calculate_accuracy(predictions, true_labels)
        precision = calculate_precision(predictions, true_labels)
        recall = calculate_recall(predictions, true_labels)
        print(f"Accuracy: {accuracy:.2f}, Precision: {precision:.2f}, Recall: {recall:.2f}")
        
        cm = confusion_matrix(true_labels, predictions)
        fig, ax = plt.subplots(figsize=(10, 10))
        disp = ConfusionMatrixDisplay(confusion_matrix=cm)
        disp.plot(cmap=plt.cm.Blues, ax=ax)
        plt.xlabel('Predicted')
        plt.ylabel('Truth')
        plt.show()
    else:
        print("True labels are required to calculate accuracy, precision, and recall.")

def calculate_accuracy(predictions, labels):
    correct_predictions = sum([pred == label for pred, label in zip(predictions, labels)])
    total_predictions = len(predictions)
    return correct_predictions / total_predictions if total_predictions > 0 else 0

def calculate_precision(predictions, labels):
    true_positives = sum([(p == 1 and l == 1) for p, l in zip(predictions, labels)])
    predicted_positives = sum([p == 1 for p in predictions])
    return true_positives / predicted_positives if predicted_positives > 0 else 0

def calculate_recall(predictions, labels):
    true_positives = sum([(p == 1 and l == 1) for p, l in zip(predictions, labels)])
    actual_positives = sum([l == 1 for l in labels])
    return true_positives / actual_positives if actual_positives > 0 else 0

if __name__ == '__main__':
    discriminator_path = "model_25/discriminator.pth"
    device = torch.device("cuda:0" if torch.cuda.is_available() else "cpu")
    image_paths = [
        "data/input/fake1.jpg", 
        "data/input/fake2.jpg", 
        "data/input/fake3.jpg", 
        "data/input/real1.jpg", 
        "data/input/real2.jpg", 
        "data/input/real3.jpg", 
        "data/input/real4.jpg", 
        "data/input/real5.jpg", 
        "data/input/real6.jpg", 
        "data/input/real7.jpg",
        "data/input/real8.jpg", 
        "data/input/real9.jpg", 
    ]
    true_labels = [0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1]
    is_fake_or_real(discriminator_path, image_paths, device, true_labels=true_labels)
