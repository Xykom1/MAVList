document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function (event) {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');

            // Supprimer les icônes d'erreur existantes
            const existingIcons = document.querySelectorAll('.error-icon');
            existingIcons.forEach(icon => icon.remove());

            if (password.value !== confirmPassword.value) {
                password.classList.add('error');
                confirmPassword.classList.add('error');

                // Ajouter une icône d'erreur
                const icon = document.createElement('i');
                icon.className = 'fas fa-exclamation-circle error-icon';
                password.parentElement.appendChild(icon);

                const iconConfirm = document.createElement('i');
                iconConfirm.className = 'fas fa-exclamation-circle error-icon';
                confirmPassword.parentElement.appendChild(iconConfirm);

                event.preventDefault();
            } else {
                password.classList.remove('error');
                confirmPassword.classList.remove('error');
            }
        });
    }
});

window.addEventListener('DOMContentLoaded', () => {
    const message = document.getElementById('success-message');
    if (message) {
        setTimeout(() => {
            message.classList.add('fade-out');
            setTimeout(() => {
                message.remove();
            }, 1000); // attendre l'animation de disparition
        }, 3000); // message visible 3 secondes
    }
});