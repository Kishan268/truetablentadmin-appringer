import sys
sys.path.append("/var/www/html/TT-Admin/")
import fitz

def extract_text_from_pdf(file_path):
    text = ""
    with fitz.open(file_path) as pdf:
        for page in pdf:
            text += page.get_text()
    return text

# Provide the path to your PDF file
file_name = sys.argv[1];
pdf_file_path = file_name

# Extract text from PDF
extracted_text = extract_text_from_pdf(pdf_file_path)

# Print the extracted text
print(extracted_text)