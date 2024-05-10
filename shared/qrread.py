# this will read qr codes from the camera and then check the db and switch visitor state to 1
#

#******** no checking if valid visit

#most importantly for this code to run is to import OpenCV which we do in the below line
import cv2
import time
import mariadb # used for database connection

def update_state(id):
    # connect to the sql db
    # eventually move these settings to a shared config file that php uses as well
    try:
        con = mariadb.connect(
            user = "root",
            password = "",
            host = "localhost",
            database = "test"
        )
        print("connected")
    except mariadb.Error as e:
        print(f"Error connecting to MariaDB Platform: {e}")
        sys.exit(1)

    query = con.cursor() # setup the db query object

    # first we check if the qr code contains a pid and vid if not then we don't accept the qr code
    # **** need to look at searching for both elements if pid and vid not in right order then it'll fail. maybe dict them in qr code e.g. {pid=>123, vid=>123}
    if id.find("vid") >= 0 and id.find("pid") >= 0:
        pid = id[id.find("pid"):id.find("vid")]
        vid = id[id.find("vid")::]
        try:
            query.execute("SELECT valid FROM visitation WHERE vid='"+vid+"'") # check if the visitation is valid
            valid = query.fetchone() # assign the result to variable
            print(valid[0])
            if valid[0] == 1:
                try:
                    # find visitor state
                    query.execute("SELECT state FROM people WHERE pid='"+pid+"'") # find the state of the user
                    state = query.fetchone() # assign the result to variable
                    state = 1 if state[0] == 0 else 0 # if state is 0 then it needs to be 1 otherwise if it's 1 then it's 0

                    # update visitor state
                    query.execute("UPDATE people SET state="+str(state)+" WHERE pid='"+pid+"'")
                    con.commit() # commit changes to the database
                    print("state changed")
                except:
                    # if visitor record not found flag error *any sql error will cause this
                    print("visitor not found")
                    print(id.find("vid"))
            else:
                print("visitation not valid")
        except:
            print("visitation not found")
    else:
        print("error reading qr code")

####
# Camera qr reading part

# set up camera object which uses OpenCV
cap = cv2.VideoCapture(0)

# QR code detection method
detector = cv2.QRCodeDetector()
update_state("pid-42734-63043-40373-04432-84632 vid-59661-60895-62506-40275-86104")
# Infinite loop to scanning camera for data
while True:
    # Below is the method to get a image of the QR code
    _, img = cap.read()

    # Below is the method to read the QR code by detetecting the bounding box coords and decoding the hidden QR data
    data, bbox, _ = detector.detectAndDecode(img)

    #Below prints the found data to the below terminal (This we can easily expand on to capture the data to an Excel Sheet)
    #You can also add content to before the pass. Say the system reads red it'll activate a Red LED and the same for Green.
    if data:
        print("data found: ", data)
        update_state(data)
        time.sleep(2) # delay code after readin so we don't do multiple updates

    # Below will display the live camera feed to the Desktop on Raspberry Pi OS preview
    #cv2.imshow("code detector", img)

    #At any point if you want to stop the Code all you need to do is press 'q' on your keyboard
    if(cv2.waitKey(1) == ord("q")):
        break

# When the code is stopped the below closes all the applications/windows that the above has created
cap.release()
cv2.destroyAllWindows()
