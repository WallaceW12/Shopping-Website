const all_textarea = document.querySelectorAll("textarea");
const textarea_pattern = '/^[\w\r\n\-\.\,\'\"\(\)\?\&\%\!\:\/\*\+\; ]+$/';

all_textarea.forEach((textarea) => {
    textarea.addEventListener("keyup", (e) => {
        const content = textarea.value;
        if (content.match(textarea_pattern))
            e.target.classList.remove("invalid");
        else
            e.target.classList.add("invalid");
    });
});