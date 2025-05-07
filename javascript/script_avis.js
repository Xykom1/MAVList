const modal = document.getElementById("avisModal");
const btn = document.getElementById("openModalBtn");
const span = document.querySelector(".close");

btn.onclick = () => {
    modal.style.display = "block";
    body.style.overflow = "hidden";
};

span.onclick = () => {
    modal.style.display = "none";
    body.style.overflow = "auto";
};

window.onclick = (event) => {
    if (event.target == modal) {
        modal.style.display = "none";
        body.style.overflow = "auto";
    }
};