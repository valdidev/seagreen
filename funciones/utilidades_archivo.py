from urllib.parse import urlparse
import os

def get_filename_without_extension(file_url, extension):
    parsed_url = urlparse(file_url)
    file_path = parsed_url.path
    base_name = os.path.basename(file_path)
    file_name, _ = os.path.splitext(base_name)
    return file_name + extension