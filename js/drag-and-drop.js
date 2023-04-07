
const dropZone = document.querySelector(".drop_add .drop-zone");
const inputElement = document.querySelector(".drop_add .drop-zone .upload_button");

const img = document.querySelector(".drop_add .drop-zone .image_uploaded_add");
let p = document.querySelector(".drop_add .drop-zone .drag-and-drop_p")

var path='';
//inputElement.value= img.src;



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
            path = clickFile.name;

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
    reader.onload = function () {
        e.preventDefault();

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);

        inputElement.files = dataTransfer.files;

        //disappear text
        p.style = 'display: none';
        let src = this.result;
        img.src = src;
        img.alt = file.name;
    }
});


/*

const forms = document.querySelectorAll(".form-fill-children");
forms.forEach((form) => {
    let dropZone;
    for (let i = 0; i < form.children.length; i++) {
        console.log(form.children.length);
        if (form.children[i].classList.contains("drop_add")) {
            dropZone = form.children[i];
            break;
        }
    }

    dropZone.addEventListener("dragover", () => {
        dropZone.classList.add("drag-over");
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove("drag-over");
    });

    const imageShow = dropZone.children[0].children[0];
    console.log(imageShow);

    const uploadButton = dropZone.children[0].children[2];
    console.log(uploadButton);
    form.addEventListener("reset", () => {
        dropZone.classList.remove("drop");
        imageShow.style.display = "none";
    });

    uploadButton.addEventListener("change", () => {
        dropZone.classList.add("drop");
        dropZone.classList.remove("drag-over");

        let files = uploadButton.files;

        let reader = new FileReader();
        reader.readAsDataURL(files[0]);
        reader.onload = (e) => {
            imageShow.src = e.target.result;
            imageShow.style.display = "block";
            imageShow.innerHTML = files[0].name;
        };
    });
});

*/