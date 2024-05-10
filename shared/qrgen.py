import qrcode # used for qr generation
import os # used to clean up temp PNG files
import sys
import base64
from io import BytesIO

# this generates a qr image and encodes it with the first argument from the script call
# we will only have our unique id encoded in the qr code

def qr_gen(qr_name):
    #Creating an instance of qrcode
    qr = qrcode.QRCode(
            version=1,
            box_size=10,
            border=5)
    qr.add_data(qr_name) # adds data to the qr image
    qr.make(fit=True)
    img = qr.make_image(fill='black', back_color='white')

    buffered = BytesIO() # setting up memory pointer to convert image to base64
    img.save(buffered, format="PNG") # saving qr image to buffered
    img_str = base64.b64encode(buffered.getvalue()) # converting buffered into base64 byte
    img_str = img_str.decode("ascii") # decode byte to a string

    return img_str

args = sys.argv # list of arguments entered after script seperated by space

#print(qr_gen(args[1])) # first element is the script path print the string to the console for php retrieval
print(qr_gen(args[1]+" "+args[2]), end="") # we expect to have a visitor id and a visitation id
