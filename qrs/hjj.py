from PIL import Image
import qrcode

# Your desired URL
url = "https://www.xalal.tech"

# Load your existing QR code image
qr_image = Image.open(r"C:\Users\Admin\OneDrive\Documents\Xalal\qrcode.jpg")

# Generate a new QR code with the desired URL
new_qr = qrcode.QRCode(
    version=1,
    error_correction=qrcode.constants.ERROR_CORRECT_M,
    box_size=10,
    border=4,
)
new_qr.add_data(url)
new_qr.make(fit=True)

# Create an image from the QR code
new_qr_image = new_qr.make_image(fill_color="black", back_color="white")

# Resize the new QR code to match the size of the custom QR code image
new_qr_image = new_qr_image.resize(qr_image.size)

# Overlay the new QR code onto the custom QR code image
merged_image = Image.alpha_composite(qr_image.convert("RGBA"), new_qr_image.convert("RGBA"))

# Save the modified image
merged_image.save(r"C:\Users\Admin\OneDrive\Documents\Xalal\xaltech\qrs\xal.png")
