document.getElementById("open-suppr-modal").addEventListener("click", function (event) {
    event.preventDefault(); // Empêche le lien direct
    document.getElementById("suppr-modal").style.display = "flex";
    body.style.overflow = "hidden";
});

document.getElementById("cancel-suppr").addEventListener("click", function () {
    document.getElementById("suppr-modal").style.display = "none";
    body.style.overflow = "auto";
});

document.getElementById("confirm-suppr").addEventListener("click", function () {
    document.getElementById("suppr-form").submit();
});

window.addEventListener('DOMContentLoaded', () => {
    const message = document.getElementById('message');
    if (message) {
        setTimeout(() => {
            message.classList.add('fade-out');
            setTimeout(() => {
                message.remove();
            }, 1000); // attendre l'animation de disparition
        }, 8000); // message visible 3 secondes
    }
});