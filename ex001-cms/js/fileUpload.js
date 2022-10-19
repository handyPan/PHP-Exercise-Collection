const imageFile = document.getElementById("image");
const postBannerPreview = document.getElementById("post-banner-preview");

imageFile.addEventListener("change", function() {
    const file = this.files[0];

    if (file) {
        const reader = new FileReader();

        reader.addEventListener("load", function() {
            postBannerPreview.setAttribute("src", this.result);
        });
        reader.readAsDataURL(file);
        document.getElementById("choose-file-message").innerHTML = "";
    } else {
        postBannerPreview.setAttribute("src", "");
        document.getElementById("choose-file-message").innerHTML = "*No banner will be used.";
    }
});

document.getElementById("reset-banner").addEventListener("click", function() {
    document.getElementById("post-banner-preview").setAttribute("src", "");
    if (document.getElementById("image-previous")) {
        document.getElementById("image-previous").value = "";
    }
    document.getElementById("choose-file-message").innerHTML = "*No banner will be used.";

    document.getElementById("image").value = "";
});
