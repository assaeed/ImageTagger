import sys
import json
from PIL import Image
from torchvision import models, transforms
import torch
import os
import urllib.request

model = models.resnet18(pretrained=True)
model.eval()

LABELS_URL = 'https://raw.githubusercontent.com/pytorch/hub/master/imagenet_classes.txt'
labels = []
with urllib.request.urlopen(LABELS_URL) as f:
    labels = [line.decode('utf-8').strip() for line in f.readlines()]

preprocess = transforms.Compose([
    transforms.Resize(256),
    transforms.CenterCrop(224),
    transforms.ToTensor(),
    transforms.Normalize(mean=[0.485, 0.456, 0.406],
                         std=[0.229, 0.224, 0.225])
])

def classify_image(image_path):
    try:
        image = Image.open(image_path).convert('RGB')
        input_tensor = preprocess(image)
        input_batch = input_tensor.unsqueeze(0)

        with torch.no_grad():
            output = model(input_batch)
        probabilities = torch.nn.functional.softmax(output[0], dim=0)

        top5_prob, top5_catid = torch.topk(probabilities, 5)
        results = [
            {"label": labels[catid], "probability": round(prob.item(), 4)}
            for prob, catid in zip(top5_prob, top5_catid)
        ]

        print(json.dumps({"success": True, "results": results}))
    except Exception as e:
        print(json.dumps({"success": False, "error": str(e)}))

if __name__ == '__main__':
    if len(sys.argv) != 2:
        print(json.dumps({"success": False, "error": "يرجى تمرير مسار الصورة."}))
        sys.exit(1)

    image_path = sys.argv[1]
    if not os.path.exists(image_path):
        print(json.dumps({"success": False, "error": "الملف غير موجود."}))
        sys.exit(1)

    classify_image(image_path)
