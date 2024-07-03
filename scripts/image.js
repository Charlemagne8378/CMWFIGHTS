function updateFilename() {
    var fileInput = document.getElementById('image');
    var filenameInput = document.getElementById('filename');
    var fileNameWithoutExtension = fileInput.files[0].name.replace(/\.[^/.]+$/, '');
    filenameInput.value = fileNameWithoutExtension;
}
