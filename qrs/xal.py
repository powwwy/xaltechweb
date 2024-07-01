from PIL import Image
import qrcode

# Your desired URL
url = "https://www.example.com"

# Load your existing QR code image
qr_image = Image.open(r"C:\Users\Admin\OneDrive\Documents\Xalal\qrcode.jpg") 

# Generate a new QR code with the desired URL
new_qr = qrcode.make(url)

# Resize the new QR code to match the size of the custom QR code image
new_qr = new_qr.resize(qr_image.size)

# Overlay the new QR code onto the custom QR code image
qr_image.paste(new_qr, (0, 0), new_qr)

# Save the modified image
qr_image.save(r"C:\Users\Admin\OneDrive\Documents\Xalal\xaltech\qrs\xal.jpg")
