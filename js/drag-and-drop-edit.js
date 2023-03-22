const dropZone1 = document.querySelector(".drop_edit .drop-zone");
const inputElement1 = document.querySelector(".drop_edit .drop-zone .upload_button");
const img1 = document.querySelector(".drop_edit .drop-zone .image_uploaded_add");
let p1 = document.querySelector(".drop_edit .drop-zone .drag-and-drop_p")

inputElement1.addEventListener('change', function (e) {
    const clickFile = this.files[0];
    if (clickFile) {
        img1.style = "display:block;";
        p1.style = 'display: none';
        const reader = new FileReader();
        reader.readAsDataURL(clickFile);
        reader.onloadend = function () {
            const result = reader.result;
            let src = this.result;
            img1.src = src;
            img1.alt = clickFile.name
        }
    }
})
dropZone1.addEventListener('click', () => inputElement1.click());
dropZone1.addEventListener('dragover', (e) => {
    e.preventDefault();
});
dropZone1.addEventListener('drop', (e) => {
    e.preventDefault();
    img1.style = "display:block;";
    let file = e.dataTransfer.files[0];

    //read img file
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function () {
        e.preventDefault()
        //disappear text
        p1.style = 'display: none';
        let src = this.result;
        img1.src = src;

        img1.alt = file.name
    }
});
