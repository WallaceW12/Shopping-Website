var fileobj;


function preview_add() {
    image_uploaded_add.src=URL.createObjectURL(event.target.files[0]);
}
function preview_edit() {
    image_uploaded_edit.src=URL.createObjectURL(event.target.files[0]);
}
function upload_file(e){
    e.preventDefault();
    fileobj = e.dataTransfer.files[0];
    ajax_file_upload(fileobj);
}
function ajax_file_upload(file_obj) {
    if(file_obj != undefined) {
        var form_data = new FormData();
        form_data.append('file', file_obj);
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "ajax.php", true);
        xhttp.onload = function(event) {
            oOutput = document.querySelector('.img-content');
            if (xhttp.status == 200) {
                oOutput.innerHTML = "<img src='"+ this.responseText +"' alt='The Image' />";
            } else {
                oOutput.innerHTML = "Error " + xhttp.status + " occurred when trying to upload your file.";
            }
        }

        xhttp.send(form_data);
    }
}
//drag-and-drop


