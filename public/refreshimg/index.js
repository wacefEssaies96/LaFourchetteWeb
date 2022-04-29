
const uploadedImageDiv = document.getElementById("uploadedImage");
const fileUpload = document.getElementById("fileUpload");
fileUpload.addEventListener("change", getImage, false);

let myGreatImage = null;

function getImage() {
  console.log("images", this.files[0]);
  const imageToProcess = this.files[0];

  // display uploaded image
  let newImg = new Image(250, 250);
  newImg.src = imageToProcess;
  newImg.src = URL.createObjectURL(imageToProcess);
  newImg.id = "myGreatImage";
  uploadedImageDiv.style.border = "4px solid #FCB514";
  uploadedImageDiv.innerHTML = "";
  uploadedImageDiv.appendChild(newImg);
  myGreatImage = document.getElementById("myGreatImage");

}