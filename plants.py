import os

image_folder = 'C:\\Program Files\\Ampps\\www\\www\\img\\plants'


def get_image_paths(folder):
    image_extensions = ['.jpg', '.jpeg', '.png', '.gif', '.bmp'] 
    image_paths = []

    for dirpath, dirnames, filenames in os.walk(folder):
        for filename in filenames:
            if any(filename.lower().endswith(ext) for ext in image_extensions):
                full_path = os.path.join(dirpath, filename)
                image_paths.append(full_path)

    return image_paths

image_paths = get_image_paths(image_folder)

for path in image_paths:
    print(path)
