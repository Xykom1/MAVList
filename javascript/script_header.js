
document.addEventListener('DOMContentLoaded', function () {
    const icon = document.getElementById('profile-icon');
    const menu = document.getElementById('dropdown-menu');
    

    icon.addEventListener('click', function (e) {
        e.stopPropagation(); // empêche le clic de remonter
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    });

    document.addEventListener('click', function () {
        menu.style.display = 'none';
    });
});

const body = document.querySelector('body');
document.getElementById("logout-btn").addEventListener("click", function (event) {
    event.preventDefault(); // Empêche le lien direct
    document.getElementById("logout-modal").style.display = "flex";
    body.style.overflow = "hidden";
});

document.getElementById("cancel-logout").addEventListener("click", function () {
    document.getElementById("logout-modal").style.display = "none";
    body.style.overflow = "auto";
});

document.getElementById("confirm-logout").addEventListener("click", function () {
    window.location.href = "deconnexion.php";
});


