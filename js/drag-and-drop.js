const dropZone = document.querySelector('.drop-zone');
const inputElement = document.querySelector('.upload_button');
const img = document.querySelector('.image_uploaded_add');
let p = document.querySelector('.drag-and-drop_p')

inputElement.addEventListener('change', function (e) {
    const clickFile = this.files[0];
    if (clickFile) {
        img.style = "display:block;";
        p.style = 'display: none';
        const reader = new FileReader();
        reader.readAsDataURL(clickFile);
        reader.onloadend = function () {
            const result = reader.result;
            let src = this.result;
            img.src = src;
            img.alt = clickFile.name
        }
    }
})
dropZone.addEventListener('click', () => inputElement.click());
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
});
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    img.style = "display:block;";
    let file = e.dataTransfer.files[0];

    //read img file
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onloadend = function () {
        e.preventDefault()
        //disappear text
        p.style = 'display: none';
        let src = this.result;
        img.src = src;
        img.alt = file.name
    }
});
