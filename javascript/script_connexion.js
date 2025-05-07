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