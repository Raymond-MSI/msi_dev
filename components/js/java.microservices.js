function format(number) {
  var decimalSeparator = ".";
  var thousandSeparator = ",";

  // make sure we have a string
  var result = String(number);

  // split the number in the integer and decimals, if any
  var parts = result.split(decimalSeparator);

  // if we don't have decimals, add .00
  if (!parts[1]) {
    parts[1] = "00";
  } else {
    resultdecimal = parts[1].substring(0, 2);
    parts[1] = resultdecimal;
  }

  // reverse the string (1719 becomes 9171)
  result = parts[0].split("").reverse().join("");

  // add thousand separator each 3 characters, except at the end of the string
  result = result.replace(/(\d{3}(?!$))/g, "$1" + thousandSeparator);

  // reverse back the integer and replace the original integer
  parts[0] = result.split("").reverse().join("");

  // recombine integer with decimals
  return parts.join(decimalSeparator);
}

function deformat(number) {
  // var number = document.getElementById('m_agreed_price').value;
  var decimalSeparator = ",";
  var thousandSeparator = ".";

  var result = String(number);
  var parts = result.split(decimalSeparator);
  if (!parts[1]) {
    parts[1] = "00";
  }

  var parts2 = parts[0].split(thousandSeparator);
  parts[0] = parts2.join("");

  return parts.join(".");
}

function dataContentExportExl(sourceData, filename = "") {
  var fourceFileSaveDataUrl;
  var dataFileType = "application/vnd.ms-excel";
  var tableSelect = document.getElementById(sourceData);
  var dataContentSource = tableSelect.outerHTML.replace(/ /g, "%20");

  // Specify file name
  filename = filename ? filename + ".xls" : "export_excel_data.xls";

  // Create download link element
  fourceFileSaveDataUrl = document.createElement("a");

  document.body.appendChild(fourceFileSaveDataUrl);

  if (navigator.msSaveOrOpenBlob) {
    var blob = new Blob(["\ufeff", dataContentSource], {
      type: dataFileType,
    });
    navigator.msSaveOrOpenBlob(blob, filename);
  } else {
    // Create a link to the file
    fourceFileSaveDataUrl.href =
      "data:" + dataFileType + ", " + dataContentSource;

    // Setting the file name
    fourceFileSaveDataUrl.download = filename;

    //triggering the function
    fourceFileSaveDataUrl.click();
  }
}

////////////////
// Get Cookie //
////////////////
function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(";");
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == " ") {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

////////////////
//Upload File //
////////////////
// common variables
var iBytesUploaded = 0;
var iBytesTotal = 0;
var iPreviousBytesLoaded = 0;
var iMaxFilesize = 52428800; // 50MB
var oTimer = 0;
var sResultFileSize = "";
var oFile = "";
var iCountFiles = 0;
function secondsToTime(secs) {
  // we will use this function to convert seconds in normal time format
  var hr = Math.floor(secs / 3600);
  var min = Math.floor((secs - hr * 3600) / 60);
  var sec = Math.floor(secs - hr * 3600 - min * 60);
  if (hr < 10) {
    hr = "0" + hr;
  }
  if (min < 10) {
    min = "0" + min;
  }
  if (sec < 10) {
    sec = "0" + sec;
  }
  if (hr) {
    hr = "00";
  }
  return hr + ":" + min + ":" + sec;
}
function bytesToSize(bytes) {
  var sizes = ["Bytes", "KB", "MB"];
  if (bytes == 0) return "n/a";
  var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
  return (bytes / Math.pow(1024, i)).toFixed(1) + " " + sizes[i];
}
function fileSelected() {
  // hide different warnings
  document.getElementById("upload_response").style.display = "none";
  document.getElementById("error").style.display = "none";
  document.getElementById("error2").style.display = "none";
  document.getElementById("abort").style.display = "none";
  document.getElementById("warnsize").style.display = "none";
  // get selected file element
  oFile = document.getElementById("image_file").files[0];
  // filter for image files
  var rFilter = /^(image\/bmp|image\/gif|image\/jpeg|image\/png|image\/tiff)$/i;
  if (!rFilter.test(oFile.type)) {
    document.getElementById("error").style.display = "block";
    return;
  }
  // little test for filesize
  if (oFile.size > iMaxFilesize) {
    document.getElementById("warnsize").style.display = "block";
    return;
  }
  // get preview element
  var oImage = document.getElementById("preview");
  // prepare HTML5 FileReader
  var oReader = new FileReader();
  oReader.onload = function (e) {
    // e.target.result contains the DataURL which we will use as a source of the image
    oImage.src = e.target.result;
    oImage.onload = function () {
      // binding onload event
      // we are going to display some custom image information here
      sResultFileSize = bytesToSize(oFile.size);
      document.getElementById("fileinfo").style.display = "block";
      document.getElementById("filename").innerHTML = "Name: " + oFile.name;
      document.getElementById("filesize").innerHTML =
        "Size: " + sResultFileSize;
      document.getElementById("filetype").innerHTML = "Type: " + oFile.type;
      document.getElementById("filedim").innerHTML =
        "Dimension: " + oImage.naturalWidth + " x " + oImage.naturalHeight;
    };
  };
  // read selected file as DataURL
  oReader.readAsDataURL(oFile);
}
function startUploading() {
  // cleanup all temp states
  iPreviousBytesLoaded = 0;
  document.getElementById("upload_response").style.display = "none";
  document.getElementById("error").style.display = "none";
  document.getElementById("error2").style.display = "none";
  document.getElementById("abort").style.display = "none";
  document.getElementById("warnsize").style.display = "none";
  document.getElementById("progress_percent").innerHTML = "";
  var oProgress = document.getElementById("progress");
  oProgress.style.display = "block";
  oProgress.style.width = "0px";
  // get form data for POSTing
  //var vFD = document.getElementById('upload_form').getFormData(); // for FF3
  var vFD = new FormData(document.getElementById("upload_form"));
  // create XMLHttpRequest object, adding few event listeners, and POSTing our data
  var oXHR = new XMLHttpRequest();
  oXHR.upload.addEventListener("progress", uploadProgress, false);
  oXHR.addEventListener("load", uploadFinish, false);
  oXHR.addEventListener("error", uploadError, false);
  oXHR.addEventListener("abort", uploadAbort, false);
  oXHR.open("POST", "components/modules/upload/upload.php");
  oXHR.send(vFD);
  // set inner timer
  oTimer = setInterval(doInnerUpdates, 300);
}
function startUploading2() {
  // cleanup all temp states
  iPreviousBytesLoaded = 0;
  document.getElementById("upload_response").style.display = "none";
  document.getElementById("error").style.display = "none";
  document.getElementById("error2").style.display = "none";
  document.getElementById("abort").style.display = "none";
  document.getElementById("warnsize").style.display = "none";
  document.getElementById("progress_percent").innerHTML = "";
  var oProgress = document.getElementById("progress");
  oProgress.style.display = "block";
  oProgress.style.width = "0px";
  // get form data for POSTing
  //var vFD = document.getElementById('upload_form').getFormData(); // for FF3
  var vFD = new FormData(document.getElementById("upload_form"));
  // create XMLHttpRequest object, adding few event listeners, and POSTing our data
  var oXHR = new XMLHttpRequest();
  oXHR.upload.addEventListener("progress", uploadProgress, false);
  oXHR.addEventListener("load", uploadFinish2, false);
  oXHR.addEventListener("error", uploadError, false);
  oXHR.addEventListener("abort", uploadAbort, false);
  oXHR.open("POST", "components/modules/upload/upload_2.php");
  oXHR.send(vFD);
  // set inner timer
  oTimer = setInterval(doInnerUpdates, 300);
}
function doInnerUpdates() {
  // we will use this function to display upload speed
  var iCB = iBytesUploaded;
  var iDiff = iCB - iPreviousBytesLoaded;
  // if nothing new loaded - exit
  if (iDiff == 0) return;
  iPreviousBytesLoaded = iCB;
  iDiff = iDiff * 2;
  var iBytesRem = iBytesTotal - iPreviousBytesLoaded;
  var secondsRemaining = iBytesRem / iDiff;
  // update speed info
  var iSpeed = iDiff.toString() + "B/s";
  if (iDiff > 1024 * 1024) {
    iSpeed =
      (Math.round((iDiff * 100) / (1024 * 1024)) / 100).toString() + "MB/s";
  } else if (iDiff > 1024) {
    iSpeed = (Math.round((iDiff * 100) / 1024) / 100).toString() + "KB/s";
  }
  document.getElementById("speed").innerHTML = iSpeed;
  document.getElementById("remaining").innerHTML =
    "| " + secondsToTime(secondsRemaining);
}
function uploadProgress(e) {
  // upload process in progress
  if (e.lengthComputable) {
    iBytesUploaded = e.loaded;
    iBytesTotal = e.total;
    var iPercentComplete = Math.round((e.loaded * 100) / e.total);
    var iBytesTransfered = bytesToSize(iBytesUploaded);
    document.getElementById("progress_percent").innerHTML =
      iPercentComplete.toString() + "%";
    document.getElementById("progress").style.width =
      (iPercentComplete * 4).toString() + "px";
    document.getElementById("b_transfered").innerHTML = iBytesTransfered;
    if (iPercentComplete == 100) {
      var oUploadResponse = document.getElementById("upload_response");
      oUploadResponse.innerHTML = "<h1>Please wait...processing</h1>";
      oUploadResponse.style.display = "block";
    }
  } else {
    document.getElementById("progress").innerHTML = "unable to compute";
  }
}
function uploadFinish(e) {
  // upload successfully finished
  var oUploadResponse = document.getElementById("upload_response");
  oUploadResponse.innerHTML = e.target.responseText;
  oUploadResponse.style.display = "block";
  document.getElementById("progress_percent").innerHTML = "100%";
  document.getElementById("progress").style.width = "400px";
  document.getElementById("filesize").innerHTML = sResultFileSize;
  document.getElementById("remaining").innerHTML = "| 00:00:00";
  document.getElementById("fileList").innerHTML +=
    '<div class="row mb-1"><input type="text" class="form-control form-control-sm" id="file[' +
    iCountFiles +
    ']" value="' +
    3 +
    getCookie("ProjectCode") +
    "_" +
    oFile.name +
    '" readonly></div>';
  iCountFiles++;
  clearInterval(oTimer);
}
function uploadFinish2(e) {
  // upload successfully finished
  var oUploadResponse = document.getElementById("upload_response");
  oUploadResponse.innerHTML = e.target.responseText;
  oUploadResponse.style.display = "block";
  document.getElementById("progress_percent").innerHTML = "100%";
  document.getElementById("progress").style.width = "400px";
  document.getElementById("filesize").innerHTML = sResultFileSize;
  document.getElementById("remaining").innerHTML = "| 00:00:00";
  document.getElementById("fileList").innerHTML +=
    '<div class="row mb-1"><input type="text" class="form-control form-control-sm" id="file[' +
    iCountFiles +
    ']" value="' +
    "[" +
    getCookie("Department") +
    "]" +
    "[" +
    getCookie("Posisi") +
    "]" +
    oFile.name +
    '" readonly></div>';
  iCountFiles++;
  clearInterval(oTimer);
}
function uploadError(e) {
  // upload error
  document.getElementById("error2").style.display = "block";
  clearInterval(oTimer);
}
function uploadAbort(e) {
  // upload abort
  document.getElementById("abort").style.display = "block";
  clearInterval(oTimer);
}
/////////////////////
// End Upload File //
/////////////////////
