
  // The width and height of the captured photo. We will set the
  // width to the value defined here, but the height will be
  // calculated based on the aspect ratio of the input stream.

  const width = 320; // We will scale the photo width to this
  let height = 0; // This will be computed based on the input stream

  // |streaming| indicates whether or not we're currently streaming
  // video from the camera. Obviously, we start at false.

  let streaming = false;

  // The various HTML elements we need to configure or control. These
  // will be set by the startup() function.

  let video = null;
  let canvas = null;
  let photo = null;
  let startbutton = null;
  let clearbutton = null;

  let img_string = null;

  function showViewLiveResultButton() {
    if (window.self !== window.top) {
      // Ensure that if our document is in a frame, we get the user
      // to first open it in its own tab or window. Otherwise, it
      // won't be able to request permission for camera access.
      document.querySelector(".contentarea").remove();
      const button = document.createElement("button");
      button.textContent = "View live result of the example code above";
      document.body.append(button);
      button.addEventListener('click', () => window.open(location.href));
      return true;
    }
    return false;
  }

  function startup() {
    if (showViewLiveResultButton()) { return; }
    video = document.getElementById('videoElement');
    canvas = document.getElementById('canvas');
    photo = document.getElementById('photo');
    startbutton = document.getElementById('startbutton');
    clearbutton = document.getElementById('clearbutton');
    base64_str = document.getElementById('base64_str');

    img_string = document.getElementById('img');

    //navigator.mediaDevices.getUserMedia({video: true, audio: false})
    navigator.mediaDevices.getUserMedia({audio: false,video: {mandatory: {minWidth: 600, maxWidth: 600, minHeight: 750, maxHeight: 750}}})
      .then((stream) => {
        video.srcObject = stream;
        video.play();
      })
      .catch((err) => {
        console.error(`An error occurred: ${err}`);
      });

    video.addEventListener('canplay', (ev) => {
      if (!streaming) {
        height = video.videoHeight / (video.videoWidth/width);

        // Firefox currently has a bug where the height can't be read from
        // the video, so we will make assumptions if this happens.

        if (isNaN(height)) {
          height = width / (4/3);
        }

        video.setAttribute('width', width);
        video.setAttribute('height', height);
        canvas.setAttribute('width', width);
        canvas.setAttribute('height', height);
        streaming = true;
      }
    }, false);

    startbutton.addEventListener('click', (ev) => {
      takepicture();
      ev.preventDefault();
    }, false);

    clearbutton.addEventListener('click', (ev) => {
      clearphoto();
      ev.preventDefault();
    }, false);

    clearphoto();
  }

  // Fill the photo with an indication that none has been
  // captured.

  function clearphoto() {
    const context = canvas.getContext('2d');
    context.fillStyle = "#AAA";
    context.fillRect(0, 0, canvas.width, canvas.height);

    const data = canvas.toDataURL('image/png');
    photo.setAttribute('src', data);
  }

  // Capture a photo by fetching the current contents of the video
  // and drawing it into a canvas, then converting that to a PNG
  // format data URL. By drawing it on an offscreen canvas and then
  // drawing that to the screen, we can change its size and/or apply
  // other changes before drawing it.

  function takepicture() {
    const context = canvas.getContext('2d');
    if (width && height) {
      //canvas.width = width;
      //canvas.height = height;
      canvas.width = width;
      canvas.height = height;
      context.drawImage(video, 0, 0, width, height);
      //context.drawImage(video, 0, 0, width, height);

      const data = canvas.toDataURL('image/png');
      photo.setAttribute('src', data);

      const org_img = photo.getAttribute('src');

      base64_str.setAttribute('value', data);

      //console.log(data);

      //img_string.value = data
    }
    else {
      clearphoto();
    }
  }


  // Set up our event listener to run the startup process
  // once loading is complete.
//  window.addEventListener('load', startup, false);

function stop()
{
  //navigator.mediaDevices.getUserMedia({video: false, audio: false});
  const video = document.getElementById('videoElement');
  //video.stop();
  //const video = document.querySelector('video');

// A video's MediaStream object is available through its srcObject attribute
  const mediaStream = video.srcObject;
  const tracks = mediaStream.getTracks();
  tracks[0].stop();
  //video.getTracks().forEach(track => track.stop())
}

// stop both mic and camera
function stopBothVideoAndAudio(stream) {
    stream.getTracks().forEach(function(track) {
        if (track.readyState == 'live') {
            track.stop();
        }
    });
}

function cam_test()
{
  console.log("testo");
  alert("testtto");
  var paragraph = document.getElementById("test")
  var text = document.createTextNode("This just got added");

  paragraph.appendChild(text);
}